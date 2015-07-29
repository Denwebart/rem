<footer class="container">
	<div class="row">
		<div class="col-xs-12">
			{{ $menuWidget->bottomMenu() }}
		</div>
		<div class="col-md-12">
            @if(isset($settings))
                {{ $settings['copyright']['value'] }}
            @endif
		</div>
	</div>
</footer>