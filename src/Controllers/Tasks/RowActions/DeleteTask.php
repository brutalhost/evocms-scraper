<?php

namespace EvolutionCMS\Scraper\Controllers\Tasks\RowActions;

use EvolutionCMS\Scraper\Models\Task;
use Illuminate\Http\Request;

class DeleteTask
{
    public function __invoke(Request $request, Task $task)
    {
        $task->deleteOrFail();
        session()->flash('success', trans_choice('scraper::global.tasks_deleted', 1));

        return back();
    }
}
