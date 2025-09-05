<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Exam;
use App\Models\Exam_acc;
use App\Models\Exam_acc_detail;
use App\Models\Exam_ques;
use App\Models\Group_tb;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Arr;

class ExamController extends Controller
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
        $exam_nm = request('exam_nm');

        if ($exam_nm != '') {
            $exams = DB::table('exams')->where('exam_nm', 'like', "%{$exam_nm}%")->orderBy('start_dt', 'desc')->paginate(10)->appends(['exam_nm' => $exam_nm]);
        } else {
            $exams = DB::table('exams')->orderBy('start_dt', 'desc')->paginate(10)->appends(['exam_nm' => $exam_nm]);
        }
        $no = $exams->currentPage() * $exams->perPage() - $exams->perPage() + 1;
        foreach ($exams as $e) {
            $ques_arr = Exam_ques::where('exam_id', '=', $e->exam_id)->get();
            $e->ques_count = count($ques_arr);

            $acc_arr = Exam_acc::where('exam_id', '=', $e->exam_id)->get();
            $e->acc_count = count($acc_arr);
            $e->duration = $e->duration / 60;
            if ($e->exam_type != null && $e->exam_type != '') {
                $category = Category::where('category_id', '=', $e->exam_type)->first();
                if ($category != null) {
                    $e->exam_type = $category->category_nm;
                }
            }
            $examGroup = DB::table('exam_results')
                ->join('exams', 'exams.exam_id', '=', 'exam_results.exam_id')
                ->select(
                    DB::raw('SUM(CASE WHEN exam_results.resultmark = "不合格" THEN 1 ELSE 0 END) as failed_count'),
                    DB::raw('SUM(CASE WHEN exam_results.resultmark = "合格" THEN 1 ELSE 0 END) as passed_count')
                )
                ->where('exam_results.exam_id', '=', $e->exam_id)
                ->first();

            $e->failed_count = $examGroup->failed_count ?? 0;
            $e->passed_count = $examGroup->passed_count ?? 0;

            $e->no = $no++;
        }
        return view('admin.exam.list', [
            'exams' => $exams,
            'exam_nm' => $exam_nm,
            'activePage' => 'exam'
        ]);
    }

    /**
     * 登録
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $questionL = Question::all();
        $questions = array();
        foreach ($questionL as $q) {
            // $qt = question_type::where('question_type_id', '=', $q->question_type)->first();
            $qt = Category::where('category.category_id', '=', $q->question_type)->first();
            $q->category_nm = $qt->category_nm;
            $q->chk_flg = '';
            array_push($questions, $q);
        }
        // dd($questions);
        $accounts = User::join('group_tbs', 'users.group_id', '=', 'group_tbs.group_id')
            ->where(function ($query) {
                $query
                    ->where('is_admin', '=', 2)
                    ->orWhere('is_admin', '=', 3)
                    ->orWhere('is_admin', '=', 4);
            })
            ->where('status', '=', 0)
            ->select('users.*', 'group_tbs.group_name', 'group_tbs.order')
            ->orderBy('group_tbs.order', 'asc')
            ->get();

        $categorylist = DB::table('category')
            ->where('category_type', '=', '4')
            ->get();

        $questionList = array();
        $accountList = array();
        $exam_nm = '';
        $win_rate = '';
        $start_dt = '';
        $end_dt = '';
        $duration = '';
        $question_length = 0;
        $account_length = 0;

        return view('admin.exam.insert', compact('questions', 'accounts', 'questionList', 'accountList', 'exam_nm', 'win_rate', 'start_dt', 'end_dt', 'duration', 'question_length', 'account_length', 'categorylist'))->with('activePage', 'exam');
    }

    /**
     * 問題をテーブルに追�
     */
    public function questionadd(Request $request)
    {
        $q_id_org = array();
        if ($request->q_id_org != null) {
            $q_id_org = $request->q_id_org;
        }
        foreach ($request->rows_selected as $q) {
            array_push($q_id_org, $q);
        }

        $q_id_all = array();
        $questionL = Question::all();
        foreach ($questionL as $qc) {
            array_push($q_id_all, $qc->question_id);
        }

        foreach ($q_id_org as $qc) {
            if (($key = array_search($qc, $q_id_all)) !== false) {
                unset($q_id_all[$key]);
            }
        }

        $questionList = array();
        foreach ($q_id_org as $q_org) {
            $question = Question::where('question_id', '=', $q_org)->first();
            $qt = Category::where('category_id', '=', $question->question_type)->first();
            // $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
            $question->category_nm = $qt->category_nm;
            $question->chk_flg = '';
            array_push($questionList, $question);
        }

        return response()->json($questionList);
    }

    /**
     * 対象者をテーブルに追�
     */
    public function accountadd(Request $request)
    {
        $acc_id_org = array();
        if ($request->rows_selected_acc != null) {
            $acc_id_org = $request->rows_selected_acc;
        }
        $acc_id_all = array();
        $accountL = User::where('is_admin', '=', 2)->orWhere('is_admin', '=', 3)->orWhere('is_admin', '=', 4)->get();
        foreach ($accountL as $ac) {
            array_push($acc_id_all, $ac->id);
        }
        foreach ($acc_id_org as $ac) {
            if (($key = array_search($ac, $acc_id_all)) !== false) {
                unset($acc_id_all[$key]);
            }
        }
        $accountList = array();
        foreach ($acc_id_org as $a) {
            $account = User::where('id', '=', $a)->first();
            if ($account->group_id != null) {
                $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                $account->group_name = $g->group_name;
            }
            array_push($accountList, $account);
        }

        return response()->json($accountList);
    }

    /**
     * 登録OK
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // アカウント登録
        // 試験情報登録
        $exam_nm = $request->input('exam_nm');
        $start_dt = $request->input('start_dt');
        $end_dt = $request->input('end_dt');
        $win_rate = $request->input('win_rate');
        $duration = $request->input('duration');
        $exam_type = $request->input('exam_type');
        $examdata = array('exam_nm' => $exam_nm, 'start_dt' => $start_dt, 'end_dt' => $end_dt, 'win_rate' => $win_rate, 'duration' => $duration, 'exam_type' => $exam_type, 'create_at' => Carbon::now());
        $exam = Exam::create($examdata);
        $exam_id = $exam->exam_id;

        // 問題情報登録
        $q_id_org = array();
        $q_id_org = $request->q_id_chk_hidden;
        $count = 1;
        foreach ($q_id_org as $id) {
            Exam_ques::create(array('exam_id' => $exam_id, 'question_id' => $id, 'ord_no' => $count));
            $count = $count + 1;
        }

        // 対象者

        $acc_id_org = array();
        $acc_id_org = $request->acc_id_chk_hidden;

        foreach ($acc_id_org as $id) {
            Exam_acc::create(array('exam_id' => $exam_id, 'acc_id' => $id, 'remaing_time' => $duration));
        }
        return redirect('/admin/exam')->with('success', '試験登録が完了しました。');
    }

    /**
     * 更新
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $exam = Exam::where('exam_id', '=', $id)->first();

        $exam_ques = Exam_ques::where('exam_id', '=', $id)->get();
        $q_id_org = array();
        $questionList = array();
        for ($i = 0; $i < count($exam_ques); $i++) {
            $q = Question::where('question_id', '=', $exam_ques[$i]->question_id)->first();
            $qt = Category::where('category_id', '=', $q->question_type)->first();

            $q->category_nm = $qt->category_nm;
            array_push($q_id_org, $exam_ques[$i]->question_id);
            array_push($questionList, $q);
        }

        $exam_accs = Exam_acc::where('exam_id', '=', $id)->get();
        $acc_id_org = array();
        foreach ($exam_accs as $exam_acc) {
            array_push($acc_id_org, $exam_acc->acc_id);
        }

        // 更新前
        $q_id_all = array();
        $questionL = Question::all();
        foreach ($questionL as $qc) {
            array_push($q_id_all, $qc->question_id);
        }
        foreach ($q_id_org as $qc) {
            if (($key = array_search($qc, $q_id_all)) !== false) {
                unset($q_id_all[$key]);
            }
        }
        $questions = array();
        foreach ($q_id_all as $q) {
            $question = Question::where('question_id', '=', $q)->first();
            $qt = Category::where('category.category_id', '=', $question->question_type)->first();
            $question->question_type_nm = $qt->category_nm;
            array_push($questions, $question);
        }

        // 更新前
        $acc_id_all = array();
        $accountL = User::join('group_tbs', 'users.group_id', '=', 'group_tbs.group_id')
            ->where(function ($query) {
                $query
                    ->where('is_admin', '=', 2)
                    ->orWhere('is_admin', '=', 3)
                    ->orWhere('is_admin', '=', 4);
            })
            ->where('status', '=', 0)
            ->select('users.*', 'group_tbs.group_name', 'group_tbs.order')
            ->orderBy('group_tbs.order', 'asc')
            ->get();

        foreach ($accountL as $ac) {
            array_push($acc_id_all, $ac->id);
        }

        foreach ($acc_id_org as $ac) {
            if (($key = array_search($ac, $acc_id_all)) !== false) {
                unset($acc_id_all[$key]);
            }
        }

        $accountList = array();
        foreach ($acc_id_org as $a) {
            $account = User::where('id', '=', $a)->first();
            if ($account->group_id != null) {
                $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                $account->group_name = $g->group_name;
            }
            $exam_acc = Exam_acc::where('exam_id', '=', $id)->where('acc_id', '=', $a)->first();
            $account->exam_status = $exam_acc->take_exam_end_flg;
            array_push($accountList, $account);
        }

        $accounts = array();
        foreach ($acc_id_all as $a) {
            $account = User::where('id', '=', $a)->first();
            if ($account->group_id != null) {
                $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                $account->group_name = $g->group_name;
            }
            array_push($accounts, $account);
        }

        $start_dt = Carbon::createFromFormat('Y/m/d', $exam->start_dt);
        $today = Carbon::now();
        $status = 'edit';
        if ($today->gte($start_dt)) {
            $status = 'not-edit';
        }
        $exam_id = $exam->exam_id;
        $exam_nm = $exam->exam_nm;
        $win_rate = $exam->win_rate;
        $start_dt = $exam->start_dt;
        $end_dt = $exam->end_dt;
        $duration = $exam->duration;
        $exam_type = $exam->exam_type;
        $question_length = count($questionList);
        $account_length = count($accountList);
        $updateStartDate = Carbon::now()->format('Y/m/d');
        $updateEndDate = Carbon::now()->format('Y/m/d');
        $updateExamNm = '【再テスト】' . $exam->exam_nm;
        $examID = $exam->exam_id;
        $winRate = $exam->win_rate;
        $examType = $exam->exam_type;

        $categorylist = DB::table('category')
            ->where('category_type', '=', '4')
            ->get();

        // 再テスト
        if (Carbon::parse($end_dt) < Carbon::now()) {
            $retestFlg = true;
        } else {
            $retestFlg = false;
        }

        return view('admin.exam.update', compact('questions', 'accounts', 'questionList', 'accountList', 'status', 'exam_id', 'exam_nm', 'win_rate', 'start_dt', 'end_dt', 'duration', 'exam_type', 'question_length', 'account_length', 'categorylist', 'updateStartDate', 'updateEndDate', 'updateExamNm', 'examID', 'winRate', 'examType', 'retestFlg'))->with('activePage', 'exam');
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
        // dd($request);
        $status = $request->input('status');
        // 試験情報登録
        $exam_id = $request->input('exam_id');

        $exam_nm = $request->input('exam_nm');
        $start_dt = $request->input('start_dt');
        $end_dt = $request->input('end_dt');
        $win_rate = $request->input('win_rate');
        $duration = $request->input('duration');
        $exam_type = $request->input('exam_type');
        $q_id_org = $request->q_id_org;
        $acc_id_org = $request->acc_id_org;

        if ($status == 'not-edit') {
            // 対象
            $exam_acc_org = Exam_acc::where('exam_id', '=', $exam_id)->get();
            $examdata = array('exam_nm' => $exam_nm, 'exam_type' => $exam_type, 'updated_at' => Carbon::now());
            $exam = new Exam;
            $exam->where('exam_id', $exam_id)->update($examdata);
            foreach ($exam_acc_org as $exam_acc) {
                if (!in_array($exam_acc->acc_id, $acc_id_org)) {
                    // 試験情報削除
                    Exam_acc::where('exam_id', '=', $exam_id)->where('acc_id', '=', $exam_acc->acc_id)->delete();
                    Exam_acc_detail::where('exam_id', '=', $exam_id)->where('acc_id', '=', $exam_acc->acc_id)->delete();
                }
            }
            if ($acc_id_org) {
                foreach ($acc_id_org as $id) {
                    // 追加ユーザー
                    $exam_acc_exit = Exam_acc::where('exam_id', '=', $exam_id)->where('acc_id', '=', $id)->first();

                    if ($exam_acc_exit == null) {
                        // dd($id);
                        Exam_acc::create(array('exam_id' => $exam_id, 'acc_id' => $id, 'remaing_time' => $duration));
                    }
                }
            }
        } else {
            $examdata = array('exam_nm' => $exam_nm, 'start_dt' => $start_dt, 'end_dt' => $end_dt, 'win_rate' => $win_rate, 'duration' => $duration, 'exam_type' => $exam_type, 'updated_at' => Carbon::now());
            $exam = new Exam;
            $exam->where('exam_id', $exam_id)->update($examdata);

            // 問題情報登録
            Exam_ques::where('exam_id', '=', $request->input('exam_id'))->delete();
            $count = 1;
            if ($acc_id_org) {
                foreach ($q_id_org as $id) {
                    Exam_ques::create(array('exam_id' => $exam_id, 'question_id' => $id, 'ord_no' => $count));
                    $count = $count + 1;
                }
            }

            // 対象者
            Exam_acc::where('exam_id', '=', $exam_id)->delete();
            if ($acc_id_org) {
                foreach ($acc_id_org as $id) {
                    // 追加ユーザー
                    Exam_acc::create(array('exam_id' => $exam_id, 'acc_id' => $id, 'remaing_time' => $duration));
                }
            }
        }

        return redirect('/admin/exam')->with('success', '変更が完了しました。');
    }

    /**
     * 削除
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exam = Exam::where('exam_id', '=', $id)->first();
        $start_dt = Carbon::createFromFormat('Y/m/d', $exam->start_dt);
        $today = Carbon::now();
        if ($today->gte($start_dt)) {
            $count = DB::table('exam_results')->where('exam_id', '=', $id)->count();
            if ($count > 0) {
                return redirect('/admin/exam')->with('error', '試験が開始して、受験中もあったため削除できません。');
            }
        }
        Exam::where('exam_id', '=', $id)->delete();
        Exam_acc::where('exam_id', '=', $id)->delete();
        Exam_ques::where('exam_id', '=', $id)->delete();
        return redirect('/admin/exam')->with('success', '試験の削除が完了しました。');
    }

    /**
     * 問題追�
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addQues(Request $request)
    {
        print ($request->question_id);
        die;
        $ques = Question::where('question_id', '=', $request->question_id)->first();
        $questionsDisp = array();
        array_push($questionsDisp, $ques);
        return view('admin.exam.insert', compact('questions'), compact('questionsDisp'))->with('activePage', 'exam');
    }

    /**
     * 再テスト
     * @param \Illuminate\Http\Request $request
     * @return mixed|\Illuminate\Http\JsonResponse
     */
    public function reexam(Request $request)
    {
        DB::beginTransaction();  // Start the transaction
        $request->validate([
            'examID' => 'required',
            'updateExamNm' => 'required|string',
        ]);

        $examdata = array(
            'exam_nm' => $request->updateExamNm,
            'start_dt' => $request->updateStartDate,
            'end_dt' => $request->updateEndDate,
            'win_rate' => $request->winRate,
            'duration' => $request->duration,
            'exam_type' => $request->examType,
            'create_at' => Carbon::now()
        );
        $exam = Exam::create($examdata);
        $failedUserList = DB::table('exam_results')
            ->where('resultmark', '=', '不合格')
            ->where('exam_id', '=', $request->examID)
            ->get();
        $questionList = DB::table('exam_ques')
            ->where('exam_id', '=', $request->examID)
            ->get();
        foreach ($failedUserList as $data) {
            Exam_acc::create(array('exam_id' => $exam->exam_id,
                'acc_id' => $data->acc_id));
        }
        foreach ($questionList as $question) {
            DB::table('exam_ques')->insert([
                'exam_id' => $exam->exam_id,
                'question_id' => $question->question_id,
                'ord_no' => $question->ord_no,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        DB::commit();
        return redirect('/admin/exam')->with('success', '再テストの作成が完了しました。');
    }
}
