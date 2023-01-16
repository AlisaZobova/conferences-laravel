@extends('layouts.base')
@section('title')
    Edit {{ $conference->title }}
@endsection
@section('content')
    <form style="display: inline;" action="{{ route('conferences.update', $conference->id) }}" method="post">
        @csrf
        @include('conferences.errors')
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
        <div style="display: inline;" class="form-group">
            <a class="btn-group mr-2 btn btn-secondary" href="{{ route('conferences.show', $conference->id) }}">Back</a>
            <button type="submit" class="btn-group mr-2 btn btn-primary">Save</button>
        </div>
    </form>
    <form style="display: inline;" class="btn-group mr-2" action="{{ route('conferences.destroy', $conference->id) }}"
          method="post">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn-group mr-2 btn btn-danger">Delete</button>
    </form>
@endsection