<div class="col-lg-3 col-md-4 col-sm-12 col-xs-12">
    <div id="pages-tree">
        <ul class="nav nav-pages-tree nav-stacked">
            <li class="active">
                <a href="{{ URL::route('admin.pages.index') }}">
                    <i class="fa fa-clipboard"></i>
                    <span>Все страницы</span>
                </a>
                <span class="pull-right">
                    {{ Page::all()->count() }}
                </span>
            </li>
            @foreach(Page::whereParentId(0)->with('children')->get() as $page)
                <li class="{{ ($page->is_container) ? 'category' : 'page' }}{{ !$page->is_published ? ' not-published' : '' }}{{ isset($parentPage) ? (($parentPage->id == $page->id) ? ' curent' : '') : '' }}">
                    @if($page->is_container)
                        <a href="{{ URL::route('admin.pages.children', ['id' => $page->id]) }}" class="open" data-page-id="{{ $page->id }}">
                            <i class="fa fa-folder"></i>
                        </a>
                        <a href="{{ URL::route('admin.pages.children', ['id' => $page->id]) }}" class="title">
                            {{ $page->getTitle() }}
                            <span class="count">
                                        ({{ count($page->children) }})
                                    </span>
                        </a>
                    @else
                        <i class="fa fa-file-text-o"></i>
                        <span class="title">
                                    {{ $page->getTitle() }}
                                </span>
                    @endif
                    <a href="{{ URL::route('admin.pages.edit', ['id' => $page->id]) }}" class="edit pull-right">
                        <i class="fa fa-edit"></i>
                    </a>
                </li>
            @endforeach

            @section('script')
                @parent

                <script type="text/javascript">
                    // Открытие дерева
                    $("#pages-tree").on('click', '.open', function(e){
                        var evt = e ? e : window.event;
                        (evt.preventDefault) ? evt.preventDefault() : evt.returnValue = false;

                        var link = $(this);
                        if (link.parent().find('.children').length) {
                            var children = link.parent().find('.children');
                            if (children.is(':visible')) {
                                children.slideUp();
                                link.find('i').removeClass('fa-folder-open').addClass('fa-folder');
                            } else {
                                children.slideDown();
                                link.find('i').removeClass('fa-folder').addClass('fa-folder-open');
                            }
                        } else {
                            $.ajax({
                                url: '<?php echo URL::route('admin.pages.openTree') ?>',
                                dataType: "text json",
                                type: "POST",
                                data: {pageId: link.data('pageId')},
                                beforeSend: function(request) {
                                    return request.setRequestHeader('X-CSRF-Token', $("meta[name='csrf-token']").attr('content'));
                                },
                                success: function(response) {
                                    if(response.success) {
                                        if(response.childrenCount) {
                                            link.parent().append(response.children);
                                            link.parent().find('.children').slideDown();
                                            link.find('i').removeClass('fa-folder').addClass('fa-folder-open');
                                        } else {
                                            window.location.href = link.attr('href');
                                        }
                                    }
                                }
                            });
                        }
                    });
                </script>
            @stop
        </ul>
    </div>
</div>