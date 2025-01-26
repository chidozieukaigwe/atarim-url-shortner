<?php

namespace Tests\Unit\Repositories;

use App\Models\Link;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class LinkRepositoriesTest extends TestCase
{
    use RefreshDatabase;

    private MockInterface $linkRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->linkRepository = $this->mock('App\Repositories\LinkRepositoryInterface');
    }

    public function test_link_repo_finds_returns_a_link_model()
    {
        $link = Link::factory()->create();

        $this->linkRepository->shouldReceive('find')
            ->with($link->id)
            ->andReturn($link);

        $this->assertEquals($link, $this->linkRepository->find($link->id));
    }

    public function test_link_repo_finds_throws_model_not_found_exception()
    {
        $this->expectException(ModelNotFoundException::class);
        $this->linkRepository->shouldReceive('find')
            ->with(123)
            ->andThrow(ModelNotFoundException::class);

        $this->linkRepository->find(123);
    }

    public function test_link_repo_creates_returns_a_link_model()
    {
        $data = ['original_url' => 'https://example.com'];
        $link = Link::factory()->make($data);

        $this->linkRepository->shouldReceive('create')
            ->with($data)
            ->andReturn($link);

        $this->assertEquals($link, $this->linkRepository->create($data));
    }

    public function test_link_repo_where_returns_a_link_model()
    {
        $link = Link::factory()->create([
            'original_url' => 'https://example.com',
            'short_code' => 'abc123'
        ]);

        $column = 'original_url';
        $value = 'https://example.com';

        $this->linkRepository->shouldReceive('where')
            ->with($column, $value)
            ->andReturn($link);

        $this->assertEquals($link, $this->linkRepository->where($column, $value));
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        Mockery::close();
    }
}
