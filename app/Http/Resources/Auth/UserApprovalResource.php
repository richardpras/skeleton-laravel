<?php
  
namespace App\Http\Resources\Auth;

use App\Http\Classes\Utility;
use Illuminate\Http\Resources\Json\JsonResource;
   
class UserApprovalResource extends JsonResource
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
            'idUser'=>$this->idUser,
            'name'=>isset($this->employee->name)?$this->employee->name .' ( '.$this->employee->position->name.' )':'',
            'remark'=>$this->remark,
            'created_at' => Utility::getFormatDate($this->createdAt),
            'updated_at' => Utility::getFormatDate($this->updatedAt),
        ];
    }
}