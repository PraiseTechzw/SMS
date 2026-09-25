<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNoticesTable extends Migration
{
    public function up()
    {
        Schema::create('notices', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 180);
            $table->text('body');
            $table->string('audience', 30)->default('all');
            $table->date('published_at')->nullable();
            $table->date('expires_at')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();
            $table->index(['is_published', 'published_at', 'expires_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notices');
    }
}
