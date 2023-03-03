<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Models\ZoomMeeting;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;

class ZoomMeetingController extends Controller
{
    public $client;
    public $jwt;
    public $headers;
    public $baseUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->jwt = $this->generateZoomToken();
        $this->headers = [
            'Authorization' => 'Bearer '.$this->jwt,
            'Content-Type'  => 'application/json',
            'Accept'        => 'application/json',
        ];
        $this->baseUrl = config('zoom.url');
    }

    public function generateZoomToken()
    {
        $key = config('zoom.key');
        $secret = config('zoom.secret');
        $payload = [
            'iss' => $key,
            'exp' => time() + 3600,
        ];

        return JWT::encode($payload, $secret, 'HS256');
    }

    public function toZoomTimeFormat(string $dateTime)
    {
        $date = new \DateTime($dateTime);
        return $date->format('Y-m-d\TH:i:s');
    }

    public function getDuration(Report $report) {
        $start = new \DateTime($report->start_time);
        $end = new \DateTime($report->end_time);
        $timeDiff = $end->diff($start);

        return $timeDiff->h * 60 + $timeDiff->i;
    }

    public function createZoomConference($response, $reportId) {
        $conferenceData = [];
        $conferenceData['id'] = $response['id'];
        $conferenceData['report_id'] = $reportId;
        $conferenceData['join_url'] = $response['join_url'];
        $conferenceData['start_url'] = $response['start_url'];

        ZoomMeeting::create($conferenceData);
    }

    public function store(Report $report)
    {
        $path = 'users/me/meetings';

        $body = [
            'headers' => $this->headers,
            'body'    => json_encode([
                'topic'      => $report->topic,
                'type'       => 2,
                'start_time' => $this->toZoomTimeFormat($report->start_time),
                'duration'   => $this->getDuration($report),
            ]),
        ];

        $response =  $this->client->post($this->baseUrl.$path, $body);
        $response = json_decode($response->getBody(), true);

        $this->createZoomConference($response, $report->id);
    }

    public function update($id, $report)
    {
        $path = 'meetings/'.$id;

        $body = [
            'headers' => $this->headers,
            'body'    => json_encode([
                'topic'      => $report->topic,
                'start_time' => $this->toZoomTimeFormat($report->start_time),
                'duration'   => $this->getDuration($report),
            ]),
        ];

        $this->client->patch($this->baseUrl.$path, $body);
    }

    public function delete($id)
    {
        $path = 'meetings/'.$id;
        $body = [
            'headers' => $this->headers,
            'body'    => json_encode([]),
        ];

        $this->client->delete($this->baseUrl.$path, $body);
    }

    public function getNextPage($nextPageToken='') {
        $path = 'users/me/meetings';

        $content = [
            'headers' => $this->headers,
            'query' => $nextPageToken ? ["next_page_token" => $nextPageToken] : ''
        ];

        $response =  $this->client->get($this->baseUrl.$path, $content);

        return json_decode($response->getBody(), true);
    }

    public function index() {

        if(cache('meetings')) {
            return cache('meetings');
        }

        $meetings = [];
        $data = $this->getNextPage();
        $meetings = array_merge($meetings, $data['meetings']);

        while ($data['next_page_token']) {
            $data = $this->getNextPage($data['next_page_token']);
            $meetings = array_merge($meetings, $data['meetings']);
        }

        foreach ($meetings as &$meeting) {
            $meeting['report_id'] = ZoomMeeting::find($meeting['id'])->report_id;
        }

        cache(['meetings' => $meetings]);

        return $meetings;
    }
}
