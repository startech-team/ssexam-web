<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TermController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 一覧取得
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $category_id = request('category_id');
        $categoryTypeList = DB::table('category')
            ->where('category_type', '=', '3')
            ->get();

        $terms = DB::table('term')
            ->join('category', 'category.category_id', '=', 'term.category_id')
            ->select('term.term_id', 'category.category_id', 'category_nm', 'category_type', 'word', 'explanation')
            ->when($category_id, function ($query) use ($category_id) {
                return $query->where('term.category_id', $category_id);
            })
            ->orderBy('term.category_id')
            ->orderBy('term.word')
            ->paginate(10)
            ->appends(['category_id' => $category_id]);

        $no = $terms->currentPage() * $terms->perPage() - $terms->perPage() + 1;
        foreach ($terms as $t) {
            $t->no = $no++;
        }

        return view('admin.term.list', compact('terms', 'categoryTypeList', 'category_id'))->with('activePage', 'term');
    }

    /**
     * 更新
     */
    public function edit(Request $request)
    {
        $categorylist = DB::table('category')
            ->where('category_type', '=', '3')
            ->get();
        $term = DB::table('term')
            ->join('category', 'category.category_id', '=', 'term.category_id')
            ->select('term.term_id', 'category.category_id', 'category_nm', 'category_type', 'word', 'explanation')
            ->where('term.term_id', $request->term_id)
            ->first();
        return view('admin.term.edit', compact('categorylist', 'term'))->with('activePage', 'term');
    }

    /**
     * 更新 OK
     */
    public function update(Request $request)
    {
        $validator = $request->validate([
            'category_id' => 'required',
            'word' => 'required',
            'explanation' => 'required',
        ]);
        $data = DB::table('category')->where('category_id', $request->category_id)->first();
        $update = Term::where('term.term_id', $request->term_id)
            ->update(['category_id' => $request->category_id,
                'word' => $request->word,
                'explanation' => $request->explanation]);
        if ($update) {
            return redirect('/admin/term')->with('success', "{$data->category_nm}に更新しました。");;
        }
    }

    /**
     * 削除
     */
    public function destroy(Request $request)
    {
        Term::where('term_id', '=', $request->id)->delete();
        return redirect('/admin/term')->with('success', '用語を削除しました。');
    }

    /**
     * 新規作成
     */
    public function create(Request $request)
    {
        $categorylist = DB::table('category')
            ->where('category_type', '=', '3')
            ->get();
        return view('admin.term.insert', compact('categorylist'))->with('activePage', 'term');
    }

    /**
     * 新規作成 OK
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'category_id' => 'required',
            'word' => 'required',
            'explanation' => 'required',
        ]);
        $term = new Term();
        $term->category_id = $request->category_id;
        $term->word = $request->word;
        $term->explanation = $request->explanation;
        $term->save();
        return redirect('admin/term')->with('success', $request->word . 'を登録しました。');
    }
}
