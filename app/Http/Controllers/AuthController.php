<?php

namespace App\Http\Controllers;

use App\Models\Affiliate;
use App\Models\Cashout;
use App\Models\Discount;
use App\Models\LangkahKecilAnak;
use App\Models\Plan;
use App\Models\Subscribe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    private function userResponse(User $user): array
    {
        $user->load('subscribe.plan');
        $subscribe = $user->subscribe;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $user->role,
            'affiliate_code' => $user->affiliate_code,
            'affiliate_reff' => $user->affiliate_reff,
            'affiliate_reff_nama' => $user->affiliate_reff ? User::where('affiliate_code', $user->affiliate_reff)->value('name') : null,
            'komisi' => $user->komisi(),
            'rekening_nama' => $user->rekening_nama,
            'rekening_bank' => $user->rekening_bank,
            'rekening_nomor' => $user->rekening_nomor,
            'plan' => $subscribe ? [
                'subscribe_id' => $subscribe->subscribe_id,
                'plan_id' => $subscribe->subscribe_id_plan,
                'plan_nama' => $subscribe->plan?->plan_nama,
                'plan_value' => $subscribe->subsribe_value,
                'plan_harga' => $subscribe->subscribe_harga,
                'subscribe_start_at' => $subscribe->subscribe_start_at ? \Carbon\Carbon::parse($subscribe->subscribe_start_at)->toIso8601String() : null,
                'subscribe_end_at' => $subscribe->subscribe_end_at ? \Carbon\Carbon::parse($subscribe->subscribe_end_at)->toIso8601String() : null,
                'subscribe_trial_at' => $subscribe->subscribe_trial_at ? \Carbon\Carbon::parse($subscribe->subscribe_trial_at)->toIso8601String() : null,
            ] : null,
        ];
    }

    private function plansData(): array
    {
        return Plan::where('plan_status', 1)
            ->orderBy('plan_harga')
            ->get()
            ->map(function ($p) {
                $periodEnum = \App\PeriodEnum::tryFrom($p->plan_periode);
                return [
                    'id' => $p->plan_id,
                    'name' => $p->plan_nama,
                    'description' => $p->plan_keterangan,
                    'value' => $p->plan_value,
                    'price' => $p->plan_harga,
                    'fee' => $p->plan_fee,
                    'color' => $p->plan_color,
                    'recommended' => (bool) $p->plan_recomended,
                    'period' => $p->plan_periode,
                    'period_label' => $periodEnum?->description() ?? $p->plan_periode,
                    'interval' => $p->plan_interval,
                ];
            })
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
            'trial_days' => (int) config('langkahkecil.trial_days', 10),
            'plans' => $this->plansData(),
            'discounts' => $this->discountsData(),
            'affiliate_config' => [
                'commission_rate' => (int) config('langkahkecil.affiliate.upgrade_commission_rate', 15),
            ],
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
            'password' => 'required|string|min:6|confirmed',
            'ref' => 'nullable|string|max:30',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $affiliateReff = null;
        if ($request->input('ref')) {
            $referrer = User::where('affiliate_code', $request->input('ref'))->first();
            if ($referrer) {
                $affiliateReff = $request->input('ref');
            }
        }

        $affiliateCode = strtoupper(substr(md5(uniqid($request->email, true)), 0, 8));

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
            'role' => 'trial',
            'affiliate_code' => $affiliateCode,
            'affiliate_reff' => $affiliateReff,
        ]);

        $freePlan = Plan::where('plan_harga', 0)->where('plan_status', 1)->first();
        if ($freePlan) {
            $trialDays = (int) config('langkahkecil.trial_days', 10);
            $subscription = Subscribe::create([
                'subscribe_id_user' => $user->id,
                'subscribe_harga' => 0,
                'subscribe_discount' => 0,
                'subscribe_total' => 0,
                'subscribe_id_plan' => $freePlan->plan_id,
                'subsribe_value' => $freePlan->plan_value ?? 1,
                'subscribe_trial_at' => now(),
                'subscribe_start_at' => now(),
                'subscribe_end_at' => now()->addDays($trialDays),
                'subscribe_created_at' => now(),
            ]);
            $user->update(['plan' => $subscription->subscribe_id]);
        }

        if ($affiliateReff) {
            $referrer = User::where('affiliate_code', $affiliateReff)->first();
            if ($referrer) {
                $registerBonus = (int) config('langkahkecil.affiliate.register_bonus', 500);
                Affiliate::create([
                    'affiliate_id_user' => $referrer->id,
                    'affiliate_id_from_user' => $user->id,
                    'affiliate_tipe' => 'register',
                    'affiliate_jumlah' => $registerBonus,
                    'affiliate_catatan' => "Bonus referral: " . $user->name . " bergabung",
                    'affiliate_status' => 'pending',
                    'affiliate_created_at' => now(),
                    'affiliate_updated_at' => now(),
                ]);
            }
        }

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
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = $request->user();

        if (! Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Password lama salah'], 422);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password berhasil diubah']);
    }

    public function updateAffiliateCode(Request $request)
    {
        $request->validate([
            'affiliate_code' => 'required|string|min:4|max:20|alpha_dash',
        ]);

        $user = $request->user();
        $code = strtoupper($request->affiliate_code);

        $exists = User::where('affiliate_code', $code)->where('id', '!=', $user->id)->exists();
        if ($exists) {
            return response()->json(['message' => 'Kode sudah digunakan orang lain'], 422);
        }

        $user->update(['affiliate_code' => $code]);

        return response()->json([
            'user' => $this->userResponse($user),
        ]);
    }

    public function referralList(Request $request)
    {
        $user = $request->user();

        $referrals = User::where('affiliate_reff', $user->affiliate_code)
            ->select('id', 'name', 'email', 'role', 'created_at')
            ->orderByDesc('created_at')
            ->limit(50)
            ->get()
            ->map(fn ($u) => [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $this->maskEmail($u->email),
                'role' => $u->role,
                'joined_at' => $u->created_at->toIso8601String(),
            ]);

        $earnings = Affiliate::where('affiliate_id_user', $user->id)->get();

        $totalEarning = $earnings->sum('affiliate_jumlah');
        $totalRegister = $earnings->where('affiliate_tipe', 'register')->sum('affiliate_jumlah');
        $totalUpgrade = $earnings->where('affiliate_tipe', 'upgrade')->sum('affiliate_jumlah');
        $pendingEarning = $earnings->where('affiliate_status', 'pending')->sum('affiliate_jumlah');

        return response()->json([
            'total' => $referrals->count(),
            'referrals' => $referrals,
            'komisi' => $user->komisi(),
            'earnings' => [
                'total' => $totalEarning,
                'register' => $totalRegister,
                'upgrade' => $totalUpgrade,
                'pending' => $pendingEarning,
            ],
            'rates' => [
                'register_bonus' => (int) config('langkahkecil.affiliate.register_bonus', 500),
                'commission_rate' => (int) config('langkahkecil.affiliate.upgrade_commission_rate', 15),
                'commission_bonus' => (int) config('langkahkecil.affiliate.upgrade_commission_bonus', 1000),
            ],
            'cashout' => [
                'minimum' => (int) config('langkahkecil.cashout.minimum', 50000),
                'admin_rate' => (int) config('langkahkecil.cashout.admin_rate', 3),
            ],
            'banks' => config('langkahkecil.banks', []),
        ]);
    }

    public function updateRekening(Request $request)
    {
        $request->validate([
            'rekening_nama' => 'required|string|max:100',
            'rekening_bank' => 'required|string|max:50',
            'rekening_nomor' => 'required|string|max:30',
        ]);

        $user = $request->user();
        $data = $request->only(['rekening_nama', 'rekening_bank', 'rekening_nomor']);

        $user->update($data);

        return response()->json([
            'user' => $this->userResponse($user),
        ]);
    }

    public function requestCashout(Request $request)
    {
        $minimum = (int) config('langkahkecil.cashout.minimum', 20000);
        $adminRate = (int) config('langkahkecil.cashout.admin_rate', 3);

        $request->validate([
            'amount' => "required|integer|min:{$minimum}",
        ]);

        $user = $request->user();
        $adminFee = (int) round($request->amount * $adminRate / 100);
        $totalDeduct = $request->amount + $adminFee;

        if ($user->komisi() < $totalDeduct) {
            return response()->json(['message' => 'Saldo komisi tidak mencukupi (termasuk platform fee)'], 422);
        }

        if (!$user->rekening_nama || !$user->rekening_bank || !$user->rekening_nomor) {
            return response()->json(['message' => 'Lengkapi data rekening terlebih dahulu'], 422);
        }

        $received = $request->amount;

        $cashout = Cashout::create([
            'cashout_id_user' => $user->id,
            'cashout_jumlah' => $request->amount,
            'cashout_admin_fee' => $adminFee,
            'cashout_diterima' => $received,
            'cashout_rekening_bank' => $user->rekening_bank,
            'cashout_rekening_nomor' => $user->rekening_nomor,
            'cashout_rekening_nama' => $user->rekening_nama,
            'cashout_status' => 'pending',
            'cashout_created_at' => now(),
            'cashout_updated_at' => now(),
        ]);

        return response()->json([
            'message' => 'Permintaan pencairan berhasil diajukan. Maksimal 1 hari kerja.',
            'komisi' => $user->komisi(),
            'cashout' => $cashout,
        ]);
    }

    public function cashoutList(Request $request)
    {
        $cashouts = Cashout::where('cashout_id_user', $request->user()->id)
            ->orderByDesc('cashout_created_at')
            ->limit(20)
            ->get();

        return response()->json(['cashouts' => $cashouts]);
    }

    private function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) return $email;

        $local = $parts[0];
        $domain = $parts[1];

        $localLen = strlen($local);
        if ($localLen <= 2) {
            $maskedLocal = $local . '***';
        } else {
            $maskedLocal = substr($local, 0, 2) . str_repeat('*', min($localLen - 2, 5));
        }

        return $maskedLocal . '@' . $domain;
    }
}
