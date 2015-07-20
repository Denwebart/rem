@if(count($user->publishedImages))
    <div id="carousel-users-images" class="carousel slide" data-ride="carousel">

        <!-- Карусель -->
        <div class="carousel-inner" role="listbox">

            @foreach($user->publishedImages as $key => $image)

                <div class="item{{ (0 == $key) ? ' active': '' }}">
                    {{ $image->getImage() }}
                    <div class="carousel-caption">
                        <h3>{{ $image->title }}</h3>
                        {{ $image->desctiption }}
                    </div>
                </div>

            @endforeach

        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#carousel-users-images" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#carousel-users-images" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>

        <!-- Controls -->
        <div style="text-align: center; margin-top: 10px">
            @foreach($user->publishedImages as $key => $image)
                <a href="javascript:void(0)" data-target="#carousel-users-images" data-slide-to="{{ $key }}" class="{{ (0 == $key) ? ' active': '' }}">
                    {{ $image->getImage(null, ['style' => 'width: 100px']) }}
                </a>
            @endforeach
        </div>
    </div>
@endif