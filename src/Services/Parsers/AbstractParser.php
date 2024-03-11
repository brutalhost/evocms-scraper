<?php

namespace EvolutionCMS\Scraper\Services\Parsers;

use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Scraper\Enums\TaskStatusEnum;
use EvolutionCMS\Scraper\Models\Task;
use EvolutionCMS\Scraper\Services\Interfaces\Parser;
use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Wa72\HtmlPageDom\HtmlPage;

abstract class AbstractParser implements Parser
{
    public Task        $task;
    public SiteContent $documentObject;
    public HtmlPage    $htmlPage;

    public function __construct(Task $scrapingHistory)
    {
        $this->task           = $scrapingHistory;
        $this->documentObject = isset($this->task->site_content_id)
            ? SiteContent::find($this->task->site_content_id)
            : new SiteContent();

        try {
            $this->htmlPage       = new HtmlPage($this->getContent());

            $this->beforeProcess();
            $this->processHtml();
            $this->afterProcess();

            $this->task->status = TaskStatusEnum::Completed;
            $this->task->description = null;
            $this->saveData();

        } catch (Exception $e) {
            $this->task->status = TaskStatusEnum::Unfinished;
            $this->task->description = $e->getMessage();
            $this->task->saveOrFail();
        }
    }

    function getContent(): string
    {
        $url = $this->task->url;
        $client = new Client();
        $response   = $client->get($url);
        $type = preg_replace('/.*charset=/', '', $response->getHeader('content-type')[0]);

        $original_body = $response->getBody()->getContents();
        $utf8_body = mb_convert_encoding($original_body, 'UTF-8', $type ?: 'UTF-8');

        return $utf8_body;
    }

    abstract function beforeProcess();

    abstract public function processHtml();

    abstract public function afterProcess();

    function saveData()
    {
        $this->documentObject->parent    = $this->task->site_content_parent_id;
        $this->documentObject->published = $this->task->site_content_published;
        $this->documentObject->template  = $this->task->site_content_template;

        $alias  = Str::slug($this->documentObject->pagetitle);
        $i =   1;
        while (true) {
            $uniqueAlias = $alias . ($i >   1 ? '-' . $i : '');
            $existingAlias = SiteContent::where('alias', $uniqueAlias)->exists();
            if (!$existingAlias) {
                $this->documentObject->alias = $uniqueAlias;
                break;
            }
            $i++;
        }

        $this->documentObject->saveOrFail();

        $this->task->site_content_id = $this->documentObject->id;
        $this->task->saveOrFail();
    }
}
