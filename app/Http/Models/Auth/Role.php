<?php

namespace App\Http\Models\Auth;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use HasFactory;
    protected $table="m_roles";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'idFunction',
        'guard_name',
        'name',
        'status',
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

    public static function checkUses(){
        return true;
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
        $query=$this::where('name','LIKE','%'.$request->name.'%');
        if(isset($request->status)){
            $query->where('status',$request->status);
        }
        return $query->orderBy('name','asc');
    }
}
