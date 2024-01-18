<?php

namespace App\Http\Controllers;

use App\Models\Answers;
use App\Models\Members;
use App\Models\Questions;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    function signin()
    {
        return view("auth.sign_in");
    }
    function signup()
    {
        $questions = Questions::all();
        return view("auth.sign_up")->with(['questions' => $questions]);
    }

    function checkEmailExists($email)
    {
        $member = Members::where('email', $email)->first();
        if ($member) {
            return true;
        } else {
            return false;
        }
    }
    function checkPermission($email)
    {
        $member = Members::where('email', $email)->first();
        if ($member['status'] == 'admin') {
            return true;
        } else {
            return false;
        }
    }

    function login(Request $req)
    {

        $data = $req->input();
        $email = $data['email'];
        $password = $data['password'];

        if ($this->checkEmailExists($email)) {
            $member = Members::where('email', $email)->first();
            $db_password = $member['password'];
            $db_memberId = $member['member_id'];
            if (Hash::check($password, $db_password)) {
                if ($this->checkPermission($email)) {
                    $req->session()->put('member_id', $db_memberId);
                    return redirect("/admin/dashboard");
                }
                $req->session()->put('member_id', $db_memberId);
                return redirect("/");
            } else {
                return redirect()->back()->with(['error' => 'รหัสผ่านผิด'])->withInput();
            }
        } else {
            return redirect()->back()->with(['warning' => 'ไม่มีบัญชีนี้ในระบบ'])->withInput();
        }
    }

    function register(Request $req)
    {
        $data = $req->input();
        $email = $data['email'];
        $password = $data['password'];
        $cf_password = $data['cf_password'];
        $question_id = $data['question'];
        $answer_txt = $data['answer'];

        if ($password != $cf_password) {
            return redirect()->back()->with(['warning' => 'รหัสผ่านไม่เหมือนกัน'])->withInput();
        } else if ($this->checkEmailExists($email)) {
            return redirect()->back()->with(['warning' => 'มีบัญชีนี้ในระบบแล้ว'])->withInput();
        } else {
            $emailParts = explode('@', $email);
            $username = $emailParts[0];
            $hashedPassword = Hash::make($password);
            $member = new Members();
            $member->email = $email;
            $member->password = $hashedPassword;
            $member->status = "user";
            $member->username = $username;
            $member->save();

            $member = Members::where('email', $email)->first();
            $db_memberId = $member['member_id'];
            $req->session()->put('member_id', $db_memberId);

            $answer = new Answers();
            $answer->answer_text = $answer_txt;
            $answer->question_id = $question_id;
            $answer->member_id = $db_memberId;
            $answer->save();

            return redirect("/");
        }
    }

    function logout()
    {
        if (session()->has('member_id')) {
            session()->pull('member_id');
        }
        return redirect("/signin");
    }

    function resetPassword()
    {
        return view('auth.resetPassword');
    }
    function checkQuestion($email)
    {
        $member = Members::where('email', $email)->first();
        $member_id = $member['member_id'];
        $questionTexts = Answers::join('questions as q', 'answers.question_id', '=', 'q.question_id')
            ->where('answers.member_id', $member_id)
            ->pluck('q.question_text');
        return view('auth.checkQuestion')->with([
            'member_id' => $member_id,
            'email' => $email,
            'questionTexts' => $questionTexts,
        ]);
    }
    function newPassword(Request $request, $email)
    {
        $currentStep = $request->session()->get('password_reset_step');

        if ($currentStep !== 'checkQuiz') {
            return redirect('/resetPassword')->with(['error' => 'กรุณาทำตามขั้นตอน']);
        }
        return view('auth.newPassword')->with(['email' => $email]);
    }


    function checkEmail(Request $request)
    {
        $email = $request->input('email');
        if (!$this->checkEmailExists($email)) {
            return redirect()->back()->with(['warning' => 'ไม่พบบัญชีนี้ในระบบ'])->withInput();
        } else {
            $request->session()->put('password_reset_step', 'checkEmail');
            return  redirect("/resetPassword/checkQuestion/{$email}");
        }
    }

    function checkQuiz(Request $request)
    {
        $answer = $request->input('answer');
        $member_id = $request->input('member_id');
        $email = $request->input('email');
        $correct_answer = Answers::where('member_id', $member_id)->first();

        if ($correct_answer['answer_text'] == $answer) {
            $request->session()->put('password_reset_step', 'checkQuiz');
            return  redirect("/resetPassword/checkQuestion/{$email}/newPassword");
        } else {
            return redirect()->back()->with(['warning' => 'คำตอบไม่ถูกต้อง'])->withInput();
        }
    }
    function setNewPassword(Request $request)
    {
        $password = $request->input('password');
        $cf_password = $request->input('cf_password');
        $email = $request->input('email');

        if ($password !== $cf_password) {
            return redirect()->back()->with(['warning' => 'รหัสผ่านไม่ตรงกัน'])->withInput();
        }
        $member = Members::where('email', $email)->first();
        $member->password = Hash::make($password);
        $member->save();
        Session::forget('password_reset_step');

        return redirect('/signin')->with('success', 'รหัสผ่านถูกเปลี่ยนแล้ว');
    }
}
