<?php


namespace App\Http\Graph\Forum\Mutation;


use App\Supports\GraphQL\Definition\ObjectType;
use App\Supports\GraphQL\Entity\GraphTypeAttrs;
use App\Supports\Helpers\ForumTypes;

class MRootType extends ObjectType
{

    public function attrs(): GraphTypeAttrs
    {
        return $this->createAttr(
            'MRoot',
            '论坛变更入口类型'
        );
    }

    public function fields(): array
    {
        return [
            'user' => ForumTypes::user('mutation'),
        ];
    }

    public function resolveField()
    {
        return [];
    }
}