<?php


namespace App\Repositories\Forum;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Forum\Interfaces\IUserRepository;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserRepository implements IUserRepository
{
    /**
     * 创建用户
     *
     * @param string $name
     * @param string $email
     * @param string $password
     * @return User
     */
    public function createUser(string $name, string $email, string $password): User
    {
        $user = new User;
        $user->name = $name;
        $user->email = $email;
        $user->password = Hash::make($password);
        $user->save();

        return $user;
    }


    public function generateToken(User $user): ?string
    {
        return JWTAuth::fromUser($user);
    }

    public function findByName(string $userName): ?User
    {
        return User::where('name', $userName)->first();
    }

    public function findByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
