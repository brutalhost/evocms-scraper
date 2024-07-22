<?php

namespace EvolutionCMS\Scraper\Controllers\Parser;

use EvolutionCMS\Facades\Console;
use Illuminate\Http\Request;

class MarkCompletedTasksAsCreated
{
    public function __invoke(Request $request)
    {
        $exitCode = Console::call('scraper:mark-tasks');

        if ($exitCode == 0) {
            session()->flash('success', Console::output());
        } else {
            session()->flash('error', Console::output());
        }

        return back();
    }
}
