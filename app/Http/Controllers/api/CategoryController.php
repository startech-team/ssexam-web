<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\study;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CategoryController extends Controller
{

    public function getCategories(Request $request){
        try{
            $category_type = $request->input("category_type");
            $categories = DB::table('category')
            ->select('category_id', 'category_nm')
            ->where('category_type', '=', $category_type )->get(); 
            return response()->json([
                'success' => true,
                'data'=>[
                    'category' => $categories,
                ],
                'message' => ''
            ], 200);
        }
        catch(\Exception $e){
            return response()->json([
                'success' => false,
                'message' => 'System error: ' . $e->getMessage()
            ], 500);
        }

    }
}