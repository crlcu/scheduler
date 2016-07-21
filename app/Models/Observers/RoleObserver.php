<?php

namespace App\Models\Observers;

class RoleObserver {

    public function deleting($model)
    {
        if ($model->groups())
        {
            $model->groups()->detach();
        }

        return true;
    }
}
