<?php

namespace Mvdnbrk\Documentation\Tests;

use Illuminate\Support\Collection;

class DocumentationTest extends TestCase
{
    /** @test */
    public function it_can_get_the_versions()
    {
        $this->assertInstanceOf(Collection::class, $this->documentation->versions());

        $this->assertEquals([
            '1.0',
        ], $this->documentation->versions()->all());
    }

    /** @test */
    public function it_can_get_the_excluded_pages()
    {
        $this->assertInstanceOf(Collection::class, $this->documentation->excludedPages());

        $this->assertEquals([
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
    public function it_can_get_the_default_version()
    {
        config(['documentation.default_version' => '9.9']);

        $this->assertEquals('9.9', $this->documentation->defaultVersion());
    }

    /** @test */
    public function it_can_get_the_default_page()
    {
        $this->assertEquals('installation', $this->documentation->defaultPage());
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
        $this->assertEquals('9.9', $this->documentation->replaceVersionPlaceholders('9.9', '{{version}}'));
    }

    /** @test */
    public function it_can_get_the_index_page()
    {
        $this->assertEquals('<p><a href="1.0/dummy">Dummy</a></p>', $this->documentation->getIndex('1.0'));
    }

    /** @test */
    public function it_returns_null_when_the_index_page_does_not_exist()
    {
        config(['documentation.index_page' => 'does-not-exist']);

        $this->assertNull($this->documentation->getIndex('1.0'));
    }

    /** @test */
    public function it_can_get_the_path()
    {
        $this->assertEquals('1.0/test-page.md', $this->documentation->path('1.0', 'test-page'));
    }

    /** @test */
    public function it_can_get_a_page()
    {
        $this->assertEquals('<h1>Dummy</h1>', $this->documentation->get('1.0', 'dummy'));
    }

    /** @test */
    public function it_returns_null_for_a_page_that_does_not_exist()
    {
        $this->assertNull($this->documentation->get('1.0', 'non-existent'));
        $this->assertNull($this->documentation->get('invalid-version', 'dummy'));
    }

    /** @test */
    public function it_returns_null_for_an_excluded_page()
    {
        $this->assertNull($this->documentation->get('1.0', 'readme'));
    }
}
