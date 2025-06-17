<?php

namespace App\Service\Torneo;

class TelegramNotifierService
{
    private string $botToken;
    private string $chatId;

    public function __construct()
    {
        $this->botToken = "7718189388:AAFGWOUtVpWhYQfi_0KAYHk9Hq2uNGc6iuM";
        $this->chatId = "7005366579";
    }

    public function sendMessage(string $message): void
    {
        $url = "https://api.telegram.org/bot{$this->botToken}/sendMessage";
        $data = [
            'chat_id' => $this->chatId,
            'text' => $message,
        ];

        // Usamos cURL para enviar el mensaje
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
        ]);
        curl_exec($ch);
        curl_close($ch);
    }
}
