<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Models\Auth\AuthAssignment;
use App\Http\Models\Auth\AuthItem;
use App\Http\Models\Auth\UserAccess;
use App\Http\Models\User;
use App\Http\Requests\Auth\AssignmentRequest;
use App\Http\Resources\Auth\AssignmentResource;
use App\Http\Resources\Helper\Select2Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AssignmentController extends Controller
{
   
    public function show($id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return $this->sendError('User not found.');
        }
        $assignAvailable = AuthItem::select([DB::Raw('name as id'), DB::Raw('type as helper'),'name'])
                            ->whereNotIn('type', [3])->get();
        $model = AuthAssignment::select([DB::Raw('item_name as id'),DB::Raw('type as helper'), DB::Raw('item_name as name')])
                                ->leftjoin('auth_item',  'auth_assignment.item_name', '=','auth_item.name')
                                ->whereIn('type', [1,2])
                                ->where('user_id', $id)->get();

        $response = [];
        $response['available'] = Select2Resource::collection($assignAvailable);
        $response['used'] = Select2Resource::collection($model);
        $response['username'] = $user->username;

        return $this->sendResponse($response, 'Assignment  retrieved successfully.');
    }

    // /**
    //  * Update the specified resource in storage.
    //  *
    //  * @param  \Illuminate\Http\Request  $request
    //  * @param  int  $id
    //  * @return \Illuminate\Http\Response
    //  */
    public function update(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            for ($i = 0; $i < count($request->assign); $i++) {
                $model = AuthAssignment::firstOrCreate(['user_id' => $id, 'item_name' => $request->assign[$i]]);
            }
            DB::commit();
            return $this->show($id);
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
    public function destroy(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            for ($i = 0; $i < count($request->revoke); $i++) {
                $model = AuthAssignment::where(['user_id' => $id, 'item_name' => $request->revoke[$i]])->delete();
            }
            DB::commit();
            return $this->show($id);
        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('Internal Server Error', $e->getMessage());
        }
    }
}
