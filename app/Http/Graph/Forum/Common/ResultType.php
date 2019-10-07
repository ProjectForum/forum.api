<?php


namespace App\Http\Graph\Forum\Common;


use App\Supports\GraphQL\Definition\ObjectType;
use App\Supports\GraphQL\Entity\GraphTypeAttrs;
use App\Supports\Helpers\ForumTypes;

class ResultType extends ObjectType
{
    public function attrs(): GraphTypeAttrs
    {
        return $this->createAttr(
            'Result',
            '公共返回类型'
        );
    }

    public function fields(): array
    {
        return [
            'message' => ForumTypes::string(),
        ];
    }
}