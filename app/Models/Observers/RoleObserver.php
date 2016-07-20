<?php

namespace App\Models\Observers;

class RoleObserver {

    public function deleting($model)
    {
        if ($model->groups())
        {
            return $model->groups()->detach();
        }

        return true;
    }
}
