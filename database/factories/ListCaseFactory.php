<?php

namespace Database\Factories;

use App\Models\ListCase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ListCase>
 */
class ListCaseFactory extends Factory
{
    protected $model = ListCase::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => $this->faker->boolean(1) ? $this->faker->numberBetween(1, 5) : null,
            'user_id' => $this->faker->numberBetween(1, 5),
            'title' => $this->faker->jobTitle,
            'description' => $this->faker->text,
            'pvc' => json_encode([
                'reduce_thickness' => $this->faker->boolean,
                'size' => (string) $this->faker->numberBetween(1, 2),
                'color_code' => '#fff',
            ]),
            'stock' => json_encode([
                'sizes' => ['w' => 183, 'h' => 366],
                'qty' => $this->faker->numberBetween(1, 7),
                'color' => 'سفید',
                'pattern' => $this->faker->boolean,
                'material' => 'MDF',
            ]),
            'archived' => $this->faker->boolean(1),
            'viewed' => $this->faker->numberBetween(0, 80),
        ];
    }
}
