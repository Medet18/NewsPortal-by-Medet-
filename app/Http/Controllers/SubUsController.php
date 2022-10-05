<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Subadmin;


class SubUsController extends Controller
{
//for subadmins
    public function index_subadmins(): \Illuminate\Http\JsonResponse
    {
        $sadmins = Subadmin::all();
        return response()->json([__('word.subadmins') => $sadmins], 200);
    }

    public function show_subadmin($lang, $id): \Illuminate\Http\JsonResponse
    {
        $sadmin = Subadmin::find($id);
        if (!$sadmin) {
            return response()->json([__('word.message') => __('word.n_s_sadmin')], 404);
        }

        return response()->json(['subadmin' => $sadmin], 200);
    }

    public function destroy_subadmin($lang, $id): \Illuminate\Http\JsonResponse
    {
        $sadmin = Subadmin::find($id);
        if (!$sadmin) {
            return response()->json([__('word.message') => __('word.n_s_sadmin')],404);//'No such subadmin'], 404);
        } else {
            $sadmin->delete();
            return response()->json([__('word.message') => __('word.sadmin_delete')],200);//'Sadmin successfully deleted!'], 200);
        }
    }

//for users
    public function index_users(): \Illuminate\Http\JsonResponse
    {
        $users = User::all();
        return response()->json([__('word.users') => $users], 200);
    }

    public function show_user($lang, $id): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([__('word.message') => __('word.n_s_user')],404);//'No such user!'], 404);
        }

        return response()->json(['User' => $user], 200);
    }

    public function destroy_user($lang, $id): \Illuminate\Http\JsonResponse
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json([__('word.message') => __('word.n_s_user')], 404);
        } else {
            $user->delete();
            return response()->json([__('word.message') => __('word.user_delete')],200);//'User successfully deleted!'], 200);
        }
    }
}
