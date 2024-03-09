<?php

namespace EvolutionCMS\Scraper\Controllers;

use Closure;
use EvolutionCMS\Models\SiteTemplate;
use EvolutionCMS\Scraper\Enums\TaskStatusEnum;
use EvolutionCMS\Scraper\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IndexController
{
    public function __invoke(Request $request)
    {
        $actionsClasses = config('scraper.parsers_list');
        $templates      = SiteTemplate::all();
        $statuses = TaskStatusEnum::cases();

        $history = Task::query();
        if ($request->isMethod('post')) {
            $validator = Validator::make($request->only('status'), [
                'status' => [
                    'required', 'integer', 'gt:-2',
                    function (string $attribute, mixed $value, Closure $fail) {
                        $exist = TaskStatusEnum::tryFrom($value) || $value == -1;
                        if (is_null($exist)) {
                            $fail(__('scraper::global.status_not_exist'));
                        }
                    }
                ]
            ]);
            $validated = $validator->validated();
            if ($validated['status'] > -1) {
                view()->share('currentStatusFilter', $validated['status']);
                $history->where('status', '=', TaskStatusEnum::from($validated['status']));
            }
        }

        $page    = $request->get('page', 1);
        $history = $history->orderByDesc('id')->paginate(config('scraper.tasks_per_page'), ['*'], 'page', $page);
        if ($history->isEmpty() && $page > 1) {
            return redirect()->route('scraper::index', ['page' => $history->lastPage()]);
        }

        if ($request->hasHeader('hx-request')) {
            return view('scraper::tasks.tasks', compact('history', 'statuses'));
        } else {
            return view('scraper::index', compact('templates', 'history', 'actionsClasses', 'statuses'));
        }
    }
}
