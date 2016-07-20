<?php

namespace App\Models\Observers;

class UserObserver {

    public function deleting($model)
    {
        if ($model->tasks())
        {
            return $model->tasks()->delete();
        }

        return true;
    }
}
