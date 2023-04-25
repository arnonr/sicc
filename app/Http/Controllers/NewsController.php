<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\News;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
const whitelist = ['127.0.0.1', "::1","localhost:8115"];

class NewsController extends Controller
{
    protected $uploadUrl = 'http://143.198.208.110:8115/storage/';

    public function getAll(Request $request)
    {
        if(in_array($_SERVER['HTTP_HOST'], whitelist)){
            $this->uploadUrl = 'http://localhost:8115/storage/';
        }

        $items = News::select(
            'news.id as id',
            'news.title as title',
            'news.title_en as title_en',
            DB::raw("(CASE WHEN news_file = NULL THEN 'http://localhost:8115/storage/news/scg.png'
            ELSE CONCAT('".$this->uploadUrl."',news_file) END) AS news_file"),
            DB::raw("(CASE WHEN news_en_file = NULL THEN 'http://localhost:8115/storage/mews/en/scg.png'
            ELSE CONCAT('".$this->uploadUrl."',news_en_file) END) AS news_en_file"),
            'news.news_type_id',
            'news.detail',
            'news.detail_en',
            'news.is_publish as is_publish',
            'news.deleted_at as delete_at',
            DB::raw("DATE(news.created_at) AS created_at"),
            'news.created_by as created_by',
            'news.updated_at as updated_at',
            'news.updated_by as updated_by',
            'news_type.title as news_type_title'
        )
        ->join('news_type','news_type.id','=','news.news_type_id')
        ->where('news.deleted_at', null);
        

        if ($request->id) {
            $items->where('news.id',$request->id);
        }

        if ($request->title) {
            $items->where('news.title','LIKE',"%".$request->title."%");
        }

        if ($request->title_en) {
            $items->where('news.title_en','LIKE',"%".$request->title_en."%");
        }

        if ($request->news_type_id) {
            $items->where('news.news_type_id',$request->news_type_id);
        }

        if ($request->is_publish) {
            $items->where('news.is_publish',$request->is_publish);
        }

        if ($request->created_at) {
            $items->whereDate('news.created_at',$request->created_at);
        }

        if($request->orderBy){
            $items = $items->orderBy($request->orderBy,$request->order);
        }else{
            $items = $items->orderBy('news.created_at', 'desc');
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

        $item = News::select(
            'news.id as id',
            'news.title as title',
            'news.title_en as title_en',
            DB::raw("(CASE WHEN news_file = NULL THEN 'http://localhost:8115/storage/news/scg.png'
            ELSE CONCAT('".$this->uploadUrl."',news_file) END) AS news_file"),
            DB::raw("(CASE WHEN news_en_file = NULL THEN 'http://localhost:8115/storage/mews/en/scg.png'
            ELSE CONCAT('".$this->uploadUrl."',news_en_file) END) AS news_en_file"),
            'news.news_type_id',
            'news.detail',
            'news.detail_en',
            'news.is_publish as is_publish',
            'news.deleted_at as delete_at',
            DB::raw("DATE(news.created_at) AS created_at"),
            'news.created_by as created_by',
            'news.updated_at as updated_at',
            'news.updated_by as updated_by',
            'news_type.title as news_type_title'
        )
        ->join('news_type','news_type.id','=','news.news_type_id')
        ->where('news.id', $id)
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
            'news_file as required',
            'news_type_id as required'
        ]);
        
        $pathNews = null;
        if(($request->news_file != "") && ($request->news_file != 'null')){
            $fileNameNews = 'news-'.rand(10,100).'-'.$request->file('news_file')->getClientOriginalName();
            $pathNews = '/news/'.$fileNameNews;
            Storage::disk('public')->put($pathNews, file_get_contents($request->news_file));
        }else{
            return response()->json([
                'message' => 'error Upload Not Found'
            ], 200);
        }

        $pathNewsEN = null;
        if(($request->news_en_file != "") && ($request->news_en_file != 'null')){
            $fileNameNewsEN = 'news-en-'.rand(10,100).'-'.$request->news_en_file->getClientOriginalName();
            $pathNewsEN = '/news/en/'.$fileNameNewsEN;
            Storage::disk('public')->put($pathNewsEN, file_get_contents($request->news_en_file));
        }
 
        $item = new News;
        $item->title = $request->title;
        $item->title_en = $request->title_en;
        $item->news_file = $pathNews;
        $item->news_en_file = $pathNewsEN;
        $item->news_type_id = $request->news_type_id;
        $item->detail = $request->detail;
        $item->detail_en = $request->detail_en;
        $item->is_publish = $request->is_publish;
        $item->created_by = 'arnonr';
        $item->created_at = $request->created_at;
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
        $item = News::where('id', $id)->first();

        $pathNews = null;
        if(($request->news_file != "") && ($request->news_file != 'null')){
            $fileNameNews = 'news-'.rand(10,100).'-'.$request->news_file->getClientOriginalName();
            $pathNews = '/news/'.$fileNameNews;
            Storage::disk('public')->put($pathNews, file_get_contents($request->news_file));
        }else{
            $pathNews  = $item->news_file;
        }

        $pathNewsEN = null;
        if(($request->news_en_file != "") && ($request->news_en_file != 'null')){
            $fileNameNewsEN = 'news-en-'.rand(10,100).'-'.$request->news_en_file->getClientOriginalName();
            $pathNewsEN = '/news/en/'.$fileNameNewsEN;
            Storage::disk('public')->put($pathNewsEN, file_get_contents($request->news_en_file));
        }else{
            $pathNewsEN  = $item-news_en_file;
        }


        $item->title = $request->has('title') ? $request->title : $item->title;
        $item->title_en = $request->has('title_en') ? $request->title_en : $item->title_en;
        $item->news_file = $pathNews;
        $item->news_en_file = $pathNewsEN;
        $item->news_type_id =  $request->has('news_type_id') ? $request->news_type_id : $item->news_type_id; 
        $item->detail =  $request->has('detail') ? $request->detail : $item->detail;
        $item->detail_en = $request->has('detail_en') ? $request->detail_en : $item->detail_en;
        $item->is_publish = $request->has('is_publish') ? $request->is_publish : $item->is_publish;
        $item->updated_by = 'arnonr';
        $item->created_at = $request->has('created_at') ? $request->created_at : $item->created_at;
        $item->save();

        $responseData = [
            'message' => 'success',
            'data' => $item,
        ];
        
        return response()->json($responseData, 200);
    }

    public function delete($id)
    {
        $item = News::where('id', $id)->first();

        $item->is_publish = 0;
        $item->deleted_at = Carbon::now();
        $item->save();

        $responseData = [
            'message' => 'success'
        ];

        return response()->json($responseData, 200);
    }
}
