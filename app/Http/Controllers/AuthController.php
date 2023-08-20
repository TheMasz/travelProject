<?php

namespace App\Http\Controllers;

use App\Models\Members;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    function signin()
    {
        if(session()->has('member_id')){
            return redirect("/");
        }
        return view("auth.sign_in");
    }
    function signup()
    {
        if(session()->has('member_id')){
            return redirect("/");
        }
        return view("auth.sign_up");
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

        if($password != $cf_password){
            return redirect()->back()->with(['warning' => 'รหัสผ่านไม่เหมือนกัน'  ])->withInput();
        }else if ($this->checkEmailExists($email)) {
            return redirect()->back()->with(['warning' => 'มีบัญชีนี้ในระบบแล้ว'  ])->withInput();
        }else{
            $hashedPassword = Hash::make($password);
            $member = new Members();
            $member->email = $email;
            $member->password = $hashedPassword;
            $member->save();

            $member = Members::where('email', $email)->first();
            $db_memberId = $member['member_id'];
            $req->session()->put('member_id', $db_memberId);
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
}
