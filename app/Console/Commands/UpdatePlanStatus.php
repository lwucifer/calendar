<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Models\Plan;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class UpdatePlanStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plan:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically update status to done when the day passes';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Plan::where('status', Plan::STATUS_CONFIRM)
            ->where('is_deleted', false)
            ->whereDate('date', '<', Helper::getDateTimeWithCarbon()::today()->format('Y-m-d'))
            ->update(['status' => Plan::STATUS_DONE]);
       // $this->manualSendMail();
    }

    private function manualSendMail()
    {
        $plans = Plan::with(['campaign', 'store'])
            ->where('status', Plan::STATUS_CONFIRM)
            ->where('reminder_mail', false)
            ->where('is_deleted', false)
            ->whereIn('id', [35, 872, 69])
            ->get()
            ->toArray();

        foreach ($plans as $plan) {
            $campaign = $plan['campaign'];
            $store = $plan['store'];

            $input['token'] = Str::random(20);
            //prepare for send mail
            $campaign_name = $campaign['web_name'];
            $url = '<a href="' . url('plan/phone/confirm/' . $input['token']) . '">' . url('plan/phone/confirm/' . $input['token']) . '</a><br>';
            $link = '<a href="' . url('/booking/cancel/' . $input['token']) . '" target="_blank">URL</a>';
            $time = $plan['start_time'] . ' - ' . $plan['end_time'];

            $var = array(
                $campaign_name,
                $store['name'],
                $plan['date'],
                $time,
                $url,
                $link,
            );

            $template = 'mail_plan_request_user';

            Helper::sendMailWithMailTemplate($plan['user_email'], $template, $plan['store_id'], $var, $plan['user_name'], $plan['user_phone']);
            Helper::sendMailWithMailTemplate(env('MAIL_ADMIN'), $template, $plan['store_id'], $var, $plan['user_name'], $plan['user_phone']);
        }
    }
}
