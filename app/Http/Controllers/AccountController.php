<?php

namespace App\Http\Controllers;

use App\Models\Exam_acc;
use App\Models\Group_tb;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use DB;

class AccountController extends Controller
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
        $name_mail = request('name_mail');
        $group_id = request('group_id');

        if (Auth::user()->is_admin == 3) {
            $accs = DB::table('users')->where('group_id', '=', Auth::user()->group_id)->orderBy('status')->paginate(10);
        } else {
            if ($name_mail != '' && $group_id != '') {
                $accs = DB::table('users')
                    ->where(function ($query) use ($name_mail) {
                        $query->where('name', 'like', "%{$name_mail}%")->orWhere('email', 'like', "%{$name_mail}%");
                    })
                    ->where('group_id', '=', $group_id)
                    ->orderBy('group_id')
                    ->orderBy('status')
                    ->paginate(10)
                    ->appends(['name_mail' => $name_mail, 'group_id' => $group_id]);
            } else if ($name_mail != '' && $group_id == '') {
                $accs = DB::table('users')->where('name', 'like', "%{$name_mail}%")->orWhere('email', 'like', "%{$name_mail}%")->orderBy('group_id')->orderBy('status')->paginate(10)->appends(['name_mail' => $name_mail, 'group_id' => $group_id]);
            } else if ($group_id != '' && $name_mail == '') {
                $accs = DB::table('users')->where('group_id', '=', $group_id)->orderBy('status')->paginate(10)->appends(['name_mail' => $name_mail, 'group_id' => $group_id]);
            } else {
                $accs = DB::table('users')->orderBy('group_id')->orderBy('status')->paginate(10)->appends(['name_mail' => $name_mail, 'group_id' => $group_id]);
            }
        }

        $groups = Group_tb::all();
        $no = $accs->currentPage() * $accs->perPage() - $accs->perPage() + 1;
        foreach ($accs as $a) {
            if ($a->group_id != null) {
                $g = Group_tb::where('group_id', '=', $a->group_id)->first();
                $a->group_name = $g->group_name;
            }
            if ($a->status != null) {
                if ($a->status == '0') {
                    $a->status_nm = '無効';
                } else {
                    $a->status_nm = '有効';
                }
            }
            $a->no = $no++;
        }

        return view('admin.account.list', [
            'accs' => $accs,
            'name_mail' => $name_mail,
            'group_id' => $group_id,
            'groups' => $groups
        ])->with('activePage', 'account');
    }

    /**
     * 登録
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = Group_tb::all();
        return view('admin.account.insert', compact('groups'))->with('activePage', 'account');
    }

    /**
     * 登録OK
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|Regex:/^[\D]+$/i|max:100',
            'email' => 'required|email|max:255|unique:users',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $name = $request->input('name');
        $email = $request->input('email');
        $group_id = $request->input('group_id');
        $status = '0';
        $url = 'http://localhost:8000/';
        $random = Str::random(8);
        $is_admin = $request->input('is_admin');
        $password = bcrypt($random);
        $data1 = array('name' => $name, 'email' => $email, 'group_id' => $group_id, 'is_admin' => $is_admin, 'password' => $password, 'status' => $status, 'create_at' => Carbon::now());
        $data2 = array('name' => $name, 'email' => $email, 'password' => $random, 'url' => $url);

        Mail::send('admin.account.email-template', $data2, function ($message) use ($data2) {
            $message
                ->to($data2['email'])
                ->subject('【SS EXAM】アカウント情報案内');
        });

        User::create($data1);
        return redirect('/admin/account')->with('success', $name . 'のアカウントを登録しました。');
    }

    /**
     * 更新
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $groups = Group_tb::all();
        $account = User::where('id', '=', $id)->first();
        return view('admin.account.update', compact('account'), compact('groups'))->with('activePage', 'account');
    }

    /**
     * 更新OK
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        request()->validate([
            'name' => 'required',
        ]);

        $user = new User;
        $id = $request->input('id');
        $name = $request->input('name');
        $group_id = $request->input('group_id');
        $is_admin = $request->input('is_admin');
        $user->where('id', $id)->update(['name' => $name, 'group_id' => $group_id, 'is_admin' => $is_admin]);
        return redirect('/admin/account')->with('success', '変更が完了しました。');
    }

    /**
     * 削除
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exam_accs = Exam_acc::where('acc_id', '=', $id)->get();
        if (count($exam_accs) > 0) {
            return redirect('/admin/account')->with('error', 'アカウントは私用されてるためを削除できません。');
        }
        User::where('id', '=', $id)->delete();
        return redirect('/admin/account')->with('success', '削除が完了しました。');
    }

    /**
     * パスワードリセット
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function resetPwd($id)
    {
        $random = Str::random(8);
        $password = bcrypt($random);
        $account = User::where('id', '=', $id)->first();
        User::where('id', '=', $id)->update(['password' => $password]);
        $data = array('name' => $account->name, 'password' => $random, 'email' => $account->email);
        Mail::send('admin.account.reset-pwd-template', $data, function ($message) use ($data) {
            $message
                ->to($data['email'])
                ->subject('【SS EXAM】パスワードリセット');
        });

        return redirect('/admin/account')->with('success', $account->name . 'のパスワードをリセットしました。');
    }

    /**
     * ステータス変更
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function changeStatus($id)
    {
        $account = User::where('id', '=', $id)->first();
        $status_nm = '有効';
        if ($account->status == '0') {
            User::where('id', '=', $id)->update(['status' => '1']);
            $status_nm = '無効';
        } else {
            User::where('id', '=', $id)->update(['status' => '0']);
            $status_nm = '有効';
        }

        return redirect('/admin/account')->with('success', $account->name . 'のアカウントを' . $status_nm . 'にしました。');
    }
}
