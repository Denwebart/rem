{{ HTML::image($imageUrl, '', ['class' => 'img-responsive' . $class]); }}
@if($isDeleted)
    <a href="javascript:void(0)" id="delete-temp-image">
        <i class="material-icons">delete</i>
    </a>
@endif