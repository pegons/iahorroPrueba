<?php
namespace Tests\E2E;

use App\Models\Lead;
use Tests\TestCase;

class DeleteLeadServiceControllerTest extends TestCase
{
    // use RefreshDatabase;

    public function testDeleteLead()
    {
        $faker = \Faker\Factory::create();

        $lead = Lead::create([
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'phone' => '666777888',
        ]);

        $response = $this->deleteJson("/api/lead/{$lead->id}");
        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Lead deleted successfully',
        ]);
    }
}
