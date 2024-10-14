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
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->integer('sender_user_id');
            $table->integer('receiver_user_id')->nullable();
            $table->integer('ref_group_id')->nullable();
            $table->text('message')->nullable();
            $table->string('filename',30)->nullable();
            $table->enum('file_type',['document','image','video'])->nullable();
            $table->enum('type',['one-to-one','group','broadcast'])->default('one-to-one');
            $table->enum('is_forward',['0','1'])->default('0');
            $table->softDeletes();
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
        Schema::dropIfExists('chats');
    }
};
