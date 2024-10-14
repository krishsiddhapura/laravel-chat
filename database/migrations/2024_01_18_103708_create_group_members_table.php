<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_members', function (Blueprint $table) {
            $table->id();
            $table->integer('ref_group_id');
            $table->integer('ref_user_id');
            $table->enum('role',['admin','member']);
            $table->enum('is_pinned',['0','1'])->default('0');
            $table->enum('is_archived',['0','1'])->default('0');
            $table->enum('notification_status',['active','mute']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('group_members');
    }
};
