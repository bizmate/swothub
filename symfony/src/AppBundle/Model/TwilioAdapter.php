<?php
/**
 * Created by PhpStorm.
 * User: bizmate
 * Date: 28/06/15
 * Time: 06:36
 */

namespace AppBundle\Model;
use Services_Twilio;


class TwilioAdapter {

    private $twilioService;
    const ACCOUNT_SID = 'ACef93c62442b3eb680a86351aeeeaef71';
    const AUTH_TOKEN = 'a3eb069293cb2395657a60905bc51973';
    const TWILIO_NUM = '+447481340499';

    public function __construct()
    {
        $this->twilioService =  new Services_Twilio(self::ACCOUNT_SID, self::AUTH_TOKEN);
    }

    public function sendMsg(
        $to,
        $msgUrl = 'http://bit.ly/1ICN4Df'
    )
    {
        $msg = 'Hello from Maverick. Your friend Jon shared this trip with you ' . $msgUrl . ' :)';

        $message = $this->twilioService->account->messages->sendMessage(
            self::TWILIO_NUM, // From a valid Twilio number
            $to, // Text this number
            $msg
        );

        return $message->sid;
    }

}