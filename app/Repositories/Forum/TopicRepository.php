<?php


namespace App\Repositories\Forum;

use App\Models\Topic;
use App\Repositories\Forum\Interfaces\ITopicRepository;

class TopicRepository implements ITopicRepository
{
    public function getListPaginator(int $boardId, int $page, int $pageSize)
    {
        return Topic::query()
            ->where('state', '!=', 0)
            ->orderBy('id', 'desc')
            ->paginate(
                $pageSize,
                ['*'],
                'page',
                $page
            );
    }
}
