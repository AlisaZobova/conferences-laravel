@extends('layouts.base')
@section('content')

    <title>Conferences</title>

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
                                <form action="{{ route('conferences.destroy', $conference->id) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>

                    @endforeach

                    </tbody>
                </table>

            </div>
        </div>
    </div>

@endsection
