<?php $data = isset($data) ? $data : (Request::all() ? Request::all() : []); ?>
{{ $models->appends($data)->links() }}