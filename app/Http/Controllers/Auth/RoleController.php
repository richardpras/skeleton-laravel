<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Models\Auth\AuthItem;
use App\Http\Models\Auth\AuthItemChild;
use App\Http\Requests\User\RoleRequest;
use App\Http\Resources\Auth\AuthItemResource;
use App\Http\Resources\Auth\UserResource;
use App\Http\Resources\Helper\Select2Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $model=new AuthItem();
        if($request['limit']!='-1'){
            $result=$model->search($request,1)
                ->paginate($request['limit'])
                ->appends(request()->query());
        }else{
            $result=$model->search($request,1)->get();
            $result= new \Illuminate\Pagination\LengthAwarePaginator($result, $result->count(), -1);
        }
        AuthItemResource::collection($result);
        return $this->sendResponse($result, 'Role retrieved successfully.');
    }

    public function assign(Request $request,$id){
        try {
            DB::beginTransaction();
            for ($i = 0; $i < count($request->assign); $i++) {
                $model = AuthItemChild::firstOrCreate(['parent' => $id, 'child' => $request->assign[$i]]);
            }
            DB::commit();
            return $this->showAssign($id);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('Internal Server Error', $e->getMessage());
        }
    }

    public function revoke(Request $request,$id){
        try {
            DB::beginTransaction();
            for ($i = 0; $i < count($request->revoke); $i++) {
                $model = AuthItemChild::where(['parent' => $id, 'child' => $request->revoke[$i]])->delete();
            }
            DB::commit();
            return $this->showAssign($id);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('Internal Server Error', $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        $request->merge([
            'createdBy' => Auth::id(),
            'updatedBy' => Auth::id(),
            'type'=>1
        ]);
        $validator = Validator::make($request->all(),$request->rules(),$request->messages());
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        DB::beginTransaction();
        try{
            $model = AuthItem::create($request->all());
            DB::commit();
            return $this->sendResponse(new AuthItemResource($model), 'Role  created successfully.');
           
        }catch(\Exception $e){
            DB::rollback();
            return $this->sendError('Internal Server Error', $e->getMessage()); 
        }
    } 
   
    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function show($id)
    {
        $model = AuthItem::where('name',$id)->where('type',1)->first();
  
        if (is_null($model)) {
            return $this->sendError('Assignment not found.');
        }
        $response['name']=$model->name;
        $response['description']=$model->description;
   
        return $this->sendResponse(array_merge((new AuthItemResource($model))->toArray(request()),$response), 'User  retrieved successfully.');
    }

    public function showAssign($id)
    {
        $model = AuthItem::where('name',$id)->where('type',1)->first();
  
        if (is_null($model)) {
            return $this->sendError('Role not found.');
        }
        $assignAvailable = AuthItem::select([DB::Raw('name as id'), DB::Raw('type as helper'),'name'])
                        ->whereIn('type', [2,3])->get();
        $used = AuthItemChild::select([DB::Raw('child as id'), DB::Raw('type as helper'), DB::Raw('child as name')])
                        ->leftjoin('auth_item',  'auth_item_child.child', '=','auth_item.name')
                        ->whereIn('type', [2,3])
                        ->where('parent', $id)
                        ->get();
        $response['available'] = Select2Resource::collection($assignAvailable);
        $response['used'] = Select2Resource::collection($used);
        $collect=new AuthItemResource($model);
        $collect->additional($response);
        return $this->sendResponse(array_merge((new AuthItemResource($model))->toArray(request()),$response), 'Role retrieved successfully.');
    }
    
    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function update(RoleRequest $request, $id)
    {
        $request->merge([
            'updatedBy' => Auth::id()
        ]);
        $validator = Validator::make($request->all(),$request->rules(),$request->messages());
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        if($id != $request->name){
            $checkValiadate=AuthItem::where('name',$request->name)->first();
            if($checkValiadate !=null){
                return $this->sendError('Validation Error.', [
                    'name'=>[
                        'Name already'
                    ]
                    ]);
            }
        }
        try{
            DB::beginTransaction();
            $model = AuthItem::where('name',$id)->where('type',1)->first();
            $model->name=$request->name;
            $model->description=$request->description;
            $model->save();
            DB::commit();
            return $this->showAssign($id);
           
        }catch(\Exception $e){
            DB::rollback();
            return $this->sendError('Internal Server Error', $e->getMessage()); 
        }
    }
   
    // /**
    //  * Remove the specified resource from storage.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function destroy($id)
    {
        $model = AuthItem::where('name',$id)->where('type',1)->first();
        DB::beginTransaction();
        try{
            $model->delete();
            DB::commit();
            return $this->sendResponse([], 'Role  deleted successfully.');
        }catch(\Exception $e){
            DB::rollback();
            return $this->sendError('Internal Server Error', $e->getMessage()); 
        }
       
    }
}
