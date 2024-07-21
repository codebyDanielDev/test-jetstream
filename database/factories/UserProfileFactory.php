<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\UserProfile>
 */
class UserProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
              // 90% probability for current month and day
              $birthdate = $this->faker->boolean(90) ? $this->faker->dateTimeThisMonth() : $this->faker->date();

              return [
                'user_id' => \App\Models\User::factory(),
                'cover_photo_path' => $this->faker->imageUrl(),
                'bio' => $this->faker->paragraph(),
                'birthdate' => $birthdate,
                'gender' => $this->faker->randomElement(['male', 'female', 'other']),
                'address' => $this->faker->address(),
                'city' => $this->faker->city(),
                'state' => $this->faker->state(),
                'country' => $this->faker->country(),
                'zip_code' => $this->faker->postcode(),
                'website' => $this->faker->url(),
                'social_profiles' => json_encode([
                    'facebook' => $this->faker->url(),
                    'twitter' => $this->faker->url(),
                    'linkedin' => $this->faker->url(),
                ]),
                'preferred_language' => 'es',
                'email_notifications' => $this->faker->boolean(),
                'sms_notifications' => $this->faker->boolean(),
                'whatsapp_notifications' => $this->faker->boolean(),
                'profile_visibility' => $this->faker->randomElement(['public', 'private']),
                'occupation' => $this->faker->jobTitle(),
                'company' => $this->faker->company(),
                'education' => $this->faker->sentence(),
                'identity_document_type' => $this->faker->randomElement(['DNI', 'passport']),
                'identity_document_number' => $this->faker->randomNumber(8),
            ];
    }
}
