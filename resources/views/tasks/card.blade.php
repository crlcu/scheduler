<div class="widget">
    <div class="header indigo lighten-5">
        <span class="title">{{ $task['name'] }}</span>

        <div class="right">
            Average time {{ $task['average_for_humans'] }}
        </div>
    </div>
    <div class="content">
        <pre class="prettyprint">{{ $task['command'] }}</pre>

        @if (count($completed) || count($failed))
            <div id="chart"></div>
            <div class="right-align"><em><small>This chart includes data since {{ $start }}.</small></em></div>
        @endif

        <p class="hide">{{ $task['ping_url'] }}</p>
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

                @if (Auth::user()->hasRole('feature-notifications'))
                     | <a href="{{ action('TasksController@notifications', $task['id']) }}" class="btn-floating waves-effect waves-light amber lighten-3" title="Notifications">
                        <i class="material-icons">notifications</i>
                    </a>
                @endif
            </div>
            <div class="right">
                <a href="{{ action('TasksController@run', $task['id']) }}" class="btn waves-effect waves-light green" title="Run now" onclick="return confirm('Confirm?')">
                    <i class="material-icons left">launch</i> Run now
                </a>
            </div>
        </div>
    </div>
</div>

@section('scripts')
@parent

{!! Html::script('//cdn.rawgit.com/google/code-prettify/master/loader/run_prettify.js') !!}

@if (count($completed) || count($failed))
{!! Html::script('//www.gstatic.com/charts/loader.js') !!}

<script type="text/javascript">
// Load the Visualization API and the corechart package.
google.charts.load('current', {'packages':['corechart']});

google.charts.setOnLoadCallback(drawChart);


function drawChart() {
    var completed = new google.visualization.DataTable();
        completed.addColumn('string', 'Started at');
        completed.addColumn('number', 'Seconds');

        completed.addRows([
            @foreach ($completed as $execution)
                ['{{ $execution->created_at->format("jS M H:i:s") }}', {{ $execution['chart_y'] }}],
            @endforeach
        ]);

    var failed = new google.visualization.DataTable();
        failed.addColumn('string', 'Started at');
        failed.addColumn('number', 'Seconds');

        failed.addRows([
            @foreach ($failed as $execution)
                ['{{ $execution->created_at->format("jS M H:i:s") }}', {{ $execution['duration'] }}],
            @endforeach
        ]);

    var options = {
        interpolateNulls: true,
        chartArea: {
            top: 10,
            right: 20,
            bottom: 50,
            left: 50,
        },
        vAxis: {
            minValue: 0
        },
        colors:['#4CAF50', '#F44336'],
        legend: 'none',
        pointSize: 5,
        pointShape: {
            type: 'circle',
            rotation: 180
        }
    };

    var data = google.visualization.data.join(completed, failed, 'full', [[0, 0]], [1], [1]);

    var chart = new google.visualization.AreaChart(document.getElementById('chart'));
    chart.draw(data, options);
}
</script>
@endif

@endsection
