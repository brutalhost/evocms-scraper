<?php

namespace EvolutionCMS\Scraper\Services\Interfaces;

use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Scraper\Models\Task;

interface Parser
{
    /**
     * Run before HTML processed. Prepare data for parsing.
     * @return void
     */
    function beforeProcess();

    /**
     * HTML processing, replacing tags, removing unnecessary data, and so on.
     * @return void
     */
    function processHtml();

    /**
     * Run after HTML processed.
     * @return void
     */
    function afterProcess();

    /**
     * Receiving text data in any convenient way.
     * @return string
     */
    function getContent() : string;

    /**
     * Save data to database.
     * @return void
     */
    function saveData();
}
