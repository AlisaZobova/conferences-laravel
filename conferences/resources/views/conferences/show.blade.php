@extends('layouts.base')
@section('content')
    <title id="show">Conference{{ $conference->title }}</title>
    <div class="container">
        <form action="#">
            <div class="form-group">
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
            </div>
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

                <!--                Optional button for return to the main page-->

                {{--    <button type="button" style="float: right; margin-left: 5px;" class="btn btn-secondary"><a--}}
                {{--                style="text-decoration:none; color: white" href="/conferences">Back</a>--}}
                {{--    </button>--}}
                <div>
                    <a style="float: right; margin-left: 5px;" class="btn btn-primary"
                       href="{{ route('conferences.edit', $conference->id) }}">Update</a>
                </div>
                <div>
                    <form action="{{ route('conferences.destroy', $conference->id) }}" method="post">
                        @csrf
                        @method('delete')
                        <button type="submit" class="btn btn-danger"><a
                                style="float: right; margin-left: 5px;">Delete</a></button>
                    </form>
                </div>
            </div>
        </form>
    </div>

@endsection
