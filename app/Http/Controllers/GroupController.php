<?php

namespace App\Http\Controllers;


use App\Models\Group_tb;
use App\Models\User;
use Illuminate\Http\Request;
use DB;

class GroupController extends Controller
{
    public function __construct()
{
    $this->middleware('auth');
}
    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $groups = DB::table('group_tbs')->orderBy('order')->paginate(10);

        $no = $groups->currentPage() * $groups->perPage() - $groups->perPage() + 1;
        foreach ($groups as $g) {
            $g->no = $no++;
            $g->acc_count = User::where('group_id', $g->group_id)->where('status', 0)->count();
        }
        return view('admin.group.list', [
            'groups' => $groups,
            'activePage' => 'group'
        ]);
    }

    /**
     * 登録
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.group.insert')->with("activePage", "group");
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
            'group_name' => 'max:200',
        ]);
        if ($request->input('group_name') == '') {
            request()->validate([
                'ERGP0002' => 'required',
            ]);
        }
        $group_name = $request->input('group_name');
        $groups = Group_tb::where('group_name', '=', $group_name)->first();
        if ($groups === null) {
            $maxOrder = Group_tb::max('order') ?? 0;
            $requestData = $request->all();
            $requestData['order'] = $maxOrder + 1;
            Group_tb::create($requestData);
            return redirect('/admin/group')->with('success', $group_name . "グループを登録しました。");
        } else {
            request()->validate([
                'ERGP0001' => 'required',
            ]);
        }
    }

    /**
     * 更新
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $group = Group_tb::where('group_id', '=', $id)->first();
        return view('admin/group/edit', compact('group'))->with("activePage", 'group');
    }

    /**
     * 更新OK
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        request()->validate([
            'group_name' => 'max:200',
        ]);
        if ($request->input('group_name') == '') {
            request()->validate([
                'ERGP0002' => 'required',
            ]);
        }
        $group = new Group_tb;
        $group_name = $request->input('group_name');
        $group_id = $request->input('group_id');
        $groups = Group_tb::where('group_name', '=', $request->input('group_name'))->first();
        if ($groups === null || $group_name === $group->group_name) {
            $group->where('group_id', $group_id)->update(['group_name' => $group_name]);
            return redirect('/admin/group')->with('success', "{$request->input('group_name')}グループを更新しました。");
        } else {
            request()->validate([
                'ERGP0001' => 'required',
            ]);
        }
    }

    /**
     * 削除
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $users = User::where('group_id', '=', $id)->get();
        if (count($users) > 0) {
            return redirect('/admin/group')->with('error', "グループは私用されてるためを削除できません。");
        }
        $groups = Group_tb::where('group_id', '=', $id)->first();
        $group_name = $groups->group_name;
        $group = Group_tb::where('group_id', '=', $id)->delete();
        return redirect('/admin/group')->with('success', "{$group_name}グループを削除しました。");
    }
}
