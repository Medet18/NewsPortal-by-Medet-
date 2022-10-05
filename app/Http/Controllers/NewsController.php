<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewsStoreReq;
use App\Models\News;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class NewsController extends Controller
{

    /////////////////// Display only for users////////////////////////////
    public function index_for_user(): \Illuminate\Http\JsonResponse
    {
        $news = News::all();
        return response()->json([__('word.news')=>$news], 200);
    }

    public function show_for_user($lang, $id): \Illuminate\Http\JsonResponse
    {
        $news = News::find($id);
        if(!$news){
            return response()->json([__('word.message') => __('word.not_found')],404);
        }

        return response()->json([__('word.news')=>$news], 200);

    }
    ///////////////////////////////////////////////////////////////////


    //////////////////////Display for subadmins///////////////////////////

    public function index_for_subadmin(): \Illuminate\Http\JsonResponse
    {
        $new = News::where('subadmin_id',  auth('subadmin-api')->user()->id)->get();
        return response()->json([__('word.news')=>$new],200);
    }

    public function show_for_subadmin($lang, $id): \Illuminate\Http\JsonResponse
    {
        //$news = News::where('subadmin_id', \Auth::id())->where('id', $id)->first();
        $news = News::find($id);
        if(!$news){
            return response()->json([__('word.message')=> __('word.not_found')],404);
        }
        elseif($news->subadmin_id == auth('subadmin-api')->user()->id){
            return response()->json([__('word.message')=> $news],200);
        }
        else{
            return response()->json([__('word.message')=> __('word.access_search')],403);//"U can see only ur's news! . Look better your id's in show route!"],200);
        }
    }


    public function store(NewsStoreReq $request): \Illuminate\Http\JsonResponse
    {
        try{
            $photo = Str::random(32).".".$request->photo_of_news->getClientOriginalExtension();

            $news = new News();
            News::create([
                'subadmin_id' => auth('subadmin-api')->user()->id,
                'title_of_news' => $request->title_of_news,
                'description_of_news' => $request->description_of_news,
                'photo_of_news' => $photo,
                'date_of_news' => Carbon::create($request->date_of_news)->toDateString()

            ]);

            Storage::disk('public')->put($photo, file_get_contents($request->photo_of_news));
            return response()->json([__('word.message') => __('word.store')],200);//News successfully stored!'], 200);

        } catch(\Exception $e){
            return response()->json([__('word.message') => __('word.s_w_wrong')],500);//'Something went wrong!'], 500);
            //return response()->json(['Exception' => $e], 500);
        }
    }

    public function update(NewsStoreReq $request,$lang, $id): \Illuminate\Http\JsonResponse
    {
        try{
            $news = News::find($id);

            if(!$news){
                return response()->json([__('word.message') =>  __('word.not_found')],404);
            }
            elseif($news->subadmin_id == auth('subadmin-api')->user()->id){

                $news->title_of_news = $request->title_of_news;
                $news->description_of_news = $request->description_of_news;
                $news->date_of_news = Carbon::create($request->date_of_news)->toDateString();

                if($request->photo_of_news){
                    $storage = Storage::disk('public'); //public storage

                    //old image delete
                    if($storage->exists($news->photo_of_news))
                        $storage->delete($news->photo_of_news);

                    //Image name
                    $photo = Str::random(32).".".$request->photo_of_news->getClientOriginalExtension();
                    $news->photo_of_news = $photo;

                    //Image save in public folder
                    $storage->put($photo, file_get_contents($request->photo_of_news));
                }

                $news->save();
                return response()->json([__('word.message') => __('word.updated')],200);//'News successfully updated!'], 200);
            }
            else{
                return response()->json([__('word.message') => __('word.access_update')],403);//"U can't update! ,It's not ur's news. U can edit only urs news!"],200);
            }

        } catch(\Exception $e){
            return response()->json([__('word.message') => __('word.s_w_wrong')],500);
            //return response()->json(['message' => $e],500);
        }
    }

    public function destroy($lang, $id): \Illuminate\Http\JsonResponse
    {
        $news = News::find($id);
        if(!$news){
            return response()->json([__('word.message') => __('word.not_found')], 404);
        }
        elseif($news->subadmin_id == auth('subadmin-api')->user()->id){

            $storage = Storage::disk('public');
            if($storage->exists($news->photo_of_news))
                $storage->delete($news->photo_of_news);

            $news->delete();

            return response()->json([__('word.message') => __('word.deleted')],200);//'News successfully deleted!'], 200);
        }
        else{
            return response()->json([__('word.message') => __('word.access_delete')],404);//"U can't delete news, Delete only urs new"], 200);
        }
    }
}
