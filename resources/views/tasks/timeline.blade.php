@extends('layouts.default')

@section('page-title')
    Timeline
@endsection

@section('content')
    <div class="widget">
        <div class="header indigo lighten-5">
            <span class="title">Timeline</span>
        </div>
        <div class="content">
            {!! Form::open(['method' => 'get']) !!}
                <div class="file-field input-field">
                    <button class="btn-floating waves-effect waves-light red lighten-1 right" type="submit"><i class="material-icons">search</i></button>
                    <div class="file-path-wrapper">
                        <div class="row">
                            <div class="col s6">
                                {!! Form::text('start', $start, ['id' => 'start', 'placeholder' => 'Start date', 'provide' => 'datetimepicker', 'autocomplete' => 'off']) !!}
                            </div>
                            <div class="col s6">
                                {!! Form::text('end', $end, ['id' => 'end', 'placeholder' => 'End date', 'provide' => 'datetimepicker', 'autocomplete' => 'off']) !!}
                            </div>
                        </div>
                    </div>
                </div>
            {!! Form::close() !!}

            <div id="chart">
                @if (!count($executions))
                    <p>Nothing to show.</p>
                @endif
            </div>
        </div>
        <div class="footer indigo lighten-5"></div>
    </div>
@endsection

@section('scripts')
@parent

{!! Html::script('//www.gstatic.com/charts/loader.js') !!}

<script type="text/javascript">
// Load the Visualization API and the timeline package.
google.charts.load('current', {'packages':['timeline']});

@if (count($executions))
google.charts.setOnLoadCallback(drawChart);
@endif

function drawChart() {
    var container = document.getElementById('chart');
    var chart = new google.visualization.Timeline(container);
    var dataTable = new google.visualization.DataTable();

    dataTable.addColumn({ type: 'string', id: 'Label'});
    dataTable.addColumn({ type: 'string', id: 'Name' });
    dataTable.addColumn({ type: 'date', id: 'Start' });
    dataTable.addColumn({ type: 'date', id: 'End' });

    dataTable.addRows([
        @foreach ($executions as $execution)
            ['{{ $execution['created_at'] }}', '{{ $execution['task']['name'] }}', new Date({{ $execution->created_at->format('Y, m, d, H, i, s') }}), new Date({{ $execution->updated_at->format('Y, m, d, H, i, s') }})],
        @endforeach
    ]);

    var rowHeight = 33;
    var chartHeight = dataTable.getNumberOfRows() * rowHeight;


    var options = {
        height: chartHeight + 50,
    };

    chart.draw(dataTable, options);
}
</script>
@endsection
