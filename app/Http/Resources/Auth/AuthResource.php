<?php
  
namespace App\Http\Resources\Auth;

use App\Http\Classes\Utility;
use App\Http\Models\Auth\AuthAssignment;
use App\Http\Models\Auth\AuthItemChild;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

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
        $model=$this;
        return [
            'username'=>$this->username,
            'email'=>$this->email,
            'userAccess'=>$this->checkAuth($this->id),
            'role'=>$this->role,
            'route'=>$this->route,
            'created_at' => Utility::getFormatDate($this->createdAt),
            'updated_at' => Utility::getFormatDate($this->updatedAt),
        ];
    }
}