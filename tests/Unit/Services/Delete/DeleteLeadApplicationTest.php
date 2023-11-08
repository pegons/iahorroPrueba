<?php

namespace Tests\Unit\Services\Delete;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Microservice\Domain\Exceptions\LeadDeleteException;
use Microservice\Application\Services\Delete\DeleteLeadApplication;
use Microservice\Domain\Repositories\LeadRepositoryInterface;
use Microservice\Domain\LeadDomain;

class DeleteLeadApplicationTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private $leadRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->leadRepository = Mockery::mock(LeadRepositoryInterface::class);
    }

    /** @test */
    public function it_deletes_lead_successfully()
    {
        $uuid = 'some-uuid';
        $leadDomain = Mockery::mock(LeadDomain::class);

        $this->leadRepository->shouldReceive('find')
            ->with($uuid)
            ->andReturn($leadDomain);

        $this->leadRepository->shouldReceive('delete')
            ->with($uuid)
            ->once();

        $deleteApplication = new DeleteLeadApplication($this->leadRepository);

        $deleteApplication->__invoke($uuid);

        $this->assertTrue(true);
    }

    /** @test */
    public function it_throws_exception_if_lead_not_found()
    {
        $this->expectException(LeadDeleteException::class);
        $this->expectExceptionMessage("Lead not found with ID: some-uuid");

        $uuid = 'some-uuid';

        $this->leadRepository->shouldReceive('find')
            ->with($uuid)
            ->andReturnNull();

        $deleteApplication = new DeleteLeadApplication($this->leadRepository);

        $deleteApplication->__invoke($uuid);
    }

    /** @test */
    public function it_throws_exception_if_delete_fails()
    {
        $this->expectException(LeadDeleteException::class);
        $this->expectExceptionMessage("Unable to delete lead");

        $uuid = 'some-uuid';
        $leadDomain = Mockery::mock(LeadDomain::class);

        $this->leadRepository->shouldReceive('find')
            ->with($uuid)
            ->andReturn($leadDomain);

        $this->leadRepository->shouldReceive('delete')
            ->with($uuid)
            ->andThrow(new \Exception("An error occurred"));

        $deleteApplication = new DeleteLeadApplication($this->leadRepository);

        $deleteApplication->__invoke($uuid);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
