<?php

namespace EvolutionCMS\Scraper\Controllers\Tasks;

use EvolutionCMS\Facades\Console;
use EvolutionCMS\Scraper\Models\Task;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class MassActions extends Controller
{
    public Collection|null $tasks;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $validator   = Validator::make($request->only('mass_action'), [
                'mass_action'   => 'nullable',
                'mass_action.*' => 'integer',
            ]);
            $taskIds     = $validator->validate()['mass_action'];
            $this->tasks = is_null($taskIds) ? null : Task::whereIn('id', $taskIds)->get();

            if (is_null($this->tasks)) {
                return response('', 204);
            }

            return $next($request);
        });
    }

    public function delete(Request $request)
    {
        foreach ($this->tasks as $task) {
            $task->delete();
        }
        session()->flash('success', trans_choice('scraper::global.tasks_deleted', 2));

        return response('', 200, [
            'hx-refresh' => 'true',
        ]);
    }

    public function process(Request $request)
    {
        foreach ($this->tasks as $task) {
            Console::call('scraper:process '.$task->id);
        }

        session()->flash('success', trans_choice('scraper::global.tasks_processed', 2));

        return response('', 200, [
            'hx-refresh' => 'true',
        ]);
    }
}
