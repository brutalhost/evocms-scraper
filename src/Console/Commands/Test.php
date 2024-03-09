<?php

namespace EvolutionCMS\Scraper\Console\Commands;

use EvolutionCMS\Scraper\Enums\TaskStatusEnum;
use EvolutionCMS\Scraper\Models\Task;
use EvolutionCMS\Scraper\Services\Parsers\AbstractParser;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class Test extends Command
{
    protected $signature   = 'scraper:test {url}';
    protected $description = 'test';

    public function handle()
    {
        $url = $this->argument('url');
        $client = new Client();
        $response   = $client->get($url);
        $type = preg_replace('/.*charset=/', '', $response->getHeader('content-type')[0]);

        $original_body = (string)$response->getBody();
        $utf8_body = mb_convert_encoding($original_body, 'UTF-8', $type ?: 'UTF-8');

        $this->info($utf8_body);
    }
}
