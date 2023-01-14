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
            @include('conferences.join')
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
