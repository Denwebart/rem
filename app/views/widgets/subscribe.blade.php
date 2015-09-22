<?php
    $subscribeButtonTitle = isset($subscribeButtonTitle)
            ? $subscribeButtonTitle : 'Подписаться';
    $unsubscribeButtonTitle = isset($unsubscribeButtonTitle)
            ? $unsubscribeButtonTitle : 'Отменить подписку';

    $subscribeButtonTooltip = (Subscription::FIELD_PAGE_ID == $subscriptionField) ? 'Подписаться на вопрос' : 'Подписаться на журнал пользователя';
    $unsubscribeButtonTooltip = (Subscription::FIELD_PAGE_ID == $subscriptionField) ? 'Отписаться от вопроса' : 'Отписаться от журнала пользователя';
?>
<div class="row">
    <div class="col-md-12">
        <div id="subscription">
            @if(Auth::check())
                <div class="btn-group">
                    @if(!Auth::user()->subscribed($subscriptionObject->id, $subscriptionField))
                        <a href="javascript:void(0)" data-subscription-object-id="{{ $subscriptionObject->id }}" id="subscribe" class="btn btn-primary btn-sm" title="{{ $subscribeButtonTooltip }}" data-toggle="tooltip">
                            <span class="text-link">{{ $subscribeButtonTitle }}</span>
                        </a>
                    @else
                        <a href="javascript:void(0)" data-subscription-object-id="{{ $subscriptionObject->id }}" id="unsubscribe" class="btn btn-primary btn-sm" title="{{ $unsubscribeButtonTooltip }}" data-toggle="tooltip">
                            <span class="text-link">{{ $unsubscribeButtonTitle }}</span>
                        </a>
                    @endif
                    <a href="javascript:void(0)" class="btn btn-primary btn-sm subscribers" title="Количество подписчиков" data-toggle="tooltip">
                        {{ count($subscriptionObject->subscribers) }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

@if(Auth::check())
    @section('script')
        @parent

        <script type="text/javascript">
            $("#subscription").on('click', '#subscribe', function() {
                var $link = $(this);
                var subscriptionObjectId = $link.data('subscriptionObjectId');
                $.ajax({
                    url: "{{ URL::route('user.subscribe', ['login' => Auth::user()->getLoginForUrl()]) }}",
                    dataType: "text json",
                    type: "POST",
                    data: {subscriptionObjectId: subscriptionObjectId, subscriptionField: '<?php echo $subscriptionField ?>'},
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function(response) {
                        if(response.success){
                            $('#site-messages').prepend(response.message);
                            $link.find('.text-link').text('<?php echo $unsubscribeButtonTitle ?>');
                            $link.parent().find('.subscribers').text(response.subscribers);
                            $link.attr('id', 'unsubscribe');
                        } else {
                            $('#site-messages').prepend(response.message);
                        }
                    }
                });
            });

            $("#subscription").on('click', '#unsubscribe', function() {
                var $link = $(this);
                var subscriptionObjectId = $link.data('subscriptionObjectId');
                $.ajax({
                    url: "{{ URL::route('user.unsubscribe', ['login' => Auth::user()->getLoginForUrl()]) }}",
                    dataType: "text json",
                    type: "POST",
                    data: {subscriptionObjectId: subscriptionObjectId, subscriptionField: '<?php echo $subscriptionField ?>'},
                    beforeSend: function(request) {
                        return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                    },
                    success: function(response) {
                        if(response.success){
                            $('#site-messages').prepend(response.message);
                            $link.find('.text-link').text('<?php echo $subscribeButtonTitle ?>');
                            $link.parent().find('.subscribers').text(response.subscribers);
                            $link.attr('id', 'subscribe');
                        } else {
                            $('#site-messages').prepend(response.message);
                        }
                    }
                });
            });
        </script>
    @stop
@endif