<?php

namespace Tests\Unit\Domain;
use PHPUnit\Framework\TestCase;
use Microservice\Domain\LeadDomain;
use Microservice\Domain\Repositories\LeadScoringRepositoryInterface;
use Microservice\Domain\Repositories\ClientRepositoryInterface;
use Microservice\Domain\ClientDomain;
use Tests\DomainFactory\LeadDomainFactory;

class LeadDomainTest extends TestCase
{
    public function testCreateLeadEntitySuccessfully()
    {
        $leadData = [
            'id' => 'uuid',
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'createdAt' => '01-01-2024 10:00:00',
        ];

        $lead = LeadDomainFactory::create($leadData);

        $this->assertInstanceOf(LeadDomain::class, $lead);
        $this->assertEquals($leadData['id'], $lead->uuid());
        $this->assertEquals($leadData['name'], $lead->name()->value());
        $this->assertEquals($leadData['email'], $lead->email()->value());
        $this->assertEquals($leadData['phone'], $lead->phone()->value());
        $this->assertEquals($leadData['createdAt'], $lead->getCreatedAt());
    }

    public function testAddScoreToLead()
    {
        $lead = LeadDomainFactory::create();
        $scoringRepositoryMock = $this->createMock(LeadScoringRepositoryInterface::class);

        $expectedScore = 85;
        $scoringRepositoryMock
            ->method('calculate')
            ->willReturn($expectedScore);

        $lead->calculateMyScoring($scoringRepositoryMock);

        $this->assertEquals($expectedScore, $lead->score());
    }

    public function testGenerateClientFromLead()
    {
        $lead = LeadDomainFactory::create();
        $clientRepositoryMock = $this->createMock(ClientRepositoryInterface::class);
        $expectedClientDomain = $this->createMock(ClientDomain::class);

        $clientRepositoryMock
            ->method('create')
            ->with($this->equalTo($lead))
            ->willReturn($expectedClientDomain);

        $clientDomain = $lead->generateClient($clientRepositoryMock);

        $this->assertInstanceOf(ClientDomain::class, $clientDomain);
    }
}

