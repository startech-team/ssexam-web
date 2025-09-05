<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\question_type;
use Carbon\Carbon;
use Illuminate\Http\Request;

class QuestionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $questionTypes = question_type::all();
        return view('admin.question-type.list', compact('questionTypes'))->with("activePage", "questionType");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.question-type.insert')->with("activePage", "questionType");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'question_type_nm' => 'max:100',
        ]);
        if ($request->input('question_type_nm') == '') {
            request()->validate([
                'ERGP0012' => 'required',
            ]);
        }

        $question_type_nm = $request->input('question_type_nm');
        $data = array('question_type_nm' => $question_type_nm, 'create_at' => Carbon::now());
        question_type::create($data);
        return redirect('/admin/questionType')->with('success', $question_type_nm . "を登録しました。");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $question_type = question_type::where('question_type_id', '=', $id)->first();
        return view('admin.question-type.update', compact('question_type'))->with("activePage", "questionType");
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        request()->validate([
            'question_type_nm' => 'max:100',
        ]);
        if ($request->input('question_type_nm') == '') {
            request()->validate([
                'ERGP0012' => 'required',
            ]);
        }
        $question_type = new question_type;
        $question_type->where('question_type_id', $request->input('question_type_id'))->update(['question_type_nm' => $request->input('question_type_nm')]);
        return redirect('/admin/questionType')->with('success', $request->input('question_type_nm') . "を変更しました。");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $questions = Question::where('question_type', '=', $id)->get();
        if (count($questions) > 0) {
            return redirect('/admin/questionType')->with('error', "問題種類は私用されてるためを削除できません。");
        }
        question_type::where('question_type_id', '=', $id)->delete();
        return redirect('/admin/questionType')->with('success', "削除が完了しました。");
    }
}
