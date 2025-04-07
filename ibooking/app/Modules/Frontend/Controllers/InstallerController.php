<?php

namespace App\Modules\Frontend\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Artisan;
use Exception;
use Illuminate\Support\Facades\DB;

class InstallerController
{
    private $old_url = [
        'https://data.booteam.co',
        'http://data.booteam.co',
        'https://ibooking.booteam.co',
        'http://ibooking.booteam.co'
    ];

    private function resetData()
    {
        $tables = [
            'gmz_apartment', 'gmz_tour', 'gmz_tour_availability', 'gmz_space', 'gmz_hotel', 'gmz_room', 'gmz_room_availability', 'gmz_apartment_availability', 'gmz_space_availability', 'gmz_car', 'gmz_car_availability', '	gmz_comment', 'gmz_coupon', 'gmz_earnings', 'gmz_language', 'gmz_media', 'gmz_menu', 'gmz_menu_structure', 'gmz_notification', 'gmz_options', 'gmz_order', 'gmz_page', 'gmz_post', 'gmz_term', 'gmz_term_relation', 'gmz_withdrawal', 'gmz_agent', 'gmz_agent_availability', 'gmz_agent_relation', 'gmz_beauty', 'gmz_beauty_availability'
        ];
        foreach ($tables as $table) {
            DB::statement("DELETE FROM {$table}");
        }
    }

    public function checkDatabaseConnection($request)
    {
        $settings = config('database.connections.mysql');
        config([
            'database' => [
                'default' => 'mysql',
                'connections' => [
                    'mysql' => array_merge($settings, [
                        'driver' => 'mysql',
                        'host' => $request->post('db_host', ''),
                        'port' => $request->post('db_port', ''),
                        'database' => $request->post('db_name', ''),
                        'username' => $request->post('db_username', ''),
                        'password' => $request->post('db_password', ''),
                    ]),
                ],
            ],
        ]);
        DB::purge();
        try {
            DB::connection()->getPdo();
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function configDatabaseAction(Request $request)
    {
        $db_name = $request->post('db_name', '');
        $db_username = $request->post('db_username', '');
        $db_password = $request->post('db_password', '');
        $db_host = $request->post('db_host', '');
        $db_port = $request->post('db_port', '');

        if (empty($db_name) || is_null($db_name) || empty($db_username) || is_null($db_username) || empty($db_host) || is_null($db_host) || empty($db_port) || is_null($db_port)) {
            return view('Frontend::installer.config-database', ['message' => 'Please fill all the fields of this form!', 'post_data' => $request->all()]);
        } else {
            $check_connection = $this->checkDatabaseConnection($request);

            if (!$check_connection) {
                return view('Frontend::installer.config-database', ['message' => 'Could not connect to your database. Please check again!', 'post_data' => $request->all()]);
            } else {
                set_env('DB_HOST', $db_host);
                set_env('DB_PORT', $db_port);
                set_env('DB_DATABASE', $db_name);
                set_env('DB_USERNAME', $db_username);
                set_env('DB_PASSWORD', $db_password);

                return redirect()->to('installer/check-database');
            }
        }
    }

    public function stepOneView($step = '')
    {
        try {
            switch ($step) {
                case '':
                default:
                    return $this->welcome();
                    break;
                case 'config-database':
                    return $this->configDatabase();
                    break;
                case 'check-database':
                    return $this->checkDatabase();
                    break;
                case 'import-data':
                    return $this->importData();
                    break;
                case 'not-import-data':
                    return $this->notImportData();
                    break;
            }
        } catch (\PDOException $e) {

        }
    }

    private function welcome()
    {
        $installedFile = storage_path('gmz_imported');
        if (file_exists($installedFile)) {
            return Redirect::to('/')->send();
        }
        return view('Frontend::installer.welcome');
    }

    private function configDatabase()
    {
        $installedFile = storage_path('gmz_imported');
        if (file_exists($installedFile)) {
            return Redirect::to('/')->send();
        }
        return view('Frontend::installer.config-database');
    }

    private function checkDatabase()
    {
        $installedFile = storage_path('gmz_imported');
        if (file_exists($installedFile)) {
            return Redirect::to('/')->send();
        }

        $check_install = true;
        try {
            Artisan::call('migrate', ['--force' => true]);
        } catch (Exception $e) {
            $check_install = false;
        }

        try {
            Artisan::call('db:seed', ['--force' => true]);
        } catch (Exception $e) {
            $check_install = false;
        }

        if (!$check_install) {
            return view('Frontend::installer.config-database', ['message' => 'Have an error when setup database.']);
        } else {
            Artisan::call('storage:link');
            Artisan::call('plugin:link');
            Artisan::call('optimize:clear');
            return view('Frontend::installer.check-database');
        }
    }

    private function insertSQL($filenames)
    {
        if (!empty($filenames)) {
            foreach ($filenames as $file) {
                $sql = file_get_contents(public_path("sql/gmz_" . $file . ".sql"));
                if ($sql) {
                    if ($file == 'menu_structure') {
                        foreach ($this->old_url as $url) {
                            $sql = str_replace($url, url('/'), $sql);
                        }
                    }
                    $statements = array_filter(array_map('trim', explode('INSERT INTO', $sql)));
                    foreach ($statements as $stmt) {
                        if (!empty($stmt)) {
                            DB::insert("INSERT INTO " . $stmt);
                        }
                    }
                }
            }
        }
    }

    private function importData()
    {
        $installed11File = storage_path('gmz_installed');
        if (file_exists($installed11File)) {
            return Redirect::to('/')->send();
        }
        $step = request()->get('step', 1);
        if ($step == 1) {
            try {
                $this->resetData();
            } catch (\Exception $e) {
            }
            $file_names = ['post'];
            $this->insertSQL($file_names);
            return \redirect()->to('installer/import-data?step=2');
        }
        if ($step == 2) {
            $file_names = ['page'];
            $this->insertSQL($file_names);
            return \redirect()->to('installer/import-data?step=3');
        }
        if ($step == 3) {
            $file_names = ['hotel', 'room', 'room_availability'];
            $this->insertSQL($file_names);
            return \redirect()->to('installer/import-data?step=4');
        }
        if ($step == 4) {
            $file_names = ['apartment', 'apartment_availability'];
            $this->insertSQL($file_names);
            return \redirect()->to('installer/import-data?step=5');
        }
        if ($step == 5) {
            $file_names = ['car', 'car_availability'];
            $this->insertSQL($file_names);
            return \redirect()->to('installer/import-data?step=6');
        }
        if ($step == 6) {
            $file_names = ['space', 'space_availability'];
            $this->insertSQL($file_names);
            return \redirect()->to('installer/import-data?step=7');
        }
        if ($step == 7) {
            $file_names = ['tour', 'tour_availability'];
            $this->insertSQL($file_names);
            return \redirect()->to('installer/import-data?step=8');
        }
        if ($step == 8) {
            $file_names = ['agent', 'agent_availability', 'agent_relation', 'beauty', 'beauty_availability'];
            $this->insertSQL($file_names);
            return \redirect()->to('installer/import-data?step=9');
        }

        if ($step == 9) {
            $file_names = ['menu', 'menu_structure'];
            $this->insertSQL($file_names);
            return \redirect()->to('installer/import-data?step=10');
        }
        if ($step == 10) {
            $file_names = ['comment', 'coupon', 'earnings', 'language', 'media', 'notification', 'options', 'order', 'term', 'term_relation', 'withdrawal'];
            $this->insertSQL($file_names);
            return \redirect()->to('installer/import-data?step=11');
        }
        if ($step == 11) {
            $installedFile = storage_path('gmz_imported');
            $installed1File = storage_path('gmz_installed');
            $date = date("Y-m-d h:i:sa");
            if (!file_exists($installedFile)) {
                $message = sprintf(__('Your site has been imported at %s'), $date) . "\n";
                file_put_contents($installedFile, $message);
            } else {
                $message = sprintf(__('Your site has been imported at %s'), $date) . "\n";
                file_put_contents($installedFile, $message . PHP_EOL, FILE_APPEND | LOCK_EX);
            }

            if (!file_exists($installed1File)) {
                $message = sprintf(__('Your site has been installed at %s'), $date) . "\n";
                file_put_contents($installed1File, $message);
            } else {
                $message = sprintf(__('Your site has been installed at %s'), $date) . "\n";
                file_put_contents($installed1File, $message . PHP_EOL, FILE_APPEND | LOCK_EX);
            }
        }
        return view('Frontend::installer.import-data');
    }

    private function notImportData()
    {
        $installedFile = storage_path('gmz_imported');
        $date = date("Y-m-d h:i:sa");
        if (!file_exists($installedFile)) {
            $message = sprintf(__('Your site has been imported at %s'), $date) . "\n";
            file_put_contents($installedFile, $message);
        } else {
            $message = sprintf(__('Your site has been imported at %s'), $date) . "\n";
            file_put_contents($installedFile, $message . PHP_EOL, FILE_APPEND | LOCK_EX);
        }
        return \redirect()->to('/');
    }
}