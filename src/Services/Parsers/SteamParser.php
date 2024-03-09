<?php

namespace EvolutionCMS\Scraper\Services\Parsers;

use EvolutionCMS\Facades\UrlProcessor;
use EvolutionCMS\Models\SiteContent;
use Wa72\HtmlPageDom\HtmlPage;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class SteamParser extends AbstractParser
{
    public function beforeProcess()
    {
        $title = substr(strrchr($this->htmlPage->getTitle(), "::"), 2);
        $this->documentObject->pagetitle = $title;
    }

    public function processHtml()
    {
        $siteContent   = $this->htmlPage->getCrawler()->filter('.guide.subSections')->getInnerHtml();
        $c = new HtmlPage($siteContent);

        $c->filter(".subSectionTitle")->each(function (HtmlPageCrawler $node) {
            $node->replaceWith('<h2>'.$node->filter('.subSectionTitle')->getInnerHtml().'</h2>');
        });
        $c->filter(".bb_code")->each(function (HtmlPageCrawler $node) {
            $node->replaceWith('<pre>'.$node->getInnerHtml().'</pre>');
        });

        /* tables */
        $c->filter(".bb_table")->each(function (HtmlPageCrawler $node) {
            $node->replaceWith('<table class="table">'.$node->getInnerHtml().'</table>')->wrap('<div class="table-responsive">');
        });
        $c->filter(".bb_table_tr")->each(function (HtmlPageCrawler $node) {
            $node->replaceWith('<tr>'.$node->getInnerHtml().'</tr>');
        });
        $c->filter(".bb_table_td")->each(function (HtmlPageCrawler $node) {
            $node->replaceWith('<td>'.$node->getInnerHtml().'</td>');
        });
        $c->filter(".bb_table_th")->each(function (HtmlPageCrawler $node) {
            $node->replaceWith('<th>'.$node->getInnerHtml().'</th>');
        });

        /* remove extra divs */
        $c->filter("div")->each(function (HtmlPageCrawler $node) {
            if ($node->getStyle('clear') == 'both') {
                $node->remove();
            }
        });

        /* images */
        $c->filter(".sharedFilePreviewImage.sizeThumb")->removeAttr('class')->addClass("img-fluid float-left me-2")->css('max-width',
            '311px');
        $c->filter(".sharedFilePreviewImage")->removeAttr('class')->addClass("img-fluid");

        /* remove modals */
        $c->filter('.modalContentLink')->each(function (HtmlPageCrawler $node) {
            $node->replaceWith($node->getInnerHtml());
        });
        $c->filter('*')->removeAttribute('data-modal-content-popup-url')->removeAttribute('id')->removeAttribute('style');

        $this->htmlPage = $c;
    }

    public function afterProcess()
    {
        $this->documentObject->content   = $this->htmlPage->getBody()->html();
    }
}
