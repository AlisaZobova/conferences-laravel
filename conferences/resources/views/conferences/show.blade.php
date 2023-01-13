@extends('layouts.base')
@section('title')
    {{ $conference->title }}
@endsection
@section('content')
    <div id="fb-root"></div>
    <title id="show">Conference{{ $conference->title }}</title>
    <div class="container">
        <table class="table table-striped table-hover mt-2">
            <thead class="thead-dark">
            <th>Title</th>
            <th>Date</th>
            <th>Country</th>
            </thead>
            <tbody>
            <tr>
                <td>{{ $conference->title }}</td>
                <td>{{ $conference->conf_date }}</td>
                <td>{{ $conference->country }}</td>
            </tr>
            </tbody>
        </table>

        <div class="form-group">
            <input id="latitude" type="hidden" class="form-control" name="latitude"
                   value="{{ $conference->latitude }}">
        </div>
        <div class="form-group">
            <input id="longitude" type="hidden" class="form-control" name="longitude"
                   value="{{ $conference->longitude }}">
        </div>
        @if ($conference->latitude && $conference->longitude)
            <div class="form-group">
                <div id="googleMap" style="width:100%;height:400px;"></div>
            </div>
        @endif
        <div class="form-group">

            @if((Auth::user() && !Auth::user()->hasRole('Admin')) || !Auth::user())
                @if(Auth::user() && Auth::user()->isJoined($conference))

                    <a class="btn btn-outline-primary btn-group mr-2"
                       href="https://twitter.com/intent/tweet?text={{ Config::get('share.text') }}&url={{ route(Config::get('share.link'), $conference->id) }}">TW</a>
                    <a class="btn btn-outline-info btn-group mr-2"
                       href="http://www.facebook.com/share.php?u={{ route(Config::get('share.link'), $conference->id) }}">FB</a>

                    <div class="btn-group mr-2">
                        <form action="{{ route('users.cancel', $conference->id) }}" method="post">
                            @csrf
                            <button type="submit" class="btn btn-info">Cancel Participation</button>
                        </form>
                    </div>

                @else
                    <form class="btn-group mr-2" action="{{ route('users.join', $conference->id) }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-success">Join</button>
                    </form>
                @endif
            @endif
            <div class="btn-group mr-2">
                <a class="btn btn-primary"
                   href="{{ route('conferences.edit', $conference->id) }}">Update</a>
            </div>
            <div class="btn-group mr-2">
                <form action="{{ route('conferences.destroy', $conference->id) }}" method="post">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </div>
        </div>
    </div>

@endsection
