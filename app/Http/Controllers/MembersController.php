<?php

namespace App\Http\Controllers;

use App\Models\Members;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class MembersController extends Controller
{
    public function editProfile(Request $request)
    {
        $member_id = session('member_id');
        $member = Members::where('member_id', $member_id)->firstOrFail();

        $username = $request->input('username');
        $old_password = $request->input('old_password');
        $new_password = $request->input('new_password');

        if ($request->hasFile('profile_pic')) {
            $profile_pic = $request->file('profile_pic');
            $allowedFileTypes = ['jpeg', 'jpg', 'png', 'gif']; // Define allowed file types

            if ($profile_pic->isValid() && in_array(strtolower($profile_pic->getClientOriginalExtension()), $allowedFileTypes)) {
                $directoryPath = 'images/members/' . $member_id;

                if ($member->member_img) {
                    Storage::disk('public')->delete($directoryPath . '/' . $member->member_img);
                }

                if (!Storage::disk('public')->exists($directoryPath)) {
                    Storage::disk('public')->makeDirectory($directoryPath);
                }

                $fileName = $member_id . '.' . $profile_pic->getClientOriginalExtension();
                $profile_pic->storeAs('public/images/members/' . $member_id, $fileName);
                $member->member_img = $fileName;
                $member->save();
            } else {
                return response()->json(['warning' => true, 'message' => 'ประเภทรูปภาพไม่ถูกต้อง รองรับ JPEG, JPG, PNG, GIF']);
            }
        }


        if (!empty($old_password) || !empty($new_password)) {
            if (Hash::check($old_password, $member->password)) {
                $member->password = Hash::make($new_password);
            } else {
                return response()->json(['warning' => true, 'message' => 'รหัสผ่านไม่ถูกต้อง']);
            }
        }

        if ($username !== $member->username) {
            $existingMember = Members::where('username', $username)->first();
            if (!$existingMember || $existingMember->member_id === $member_id) {
                $member->username = $username;
            } else {
                return response()->json(['warning' => true, 'message' => 'ชื่อผู้ใช้นี้มีอยู่แล้วในระบบ']);
            }
        }

        $member->save();

        return response()->json(['success' => true, 'message' => 'บันทึกข้อมูลเรียบร้อย']);
    }
}
