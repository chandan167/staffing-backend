<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseVideosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_videos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->references('id')->on('courses')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('title')->unique();
            $table->string('slug');
            $table->text('thumbnail');
            $table->text('video_path');
            $table->string('duration')->comment('in minutes');
            $table->boolean('is_preview');
            $table->enum('status', ['publish', 'unpublish'])->default('unpublish');
            $table->unsignedInteger('place_order')->default(0);
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
        Schema::dropIfExists('course_videos');
    }
}
