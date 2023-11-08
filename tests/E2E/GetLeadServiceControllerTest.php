<?php

namespace Tests\E2E;

use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GetLeadServiceControllerTest extends TestCase
{
    //use RefreshDatabase;

    private $lead;

    protected function setUp(): void
    {
        $faker = \Faker\Factory::create();
        parent::setUp();

        // Create a lead before each test
        $this->lead = Lead::create([
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'phone' => '1234567890',
        ]);
    }

    public function testGetLead()
    {
        $response = $this->get("/api/lead/{$this->lead->id}");

        $response->assertStatus(200);

        $data = json_decode($response->getContent(), true);
        $this->assertEquals($this->lead->name, $data['name']);
        $this->assertEquals($this->lead->email, $data['email']);
        $this->assertEquals($this->lead->phone, $data['phone']);
    }

    public function testGetLeadFail()
    {
        $nonExistingId = '9d7e2220-15fc-43a8-ae83-cb4101aa3e49';

        $response = $this->get("/api/lead/{$nonExistingId}");

        $response->assertStatus(404);
    }

    protected function tearDown(): void
    {
        if ($this->lead) {
            $this->lead->delete();
        }

        parent::tearDown();
    }
}
