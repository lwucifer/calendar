<?php


namespace App\Helpers;

use App\Models\MailTemplate;
use App\Models\Store;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class Helper
{
    public static function getRequestJson(Request $re)
    {
        return json_decode($re->getContent(), true);
    }

    public static function convertDateFormaṭ($date)
    {
        $date = new DateTime($date);

        return date_format($date, 'Y') . '年' . date_format($date, 'm') . '月' . date_format($date, 'd') . '日';
    }

    public static function convertTimeFormaṭ($time)
    {
        $current_time = new DateTime('1/1/2019 ' . $time);

        return date_format($current_time, 'H') . '時' . date_format($current_time, 'i') . '分';
    }

    public static function caculateYearOld($date)
    {
        $now = new DateTime();
        $now_time = strtotime(date_format($now, 'y-m-d'));
        $b_time = strtotime($date);
        return floor(($now_time - $b_time) / 31536000);
    }

    public static function roundTimeWork($time, $isStart = true)
    {
        if ($isStart) {
            return $time->minute > 0 ?
                ($time->minute > 30 ? $time->copy()->minute(0)->addHour(1) : $time->copy()->minute(30))
                : $time->copy()->minute(0);
        }

        return $time->minute >= 30 ? $time->copy()->minute(30) : $time->copy()->minute(0);
    }

    public static function getDateTimeWithCarbon($timezone = 'Asia/Tokyo')
    {
        return (new Carbon())->setTimezone($timezone);
    }

    public static function carbonParseTime($time)
    {
        return self::getDateTimeWithCarbon()::parse($time);
    }

    public static function validateDateTime($string, $format = "Y/m/d H:i:s.u")
    {
        $parsed = date_parse_from_format($format, $string);

        if ($parsed['day'] > 31 || $parsed['month'] > 12) {
            return false;
        }

        // if value matches given format return true=validation succeeded
        if ($parsed['error_count'] === 0 && $parsed['warning_count'] === 0) {
            return true;
        }

        return false;
    }

    public static function checkHoliday($day, $format = 'Y-m-d')
    {
        $public_holiday = array(
            '2019-01-01',
            '2019-01-14',
            '2019-02-11',
            '2019-03-21',
            '2019-04-29',
            '2019-04-30',
            '2019-05-01',
            '2019-05-02',
            '2019-05-03',
            '2019-05-04',
            '2019-05-05',
            '2019-05-06',
            '2019-07-15',
            '2019-08-11',
            '2019-08-12',
            '2019-09-16',
            '2019-09-23',
            '2019-10-14',
            '2019-10-22',
            '2019-11-03',
            '2019-11-04',
            '2019-11-23',
            '2020-01-01',
            '2020-01-13',
            '2020-02-11',
            '2020-02-23',
            '2020-02-24',
            '2020-03-20',
            '2020-04-29',
            '2020-05-03',
            '2020-05-04',
            '2020-05-05',
            '2020-05-06',
            '2020-07-23',
            '2020-07-24',
            '2020-08-10',
            '2020-09-21',
            '2020-09-22',
            '2020-11-03',
            '2020-11-23',
            '2021-01-01',
            '2021-01-11',
            '2021-02-11',
            '2021-02-23',
            '2021-03-20',
            '2021-04-29',
            '2021-05-03',
            '2021-05-04',
            '2021-05-05',
            '2021-07-19',
            '2021-08-11',
            '2021-09-20',
            '2021-09-23',
            '2021-10-11',
            '2021-11-03',
            '2021-11-23',
            '2022-01-01',
            '2022-01-10',
            '2022-02-11',
            '2022-02-23',
            '2022-03-21',
            '2022-04-29',
            '2022-05-03',
            '2022-05-04',
            '2022-05-05',
            '2022-07-18',
            '2022-08-11',
            '2022-09-19',
            '2022-09-23',
            '2022-10-10',
            '2022-11-03',
            '2022-11-23');

        // public holiday of japan
        if (in_array($day, $public_holiday))
            return 2;

        $dayOfWeek = date('w', strtotime($day));
        // 0 = sunday, 6 = saturday
        if ($dayOfWeek == 0 || $dayOfWeek == 6) {
            return 2;
        }
        return 1;
    }

    public static function sendMailWithMailTemplate($emailTo, $template, $storeId, $var, $username = '', $phone = '')
    {
        if (empty($emailTo) || empty($template)) {
            return;
        }

        $mailTemplate = MailTemplate::where('template', $template)->where('is_deleted', false)->first();

        if (empty($mailTemplate)) {
            return;
        }

        $subject = $mailTemplate->title;
        $signStore = Store::where('id', $storeId)->first();
        $signStore = empty($signStore) ? '' : $signStore->sign_email;
        $contentMail = $mailTemplate->content;

        if (!empty($username)) {
            $contentMail = str_replace('[@username]', $username, $contentMail);
        }

        if (!empty($phone)) {
            $contentMail = str_replace('[@phone]', $phone, $contentMail);
        }

        $data = explode('[@var]', $contentMail);

        Mail::send('frontend.mail.common', compact('signStore', 'data', 'var', 'username', 'phone'), function ($msg) use ($emailTo, $subject) {
            $msg->to($emailTo, env('MAIL_FROM_NAME'));
            $msg->subject($subject);
        });
    }

    public static function logError($data)
    {
        if (env('LOG_ERROR') == 1) {
            \Log::error($data);
        }
    }

}
