<div>
    Добрый день, на конференцию {{ $conference->title }}
    ({{ env("FRONTEND_URL") }}/conferences/{{ $conference->id }})
    присоединился новый слушатель {{ $user->firstname . ' ' . $user->lastname }}
</div>
