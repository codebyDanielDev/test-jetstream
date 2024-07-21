<?php
namespace App\Actions\Fortify;

use App\Models\User;
use App\Rules\PhoneNumberRule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use App\Models\UserProfile;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'username' => ['required', 'string', 'max:255', 'unique:users', 'alpha_dash'], // Validación del username
            'phone_number' => ['required', 'string', new PhoneNumberRule($input['country_code']), 'unique:users'],
            'country_code' => ['required', 'string'],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'username' => $input['username'],
            'password' => Hash::make($input['password']),
            'phone_number' => $input['phone_number'],
            'country_code' => $input['country_code'],
        ]);

        // Crear perfil de usuario automáticamente
        UserProfile::create([
            'user_id' => $user->id,
            'profile_visibility' => 'public', // Puedes ajustar el valor predeterminado según tus necesidades
        ]);

        return $user;
    }

}
