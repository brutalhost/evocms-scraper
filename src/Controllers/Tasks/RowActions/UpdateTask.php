<?php

namespace EvolutionCMS\Scraper\Controllers\Tasks\RowActions;

use Closure;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Models\SiteTemplate;
use EvolutionCMS\Scraper\Enums\TaskStatusEnum;
use EvolutionCMS\Scraper\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class UpdateTask
{
    public function getForm(Task $task)
    {
        return $this->renderViewForm($task);
    }

    private function renderViewForm(Task $task)
    {
        $actionsClasses = config('scraper.parsers_list');
        $templates      = SiteTemplate::all();
        $statuses       = TaskStatusEnum::cases();

        return view('scraper::tasks.row-form',
            compact('templates', 'task', 'actionsClasses', 'statuses'));
    }

    public function save(Request $request, Task $task)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if (!$validator->fails()) {
            return $this->processFormData($validator, $task);
        } else {
            $request->flash();
            return $this->renderViewForm($task)->withErrors($validator->errors());
        }
    }

    private function rules()
    {
        return [
            'status'          => [
                'required', 'integer', 'gt:-1',
                function (string $attribute, mixed $value, Closure $fail) {
                    $exist = TaskStatusEnum::tryFrom($value);
                    if (is_null($exist)) {
                        $fail(__('scraper::global.status_not_exist'));
                    }
                },
            ],
            'script'          => [
                'required', 'string',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (is_null(config('scraper.parsers_list')[$value])) {
                        $fail(__('scraper::global.script_not_in_config'));
                    }
                },
            ],
            'url'             => ['required', 'string', 'url'],
            'site_content_id' => [
                'required', 'integer', 'gt:-1',
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($value != 0) {
                        $boolExistDocument = SiteContent::where('id', '=', $value)->exists();
                        if (!$boolExistDocument) {
                            $fail(__('scraper::global.document_not_exist', ['id' => $value]));
                        }
                    }
                },
            ],
            'template'        => [
                'required', 'integer', 'gt:0',
                function (string $attribute, mixed $value, Closure $fail) {
                    if ($value != 0) {
                        $boolExistTemplate = SiteTemplate::where('id', '=', $value)->exists();
                        if (!$boolExistTemplate) {
                            $fail(__('scraper::global.template_not_exist', ['id' => $value]));
                        }
                    }
                },
            ],
            'timestamp'       => ['required', 'date_format:Y-m-d\TH:i'],
        ];
    }

    private function processFormData($validator, Task $task)
    {
        $validated = $validator->validated();

        $task->status    = $validated['status'];
        $task->script    = $validated['script'];
        $task->url       = $validated['url'];
        $task->timestamp = Carbon::createFromFormat('Y-m-d\TH:i', $validated['timestamp']);

        $task->site_content_template  = $validated['template'];
        $task->site_content_parent_id = $validated['parent_id'];
        $task->site_content_published = $validated['publish'];
        $task->site_content_id        = $validated['site_content_id'] == 0 ? null : $validated['site_content_id'];

        $task->description = $task->status == TaskStatusEnum::Unfinished ? $task->description : null;
        $task->updated_at  = Carbon::now();
        $task->save();

        session()->flash('success', trans_choice('scraper::global.tasks_updated', 1));
        return response('', 200, ['HX-Refresh' => 'true']);
    }
}
