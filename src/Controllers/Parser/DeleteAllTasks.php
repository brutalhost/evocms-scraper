<?php

namespace EvolutionCMS\Scraper\Controllers\Parser;

use EvolutionCMS\Scraper\Models\Task;
use Illuminate\Http\Request;

class DeleteAllTasks
{
    public function __invoke(Request $request)
    {
        $tasks = Task::take(300)->get();
        while ($tasks->isNotEmpty()) {
            foreach ($tasks as $task) {
                $task->delete();
            }
            $tasks = Task::take(300)->get();
        }
        session()->flash('success', trans_choice('scraper::global.tasks_deleted', 2));

        return back();
    }
}
