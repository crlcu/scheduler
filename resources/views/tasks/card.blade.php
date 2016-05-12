<div class="row">
    <div class="col s12">
        <div class="card indigo lighten-5">
            <div class="card-content">
                <span class="card-title">{{ $task['name'] }}</span>
                <p><em>{!! nl2br($task['command']) !!}</em></p>
                
                <p>Average time {{ $task['average'] }} seconds</p>
            </div>
            <div class="card-action">
                {!! Form::model($task, ['action' => ['TasksController@destroy', $task['id']], 'method' => 'delete', 'class' => 'left']) !!}
                    <button type="submit" class="btn-floating waves-effect waves-light red" title="Remove" onclick="return confirm('Confirm?')">
                        <i class="material-icons">delete</i>
                    </button>
                {!! Form::close() !!}

                <a href="{{ action('TasksController@edit', $task['id']) }}" class="btn-floating waves-effect waves-light blue left" title="Edit">
                    <i class="material-icons">edit</i>
                </a>

                <a href="{{ action('TasksController@run', $task['id']) }}" class="btn waves-effect waves-light green right" title="Run now">
                    <i class="material-icons left">launch</i> Run now
                </a>

                <div style="clear: both;"></div>
            </div>
        </div>
    </div>
</div>
