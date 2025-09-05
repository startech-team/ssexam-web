<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Study;
use App\Models\Term;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
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
        $category_type = request('category_type');

        $categories = DB::table('category')
        ->when($category_type, function ($query) use ($category_type) {
            $query->where('category_type', $category_type);
        })->paginate(10)->appends(['category_type' => $category_type]);

        $no = $categories->currentPage() * $categories->perPage() - $categories->perPage() + 1;
        foreach ($categories as $category) {
            if ($category->category_icon != null) {
                $iconData = base64_encode($category->category_icon);
                $iconSrc = 'data:image/jpeg;base64,' . $iconData;
                $category->category_icon = $iconSrc;
            }
            $category->no = $no++;
        }
        return view('admin.category.list', compact('categories', 'category_type'))->with('activePage', 'category');
    }

    /**
     * 削除
     */
    public function destroy(Request $request)
    {
        $study = Study::where('category_id', '=', $request->id)->get();
        $term = Term::where('category_id', '=', $request->id)->get();

        if (count($study) > 0 || count($term) > 0) {
            return redirect('/admin/category')->with('error', 'カテゴリは私用されてるためを削除できません。');
        }
        Category::where('category_id', '=', $request->id)->delete();
        return redirect('/admin/category')->with('success', 'カテゴリを削除しました。');
    }

    /**
     * 登録
     */
    public function create()
    {
        return view('admin.category.insert')->with('activePage', 'category');
    }

    /**
     * 登録OK
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'category_nm' => 'required',
            'category_type' => 'required',
            'category_icon' => 'image|mimes:jpeg,png,jpg|max:1024',  // 1MB Max
        ]);

        $category = new Category();
        $category->category_nm = $request->category_nm;
        $category->category_type = $request->category_type;
        if ($request->hasFile('category_icon')) {
            $category_icon = $request->file('category_icon');
            $binaryData = file_get_contents($category_icon->getRealPath());
            $category->category_icon = $binaryData;
        }
        $category->save();
        return redirect('/admin/category')->with('success', "{$request->category_nm}登録が完了しました。");
    }

    /**
     * 更新
     */
    public function edit(Request $request)
    {
        $category = Category::where('category_id', '=', $request->id)->first();
        if ($category->category_icon != null) {
            $iconData = base64_encode($category->category_icon);
            $iconSrc = 'data:image/jpeg;base64,' . $iconData;
            $category->category_icon = $iconSrc;
        }
        return view('admin.category.edit', compact('category'))->with('activePage', 'category');
    }

    /**
     * 更新OK
     */
    public function update(Request $request)
    {
        $validator = $request->validate([
            'category_nm' => 'required',
            'category_type' => 'required',
            'category_icon' => 'image|mimes:jpeg,png,jpg|max:1024',  // 1MB Max
        ]);

        $update1 = false;
        if ($request->hasFile('category_icon')) {
            $category_icon = $request->file('category_icon');
            $binaryData = file_get_contents($category_icon->getRealPath());
            if ($binaryData != null) {
                $update1 = Category::where('category_id', '=', $request->id)->update([
                    'category_icon' => $binaryData
                ]);
            }
        }

        $update2 = Category::where('category_id', '=', $request->id)->update([
            'category_nm' => $request->category_nm,
            'category_type' => $request->category_type,
        ]);

        if ($update1 || $update2) {
            return redirect('/admin/category')->with('success', "{$request->category_nm}に更新しました。");
        }
    }
}
