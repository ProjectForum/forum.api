<?php

namespace App\Entities\Model;

use App\Supports\Model\CamelCase;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Carbon;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * App\Entities\Model\User
 *
 * @property int $id 用户ID
 * @property string $username 用户名
 * @property string $email 电子邮箱
 * @property string|null $emailVerifiedAt 电子邮箱验证时间
 * @property string $password 密码
 * @property string $introduction 个人介绍
 * @property string $avatarUrl 头像地址
 * @property Carbon|null $createdAt
 * @property Carbon|null $updatedAt
 * @property string|null $deletedAt
 * @method static Builder|User newModelQuery()
 * @method static Builder|User newQuery()
 * @method static Builder|User query()
 * @mixin Eloquent
 */
class User extends Authenticatable implements JWTSubject
{
    use CamelCase;

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
