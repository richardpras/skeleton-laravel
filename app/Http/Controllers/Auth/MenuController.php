<?php

namespace App\Http\Controllers\Auth;

use App\Http\Classes\Accounting;
use App\Http\Controllers\Controller;
use App\Http\Models\Auth\Menu;
use App\Http\Requests\Auth\MenuRequest;
use App\Http\Resources\Auth\MenuResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $model=new Menu();
        if($request['limit']!='-1'){
            $result=$model->search($request)
                ->paginate($request['limit'])
                ->appends(request()->query());
        }else{
            $result=$model->search($request)->get();
            $result= new \Illuminate\Pagination\LengthAwarePaginator($result, $result->count(), -1);
        }
        MenuResource::collection($result);
        return $this->sendResponse($result, 'Menus retrieved successfully.');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MenuRequest $request)
    {
        $request->merge([
            'createdBy' => Auth::id(),
            'updatedBy' => Auth::id()
        ]);
        $validator = Validator::make($request->all(),$request->rules(),$request->messages());
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
        
        DB::beginTransaction();
        try{
            $model = Menu::create($request->all());
            DB::commit();
            return $this->sendResponse(new MenuResource($model), 'Menu  created successfully.');
           
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
        $model = Menu::find($id);
  
        if (is_null($model)) {
            return $this->sendError('Menu not found.');
        }
   
        return $this->sendResponse(new MenuResource($model), 'Menu  retrieved successfully.');
    }
    
    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function update(MenuRequest $request, $id)
    {
        $request->merge([
            'updatedBy' => Auth::id()
        ]);
        $validator = Validator::make($request->all(),$request->rules(),$request->messages());
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }

        DB::beginTransaction();
        try{
            $model = Menu::find($id);
            $model->update($request->except('_method'));
            DB::commit();
            return $this->sendResponse(new MenuResource($model), 'Menu  created successfully.');
           
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
        $model = Menu::find($id);
        $uses=Menu::checkUses();
        if(!$uses){
            return $this->sendError('check data',['This Menu in use.']);    
        }

        DB::beginTransaction();
        try{
            $model->delete();
            DB::commit();
            return $this->sendResponse([], 'Menu  deleted successfully.');
        }catch(\Exception $e){
            DB::rollback();
            return $this->sendError('Internal Server Error', $e->getMessage()); 
        }
       
    }
}
