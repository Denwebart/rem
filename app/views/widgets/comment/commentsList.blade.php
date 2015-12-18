<div class="count">
    Показано комментариев: <span>{{ $comments->count() }}</span>.
    Всего: <span>{{ $comments->getTotal() }}</span>.
</div>
<div class="comments list">
    @foreach($comments as $comment)
        <!-- Comment -->
        @include('widgets.comment.comment1Level', ['page' => $page, 'comment' => $comment, 'isBannedIp' => $isBannedIp])
    @endforeach
</div>
{{ $comments->links() }}