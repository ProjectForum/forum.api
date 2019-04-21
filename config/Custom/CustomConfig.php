<?php


namespace Config\Custom;


use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CustomConfig
{
    /**
     * 配置项
     * @var array
     */
    protected $config = [];

    /**
     * 当前类的实例
     * @var CustomConfig
     */
    protected static $instance = null;

    /**
     * 配置文件路径
     * @var string
     */
    protected $configPath = __DIR__ . '/config.php';

    /**
     * 获取当前类的实例
     * @return CustomConfig
     */
    public static function getInstance(): CustomConfig
    {
        if (empty(self::$instance)) {
            self::$instance = new CustomConfig();
        }
        return self::$instance;
    }

    /**
     * 获取配置项
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public static function get(string $key, $default = null)
    {
        return self::getInstance()->getValue($key, $default);
    }

    public static function set(string $key, $value)
    {
        return self::getInstance()->setValue($key, $value);
    }

    public function __construct()
    {
        // 如果配置文件不存在则从默认配置中生成一个
        $isNewly = false;
        if (!file_exists($this->configPath)) {
            file_put_contents(
                $this->configPath,
                file_get_contents(
                    __DIR__ . '/config-default.php'
                )
            );
            $isNewly = true;
        }
        // 加载配置
        $this->config = include __DIR__ . '/config.php';

        // 新生成的配置文件执行一部分默认操作
        if ($isNewly) {
            // 生成 JWT Secret Key
            $this->setValue('jwt.secret', Str::random(64));
            // 生成 APP Key
            $this->setValue('app.key', 'base64:' . base64_encode(Str::random(32)));

            // 保存
            $this->save();
        }
    }

    /**
     * 获取配置项
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function getValue(string $key, $default = null)
    {
        $value = $this->findConfig($key);
        if ($value === null) {
            return $default;
        } else {
            return $value;
        }
    }

    public function setValue(string $key, $value)
    {
        $this->findConfig($key, $value);
    }

    public function findConfig(string $key, $setValue = false)
    {
        $keys = explode('.', $key);

        if (count($keys) == 1) {
            if (!array_key_exists($key, $this->config)) {
                return null;
            }
            if ($setValue !== false) {
                $this->config[$key] = $setValue;
            }
            return $this->config[$key];
        } else {
            if (!array_key_exists($keys[0], $this->config) || !array_key_exists($keys[1], $this->config[$keys[0]])) {
                return null;
            }
            if ($setValue !== false) {
                $this->config[$keys[0]][$keys[1]] = $setValue;
            }

            return $this->config[$keys[0]][$keys[1]];
        }
    }

    /**
     * 将修改保存至配置文件
     */
    public function save()
    {
        $configExport = var_export($this->config, true);
        file_put_contents(
            $this->configPath,
            "<?php\n\nreturn {$configExport};\n"
        );
    }
}
