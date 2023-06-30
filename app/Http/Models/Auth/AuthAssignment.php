<?php

namespace App\Http\Models\Auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class AuthAssignment extends Model
{
    use HasFactory;
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';
    protected $table="auth_assignment";
    protected $keyType = 'string';
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'item_name',
        'user_id',
        'status',
        'createdBy',
        'updatedBy'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'createdBy',
        'updatedBy'
    ];

    public static function checkUses(){
        return true;
    }
    public function authItem(){
        return $this->hasOne(AuthItem::class,'name','item_name');
    }
    public function user(){
        return $this->hasOne(User::class,'id','idUser');
    }

    public function role(){
        return $this->hasOne(Role::class,'id','idRole');
    }

    public function getshowStatusAttribute()
    {
        return $this->showStatus($this->status);    
    }
    private function showStatus($value){
        $text="";
        switch($value){
            case 1:
                $text='Active';
                break;
            case 0:
                $text='Non Active';
                break;
        }
        return $text;
    }

    public function search($request){
        // $query=$this::where('name','LIKE','%'.$request->name.'%');
        // if(isset($request->status)){
        //     $query->where('status',$request->status);
        // }
        // return $query->orderBy('name','asc');
    }
}
