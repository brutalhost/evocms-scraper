<?php

namespace EvolutionCMS\Scraper\Models;

use Carbon\Carbon;
use EvolutionCMS\Models\SiteContent;
use EvolutionCMS\Scraper\Enums\TaskStatusEnum;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'scraper_tasks';
    protected $casts = [
        'status' => TaskStatusEnum::class,
    ];
    protected $dates = [
        'timestamp'
    ];

    public function __construct()
    {
        $this->status = TaskStatusEnum::Created;
        parent::__construct();
    }


    public function setTimestampAttribute($value)
    {
        $this->attributes['timestamp'] = $value->addSeconds(-evo()->getConfig('server_offset_time'));
    }

    public function siteContent()
    {
        return $this->belongsTo(SiteContent::class);
    }
}
