<?php

namespace App\Http\Controllers;

use App\Models\NewsGallery;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
const whitelist = ['127.0.0.1', "::1","localhost:8115"];

class NewsGalleryController extends Controller
{
    protected $uploadUrl = 'http://143.198.208.110:8115/storage/';

    public function getAll(Request $request)
    {
        if(in_array($_SERVER['HTTP_HOST'], whitelist)){
            $this->uploadUrl = 'http://localhost:8115/storage/';
        }

        $items = NewsGallery::select(
            'id as id',
            DB::raw("(CASE WHEN news_gallery_file = NULL THEN 'http://localhost:8115/storage/news/scg.png'
            ELSE CONCAT('".$this->uploadUrl."',news_gallery_file) END) AS news_gallery_file"),
            'news_id',
            'is_publish as is_publish',
            'deleted_at as delete_at',
            DB::raw("DATE(created_at) AS created_at"),
            'created_by as created_by',
            'updated_at as updated_at',
            'updated_by as updated_by',
        )
        ->where('deleted_at', null);
        

        if ($request->id) {
            $items->where('id',$request->id);
        }

        if ($request->news_id) {
            $items->where('news_id',$request->news_id);
        }

        if ($request->is_publish) {
            $items->where('is_publish',$request->is_publish);
        }

        if ($request->created_at) {
            $items->whereDate('created_at',$request->created_at);
        }

        if($request->orderBy){
            $items = $items->orderBy($request->orderBy,$request->order)->orderBy('id', 'DESC');
        }else{
            $items = $items->orderBy('created_at', 'desc')->orderBy('id', 'DESC');
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

    public function uppy(Request $request)
    {
        if(in_array($_SERVER['HTTP_HOST'], whitelist)){
            $this->uploadUrl = 'http://localhost:8115/storage/';
        }
        $request->validate([
            'file as required',
        ]);
        
        $pathFile = "";
        if(($request->file != "") && ($request->file != 'null')){
            $fileName = date("Y-m-d").'-rand'.rand(10,100).'-'.$request->file('file')->getClientOriginalName();
            $pathFile = '/uppy/image/'.$fileName;
            Storage::disk('public')->put($pathFile, file_get_contents($request->file));
        }else{
            return response()->json([
                'message' => 'error Upload Not Found'
            ], 200);
        }
 
        $res = $this->uploadUrl.$pathFile;

        $item = new NewsGallery;
        $item->news_id = $request->news_id != 'null' ? $request->news_id : null;
        $item->news_gallery_file = $pathFile;
        $item->secret_key = $request->secret_key;
        $item->is_publish = 1;
        $item->created_by = 'arnonr';
        $item->save();


        $responseData = [
            'message' => 'success',
            'link' => $res,
            'news_id' => $item->news_id,
            'news_gallery_id' => $item->id,
        ];
        
        return response()->json($responseData, 200);
    }

    public function delete($id)
    {
        $item = NewsGallery::where('id', $id)->first();
        $item->delete();

        $responseData = [
            'message' => 'success'
        ];

        return response()->json($responseData, 200);
    }
}
