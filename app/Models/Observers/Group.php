<?php

namespace App\Models\Observers;

class Group {

    public function deleting($model)
    {
        if ($model->users())
        {
            $model->users()->delete();
        }

        return true;
    }
}
