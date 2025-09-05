<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Exam_ques;
use App\Models\Question;
use App\Models\question_type;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    /**
     * 一覧
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title_detail = request('title_detail');
        $question_type = request('question_type');

        if($title_detail != '' && $question_type != ''){
            $questions = DB::table('questions')
            ->where(function($query) use ($title_detail) {
                $query->where('title', 'like', "%{$title_detail}%")->orWhere('body', 'like', "%{$title_detail}%");
            })
            ->where('question_type', '=', $question_type)
            ->orderBy('question_type')
            ->orderBy('question_id')
            ->paginate(10)->appends(['title_detail' => $title_detail, 'question_type' => $question_type]);
        }
        else if($title_detail != '' && $question_type == ''){
            $questions = DB::table('questions')->where('title', 'like', "%{$title_detail}%")->orWhere('body', 'like', "%{$title_detail}%")->orderBy('question_type')->orderBy('question_id')->paginate(10)->appends(['title_detail' => $title_detail, 'question_type' => $question_type]);
        }
        else if($question_type != '' && $title_detail == ''){
            $questions = DB::table('questions')->where('question_type', '=', $question_type)->orderBy('question_type')->orderBy('question_id')->paginate(10)->appends(['title_detail' => $title_detail, 'question_type' => $question_type]);
        }else{
            $questions = DB::table('questions')->orderBy('question_type')->orderBy('question_id')->paginate(10)->appends(['title_detail' => $title_detail, 'question_type' => $question_type]);
        }

        $no = $questions->currentPage() * $questions->perPage() - $questions->perPage() + 1;
        foreach ($questions as $q) {
            $category = Category::where('category_id','=',$q->question_type)->first();
            if($category != null){
                $q->category_nm = $category->category_nm;
            }
            $q->no = $no++;
        }
        $categoryList = Category::where('category_type','=','1')->get();
        return view('admin.question.list', [
            'questions' => $questions,
            'categoryList' => $categoryList,
            'title_detail' => $title_detail,
            'question_type' => $question_type,
            'activePage' => 'question'
        ]);
    }

    /**
     * 登録
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$questionTypes = question_type::all();
        $categoryList = Category::where('category_type','=','1')->get();
        return view('admin.question.insert', compact('categoryList'))->with("activePage", "question");
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
            'option1' => 'required',
            'option2' => 'required',
            'correct_answer' => 'required',
        ]);
        if($request->input('option3') === null && $request->input('option4') != null) {
            request()->validate([
                'ERQA0005' => 'required',
            ]);
        }
        if($request->input('option3') === null && $request->input('correct_answer') === '3') {
            request()->validate([
                'ERQA0006' => 'required',
            ]);
        }
        if($request->input('option4') === null && $request->input('correct_answer') === '4') {
            request()->validate([
                'ERQA0007' => 'required',
            ]);
        }

        $questions = Question::create($request->all());
        return redirect('/admin/question')->with('success', "{$request->input('title')}登録が完了しました。");
    }

    /**
     * 更新
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($question_id)
    {
       // $questionTypes = question_type::all();
        $categoryList = Category::all();
        $questions = Question::where('question_id', '=', $question_id)->first();
        return view('admin/question/edit', compact('questions', 'categoryList'))->with("activePage", 'question');
    }

    /**
     * 更新OK
     * 
     */
    public function update(Request $request)
    {
        request()->validate([
            'title' => 'required',
            'body' => 'required',
            'option1' => 'required',
            'option2' => 'required',
            'correct_answer' => 'required',
        ]);
        if($request->input('option3') === null && $request->input('option4') != null) {
            request()->validate([
                'ERQA0005' => 'required',
            ]);
        }
        if($request->input('option3') === null && $request->input('correct_answer') === '3') {
            request()->validate([
                'ERQA0006' => 'required',
            ]);
        }
        if($request->input('option4') === null && $request->input('correct_answer') === '4') {
            request()->validate([
                'ERQA0007' => 'required',
            ]);
        }

        $questions = new Question;
        $question_id = $request->input('question_id');
        $category_id = $request->input('category_id');
        $title = $request->input('title');
        $body = $request->input('body');
        $option1 = $request->input('option1');
        $option2 = $request->input('option2');
        $option3 = $request->input('option3');
        $option4 = $request->input('option4');
        $correct_answer = $request->input('correct_answer');
        $questions->where('question_id', $question_id)->update(['category_id' => $category_id, 'title' => $title, 'body' => $body, 'option1' => $option1, 'option2' => $option2, 'option3' => $option3, 'option4' => $option4, 'correct_answer' => $correct_answer]);
        return redirect('/admin/question')->with('success', "{$request->input('title')}更新しました。");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $exam_ques = Exam_ques::where('question_id', '=', $id)->get();
        if (count($exam_ques) > 0) {
            return redirect('/admin/question')->with('error', "問題は私用されてるためを削除できません。");
        }
        Question::where('question_id','=',$id)->delete();
        return redirect('/admin/question')->with('success', '問題を削除しました。');
    }
}
