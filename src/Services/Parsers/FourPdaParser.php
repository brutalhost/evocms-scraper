<?php

namespace EvolutionCMS\Scraper\Services\Parsers;

use Wa72\HtmlPageDom\HtmlPage;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class FourPdaParser extends AbstractParser
{
    public function beforeProcess()
    {
        $title                           = str_replace(' - 4PDA', '', $this->htmlPage->getTitle());
        $this->documentObject->pagetitle = $title;
    }

    public function processHtml()
    {
        $siteContent = $this->htmlPage->getCrawler()->filter('.content-box')->getInnerHtml();
        $c           = new HtmlPage($siteContent);

        $c->filter('*')->removeAttr('style')->removeAttr('width')->removeAttr('height');

        // remove source paragraph
        $c->filter('.mb_source')->remove();

        // remove empty <p></p>
        $c->filter('p')->each(function (HtmlPageCrawler $node) {
            if ($node->innerText() == '') {
                $node->replaceWith($node->getInnerHtml());
            }
        });

        // caption
        $c->filter('.wp-caption')->each(function (HtmlPageCrawler $node) {
            $node->replaceWith('<figure class="figure">'.$node->getInnerHtml().'</figure>');
        });
        $c->filter('.wp-caption-dt')->addClass('figure-img');
        $c->filter('.wp-caption-dd')->each(function (HtmlPageCrawler $node) {
            $node->replaceWith('<figcaption  class="figure-caption">'.$node->getInnerHtml().'</figcaption>');
        });

        $c->filter('blockquote')->addClass('blockquote');
        $c->filter("img")->removeAttr('class')->addClass("img-fluid");

        // list of images
        $c->filter('.galContainer')->addClass('list-unstyled');

        $this->htmlPage = $c;
    }

    public function afterProcess()
    {
        $this->documentObject->content = $this->htmlPage->getBody()->html();
    }
}
