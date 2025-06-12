<?php
class Telegram {
    private $token;
    private $apiUrl;

    public function __construct($token) {
        $this->token = $token;
        $this->apiUrl = "https://api.telegram.org/bot{$token}/";
    }

    public function sendMessage($chat_id, $text) {
        $this->post('sendMessage', ['chat_id' => $chat_id, 'text' => $text]);
    }

    public function sendMenu($chat_id, $text) {
        $keyboard = [
            'keyboard' => [
                ['💼 Fund','👜 Working'],
                ['💰 Profit','📊 Business'],
                ['🔢 Team Count','🧩 Sponsor Count'],
                ['💳 Self Investment','🏅 My Rank'],
                ['👤 Profile','🔗 Referral Link'],
                ['📢 Join Our Channel']
              
            ],
            'resize_keyboard' => true,
            'one_time_keyboard' => false
        ];

        $this->post('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_markup' => json_encode($keyboard)
        ]);
    }

    public function sendLinkButton($chat_id, $title, $text, $url) {
        $keyboard = [
            'inline_keyboard' => [[['text' => $title, 'url' => $url]]]
        ];

        $this->post('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
            'reply_markup' => json_encode($keyboard)
        ]);
    }

    private function post($method, $data) {
        $ch = curl_init($this->apiUrl . $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_exec($ch);
        curl_close($ch);
    }
}
