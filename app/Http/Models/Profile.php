<?php

namespace App\Http\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Profile extends Model
{
    use HasFactory;
    protected $table="profiles";
    protected $primaryKey = 'user_id';
    public $incrementing = false;
    protected $fillable = [
        'user_id',
        'relation_id',
        'first_name',
        'last_name',
        'address',
        'phone',
        'email',
        'photo',
    ];
    protected $hidden = [
        'created_by',
        'updated_by'
    ];
  
    public function user(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function search($request){
        $query=$this::where('first_name','LIKE','%'.$request->name.'%');
        if(isset($request->sortColumn)){
            $query->orderBy($request->sortColumn,$request->sort);
        }else{
            $query->orderBy('first_name','ASC');
        }
        return $query;
    }

}
