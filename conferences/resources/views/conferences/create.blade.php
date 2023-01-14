@extends('layouts.base')
@section('title')
    Create conference
@endsection
@section('content')
    <form action="{{ route('conferences.store') }}" method="post">
        @csrf
        @include('conferences.errors')
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" placeholder="Title" name="title">
        </div>
        <div class="form-group">
            <label for="conf_date">Conference date</label>
            <input type="date" class="form-control" id="conf_date" name="conf_date">
        </div>
        <div class="form-group">
            <label for="latitude">Latitude</label>
            <input type="text" class="form-control latlng" id="latitude" placeholder="20.5" name="latitude">
        </div>
        <div class="form-group">
            <label for="longitude">Longitude</label>
            <input type="text" class="form-control latlng" id="longitude" placeholder="38" name="longitude">
        </div>
        <div class="form-group">
            <div id="googleMap" style="width:100%;height:400px;"></div>
        </div>
        <div class="form-group">
            <label for="country">Country</label>
            <select class="form-control" id="country" name="country">
                @foreach ($countries as $country)
                    <option value="{{ $country->id  }}">{{ $country }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <a style="float: right; margin: 5px 0 5px 0;" class="btn btn-secondary" href="/conferences">Back</a>
            <button type="submit" style="float: right; margin: 5px 5px 0 0;" class="btn btn-primary">Save</button>
        </div>
    </form>
@endsection
