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
