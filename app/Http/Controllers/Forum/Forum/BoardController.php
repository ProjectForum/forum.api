<?php

namespace App\Http\Controllers\Forum\Forum;

use App\Libs\Result;
use App\Repositories\Forum\Interfaces\IBoardRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class BoardController extends Controller
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
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $parentId = $request->input('parentId',  0);

        return Result::success('succeed', [
            'list' => $this->boardRepository->getList($parentId),
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        return Result::success('', [
            'board' => $this->boardRepository->get($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }
}
