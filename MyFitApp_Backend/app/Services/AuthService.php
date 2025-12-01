<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    public function __construct(
        protected UserRepository $userRepository
    ) {}

    public function register(array $data): array
    {
        $user = $this->userRepository->create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => $data['password'],
        ]);

        $token = $user->createToken('myfitapp_token')->plainTextToken;

        return compact('user', 'token');
    }

    public function login(array $credentials): array
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->tokens()->delete();

        $token = $user->createToken('myfitapp_token')->plainTextToken;

        return compact('user', 'token');
    }

    public function me(User $user): User
    {
        return $user;
    }

    public function logout(User $user): void
    {
        $user->tokens()->delete();
    }
}
