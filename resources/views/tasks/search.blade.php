@extends('layouts.default')

@section('page-title')
    Tasks
@endsection

@section('content')
    <div id="tasks"></div>
@endsection

@section('scripts')
@parent
{{ Html::script('js/tasks/search.min.js') }}

<script type="text/javascript">
$(document).ready(function($) {
    $('[name="Task[is_enabled]"]').on('change', function(e) {
        $(this).closest('form').submit();
    });
});
</script>
@endsection
