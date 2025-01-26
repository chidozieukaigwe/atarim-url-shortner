<?php

namespace Tests\Feature\Api\V1;

use App\Models\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LinkTest extends TestCase
{
    use RefreshDatabase;

    public function testShorteningAUrlReturnsAValidLinkResource(): void
    {
        $response = $this->postJson('/api/v1/encode', [
            'original_url' => 'https://www.bbc.co.uk/sport',
        ]);

        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'original_url',
                'short_code',
                'short_url'
            ]

        ]);
    }

    public function testShorteningAUrlFailsWhenUrlIsInvalid(): void
    {
        $response = $this->postJson('/api/v1/encode', [
            'original_url' => 'https:test.com',
        ]);

        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => [
                'original_url'
            ]
        ]);
        $response->assertJson([
            'message' => 'The original url field format is invalid.',
        ]);
    }

    public function testShortenedUrlCanBeDecodedToOriginalUrl(): void
    {
        $response = $this->postJson('/api/v1/encode', [
            'original_url' => 'https://accreditly.io/articles/how-to-build-a-url-shortener-in-laravel',
        ]);

        $link = $response->json('data');

        $response = $this->getJson('/api/v1/decode/' . $link['short_code']);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'data' => [
                'original_url',
            ]
        ]);
        $this->assertEquals($link['original_url'], $response->json('data.original_url'));
        $this->assertEquals($response->json('data.original_url'), 'https://accreditly.io/articles/how-to-build-a-url-shortener-in-laravel');
    }

    public function testDecodedUrlReturns404WhenShortCodeIsNotFound(): void
    {
        $response = $this->getJson('/api/v1/decode/not-a-valid-short-code');

        $response->assertStatus(404);
        $response->assertJsonStructure([
            'status',
            'message',
            'short_code'
        ]);
        $response->assertJson([
            'message' => 'Could not find url: The short code could not be found',
            'short_code' => ['not-a-valid-short-code']
        ]);
    }

    public function testSameLinkModelIsReturnedWhenOriginalUrlExistsInDatabase(): void
    {
        // Arrange
        Link::factory()->create([
            'original_url' => 'https://bbc.com',
            'short_code' => 'abc12',
        ]);

        // Act
        $response = $this->postJson('/api/v1/encode', [
            'original_url' => 'https://bbc.com',
        ]);

        // Assert
        $response->assertStatus(200);
        $this->assertEquals($response->json('data.original_url'), 'https://bbc.com');
        $this->assertEquals($response->json('data.short_code'), 'abc12');
    }
}
