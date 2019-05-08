<?php

namespace App\Http\Controllers\Forum\Forum;

use App\Libs\Result;
use App\Services\Forum\Interfaces\ITopicService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class TopicController extends Controller
{
    /**
     * @var ITopicService
     */
    protected $topicService;

    public function __construct(ITopicService $topicService)
    {
        $this->topicService = $topicService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $boardId
     * @param Request $request
     * @return Response
     */
    public function index(int $boardId, Request $request)
    {
        $page = $request->input('page', 1);

        return Result::pagination(
            $this->topicService->getListPaginator($boardId, $page)
        );
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param int $boardId
     * @param Request $request
     * @return Response
     * @throws ValidationException
     */
    public function store(int $boardId, Request $request)
    {
        $input = $this->validate(
            $request,
            [
                'title' => 'required|max:32',
                'content' => 'required',
            ],
            [
                'title.required' => '标题不能为空',
                'title.max' => '标题不能大于32个字',
                'content.required' => '内容不能为空',
            ]
        );

        $topic = $this->topicService->createTopic($boardId, $input);

        return Result::success('', [
            'topicId' => $topic->id,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
