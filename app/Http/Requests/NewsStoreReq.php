<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed $photo_of_news
 * @property mixed $title_of_news
 * @property mixed $description_of_news
 * @property mixed $date_of_news
 */
class NewsStoreReq extends FormRequest
{
    public function authorize(): bool
    {
        //return false;
        return true;
    }

    public function rules(): array
    {
        return [
            'title_of_news' => 'required|string|max:255',
            'description_of_news' => 'required|string',
            'photo_of_news' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'date_of_news' => 'required|date'
        ];
//        if(request()->isMethod('post')) {
//            return [
//                'title_of_news' => 'required|string|max:255',
//                'description_of_news' => 'required|string',
//                'photo_of_news' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//                'date_of_news' => 'required|date'
//            ];
//        }
//        else{
//            return [
//                'title_of_news' => 'required|string|max:255',
//                'description_of_news' => 'required|string',
//                'photo_of_news' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
//                'date_of_news' => 'required|date'
//            ];
//        }
    }
    public function messages(): array
    {
        if(request()->isMethod('post')){
            return [
                //'subadmin_id' => 'required|bigInteger',
                'title_of_news.required' => __('word.title_required'),
                'description_of_news.required' => __('word.desc_required'),
                'photo_of_news.required' => __('word.photo_news_required'),
                'date_of_news.required' => __('word.date_required')
            ];
        }
        else{
            return [
                'title_of_news.required' => __('word.title_required'),
                'description_of_news.required' => __('word.desc_required'),
                'date_of_news.required' => __('word.date_required')
            ];
        }
    }
}
