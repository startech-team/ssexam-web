<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Exam_acc;
use App\Models\User;
use App\Models\UserForm;
use App\Models\Exam_acc_detail;
use App\Models\Exam_ques;
use App\Models\Question;
use App\Models\ExamResult;
use App\Models\Group_tb;
use App\Models\PersonalAccessToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;

class ExamController extends Controller
{

    /**
     * 試験情報取得
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function dashboard_exam(Request $request)
    {
        // version
        $version = "v1";
        // param
        $acc_id = $request->query('acc_id');

        try {
          // ログインしてない場合
          if($acc_id == 0){
              // 検索
              $query = DB::table("exams")
                    ->leftJoin('category', 'exams.exam_type', '=', 'category.category_id')
                    ->select(
                        'exams.exam_id',
                        'exams.exam_nm',
                        DB::raw("DATE_FORMAT(exams.start_dt, '%Y/%m/%d') as start_dt"),
                        DB::raw("DATE_FORMAT(exams.end_dt, '%Y/%m/%d') as end_dt"),
                        'exams.duration',
                        DB::raw('(SELECT COUNT(*) FROM exam_ques WHERE exam_ques.exam_id = exams.exam_id) as question_count'),
                        'category.category_icon'
                    )
                    ->orderBy('exams.start_dt', 'asc')
                    ->where('exams.exam_id', '=', 57)
                    ->orWhere('exams.exam_id', '=', 58)
                    ->orWhere('exams.exam_id', '=', 59)
                    ->orWhere('exams.exam_id', '=', 60);

              $data = $query->get();

              $dataList = array();
              foreach ($data as $exam) {
                  if($exam->category_icon != null){
                      $iconData = base64_encode($exam->category_icon);
                      $iconSrc = 'data:image/jpeg;base64,'.$iconData;
                      $exam->category_icon = $iconSrc;
                  }
                  array_push($dataList, $exam);
              }

              return response()->json([
                    'success' => true,
                    'data' => $dataList,
                    'message' => '',
                    'version' => $version,
                  ], 200);
          }
          // ログインしている場合
          elseif($acc_id > 0){
              // 検索
              $query = DB::table("exams")
                  ->leftJoin('exam_accs', 'exams.exam_id', '=', 'exam_accs.exam_id')
                  ->leftJoin('category', 'exams.exam_type', '=', 'category.category_id')
                  ->select(
                      'exams.exam_id',
                      'exams.exam_nm',
                      DB::raw("DATE_FORMAT(exams.start_dt, '%Y/%m/%d') as start_dt"),
                      DB::raw("DATE_FORMAT(exams.end_dt, '%Y/%m/%d') as end_dt"),
                      'exams.duration',
                      DB::raw('(SELECT COUNT(*) FROM exam_ques WHERE exam_ques.exam_id = exams.exam_id) as question_count'),
                      'exam_accs.take_exam_dt',
                      'exam_accs.take_exam_end_flg',
                      'category.category_icon'
                  )
                  ->orderBy('exams.start_dt', 'asc')
                  ->where('exam_accs.acc_id', '=', $acc_id)
                  ->limit(5);

              $data = $query->get();

              if ($data->isEmpty()) {
                  return response()->json([
                      'success' => true,
                      'message' => '',
                      'version' => $version,
                  ], 200);
              }
              
              // Retrieve Exam Result
              $collection = new Collection();
              foreach($data as $examData){
                  $exam_result = ExamResult::where('acc_id', $acc_id)
                  ->where('exam_id', $examData->exam_id)
                  ->first();
                  if($exam_result == null){
                      $examData->resultmark = null;
                      $examData->mark = null;
                  }
                  else{
                      $examData->resultmark = $exam_result->resultmark;
                      $examData->mark = $exam_result->mark;
                  }

                  if($examData->category_icon != null && $examData->category_icon != '' ){
                      $iconData = base64_encode($examData->category_icon);
                      $iconSrc = 'data:image/jpeg;base64,'.$iconData;
                      $examData->category_icon = $iconSrc;
                  }
                  $collection->push($examData);
              }

              $data = $collection;
              return response()->json([
                    'success' => true,
                    'data' => $data,
                    'message' => '',
                    'version' => $version,
                  ], 200);
          }
          else{
              return response()->json([
                  'success' => false,
                  'message' => 'アクセスできないです。'
              ], 404);
          }
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Query Error'
            ], 500);
        } catch (\Exception $e) {
            print($e);
            return response()->json([
                'success' => false,
                'message' => 'System Error'
            ], 500);
        }
    }


    /**
     * 試験情報取得
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function exams(Request $request)
    {
        // param
        $acc_id = $request->query('acc_id');
        $exam_nm = $request->query('exam_nm');
        $exam_date = $request->query('exam_date');
        $exam_start_date = Carbon::create($exam_date . "/01")->startOfDay()->toDateString();
        $exam_end_date = Carbon::create($exam_date . "/31")->endOfDay()->toDateString();
        $array = explode('/', $exam_date);
        $year = $array[0];
        $month = $array[1];

        try {
            // 検索
            $query = DB::table("exams")
                ->leftJoin('exam_accs', 'exams.exam_id', '=', 'exam_accs.exam_id')
                ->leftJoin('category', 'exams.exam_type', '=', 'category.category_id')
                ->select(
                    'exams.exam_id',
                    'exams.exam_nm',
                    DB::raw("DATE_FORMAT(exams.start_dt, '%Y/%m/%d') as start_dt"),
                    DB::raw("DATE_FORMAT(exams.end_dt, '%Y/%m/%d') as end_dt"),
                    'exams.duration',
                    DB::raw('(SELECT COUNT(*) FROM exam_ques WHERE exam_ques.exam_id = exams.exam_id) as question_count'),
                    'exam_accs.take_exam_dt',
                    'exam_accs.take_exam_end_flg',
                    'category.category_icon'
                )
                ->where(function ($query) use ($year, $month) {
                    $query->whereYear(DB::raw("STR_TO_DATE(exams.start_dt, '%Y/%m/%d')"), '<', $year)
                          ->orWhere(function ($query) use ($year, $month) {
                              $query->whereYear(DB::raw("STR_TO_DATE(exams.start_dt, '%Y/%m/%d')"), '=', $year)
                                    ->whereMonth(DB::raw("STR_TO_DATE(exams.start_dt, '%Y/%m/%d')"), '<=', $month);
                          });
                })
                ->where(function ($query) use ($year, $month) {
                    $query->whereYear(DB::raw("STR_TO_DATE(exams.end_dt, '%Y/%m/%d')"), '>', $year)
                          ->orWhere(function ($query) use ($year, $month) {
                              $query->whereYear(DB::raw("STR_TO_DATE(exams.end_dt, '%Y/%m/%d')"), '=', $year)
                                    ->whereMonth(DB::raw("STR_TO_DATE(exams.end_dt, '%Y/%m/%d')"), '>=', $month);
                          });
                })
                ->orderBy('exams.start_dt', 'asc')
                ->where('exam_accs.acc_id', '=', $acc_id);

            if ($exam_nm != null && $exam_nm != '') {
                $query->where('exams.exam_nm', 'LIKE', "%{$exam_nm}%");
            }
            $data = $query->get();

            if ($data->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => '試験情報が見つかりませんでした。',
                ], 404); // Not Found status code
            }
            
            // Retrieve Exam Result
            $collection = new Collection();
            foreach($data as $examData){
                $exam_result = ExamResult::where('acc_id', $acc_id)
                ->where('exam_id', $examData->exam_id)
                ->first();
                if($exam_result == null){
                    $examData->resultmark = null;
                    $examData->mark = null;
                }
                else{
                    $examData->resultmark = $exam_result->resultmark;
                    $examData->mark = $exam_result->mark;
                }
                if($examData->category_icon != null && $examData->category_icon != '' ){
                    $iconData = base64_encode($examData->category_icon);
                    $iconSrc = 'data:image/jpeg;base64,'.$iconData;
                    $examData->category_icon = $iconSrc;
                }
                $collection->push($examData);
            }

            $data = $collection;

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => ''
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'system error'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'system error'
            ], 500);
        }
    }

    /**
     * 試験開始
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function exams_start(Request $request)
    {
        // param
        $acc_id = $request->query('acc_id');
        $exam_id = $request->query('exam_id');
        // Today
        $today = Carbon::now()->setTimezone('Asia/Tokyo');
        $timestamp = strtotime($today);
        $today_str = date("Y/m/d", $timestamp);

        try {
            // Exam Info
            $data = DB::table("exams")
                ->leftJoin('exam_accs', 'exams.exam_id', '=', 'exam_accs.exam_id')
                ->select(
                    'exams.exam_id',
                    'exams.exam_nm',
                    'exams.duration',
                    DB::raw('(SELECT COUNT(*) FROM exam_ques WHERE exam_ques.exam_id = exams.exam_id) as question_count'),
                    'exam_accs.take_exam_end_flg',
                    'exam_accs.remaing_time'
                )
                ->where('exam_accs.acc_id', '=', $acc_id)
                ->where('exams.exam_id', '=', $exam_id)
                ->first();
            
            if ($data === null) {
                return response()->json([
                    'success' => false,
                    'message' => '試験情報が見つかりませんでした。'
                ], 404);
            }

            if ($data->take_exam_end_flg == '1') {
                return response()->json([
                    'success' => false,
                    'message' => '試験が終わってます。'
                ], 400);
            }

            // 受験日を更新
            $exam_acc = new Exam_acc;
            $exam_acc->where('exam_id', $exam_id)->where('acc_id', $acc_id)->update(['take_exam_dt' => $today_str]);
            // Question Info
            $question_info = DB::table("exam_ques")
                ->leftJoin('questions', 'questions.question_id', '=', 'exam_ques.question_id')
                ->select(
                    'questions.question_id',
                    'questions.title',
                    'questions.body',
                    'questions.option1',
                    'questions.option2',
                    'questions.option3',
                    'questions.option4'
                )
                ->where('exam_ques.exam_id', '=', $exam_id)
                ->get();

            // Set My Answer
            $collection = new Collection();
            foreach($question_info as $question){
                $exam_acc_detail = Exam_acc_detail::where('exam_id', $exam_id)
                ->where('acc_id', $acc_id)
                ->where('question_id', $question->question_id)
                ->first();
                if($exam_acc_detail == null){
                    $question->my_answer = null;
                }
                else{
                    $question->my_answer = $exam_acc_detail->my_answer;
                }
                $collection->push($question);
            }

            $data->questions = $collection;

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => ''
            ], 200);
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'system error'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'system error'
            ], 500);
        }
    }

    /**
     * 試験更新
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function exams_update(Request $request)
    {
        // param
        $acc_id = $request->acc_id;
        $exam_id = $request->exam_id;
        $question_id = $request->question_id;
        $my_answer = $request->my_answer;
        $end_flg = $request->end_flg;

        // Validate that acc_id exists
        $accExists = DB::table('exam_accs')->where('acc_id', $acc_id)->exists();
        if (!$accExists) {
            return response()->json([
                'success' => false,
                'message' => '無効な acc_id です。'
            ], 400);
        }

        // Validate that exam_id exists
        $examExists = DB::table('exams')->where('exam_id', $exam_id)->exists();
        if (!$examExists) {
            return response()->json([
                'success' => false,
                'message' => '無効な exam_id です。'
            ], 404);
        }
        // Validate that question_id exists
        $questionExists = DB::table('questions')->where('question_id', $question_id)->exists();
        if (!$questionExists) {
            return response()->json([
                'success' => false,
                'message' => '無効な question_id です。'
            ], 404);
        }

        // Today
        $today = Carbon::now()->setTimezone('Asia/Tokyo');
        try {
            // 結果登録、すでにある場合は更新
            $examAccDetail = Exam_acc_detail::where('exam_id', $exam_id)
                ->where('acc_id', $acc_id)
                ->where('question_id', $question_id)
                ->first();
            if ($examAccDetail != null) {
                Exam_acc_detail::where('exam_id', $exam_id)
                    ->where('acc_id', $acc_id)
                    ->where('question_id', $question_id)
                    ->update(['my_answer' => $my_answer]);
            } else {
                DB::table('exam_acc_details')->insert(
                    [
                        'acc_id' => $acc_id,
                        'exam_id' => $exam_id,
                        'question_id' => $question_id,
                        'my_answer' => $my_answer,
                        'created_at' => $today,
                        'updated_at' => $today
                    ],
                    true
                );
            }

            $end_exam = false;
            // 終了
            if ($end_flg == '1') {
                $end_exam = true;

                // exam acc 更新
                Exam_acc::where('exam_id', $exam_id)
                    ->where('acc_id', $acc_id)
                    ->update(['remaing_time' => 0, 'take_exam_end_flg' => '1']);
                
                DB::commit();

                $userexam = $this->examResultCal($exam_id, $acc_id);
                $examresult = ExamResult::where('exam_id', $exam_id)
                                        ->where('acc_id', $acc_id)
                                        ->first();

                if($examresult == null){
                    DB::table('exam_results')->insert(
                        [
                            'acc_id' => $userexam->acc_id,
                            'exam_id' => $userexam->exam_id,
                            'status' => $userexam->status,
                            'take_exam_status' => '0',
                            'resultmark' => $userexam->result,
                            'win_mark' => $userexam->win_mark,
                            'question_count' => $userexam->question_count,
                            'mark' => $userexam->mark,
                            'created_at' => $today,
                            'updated_at' => $today
                        ],
                        true
                    );
                }else{
                    ExamResult::where('exam_id', $exam_id)
                    ->where('acc_id', $acc_id)
                    ->update(['status' => $userexam->status, 'take_exam_status' => '0', 'resultmark' => $userexam->result, 'win_mark' => $userexam->win_mark, 'question_count' => $userexam->question_count, 'mark' => $userexam->mark]);
                }

                $data = [
                    "endexam" => $end_exam,
                    "examresult" => [
                        "resultmark" => $userexam->result,
                        "mark" => $userexam->mark
                    ]
                ];
                return response()->json([
                    'success' => true,
                    'data'=> $data,
                    'message' => ''
                ], 200);
            }
            else{
                $data = [
                    "endexam" => $end_exam,
                    "examresult" => [
                        "resultmark" => null,
                        "mark" => null
                    ]
                ];
                return response()->json([
                    'success' => true,
                    'data'=> $data,
                    'message' => ''
                ], 200);
            }

            
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ], 500);
            DB::rollBack();
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e
            ], 500);
            DB::rollBack();
        }
    }

    /**
     * 結果計算
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

        return $userexam;
    }

    /**
     * 期間カウント
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function exam_count_time(Request $request)
    {
        $acc_id = $request->acc_id;
        $exam_id = $request->exam_id;

        // Validate that acc_id exists
        $accExists = DB::table('exam_accs')->where('acc_id', $acc_id)->exists();
        if (!$accExists) {
            return response()->json([
                'success' => false,
                'message' => '無効な acc_id です。'
            ], 400);
        }

        // Validate that exam_id exists
        $examExists = DB::table('exams')->where('exam_id', $exam_id)->exists();
        if (!$examExists) {
            return response()->json([
                'success' => false,
                'message' => '無効な exam_id です。'
            ], 404);
        }

        $remaing_time = $request->remaing_time;
        $remaing_time = $remaing_time - 5;
        $exam_acc = new Exam_acc;
        $exam_acc->where('exam_id', $exam_id)->where('acc_id', $acc_id)->update(['remaing_time' => $remaing_time]);
        return response()->json([
            'success' => true,
            'data' => [
                'remaining_time' => $remaing_time
            ],
            'message' => ''
        ], 200);
    }

}