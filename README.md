# Scraper - парсер статических сайтов
## Установка
```cd core```  
```php -d="memory_limit=-1" artisan package:installrequire brutalhost/evocms-scraper "*"```  
```php artisan vendor:publish --provider="EvolutionCMS\Scraper\ScraperServiceProvider"```  
```php artisan migrate```
## Использование
Для использования модуля откройте его и прочтите короткую инструкцию внутри админ-панели. Пользовательский интерфейс интуитивно понятен.
## Разработка парсеров
Модуль рассчитан на парсинг статических сайтов, которые не подгружают данные "налету". Парсеры создаются с использованием jQuery подобного синтаксиса, библиотека [wasinger/htmlpagedom](https://github.com/wasinger/htmlpagedom). Подразумевается, что классы парсеров наследуются от ```EvolutionCMS\Scraper\Services\Parsers\AbstractParser```. Нет привязки к интерфейсу, поэтому можно создавать корневые классы с собственной логикой.
