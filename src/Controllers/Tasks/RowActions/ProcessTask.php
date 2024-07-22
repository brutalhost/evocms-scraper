<?php

namespace EvolutionCMS\Scraper\Controllers\Tasks\RowActions;

use EvolutionCMS\Facades\Console;
use EvolutionCMS\Scraper\Models\Task;
use Illuminate\Http\Request;

class ProcessTask
{
    public function __invoke(Request $request, Task $task)
    {
        $exitCode = Console::call('scraper:process '.$task->id);

        if ($exitCode == 0) {
            session()->flash('success', Console::output());
        } else {
            session()->flash('error', Console::output());
        }

        return back();
    }
}
