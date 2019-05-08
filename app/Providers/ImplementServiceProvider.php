<?php

namespace App\Providers;

use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Nwidart\Modules\Facades\Module;

class ImplementServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     * @throws Exception
     */
    public function register()
    {
        $implements = [];

        foreach (config('app.implementsMap', []) as $implement) {
            $implements = array_merge(
                $implements,
                $this->findImplements(
                    $implement['path'],
                    "{$implement['namespace']}\\Interfaces",
                    $implement['namespace']
                )
            );
        }

        // 合并系统配置
        $implements = array_merge(
            $implements,
            config('app.implements')
        );

        // 将实现绑定到容器
        foreach ($implements as $implement) {
            $this->app->bind($implement['implement']);
            $this->app->bind($implement['interface'], function () use ($implement) {
                return $this->app->make($implement['implement']);
            });
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * @param string $interfacesDirPath
     * @param string $interfacesNamespace
     * @param string $implementsNamespace
     * @return array
     * @throws Exception
     */
    public function findImplements(string $interfacesDirPath, string $interfacesNamespace, string $implementsNamespace): array
    {
        $implements = [];
        $interfaceFiles = scandir($interfacesDirPath);
        foreach ($interfaceFiles as $interfaceFile) {
            // 判断是否为I开头.php为结尾的文件
            if (Str::startsWith($interfaceFile, 'I') && Str::endsWith($interfaceFile, '.php')) {
                // 找到Interface与对应的实现
                $interfaceName = Str::before($interfaceFile, '.php');
                $className = Str::after($interfaceName, 'I');
                $classPath = $implementsNamespace . "\\{$className}";

                // 检查实现类是否存在
                if (!class_exists($classPath)) {
                    throw new Exception("找不到[{$interfaceName}]的实现类");
                }

                // 存储
                $implements[$interfaceName] = [
                    'interface' => $interfacesNamespace . "\\{$interfaceName}",
                    'implement' => $classPath,
                ];
            }
        }

        return $implements;
    }
}
