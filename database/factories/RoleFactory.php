<?php

// database/factories/RoleFactory.php
namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition()
    {
        return [
            'nama_role' => $this->faker->unique()->word, // Nama role yang acak
        ];
    }
}
