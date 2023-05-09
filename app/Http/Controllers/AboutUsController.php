<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AboutUs;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AboutUsController extends Controller
{
    public function getAll(Request $request)
    {
        $items = AboutUs::select(
            'id as id',
            'title as title',
            'title_en as title_en',
            'detail',
            'detail_en',
            'level',
            'is_publish as is_publish',
            'deleted_at as delete_at',
            'created_by as created_by',
            'updated_at as updated_at',
            'updated_by as updated_by',
        )
        ->where('deleted_at', null);
        

        if ($request->id) {
            $items->where('id',$request->id);
        }

        if ($request->title) {
            $items->where('title','LIKE',"%".$request->title."%");
        }

        if ($request->title_en) {
            $items->where('title_en','LIKE',"%".$request->title_en."%");
        }

        if ($request->level) {
            $items->where('level',$request->level);
        }

        if ($request->is_publish) {
            $items->where('is_publish',$request->is_publish);
        }

        if($request->orderBy){
            // $order = 'desc';
            // if($request->order == 'desc'){
            //     $order = 'asc';
            // }
            $items = $items->orderBy($request->orderBy, $request->order);
        }else{
            $items = $items->orderBy('level', 'asc');
        }

        $count = $items->count();
        $perPage = $request->perPage ? $request->perPage : 10;
        $currentPage = $request->currentPage ? $request->currentPage : 1;

        $totalPage = ceil($count /$perPage) == 0 ? 1 : ceil($count / $perPage);
        $offset = $perPage * ($currentPage - 1);
        $items = $items->skip($offset)->take($perPage);
        $items = $items->get();

        return response()->json([
            'message' => 'success',
            'data' => $items,
            'totalPage' => $totalPage,
            'totalData' => $count,
        ], 200);
    }

    public function get($id)
    {
        $item = AboutUs::select(
            'id as id',
            'title as title',
            'title_en as title_en',
            'detail',
            'detail_en',
            'level',
            'is_publish as is_publish',
            'deleted_at as delete_at',
            'created_by as created_by',
            'updated_at as updated_at',
            'updated_by as updated_by',
        )
        ->where('id', $id)
        ->first();
        
        return response()->json([
            'message' => 'success',
            'data' => $item,
        ], 200);
    }

    public function add(Request $request)
    {
        $request->validate([
            'title as required',
        ]);

        $level = 1;
        if(($request->level == null) || ($request->level == 'null')){
            $check_level = AboutUs::max('level');
            if($check_level){
                $level = $check_level + 1;
            }
        }
 
        $item = new AboutUs;
        $item->title = $request->title;
        $item->title_en = $request->title_en;
        $item->detail = $request->detail;
        $item->detail_en = $request->detail_en;
        $item->level = $level;
        $item->is_publish = $request->is_publish;
        $item->created_by = 'arnonr';
        $item->save();

        $responseData = [
            'message' => 'success',
            'data' => $item,
        ];
        
        return response()->json($responseData, 200);
    }

    public function edit($id, Request $request)
    {
        $request->validate([
            'id as required',
        ]);

        $id = $request->id;
        $item = AboutUs::where('id', $id)->first();

        $item->title = $request->has('title') ? $request->title : $item->title;
        $item->title_en = $request->has('title_en') ? $request->title_en : $item->title_en;
        $item->detail =  $request->has('detail') ? $request->detail : $item->detail;
        $item->detail_en = $request->has('detail_en') ? $request->detail_en : $item->detail_en;
        $item->is_publish = $request->has('is_publish') ? $request->is_publish : $item->is_publish;
        $item->updated_by = 'arnonr';
        $item->save();

        $responseData = [
            'message' => 'success',
            'data' => $item,
        ];
        
        return response()->json($responseData, 200);
    }

    public function delete($id)
    {
        $item = AboutUs::where('id', $id)->first();

        $item->level = null;
        $item->is_publish = 0;
        $item->deleted_at = Carbon::now();
        $item->save();

         // เรียงลำดับใหม่
         $items = AboutUs::where('deleted_at', null)->orderBy('level', 'asc')->get();

         $i = 1; 
         foreach($items as $it){
             $it->level = $i;
             $i++;
             $it->save();
         }

        $responseData = [
            'message' => 'success'
        ];

        return response()->json($responseData, 200);
    }

    public function editLevel($id, Request $request)
    {
        $request->validate([
            'id as required',
            'type as required',
        ]);

        $id = $request->id;
        $type = $request->type;

        $item = AboutUs::where('id', $id)->first();

        $item1 = null;
        if($type == 'IC'){
            $item1 = AboutUs::where('level', $item->level + 1)->first();
        }

        if($type == 'DC'){
            $item1 = AboutUs::where('level', $item->level - 1)->first();
        }

        if($item1 != null){
            $level = $item1->level;
            $level1 = $item->level;

            $item->level = $level;
            $item->save();

            $item1->level = $level1;
            $item1->save();
        }

        $responseData = [
            'message' => 'success',
            // 'data' => $item,
        ];
        
        return response()->json($responseData, 200);
    }
}
