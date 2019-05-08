<?php


namespace App\Services\Forum;

use App\Exceptions\ResultException;
use App\Libs\Result;
use App\Models\Board;
use App\Repositories\Forum\Interfaces\IBoardRepository;
use App\Services\Forum\Interfaces\IBoardService;
use Illuminate\Http\Response;

class BoardService implements IBoardService
{
    /**
     * @var IBoardRepository
     */
    protected $boardRepository;

    public function __construct(IBoardRepository $boardRepository)
    {
        $this->boardRepository = $boardRepository;
    }

    /**
     * 读取板块
     * @param int $boardId
     * @return Board
     * @throws ResultException
     */
    public function get(int $boardId): Board
    {
        $board = $this->boardRepository->get($boardId);

        if (empty($board)) {
            throw new ResultException(
                '板块不存在',
                Result::RESOURCE_NOT_FOUND,
                Response::HTTP_NOT_FOUND
            );
        }

        return $board;
    }
}
