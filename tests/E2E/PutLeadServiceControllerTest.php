<?php
namespace Tests\E2E;

use App\Models\Lead;
use Microservice\Domain\Shared\ValueObject\Uuid;
use Tests\TestCase;

class PutLeadServiceControllerTest extends TestCase
{
    // use RefreshDatabase;

    public function testUpdateLead()
    {
        $faker = \Faker\Factory::create();

        $originalName = $faker->name;
        $originalEmail = $faker->unique()->safeEmail;

        $lead = Lead::create([
             'name' => $originalName,
            'email' => $originalEmail,
            'phone' => '666777888',
        ]);

        $updateData = [
             'name' => $faker->name,
            'email' => $faker->unique()->safeEmail,
            'phone' => '666777881',
        ];

        $response = $this->putJson("/api/lead/$lead->id", $updateData);
        $response->assertStatus(200);

        $response->assertJson([
            'message' => 'Lead updated correctly',
        ]);

        $this->assertDatabaseHas('leads', [
            'id' => $lead->id,
            'name' => $updateData['name'],
            'email' => $updateData['email'],
            'phone' => $updateData['phone'],
        ]);

        $this->assertDatabaseMissing('leads', [
            'id' => $lead->id,
            'name' => $originalName,
            'email' => $originalEmail,
        ]);
    }

public function testUpdateLeadNotFound()
{
    $faker = \Faker\Factory::create();

    $nonExistingId = Uuid::random();

    $updateData = [
         'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'phone' => '666777881',
    ];

    $response = $this->putJson("/api/lead/{$nonExistingId}", $updateData);

    $response->assertStatus(404);

    $this->assertDatabaseMissing('leads', [
        'id' => $nonExistingId
    ]);
}
}
