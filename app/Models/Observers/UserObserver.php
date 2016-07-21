<?php

namespace App\Models\Observers;

class UserObserver {

    public function deleting($model)
    {
        if ($model->tasks())
        {
            $model->tasks()->delete();
        }

        return true;
    }
}
