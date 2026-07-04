<?php

namespace App\Services;

use App\Models\User;
use Carbon\Carbon;

class OtpService
{
    public function send(User $user): void
    {
        $user->update([
            'otp' => '123456',
            'otp_expires_at' => Carbon::now()->addMinutes(5),
        ]);

        // لا ترسل SMS أثناء التطوير
    }
}