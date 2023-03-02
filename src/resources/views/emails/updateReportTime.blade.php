<div>
    Добрый день, на конференции {{ $conference->title }}
    ({{ env("FRONTEND_URL") }}/conferences/{{ $conference->id }})
    участник {{ $user->firstname . ' ' . $user->lastname }} с докладом
    на тему {{ $report->topic }} ({{ env("FRONTEND_URL") }}/reports/{{ $report->id }})
    перенес доклад на другое время. <br/><br/>
    Новое время доклада: {{ substr($report->start_time, 11, -3) }} - {{ substr($report->end_time, 11, -3) }}.
</div>
