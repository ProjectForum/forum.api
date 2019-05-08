<?php

namespace App\Models;

/**
 * Class Topic
 * @package App\Models
 * @property int $id
 * @property string $title
 * @property string $content
 * @property int $authorId
 * @property int $boardId
 */
class Topic extends BaseModel
{
    protected $fillable = [
        'title',
        'content',
    ];
}
