<div>
    Добрый день, на конференцию {{ $conference->title }} ({{ env("FRONTEND_URL") }}/conferences/{{ $conference->id }})
    присоединился новый участник {{ $user->firstname . ' ' . $user->lastname }} с докладом на тему {{ $report->topic }}
    ({{ env("FRONTEND_URL") }}/reports/{{ $report->id }}) <br/><br/>
    Время доклада: {{ substr($report->start_time, 11, -3) }} - {{ substr($report->end_time, 11, -3) }}.
</div>
