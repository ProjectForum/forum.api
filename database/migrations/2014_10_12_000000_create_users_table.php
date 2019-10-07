<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->comment('用户ID');
            $table->string('username')->comment('用户名');
            $table->string('email')->unique()->comment('电子邮箱');
            $table->timestamp('email_verified_at')->nullable()->comment('电子邮箱验证时间');
            $table->string('password', 128)->comment('密码');
            $table->string('introduction', 64)->default('')->comment('个人介绍');
            $table->string('avatar_url', 128)->default('')->comment('头像地址');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
