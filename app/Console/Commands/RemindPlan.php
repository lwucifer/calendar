<?php

namespace App\Console\Commands;

use App\Helpers\Helper;
use App\Models\MailTemplate;
use App\Models\Plan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemindPlan extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plan:remind';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically send reminder plan mail';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    private function getPlanDate($plan, $type)
    {
        $planDate = $type != 1 ? $plan['date'] : $plan['created_at'];
        return Helper::getDateTimeWithCarbon()::parse($planDate)->hour(0)->minute(0);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $plans = Plan::with(['campaign', 'store'])
            ->where('status', Plan::STATUS_CONFIRM)
            ->where('reminder_mail', false)
            ->where('is_deleted', false)
            ->get();

        if (empty($plans)) {
            return;
        }

        $today = Helper::getDateTimeWithCarbon()::now();
        $base = url('/');
        $plans = $plans->toArray();

        foreach ($plans as $plan) {
            $campaign = $plan['campaign'];
            $store = $plan['store'];

            if (empty($plan['user_email']) || empty($campaign) || empty($store)) {
                continue;
            }

            $url = '<a href="' . $base . '/booking/info/' . $plan['token'] . '" target="_blank">' . $campaign['name'] . '</a><br>';
            $link = '<a href="' . $base . '/booking/cancel/' . $plan['token'] . '" target="_blank">こちら</a>';

            $var = array(
                $plan['start_time'],
                $plan['end_time'],
                $store['name'],
                $campaign['web_name'],
                $url,
                $link
            );

            $count = 0;
            $campaignMails = DB::table('campaign_mail')
                ->where('campaign_id', $plan['campaign_id'])
                ->where('is_deleted', false)
                ->get();

            foreach ($campaignMails as $i) {
                $planDate = $this->getPlanDate($plan, $i->type);
                $diff = $planDate->diffInHours($today);
                $isRunSentMailCancel = false;

                if ($i->action == 1 && $today->lt($planDate) && $diff <= 24 * intval($i->day)) {
                    $isRunSentMailCancel = true;
                }

                if ($i->action == 2 && $today->gt($planDate) && $diff >= 24 * intval($i->day)) {
                    $isRunSentMailCancel = true;
                }

                if ($isRunSentMailCancel) {
                    try {
                        $count++;

                        $template = MailTemplate::where('id', $i->template)
                            ->where('is_deleted', false)
                            ->value('template');

                        Helper::sendMailWithMailTemplate(
                            $plan['user_email'],
                            $template,
                            $store['id'],
                            $var,
                            $plan['user_name'],
                            $plan['user_phone']
                        );
                        Helper::logError('COUNT: '. $count . ' == MAIL TEMPLATE: ' . json_encode($i));
                    } catch (\Exception $e){
                        Helper::logError($e);
                    }
                }
            }

            if ($count)
                Plan::where('id', $plan['id'])->update(['reminder_mail' => true]);

            Helper::logError('PLAN DATA: ' . json_encode([
                    'plan_id' => $plan['id'],
                    'campaign_id' => $plan['campaign_id'],
                    'campaign_code' => $plan['campaign']['code'],
                    'store_id' => $plan['store_id'],
                    'store_code' => $plan['store']['code'],
                    'user_email' => $plan['store_id'],
                    'user_phone' => $plan['user_phone']
            ]));
            //limit email send in a range time
            if ($count > 10) break;
        }
    }

}
