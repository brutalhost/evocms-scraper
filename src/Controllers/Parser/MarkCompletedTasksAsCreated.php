<?php

namespace EvolutionCMS\Scraper\Controllers\Parser;

use Illuminate\Http\Request;

class MarkCompletedTasksAsCreated
{
    public function __invoke(Request $request)
    {
        $output   = null;
        $exitCode = null;
        exec('cd "'.EVO_CORE_PATH.'" && php artisan scraper:mark-tasks',
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
