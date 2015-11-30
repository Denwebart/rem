<div id="social-buttons-widget" class="sidebar-widget margin-bottom-10">
    @if(isset($settings['socialButtonsTitle']))
        <span class="title-h5 margin-bottom-5 display-inline-block">
            {{ $settings['socialButtonsTitle']['value'] }}
        </span>
    @endif
    <script type="text/javascript" src="//yastatic.net/share/share.js" charset="utf-8"></script>
    <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareQuickServices="vkontakte,facebook,twitter,odnoklassniki,moimir,moikrug,gplus" data-yashareTheme="counter"></div>
</div>