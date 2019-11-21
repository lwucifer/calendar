<?php
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'admin',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'id' => 2,
                'name' => 'user',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'id' => 3,
                'name' => 'manager',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'id' => 4,
                'name' => 'customer',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]
        ]);

        DB::table('store')->insert([
            [
                'id' => 1,
                'name' => 'LovePhoto撮影',
                'manager_id' => 1,
                'phone' => '8034569876',
                'weekday_start_time' => "09:00:00",
                'weekday_end_time' => "18:00:00",
                'holiday_start_time' => "09:00:00",
                'holiday_end_time' => "18:00:00",
                'day_off_monday' => false,
                'day_off_tuesday' => false,
                'day_off_wednesday' => false,
                'day_off_thursday' => false,
                'day_off_friday' => false,
                'day_off_saturday' => true,
                'day_off_sunday' => true,
                'fixed_days_off' => "1",
                'fixed_days_on' => "2",
                'last_update_by' => 1,
                'code' => '68127421470ahjsdwetsdidad',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'id' => 2,
                'name' => '安い撮影',
                'manager_id' => 1,
                'phone' => '07035245764',
                'weekday_start_time' => "09:00:00",
                'weekday_end_time' => "18:00:00",
                'holiday_start_time' => "09:00:00",
                'holiday_end_time' => "18:00:00",
                'day_off_monday' => false,
                'day_off_tuesday' => false,
                'day_off_wednesday' => false,
                'day_off_thursday' => false,
                'day_off_friday' => false,
                'day_off_saturday' => true,
                'day_off_sunday' => true,
                'fixed_days_off' => "1",
                'fixed_days_on' => "2",
                'last_update_by' => 1,
                'code' => '1559207753.593zz83qxMARf3PokwEubeU',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ],
            [
                'id' => 3,
                'name' => 'Smile撮影',
                'manager_id' => 1,
                'phone' => '9024687531',
                'weekday_start_time' => "07:00:00",
                'weekday_end_time' => "18:00:00",
                'holiday_start_time' => "07:00:00",
                'holiday_end_time' => "13:00:00",
                'day_off_monday' => false,
                'day_off_tuesday' => false,
                'day_off_wednesday' => false,
                'day_off_thursday' => false,
                'day_off_friday' => false,
                'day_off_saturday' => true,
                'day_off_sunday' => true,
                'fixed_days_off' => "1",
                'fixed_days_on' => "2",
                'last_update_by' => 1,
                'code' => '1559208161.605sjDHNO2kvQe6tK8fLoQC',
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
            ]
        ]);

        DB::table('users')->insert([
            [
                'id' => 1,
                'username' => 'admin999',
                'kana_first_name' => 'カンリ',
                'kana_last_name' => 'カンリ',
                'first_name' => 'admin',
                'last_name' => 'admin',
                'email' => 'admin@admin.vn',
                'password' => bcrypt('12345678aB$'),
                'updated_password_at' => Carbon::now()->toDateTimeString(),
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString(),
                'setting_expire_password' => 180,
                'role_id' => 1,
                'store_id' => 1,
                'phone' => '9046503959',
                'parent_user' => 1,
                'last_update_by' => 1,
                'address1' => '',
                'address2' => '',
                'comment' => 'This is account admin',
                'remember_token' => '10000'
            ]
        ]);

        DB::table('f_numbers')->insert([
            [
                'id' => 1,
                'name' => 'admin_f_id',
                'memo' => 'auto crete',
                'user_id' => 1,
                'created_at' => Carbon::now()->toDateTimeString(),
                'updated_at' => Carbon::now()->toDateTimeString()
            ]
        ]);

        $this->call(ManagerTableSeeder::class);
        $this->call(CashDepartTableSeeder::class);
        $this->call(PhotoTableSeeder::class);
        $this->call(TaxTableSeeder::class);
        $this->call(CampaignTableSeeder::class);
        $this->call(MailTemplateSeederTable::class);
        $this->call(DeleteTestDB::class);
    }
}
