<?php

namespace Tests\Unit\Services\Post;

use Mockery;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use PHPUnit\Framework\TestCase;
use Microservice\Domain\LeadDomain;
use Microservice\Application\Services\Post\PostLeadApplication;
use Microservice\Domain\Exceptions\NewLeadCreateException;
use Microservice\Domain\Repositories\LeadRepositoryInterface;
use Microservice\Domain\Repositories\ClientRepositoryInterface;
use Microservice\Domain\Repositories\LeadScoringRepositoryInterface;
use App\Dtos\PostLeadDto;
use Microservice\Domain\ClientDomain;

class PostLeadApplicationTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    private $leadRepository;
    private $scoringRepository;
    private $clientRepository;

    public function setUp(): void
    {
        parent::setUp();

        $this->leadRepository = Mockery::mock(LeadRepositoryInterface::class);
        $this->scoringRepository = Mockery::mock(LeadScoringRepositoryInterface::class);
        $this->clientRepository = Mockery::mock(ClientRepositoryInterface::class);
    }

    /** @test */
        public function it_creates_lead_and_client_successfully()
    {
        $postData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1234567890',
        ];

        $postDto = new PostLeadDto($postData);
        $leadDomainMock = Mockery::mock(LeadDomain::class)->makePartial();
        $clientDomainMock = Mockery::mock(ClientDomain::class)->makePartial();

        $this->scoringRepository->shouldReceive('calculate')
            ->with(Mockery::type(LeadDomain::class))
            ->andReturnUsing(function () {
                return 1;
            });

        $this->clientRepository->shouldNotReceive('create');

        $leadDomainMock->shouldReceive('calculateMyScoring')
                  ->andReturnSelf();


        $leadDomainMock->shouldReceive('generateClient')
            ->andReturnUsing(function () use ($clientDomainMock) {
                return $clientDomainMock;
        });

        $this->leadRepository->shouldReceive('create')
                        ->with(Mockery::type(LeadDomain::class))
                        ->andReturn($leadDomainMock);

        $postApplication = new PostLeadApplication(
            $this->leadRepository,
            $this->clientRepository,
            $this->scoringRepository
        );

        $postApplication->__invoke($postDto);

        $this->assertTrue(true);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_throws_exception_if_lead_creation_fails()
    {
        $this->expectException(NewLeadCreateException::class);

        $postData = [
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1234567890',
        ];

        $postDto = new PostLeadDto($postData);

        $this->leadRepository->shouldReceive('create')
            ->andThrow(new \Exception());

        $postApplication = new PostLeadApplication(
            $this->leadRepository,
            $this->clientRepository,
            $this->scoringRepository
        );

        $postApplication->__invoke($postDto);
    }
}
