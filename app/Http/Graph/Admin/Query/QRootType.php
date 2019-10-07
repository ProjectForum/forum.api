<?php


namespace App\Http\Graph\Admin\Query;


use App\Supports\GraphQL\Definition\ObjectType;
use App\Supports\GraphQL\Entity\GraphTypeAttrs;
use App\Supports\Helpers\AdminTypes;

class QRootType extends ObjectType
{

    public function attrs(): GraphTypeAttrs
    {
        return $this->createAttr(
            'QRoot',
            '论坛入口类型'
        );
    }

    public function fields(): array
    {
        return [
            'test' => AdminTypes::string(),
        ];
    }

    public function resolveTest()
    {
        return 'hello2';
    }
}