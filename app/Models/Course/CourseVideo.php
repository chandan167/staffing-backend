<?php

namespace App\Models\Course;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CourseVideo extends Model
{
    use HasFactory, SoftDeletes;


    public static function getNotFoundMessage():string
    {
        return __('notFound.course_video');
    }
}
