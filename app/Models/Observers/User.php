<?php

namespace App\Models\Observers;

class User {

    public function deleting($model)
    {
        if ($model->tasks())
        {
            $model->tasks()->delete();
        }

        return true;
    }
}
