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
