<?php

namespace App\Services\Notify;

use App\Mail\NotificationMail;
use App\Mail\EmailVerified;
use App\Mail\LectureMail;
use App\Mail\PasswordResetMail;
use App\Mail\WelcomeMail;
use App\Models\Company;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;

class NotifyService
{
    private static $template;

    public static function Notify(User|Collection $users, array $message, array $channels = [])
    {
        $channel = object(config('data.notifyChannels'));

        // Convert one user to a collection of users
        if (!$users instanceof Collection) {
            $users = collect([$users]);
        }

        // send default in-app notification to the users collection
        $users->map(fn ($user) => Notification::create([
            'user_id' => $user->id,
            'title' => $message['subject'],
            'message' => $message['body']
        ]));



        // SMS NOFITICATION
        if (in_array($channel->sms, array_keys($channels))) {
            self::notifyBySMS($users, $message);
        }

        // EMAIL NOFITICATION
        if (in_array($channel->email, array_keys($channels))) {
            self::$template = $channels[$channel->email];
            self::notifyByEmail($users, $message);
        }
    }


    private static function notifyByEmail(Collection $users, array $message)
    {
        $company = Company::get();

        // send email to ther first user
        $user = $users->first();

        // Build copy emails if users are more than 1

        $cc = $users->count() > 1 ? $users->pluck('email')->toArray() : [];

        $message = array_merge($message, [
            'to_name' => $users->count() > 1 ? "{$company->name} user" : "{$user->first_name} {$user->last_name}",
            'company' => $company,
        ]);

        switch (self::$template) {
            case 'email.welcome':
                $template = new WelcomeMail($message);
                break;

            case 'email.verification-success':
                $template = new EmailVerified($message);
                break;

            case 'email.notify':
                $template = new NotificationMail($message);
                break;

            case 'email.password-reset-success':
                $template = new PasswordResetMail($message);
                break;

            case 'email.lecture':
                $template = new LectureMail($message);
                break;
            default:
                $template = new NotificationMail($message);
                break;
        }

        MailService::send($user->email, $template, true, $cc);
    }

    private static function notifyBySMS(Collection $users, array $message)
    {
    }
}
