<?php


namespace App\Services\Forum;


use App\Models\Topic;
use App\Repositories\Forum\Interfaces\ITopicRepository;
use App\Services\Forum\Interfaces\ITopicService;
use Illuminate\Support\Facades\Auth;

class TopicService implements ITopicService
{
    /**
     * @var ITopicRepository
     */
    protected $topicRepository;

    public function __construct(ITopicRepository $topicRepository)
    {
        $this->topicRepository = $topicRepository;
    }

    public function getListPaginator(int $boardId, $page, $pageSize = 20)
    {
        return $this->topicRepository->getListPaginator($boardId, $page, $pageSize);
    }

    public function createTopic(int $boardId, array $topicInput)
    {
        $topic = new Topic($topicInput);
        $topic->boardId = $boardId;
        $topic->authorId = 1; // Auth::id();
        $topic->save();

        return $topic;
    }
}
