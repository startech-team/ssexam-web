<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TermsController extends Controller
{

    /**
     * 用語情報取得
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function term(Request $request)
    {
        // param
        $category_id = $request->category_id;
        $search_word = $request->query('search_word');
        try {
            // 検索 category,term
            $category = DB::table("category")
                ->select('category_id', 'category_nm')
                ->where('category_id', $category_id)
                ->where('category_type', '=', 3)
                ->get();

            $termquery = DB::table("term")
            ->join('category', 'category.category_id', '=', 'term.category_id')
            ->select('term.term_id','category.category_id','category_nm','word','explanation');
            
            if ($category_id || $search_word) {
                $termquery->where(function ($query) use ($search_word, $category_id) {
                    if ($category_id) {
                        $query->orWhere('term.category_id', '=', $category_id);
                    }
                    if ($search_word) {
                        $query->where('word', 'LIKE', "%$search_word%")
                            ->orWhere('explanation', 'LIKE', "%$search_word%");
                    }
                });
            }
            $term = $termquery->get();

            if (!$term) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terms not found'
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => [
                    'category' => $category,
                    'term' => $term,
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