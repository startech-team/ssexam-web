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

class StudysController extends Controller
{
     /**
     * 勉強情報取得
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function study(Request $request)
    {
        $category_id = $request->query('category_id');
        $search_word = $request->query('search_word');
        
        try {
            // Categories Query
            $categoriesQuery = DB::table('category')
                ->select('category_id', 'category_nm')
                ->where('category_id', $category_id)
                ->where('category_type', '=', 2);  

            $categories = $categoriesQuery->get();

            // Studies Query
             $studiesQuery = DB::table('study')
            ->join('category', 'category.category_id', '=', 'study.category_id')
            ->select('study.study_id', 'study.category_id', 'category.category_nm', 'study.title', 'study.body');

            if ($search_word || $category_id) {
                $studiesQuery->where(function ($query) use ($search_word, $category_id) {
                    if ($category_id) {
                        $query->orWhere('study.category_id', '=', $category_id);
                    }
                    
                    if ($search_word) {
                        $query->where('title', 'LIKE', "%$search_word%")
                            ->orWhere('body', 'LIKE', "%$search_word%");
                    }
                    
                });
            }
            $studies = $studiesQuery->get();

            return response()->json([
                'success' => true,
                'data' => [
                    'categories' => $categories,
                    'studies' => $studies,
                ],
                'message' => ''
            ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'System error: ' . $e->getMessage()
            ], 500);
        }
    }

  
}