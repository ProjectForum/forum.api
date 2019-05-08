<?php


namespace App\Services\Forum\Interfaces;

use App\Models\Topic;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ITopicService
{
    /**
     * @param int $boardId
     * @param $page
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getListPaginator(int $boardId, $page, $pageSize = 20);


    /**
     * @param int $boardId
     * @param array $topicInput
     * @return Topic
     */
    public function createTopic(int $boardId, array $topicInput);
}
