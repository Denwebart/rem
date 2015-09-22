<h3>
    Лучшие ответы
    <span class="count-best-comments">
        ({{ count($page->bestComments) }})
    </span>
</h3>

@foreach($bestComments as $comment)
    <!-- Comment -->
    @include('widgets.comment.comment1Level', ['isBannedIp' => $isBannedIp])
@endforeach