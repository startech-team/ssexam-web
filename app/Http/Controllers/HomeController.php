<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Exam_acc;
use App\Models\Exam_acc_detail;
use App\Models\Exam_ques;
use App\Models\ExamForm;
use App\Models\Group_tb;
use App\Models\Question;
use App\Models\User;
use App\Models\UserForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
//use Barryvdh\DomPDF\Facade\PDF as PDF;
use Barryvdh\DomPDF\Facade\Pdf;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * ログイン
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('login');
    }
    /**
     * ログイン正常：Admin
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function adminHome(Request $request)
    {
        $search_name = '';
        $search_group_id = '';
        $search_exam_id = '';
     //   $userexams = array();
        //dd($request);
        $search_name = $request->input('username');
        $search_group_id = request('group_id');
        $search_exam_id = request('exam_id');

        $examaccs = Exam_acc::all();

        // foreach ($examaccs as $e) {
        //     array_push($userexams, $this->examResultCal($e));
        // }

      $userexams =  DB::table("exam_results")
      ->join('exam_accs', function ($join) {
        $join->on('exam_results.acc_id', '=', 'exam_accs.acc_id')
        ->On("exam_results.exam_id","=","exam_accs.exam_id");
          })
        ->join("exams","exam_results.exam_id","=","exams.exam_id")
        ->join("users","users.id","=","exam_results.acc_id")
        ->join("group_tbs","users.group_id","=","group_tbs.group_id")
        ->select("exams.exam_nm","exams.exam_id","users.name","users.id","exams.duration",
        "exam_results.status","exam_results.take_exam_status","exam_results.win_mark","group_tbs.group_name","exam_results.resultmark as result",
        "exam_accs.take_exam_dt",
        "exams.exam_id","users.id as acc_id",
        "exam_results.question_count","exam_results.mark","exam_results.question_count")->orderBy('exam_results.created_at', 'DESC');

        if($search_name != null){
            $userexams ->where('users.name', 'LIKE', "%{$search_name}%");
        } if($search_group_id != null){
            $userexams ->where("group_tbs.group_id","=", $search_group_id);
        }
        if($search_exam_id != null){
            $userexams ->where("exams.exam_id","=",$search_exam_id);
        }
        if(Auth::user()->is_admin == 3){
            $userexams ->where("group_tbs.group_id","=",Auth::user()->group_id);
       
        }
        $userexams =  $userexams ->paginate(10);
        $userList = Count(User::where('is_admin', '=', '2')->get());
        $groups = Group_tb::all();
        $groupList = Count($groups);
        $exams = Exam::all();
        $examList = Count($exams);
        $questionList = Count(Question::all());

        return view('admin.dashboard.list', compact('userexams', 'userList', 'groups', 'groupList', 'examList', 'questionList', 'exams'))->with("name", $search_name)->with("group_id", $search_group_id)->with("exam_id", $search_exam_id)->with("activePage", "dashboard");
    }

    /**
     * 
     * 
     */
    private function examResultCal($e)
    {

        $exam = Exam::where('exam_id', '=', $e->exam_id)->first();
        $userexam = new UserForm;
        $userexam->exam_id = $e->exam_id;
        $userexam->exam_nm = $exam->exam_nm;
        $userexam->acc_id = $e->acc_id;
        $userexam->start_dt = $exam->start_dt;
        $userexam->end_dt = $exam->end_dt;
        $userexam->duration = $exam->duration / 60;

        $u = User::where('id', '=', $e->acc_id)->first();
        $userexam->name = $u->name;
        if ($u->group_id != null) {
            $g = Group_tb::where('group_id', '=', $u->group_id)->first();
            $userexam->group_id =  $g->group_id;
            $userexam->group_name = $g->group_name;
        }

        //　合格計算
        $exam_ques = Exam_ques::where('exam_id', '=', $e->exam_id)->get();
        $question_count = count($exam_ques);
        $win_rate = $exam->win_rate / 100;
        $win_mark = $question_count * $win_rate;
        if ($e->take_exam_dt == null) {
            $userexam->take_exam_dt = '-';
            $userexam->result = '-';
            $userexam->mark = '-';
            $userexam->question_count = $question_count;
            $userexam->win_mark = $win_mark;
        } elseif ($e->take_exam_dt != null && $e->take_exam_end_flg != '1') {
            $userexam->take_exam_dt = '-';
            $userexam->result = '試験中';
            $userexam->mark = '-';
            $userexam->question_count = $question_count;
            $userexam->win_mark = $win_mark;
        } else {
            $userexam->take_exam_dt = $e->take_exam_dt;
            // 正当な点数取得
            $correct_mark = 0;
            foreach ($exam_ques as $eq) {
                $exam_acc_detail = Exam_acc_detail::where('exam_id', '=', $e->exam_id)
                    ->where('acc_id', '=', $e->acc_id)->where('question_id', '=', $eq->question_id)->first();
                if ($exam_acc_detail != null) {
                    $question = Question::where('question_id', '=', $eq->question_id)->first();
                    if ($question->correct_answer == $exam_acc_detail->my_answer) {
                        $correct_mark = $correct_mark + 1;
                    }
                }
            }
            // 合格/不合格
            if ($correct_mark >= $win_mark) {
                $userexam->result = '合格';
            } else {
                $userexam->result = '不合格';
            }
            $userexam->mark = $correct_mark . '/' .  $question_count;
            $userexam->question_count = $question_count;
            $userexam->win_mark = $win_mark;
        }
        return $userexam;
    }

    /**
     * パスワード変更
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function changePassword()
    {
        return view('admin.dashboard.change-password')->with("success", "")->with("activePage", "");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function changePasswordOk(Request $request)
    {

        $id = Auth::id();
        request()->validate([
            'new_password' => 'required|min:6',
            'confirm_password' => 'required|min:6',
        ]);
        if (($request->input('new_password') != $request->input('confirm_password'))) {
            return back()->withErrors(['ERGP0013' => '新規パスワードと確認パスワードは一致していません!']);
        }
        $password = bcrypt($request->input('new_password'));
        User::where('id', $id)->update(['password' => $password]);
        return redirect()->back()->with("success", "パスワードを変更しました。")->with("activePage", "");
    }

    /**
     * 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dashboardDetail($acc_id, $exam_id)
    {
        // 問題準備
        $exam = Exam::where('exam_id', '=', $exam_id)->first();
        $examques = Exam_ques::where('exam_id', '=', $exam_id)->orderBy('ord_no', 'asc')->get();

        $details = array();
        $correct_answer_count = 0;
        foreach ($examques as $eq) {
            $q = Question::where('question_id', '=', $eq->question_id)->first();
            $ea = Exam_acc_detail::where('exam_id', '=', $exam_id)
                ->where('acc_id', '=', $acc_id)
                ->where('question_id', '=', $eq->question_id)
                ->first();
            $examform = new ExamForm;
            $examform->question_id = $q->question_id;
            $examform->body = $q->body;
            $examform->option1 = $q->option1;
            $examform->option2 = $q->option2;
            $examform->option3 = $q->option3;
            $examform->option4 = $q->option4;
            if ($ea == null || ($ea->my_answer == null || $ea->my_answer == '')) {
                $examform->my_answer = '';
            } else {
                $examform->my_answer = $ea->my_answer;
                if ($examform->my_answer == $q->correct_answer) {
                    $correct_answer_count = $correct_answer_count + 1;
                }
            }
            $examform->correct_answer = $q->correct_answer;
            array_push($details, $examform);
        }

        $exam_nm = $exam->exam_nm;
        $question_count =  count($examques);
        $user = User::where('id', '=', $acc_id)->first();
        $name = $user->name;

        $win_rate = $exam->win_rate / 100;
        $win_mark = $question_count * $win_rate;
        if ($correct_answer_count >= $win_mark) {
            $result = '合格';
        } else {
            $result = '不合格';
        }

        return view('admin.dashboard.detail', compact('details', 'exam_nm', 'question_count', 'name', 'correct_answer_count', 'result', 'acc_id', 'exam_id'))->with("activePage", "dashboard");
    }

    /**
     * 検索
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $search_name = trim($request->input('name'));
        $search_group_id = request('group_id');
        $search_exam_id = request('exam_id');

        $examaccs = Exam_acc::all();
        $userexams = array();
        foreach ($examaccs as $e) {
            array_push($userexams, $this->examResultCal($e));
        }

        $userList = Count(User::where('is_admin', '=', '2')->get());
        $groups = Group_tb::all();
        $groupList = Count($groups);
        $exams = Exam::all();
        $examList = Count($exams);
        $questionList = Count(Question::all());

        if ($search_name != null && $search_group_id = '' && $search_exam_id = '') {
            $userexams = collect($userexams)
                ->where('name', 'LIKE', "%{$search_name}%")
                ->all();
        } elseif ($search_name != null && $search_group_id != '' && $search_exam_id != '') {
            $userexams = collect($userexams)
                ->where('name', 'LIKE', "%{$search_name}%")
                ->where('group_id', '=', "%{$search_group_id}%")
                ->where('exam_id', '=', "%{$search_exam_id}%")
                ->all();
        }


        return view('admin.dashboard.list', compact('userexams', 'userList', 'groups', 'groupList', 'examList', 'questionList', 'exams'))->with("name", $search_name)->with("group_id", $search_group_id)->with("exam_id", $search_exam_id)->with("activePage", "dashboard");
    }

    /**
     * 
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function pdf($acc_id, $exam_id)
    {
        // 問題準備
        $exam = Exam::where('exam_id', '=', $exam_id)->first();
        $exam_acc = Exam_acc::where('exam_id', '=', $exam_id)->where('acc_id', '=', $acc_id)->first();
        $take_exam_dt = $exam_acc->take_exam_dt;
        $examques = Exam_ques::where('exam_id', '=', $exam_id)->orderBy('ord_no', 'asc')->get();

        $details = array();
        $correct_answer_count = 0;
        foreach ($examques as $eq) {
            $q = Question::where('question_id', '=', $eq->question_id)->first();
            $ea = Exam_acc_detail::where('exam_id', '=', $exam_id)
                ->where('acc_id', '=', $acc_id)
                ->where('question_id', '=', $eq->question_id)
                ->first();
            $examform = new ExamForm;
            $examform->question_id = $q->question_id;
            $examform->body = $q->body;
            $examform->option1 = $q->option1;
            $examform->option2 = $q->option2;
            $examform->option3 = $q->option3;
            $examform->option4 = $q->option4;
            if ($ea == null || ($ea->my_answer == null || $ea->my_answer == '')) {
                $examform->my_answer = '';
            } else {
                $examform->my_answer = $ea->my_answer;
                if ($examform->my_answer == $q->correct_answer) {
                    $correct_answer_count = $correct_answer_count + 1;
                }
            }
            $examform->correct_answer = $q->correct_answer;
            array_push($details, $examform);
        }

        $exam_nm = $exam->exam_nm;
        $question_count =  count($examques);
        $user = User::where('id', '=', $acc_id)->first();
        $name = $user->name;

        $win_rate = $exam->win_rate / 100;
        $win_mark = $question_count * $win_rate;
        if ($correct_answer_count >= $win_mark) {
            $result = '合格';
        } else {
            $result = '不合格';
        }

        $pdf = Pdf::loadView('admin.dashboard.pdf_output', ['details' => $details, 'exam_nm' => $exam_nm, 'question_count' => $question_count, 'name' => $name, 'correct_answer_count' => $correct_answer_count, 'result' => $result, 'take_exam_dt' => $take_exam_dt]);
        $pdf->setPaper('A4');
        return $pdf->stream();
    }
}
