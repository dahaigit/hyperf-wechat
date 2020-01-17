<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateWechatUsersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wechat_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nickname')->comment('用户昵称');
            $table->tinyInteger('gender')->comment('性别：1男2女，0未知');
            $table->string('language')->comment('语言');
            $table->string('city')->comment('城市');
            $table->string('province')->comment('省');
            $table->string('country')->comment('国家');
            $table->string('avatarUrl')->comment('头像');
            $table->string('mobile')->comment('手机号码');
            $table->string('open_id')->unique()->comment('小程序唯一id');
            $table->string('unique_id')->comment('平台用户唯一id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wechat_users');
    }
}
