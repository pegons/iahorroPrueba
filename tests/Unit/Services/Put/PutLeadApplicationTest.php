<?php

namespace Tests\Unit\Services\Put;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Microservice\Domain\LeadDomain;
use Microservice\Application\Services\Put\PutLeadApplication;
use Microservice\Domain\Exceptions\LeadUpdateException;
use Microservice\Domain\Repositories\LeadRepositoryInterface;
use Microservice\Domain\Repositories\LeadScoringRepositoryInterface;
use App\Dtos\PutLeadDto;

class PutLeadApplicationTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private $leadRepository;
    private $scoringRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->leadRepository = Mockery::mock(LeadRepositoryInterface::class);
        $this->scoringRepository = Mockery::mock(LeadScoringRepositoryInterface::class);
    }

    /** @test */
    public function testLeadIsUpdateSuccessFully()
    {
        $uuid = 'some-uuid';
        $updateData = [
            'uuid' => $uuid,
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'phone' => '+1234567891',
        ];

        $putDto = new PutLeadDto($updateData);
        $leadDomain = Mockery::mock(LeadDomain::class);

        $this->leadRepository->shouldReceive('find')
            ->with($uuid)
            ->andReturn($leadDomain);

        $leadDomain->shouldReceive('setName')
            ->andReturnSelf();

        $leadDomain->shouldReceive('setEmail')
            ->andReturnSelf();

        $leadDomain->shouldReceive('setPhone')
            ->andReturnSelf();

        $leadDomain->shouldReceive('calculateMyScoring')
            ->with($this->scoringRepository)
            ->andReturnSelf();

        $this->leadRepository->shouldReceive('update')
            ->with($leadDomain)
            ->andReturn($leadDomain);

        $putApplication = new PutLeadApplication($this->leadRepository, $this->scoringRepository);

        $putApplication->__invoke($putDto);

        $this->assertTrue(true);
    }

    /** @test */
    public function testLeadFailedUpdating()
    {
        $this->expectException(LeadUpdateException::class);

        $uuid = 'some-uuid';
        $updateData = [
            'uuid' => $uuid,
            'name' => 'Jane Doe',
            'email' => 'jane.doe@example.com',
            'phone' => '+1234567891',
        ];

        $putDto = new PutLeadDto($updateData);

        $this->leadRepository->shouldReceive('find')
            ->with($uuid)
            ->andThrow(new \Exception());

        $putApplication = new PutLeadApplication($this->leadRepository, $this->scoringRepository);

        $putApplication->__invoke($putDto);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
