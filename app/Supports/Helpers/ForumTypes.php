<?php


namespace App\Supports\Helpers;

use App\Http\Graph\Forum\Common\ResultType;
use App\Http\Graph\Forum\Mutation\MUserType;
use App\Supports\GraphQL\Types;

/**
 * Class GraphTypes
 * @package App\Supports\Helpers
 * @method static ResultType result(array|string $directive = 'query', array $config = [])
 * @method static MUserType user(array|string $directive = 'query', array $config = [])
 */
class ForumTypes extends Types
{
    const TYPES_KEY = 'forumTypes';
    protected $configTypesKey = self::TYPES_KEY;

    public static function __callStatic($name, $arguments)
    {
        return app(self::class)->handleCallStatic($name, $arguments);
    }
}
