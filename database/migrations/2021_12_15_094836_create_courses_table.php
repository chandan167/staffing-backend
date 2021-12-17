<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_category_id')->nullable()->references('id')->on('course_categories')->nullOnDelete()->cascadeOnUpdate();
            $table->string('title')->unique();
            $table->string('slug')->nullable();
            $table->text('thumbnail');
            $table->decimal('price');
            $table->string('currency');
            $table->string('duration')->comment('in minutes');
            $table->text('description');
            $table->enum('status', ['publish', 'unpublish'])->default('unpublish');
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
        Schema::dropIfExists('courses');
    }
}
