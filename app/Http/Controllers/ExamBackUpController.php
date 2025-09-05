<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Exam_acc;
use App\Models\Exam_acc_detail;
use App\Models\Exam_ques;
use App\Models\Group_tb;
use App\Models\Question;
use App\Models\question_type;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ExamBackUpController extends Controller
{
     /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $exams = Exam::all();
        $data = array();
        foreach ($exams as $e) {
            $ques_arr = Exam_ques::where('exam_id', '=', $e->exam_id)->get();
            $e->ques_count = count($ques_arr);

            $acc_arr = Exam_acc::where('exam_id', '=', $e->exam_id)->get();
            $e->acc_count = count($acc_arr);
            $e->duration = $e->duration / 60;
            array_push($data, $e);
        }
        return view('admin.exam.list', compact('data'))->with("activePage", "exam");
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
            $qt = question_type::where('question_type_id', '=', $q->question_type)->first();
            $q->question_type_nm = $qt->question_type_nm;
            $q->chk_flg = '';
            array_push($questions, $q);
        }

        $accountL = User::where('is_admin', '=', 2)->get();
        $accounts = array();
        foreach ($accountL as $a) {
            if ($a->group_id != null) {
                $g = Group_tb::where('group_id', '=', $a->group_id)->first();
                $a->group_name = $g->group_name;
            }
            array_push($accounts, $a);
        }

        $questionList = array();
        $accountList = array();
        $exam_nm = '';
        $win_rate = '';
        $start_dt = '';
        $end_dt = '';
        $duration = '';
        $question_length = 0;
        $account_length = 0;

        return view('admin.exam.insert', compact('questions', 'accounts', 'questionList', 'accountList', 'exam_nm', 'win_rate', 'start_dt', 'end_dt', 'duration', 'question_length', 'account_length'))->with("activePage", "exam");
    }

    /**
     * 登録OK
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // 問題追加
        if ($request->input('action') == 'addQuesBtn') {

            // 問題 ------------------------
            $q_id_org = array();
            if ($request->q_id_org != null) {
                $q_id_org =  $request->q_id_org;
            }
            foreach ($request->question_id as $q) {
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
                $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
                $question->question_type_nm = $qt->question_type_nm;
                $question->chk_flg = '';
                array_push($questionList, $question);
            }

            $questions = array();
            foreach ($q_id_all as $q_all) {
                $question = Question::where('question_id', '=', $q_all)->first();
                $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
                $question->question_type_nm = $qt->question_type_nm;
                $question->chk_flg = '';
                array_push($questions, $question);
            }

            // アカウント ------------------------
            $acc_id_org = array();
            if ($request->acc_id_org != null) {
                $acc_id_org =  $request->acc_id_org;
            }
            $acc_id_all = array();
            $accountL = User::where('is_admin', '=', 2)->get();
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
                $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
                if ($account->group_id != null) {
                    $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                    $account->group_name = $g->group_name;
                }
                array_push($accountList, $account);
            }
            $accounts = array();
            foreach ($acc_id_all as $a) {
                $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
                if ($account->group_id != null) {
                    $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                    $account->group_name = $g->group_name;
                }
                array_push($accounts, $account);
            }
            // 基本情報
            $exam_nm = $request->exam_nm;
            $win_rate = $request->win_rate;
            $start_dt = $request->start_dt;
            $end_dt = $request->end_dt;
            $duration = $request->duration;
            $question_length = count($questionList);
            $account_length = count($accountList);

            return view('admin.exam.insert', compact('questions', 'accounts', 'questionList', 'accountList', 'exam_nm', 'win_rate', 'start_dt', 'end_dt', 'duration', 'question_length', 'account_length'))->with("activePage", "exam");
        }

        // 問題取消
        if ($request->input('action') == 'removeQuesBtn') {

            // 問題 ------------------------
            $q_id_org = $request->q_id_org;
            $q_id_chk = $request->q_id_chk;
            // print(count($q_id_chk));die;
            foreach ($q_id_chk as $qc) {
                if (($key = array_search($qc, $q_id_org)) !== false) {
                    unset($q_id_org[$key]);
                }
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
                $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
                $question->question_type_nm = $qt->question_type_nm;
                $question->chk_flg = '';
                array_push($questionList, $question);
            }

            $questions = array();
            foreach ($q_id_all as $q_all) {
                $question = Question::where('question_id', '=', $q_all)->first();
                $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
                $question->question_type_nm = $qt->question_type_nm;
                $question->chk_flg = '';
                array_push($questions, $question);
            }
            // print(implode(" ",$questionList));die;

            // アカウント ------------------------
            $acc_id_org = array();
            if ($request->acc_id_org != null) {
                $acc_id_org =  $request->acc_id_org;
            }
            $acc_id_all = array();
            $accountL = User::where('is_admin', '=', 2)->get();
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
                $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
                if ($account->group_id != null) {
                    $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                    $account->group_name = $g->group_name;
                }
                array_push($accountList, $account);
            }
            $accounts = array();
            foreach ($acc_id_all as $a) {
                $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
                if ($account->group_id != null) {
                    $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                    $account->group_name = $g->group_name;
                }
                array_push($accounts, $account);
            }
            // 基本情報
            $exam_nm = $request->exam_nm;
            $win_rate = $request->win_rate;
            $start_dt = $request->start_dt;
            $end_dt = $request->end_dt;
            $duration = $request->duration;
            $question_length = count($questionList);
            $account_length = count($accountList);

            return view('admin.exam.insert', compact('questions', 'accounts', 'questionList', 'accountList', 'exam_nm', 'win_rate', 'start_dt', 'end_dt', 'duration', 'question_length', 'account_length'))->with("activePage", "exam");
        }

        // アカウント追加
        if ($request->input('action') == 'addAccBtn') {
            // アカウント ------------------------
            $acc_id_org = array();
            if ($request->acc_id_org != null) {
                $acc_id_org =  $request->acc_id_org;
            }
            foreach ($request->acc_id as $a) {
                array_push($acc_id_org, $a);
            }
            $acc_id_all = array();
            $accountL = User::where('is_admin', '=', 2)->get();
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
                $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
                if ($account->group_id != null) {
                    $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                    $account->group_name = $g->group_name;
                }
                array_push($accountList, $account);
            }
            $accounts = array();
            foreach ($acc_id_all as $a) {
                $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
                if ($account->group_id != null) {
                    $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                    $account->group_name = $g->group_name;
                }
                array_push($accounts, $account);
            }
            // 問題 ------------------------
            $q_id_org = array();
            if ($request->q_id_org != null) {
                $q_id_org =  $request->q_id_org;
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
                $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
                $question->question_type_nm = $qt->question_type_nm;
                $question->chk_flg = '';
                array_push($questionList, $question);
            }

            $questions = array();
            foreach ($q_id_all as $q_all) {
                $question = Question::where('question_id', '=', $q_all)->first();
                $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
                $question->question_type_nm = $qt->question_type_nm;
                $question->chk_flg = '';
                array_push($questions, $question);
            }

            // 基本情報
            $exam_nm = $request->exam_nm;
            $win_rate = $request->win_rate;
            $start_dt = $request->start_dt;
            $end_dt = $request->end_dt;
            $duration = $request->duration;
            $question_length = count($questionList);
            $account_length = count($accountList);
            return view('admin.exam.insert', compact('questions', 'accounts', 'questionList', 'accountList', 'exam_nm', 'win_rate', 'start_dt', 'end_dt', 'duration', 'question_length', 'account_length'))->with("activePage", "exam");
        }

        // アカウント取消
        if ($request->input('action') == 'removeAccBtn') {
            // アカウント ------------------------
            $acc_id_org = $request->acc_id_org;
            $acc_id_chk = $request->acc_id_chk;
            foreach ($acc_id_chk as $ac) {
                if (($key = array_search($ac, $acc_id_org)) !== false) {
                    unset($acc_id_org[$key]);
                }
            }
            $acc_id_all = array();
            $accountL = User::where('is_admin', '=', 2)->get();
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
                $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
                if ($account->group_id != null) {
                    $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                    $account->group_name = $g->group_name;
                }
                array_push($accountList, $account);
            }
            $accounts = array();
            foreach ($acc_id_all as $a) {
                $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
                if ($account->group_id != null) {
                    $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                    $account->group_name = $g->group_name;
                }
                array_push($accounts, $account);
            }
            // 問題 ------------------------
            $q_id_org = array();
            if ($request->q_id_org != null) {
                $q_id_org =  $request->q_id_org;
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
                $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
                $question->question_type_nm = $qt->question_type_nm;
                $question->chk_flg = '';
                array_push($questionList, $question);
            }

            $questions = array();
            foreach ($q_id_all as $q_all) {
                $question = Question::where('question_id', '=', $q_all)->first();
                $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
                $question->question_type_nm = $qt->question_type_nm;
                $question->chk_flg = '';
                array_push($questions, $question);
            }
            // 基本情報
            $exam_nm = $request->exam_nm;
            $win_rate = $request->win_rate;
            $start_dt = $request->start_dt;
            $end_dt = $request->end_dt;
            $duration = $request->duration;
            $question_length = count($questionList);
            $account_length = count($accountList);
            return view('admin.exam.insert', compact('questions', 'accounts', 'questionList', 'accountList', 'exam_nm', 'win_rate', 'start_dt', 'end_dt', 'duration', 'question_length', 'account_length'))->with("activePage", "exam");
        }

        // アカウント登録
        // 試験情報登録
        $exam_nm = $request->input('exam_nm');
        $start_dt = $request->input('start_dt');
        $end_dt = $request->input('end_dt');
        $win_rate = $request->input('win_rate');
        $duration = $request->input('duration');

        $examdata = array('exam_nm' => $exam_nm, 'start_dt' => $start_dt, 'end_dt' => $end_dt, 'win_rate' => $win_rate, 'duration' => $duration, 'create_at' => Carbon::now());
        $exam = Exam::create($examdata);
        $exam_id = $exam->exam_id;

        // 問題情報登録
        $q_id_org = array();
        $q_id_org =  $request->q_id_org;
        $count = 1;
        foreach ($q_id_org as $id) {
            Exam_ques::create(array('exam_id' => $exam_id, 'question_id' => $id, 'ord_no' => $count));
            $count = $count + 1;
        }

        // 対象者
        $acc_id_org = array();
        $acc_id_org =  $request->acc_id_org;
        foreach ($acc_id_org as $id) {
            Exam_acc::create(array('exam_id' => $exam_id, 'acc_id' => $id, 'remaing_time' => $duration));
        }
        return redirect('/admin/exam')->with('success', "試験登録が完了しました。");
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
            $qt = question_type::where('question_type_id', '=', $q->question_type)->first();
            $q->question_type_nm = $qt->question_type_nm;
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
            $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
            $question->question_type_nm = $qt->question_type_nm;
            array_push($questions, $question);
        }

        // 更新前
        $acc_id_all = array();
        $accs = User::where('is_admin', '=', 2)->get();
        $accountL = User::where('is_admin', '=', 2)->get();
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
            $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
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
            $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
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
        $question_length = count($questionList);
        $account_length = count($accountList);

        return view('admin.exam.update', compact('questions', 'accounts', 'questionList', 'accountList', 'status', 'exam_id', 'exam_nm', 'win_rate', 'start_dt', 'end_dt', 'duration', 'question_length', 'account_length'))->with("activePage", "exam");
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
        $status = $request->input('status');
        // 試験情報登録
        $exam_id = $request->input('exam_id');

        // 問題追加
        if ($request->input('action') == 'addQuesBtn') {

            // 問題 ------------------------
            $q_id_org = array();
            if ($request->q_id_org != null) {
                $q_id_org =  $request->q_id_org;
            }
            foreach ($request->question_id as $q) {
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
                $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
                $question->question_type_nm = $qt->question_type_nm;
                $question->chk_flg = '';
                array_push($questionList, $question);
            }

            $questions = array();
            foreach ($q_id_all as $q_all) {
                $question = Question::where('question_id', '=', $q_all)->first();
                $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
                $question->question_type_nm = $qt->question_type_nm;
                $question->chk_flg = '';
                array_push($questions, $question);
            }

            // アカウント ------------------------
            $acc_id_org = array();
            if ($request->acc_id_org != null) {
                $acc_id_org =  $request->acc_id_org;
            }
            $acc_id_all = array();
            $accountL = User::where('is_admin', '=', 2)->get();
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
                $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
                if ($account->group_id != null) {
                    $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                    $account->group_name = $g->group_name;
                }
                array_push($accountList, $account);
            }
            $accounts = array();
            foreach ($acc_id_all as $a) {
                $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
                if ($account->group_id != null) {
                    $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                    $account->group_name = $g->group_name;
                }
                array_push($accounts, $account);
            }

            // 基本情報
            $exam_nm = $request->exam_nm;
            $win_rate = $request->win_rate;
            $start_dt = $request->start_dt;
            $end_dt = $request->end_dt;
            $duration = $request->duration;
            $question_length = count($questionList);
            $account_length = count($accountList);

            return view('admin.exam.update', compact('questions', 'accounts', 'questionList', 'accountList', 'status', 'exam_id', 'exam_nm', 'win_rate', 'start_dt', 'end_dt', 'duration', 'question_length', 'account_length'))->with("activePage", "exam");
        }

        // 問題取消
        if ($request->input('action') == 'removeQuesBtn') {
            // 問題 ------------------------
            $q_id_org = $request->q_id_org;
            $q_id_chk = $request->q_id_chk;
            foreach ($q_id_chk as $qc) {
                if (($key = array_search($qc, $q_id_org)) !== false) {
                    unset($q_id_org[$key]);
                }
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
                $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
                $question->question_type_nm = $qt->question_type_nm;
                $question->chk_flg = '';
                array_push($questionList, $question);
            }

            $questions = array();
            foreach ($q_id_all as $q_all) {
                $question = Question::where('question_id', '=', $q_all)->first();
                $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
                $question->question_type_nm = $qt->question_type_nm;
                $question->chk_flg = '';
                array_push($questions, $question);
            }

            // アカウント ------------------------
            $acc_id_org = array();
            if ($request->acc_id_org != null) {
                $acc_id_org =  $request->acc_id_org;
            }
            $acc_id_all = array();
            $accountL = User::where('is_admin', '=', 2)->get();
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
                $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
                if ($account->group_id != null) {
                    $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                    $account->group_name = $g->group_name;
                }
                array_push($accountList, $account);
            }
            $accounts = array();
            foreach ($acc_id_all as $a) {
                $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
                if ($account->group_id != null) {
                    $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                    $account->group_name = $g->group_name;
                }
                array_push($accounts, $account);
            }
            // 基本情報
            $exam_nm = $request->exam_nm;
            $win_rate = $request->win_rate;
            $start_dt = $request->start_dt;
            $end_dt = $request->end_dt;
            $duration = $request->duration;
            $question_length = count($questionList);
            $account_length = count($accountList);

            return view('admin.exam.update', compact('questions', 'accounts', 'questionList', 'accountList', 'status', 'exam_id', 'exam_nm', 'win_rate', 'start_dt', 'end_dt', 'duration', 'question_length', 'account_length'))->with("activePage", "exam");
        }

        // アカウント追加
        if ($request->input('action') == 'addAccBtn') {
            // アカウント ------------------------
            $acc_id_org = array();
            if ($request->acc_id_org != null) {
                $acc_id_org =  $request->acc_id_org;
            }
            foreach ($request->acc_id as $a) {
                array_push($acc_id_org, $a);
            }
            $acc_id_all = array();
            $accountL = User::where('is_admin', '=', 2)->get();
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
                $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
                if ($account->group_id != null) {
                    $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                    $account->group_name = $g->group_name;
                    if ($status == 'not-edit') {
                        $exam_acc = Exam_acc::where('exam_id', '=', $exam_id)->where('acc_id', '=', $a)->first();
                        if ($exam_acc != null) {
                            $account->exam_status = $exam_acc->take_exam_end_flg;
                        }
                    }
                }
                array_push($accountList, $account);
            }
            $accounts = array();
            foreach ($acc_id_all as $a) {
                $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
                if ($account->group_id != null) {
                    $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                    $account->group_name = $g->group_name;
                }
                array_push($accounts, $account);
            }
            // 問題 ------------------------
            $q_id_org = array();
            if ($request->q_id_org != null) {
                $q_id_org =  $request->q_id_org;
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
                $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
                $question->question_type_nm = $qt->question_type_nm;
                $question->chk_flg = '';
                array_push($questionList, $question);
            }

            $questions = array();
            foreach ($q_id_all as $q_all) {
                $question = Question::where('question_id', '=', $q_all)->first();
                $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
                $question->question_type_nm = $qt->question_type_nm;
                $question->chk_flg = '';
                array_push($questions, $question);
            }
            // 基本情報
            $exam_nm = $request->exam_nm;
            $win_rate = $request->win_rate;
            $start_dt = $request->start_dt;
            $end_dt = $request->end_dt;
            $duration = $request->duration;
            $question_length = count($questionList);
            $account_length = count($accountList);
            return view('admin.exam.update', compact('questions', 'accounts', 'questionList', 'accountList', 'status', 'exam_id', 'exam_nm', 'win_rate', 'start_dt', 'end_dt', 'duration', 'question_length', 'account_length'))->with("activePage", "exam");
        }

        // アカウント取消
        if ($request->input('action') == 'removeAccBtn') {
            // アカウント ------------------------
            $acc_id_org = $request->acc_id_org;
            $acc_id_chk = $request->acc_id_chk;
            foreach ($acc_id_chk as $ac) {
                if (($key = array_search($ac, $acc_id_org)) !== false) {
                    unset($acc_id_org[$key]);
                }
            }
            $acc_id_all = array();
            $accountL = User::where('is_admin', '=', 2)->get();
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
                $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
                if ($account->group_id != null) {
                    $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                    $account->group_name = $g->group_name;
                    if ($status == 'not-edit') {
                        $exam_acc = Exam_acc::where('exam_id', '=', $exam_id)->where('acc_id', '=', $a)->first();
                        if ($exam_acc != null) {
                            $account->exam_status = $exam_acc->take_exam_end_flg;
                        }
                    }
                }
                array_push($accountList, $account);
            }
            $accounts = array();
            foreach ($acc_id_all as $a) {
                $account = User::where('is_admin', '=', 2)->where('id', '=', $a)->first();
                if ($account->group_id != null) {
                    $g = Group_tb::where('group_id', '=', $account->group_id)->first();
                    $account->group_name = $g->group_name;
                }
                array_push($accounts, $account);
            }
            // 問題 ------------------------
            $q_id_org = array();
            if ($request->q_id_org != null) {
                $q_id_org =  $request->q_id_org;
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
                $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
                $question->question_type_nm = $qt->question_type_nm;
                $question->chk_flg = '';
                array_push($questionList, $question);
            }

            $questions = array();
            foreach ($q_id_all as $q_all) {
                $question = Question::where('question_id', '=', $q_all)->first();
                $qt = question_type::where('question_type_id', '=', $question->question_type)->first();
                $question->question_type_nm = $qt->question_type_nm;
                $question->chk_flg = '';
                array_push($questions, $question);
            }
            // 基本情報
            $exam_nm = $request->exam_nm;
            $win_rate = $request->win_rate;
            $start_dt = $request->start_dt;
            $end_dt = $request->end_dt;
            $duration = $request->duration;
            $question_length = count($questionList);
            $account_length = count($accountList);
            return view('admin.exam.update', compact('questions', 'accounts', 'questionList', 'accountList', 'status', 'exam_id', 'exam_nm', 'win_rate', 'start_dt', 'end_dt', 'duration', 'question_length', 'account_length'))->with("activePage", "exam");
        }

        $exam_nm = $request->input('exam_nm');
        $start_dt = $request->input('start_dt');
        $end_dt = $request->input('end_dt');
        $win_rate = $request->input('win_rate');
        $duration = $request->input('duration');
        $acc_id_org =  $request->acc_id_org;
        $q_id_org =  $request->q_id_org;

        if ($status == 'not-edit') {
            // 対象
            $exam_acc_org = Exam_acc::where('exam_id', '=', $exam_id)->get();
            $examdata = array('exam_nm' => $exam_nm, 'updated_at' => Carbon::now());
            $exam = new Exam;
            $exam->where('exam_id', $exam_id)->update($examdata);
            foreach ($exam_acc_org as $exam_acc) {
                if (!in_array($exam_acc->acc_id, $acc_id_org)) {
                    // 試験情報削除
                    Exam_acc::where('exam_id', '=', $exam_id)->where('acc_id', '=', $exam_acc->acc_id)->delete();
                    Exam_acc_detail::where('exam_id', '=', $exam_id)->where('acc_id', '=', $exam_acc->acc_id)->delete();
                }
            }
            foreach ($acc_id_org as $id) {
                // 追加ユーザー
                $exam_acc_exit = Exam_acc::where('exam_id', '=', $exam_id)->where('acc_id', '=', $id)->first();
                if ($exam_acc_exit == null) {
                    Exam_acc::create(array('exam_id' => $exam_id, 'acc_id' => $id, 'remaing_time' => $duration));
                }
            }
        } else {

            $examdata = array('exam_nm' => $exam_nm, 'start_dt' => $start_dt, 'end_dt' => $end_dt, 'win_rate' => $win_rate, 'duration' => $duration, 'updated_at' => Carbon::now());
            $exam = new Exam;
            $exam->where('exam_id', $exam_id)->update($examdata);

            // 問題情報登録
            Exam_ques::where('exam_id', '=', $request->input('exam_id'))->delete();
            $count = 1;
            foreach ($q_id_org as $id) {
                Exam_ques::create(array('exam_id' =>  $exam_id, 'question_id' => $id, 'ord_no' => $count));
                $count = $count + 1;
            }

            // 対象者
            Exam_acc::where('exam_id', '=',  $exam_id)->delete();
            foreach ($acc_id_org as $id) {
                // 追加ユーザー
                Exam_acc::create(array('exam_id' => $exam_id, 'acc_id' => $id, 'remaing_time' => $duration));
            }
        }

        return redirect('/admin/exam')->with('success', "変更が完了しました。");
    }

    /**
     * Remove the specified resource from storage.
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
            return redirect('/admin/exam')->with('error', "試験が開始したため削除できません。");
        }
        Exam::where('exam_id', '=', $id)->delete();
        Exam_acc::where('exam_id', '=', $id)->delete();
        Exam_ques::where('exam_id', '=', $id)->delete();
        return redirect('/admin/exam')->with('success', "試験の削除が完了しました。");
    }

    /**
     * Add Question
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function addQues(Request $request)
    {
        print($request->question_id);
        die;
        $ques = Question::where('question_id', '=', $request->question_id)->first();
        $questionsDisp  = array();
        array_push($questionsDisp, $ques);
        return view('admin.exam.insert', compact('questions'), compact('questionsDisp'))->with("activePage", "exam");
    }
}


