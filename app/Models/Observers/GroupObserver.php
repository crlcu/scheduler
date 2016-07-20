<?php

namespace App\Models\Observers;

class GroupObserver {

    public function deleting($model)
    {
        if ($model->users())
        {
            return $model->users()->delete();
        }

        return true;
    }
}
