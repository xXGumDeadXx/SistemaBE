<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Str;
use App\Models\Perfil;
use App\Models\Sede;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Persona>
 */
class PersonaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $generos = ['Masculino', 'Femenino', 'Otro'];
        $edad = fake()->numberBetween(16, 60);
        $fechaNacimiento =fake()->dateTimeBetween('-' . $edad .  'years','-'. $edad . 'years + 11 months')->format('Y-m-d');
        return [
            'nombre_persona' => fake()->firstName(),
            'segundo_nombre_persona' => fake()->firstName(),
            'apellido_persona' => fake()->lastName(),
            'segundo_apellido_persona' => fake()->lastName(),
            'cedula_persona'=> fake()->unique()->numerify('#########'), // genera un numero unico y aleatorio
            'telefono_persona' => fake()->numerify('04#########'), // genera un numero de 11 digitos q comienza con 04
            'genero_persona' => fake()->randomElement($generos),
            'edad_persona' => $edad,
            'fecha_nacimiento_persona' => $fechaNacimiento,
            'email_persona' => Str::random(10) . '@example.com',
            'regis_patria' => fake()->boolean(),
            'id_perfil' => Perfil::inRandomOrder()->first()->id ?? 1, // obtiene un id existente o 1 si no hay 
            'id_sede' => Sede::inRandomOrder()->first()->id ?? 1, // obtiene un id existente o 1 si no hay
        ];
    }
}
