<?php
  
namespace App\Http\Resources\Auth;

use App\Http\Classes\Utility;
use Illuminate\Http\Resources\Json\JsonResource;
   
class AuthItemResource extends JsonResource
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
            'id'=>$this->name,
            'name'=>$this->name,
            'created_at' => Utility::getFormatDate($this->createdAt),
            'updated_at' =>Utility::getFormatDate($this->updatedAt),
            'created_by' => Utility::getUsername($this->createdBy),
            'updated_by' => Utility::getUsername($this->updatedBy),
        ];
    }
}