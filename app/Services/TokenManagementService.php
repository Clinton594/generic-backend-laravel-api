<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use App\Helpers\Response;
use App\Mail\OTPTokenMail;
use App\Models\TokenVerification;
use App\Services\Notify\Logger;
use App\Services\Notify\MailService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class TokenManagementService
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function list($request)
    {
        $Response = new Response();
        $response = $Response::get();
        $limit = intval($request->limit) ?? config('data.paginate10');
        $user = Auth::user();

        try {
            $data = TokenVerification::where('user_id', $user->id)->latest()->simplePaginate($limit, ['*'], 'page');
            $response = $Response::set(['data' => $data], true);
        } catch (\Throwable $th) {
            $response = $Response::set(['message' => $th->getMessage()]);
        }

        return $response;
    }

    // Creates a new token for a user and decides if to send to the user or not
    private static function createToken(User $user, string $tokenFor): TokenVerification
    {
        // 4 digit token for email verification while 6 for others
        $otp = $tokenFor === config("data.tokenFor.emailVerification") ? rand(1000, 9999) : rand(100000, 999999);
        $token = TokenVerification::create([
            'user_id' => $user->id,
            'tokenFor' => $tokenFor,
            "OTP" => $otp
        ]);

        $otp = $tokenFor === 'PASSWORD_RESET' ? $token->id : $otp;
        return $token;
    }

    // Resend An Existing token or Create a new one
    public static function sendToken(User $user, string $tokenFor, bool $pushToken = false): int|string|object
    {
        $token = TokenVerification::where([
            ['user_id', $user->id],
            ['tokenFor', $tokenFor],
            ['is_used', false],
        ])
            ->where('expires_at', '>=', Carbon::now()->format(config('data.dateFullFormat')))
            ->latest()
            ->first();

        // send the id for PASSWORD_RESET and OTP for others
        if ($token) {
            $otp =  $token->tokenFor === 'PASSWORD_RESET' ? $token->id : $token->OTP;
            $token->update([
                'expires_at' => Carbon::now()->addMinute(config('data.tokenExpiration'))->format(config('data.dateFullFormat'))
            ]);
        } else
            $token = self::createToken($user, $tokenFor);

        if ($pushToken) return (new TokenManagementService($user))->pushToken($token);

        $otp =  $token->tokenFor === 'PASSWORD_RESET' ? $token->id : $token->OTP;
        return $otp;
    }

    // Push token to the user's email
    private function pushToken(TokenVerification $token): object
    {

        $key = 'send-token:' . $this->user->id . strtoupper($token->tokenFor);

        // Push otp only once in 2 minutes
        return RateLimiter::attempt(
            $key,
            1, //One email every two minutes
            function () use ($token) {
                $subject = ucwords(toSentence($token->tokenFor));
                Logger::created($token);

                $message = array_merge([
                    "to_name" => "{$this->user->first_name} {$this->user->last_name}",
                    "subject" => $subject,
                    'otp' => $token->tokenFor === config("data.tokenFor.passwordReset") ? $token->id : $token->OTP,
                ]);

                return MailService::send($this->user->email, new OTPTokenMail($message), false);
            },
            120, //Reset limit every 2 mins
        );
    }

    // Verify an OTP sent to a suer
    public static function verifyOTP(User $user, string $otp): object
    {
        $Response = new Response();

        $token = TokenVerification::where([
            ['user_id', $user->id],
            ['OTP', $otp],
            ['is_used', false],
        ])
            ->where('expires_at', '>=', Carbon::now()->format(config('data.dateFullFormat')))
            ->first();

        if ($token)
            $token->update(['is_used' => true]);
        else
            return $Response::set(["message" => 'Invalid OTP, try requesting a new one', 'code' => 'bad_request']);

        return $Response::set([], true);
    }
}
