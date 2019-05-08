<?php


namespace App\Repositories\Forum\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ITopicRepository
{
    /**
     * @param int $boardId
     * @param int $page
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function getListPaginator(int $boardId, int $page, int $pageSize);
}
