@if(count($models))
    Показано: <span>{{ $models->count() }}</span>.
    Всего: <span>{{ $models->getTotal() }}</span>.
@else
    Показано: 0.
    Всего: 0.
@endif