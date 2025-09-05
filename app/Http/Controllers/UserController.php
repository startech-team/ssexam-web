<?php

namespace App\Http\Controllers;

use App\Models\UserForm;
use App\Models\Exam;
use App\Models\ExamResult;
use App\Models\Exam_acc;
use App\Models\Exam_acc_detail;
use App\Models\Exam_ques;
use App\Models\ExamForm;
use App\Models\Question;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $id = Auth::id();
        $examaccs = Exam_acc::where('acc_id', '=', $id)->get();
      
        $userexams = array();
        foreach ($examaccs as $e) {
            $userexam = $this->examResultCal($e->exam_id, $id);
            if($userexam->take_exam_status == '1' || $userexam->take_exam_status == '2'){
                array_push($userexams, $userexam);
            }
        }     
        return view('user.result-list', compact('userexams'))->with("title", "SS-EXAM(Dashboard)");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function rule($exam_id)
    {
        $id = Auth::id();
        $take_exam_end_flg =  Exam_acc::where('exam_id', $exam_id)->where('acc_id', $id)->first()->take_exam_end_flg;
        if ($take_exam_end_flg == 1){
            abort(404);
        }else{
            return view('user.exam-rule')->with('exam_id', $exam_id)->with("title", "SS-EXAM(Rule)");
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function detail($exam_id)
    {
        $id = Auth::id();
        $today = Carbon::now()->setTimezone('Asia/Tokyo');;
        $timestamp = strtotime($today);
        $today_str = date("Y/m/d", $timestamp);

        // 受験日を更新
        $exam_acc = new Exam_acc;
        $exam_acc->where('exam_id', $exam_id)->where('acc_id', $id)->update(['take_exam_dt' => $today_str]);
        $exam_acc = Exam_acc::where('exam_id', $exam_id)->where('acc_id', $id)->first();
        $take_exam_end_flg = $exam_acc->take_exam_end_flg;
        if ($take_exam_end_flg == 1){
            abort(404);
        }

        // 問題準備
        $exam = Exam::where('exam_id', '=', $exam_id)->first();
        $examques = Exam_ques::where('exam_id', '=', $exam_id)->orderBy('ord_no', 'asc')->get();
        $question_count = count($examques);
        $examque = Exam_ques::where('exam_id', '=', $exam_id)->orderBy('ord_no', 'asc')->first();
        $q = Question::where('question_id', '=', $examque->question_id)->first();
        $examform = new ExamForm;
        $examform->question_id = $q->question_id;
        $examform->body = $q->body;
        $examform->option1 = $q->option1;
        $examform->option2 = $q->option2;
        $examform->option3 = $q->option3;
        $examform->option4 = $q->option4;
        $examform->ord_no = $examque->ord_no;
        $examform->pre_ord_no = 0;

        $exam_acc_detail = Exam_acc_detail::where('exam_id', '=', $exam_id)
            ->where('acc_id', '=', $id)
            ->where('question_id', '=', $q->question_id)->first();
        $remaing_time = $exam_acc->remaing_time;
        $remaing_time_show = floor($remaing_time / 60) . ":" . ($remaing_time % 60 ? $remaing_time % 60 : '00');

        if ($exam_acc_detail != null) {
            $examform->my_answer = $exam_acc_detail->my_answer;
        }

        if (count($examques) == 1) {
            $examform->nxt_ord_no = 0;
        } else {
            $examform->nxt_ord_no = 2;
        }

        return view('user.exam-detail', compact('examform', 'exam', 'question_count', 'remaing_time', 'remaing_time_show'))->with("title", "SS-EXAM(Detail)");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function examCommit(Request $request)
    {
        $id = Auth::id();

        $exam_acc = Exam_acc::where('exam_id', $request->input('exam_id'))->where('acc_id', $id)->first();
        $take_exam_end_flg = $exam_acc->take_exam_end_flg;
        if ($take_exam_end_flg == 1){
            abort(404);
        }

        $exam_acc_detail = Exam_acc_detail::where('exam_id', '=', $request->input('exam_id'))
            ->where('acc_id', '=', $id)
            ->where('question_id', '=', $request->input('question_id'))->first();
        if ($exam_acc_detail == null) {
            if($request->input('my_answer') == '' || $request->input('my_answer') == null) {
                $data = array('exam_id' => $request->input('exam_id'), 'acc_id' => $id, 'question_id' => $request->input('question_id'), 'my_answer' => '');
                Exam_acc_detail::create($data);
            }
            else{
                $data = array('exam_id' => $request->input('exam_id'), 'acc_id' => $id, 'question_id' => $request->input('question_id'), 'my_answer' => $request->input('my_answer'));
                Exam_acc_detail::create($data);
            }
        } else {
            $exam_acc_detail->where('exam_id', '=', $request->input('exam_id'))
                ->where('acc_id', '=', $id)
                ->where('question_id', '=', $request->input('question_id'))->update(['my_answer' => $request->input('my_answer')]);
        }

        $exam_acc = Exam_acc::where('exam_id', $request->input('exam_id'))->where('acc_id', $id)->first();
        $remaing_time = $exam_acc->remaing_time;
        $remaing_time_show = floor($remaing_time / 60) . ":" . ($remaing_time % 60 ? $remaing_time % 60 : '00');

        // 次へ
        if ($request->input('action') == 'nxtBtn') {
            $examque = Exam_ques::where('exam_id', '=', $request->input('exam_id'))->where('ord_no', '=', $request->input('nxt_ord_no'))->first();
            $q = Question::where('question_id', '=', $examque->question_id)->first();
            $examform = new ExamForm;
            $examform->question_id = $q->question_id;
            $examform->body = $q->body;
            $examform->option1 = $q->option1;
            $examform->option2 = $q->option2;
            $examform->option3 = $q->option3;
            $examform->option4 = $q->option4;
           
            $exam_acc_detail_nxt = Exam_acc_detail::where('exam_id', '=', $request->input('exam_id'))
                ->where('acc_id', '=', $id)
                ->where('question_id', '=', $q->question_id)->first();
            if ($exam_acc_detail_nxt != null) {
                $examform->my_answer = $exam_acc_detail_nxt->my_answer;
            }
            $examform->ord_no = $examque->ord_no;
            $examform->pre_ord_no = $request->input('ord_no');
            if ($request->input('question_count') == $examque->ord_no) {
                $examform->nxt_ord_no = 0;
            } else {
                $examform->nxt_ord_no = $examque->ord_no + 1;
            }

            $question_count = $request->input('question_count');
            $exam = new Exam;
            $exam->exam_id =  $request->input('exam_id');
            $exam->exam_nm = $request->input('exam_nm');

            return view('user.exam-detail', compact('examform', 'exam', 'question_count', 'remaing_time', 'remaing_time_show'))->with("title", "SS-EXAM(Detail)");
        }

        // 前へ
        if ($request->input('action') == 'preBtn') {
            $examque = Exam_ques::where('exam_id', '=', $request->input('exam_id'))->where('ord_no', '=', $request->input('pre_ord_no'))->first();
            $q = Question::where('question_id', '=', $examque->question_id)->first();
            $examform = new ExamForm;
            $examform->question_id = $q->question_id;
            $examform->body = $q->body;
            $examform->option1 = $q->option1;
            $examform->option2 = $q->option2;
            $examform->option3 = $q->option3;
            $examform->option4 = $q->option4;
            $exam_acc_detail_pre = Exam_acc_detail::where('exam_id', '=', $request->input('exam_id'))
                ->where('acc_id', '=', $id)
                ->where('question_id', '=', $q->question_id)->first();
            if ($exam_acc_detail_pre != null) {
                $examform->my_answer = $exam_acc_detail_pre->my_answer;
            }
            $examform->ord_no = $examque->ord_no;
            if ($examque->ord_no == 1) {
                $examform->pre_ord_no = 0;
            } else {
                $examform->pre_ord_no = $examque->ord_no - 1;
            }
            $examform->nxt_ord_no = $request->input('ord_no');

            $question_count = $request->input('question_count');
            $exam = new Exam;
            $exam->exam_id =  $request->input('exam_id');
            $exam->exam_nm = $request->input('exam_nm');

            return view('user.exam-detail', compact('examform', 'exam', 'question_count', 'remaing_time', 'remaing_time_show'))->with("title", "SS-EXAM(Detail)");
        }

        $exam_acc = new Exam_acc;
        $exam_acc->where('exam_id', $request->input('exam_id'))->where('acc_id', $id)->update(['remaing_time' => '0', 'take_exam_end_flg' => '1' ]);
        $examaccs = Exam_acc::where('exam_id', $request->input('exam_id'))->where('acc_id', $id)->get();
        
        $userexams = array();

        foreach ($examaccs as $e) {
            $userexam = $this->examResultCal($e->exam_id, $id);
           try{
            DB::table('exam_results')->insert(
                ['acc_id' => $userexam->acc_id, 
                'exam_id' =>$userexam->exam_id,
                'status' =>$userexam->status,
                'take_exam_status' =>$userexam->take_exam_status,
                'resultmark' =>$userexam->result,
                'win_mark' =>$userexam->win_mark,
                'question_count' => $userexam->question_count,
                'mark' =>$userexam->mark,
                'created_at'=>Carbon::now(),
                'updated_at'=>Carbon::now()
            ],true
              );
            } catch(\Exception $e){
                //if there is an error/exception in the above code before commit, it'll rollback
                DB::rollBack();
               // return $e->getMessage();
             }
           
          
        }
        // 終了
        return view('user.exam-end')->with("title", "SS-EXAM(Detail)");
    }

    /**
     * 
     * 
     */
    private function examResultCal($exam_id, $acc_id)
    {
        $e = Exam_acc::where('exam_id', '=', $exam_id)->where('acc_id', '=', $acc_id)->first();

        $exam = Exam::where('exam_id', '=', $exam_id)->first();
        $userexam = new UserForm;
        $userexam->exam_id = $exam_id;
        $userexam->exam_nm = $exam->exam_nm;
        $userexam->acc_id = $acc_id;
        $userexam->start_dt = $exam->start_dt;
        $userexam->end_dt = $exam->end_dt;
        $userexam->duration = $exam->duration;

        //　合格計算
        $exam_ques = Exam_ques::where('exam_id', '=', $exam_id)->get();
        $question_count = count($exam_ques);
        $win_rate = $exam->win_rate / 100;
        $win_mark = $question_count * $win_rate;
        if ($e->remaing_time == $exam->duration && $e->take_exam_end_flg == null) {
            $userexam->question_count = $question_count;
            $userexam->win_mark = $win_mark;
            $userexam->status = "未";
        } else if ($e->remaing_time <= $exam->duration && $e->remaing_time > 0 && $e->take_exam_end_flg === null) {
            $userexam->question_count = $question_count;
            $userexam->win_mark = $win_mark;
            $userexam->status = "試験中";
        } else {
            $userexam->take_exam_dt = $e->take_exam_dt;
            // 正当な点数取得
            $correct_mark = 0;
            foreach ($exam_ques as $eq) {
                $exam_acc_detail = Exam_acc_detail::where('exam_id', '=', $exam_id)
                    ->where('acc_id', '=', $acc_id)->where('question_id', '=', $eq->question_id)->first();
                if (!empty($exam_acc_detail)) {
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
            $userexam->status = "済";
        }
        $start_dt = Carbon::createFromFormat('Y/m/d', $userexam->start_dt);
        $end_dt = Carbon::createFromFormat('Y/m/d', $userexam->end_dt);
        $today = Carbon::now();

        if ($today->gte($start_dt) && $today->lte($end_dt)) {
            if ($userexam->take_exam_dt == null) {
                // 開始ボタンを表示
                $userexam->take_exam_status = "1";
            } elseif ($e->remaing_time <= $exam->duration && $e->remaing_time > 0 && $e->take_exam_end_flg === null) {
                // 再開始ボタンを表示
                $userexam->take_exam_status = "2";
            } else {
                // 受験が完了しました。
                $userexam->take_exam_status = "3";
            }
        } elseif ($today->lt($start_dt)) {
            // 試験が開始していません。
            $userexam->take_exam_status = "4";
        } elseif ($today->gt($end_dt)) {
            // 試験が終了しました。
            $userexam->take_exam_status = "5";
        } else {
            // 試験が受けれません。
            $userexam->take_exam_status = "6";
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
        return view('user.change-password')->with("title", "SS-EXAM");
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
            'confirm_password' => 'same:new_password',
        ]);
        $password = bcrypt($request->input('new_password'));
        User::where('id', $id)->update(['password' => $password]);
        return redirect('/user');
    }

    public function examCountTime(Request $request)
    {
        $id = Auth::id();
        $exam_id = $request->exam_id;
        $remaing_time = $request->remaing_time;
        $remaing_time = $remaing_time - 5;
        $exam_acc = new Exam_acc;
        $exam_acc->where('exam_id', $exam_id)->where('acc_id', $id)->update(['remaing_time' => $remaing_time]);
        return $remaing_time;
    }

}
