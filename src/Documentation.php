<?php

namespace Mvdnbrk\Documentation;

use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use ParsedownExtra;

class Documentation
{
    protected Factory $filesystem;
    protected Filesystem $files;

    public function __construct(Factory $filesystem)
    {
        $this->filesystem = $filesystem;

        $this->files = $this->filesystem->disk(
            config('documentation.storage.disk')
        );
    }

    public function get(string $version, string $page): ?string
    {
        if ($this->isExcludedPage($page)) {
            return null;
        }

        if (! $this->sectionExists($version, $page)) {
            return null;
        }

        return (new ParsedownExtra)->text($this->files->get(
            $this->path($version, $page)
        ));
    }

    public function getIndex(string $version): ?string
    {
        $indexPage = config('documentation.index_page');

        if (! $this->sectionExists($version, $indexPage)) {
            return null;
        }

        return (new ParsedownExtra)->text(
            $this->replaceVersionPlaceHolders($version, $this->files->get(
                $this->path($version, $indexPage)
            ))
        );
    }

    public function defaultVersion()
    {
        return config('documentation.default_version');
    }

    public function isExcludedPage(string $page): bool
    {
        return $this->excludedPages()->contains(
            Str::lower($page)
        );
    }

    public function path(string $version, string $page): string
    {
        return "{$version}/{$page}.md";
    }

    public function replaceVersionPlaceholders($version, $content)
    {
        return str_replace('{{version}}', $version, $content);
    }

    public function sectionExists($version, $page): bool
    {
        return $this->files->exists(
            $this->path($version, $page)
        );
    }

    public function excludedPages(): Collection
    {
        return new Collection(
            config('documentation.excluded_pages')
        );
    }

    public function versions(): Collection
    {
        return new Collection(
            config('documentation.versions')
        );
    }
}
