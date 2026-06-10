<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\LangkahKecilAnak;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private function userResponse(User $user): array
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'trial_start_date' => $user->trial_start_date?->toIso8601String(),
        ];
    }

    private function plansData(): array
    {
        return Plan::all()
            ->map(fn ($plan) => [
                'plan_id' => $plan->plan_id,
                'plan_nama' => $plan->plan_nama,
                'plan_keteranan' => $plan->plan_keteranan,
                'plan_harga' => $plan->plan_harga,
                'plan_fee' => $plan->plan_fee,
                'plan_periode' => $plan->plan_periode,
                'plan_interval' => $plan->plan_interval,
            ])
            ->toArray();
    }

    private function discountsData(): array
    {
        return Discount::where('discount_active', true)
            ->where(function ($q) {
                $q->whereNull('discount_start')->orWhere('discount_start', '<=', now());
            })
            ->where(function ($q) {
                $q->whereNull('discount_end')->orWhere('discount_end', '>=', now());
            })
            ->get()
            ->map(fn ($d) => [
                'code' => $d->discount_code,
                'name' => $d->discount_nama,
                'type' => $d->discount_type,
                'value' => $d->discount_value,
                'min_transaction' => $d->discount_min_transaction,
                'max_amount' => $d->discount_max_amount,
            ])
            ->toArray();
    }

    private function appConfig(): array
    {
        return [
            'server_date' => now()->toIso8601String(),
            'trial_days' => (int) env('LANGKAHKECIL_TRIAL_DAYS', 10),
            'plans' => $this->plansData(),
            'discounts' => $this->discountsData(),
        ];
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'The provided credentials are incorrect.',
            ], 421);
        }

        $token = $user->createToken('api_token')->plainTextToken;

        $anakList = LangkahKecilAnak::where('user_id', $user->id)
            ->with(['skills', 'completedSkills', 'challenges', 'challengeHistory', 'checklists', 'schedules', 'worksheets'])
            ->get();

        return response()->json(array_merge([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $this->userResponse($user),
            'anak_list' => $anakList,
        ], $this->appConfig()));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'role' => 'trial',
            'trial_start_date' => now(),
        ]);

        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json(array_merge([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $this->userResponse($user),
            'anak_list' => [],
        ], $this->appConfig()), 201);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'sometimes|string|max:20|unique:users,phone,'.$user->id,
        ]);

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
            $user->email_verified_at = null;
        }
        if ($request->has('phone')) {
            $user->phone = $request->phone;
        }

        $user->save();

        return response()->json([
            'user' => $this->userResponse($user),
        ]);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json(array_merge([
            'user' => $this->userResponse($user),
        ], $this->appConfig()));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password lama salah'], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password berhasil diubah']);
    }
}
