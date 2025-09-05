<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Study;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StudyController extends Controller
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
            ->where('category_type', '=', '2')
            ->get();

        $studys = DB::table('study')
            ->leftJoin('category', 'category.category_id', '=', 'study.category_id')
            ->select('study.study_id', 'study.category_id', 'category_nm', 'category_type', 'study.title', 'study.body')
            ->where('category.category_type', '=', 2)
            ->when($category_id, function ($query) use ($category_id) {
                return $query->where('study.category_id', $category_id);
            })
            ->orderBy('study.category_id')
            ->orderBy('study.title')
            ->paginate(10)
            ->appends(['category_id' => $category_id]);

        $no = $studys->currentPage() * $studys->perPage() - $studys->perPage() + 1;
        foreach ($studys as $s) {
            $s->no = $no++;
        }

        return view('admin.study.list', compact('studys', 'categoryTypeList', 'category_id'))->with('activePage', 'study');
    }

    /**
     * 登録
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categoryType = DB::table('category')
            ->where('category_type', '=', '2')
            ->get();
        return view('admin.study.insert', compact('categoryType'))->with('activePage', 'study');
    }

    /**
     * 登録OK
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'title' => 'required',
            'body' => 'required',
        ]);

        $category_id = $request->input('category_id');
        $title = $request->input('title');
        $body = $request->input('body');
        $data = array('category_id' => $category_id, 'title' => $title, 'body' => $body, 'create_at' => Carbon::now()->setTimezone('Asia/Tokyo'));
        Study::create($data);
        return redirect('/admin/study')->with('success', "{$request->input('title')}登録が完了しました。");
    }

    /**
     * 更新
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($study_id)
    {
        // Fetch all categories
        $categories = DB::table('category')
            ->where('category_type', '=', 2)
            ->get();

        // Fetch the study by its ID
        $study = DB::table('study')
            ->join('category', 'category.category_id', '=', 'study.category_id')
            ->select('study.study_id', 'category.category_id', 'category_nm', 'category_type', 'title', 'body')
            ->where('study.study_id', $study_id)
            ->first();
        return view('admin.study.edit', compact('categories', 'study'))->with('activePage', 'study');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        request()->validate([
            'title' => 'required',
            'body' => 'required',
        ]);
        if ($request->input('title') == '') {
            request()->validate([
                'ERGP0012' => 'required',
            ]);
        }
        if ($request->input('body') == '') {
            request()->validate([
                'ERGP0012' => 'required',
            ]);
        }

        $Study = new Study;
        $study_id = $request->input('study_id');
        $category_id = $request->input('category_id');
        $title = $request->input('title');
        $body = $request->input('body');
        $updated_at = Carbon::now()->setTimezone('Asia/Tokyo');

        $Study->where('study_id', $study_id)->update(['category_id' => $category_id, 'title' => $title, 'body' => $body, 'updated_at' => $updated_at]);
        return redirect('/admin/study')->with('success', "{$request->input('title')}グループを更新しました。");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        Study::where('study_id', '=', $request->id)->delete();
        return redirect('/admin/study')->with('success', '勉強を削除しました。');
    }
}
