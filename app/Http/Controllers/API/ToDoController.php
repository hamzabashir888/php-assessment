<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GuzzleHttp\Promise\Create;
use Illuminate\Http\Request;
use Validator;
use Auth;
use App\Models\ToDoList;
class ToDoController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api');
    }
    public function create(Request $request)
    {
        $rules =
        [
            'title' => 'required',
            'description' => 'required|string|max:199',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 500);
        }
        $data=[
            'user_id'=>Auth::user()->id,
            'title'=>$request->title,
            'description'=>$request->description
        ];
        $list=ToDoList::Create($data);
        return response()->json(['status'=>200,'message'=>'List Saved Successfully.','data'=>$data]);
    }
    public function viewList()
    {
        $list=ToDoList::where('user_id',Auth::user()->id)->select('title','description')->take(10)->get();
        if(count($list)>0){
            return response()->json(['status'=>200,'message'=>'To do list generated','data'=>$list]);
        }else{
            return response()->json(['status'=>100,'message'=>'No record found']);
        }
    }
    public function searchItems(Request $request)
    {
        $list=ToDoList::where('title','LIKE',"%{$request->item}%")->select('title','description')->get();
        if(count($list)>0){
            return response()->json(['status'=>200,'message'=>'To do list generated','data'=>$list]);
        }else{
            return response()->json(['status'=>100,'message'=>'No record found']);
        }
    }
    public function updateItems(Request $request)
    {
        $rules =
        [
            'item_id' => 'required',
            'title' => 'required',
            'description' => 'required|string|max:199',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()){
            return response()->json(['message' => $validator->errors()->first(), 'status' => false], 500);
        }
        $list=ToDoList::where('id',$request->item_id)->first();
        if($list){
            $data=[
                'title'=>$request->title,
                'description'=>$request->description
            ];
            $list=ToDoList::where('id',$request->item_id)->update($data);
            return response()->json(['status'=>200,'message'=>'To do list updated']);
        }else{
            return response()->json(['status'=>100,'message'=>'No item exists']);
        }
    }
    public function delete($id)
    {
        $list=ToDoList::where('id',$id)->delete();
        if($list){
            return response()->json(['status'=>200,'message'=>'deleted successfully']);
        }else{
            return response()->json(['status'=>100,'message'=>'No Item exists']);
        }
    }
}
