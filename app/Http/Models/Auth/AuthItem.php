<?php

namespace App\Http\Models\Auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthItem extends Model
{
    use HasFactory;
    protected $table="auth_item";
    protected $keyType = 'string';
    protected $primaryKey = 'name';
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'type',
        'description',
        'rule_name',
        'data',
        'created_by',
        'updated_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_by',
        'updated_by'
    ];

    public function itemChild(){
        return $this->hasMany(AuthItemChild::class,'parent','name');
    }
    public function search($request,$type){
        $query=$this::where('type',$type)
                ->where('name','LIKE','%'.$request->name.'%');
        
        return $query->orderBy('name','asc');
    }
}
