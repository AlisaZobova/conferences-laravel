<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Report;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Report $report)
    {
        return Comment::where('report_id', $report->id)->get();
    }

    public function store(Request $request)
    {
        $comment = Comment::create($request->all());
        return $comment->load('user', 'report');
    }

    public function show(Comment $comment)
    {
        return $comment->load('user', 'report');
    }

    public function update(Comment $comment, Request $request)
    {
        $comment->update($request->all());
        return $comment->load('user', 'report');
    }
}
