<?php
namespace Tests\E2E;

use Tests\TestCase;

class PostLeadServiceControllerTest extends TestCase
{
    //RefreshDatabase

    public function testCreateLead()
    {
        $faker = \Faker\Factory::create();

        $postData = [
            'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'phone' => '666777888',
        ];

        $response = $this->postJson('/api/lead', $postData);
        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'Client created correctly',
        ]);

        $this->assertDatabaseHas('leads', $postData);
    }
}

