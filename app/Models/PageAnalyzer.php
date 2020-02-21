<?php

namespace App\Models;

use DiDom\Document;

class PageAnalyzer
{
    public function parsePage($page)
    {
        $document = new Document($page);

        $h1Element = $document->first('h1');
        $h1 = $h1Element ? $h1Element->text() : '';

        $keywordsElement = $document->first('meta[name="keywords"]');
        $keywords = $keywordsElement ? $keywordsElement->attr('content') : '';

        $descriptionElement = $document->first('meta[name="description"]');
        $description = $descriptionElement ? $descriptionElement->attr('content') : '';

        return [
            'h1' => $h1,
            'keywords' => $keywords,
            'description' => $description
        ];
    }
}
