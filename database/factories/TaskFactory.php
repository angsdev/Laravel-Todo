<?php

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory {

  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected $model = Task::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function definition() {

    return [
      'user_id' => User::all()->random()->id,
      'title' => $this->faker->title(),
      'description' => $this->faker->paragraph(30),
      'created_at' => now(),
      'updated_at' => now(),
    ];
  }
}
