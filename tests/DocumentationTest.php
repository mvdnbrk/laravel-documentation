<?php

declare(strict_types=1);

namespace Mvdnbrk\Documentation\Tests;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use PHPUnit\Framework\Attributes\Test;

class DocumentationTest extends TestCase
{
    #[Test]
    public function it_can_get_the_versions(): void
    {
        $this->assertInstanceOf(Collection::class, $this->documentation->versions());

        $this->assertSame([
            '1.0',
        ], $this->documentation->versions()->all());
    }

    #[Test]
    public function it_converts_versions_with_an_integer_to_a_string(): void
    {
        config(['documentation.versions.published' => [
            1,
        ]]);

        $this->assertSame([
            '1',
        ], $this->documentation->versions()->all());
    }

    #[Test]
    public function versions_get_sorted_in_opposite_alphabetical_order(): void
    {
        config(['documentation.versions.published' => [
            'a',
            'z',
        ]]);

        $this->assertSame([
            'z',
            'a',
        ], $this->documentation->versions()->all());
    }

    #[Test]
    public function it_can_get_the_excluded_pages(): void
    {
        $this->assertInstanceOf(Collection::class, $this->documentation->excludedPages());

        $this->assertEquals([
            'index',
            'readme',
        ], $this->documentation->excludedPages()->all());
    }

    #[Test]
    public function it_can_determine_if_a_page_is_excluded(): void
    {
        $this->assertFalse($this->documentation->isExcludedPage('test-page'));
        $this->assertTrue($this->documentation->isExcludedPage('readme'));
        $this->assertTrue($this->documentation->isExcludedPage('README'));
    }

    #[Test]
    public function the_index_page_is_an_exluded_page_by_default(): void
    {
        $this->assertTrue($this->documentation->isExcludedPage('index'));
    }

    #[Test]
    public function it_can_determine_if_a_version_exists(): void
    {
        $this->assertTrue($this->documentation->isVersion('1.0'));
        $this->assertFalse($this->documentation->isVersion('does-not-exist'));
    }

    #[Test]
    public function it_can_get_the_default_version(): void
    {
        config(['documentation.versions.published' => [
            '2.0',
            '1.0',
        ]]);
        config(['documentation.versions.default' => '1.0']);

        $this->assertSame('1.0', $this->documentation->defaultVersion());
    }

    #[Test]
    public function it_falls_back_to_the_first_version_if_default_version_is_not_configured(): void
    {
        config(['documentation.versions.default' => null]);

        $this->assertSame('1.0', $this->documentation->defaultVersion());
    }

    #[Test]
    public function it_does_not_fall_back_to_the_master_version_if_default_version_is_not_configured(): void
    {
        config(['documentation.versions.published' => [
            'master',
            '1.0',
        ]]);

        $this->assertSame('1.0', $this->documentation->defaultVersion());
    }

    #[Test]
    public function it_falls_back_to_the_master_version_if_master_is_configured_as_the_default_verison(): void
    {
        config(['documentation.versions.published' => [
            'master',
        ]]);
        config(['documentation.versions.default' => 'master']);

        $this->assertSame('master', $this->documentation->defaultVersion());
    }

    #[Test]
    public function getting_the_default_version_does_not_return_a_non_existent_version(): void
    {
        config(['documentation.versions.default' => 'does-not-exist']);

        $this->assertSame('1.0', $this->documentation->defaultVersion());
    }

    #[Test]
    public function getting_the_default_version_returns_null_when_versions_and_default_version_are_not_configured(): void
    {
        config(['documentation.versions.published' => []]);
        config(['documentation.versions.default' => null]);

        $this->assertNull($this->documentation->defaultVersion());
    }

    #[Test]
    public function it_can_get_the_default_page(): void
    {
        $this->assertEquals('installation', $this->documentation->defaultPage());

        config(['documentation.pages.default' => null]);
        $this->assertSame('', $this->documentation->defaultPage());
    }

    #[Test]
    public function it_can_determine_if_a_section_exists(): void
    {
        $this->assertTrue($this->documentation->sectionExists('1.0', 'dummy'));
        $this->assertFalse($this->documentation->sectionExists('1.0', 'does-not-exist'));
    }

    #[Test]
    public function it_can_replace_a_version_placeholder(): void
    {
        $this->assertSame('9.9', $this->documentation->replaceVersionPlaceholders('9.9', '{{version}}'));
    }

    #[Test]
    public function it_can_get_the_index_page(): void
    {
        $this->assertInstanceOf(HtmlString::class, $this->documentation->getIndex('1.0'));

        $this->assertSame('<p><a href="1.0/dummy">Dummy</a></p>', $this->documentation->getIndex('1.0')->toHtml());
    }

    #[Test]
    public function it_returns_an_empty_html_string_when_the_index_page_does_not_exist(): void
    {
        config(['documentation.pages.table_of_contents' => 'does-not-exist']);

        $this->assertInstanceOf(HtmlString::class, $this->documentation->getIndex('1.0'));
        $this->assertEmpty($this->documentation->getIndex('1.0')->toHtml());
    }

    #[Test]
    public function it_can_get_the_path(): void
    {
        $this->assertSame('1.0/test-page.md', $this->documentation->path('1.0', 'test-page'));
    }

    #[Test]
    public function it_can_get_a_page(): void
    {
        $this->assertInstanceOf(HtmlString::class, $this->documentation->get('1.0', 'dummy'));

        $this->assertSame('<h1>Dummy</h1>', $this->documentation->get('1.0', 'dummy')->toHtml());
    }

    #[Test]
    public function it_returns_an_empty_html_string_for_a_page_that_does_not_exist(): void
    {
        $this->assertInstanceOf(HtmlString::class, $this->documentation->get('1.0', 'non-existent'));
        $this->assertEmpty($this->documentation->get('1.0', 'non-existent')->toHtml());

        $this->assertInstanceOf(HtmlString::class, $this->documentation->get('999', 'dummy'));
        $this->assertEmpty($this->documentation->get('999', 'dummy')->toHtml());
    }

    #[Test]
    public function it_returns_an_empty_html_string_for_an_excluded_page(): void
    {
        $this->assertInstanceOf(HtmlString::class, $this->documentation->get('1.0', 'readme'));
        $this->assertEmpty($this->documentation->get('1.0', 'readme')->toHtml());
    }

    #[Test]
    public function it_can_determine_which_versions_contains_a_page(): void
    {
        config(['documentation.versions.published' => [
            '2.0',
            '1.0',
        ]]);

        $this->assertInstanceOf(Collection::class, $this->documentation->versionsContainingPage('dummy'));

        $this->assertEquals([
            '1.0',
        ], $this->documentation->versionsContainingPage('dummy')->all());
    }
}
