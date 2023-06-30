<?php
  
namespace App\Http\Resources\Auth;

use App\Http\Classes\Utility;
use Illuminate\Http\Resources\Json\JsonResource;
   
class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $model=$this;
        return [
            'id'=>$this->id,
            'name'=>$this->username,
            'email'=>$this->email,
            'status'=>$this->status,
            'created_at' => Utility::getFormatDate($this->createdAt),
            'updated_at' => Utility::getFormatDate($this->updatedAt),
        ];
    }
}