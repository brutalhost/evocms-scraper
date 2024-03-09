<?php

namespace EvolutionCMS\Scraper\Controllers\Tasks\RowActions;

use EvolutionCMS\Scraper\Models\Task;
use Illuminate\Http\Request;

class ProcessTask
{
    public function __invoke(Request $request, Task $task)
    {
        $output   = null;
        $exitCode = null;
        exec('cd "'.EVO_CORE_PATH.'" && php artisan scraper:process '.$task->id,
            $output,
            $exitCode);

        if ($exitCode == 0) {
            session()->flash('success', $output[0]);
        } else {
            session()->flash('error', implode(' ', $output));
        }

        return back();
    }
}
