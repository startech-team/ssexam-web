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

class UsersController extends Controller
{

    /**
     * ログイン
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;

            // Initialize the query for the user
            $query = DB::table('users')->where('users.id', $user->id);

            // If group_id is not null, join the group table
            if ($user->group_id !== null) {
                $query->leftJoin('group_tbs', 'users.group_id', '=', 'group_tbs.group_id')
                    ->select('users.*', 'group_tbs.group_name');
            } else {
                $query->select('users.*');
            }
            $userWithGroup = $query->first();            

            // 有効・無効確認
            $data = new \stdClass();
            if ($userWithGroup->status == '1') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
            // Profile Picture
            if($userWithGroup->profile_image != null){
                $image = base64_encode($userWithGroup->profile_image);
                $imgSrc = 'data:image/jpeg;base64,'.$image;
                $userWithGroup->profile_image = $imgSrc;
            }
            $data->token = $token;
            $data->user = $userWithGroup;
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => ''
            ], 200);
        }
        return response()->json([
            'success' => false,
            'message' => 'Unauthorized'
        ], 401);
        //return response()->json(['error' => ''], 401);
    }


    /**
     * ユーザーサインアップ
     * 
     */
    public function signUp(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email','unique:users']
            ]);
            if ($validator->fails()) {
                $errors ="";
                if ($validator->errors()->has('email')) {
                    $errors .= $validator->errors()->get('email')[0];
                }
                if ($validator->errors()->has('name') && $validator->errors()->has('email')){
                    $errors .= "\n";
                }
                if ($validator->errors()->has('name')) {
                    $errors .= $validator->errors()->get('name')[0];
                }
                return Response::json([
                    'success' => false,
                    'message' => $errors
                ], 400);
            }
            $email = $request->email;
            $name = $request->name;
            $password = Str::random(8);
            $user =User::create(
                [
                    "name" => $name,
                    "email" => $email,
                    "password" => bcrypt($password),
                    "is_admin" => "4",
                    "group_id" => 19,
                    "status" => 0
                ]
            );
            for($i=57;$i<=60;$i++){
                Exam_acc::create(
                    [
                        "exam_id"=>$i,
                        "acc_id"=>$user->id,
                        "remaing_time"=>"600"
                    ]
                    );
            }
            $data2 = array('name' => $name, 'email' => $email, 'verificationCode' => $password);

            Mail::send('api.signup', $data2, function ($message) use ($data2) {
                $message->to($data2['email'])
                ->subject("【SS EXAM】アカウント情報案内");
            });

            return Response::json([
                'success' => true,
                'data' => [
                    // 'verification_code'=>$verificationCode,
                    'isSignup' => true
                ],
                'message' => ''
            ], 200);
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    /**
     * ユーザーログアウト
     * 
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return Response::json([
            'success' => true,
            'message' => 'logout successful'
        ], 200);
    }

    /**
     * 削除
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        // param
        $id = $request->query('acc_id');
        $user = User::where('id', '=', $id)->first();

        if($user == null){
            return response()->json([
                'success' => false,
                'message' => 'アカウントがありません。'
            ], 400);
        }
        if($user->is_admin != "4"){
            return response()->json([
                'success' => false,
                'message' => 'アカウントは削除できません。管理者へお問い合わせください。'
            ], 400);
        }

        // Delete exam_result
        ExamResult::where('acc_id', '=', $id)->delete();
        // Delete exam_accs
        Exam_acc::where('acc_id', '=', $id)->delete();
        // Delete exam_acc_details
        Exam_acc_detail::where('acc_id', '=', $id)->delete();
        // Delete personal_access_token
        PersonalAccessToken::where('tokenable_id', '=', $id)->delete();
        // Delete User
        User::where('id', '=', $id)->delete();
        return response()->json([
            'success' => true,
            'message' => 'アカウント削除しました。'
        ], 200);
    }

    public function updatePW(Request $request)
    {
        try {
            $validator =Validator::make($request->all(),[
                'new_password' => ['required','min:8',
                'regex:/^(?=.*[A-Z])(?=.*\d).+$/']
            ]);
            if($validator->fails()){
                return Response::json([
                    'success' => false,
                    'message' => '新しパスワードを最低8桁最低数字1桁最低大文字1桁で入力して下さい。'
                ], 400);
            }
    
            $acc_id = $request->acc_id;
            $new_pw = $request->new_password;
            $update = User::where('id', '=', $acc_id)->update(['password' => bcrypt($new_pw)]);
            if ($update) {
                return Response::json([
                    'success' => true,
                    'data'=>'',
                    'message' => 'パスワード変更しました。'
                ], 200);
            } else {
                return Response::json([
                    'success' => false,
                    'message' => 'ユーザーが見つかりません'
                ], 404);
            }
        } catch (\Exception $e) {
            return Response::json([
                'success' => false,
                'message' => $e
            ], 500);
            DB::rollBack();
        }
    }
    
    /**
     * パスワード忘れ
     * 
     */
    public function forgotPw(Request $request)
    {
        try {
            $validator =Validator::make($request->all(),[
                'email' => ['required','email']
            ]);
            if($validator->fails()){
                return Response::json([
                    'success' => false,
                    'message' => 'メールを正しく記入して下さい。'
                ], 400);
            }
            $email = $request->email;
            $is_admin = ['2', '3', '4'];
            $user = User::where([
                'email' => $email,
                'status' => 0
            ])
            ->whereIn('is_admin', $is_admin)
            ->first();
            if ($user) {
                $verificationCode = Str::random(4);
                $verificationCode = str_pad(random_int(0, 9999), 4, '0', STR_PAD_LEFT);
                $data2 = array('name' => $user->name, 'email' => $email, 'verificationCode' => $verificationCode);

                Mail::send('api.verificationCode', $data2, function ($message) use ($data2) {
                    $message->to($data2['email'])
                        ->subject("【SS EXAM】アカウント情報案内");
                });
                User::where('email', '=', $email)
                    ->update(['verification_code' => $verificationCode, 'vertification_code_time' => 0]);
                return Response::json([
                    'success' => true,
                    'data' =>[
                       'acc_id'=>$user->id
                    ],
                    'message'=>''
                ], 200);
            } else {
                return Response::json([
                    'success' => false,
                    'message' => "メールアドレスは見つかりません。"
                ], 404);
            }
        } catch (\QueryException $e) {
            return Response::json([
                'success' => false,
                'message' => $e
            ], 500);
        }
    }

    /**
     * パスワードリセット
     * 
     */
    public function resetPw(Request $request)
    {
        try {
            $validator =Validator::make($request->all(),[
                'verification_code' => ['required','min:4','max:4']
            ]);
            if($validator->fails()){
                return Response::json([
                    'success' => false,
                    'message' => '検証コード(4桁)を入力して下さい。'
                ], 400);
            }
            $acc_id = $request->acc_id;
            $verification_code = $request->verification_code;
            $data = User::where('id', '=', $acc_id)->first();
            if ($data != null) {
                if($data->vertification_code_time != null && $data->vertification_code_time > 3){
                    return Response::json([
                        'success'=>true,
                        'data'=>'',
                        'message'=>'コードの入力が3回間違いました。もう一度パスワードリセットを行なってください。'
                    ], 400);
                }
                if ($data->verification_code == $verification_code) {
                    if($data->vertification_code_time != null && $data->vertification_code_time <= 3){
                        // 認証回数を0にする
                        User::where('email', '=', $data->email)->update(['vertification_code_time' => 0]);
                        return Response::json([
                            'success'=>true,
                            'data'=>'',
                            'message'=>''
                        ], 200);
                    }
                    // 認証回数を増やす
                    else{
                        if($data->vertification_code_time == null){
                            User::where('email', '=', $data->email)->update(['vertification_code_time' => 1]);
                        }
                        else{
                            User::where('email', '=', $data->email)->update(['vertification_code_time' => ($data->vertification_code_time + 1)]);
                        }
                    }
                }
                else{
                    // 認証回数を増やす
                    if($data->vertification_code_time == null){
                        User::where('email', '=', $data->email)->update(['vertification_code_time' => 1]);
                    }
                    else{
                        User::where('email', '=', $data->email)->update(['vertification_code_time' => ($data->vertification_code_time + 1)]);
                    }
                    return Response::json([
                        'success'=>true,
                        'data'=>'',
                        'message'=>'コードが間違っております。'
                    ], 400);
                }
            }
            else {
                return Response::json([
                    'success' => false,
                    'message' => 'ユーザーが見つかりません'
                ], 404);
            }
        } catch (\Exception $e) {
            return Response::json([
                'error' => $e
            ], 500);
        }
    }


    /**
     * プロファイル
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        // param
        $id = $request->query('acc_id');
        $user = User::where('id', '=', $id)->first();
        if($user == null){
            return response()->json([
                'success' => false,
                'message' => 'アカウントがありません。'
            ], 400);
        }
        // Group Name
        $profile = new \stdClass();
        if ($user->group_id !== null) {
            $group = Group_tb::where('group_id', '=', $user->group_id)->first();
            if($group != null){
                $profile->group_name = $group->group_name;
            }
        }
        // Profile Image
        if($user->profile_image != null){
            $image = base64_encode($user->profile_image);
            $imageSrc = 'data:image/jpeg;base64,'.$image;
            $profile->profile_image = $imageSrc;
        }
        else{
            $profile->profile_image = null;
        }
        $profile->name = $user->name;
        $profile->acc_id = $user->id;
        $profile->email = $user->email;
        return response()->json([
            'success' => true,
            'data'=> $profile,
            'message' => ''
        ], 200);
    }

    /**
     * プロファイル更新
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile_update(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email']
            ]);
            if ($validator->fails()) {
                $errors ="";
                if ($validator->errors()->has('email')) {
                    $errors .= $validator->errors()->get('email')[0];
                }
                if ($validator->errors()->has('name') && $validator->errors()->has('email')){
                    $errors .= "\n";
                }
                if ($validator->errors()->has('name')) {
                    $errors .= $validator->errors()->get('name')[0];
                }
                return Response::json([
                    'success' => false,
                    'message' => $errors
                ], 400);
            }
            $user = User::where('email', '=', $request->email)
            ->where('id', '!=', $request->acc_id)
            ->first();
            if($user != null){
                return response()->json([
                    'success' => false,
                    'message' => 'メールが既に存在しています。'
                ], 400);
            }

            // body
            $acc_id = $request->acc_id;
            $name = $request->name;
            $email = $request->email;
            $profile_image = $request->profile_image;

            if($request->hasFile('profile_image')){
                $image = $request->file('profile_image');
                $binaryData = file_get_contents($image->getRealPath());
                User::where('id', $acc_id)
                ->update(['name' => $name, 'email' => $email, 'profile_image' => $binaryData]);
            }
            else{
                User::where('id', $acc_id)
                ->update(['name' => $name, 'email' => $email]);
            }
            return response()->json([
                'success' => true,
                'message' => 'プロファイルを更新しました。'
            ], 200);
        } catch (\Exception $e) {   
            return Response::json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}