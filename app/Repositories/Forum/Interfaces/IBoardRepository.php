<?php


namespace App\Repositories\Forum\Interfaces;


use App\Models\Board;

interface IBoardRepository
{
    /**
     * @param int $parentId
     * @return Board[]
     */
    public function getList(int $parentId = 0);


    /**
     * @param int $boardId
     * @return Board|null
     */
    public function get(int $boardId);
}
