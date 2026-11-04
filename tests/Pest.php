<?php

use Ss2iDigital\FilamentMasonry\Tests\TestCase;

uses(TestCase::class)->in(__DIR__);

/**
 * Evaluates an XPath expression on an HTML fragment (class names matched as whole tokens).
 */
function xpath(string $html, string $expression): DOMNodeList
{
    $document = new DOMDocument;
    @$document->loadHTML('<?xml encoding="utf-8" ?>' . $html);

    $nodes = (new DOMXPath($document))->query($expression);

    assert($nodes instanceof DOMNodeList);

    return $nodes;
}

function hasClass(string $class): string
{
    return "contains(concat(' ', normalize-space(@class), ' '), ' {$class} ')";
}
