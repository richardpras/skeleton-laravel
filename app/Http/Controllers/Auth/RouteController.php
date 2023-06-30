<?php

namespace App\Http\Controllers\Auth;

use App\Http\Classes\Accounting;
use App\Http\Controllers\Controller;
use App\Http\Models\Auth\AuthAssignment;
use App\Http\Models\Auth\AuthItem;
use App\Http\Models\Auth\Role;
use App\Http\Models\User;
use App\Http\Requests\Auth\RoleRequest;
use App\Http\Resources\Auth\RoleResource;
use App\Http\Resources\Helper\Select2Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class RouteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $routeCollection = \Illuminate\Support\Facades\Route::getRoutes();
        $arr=[];
        foreach ($routeCollection as $value) {
            if(in_array('api',$value->getAction('middleware')) || in_array('web',$value->getAction('middleware'))){
                $arr[]= $value->getName();
            }
          
        }
        return $arr;

        // $model=new Role();
        // if($request['limit']!='-1'){
        //     $result=$model->search($request)
        //         ->paginate($request['limit'])
        //         ->appends(request()->query());
        // }else{
        //     $result=$model->search($request)->get();
        //     $result= new \Illuminate\Pagination\LengthAwarePaginator($result, $result->count(), -1);
        // }
        // RoleResource::collection($result);
        // return $this->sendResponse($result, 'Roles retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RoleRequest $request)
    {
        // $rolename='admin';
        // $role = Role::findOrCreate($rolename);
        // $permission = Permission::findOrCreate('save articles');
        // // $role->givePermissionTo($permission);
        // // $permission->assignRole($role);
        // $user=Auth::user();
        
        // $user->givePermissionTo($permission);
        // $user->assignRole($role);
        // return ['role'=>$role, 'permision'=>$permission,'user'=>$user];
        // $request->merge([
        //     'createdBy' => Auth::id(),
        //     'updatedBy' => Auth::id()
        // ]);
        // $validator = Validator::make($request->all(),$request->rules(),$request->messages());
        // if($validator->fails()){
        //     return $this->sendError('Validation Error.', $validator->errors());       
        // }
        
        // DB::beginTransaction();
        // try{

        //     $model = Role::create($request->all());
        //     DB::commit();
        //     return $this->sendResponse(new RoleResource($model), 'Role  created successfully.');
           
        // }catch(\Exception $e){
        //     DB::rollback();
        //     return $this->sendError('Internal Server Error', $e->getMessage()); 
        // }
    } 
   
    // /**
    //  * Display the specified resource.
    //  *
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function show()
    {
       
        $routeCollection = \Illuminate\Support\Facades\Route::getRoutes();
        $assignAvailable=[];
        foreach ($routeCollection as $value) {
            if(in_array('api',$value->getAction('middleware')) || in_array('web',$value->getAction('middleware'))){
                $assignAvailable[]= ['value'=>$value->getName(),'label'=>$value->getName()];
            }
          
        }
        $model = AuthItem::select([DB::Raw('name as id'), 'name'])->whereIn('type', [3])->get();
        
        $response = [];
        $response['available'] = $assignAvailable;
        $response['used'] = Select2Resource::collection($model);

        return $this->sendResponse($response, 'Route retrieved successfully.');
    }
    
    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function update(Request $request)
    {
        try {
            DB::beginTransaction();
            for ($i = 0; $i < count($request->assign); $i++) {
                $model = AuthItem::firstOrCreate(['name'=>$request->assign[$i],'type'=>3]);
            }
            DB::commit();
            return $this->show();
        } catch (\Exception $e) {
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
    public function delete(Request $request)
    {
        try {
            DB::beginTransaction();
            for ($i = 0; $i < count($request->revoke); $i++) {
                $model = AuthItem::where(['name'=>$request->revoke[$i],'type'=>3])->delete();
            }
            DB::commit();
            return $this->show();
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('Internal Server Error', $e->getMessage());
        }
       
    }
}
