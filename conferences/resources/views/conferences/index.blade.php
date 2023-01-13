@extends('layouts.base')
@section('title')
    Conferences
@endsection
@section('content')

    <div id="main" class="container">
        <div class="row">
            <div class="col-12">

                <a href="{{ route('conferences.create') }} " class="btn btn-success mt-3 mb-2"><i
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
                                @if((Auth::user() && !Auth::user()->hasRole('Admin')) || !Auth::user())
                                    @if(Auth::user() && Auth::user()->isJoined($conference))
                                        <form class="btn-group mr-2"
                                              action="{{ route('users.cancel', $conference->id) }}" method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-info">Cancel Participation</button>
                                        </form>

                                        <a class="btn btn-outline-primary btn-group mr-2"
                                           href="https://twitter.com/intent/tweet?text={{ Config::get('share.text') }}&url={{ route(Config::get('share.link'), $conference->id) }}">TW</a>
                                        <a class="btn btn-outline-info btn-group mr-2"
                                           href="http://www.facebook.com/share.php?u={{ route(Config::get('share.link'), $conference->id) }}">FB</a>

                                    @else
                                        <form class="btn-group mr-2" action="{{ route('users.join', $conference->id) }}"
                                              method="post">
                                            @csrf
                                            <button type="submit" class="btn btn-success">Join</button>
                                        </form>
                                    @endif
                                @endif
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
