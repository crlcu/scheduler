<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Venturecraft\Revisionable\RevisionableTrait;

use Mail;
use Nexmo;
use Maknz\Slack\Client as Slack;

class TaskNotification extends Model
{
	use SoftDeletes, RevisionableTrait;
	
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['task_id', 'type', 'status', 'with_result', 'to', 'slack_config_json'];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
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
     * Scopes
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', '=', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', '=', 'failed');
    }

    public function scopeInterrupted($query)
    {
        return $query->where('status', '=', 'interrupted');
    }

    public function scopeRunning($query)
    {
        return $query->where('status', '=', 'running');
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
                Mail::queue('emails.tasks.running', ['task' => $task, 'notification' => $this], function ($mail) use ($to, $task)
                {
                    $mail->to($to)
                        ->subject(sprintf('The task "%s" has started to run', $task->name));
                });

                break;
            case 'failed':
                Mail::queue('emails.tasks.execution', ['task' => $task, 'notification' => $this], function ($mail) use ($to, $task)
                {
                    $mail->to($to)
                        ->subject(sprintf('The execution of task "%s" has failed', $task->name));
                });

                break;
            case 'interrupted':
                Mail::queue('emails.tasks.execution', ['task' => $task, 'notification' => $this], function ($mail) use ($to, $task)
                {
                    $mail->to($to)
                        ->subject(sprintf('The execution of task "%s" was interrupted', $task->name));
                });

                break;
            case 'completed':
                Mail::queue('emails.tasks.execution', ['task' => $task, 'notification' => $this], function ($mail) use ($to, $task)
                {
                    $mail->to($to)
                        ->subject(sprintf('The execution of task "%s" is now completed', $task->name));
                });

                break;
        }
    }

    protected function sendViaSlack()
    {
        $slack = new Slack($this->to, $this->slack);
        $slack->sent($this->__message());
    }

    protected function sendViaSms()
    {
        $result = $this->with_result ? sprintf("\n```%s```", $this->task->last_run->result) : '';

        $response = Nexmo::message()->send([
            'from'  => config('nexmo.from'),
            'to'    => $this->to,
            'text'  => $this->__message(),
        ]);
    }

    private function __message()
    {
        $message = '';
        $result = $this->with_result ? sprintf("\n```%s```", $this->task->last_run->result) : '';

        switch ($this->status)
        {
            case 'running':
                $message = sprintf('The task *%s* has started to run.%s', $this->task->name, $result);

                break;
            case 'failed':
                $message = sprintf("The execution of task *%s* has failed.%s", $this->task->name, $result);

                break;
            case 'interrupted':
                $message = sprintf("The execution of task *%s* was interrupted.%s", $this->task->name, $result);

                break;
            case 'completed':
                $message = sprintf("The execution of task *%s* is now completed.%s", $this->task->name, $result);

                break;
        }

        return $message;
    }
}
