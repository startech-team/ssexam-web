<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Exam;
use App\Models\Exam_acc;
use App\Models\Exam_acc_detail;
use App\Models\Exam_ques;
use App\Models\ExamForm;
use App\Models\Group_tb;
use App\Models\Question;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use function PHPSTORM_META\map;

class DashboardController extends Controller
{
    /**
     * index
     */
    public function index()
    {
        $examPieChart = DB::table('exam_results')
            ->select(
                'exam_results.resultmark',
                DB::raw('COUNT(*) as total_count')
            )
            ->groupBy('exam_results.resultmark')
            ->get();

        $userPercent = $this->userPercent();
        $questionGroup = $this->questionGroup();
        $examList = $this->latestExamList();
        return view(
            'admin.dashboard.index',
            compact(
                'userPercent',
                'questionGroup',
                'examList'
            )
        )
            ->with('activePage', 'dashboard');
    }

    /**
     * Active User Count
     */
    public function userPercent()
    {
        $userPercent = DB::table('users')
            ->select(
                'is_admin',
                DB::raw('COUNT(*) as count'),
                DB::raw('ROUND((COUNT(*) / (SELECT COUNT(*) FROM users)) * 100, 2) as percentage')
            )
            ->where('status', 0)
            ->groupBy('is_admin')
            ->orderBy('count', 'desc')
            ->get();
        return $userPercent;
    }

   

    /**
     * Question Group
     */
    public function questionGroup()
    {
        $questionGp = DB::table('questions')
            ->select(
                'category.category_id',
                'category.category_nm',
                DB::raw('COUNT(questions.question_id) as question_count'),
                'category.category_icon'
            )
            ->join('category', 'category.category_id', '=', 'questions.question_type')
            ->where('category.category_type', '=', 1)
            ->groupBy('category.category_nm', 'category.category_icon', 'category.category_id')  // Group by category name, icon, and type
            ->orderBy('category.category_type', 'asc')
            ->get();
        $questionGp->transform(function ($item) {
            $item->category_icon = base64_encode($item->category_icon);
            return $item;
        });

        return $questionGp;
    }

    /**
     * Latest Exam List
     */
    public function latestExamList()
    {
        $exams = DB::table('exams')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        $data = array();
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
                    $e->category_icon = base64_encode($category->category_icon);
                }
            }
            $e->result = DB::table('exam_results')
            ->where('exam_id', '=', $e->exam_id)
            ->select(
                DB::raw('SUM(CASE WHEN exam_results.resultmark = "不合格" THEN 1 ELSE 0 END) as failed_count'),
                DB::raw('SUM(CASE WHEN exam_results.resultmark = "合格" THEN 1 ELSE 0 END) as passed_count')
            )
            ->first();
            array_push($data, $e);
        }
        return $data;
    }

    /**
     * Exam List
     */
    public function examList()
    {
        $exams = DB::table('exams')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $no = $exams->currentPage() * $exams->perPage() - $exams->perPage() + 1;
        foreach ($exams as $e) {
            $e->no = $no++;
            $e->ques_count = Exam_ques::where('exam_id', $e->exam_id)->count();
            $e->acc_count = Exam_acc::where('exam_id', $e->exam_id)->count();
            $e->duration = $e->duration / 60;

            if ($e->exam_type) {
                $category = Category::where('category_id', $e->exam_type)->first();
                if ($category) {
                    $e->exam_type = $category->category_nm;
                    $e->category_icon = base64_encode($category->category_icon);
                }
            }

            $e->result = DB::table('exam_results')
                ->where('exam_id', $e->exam_id)
                ->selectRaw('SUM(CASE WHEN resultmark = "不合格" THEN 1 ELSE 0 END) as failed_count')
                ->selectRaw('SUM(CASE WHEN resultmark = "合格" THEN 1 ELSE 0 END) as passed_count')
                ->first();
        }

        return view('admin.dashboard.exam-list.index', [
            'exams' => $exams,
            'activePage' => 'dashboard'
        ]);
    }

    /**
     * Exam Summary
     */
    public function examSummary(Request $request)
    {
        $examListByID = $this->getExamListById($request->exam_id);
        $questionDetail = $this->questionDetail($request->exam_id);
        $questionDetail = $questionDetail->map(function ($item) {
            $item->userlist = explode(',', $item->userIdlist);  // Convert the userlist string to an array of IDs
            $item->usernames = collect($item->userlist)->map(function ($userId) {
                $user = DB::table('users')->where('id', $userId)->first();  // Correct the method name from 'wher' to 'where'
                return $user ? $user->name : null;  // Return the username or null if user not found
            });
            return $item;
        });
        $userexams = $this->detailexamInfo($request->exam_id);
        $examID = $request->exam_id;
        return view(
            'admin.dashboard.details.index',
            compact('examListByID', 'questionDetail', 'userexams',
                'examID')
        )
        ->with('activePage', 'dashboard');
    }

    /**
     * Get Exam List By ID
     */
    public function getExamListById(int $exam_id)
    {
        $e = DB::table('exams')
            ->where('exam_id', '=', $exam_id)
            ->first();
        $data = array();

        $ques_arr = Exam_ques::where('exam_id', '=', $e->exam_id)->get();
        $e->ques_count = count($ques_arr);

        $acc_arr = Exam_acc::where('exam_id', '=', $e->exam_id)->get();
        $e->acc_count = count($acc_arr);
        $e->duration = $e->duration / 60;
        if ($e->exam_type != null && $e->exam_type != '') {
            $category = Category::where('category_id', '=', $e->exam_type)->first();
            if ($category != null) {
                $e->exam_type = $category->category_nm;
                $e->category_icon = base64_encode($category->category_icon);
            }
        }
        array_push($data, $e);

        return $data;
    }

    /**
     * Exam Pie Chart
     */
    public function examPieChart(Request $request)
    {
        $data = DB::table('exam_results')
            ->select(
                DB::raw('SUM(CASE WHEN resultmark = "不合格" THEN 1 ELSE 0 END) as failed_count'),
                DB::raw('SUM(CASE WHEN resultmark = "合格" THEN 1 ELSE 0 END) as passed_count')
            )
            ->where('exam_id', $request->exam_id)
            ->first();
        return response()->json($data);
    }

     /**
     * Group User Count
     */
    public function groupPercent()
    {
        $groupPercent = DB::table('users')
            ->select(
                'users.group_id',  // Ensure you're selecting the correct group_id from users
                'group_tbs.group_name',  // Assuming there's a group_name field in group_tbs
                'group_tbs.order',
                DB::raw('COUNT(users.id) as count'),  // Count the users in each group
                DB::raw('ROUND((COUNT(users.id) / (SELECT COUNT(*) FROM users)) * 100, 2) as percentage')  // Calculate percentage
            )
            ->join('group_tbs', 'group_tbs.group_id', '=', 'users.group_id')  // Join with group_tbs table
            ->where('users.status', 0)
            ->groupBy('users.group_id', 'group_tbs.group_name')  // Group by both group_id and group_name
            ->orderBy('order', 'asc')
            ->get();

        return response()->json($groupPercent);
    }


    /**
     * Exam Group
     */
    public function examGroup(Request $request)
    {
        $examGroup = DB::table('exam_results')
            ->join('exams', 'exams.exam_id', '=', 'exam_results.exam_id')
            ->select(
                'exams.exam_nm as exam_nm',
                'exams.start_dt as start_dt',
                DB::raw('SUM(CASE WHEN exam_results.resultmark = "不合格" THEN 1 ELSE 0 END) as failed_count'),
                DB::raw('SUM(CASE WHEN exam_results.resultmark = "合格" THEN 1 ELSE 0 END) as passed_count')
            )
            ->groupBy('exam_nm')
            ->orderBy('start_dt', 'desc')
            ->limit(10)
            ->get();
        return response()->json($examGroup);
    }

    /**
     * Analytic by question
     */
    public function anatyticByQuestion(Request $request)
    {
        $results = DB::table('exam_acc_details')
            ->select(
                'exam_acc_details.question_id',
                DB::raw('COUNT(CASE WHEN exam_acc_details.my_answer = questions.correct_answer THEN 1 END) as correct_count'),
                DB::raw('COUNT(CASE WHEN exam_acc_details.my_answer != questions.correct_answer THEN 1 END) as incorrect_count')
            )
            ->join('questions', 'exam_acc_details.question_id', '=', 'questions.question_id')
            ->where('exam_acc_details.exam_id', '=', $request->examID)
            ->groupBy('exam_acc_details.question_id')
            ->orderBy('exam_acc_details.question_id', 'asc')
            ->get();

        return $results;
    }

    /**
     * Question Detail
     */
    public function questionDetail(int $examID)
    {
        $results = DB::table('exam_acc_details')
            ->select(
                'exam_acc_details.question_id',
                'questions.title',
                'questions.body',
                'questions.option1',
                'questions.option2',
                'questions.option3',
                'questions.option4',
                'questions.correct_answer',
                DB::raw('GROUP_CONCAT(CASE WHEN exam_acc_details.my_answer != questions.correct_answer THEN (SELECT users.name FROM users WHERE users.id = exam_acc_details.acc_id) END) as userIdlist'),
                DB::raw('COUNT(CASE WHEN exam_acc_details.my_answer = questions.correct_answer THEN 1 END) as correct_count'),
                DB::raw('COUNT(CASE WHEN exam_acc_details.my_answer != questions.correct_answer THEN 1 END) as incorrect_count')
            )
            ->join('questions', 'exam_acc_details.question_id', '=', 'questions.question_id')
            ->where('exam_acc_details.exam_id', '=', $examID)
            ->groupBy('exam_acc_details.question_id')
            ->orderBy('exam_acc_details.question_id', 'asc')
            ->get();
            Log::info($results);

        return $results;
    }

    /**
     * Fail User List
     */
    public function failUserlist(int $examID)
    {
        $userList = DB::table('exam_acc_details')
            ->select(
                'exam_acc_details.question_id',
                'exam_acc_details.acc_id'
            )
            ->join('questions', 'exam_acc_details.question_id', '=', 'questions.question_id')
            ->where('exam_acc_details.exam_id', '=', 2)
            ->where('exam_acc_details.my_answer', '!=', 'questions.correct_answer')
            ->get();
        return $userList;
    }

    /**
     * Detail Exam Info
     */
    public function detailexamInfo(int $examID)
    {
        $userexams = DB::table('exam_results')
            ->join('exam_accs', function ($join) {
                $join
                    ->on('exam_results.acc_id', '=', 'exam_accs.acc_id')
                    ->On('exam_results.exam_id', '=', 'exam_accs.exam_id');
            })
            ->join('exams', 'exam_results.exam_id', '=', 'exams.exam_id')
            ->join('users', 'users.id', '=', 'exam_results.acc_id')
            ->join('group_tbs', 'users.group_id', '=', 'group_tbs.group_id')
            ->select(
                'exams.exam_nm',
                'exams.exam_id',
                'users.name',
                'users.id',
                'exams.duration',
                'exam_results.status',
                'exam_results.take_exam_status',
                'exam_results.win_mark',
                'group_tbs.group_name',
                'exam_results.resultmark as result',
                'exam_accs.take_exam_dt',
                'exams.exam_id',
                'users.id as acc_id',
                'exam_results.question_count',
                'exam_results.mark',
                'exam_results.question_count'
            )
            ->where('exams.exam_id', '=', $examID)
            ->get();

        return $userexams;
    }

    /**
     * Export PDF
     */
    public function exportPdf(Request $request)
    {
        $chartImage = $request->input('images');
        $examListByID = $this->getExamListById($request->input('examID'));

        if (!$chartImage) {
            return response()->json(['error' => 'No chart image found'], 400);
        }
        $questionDetail = $this->questionDetail($request->input('examID'));
        $questionDetail = $questionDetail->map(function ($item) {
            $item->userlist = explode(',', $item->userIdlist);  // Convert the userlist string to an array of IDs
            $item->usernames = collect($item->userlist)->map(function ($userId) {
                $user = DB::table('users')->where('id', $userId)->first();  // Correct the method name from 'wher' to 'where'
                return $user ? $user->name : null;  // Return the username or null if user not found
            });
            return $item;
        });
        $userexams = $this->detailexamInfo($request->input('examID'));
        $html = view('admin.dashboard.details.pdf.export-chart',
            compact('chartImage', 'questionDetail', 'examListByID',
                'userexams'))->render();

        $pdf = Pdf::loadHTML($html);
        return response($pdf->output(), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="chart.pdf"');
    }
}
