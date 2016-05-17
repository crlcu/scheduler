<div class="widget">
    <div class="header indigo lighten-5">
        <span class="title">History</span>
    </div>
    <div class="content">
        <table class="bordered highlight condensed">
            <thead>
                <tr>
                    <th width="45px"></th>
                    <th>Result</th>
                    <th>Started at</th>
                    <th>Done at</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($executions as $execution)
                    <tr class="{{ $execution['status'] == 'failed' ? 'red lighten-5' : '' }}">
                        <td class="center-align">{!! $execution['status_icon'] !!}</td>
                        <td>
                            @if (strlen($execution['result']) > 100)
                                <!-- Modal Trigger -->
                                <a class="modal-trigger" href="#result{{ $execution['id'] }}">{!! nl2br(str_limit($execution['result'], 100)) !!}</a>

                                <!-- Modal Structure -->
                                <div id="result{{ $execution['id'] }}" class="modal modal-fixed-footer">
                                    <div class="modal-content">
                                        <p>{!! nl2br($execution['result']) !!}</p>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="#!" class="modal-action modal-close waves-effect waves-green btn-flat ">Close</a>
                                    </div>
                                </div>
                            @else
                                {!! nl2br($execution['result']) !!}
                            @endif
                        </td>
                        <td>{{ $execution['created_at'] }}</td>
                        <td>
                            @if ($execution['is_running'])
                                -
                            @else
                                {{ $execution['updated_at'] }}
                            @endif
                        </td>
                        <td>
                            @if ($execution['is_running'])
                                -
                            @else
                                {{ $execution['duration_for_humans'] }}
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="footer indigo lighten-5">
        <div class="row">
            <div class="left">
                {!! $executions->links() !!}
            </div>
            <div class="right">
                <a href="{{ action('TasksController@clear', $task['id']) }}" class="btn waves-effect waves-light red" title="Run now" onclick="return confirm('Confirm?')">
                    <i class="material-icons left">delete</i> Clear all
                </a>
            </div>
        </div>
    </div>
</div>
