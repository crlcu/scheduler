<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TasksController extends Controller
{
    public function search(Request $request)
    {
        return view('tasks.search', compact('tasks', 'q'));
    }
}
