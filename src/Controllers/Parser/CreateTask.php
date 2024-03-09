<?php

namespace EvolutionCMS\Scraper\Controllers\Parser;

use Closure;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Models\SiteTemplate;
use EvolutionCMS\Scraper\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class CreateTask
{
    public function __invoke(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if (!$validator->fails()) {
            $validated = $validator->validated();

            $validated['publish'] = isset($validated['publish']) ? true : false;

            session()->put('scraper__script', $validated['script']);
            session()->put('scraper__publish', $validated['publish']);
            session()->put('scraper__parent_id', $validated['parent_id']);

            $urls = preg_split("/[\r\n\s+,]+/", $validated['urls'], -1, PREG_SPLIT_NO_EMPTY);
            $this->createTasksFromUrls($urls, $validated);

            session()->flash('success', trans_choice('scraper::global.tasks_created', count($urls)));
            return back();
        } else {
            return back()->withErrors($validator->errors())->withInput();
        }
    }

    private function rules()
    {
        return [
            'script'    => [
                'required', 'string',
                function (string $attribute, mixed $value, Closure $fail) {
                    if (is_null(config('scraper.parsers_list')[$value])) {
                        $fail(__('scraper::global.script_not_in_config'));
                    }
                },
            ],
            'urls'      => 'required|string',
            'parent_id' => [
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
            'template'  => [
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
            'publish'   => 'sometimes|in:on',
            'timestamp' => 'required|date_format:Y-m-d\TH:i',
        ];
    }

    private function createTasksFromUrls($urls, $validated)
    {
        foreach ($urls as $url) {
            $task                         = new Task();
            $task->timestamp              = Carbon::createFromFormat('Y-m-d\TH:i', $validated['timestamp']);
            $task->script                 = $validated['script'];
            $task->site_content_template  = $validated['template'];
            $task->site_content_parent_id = $validated['parent_id'];
            $task->site_content_published = $validated['publish'];
            $task->url                    = $url;

            $task->save();
        }
    }
}
