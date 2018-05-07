<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;
    protected $table = 'questions';
    protected $primaryKey = 'id';
    protected $fillable = ['title','answer','question_type'];
    protected $dates = ['deleted_at'];
    //
    public static function questionAdd($params){
        $res = Question::create($params);
        return $res->id;
    }


}
