<?php

namespace App\Models\Observers;

class GroupObserver {

    public function deleting($model)
    {
        if ($model->users())
        {
            $model->users()->delete();
        }

        return true;
    }
}
