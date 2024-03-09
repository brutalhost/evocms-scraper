<?php

namespace EvolutionCMS\Scraper\Enums;

enum TaskStatusEnum:int {
    case Created = 0;
    case Completed = 1;
    case Unfinished = 2;
}
