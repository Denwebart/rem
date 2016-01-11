<div id="social-buttons-widget" class="sidebar-widget margin-bottom-10">
    @if(isset($settings['socialButtonsTitle']))
        <span class="title-h5 margin-bottom-5 display-inline-block">
            {{ $settings['socialButtonsTitle']['value'] }}
        </span>
    @endif
        <script type="text/javascript" src="//yastatic.net/es5-shims/0.0.2/es5-shims.min.js" charset="utf-8"></script>
        <script type="text/javascript" src="//yastatic.net/share2/share.js" charset="utf-8"></script>
        <div class="ya-share2" data-services="vkontakte,facebook,odnoklassniki,moimir,gplus,twitter,lj" data-counter=""></div>
</div>