<div>
    Добрый день, на конференции {{ $conference->title }}
    ({{ env("FRONTEND_URL") }}/conferences/{{ $conference->id }})
    пользователь {{ $user->firstname . ' ' . $user->lastname }} оставил комментарий
    на ваш доклад {{ $report->topic }} ({{ env("FRONTEND_URL") }}/reports/{{ $report->id }}).
</div>
