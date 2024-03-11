<h3 class="code-line" data-line-start=1 data-line-end=2><a id="__1"></a>Parser scripts</h3>
<p class="has-line-data" data-line-start="2" data-line-end="3">The list of classes is in the configuration file <code>/project/core/custom/configs/scraper. php</code>.
</p>
<p class="has-line-data" data-line-start="4" data-line-end="5">You can either create classes from scratch or
    inherit from <code>EvolutionCMS\Scraper\Services\Parsers\AbstractParser</code>. For better understanding,
    study the source files of the parsers supplied with the module.</p>
<h3 class="code-line" data-line-start=5 data-line-end=6><a id="__5"></a>Task processing</h3>
<p class="has-line-data" data-line-start="6" data-line-end="8"><code>php artisan scraper:process {task?}
        {--with-completed} { --with-unfinished} {--ignore-timestamp}</code><br>
    Processing a task with the Created status.</p>
<p class="has-line-data" data-line-start="9" data-line-end="11"><code>php artisan scraper:process 13</code><br>
    Forced processing of task ID 13.</p>
<p class="has-line-data" data-line-start="12" data-line-end="14"><code>php artisan scraper:process
        --with-completed</code><br>
    Processing a task with the status Created or Completed.</p>
<p class="has-line-data" data-line-start="15" data-line-end="17"><code>php artisan scraper:process --with-unfinished
        --with-completed</code><br>
    Process a task with the status Created, Unfinished, or Completed.</p>
<p class="has-line-data" data-line-start="18" data-line-end="20"><code>php artisan scraper:process
        --ignore-timestamp</code><br>
    Ignores the timestamp field (by default, tasks are processed whose timestamp does not indicate a date in the future).</p>
<p class="has-line-data" data-line-start="21" data-line-end="23"><code>php artisan scraper:mark-tasks</code><br>
    Changes the status of all Completed tasks without documents to Created.</p>
