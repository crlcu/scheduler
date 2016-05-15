<div class="widget">
    <div class="header indigo lighten-5">
        <span class="title">{{ $task['name'] }}</span>

        <div class="right">
            Average time {{ $task['average'] }} seconds
        </div>
    </div>
    <div class="content">
        <em>{!! nl2br($task['command']) !!}</em>

        <div id="chart"></div>
    </div>
    <div class="footer indigo lighten-5">
        <div class="row">
            <div class="left">
                {!! Form::model($task, ['action' => ['TasksController@destroy', $task['id']], 'method' => 'delete', 'class' => 'delete']) !!}
                    <button type="submit" class="btn-floating waves-effect waves-light red" title="Remove" onclick="return confirm('Confirm?')">
                        <i class="material-icons">delete</i>
                    </button>
                {!! Form::close() !!}

                <a href="{{ action('TasksController@edit', $task['id']) }}" class="btn-floating waves-effect waves-light blue" title="Edit">
                    <i class="material-icons">edit</i>
                </a>
            </div>
            <div class="right">
                <a href="{{ action('TasksController@run', $task['id']) }}" class="btn waves-effect waves-light green" title="Run now">
                    <i class="material-icons left">launch</i> Run now
                </a>
            </div>
        </div>
    </div>
</div>

@section('scripts')
@parent

{!! Html::script('//www.gstatic.com/charts/loader.js') !!}

<script type="text/javascript">
// Load the Visualization API and the corechart package.
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);
function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Started at', 'Seconds'],
        @foreach ($executions as $execution)
            ['{{ $execution['created_at'] }}', {{ $execution['duration'] }}],
        @endforeach
    ]);

    var options = {
        chartArea: {
            top: 10,
            right: 0,
            bottom: 30,
            left: 30,
        },
        vAxis: {minValue: 0},
        legend: 'none',
        pointSize: 5,
        pointShape: { type: 'circle', rotation: 180 }
    };

    var chart = new google.visualization.AreaChart(document.getElementById('chart'));
    chart.draw(data, options);
}
</script>
@endsection
