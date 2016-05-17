<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Mail;
use Maknz\Slack\Client as Slack;

class TaskNotification extends Model
{
	use SoftDeletes;
	
    protected $fillable = ['type', 'status', 'to', 'slack_config_json'];
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
        $to = $this->to;
        $task = $this->task;

        if ($this->type == 'mail')
        {
            Mail::queue('emails.tasks.running', ['task' => $task], function ($mail) use ($to, $task)
            {
                $mail->to($to)
                    ->subject(sprintf('"%s" has started to run', $task->name));
            });
        }
        else
        {
            $slack = new Slack($to, $this->slack);
            $slack->send(sprintf('*%s* - has started to run', $task->name));   
        }
    }
}
