# Scraper - парсер статических сайтов для Evolution CMS
Парсер статических сайтов для Evolution CMS. Модуль написан на Laravel компонентах, совместим только с Evo 3. Для работы с DOM деревом используется библиотека [wasinger/htmlpagedom](https://github.com/wasinger/htmlpagedom "wasinger/htmlpagedom").

![Форма парсера](https://community.evocms.ru/assets/images/uploads/2045/IXNoKte8fgz8FHK.png "Форма парсера")
![Список задач](https://community.evocms.ru/assets/images/uploads/2045/iklIbRB9CPEOkEq.png "Список задач")

Как работает:
- Создаются задания в админ-панели.
- Список PHP классов парсеров прописывается в config файле.
- Обработка заданий производится через админ-панель, либо с помощью artisan команд.

### Установка
```cd core```
```php -d="memory_limit=-1" artisan package:installrequire brutalhost/evocms-scraper "*"```
```php artisan vendor:publish --provider="EvolutionCMS\Scraper\ScraperServiceProvider"```
```php artisan migrate```

### Разработка собственного парсера

#### Работа с файлами
1. Создайте Laravel пакет (для этого подходит ```EvolutionCMS\Main```).
2. Создайте папку для парсеров (например ```EvolutionCMS\Main\Parsers```).
3. Создайте класс с наследованием от ```EvolutionCMS\Scraper\Services\Parsers\AbstractParser```
4. Добавьте класс в конфигурационный файл ```yourproject\core\custom\config\scraper.php```

#### AbstractParser
Основная логика абстрактного класса описана в конструкторе. Что в первую очередь нужно знать, чтобы создать свой класс парсера на его основе:
- ```beforeProcess()``` - здесь производится работа с начальным DOM деревом. Например, можно задать title, description для documentObject.
- ```processHtml()``` - сужение DOM, обрабокта тегов, классов и т.д.
- ```afterProcess()``` - работа с обработанным контентом, его сохранениие в поле content у documentObject.

Запускаются функции именно в такой последовательности. Помимо этого, в AbstractParser прописана логика получения данных по url из задания. Рекомендуется переписать AbstractParser для своих нужд.

#### Пример парсера
```php
<?php

namespace EvolutionCMS\Scraper\Services\Parsers;

use Wa72\HtmlPageDom\HtmlPage;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class FourPdaParser extends AbstractParser
{
    /*
     * $this->htmlPage - экземпляр Wa72\HtmlPageDom\HtmlPage; здесь хранится ответ, полученный при обращении к url из задания
     * $this->documentObject - стандартная страница Evolution CMS; сама создаётся либо перезаписывается уже существующая
     * $this->task - обрабатываемое задание (модель Task)
     * */
    public function beforeProcess()
    {
        $title                           = str_replace(' - 4PDA', '', $this->htmlPage->getTitle());
        $this->documentObject->pagetitle = $title;
    }

    public function processHtml()
    {
        // заносим в $c контентную часть, с которой будем работать
        $siteContent = $this->htmlPage->getCrawler()->filter('.content-box')->getInnerHtml();
        $c           = new HtmlPage($siteContent);
        // удаляем аттрибуты, которые сильно влияют на отображение
        $c->filter('*')->removeAttr('style')->removeAttr('width')->removeAttr('height');
        // удаляем блок с источником информации
        $c->filter('.mb_source')->remove();
        // удаляем пустые <p></p>
        $c->filter('p')->each(function (HtmlPageCrawler $node) {
            if ($node->innerText() == '') {
                // замещает текущую node дочерними node, если они существуют
                // если дочерних node нет - замещение пустотой
                $node->replaceWith($node->getInnerHtml());
            }
        });
        // картинки с описаниями
        $c->filter('.wp-caption')->each(function (HtmlPageCrawler $node) {
            $node->replaceWith('<figure class="figure">'.$node->getInnerHtml().'</figure>');
        });
        $c->filter('.wp-caption-dt')->addClass('figure-img');
        $c->filter('.wp-caption-dd')->each(function (HtmlPageCrawler $node) {
            $node->replaceWith('<figcaption  class="figure-caption">'.$node->getInnerHtml().'</figcaption>');
        });
        $c->filter('blockquote')->addClass('blockquote');
        $c->filter("img")->removeAttr('class')->addClass("img-fluid");
        // изображения в неупорядоченном списке, удаляем стандартные точки сбоку от <li>
        $c->filter('.galContainer')->addClass('list-unstyled');
        $this->htmlPage = $c;
    }

    public function afterProcess()
    {
        $this->documentObject->content = $this->htmlPage->getBody()->html();
    }
}

```

### Обработка заданий

```php artisan scraper:process {task?} {--with-completed} { --with-unfinished} {--ignore-timestamp}```
Обработка задания со статусом Created.

```php artisan scraper:process 13```
Принудительная обработка задания с идентификатором 13.

```php artisan scraper:process --with-completed```
Обработка задания со статусом Created или Completed.

```php artisan scraper:process --with-unfinished --with-completed```
Обработка задания со статусом Created, Unfinished или Completed.

```php artisan scraper:process --ignore-timestamp```
Игнорирует поле метки времени (по умолчанию обрабатываются задания, timestamp которых не указывает на дату из будущего).

```php artisan scraper:mark-tasks```
Меняет статус всех Completed заданий без документов на Created.
