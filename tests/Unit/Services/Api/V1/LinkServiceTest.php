<?php

namespace Tests\Unit\Services\Api\V1;

use App\Models\Link;
use App\Repositories\LinkRepository;
use App\Services\Api\V1\LinkService;
use App\Services\HashIdsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LinkServiceTest extends TestCase
{
    use RefreshDatabase;

    private LinkService $linkService;

    public function setUp(): void
    {
        parent::setUp();

        $this->linkService = new LinkService(
            new LinkRepository(),
            new HashIdsService()
        );
    }

    public function testLinkServiceReturnsLinkModel(): void
    {
        $link = $this->linkService->generateShortUrl('https://example.com');

        $this->assertInstanceOf(Link::class, $link);
    }

    public function testLinkServiceReturnsOriginalUrl(): void
    {
        $link = $this->linkService->generateShortUrl('https://bbc.com');

        $originalUrl = $this->linkService->retrieveOriginalUrl($link->short_code);

        $this->assertInstanceOf(Link::class, $originalUrl);
        $this->assertEquals('https://bbc.com', $originalUrl->original_url);
    }
}
