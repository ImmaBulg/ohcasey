<?php

namespace App\Support;

use GuzzleHttp\Client;

/**
 * Класс отправки СМС.
 *
 * @package App\Support
 */
class SmsSender
{
    /**
     * SmsSender constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param string $phone
     * @param string $text
     * @return mixed|\Psr\Http\Message\ResponseInterface
     */
    public function send($phone, $text)
    {
        $params = [
            'api_id' => config('sms.api_id'),
            'to'     => $phone,
            'text'   => trim($text),
            'from'   => config('sms.from'),
        ];

        if (env('SMS_DEBUG')) {
            \Log::debug('Sms params:', $params);

            return true;
        } else {
            $client = new Client([
                'base_uri' => config('sms.http_api_base_uri'),
                'timeout' => (float)config('sms.curl_timeout'),
            ]);

            return $client->request(config('sms.http_method'), '', [
                'form_params' => $params,
            ]);
        }
    }
}