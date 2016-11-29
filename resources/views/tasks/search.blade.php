@extends('layouts.default')

@section('page-title')
    Tasks
@endsection

@section('content')
    content
@endsection

@section('scripts')
@parent
<script type="text/javascript">
$(document).ready(function($) {
    $('[name="Task[is_enabled]"]').on('change', function(e) {
        $(this).closest('form').submit();
    });
});
</script>
@endsection
