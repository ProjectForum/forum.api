<?php


namespace App\Supports\Helpers;

use App\Supports\GraphQL\Types;

/**
 * Class GraphTypes
 * @package App\Supports\Helpers
 */
class AdminTypes extends Types
{
    const TYPES_KEY = 'adminTypes';
    protected $configTypesKey = self::TYPES_KEY;

    public static function __callStatic($name, $arguments)
    {
        return app(self::class)->handleCallStatic($name, $arguments);
    }
}
