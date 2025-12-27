<?php

declare(strict_types=1);

namespace Mvdnbrk\Documentation;

use Illuminate\Support\HtmlString;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;
use League\CommonMark\MarkdownConverter;

class Markdown
{
    public static function parse(string $text): HtmlString
    {
        $environment = new Environment([
            'allow_unsafe_links' => false,
        ]);

        $environment->addExtension(new CommonMarkCoreExtension);
        $environment->addExtension(new GithubFlavoredMarkdownExtension);

        $converter = new MarkdownConverter($environment);

        return new HtmlString(
            trim($converter->convert($text)->getContent())
        );
    }
}
