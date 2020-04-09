<?php

namespace Mvdnbrk\Documentation\Tests;

use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

class DocumentationTest extends TestCase
{
    /** @test */
    public function it_can_get_the_versions()
    {
        $this->assertInstanceOf(Collection::class, $this->documentation->versions());

        $this->assertSame([
            '1.0',
        ], $this->documentation->versions()->all());
    }

    /** @test */
    public function it_converts_versions_with_an_integer_to_a_string()
    {
        config(['documentation.versions.published' => [
            1,
        ]]);

        $this->assertSame([
            '1',
        ], $this->documentation->versions()->all());
    }

    /** @test */
    public function versions_get_sorted_in_opposite_alphabetical_order()
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

    /** @test */
    public function it_can_get_the_excluded_pages()
    {
        $this->assertInstanceOf(Collection::class, $this->documentation->excludedPages());

        $this->assertEquals([
            'index',
            'readme',
        ], $this->documentation->excludedPages()->all());
    }

    /** @test */
    public function it_can_determine_if_a_page_is_excluded()
    {
        $this->assertFalse($this->documentation->isExcludedPage('test-page'));
        $this->assertTrue($this->documentation->isExcludedPage('readme'));
        $this->assertTrue($this->documentation->isExcludedPage('README'));
    }

    /** @test */
    public function the_index_page_is_an_exluded_page_by_default()
    {
        $this->assertTrue($this->documentation->isExcludedPage('index'));
    }

    /** @test */
    public function it_can_determine_if_a_version_exists()
    {
        $this->assertTrue($this->documentation->isVersion('1.0'));
        $this->assertFalse($this->documentation->isVersion('does-not-exist'));
    }

    /** @test */
    public function it_can_get_the_default_version()
    {
        config(['documentation.versions.published' => [
            '2.0',
            '1.0',
        ]]);
        config(['documentation.versions.default' => '1.0']);

        $this->assertSame('1.0', $this->documentation->defaultVersion());
    }

    /** @test */
    public function it_falls_back_to_the_first_version_if_default_version_is_not_configured()
    {
        config(['documentation.versions.default' => null]);

        $this->assertSame('1.0', $this->documentation->defaultVersion());
    }

    /** @test */
    public function it_does_not_fall_back_to_the_master_version_if_default_version_is_not_configured()
    {
        config(['documentation.versions.published' => [
            'master',
            '1.0',
        ]]);

        $this->assertSame('1.0', $this->documentation->defaultVersion());
    }

    /** @test */
    public function it_falls_back_to_the_master_version_if_master_is_configured_as_the_default_verison()
    {
        config(['documentation.versions.published' => [
            'master',
        ]]);
        config(['documentation.versions.default' => 'master']);

        $this->assertSame('master', $this->documentation->defaultVersion());
    }

    /** @test */
    public function getting_the_default_version_does_not_return_a_non_existent_version()
    {
        config(['documentation.versions.default' => 'does-not-exist']);

        $this->assertSame('1.0', $this->documentation->defaultVersion());
    }

    /** @test */
    public function getting_the_default_version_returns_null_when_versions_and_default_version_are_not_configured()
    {
        config(['documentation.versions.published' => []]);
        config(['documentation.versions.default' => null]);

        $this->assertNull($this->documentation->defaultVersion());
    }

    /** @test */
    public function it_can_get_the_default_page()
    {
        $this->assertEquals('installation', $this->documentation->defaultPage());

        config(['documentation.pages' => []]);
        $this->assertSame('', $this->documentation->defaultPage());

        config(['documentation.pages.default' => null]);
        $this->assertSame('', $this->documentation->defaultPage());
    }

    /** @test */
    public function it_can_determine_if_a_section_exists()
    {
        $this->assertTrue($this->documentation->sectionExists('1.0', 'dummy'));
        $this->assertFalse($this->documentation->sectionExists('1.0', 'does-not-exist'));
    }

    /** @test */
    public function it_can_replace_a_version_placeholder()
    {
        $this->assertSame('9.9', $this->documentation->replaceVersionPlaceholders('9.9', '{{version}}'));
    }

    /** @test */
    public function it_can_get_the_index_page()
    {
        $this->assertInstanceOf(HtmlString::class, $this->documentation->getIndex('1.0'));

        $this->assertSame('<p><a href="1.0/dummy">Dummy</a></p>', $this->documentation->getIndex('1.0')->toHtml());
    }

    /** @test */
    public function it_returns_an_empty_html_string_when_the_index_page_does_not_exist()
    {
        config(['documentation.pages.table_of_contents' => 'does-not-exist']);

        $this->assertInstanceOf(HtmlString::class, $this->documentation->getIndex('1.0'));
        $this->assertEmpty($this->documentation->getIndex('1.0')->toHtml());
    }

    /** @test */
    public function it_can_get_the_path()
    {
        $this->assertSame('1.0/test-page.md', $this->documentation->path('1.0', 'test-page'));
    }

    /** @test */
    public function it_can_get_a_page()
    {
        $this->assertInstanceOf(HtmlString::class, $this->documentation->get('1.0', 'dummy'));

        $this->assertSame('<h1>Dummy</h1>', $this->documentation->get('1.0', 'dummy')->toHtml());
    }

    /** @test */
    public function it_returns_an_empty_html_string_for_a_page_that_does_not_exist()
    {
        $this->assertInstanceOf(HtmlString::class, $this->documentation->get('1.0', 'non-existent'));
        $this->assertEmpty($this->documentation->get('1.0', 'non-existent')->toHtml());

        $this->assertInstanceOf(HtmlString::class, $this->documentation->get('999', 'dummy'));
        $this->assertEmpty($this->documentation->get('999', 'dummy')->toHtml());
    }

    /** @test */
    public function it_returns_an_empty_html_string_for_an_excluded_page()
    {
        $this->assertInstanceOf(HtmlString::class, $this->documentation->get('1.0', 'readme'));
        $this->assertEmpty($this->documentation->get('1.0', 'readme')->toHtml());
    }

    /** @test */
    public function it_can_determine_which_versions_contains_a_page()
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
