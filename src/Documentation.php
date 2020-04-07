<?php

namespace Mvdnbrk\Documentation;

use Illuminate\Contracts\Filesystem\Factory;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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

        return Markdown::parse($this->files->get(
            $this->path($version, $page)
        ));
    }

    public function getIndex(string $version): ?string
    {
        $indexPage = config('documentation.index_page');

        if (! $this->sectionExists($version, $indexPage)) {
            return null;
        }

        return Markdown::parse(
            $this->replaceVersionPlaceHolders($version, $this->files->get(
                $this->path($version, $indexPage)
            ))
        );
    }

    public function defaultPage(): string
    {
        return config('documentation.default_page');
    }

    public function defaultVersion(): ?string
    {
        $version = config('documentation.default_version');

        if (! is_null($version) && $this->isVersion($version)) {
            return $version;
        }

        return $this->versions()->reject('master')->first();
    }

    public function isExcludedPage(string $page): bool
    {
        return $this->excludedPages()->contains(
            Str::lower($page)
        );
    }

    public function isVersion(string $version): bool
    {
        return $this->versions()->contains($version);
    }

    public function path(string $version, string $page): string
    {
        return "{$version}/{$page}.md";
    }

    public function replaceVersionPlaceholders($version, $content): string
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
        return (new Collection(config('documentation.versions')))
            ->sortDesc()
            ->values();
    }

    public function versionsContainingPage(string $page): Collection
    {
        return collect($this->versions())
            ->filter(fn (string $version): bool => $this->sectionExists($version, $page))
            ->values();
    }
}
