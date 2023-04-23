<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Banner;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
const whitelist = ['127.0.0.1', "::1","localhost:8115"];

class BannerController extends Controller
{
    protected $uploadUrl = 'http://143.198.208.110:8115/storage/';

    public function getAll(Request $request)
    {
        if(in_array($_SERVER['HTTP_HOST'], whitelist)){
            $this->uploadUrl = 'http://localhost:8115/storage/';
        }

        $items = Banner::select(
            'id as id',
            'title as title',
            'title_en as title_en',
            DB::raw("(CASE WHEN banner_file = NULL THEN 'http://localhost:8115/storage/banner/scg.png'
            ELSE CONCAT('".$this->uploadUrl."',banner_file) END) AS banner_file"),
            DB::raw("(CASE WHEN banner_en_file = NULL THEN 'http://localhost:8115/storage/banner/en/scg.png'
            ELSE CONCAT('".$this->uploadUrl."',banner_en_file) END) AS banner_en_file"),
            'link_url',
            'link_url_en',
            'level',
            'is_publish as is_publish',
            'deleted_at as delete_at',
            'created_at as created_at',
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
            $order = 'desc';
            if($request->order == 'desc'){
                $order = 'asc';
            }
            $items = $items->orderBy($request->orderBy, $order);
        }else{
            $items = $items->orderBy('level', 'desc');
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
        if(in_array($_SERVER['HTTP_HOST'], whitelist)){
            $this->uploadUrl = 'http://localhost:8115/storage/';
        }

        $item = Banner::select(
            'id as id',
            'title as title',
            'title_en as title_en',
            DB::raw("(CASE WHEN banner_file = NULL THEN 'http://localhost:8115/storage/banner/scg.png'
            ELSE CONCAT('".$this->uploadUrl."',banner_file) END) AS banner_file"),
            
            DB::raw("(CASE WHEN banner_en_file = NULL THEN 'http://localhost:8115/storage/banner/en/scg.png'
            ELSE CONCAT('".$this->uploadUrl."',banner_en_file) END) AS banner_en_file"),

            'link_url',
            'link_url_en',
            'level',
            'is_publish as is_publish',
            'deleted_at as delete_at',
            'created_at as created_at',
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
            'banner_file as required',
        ]);

        // return response()->json([
        //     'message' => $request->banner_file
        // ], 200);

        $pathBanner = null;
        if(($request->banner_file != "") && ($request->banner_file != 'null')){
            $fileNameBanner = 'banner-'.rand(10,100).'-'.$request->file('banner_file')->getClientOriginalName();
            $pathBanner = '/banner/'.$fileNameBanner;
            Storage::disk('public')->put($pathBanner, file_get_contents($request->banner_file));
        }else{
            return response()->json([
                'message' => 'error Upload Not Found'
            ], 200);
        }

        $pathBannerEN = null;
        if(($request->banner_en_file != "") && ($request->banner_en_file != 'null')){
            $fileNameBannerEN = 'banner-en-'.rand(10,100).'-'.$request->banner_en_file->getClientOriginalName();
            $pathBannerEN = '/banner/en/'.$fileNameBannerEN;
            Storage::disk('public')->put($pathBannerEN, file_get_contents($request->banner_en_file));
        }

        // 
        $level = 1;
        if(($request->level == null) || ($request->level == 'null')){
            $check_level = Banner::max('level');
            if($check_level){
                $level = $check_level + 1;
            }
        }

        // if(($request->level == null) || ($request->level == 'null')){
        //     $check_level = Banner::min('level');
        //     if($check_level){
        //         $level = $check_level + 1;
        //     }
        // }
 
        $item = new Banner;
        $item->title = $request->title;
        $item->title_en = $request->title_en;
        $item->banner_file = $pathBanner;
        $item->banner_en_file = $pathBannerEN;
        $item->link_url = $request->link_url;
        $item->link_url_en = $request->link_url_en;
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
        $item = Banner::where('id', $id)->first();

        $pathBanner = null;
        if(($request->banner_file != "") && ($request->banner_file != 'null')){
            $fileNameBanner = 'banner-'.rand(10,100).'-'.$request->banner_file->getClientOriginalName();
            $pathBanner = '/banner/'.$fileNameBanner;
            Storage::disk('public')->put($pathBanner, file_get_contents($request->banner_file));
        }else{
            $pathBanner  = $item->banner_file;
        }

        $pathBannerEN = null;
        if(($request->banner_en_file != "") && ($request->banner_en_file != 'null')){
            $fileNameBannerEN = 'banner-en-'.rand(10,100).'-'.$request->banner_en_file->getClientOriginalName();
            $pathBannerEN = '/banner/en/'.$fileNameBannerEN;
            Storage::disk('public')->put($pathBannerEN, file_get_contents($request->banner_en_file));
        }else{
            $pathBannerEN  = $item->banner_en_file;
        }


        $item->title = $request->has('title') ? $request->title : $item->title;
        $item->title_en = $request->has('title_en') ? $request->title_en : $item->title_en;
        $item->banner_file = $pathBanner;
        $item->banner_en_file = $pathBannerEN;
        $item->link_url = $request->has('link_url') ? $request->link_url : $item->link_url;
        $item->link_url_en = $request->has('link_url_en') ? $request->link_url_en : $item->link_url_en;
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
        $item = Banner::where('id', $id)->first();

        $item->level = null;
        $item->is_publish = 0;
        $item->deleted_at = Carbon::now();
        $item->save();

        // เรียงลำดับใหม่
        $items = Banner::where('deleted_at', null)->orderBy('level', 'asc')->get();

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

        $item = Banner::where('id', $id)->first();

        $item1 = null;
        if($type == 'IC'){
            $item1 = Banner::where('level', $item->level - 1)->first();
        }

        if($type == 'DC'){
            $item1 = Banner::where('level', $item->level + 1)->first();
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
