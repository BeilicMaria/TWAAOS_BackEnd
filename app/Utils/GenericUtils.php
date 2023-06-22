<?php

namespace App\Utils;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GenericUtils
{

    /**
     * send an email
     * @param $from
     * @param $to
     * @param $name
     * @param $subject
     * @param $message
     * @param $attachment
     */
    public static function sendMail($from, $to, $cc, $name, $subject, $message, $attachment, $layout, $layoutData = null)
    {
        $layoutData = isset($layoutData) ? $layoutData : array("content" => $message);
        Mail::send($layout, $layoutData, function ($message) use ($attachment, $name, $from, $to, $cc, $subject) {
            $message->from($from, Config::get('app.APP_NAME'));
            $message->to($to)->subject($subject);
            if (isset($cc)) {
                $message->cc($cc);
            }
            if (isset($attachment)) {
                if (is_array($attachment)) {
                    foreach ($attachment as $a) {
                        $message->attach($a);
                    }
                } else $message->attach($attachment);
            }
        });
    }
}
