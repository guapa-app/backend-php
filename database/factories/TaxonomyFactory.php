<?php

namespace Database\Factories;

use App\Models\Taxonomy;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxonomyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Taxonomy::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title'       => $this->faker->title,
            'description' => $this->faker->paragraph,
            'font_icon'   => null,
            'type'        => 'Products',
            'parent_id'   => null,
            'fees'        => 10,
        ];
    }
}
