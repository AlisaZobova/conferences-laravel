<?php

namespace App\Http\Controllers;

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
        $this->baseUrl = env('ZOOM_API_URL', '');
    }

    public function generateZoomToken()
    {
        $key = env('ZOOM_API_KEY', '');
        $secret = env('ZOOM_API_SECRET', '');
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

    public function getDuration($data) {
        $start = new \DateTime($data['start_time']);
        $end = new \DateTime($data['end_time']);
        $timeDiff = $end->diff($start);

        return $timeDiff->h * 60 + $timeDiff->i;
    }

    public function store($data)
    {
        $path = 'users/me/meetings';

        $body = [
            'headers' => $this->headers,
            'body'    => json_encode([
                'topic'      => $data['topic'],
                'type'       => 2,
                'start_time' => $this->toZoomTimeFormat($data['start_time']),
                'duration'   => $this->getDuration($data),
            ]),
        ];

        $response =  $this->client->post($this->baseUrl.$path, $body);
        $response = json_decode($response->getBody(), true);

        $data['join_url'] = $response['join_url'];
        $data['start_url'] = $response['start_url'];

        return $data;
    }

}
