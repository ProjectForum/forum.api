<?php


namespace App\Http\Graph\Forum\Query;


use App\Supports\GraphQL\Definition\ObjectType;
use App\Supports\GraphQL\Entity\GraphTypeAttrs;
use App\Supports\Helpers\ForumTypes;

class QRootType extends ObjectType
{

    public function attrs(): GraphTypeAttrs
    {
        return $this->createAttr(
            'QRoot',
            '论坛查询入口类型'
        );
    }

    public function fields(): array
    {
        return [
            'test' => ForumTypes::string(),
        ];
    }

    public function resolveTest()
    {
        return 'hello';
    }
}