@if(count($models))
    Показано: <span class="on-page">{{ $models->count() }}</span>.
    Всего: <span class="total">{{ $models->getTotal() }}</span>.
@endif