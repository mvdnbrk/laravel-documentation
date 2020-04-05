<?php

namespace Mvdnbrk\Documentation;

use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;

class Markdown
{
    public function parse(string $text): string
    {
        $environment = Environment::createCommonMarkEnvironment();

        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        $converter = new CommonMarkConverter([
            'allow_unsafe_links' => false,
        ], $environment);

        return trim($converter->convertToHtml($text));
    }
}
