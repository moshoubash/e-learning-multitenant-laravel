<?php

namespace Database\Factories;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Stancl\Tenancy\UUIDGenerator;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Tenant::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company();

        return [
            'id' => UUIDGenerator::generate(),
            'name' => $name,
            'data' => [
                'status' => true,
                'plan' => 'basic',
                'created_by' => null,
            ],
        ];
    }

    /**
     * Indicate that the tenant is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn(array $attributes) => [
            'data' => array_merge($attributes['data'] ?? [], ['status' => false]),
        ]);
    }

    /**
     * Indicate that the tenant has a premium plan.
     */
    public function premium(): static
    {
        return $this->state(fn(array $attributes) => [
            'data' => array_merge($attributes['data'] ?? [], ['plan' => 'premium']),
        ]);
    }
}
