<?php


namespace App\Repositories\Forum;


use App\Repositories\Forum\Interfaces\IBoardRepository;
use App\Models\Board;

class BoardRepository implements IBoardRepository
{
    public function getList(int $parentId = 0)
    {
        return Board::query()
            ->where('parent_id', $parentId)
            ->orderByRaw('order_code, id')
            ->get();
    }

    public function get(int $boardId)
    {
        return Board::query()
            ->where('state', 1)
            ->first();
    }
}
