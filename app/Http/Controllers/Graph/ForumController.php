<?php


namespace App\Http\Controllers\Graph;

use App\Supports\GraphQL\GraphController;
use App\Supports\GraphQL\Definition\ObjectType;
use App\Supports\Helpers\ForumTypes;

class ForumController extends GraphController
{
    protected $defaultAction = 'forum';

    /**
     * 获取当前控制前的 GraphTypes
     * @return array
     */
    protected function getGraphTypes(): array
    {
        return $this->graphConfig[ForumTypes::TYPES_KEY];
    }

    /**
     * 获取 GraphType
     * @param string $type
     * @param string $directive
     * @return ObjectType
     */
    protected function getGraphType(string $type, string $directive): ObjectType
    {
        return ForumTypes::{$type}($directive);
    }
}