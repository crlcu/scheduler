<div class="row">
    <div class="col s12">
        <div class="card indigo lighten-5">
            <div class="card-content">
                <span class="card-title">{{ $task['name'] }}</span>
                <p><em>{!! nl2br($task['command']) !!}</em></p>
            </div>
            <div class="card-action">
                <a href="{{ action('TasksController@run', $task['id']) }}" class="btn waves-effect waves-light green" title="Run now">
                    <i class="material-icons left">launch</i> Run now
                </a>
                |
                <a href="{{ action('TasksController@edit', $task['id']) }}" class="btn-floating waves-effect waves-light blue" title="Edit">
                    <i class="material-icons">edit</i>
                </a>
                <a href="{{ action('TasksController@edit', $task['id']) }}" class="btn-floating waves-effect waves-light red" title="Remove">
                    <i class="material-icons">delete</i>
                </a>

                
                <a href="#" class="right">Average time {{ $task['average'] }} seconds</a>
            </div>
        </div>
    </div>
</div>
