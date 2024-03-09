<?php

namespace EvolutionCMS\Scraper\Console\Commands;

use EvolutionCMS\Scraper\Enums\TaskStatusEnum;
use EvolutionCMS\Scraper\Models\Task;
use EvolutionCMS\Scraper\Services\Parsers\AbstractParser;
use Illuminate\Console\Command;

class MarkCompletedTasksAsCreated extends Command
{
    protected $signature   = 'scraper:mark-tasks';
    protected $description = 'Mark completed tasks without documents as created';

    public function handle()
    {
        $tasks       = Task::query()->where('status', '=', TaskStatusEnum::Completed)->where('site_content_id', '=', null)->get();

        if ($tasks->isNotEmpty()) {
            foreach ($tasks as $task) {
                $task->status = TaskStatusEnum::Created;
                $task->save();
                $this->info($task->id . ' - ' . $task->url);
            }
            return 0;
        } else {
            $this->info(__('scraper::global.no_tasks'));
            return 1;
        }
    }
}
