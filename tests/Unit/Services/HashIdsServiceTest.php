<?php

namespace Tests\Unit\Services;

use App\Services\HashIdsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Mockery\MockInterface;
use Tests\TestCase;

class HashIdsServiceTest extends TestCase
{

    use RefreshDatabase;

    private HashIdsService $hashIdService;
    private MockInterface $hashIdsPackageMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->hashIdsPackageMock = $this->mock('overload:Vinkla\Hashids\Facades\Hashids');
        $this->hashIdService = new HashIdsService();
    }

    public function test_create_and_return_a_hash_short_code()
    {

        $this->hashIdsPackageMock->shouldReceive('encode')
            ->with(1)
            ->andReturn('abc12');

        $this->assertEquals('abc12', $this->hashIdService->createAHashShortCode(1));
    }

    public function test_short_code_create_failure_when_no_short_code_arg()
    {
        $this->expectException(\App\Exceptions\EncodeDecodeFailureException::class);
        $this->hashIdService->createAHashShortCode('');
    }

    public function test_decode_and_return_short_code_id()
    {

        $this->hashIdsPackageMock->shouldReceive('decode')
            ->with('abc12')
            ->andReturn([0 => 1]);

        $this->assertEquals(1, $this->hashIdService->decodeAHashShortCode('abc12'));
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
