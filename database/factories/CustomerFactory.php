<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Customer>
 */
class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'customer_id' => 'CUST-'.fake()->unique()->randomNumber(4),
            'email' => fake()->unique()->safeEmail(),
            'mobile' => fake()->numerify('##########'),
            'address' => fake()->address(),
            'area' => fake()->randomElement(['Route A', 'Route B', 'Route C']),
            'start_date' => fake()->date(),
            'status' => 'Active',
        ];
    }
}
