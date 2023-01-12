@extends('layouts.base')
@section('content')
    <form action="{{ route('conferences.update', $conference->id) }}" method="post">
        @csrf
        @if ($errors->any())
            <div class="alert alert-danger mt-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @method('patch')
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" placeholder="Title" name="title"
                   value="{{ $conference->title }}">
        </div>
        <div class="form-group">
            <label for="conf_date">Conference date</label>
            <input type="datetime-local" class="form-control" id="conf_date" name="conf_date"
                   value="{{ $conference->conf_date }}">
        </div>
        <div class="form-group">
            <label for="latitude">Latitude</label>
            <input type="text" class="form-control latlng" id="latitude" placeholder="20.5" name="latitude"
                   value="{{ $conference->latitude }}">
        </div>
        <div class="form-group">
            <label for="longitude">Longitude</label>
            <input type="text" class="form-control latlng" id="longitude" placeholder="38" name="longitude"
                   value="{{ $conference->longitude }}">
        </div>
        <div class="form-group">
            <div id="googleMap" style="width:100%;height:400px;"></div>
        </div>
        <div class="form-group">
            <label for="country">Country</label>
            <select class="form-control" id="country" name="country">
                <option value="{{ $conference->country->id }}">{{ $conference->country }}</option>
                @foreach ($countries as $country)
                    @if($country != $conference->country)
                        <option value="{{ $country->id  }}">{{ $country }}</option>
                    @endif
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <a style="float: right; margin: 5px 0 5px 0;" class="btn btn-secondary" href="{{ route('conferences.show', $conference->id) }}">Back</a>
            <button type="submit" style="float: right; margin: 5px 5px 0 0;" class="btn btn-primary">Update</button>
        </div>
    </form>
@endsection
