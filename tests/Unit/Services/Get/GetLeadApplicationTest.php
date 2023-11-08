<?php

namespace Tests\Unit\Services\Get;

use Mockery;
use App\Dtos\GetLeadDto;
use PHPUnit\Framework\TestCase;
use Microservice\Domain\LeadDomain;
use Microservice\Application\Mappers\LeadMapper;
use Microservice\Application\Services\Get\GetLeadApplication;
use Microservice\Domain\Repositories\LeadRepositoryInterface;

class GetLeadApplicationTest extends TestCase
{
    private GetLeadApplication $getApplication;
    private LeadRepositoryInterface $leadRepository;
    private LeadMapper $leadMapper;

    public function setUp(): void
    {
        parent::setUp();

        $this->leadRepository = Mockery::mock(LeadRepositoryInterface::class);
        $this->leadMapper = Mockery::mock(LeadMapper::class);

        $this->getApplication = new GetLeadApplication(
            $this->leadRepository,
            $this->leadMapper
        );
    }
    /** @test */
    public function testGetLeadApplication()
    {
        $leadId = 'some-uuid';
        $leadDomainModel = Mockery::mock(LeadDomain::class);
        $getLeadDto = new GetLeadDto([
            'id' => $leadId,
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1234567890',
            'createdAt' => '2023-11-07T12:34:56Z',
            'updatedAt' => '2023-11-07T12:34:56Z'
        ]);

        // Expect the repository to return a domain model when find() is called with the lead ID
        $this->leadRepository->shouldReceive('find')
            ->once()
            ->with($leadId)
            ->andReturn($leadDomainModel);

        // Expect the mapper to convert the domain model into a DTO
        $this->leadMapper->shouldReceive('fromDomainToDto')
            ->once()
            ->with($leadDomainModel)
            ->andReturn($getLeadDto);

        // Invoke the application service and assert the returned DTO is as expected
        $resultDto = $this->getApplication->__invoke($leadId);

        $this->assertInstanceOf(GetLeadDto::class, $resultDto);
        $this->assertEquals($getLeadDto, $resultDto);
    }
}
