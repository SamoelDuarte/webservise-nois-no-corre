<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class MobileAuthController extends Controller
{
    public function register(Request $request): JsonResponse
    {
        $this->normalizeEmail($request);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'string', 'email:rfc', 'max:254', 'unique:users,email'],
            'password' => ['bail', 'required', 'string', 'min:8', 'confirmed', $this->validPasswordBytes(...)],
        ], ['email.unique' => 'Este e-mail já está cadastrado.']);

        try {
            return DB::transaction(fn () => $this->session(User::create($data), 201));
        } catch (UniqueConstraintViolationException $exception) {
            if (! User::where('email', $data['email'])->exists()) {
                throw $exception;
            }

            throw ValidationException::withMessages(['email' => 'Este e-mail já está cadastrado.']);
        }
    }

    public function login(Request $request): JsonResponse
    {
        $this->normalizeEmail($request);

        $data = $request->validate([
            'email' => ['required', 'string', 'email:rfc', 'max:254'],
            'password' => ['bail', 'required', 'string', $this->validPasswordBytes(...)],
        ]);

        $user = User::where('email', $data['email'])->first();

        if (! $user || $user->is_admin || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'E-mail ou senha inválidos.'], 401);
        }

        return $this->session($user);
    }

    public function me(Request $request): JsonResponse
    {
        abort_if($request->user()->is_admin, 403);

        return response()->json(['user' => $this->profile($request->user())]);
    }

    public function logout(Request $request): Response
    {
        $request->user()->currentAccessToken()->delete();

        return response()->noContent();
    }

    private function normalizeEmail(Request $request): void
    {
        if (is_string($request->input('email'))) {
            $request->merge(['email' => mb_strtolower(trim($request->input('email')))]);
        }
    }

    private function validPasswordBytes(string $attribute, string $value, \Closure $fail): void
    {
        if (strlen($value) > 72 || str_contains($value, "\0")) {
            $fail('A senha deve ter até 72 bytes e usar caracteres válidos.');
        }
    }

    private function session(User $user, int $status = 200): JsonResponse
    {
        $token = $user->createToken('Nois no Corre Android', ['*'], now()->addDays(30));

        return response()->json([
            'token' => $token->plainTextToken,
            'user' => $this->profile($user),
        ], $status)->header('Cache-Control', 'no-store');
    }

    private function profile(User $user): array
    {
        return $user->only(['id', 'name', 'email']);
    }
}
