<h2 class="code-line" data-line-start=0 data-line-end=1><a id="___0"></a>Инструкция модуля</h2>
<h3 class="code-line" data-line-start=1 data-line-end=2><a id="__1"></a>Скрипты парсеров</h3>
<p class="has-line-data" data-line-start="2" data-line-end="3">Список классов находится в файле конфигурации <code>/project/core/custom/configs/scraper.php</code>.</p>
<p class="has-line-data" data-line-start="4" data-line-end="5">Вы можете как создавать классы с нуля, так и наследоваться от <code>EvolutionCMS\Scraper\Services\Parsers\AbstractParser</code>. Для лучшего понимания,
    изучите исходные файлы парсеров, поставляемых с модулем.</p>
<h3 class="code-line" data-line-start=5 data-line-end=6><a id="__5"></a>Обработка заданий</h3>
<p class="has-line-data" data-line-start="6" data-line-end="8"><code>php artisan scraper:process {task?}
        {--with-completed} { --with-unfinished} {--ignore-timestamp}</code><br>
    Начинает обработку задания со статусом Created.</p>
<p class="has-line-data" data-line-start="9" data-line-end="11"><code>php artisan scraper:process 13</code><br>
    Запустить обработку задания с идентификатором 13, даже если она уже обработана.</p>
<p class="has-line-data" data-line-start="12" data-line-end="14"><code>php artisan scraper:process
        --with-completed</code><br>
    Обработка задания со статусом Created или Completed.</p>
<p class="has-line-data" data-line-start="15" data-line-end="17"><code>php artisan scraper:process --with-unfinished
        --with-completed</code><br>
    Обработка задания со статусом Created, Unfinished или Completed.</p>
<p class="has-line-data" data-line-start="18" data-line-end="20"><code>php artisan scraper:process
        --ignore-timestamp</code><br>
    Игнорирует поле метки времени.</p>
<p class="has-line-data" data-line-start="21" data-line-end="23"><code>php artisan scraper:mark-tasks</code><br>
    Изменяет статус на Created для всех заданий Completed, к которым не прикреплены документы.</p>
