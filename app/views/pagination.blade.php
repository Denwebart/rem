<?php
$presenter = new Illuminate\Pagination\BootstrapPresenter($paginator);
?>

<?php if ($paginator->getLastPage() > 1): ?>
<ul class="pagination">
    <?php echo $presenter->render(); ?>
</ul>
<?php endif; ?>

@section('script')
    @parent

    <script type="text/javascript">
        $(".pagination li").first().tooltip({
            title: 'Предыдущая'
        });
        $(".pagination li").last().tooltip({
            title: 'Следующая'
        });
        $(".pagination li").first().next().tooltip({
            title: 'Первая'
        });
        $(".pagination li").last().prev().tooltip({
            title: 'Последняя'
        });
    </script>
@stop