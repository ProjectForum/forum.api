<?php

$forumTypes = [
    'forum' => [
        'query' => \App\Http\Graph\Forum\Query\QRootType::class,
        'mutation' => \App\Http\Graph\Forum\Mutation\MRootType::class,
    ],
    'result' => \App\Http\Graph\Forum\Common\ResultType::class,
    'user' => [
        'mutation' => \App\Http\Graph\Forum\Mutation\MUserType::class,
    ],
];

$adminTypes = [
    'admin' => [
        'query' => \App\Http\Graph\Admin\Query\QRootType::class,
    ],
];

return [
    // 类型注册表
    'types' => [],
    'forumTypes' => $forumTypes,
    'adminTypes' => $adminTypes,
    // 入口类型
    'schema' => [
        'forum',
        'admin',
    ],
];

