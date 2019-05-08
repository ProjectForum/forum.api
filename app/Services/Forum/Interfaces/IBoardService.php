<?php


namespace App\Services\Forum\Interfaces;


use App\Models\Board;

interface IBoardService
{
    /**
     * 通过 ID 读取板块信息
     * @param int $boardId
     * @return Board
     */
    public function get(int $boardId): Board;
}
