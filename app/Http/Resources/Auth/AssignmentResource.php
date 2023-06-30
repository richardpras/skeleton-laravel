<?php
  
namespace App\Http\Resources\Auth;

use App\Http\Classes\Utility;
use Illuminate\Http\Resources\Json\JsonResource;
   
class AssignmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name'=>$this->username,
            'status'=>$this->status,
            'created_at' => Utility::getFormatDate($this->createdAt),
            'updated_at' =>Utility::getFormatDate($this->updatedAt),
            'created_by' => Utility::getUsername($this->createdBy),
            'updated_by' => Utility::getUsername($this->updatedBy),
        ];
    }
}