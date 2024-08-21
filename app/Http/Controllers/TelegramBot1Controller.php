<?php

namespace App\Http\Controllers;

use App\Models\Children;
use App\Models\User;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class TelegramBot1Controller extends Controller
{
    /**
     * @throws TelegramSDKException
     */
    public function handle()
    {
        $telegram = new Api(env('TELEGRAM_BOT_TOKEN'));

        // Foydalanuvchidan kelgan ma'lumotlar
        $update = $telegram->getWebhookUpdate();

        // Foydalanuvchi haqidagi ma'lumotlar
        $user_id = $update->getMessage()->getChat()->getId();
        $user_name = $update->getMessage()->getChat()->getFirstName() . ' ' . $update->getMessage()->getChat()->getLastName();

        // Foydalanuvchidan kelgan lokatsiyani olish
        $location = $update->getMessage()->getLocation();

        $telegram->sendMessage([
            'chat_id' => $user_id,
            'text' => "Lokatsiyangizni yuboring, iltimos!",
        ]);
        exit();
        // Foydalanuvchi ID'si boyicha foydalanuvchi ma'lumotlarini olish
        $user = User::updateOrCreate(
            ['telegram_id' => $user_id],
            ['name' => $user_name]
        );

        // Foydalanuvchidan kelgan lokatsiyani tekshirish va unga javob yuborish
        if ($location) {
            // Lokatsiyani tekshirish va javob yuborish
            $response = $this->checkLocation($location->getLatitude(), $location->getLongitude());

            // Foydalanuvchiga javob yuborish
            $telegram->sendMessage([
                'chat_id' => $user_id,
                'text' => $response,
            ]);
        } else {
            // Lokatsiyani so'rash
            $telegram->sendMessage([
                'chat_id' => $user_id,
                'text' => "Lokatsiyangizni yuboring, iltimos!",
            ]);
        }
    }

    /**
     * Farzandning joylashgan joyini tekshirish
     */
    private function checkLocation($latitude, $longitude)
    {
        // Bu joylashgan joy tekshirish funksiyasi, masalan, har bir soatda qo'lda bo'lishi mumkin

        // Sizning logikangizni qo'shing
        // Misol uchun:
        if ($latitude >= 41.0 && $latitude <= 41.5 && $longitude >= 69.0 && $longitude <= 69.5) {
            return "Farzandingizni joylashgan joyida!";
        } else {
            return "Uzr, farzandingizni topa olmadim.";
        }
    }
}
