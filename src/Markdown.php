<?php

namespace Mvdnbrk\Documentation;

use Illuminate\Support\HtmlString;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;

class Markdown
{
    public static function parse(string $text): HtmlString
    {
        $environment = Environment::createCommonMarkEnvironment();

        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        $converter = new CommonMarkConverter([
            'allow_unsafe_links' => false,
        ], $environment);

        return new HtmlString(
            trim($converter->convertToHtml($text))
        );
    }
}
