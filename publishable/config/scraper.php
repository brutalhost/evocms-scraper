<?php

use EvolutionCMS\Scraper\Services\Parsers\FourPdaParser;
use EvolutionCMS\Scraper\Services\Parsers\SteamParser;

return [
    'parsers_list' => [
        'SteamCommunity' => SteamParser::class,
        '4PDA' => FourPdaParser::class,
    ],
    'tasks_per_page' => 10,
];
