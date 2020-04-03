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

        $path = $this->path($version, $page);

        if (! $this->files->exists($path)) {
            return null;
        }

        return (new ParsedownExtra)->text($this->files->get($path));
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
