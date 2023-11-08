<?php
namespace Database\Factories;

use App\Models\Lead;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class LeadFactory extends Factory
{
    protected $model = Lead::class;

    /**
     * Define el estado predeterminado del modelo.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => (string) Str::uuid(),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->optional()->phoneNumber
        ];
    }
}