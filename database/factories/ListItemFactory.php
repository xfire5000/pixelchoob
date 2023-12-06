<?php

namespace Database\Factories;

use App\Models\ListItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ListItem>
 */
class ListItemFactory extends Factory
{
    protected $model = ListItem::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $grooveGenerator = [
            'l' => $this->faker->boolean,
            'w' => $this->faker->boolean,
        ];

        $gazorHingeGenerator = [
            'l' => ! $grooveGenerator['l'] ? $this->faker->boolean : false,
            'w' => ! $grooveGenerator['w'] ? $this->faker->boolean : false,
        ];

        $chamferGenerator = [
            'l1' => ! $gazorHingeGenerator['l'] ? $this->faker->boolean : false,
            'l2' => ! $gazorHingeGenerator['l'] ? $this->faker->boolean : false,
            'w1' => ! $gazorHingeGenerator['w'] ? $this->faker->boolean : false,
            'w2' => ! $gazorHingeGenerator['w'] ? $this->faker->boolean : false,
        ];

        $pvcGenerator = [
            'l1' => ! $chamferGenerator['l1'],
            'l2' => ! $chamferGenerator['l2'],
            'w1' => ! $chamferGenerator['w1'],
            'w2' => ! $chamferGenerator['w2'],
        ];

        return [
            'list_case_id' => $this->faker->numberBetween(1, 10),
            'description' => $this->faker->boolean ? $this->faker->text : null,
            'chamfer' => json_encode($chamferGenerator),
            'gazor_hinge' => json_encode($gazorHingeGenerator),
            'groove' => json_encode($grooveGenerator),
            'pvc' => json_encode($pvcGenerator),
            'qty' => $this->faker->numberBetween(1, 20),
            'dimensions' => json_encode([
                'w' => $this->faker->numberBetween(10, 366),
                'h' => $this->faker->numberBetween(10, 180),
            ]),
        ];
    }
}
