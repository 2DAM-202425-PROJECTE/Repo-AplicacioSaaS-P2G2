<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validar i actualitzar la informació del perfil de l'usuari.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        // Validació de l'entrada
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'empresa' => ['nullable', 'boolean', 'max:255'], //validació per a l'empresa
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        // Actualització de la foto si existeix
        if (isset($input['photo'])) {
            $user->updateProfilePhoto($input['photo']);
        }

        // Actualitzar l'usuari
        $user->forceFill([
            'name' => $input['name'],
            'email' => $input['email'],
            'empresa' => $input['empresa'], // Actualitzar empresa
        ])->save();
    }
}
