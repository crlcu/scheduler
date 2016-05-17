<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Mail;
use Maknz\Slack\Client as Slack;

class TaskNotification extends Model
{
	use SoftDeletes;
	
    protected $fillable = ['task_id', 'type', 'status', 'to', 'slack_config_json'];
    protected $appends = ['is_via_slack', 'slack'];


    /**
     * Accessors & Mutators
     */
    public function getIsViaSlackAttribute($value)
    {
        return $this->type == 'slack';
    }

    public function getSlackAttribute($value)
    {
        return json_decode($this->slack_config_json ? : '[]', true);
    }


    /**
     * Relations
     */
    public function task()
    {
        return $this->belongsTo('App\Models\Task');
    }


    /**
     * Methods
     */
    public function send()
    {
        $method = sprintf('sendVia%s', ucfirst($this->type));

        return $this->$method();
    }

    protected function sendViaMail()
    {
        $to = $this->to;
        $task = $this->task;

        switch ($this->status)
        {
            case 'running':
                Mail::queue('emails.tasks.running', ['task' => $task], function ($mail) use ($to, $task)
                {
                    $mail->to($to)
                        ->subject(sprintf('The task "%s" has started to run', $task->name));
                });

                break;
            case 'failed':
                Mail::queue('emails.tasks.execution', ['task' => $task], function ($mail) use ($to, $task)
                {
                    $mail->to($to)
                        ->subject(sprintf('The execution of task "%s" has failed', $task->name));
                });

                break;
            case 'completed':
                Mail::queue('emails.tasks.execution', ['task' => $task], function ($mail) use ($to, $task)
                {
                    $mail->to($to)
                        ->subject(sprintf('The execution of task "%s" is now completed', $task->name));
                });

                break;
        }
    }

    protected function sendViaSlack()
    {
        $to = $this->to;
        $task = $this->task;

        $slack = new Slack($to, $this->slack);

        switch ($this->status)
        {
            case 'running':
                $slack->send(sprintf('The task *%s* has started to run', $task->name));

                break;
            case 'failed':
                $slack->send(sprintf('The execution of task *%s* has failed. ```%s```', $task->name, $task->last_run->result));

                break;
            case 'completed':
                $slack->send(sprintf('The execution of task *%s* is now completed. ```%s```', $task->name, $task->last_run->result));

                break;
        }
    }
}
