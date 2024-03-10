<?php

namespace EvolutionCMS\Scraper\Console\Commands;

use EvolutionCMS\Scraper\Enums\TaskStatusEnum;
use EvolutionCMS\Scraper\Models\Task;
use EvolutionCMS\Scraper\Services\Parsers\AbstractParser;
use Illuminate\Console\Command;

class ProcessTask extends Command
{
    protected $signature   = 'scraper:process {task?} {--with-completed} {--with-unfinished} {--ignore-timestamp}';
    protected $description = 'Process task';

    public function handle()
    {
        $taskId     = $this->argument('task');
        $task       = Task::query();
        $statusList = [TaskStatusEnum::Created];
        if ($this->option('with-completed')) {
            $statusList[] = TaskStatusEnum::Completed;
        }
        if ($this->option('with-unfinished')) {
            $statusList[] = TaskStatusEnum::Unfinished;
        }

        // eloquent query
        if (is_null($taskId)) {
            $task = $task
                ->whereIn('status', $statusList)
                ->where('site_content_id', '=', null);
        } else {
            $task = $task->where('id', '=', $taskId);
        }

        if (!$this->option('ignore-timestamp')) {
            $task = $task->where('timestamp', '<', now());
        }

        $task = $task->orderBy('timestamp', 'asc')->limit(1)->get();

        if ($task->isEmpty()) {
            if (is_null($taskId)) {
                $this->info(trans_choice('scraper::global.tasks_not_found', 2));
            } else {
                $this->error(trans_choice('scraper::global.tasks_not_found', 1));
            }
            return 1;
        }

        $task           = $task[0];
        $hasSiteContent = !is_null($task->site_content_id);

        $parserClass = config('scraper.parsers_list')[$task->script];

        /* @var AbstractParser $parser */
        $parser = new $parserClass($task);

        if ($task->status == TaskStatusEnum::Unfinished) {
            $this->info(__('scraper::global.transaction_error'));
            return 1;
        } else {
            $this->info(__('scraper::global.task_completed'));
            return 0;
        }
    }
}
