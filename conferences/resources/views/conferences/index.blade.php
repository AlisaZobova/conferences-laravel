@extends('layouts.base')
@section('title')
    Conferences
@endsection
@section('content')
    @if(auth()->check())
        <div class="container">
            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" style="float: right;" class="btn btn-outline-warning mt-2 mb-2">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    @else
        <div class="container">
            <a href="{{ route('login') }}" style="float: right;" class="btn btn-outline-warning mt-2 mb-2">
                {{ __('Log In') }}</a>
        </div>
    @endif

    <div id="main" class="container">
        <div class="row">
            <div class="col-12">

                <a href="{{ route('conferences.create') }} " class="btn btn-success mt-2 mb-2" style="float: right;"><i
                        class="fa fa-plus"></i> Add new conference</a>

                <table class="table table-striped table-hover mt-2">
                    <thead class="thead-dark">
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($conferences as $conference)

                        <tr onclick="window.location.href='{{ route('conferences.show', $conference->id) }}'">
                            <td>{{ $conference->title }}</td>
                            <td>{{ $conference->conf_date }}</td>
                            <td>
                                <div class="btn-group mr-2">
                                    <form action="{{ route('conferences.show', $conference->id) }}" method="get">
                                        <button type="submit" class="btn btn-primary">Details</button>
                                    </form>
                                </div>
                                <form class="btn-group mr-2"
                                      action="{{ route('conferences.destroy', $conference->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                                @include('conferences.join')
                            </td>
                        </tr>

                    @endforeach

                    </tbody>
                </table>

            </div>
        </div>
        {{ $conferences->links("pagination::bootstrap-4") }}
    </div>

@endsection
