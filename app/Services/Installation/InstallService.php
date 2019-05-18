<?php


namespace App\Services\Installation;


use App\Models\User;
use Config\User\UserConfig;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use phpDocumentor\Reflection\Types\Boolean;

class InstallService
{
    public function createConfig(array $config)
    {
        $userConfig = UserConfig::getInstance();

        // app
        $userConfig->setValue('app.name', $config['appName']);
        $userConfig->setValue('app.url', $config['appUrl']);

        // database
        $userConfig->setValue('db.host', $config['dbHost']);
        $userConfig->setValue('db.port', $config['dbPort']);
        $userConfig->setValue('db.database', $config['dbDatabase']);
        $userConfig->setValue('db.username', $config['dbUsername']);
        $userConfig->setValue('db.password', $config['dbPassword']);
        $userConfig->setValue('db.prefix', $config['dbPrefix']);

        $userConfig->save();

        Artisan::call('config:clear');
    }

    public function migrateTables()
    {
        $output = '';
        Artisan::call('migrate:reset');
        $output .= Artisan::output();

        Artisan::call('migrate');
        $output .= Artisan::output();
        return $output;
    }

    public function seedDatabase()
    {
        Artisan::call('db:seed');
        return Artisan::output();
    }

    public function initSetting(array $settings)
    {
        $user = new User;
        $user->name = $settings['username'];
        $user->email = $settings['email'];
        $user->password = Hash::make($settings['password']);
        $user->adminRoleId = 1;
        $user->save();
    }

    /**
     * 创建安装锁
     */
    public function createLock()
    {
        file_put_contents(base_path('install.lock'), 1);
    }


    /**
     * 是否已经有了安装锁
     * @return bool
     */
    public function hasLock(): bool
    {
        return file_exists(base_path('install.lock'));
    }
}
