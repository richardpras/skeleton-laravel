<?php
  
namespace App\Http\Resources\Auth;

use App\Http\Classes\Utility;
use App\Http\Models\Auth\AuthAssignment;
use App\Http\Models\Auth\AuthItemChild;
use App\Http\Models\Item;
use App\Http\Resources\ItemResource;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    private function checkAuth($id){
        $role=[];
        $permission=[];
        $route=[];
        $auth=AuthAssignment::where('user_id',$id)->get();
        foreach($auth as $row){
            //check by route
            switch($row->authItem->type){
                case 1:
                    $role[]=$row->authItem->name;
                    $child=AuthItemChild::where('parent',$row->authItem->name)->get();
                    foreach($child as $rowPermission){
                        switch($rowPermission->authItem->type){
                            case 2:
                                $permission[]=$rowPermission->authItem->name;
                                $childRoute=AuthItemChild::where('parent',$rowPermission->authItem->name)->get();
                                foreach($childRoute as $rowRoute){
                                    $route[]=$rowRoute->authItem->name;
                                }
                            break;
                            case 3:
                                $route[]=$rowPermission->authItem->name;
                            break;
                        }
                    }
                break;
                case 2:
                    $permission[]=$row->authItem->name;
                    $childRoute=AuthItemChild::where('parent',$row->authItem->name)->get();
                    foreach($childRoute as $rowRoute){
                        $route[]=$rowRoute->authItem->name;
                    }
                break;
                case 3:
                    $route[]=$row->authItem->name;
                    break;
            }
        }
        $response['role']=$role;
        $response['permission']=$permission;
        $response['route']=$route;
        return $response;
    }

    public function toArray($request)
    {
       
        $response=[
            'name'=>isset($this->profile->first_name)?$this->profile->first_name:$this->username,
            'location_code'=>isset($this->profile->location->code)?$this->profile->location->code:'',
            'location'=>isset($this->profile->location->name)?$this->profile->location->name:'',
            'location_id'=>isset($this->profile->location_id)?$this->profile->location_id:null,
            'address'=>isset($this->profile->location->address)?$this->profile->location->address:'',
            'counter_name'=>isset($this->profile->counter_name)?$this->profile->counter_name:'',
            'code'=>isset($this->profile->code)?$this->profile->code:'',
            'username'=>$this->username,
            'email'=>$this->email,
            'userAccess'=>$this->checkAuth($this->id),
            'role'=>$this->level,
            'blocked'=>isset($this->blocked_at)?true:false,
            'route'=>$this->route,
            'routeName'=> Route::currentRouteName(),
            'created_at' => Utility::getFormatDate($this->createdAt),
            'updated_at' => Utility::getFormatDate($this->updatedAt),
        ];
        $currentRouteName = Route::currentRouteName();
        if($currentRouteName=='Verification User Apps'){
            return array_merge($response);
        }
        return $response;
    }
}