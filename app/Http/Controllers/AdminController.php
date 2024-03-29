<?php

namespace App\Http\Controllers;




use App\Models\Members;
use App\Models\Locations;
use App\Models\LocationImages;
use App\Models\LocationTypes;
use App\Models\Preferences;
use App\Models\PersonalPreference;
use App\Models\PlansTrip;
use App\Models\Comments;
use App\Models\DaysOpening;
use App\Models\Reviews;
use App\Models\Questions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

class AdminController extends Controller
{
    function Dashboard()
    {
        return view('admin.dashboard');
    }

    function Preferences()
    {
        $preferences = DB::table('preferences')->paginate(12);
        return view('admin.preferences', compact('preferences'));
    }

    function Locations()
    {
        $locations = DB::table('locations')
            ->join('location_images', 'locations.location_id', '=', 'location_images.location_id')
            ->groupBy('locations.location_name', 'locations.detail', 'locations.s_time', 'locations.e_time', 'locations.location_id')
            ->selectRaw('locations.location_id,locations.location_name,locations.detail,locations.s_time,locations.e_time,SUBSTRING_INDEX(GROUP_CONCAT(location_images.img_path), ",", 1) as img_names')
            ->orderBy('locations.location_id', 'asc') // เพิ่มส่วนนี้เพื่อเรียงลำดับ
            ->paginate(6);

        $preferences = DB::table('preferences')->paginate(12);

        return view('admin.locations', compact('locations', 'preferences'));
    }

    function provinces()
    {
        $provinces = DB::table('provinces')->get();
        return view('admin.locations', compact('provinces'));
    }

    function Users()
    {
        $users = members::where('status', 'user')->paginate(6);
        return view('admin.users', compact('users'));
    }

    function Reviews()
    {


        $data = DB::table('locations')
            ->join('location_images', 'locations.location_id', '=', 'location_images.location_id')
            ->join('reviews', 'locations.location_id', '=', 'reviews.location_id')
            ->join('members', 'members.member_id', '=', 'reviews.member_id')
            ->select(
                'locations.location_id',
                'locations.location_name',
                DB::raw('ROUND(AVG(reviews.rating), 1) as rating'),
                DB::raw('COUNT(DISTINCT reviews.review_id) as countrat'),
                DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(members.username), ",", 1) as username'),
                DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(reviews.review), ",", 1) as comment_txt'),
                DB::raw('(SELECT GROUP_CONCAT(review_id) FROM reviews WHERE location_id = locations.location_id) as review_id'),
                DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(location_images.img_path), ",", 1) as img_names')
            )
            ->groupBy('locations.location_id', 'locations.location_name')
            ->paginate(6);

        return view('admin.reviews', compact('data'));
    }

    function Test()
    {
        return view('admin.test');
    }

    //    

    public function store(Request $request)
    {
        // Validate ข้อมูลจาก request
        $request->validate([
            'location_name' => 'required|string',
            'address' => 'required',
            'detail' => 'required',
            's_time' => 'required',
            'e_time' => 'required',
            'province_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'credit' => 'required',
            'preferences_id' => 'required|array',
            'img_path.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'days_id' => 'required|array'
        ]);

        // เพิ่มเงื่อนไขเพื่อตรวจสอบว่า location_name ซ้ำกับข้อมูลที่มีอยู่แล้วหรือไม่
        if (Locations::where('location_name', $request->input('location_name'))->exists()) {
            return redirect('/admin/locations')->with('error', 'ชื่อสถานที่ท่องเที่ยวซ้ำ !!');
        }


        $sTime = strtotime($request->input('s_time'));
        $eTime = strtotime($request->input('e_time'));

        // เพิ่มเงื่อนไขตรวจสอบ
        if ($sTime >= $eTime) {
            return redirect('/admin/locations')->with('error', 'เวลาเปิดของร้านต้องน้อยกว่าเวลาปิด !!');
        }


        // เพิ่มเงื่อนไขตรวจสอบ latitude และ longitude ซ้ำ
        $existingLocationCoordinates = DB::table('locations')
            ->where('latitude', $request->latitude)
            ->first();

        if ($existingLocationCoordinates) {
            // return redirect()->back()->with('error', 'Location with these coordinates already exists.');
            return redirect('/admin/locations')->with('error', 'Latitude นี้มีอยู่แล้ว !!');
        }

        // เพิ่มเงื่อนไขตรวจสอบ latitude และ longitude ซ้ำ
        $existingLocationCoordinates1 = DB::table('locations')
            ->where('longitude', $request->longitude)
            ->first();

        if ($existingLocationCoordinates1) {
            // return redirect()->back()->with('error', 'Location with these coordinates already exists.');
            return redirect('/admin/locations')->with('error', 'Longitude นี้มีอยู่แล้ว !!');
        }




        try {
            // ในกรณีที่มีการใส่ไฟล์รูปภาพ
            if ($request->hasFile('img_path') && is_array($request->file('img_path'))) {
                DB::transaction(function () use ($request) {
                    // Insert ข้อมูลลงในตาราง locations
                    $location = Locations::create([
                        'location_name' => $request->input('location_name'),
                        'address' => $request->input('address'),
                        'detail' => $request->input('detail'),
                        's_time' => $request->input('s_time'),
                        'e_time' => $request->input('e_time'),
                        'province_id' => $request->input('province_id'),
                        'latitude' => $request->input('latitude'),
                        'longitude' => $request->input('longitude'),
                        // เพิ่ม fields ตามต้องการ
                    ]);

                    // ดึงค่า location_id ล่าสุดจากฐานข้อมูล
                    $latestLocationId = Locations::latest('location_id')->value('location_id');

                    foreach ($request->input('preferences_id') as $preferenceId) {
                        LocationTypes::create([
                            'location_id' => $latestLocationId,
                            'preference_id' => $preferenceId,
                            // เพิ่ม fields ตามต้องการ
                        ]);
                    }

                    $day_ids = $request->days_id;
                    foreach ($day_ids as $day_id) {
                        $opening = new DaysOpening();
                        $opening->location_id = $latestLocationId;
                        $opening->day_id = $day_id;
                        $opening->save();
                    }

                    foreach ($request->file('img_path') as $image) {
                        // ทำการบันทึกรูปลงใน storage
                        $imgPath = $latestLocationId . '/' . $image->getClientOriginalName();
                        $image->storeAs("public/images/locations/", $imgPath);

                        // Insert ข้อมูลลงในตาราง location_images
                        LocationImages::create([
                            'location_id' => $latestLocationId,
                            'img_path' => $imgPath,
                            'credit' => $request->input('credit'),
                            // เพิ่ม fields ตามต้องการ
                        ]);
                    }
                });

                return redirect('/admin/locations')->with('success', 'Insert Success');
            }
        } catch (\Exception $e) {
            // กรณีเกิดข้อผิดพลาด
            return redirect('/admin/locations')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    function deletelocation($location_id)
    {
        try {
            // ใช้ transaction เพื่อให้การลบข้อมูลเป็นครั้งเดียว
            DB::transaction(function () use ($location_id) {
                // // ลบข้อมูลในตาราง location_images ที่มี location_id เท่ากับ $locationId
                // LocationImages::where('location_id', $location_id)->delete();
                // // ลบข้อมูลในตาราง LocationTypes ที่มี location_id เท่ากับ $locationId
                // LocationTypes::where('location_id', $location_id)->delete();
                //   // ลบข้อมูลในตาราง Comments ที่มี location_id เท่ากับ $locationId
                //   Comments::where('location_id', $location_id)->delete();
                // // ลบข้อมูลในตาราง locations ที่มี location_id เท่ากับ $locationId
                Locations::where('location_id', $location_id)->delete();

                Storage::deleteDirectory("public/images/locations/{$location_id}");
            });

            return redirect('/admin/locations')->with('success', 'Delete Success');
        } catch (\Exception $e) {
            // กรณีเกิดข้อผิดพลาด
            return redirect('/admin/locations')->with('error', 'Error: ' . $e->getMessage());
        }
    }

    function searchlocation(Request $request)
    {
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');

            $locations = DB::table('locations')
                ->leftJoin('location_images', 'locations.location_id', '=', 'location_images.location_id')
                ->select('locations.location_id', 'locations.location_name', 'locations.address', 'locations.detail', 'locations.s_time', 'locations.e_time', 'locations.province_id', 'locations.latitude', 'locations.longitude', DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(location_images.img_path), ",", 1) as img_names'))
                ->where('locations.location_name', 'like', '%' . $searchTerm . '%')
                ->groupBy('locations.location_id', 'locations.location_name', 'locations.address', 'locations.detail', 'locations.s_time', 'locations.e_time', 'locations.province_id', 'locations.latitude', 'locations.longitude') // ไม่ต้องรวม 'locations.credit'
                ->paginate(15);

            return view('admin.locations', compact('locations', 'searchTerm'));
        }

        // ถ้าค่าว่างเปล่า หรือไม่มีค่าที่ส่งมาในช่องค้นหา
        return redirect('/admin/locations');
    }

    function searchusers(Request $request)
    {
        // ตรวจสอบว่ามีค่าที่ส่งมาและไม่ใช่ค่าว่างเปล่าหรือไม่
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');

            // ค้นหาข้อมูลในตาราง users
            $users = Members::where('email', 'like', '%' . $searchTerm . '%')
                ->orWhere('username', 'like', '%' . $searchTerm . '%')
                ->paginate(15);

            return view('admin.users', compact('users', 'searchTerm'));
        }

        // ถ้าค่าว่างเปล่า หรือไม่มีค่าที่ส่งมาในช่องค้นหา
        return redirect('/admin/users');
    }

    function deleteusers($memberid)
    {

        try {

            DB::transaction(function () use ($memberid) {
                PersonalPreference::where('member_id', $memberid)->delete();
                PlansTrip::where('member_id', $memberid)->delete();
                Comments::where('member_id', $memberid)->delete();
                Members::where('member_id', $memberid)->delete();
            });
            return redirect('/admin/users')->with('success', 'Delete Success');
        } catch (\Exception $e) {
            // กรณีเกิดข้อผิดพลาด
            return redirect('/admin/users')->with('error', 'ลบข้อมูลไม่สำเร็จ !!');
        }
    }

    function insertpreference(Request $request)
    {
        try {
            $request->validate([
                'preference_name' => 'required|string|unique:preferences',
            ]);

            Preferences::create([
                'preference_name' => $request->input('preference_name'),
            ]);

            return redirect('/admin/preferences')->with('success', 'Preference added successfully');
        } catch (\Exception $e) {
            // กรณีเกิดข้อผิดพลาด
            return redirect('/admin/preferences')->with('error', 'ประเภทความชอบนี้มีอยู่แล้ว !!');
        }
    }

    public function delpreferences(Request $request, $preference_id)
    {
        try {
            DB::transaction(function () use ($preference_id) {
                Preferences::where('preference_id', $preference_id)->delete();
            });
            return redirect('/admin/preferences')->with('success', 'Delete Success');
        } catch (\Exception $e) {
            return redirect('/admin/preferences')->with('error', 'ลบข้อมูลไม่สำเร็จ !!');
        }
    }


    function searchpre(Request $request)
    {
        // ตรวจสอบว่ามีค่าที่ส่งมาและไม่ใช่ค่าว่างเปล่าหรือไม่
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            // ค้นหาข้อมูลในตาราง preference
            $preferences = Preferences::where('preference_name', 'like', '%' . $searchTerm . '%')->paginate(16);

            return view('admin.preferences', compact('preferences', 'searchTerm'));
        }

        // ถ้าค่าว่างเปล่า หรือไม่มีค่าที่ส่งมาในช่องค้นหา
        return redirect('/admin/preferences');
    }

    function searchreviews(Request $request)
    {
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');

            $data = DB::table('locations')
                ->join('location_images', 'locations.location_id', '=', 'location_images.location_id')
                ->join('reviews', 'locations.location_id', '=', 'reviews.location_id')
                ->join('members', 'members.member_id', '=', 'reviews.member_id')
                ->when($searchTerm, function ($query) use ($searchTerm) {
                    $query->where('locations.location_name', 'like', '%' . $searchTerm . '%');
                })
                ->select(
                    'locations.location_id',
                    'locations.location_name',
                    DB::raw('ROUND(AVG(reviews.rating), 1) as rating'),
                    DB::raw('COUNT(DISTINCT reviews.rating) as countrat'),
                    DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(members.username), ",", 1) as username'),
                    DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(reviews.review), ",", 1) as comment_txt'),
                    DB::raw('(SELECT GROUP_CONCAT(review_id) FROM reviews WHERE location_id = locations.location_id) as review_id'),
                    DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(location_images.img_path), ",", 1) as img_names')
                )
                ->groupBy('locations.location_id', 'locations.location_name')
                ->paginate(12);

            return view('admin.reviews', compact('data', 'searchTerm'));
        }

        // ถ้าค่าว่างเปล่า หรือไม่มีค่าที่ส่งมาในช่องค้นหา
        return redirect('/admin/reviews');
    }

    function delreviews($review_id)
    {

        try {

            DB::transaction(function () use ($review_id) {
                Reviews::where('review_id', $review_id)->delete();
            });
            return redirect('/admin/reviews')->with('success', 'Delete Success');
        } catch (\Exception $e) {
            // กรณีเกิดข้อผิดพลาด
            return redirect('/admin/reviews')->with('error', 'ลบข้อมูลไม่สำเร็จ !!');
        }
    }

    function updatepreference(Request $request, $preferenceId)
    {
        $request->validate([
            'preference_name' => 'required|string|max:255',
        ]);

        // ตรวจสอบว่ามี preference_name ที่เหมือนกันในฐานข้อมูลหรือไม่
        $existingPreference = Preferences::where('preference_name', $request->input('preference_name'))
            ->where('preference_id', '!=', $preferenceId)
            ->first();

        if ($existingPreference) {
            // ถ้าพบ preference_name ที่ซ้ำกัน
            return redirect('/admin/preferences')->with('error', 'ชื่อประเภทความชอบนี้มีอยู่แล้ว !!');
        }

        // ไม่ต้องใช้ find ก่อน update ใน Eloquent
        Preferences::where('preference_id', $preferenceId)
            ->update(['preference_name' => $request->input('preference_name')]);
        return redirect('/admin/preferences')->with('success', 'Preference Updated Successfully');
    }

    public function updateusers(Request $request, $member_id)
    {
        // ตรวจสอบความถูกต้องของข้อมูลที่ส่งมา
        $request->validate([
            'email' => 'required|email',
            'username' => 'required|string',
            'password' => 'nullable|string|min:6',
            'status' => 'required|in:admin,user',
        ]);

        // ดึงข้อมูลสมาชิก
        $member = Members::findOrFail($member_id);

        // ตรวจสอบ Email ว่าซ้ำหรือไม่
        $existingEmail = Members::where('email', $request->input('email'))
            ->where('member_id', '<>', $member_id)
            ->first();
        if ($existingEmail) {
            return redirect()->back()->with('error', 'Email นี้มีอยู่ในระบบอยู่แล้ว !!');
        }

        // ตรวจสอบ Username ว่าซ้ำหรือไม่
        $existingUsername = Members::where('username', $request->input('username'))
            ->where('member_id', '<>', $member_id)
            ->first();
        if ($existingUsername) {
            return redirect()->back()->with('error', 'Username นี้มีอยู่ในระบบอยู่แล้ว !!');
        }

        // ถ้าระบุรหัสผ่านใหม่ในฟอร์ม
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:6',
            ]);

            // ตรวจสอบว่ารหัสผ่านใหม่ไม่เป็นค่าว่าง และไม่ตรงกับรหัสผ่านที่อยู่ในฐานข้อมูล
            if ($request->input('password') !== $member->password) {
                // แปลงรหัสผ่านใหม่เป็น hash
                $member->password = Hash::make($request->input('password'));
            }
        }

        // อัปเดตข้อมูลตามที่ส่งมา
        $member->email = $request->input('email');
        $member->username = $request->input('username');
        $member->status = $request->input('status');

        // บันทึกการเปลี่ยนแปลง
        $member->save();

        // ส่งกลับไปยังหน้าที่มาพร้อมกับข้อความ 'success'
        return redirect()->back()->with('success', 'Users information updated successfully');
    }


    function locationsMore($location_id)
    {
        // ดึงข้อมูลสถานที่ท่องเที่ยว
        $locationFunction = App::make('getLocation');
        $location = $locationFunction($location_id);

        if (!$location) {
            abort(404); // หากไม่พบข้อมูลสถานที่ท่องเที่ยว
        }

        // ดึงข้อมูลรูปภาพสถานที่ท่องเที่ยว
        $images = DB::table('location_images')
            ->where('location_id', $location_id)
            ->get();

        // ส่งข้อมูลไปยัง view
        return view('admin.locations_more', compact('location', 'images'));
    }




    public function reviews_more($location_id)
    {

        $reviewIds = Reviews::where('location_id', $location_id)->pluck('review_id');

        // // ดึงข้อมูลสถานที่ท่องเที่ยว
        $location = Locations::find($location_id);

        // // ดึงข้อมูลรูปภาพสถานที่ท่องเที่ยว
        $images = LocationImages::where('location_id', $location_id)->get();

        // // ดึงข้อมูลความคิดเห็น
        $reviews = Reviews::whereIn('review_id', $reviewIds)
            ->orderBy('rating', 'desc')
            ->paginate(6);


        // // ดึงข้อมูลสมาชิกที่รีวิว
        $members = Reviews::leftJoin('members', 'reviews.member_id', '=', 'members.member_id')
            ->leftJoin('locations', 'reviews.location_id', '=', 'locations.location_id')
            ->select('members.username', 'reviews.*', 'locations.*')
            ->whereIn('reviews.review_id', $reviewIds)
            ->get();



        // ส่งข้อมูลไปยัง view
        return view('admin.reviews_more', compact('location', 'images', 'members', 'reviews'));
    }

    public function updatephoto(Request $request, $imgId)
    {
        // ใช้ validation ตรวจสอบไฟล์ที่อัปโหลด
        $request->validate([
            'img_path' => 'required|image|mimes:jpeg,png|max:2048', // ระบุประเภทไฟล์ที่ยอมรับได้
        ]);

        // ตรวจสอบว่ามีไฟล์รูปถูกอัปโหลดหรือไม่
        if ($request->hasFile('img_path')) {
            $image = $request->file('img_path');

            // ดึง location_id จาก Input Hidden
            $locationId = $request->input('location_id');

            // ตรวจสอบว่า location_id ที่ได้จาก Input Hidden ไม่เป็นค่า null
            if ($locationId !== null) {
                // กำหนดชื่อไฟล์
                $imgPath = $locationId . '/' . time() . '_' . $image->getClientOriginalName();

                // ลบภาพเก่า
                $oldImage = LocationImages::where('img_id', $imgId)->first();
                if ($oldImage) {
                    // ใช้ Storage::delete เพื่อลบไฟล์จาก storage
                    Storage::delete('public/images/locations/' . $oldImage->img_path);
                    $oldImage->delete();
                }

                // บันทึกข้อมูลรูปใหม่
                LocationImages::create([
                    'img_id' => $imgId,
                    'img_path' => $imgPath,
                    'credit' => $request->input('credit'),
                    'location_id' => $locationId, // ใส่ค่า location_id ที่ได้จาก Input Hidden
                ]);

                // อัปโหลดไฟล์รูป
                $image->storeAs("public/images/locations/", $imgPath);

                return redirect()->back()->with('success', 'Image Updated Successfully.');
            } else {
                // กรณีไม่พบ location_id ใน Input Hidden
                return redirect()->back()->with('error', 'Unable to find location_id from the form.');
            }
        }

        return redirect()->back()->with('error', 'No image uploaded.');
    }


    public function updateLocation(Request $request, $id)
    {
        $request->validate([
            'location_name' => 'required|string',
            'address' => 'required',
            'detail' => 'required',
            's_time' => 'required',
            'e_time' => 'required',
            'province_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'credit' => 'required',
            'preferences_id' => 'required',
            'days_id' => 'required',
        ]);

        // เพิ่มเงื่อนไขตรวจสอบ location_name ซ้ำ
        $existingLocationName = DB::table('locations')
            ->where('location_name', $request->location_name)
            ->where('location_id', '!=', $id)
            ->first();

        if ($existingLocationName) {
            // dd($existingLocationName); // หรือ var_dump($existingLocationName);
            // return redirect()->back()->with('error', 'Location name already exists.');
            return redirect('/admin/locations')->with('error', 'ชื่อสถานที่ท่องเที่ยวนี้ ซ้ำ !!');
        }

        // เพิ่มเงื่อนไขตรวจสอบ latitude และ longitude ซ้ำ
        $existingLocationCoordinates = DB::table('locations')
            ->where('latitude', $request->latitude)
            ->where('location_id', '!=', $id)
            ->first();

        if ($existingLocationCoordinates) {
            // return redirect()->back()->with('error', 'Location with these coordinates already exists.');
            return redirect('/admin/locations')->with('error', 'Latitude นี้มีอยู่แล้ว !!');
        }

        // เพิ่มเงื่อนไขตรวจสอบ latitude และ longitude ซ้ำ
        $existingLocationCoordinates1 = DB::table('locations')
            ->where('longitude', $request->longitude)
            ->where('location_id', '!=', $id)
            ->first();

        if ($existingLocationCoordinates1) {
            // return redirect()->back()->with('error', 'Location with these coordinates already exists.');
            return redirect('/admin/locations')->with('error', 'Longitude นี้มีอยู่แล้ว !!');
        }

        $data = [
            'location_name' => $request->location_name,
            'address' => $request->address,
            'detail' => $request->detail,
            's_time' => $request->s_time,
            'e_time' => $request->e_time,
            'province_id' => $request->province_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        $data1 = [
            'credit' => $request->credit
        ];

        DB::table('locations')->where('location_id', $id)->update($data);
        DB::table('location_images')->where('location_id', $id)->update($data1);

        $preference_ids = $request->preferences_id;
        $existingTypes = LocationTypes::where('location_id', $id)->pluck('preference_id')->toArray();
        $idsToRemove = array_diff($existingTypes, $preference_ids);
        LocationTypes::where('location_id', $id)->whereIn('preference_id', $idsToRemove)->delete();
        foreach ($preference_ids as $prefId) {
            // Check if the record already exists before adding
            if (!in_array($prefId, $existingTypes)) {
                // Record doesn't exist, add it
                LocationTypes::create([
                    'location_id' => $id,
                    'preference_id' => $prefId,
                ]);
            }
        }

        $day_ids = $request->days_id;
        $existingDays = DaysOpening::where('location_id', $id)->pluck('day_id')->toArray();
        $idsToRemove = array_diff($existingDays, $day_ids);
        DaysOpening::where('location_id', $id)->whereIn('day_id', $idsToRemove)->delete();
        foreach ($day_ids as $dayId) {
            // Check if the record already exists before adding
            if (!in_array($dayId, $existingDays)) {
                // Record doesn't exist, add it
                DaysOpening::create([
                    'location_id' => $id,
                    'day_id' => $dayId,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Updated Successfully.');
    }


    function fetchData(Request $request)
    {
        $searchProvince = $request->input('searchProvince');

        $query = DB::table('locations')
            ->join('location_images', 'locations.location_id', '=', 'location_images.location_id')
            ->groupBy('locations.location_name', 'locations.detail', 'locations.s_time', 'locations.e_time', 'locations.location_id')
            ->selectRaw('locations.location_id,locations.location_name,locations.detail,locations.s_time,locations.e_time,SUBSTRING_INDEX(GROUP_CONCAT(location_images.img_path), ",", 1) as img_names')
            ->orderBy('locations.location_id', 'asc');

        // ตรวจสอบว่ามีการเลือกจังหวัดหรือไม่
        if ($searchProvince && $searchProvince != 'ทั้งหมด...') {
            $query->where('locations.province_id', $searchProvince);
        }

        $locations = $query->paginate(6);
        $preferences = DB::table('preferences')->paginate(12);

        return view('admin.locations', compact('locations', 'preferences'));
    }

    function questions()
    {
        $questions = DB::table('questions')->paginate(9);
        return view('admin.questions', compact('questions'));
    }

    function insertQue(Request $request)
    {
        try {
            // ตรวจสอบว่า question_text ไม่ซ้ำ
            $existingQuestion = Questions::where('question_text', $request->input('question_text'))->first();
            if ($existingQuestion) {
                // กรณีมีข้อมูลซ้ำ
                return redirect('/admin/questions')->with('error', 'คำถามนี้มีอยู่แล้วในระบบ !!');
            }

            // ตรวจสอบข้อมูลอื่น ๆ ด้วย Laravel Validation
            $request->validate([
                'question_text' => 'required|string',
            ]);

            // เพิ่มคำถามลงในฐานข้อมูล
            $question = new Questions();
            $question->question_text = $request->input('question_text');
            $question->save();

            return redirect('/admin/questions')->with('success', 'เพิ่มคำถามสำเร็จแล้ว');
        } catch (\Exception $e) {
            // กรณีเกิดข้อผิดพลาด
            return redirect('/admin/questions')->with('error', 'เกิดข้อผิดพลาดในการเพิ่มคำถาม !!');
        }
    }

    function delQue($question_id)
    {

        try {

            DB::transaction(function () use ($question_id) {
                Questions::where('question_id', $question_id)->delete();
            });
            return redirect('/admin/questions')->with('success', 'Delete Success');
        } catch (\Exception $e) {
            // กรณีเกิดข้อผิดพลาด
            return redirect('/admin/questions')->with('error', 'ลบข้อมูลไม่สำเร็จ !!');
        }
    }

    function searchQue(Request $request)
    {
        // ตรวจสอบว่ามีค่าที่ส่งมาและไม่ใช่ค่าว่างเปล่าหรือไม่
        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            // ค้นหาข้อมูลในตาราง preference
            $questions = Questions::where('question_text', 'like', '%' . $searchTerm . '%')->paginate(12);

            return view('admin.questions', compact('questions', 'searchTerm'));
        }

        // ถ้าค่าว่างเปล่า หรือไม่มีค่าที่ส่งมาในช่องค้นหา
        return redirect('/admin/questions');
    }

    function updatequestions(Request $request, $question_id)
    {
        $request->validate([
            'question_text' => 'required|string|max:255',
        ]);

        // ตรวจสอบว่ามี question_text ที่เหมือนกันในฐานข้อมูลหรือไม่
        $existingPreference = Questions::where('question_text', $request->input('question_text'))
            ->where('question_id', '!=', $question_id)
            ->first();

        if ($existingPreference) {
            // ถ้าพบ question_text ที่ซ้ำกัน
            return redirect('/admin/questions')->with('error', 'ชื่อประเภทความชอบนี้มีอยู่แล้ว !!');
        }

        // ไม่ต้องใช้ find ก่อน update ใน Eloquent
        Questions::where('question_id', $question_id)
            ->update(['question_text' => $request->input('question_text')]);
        return redirect('/admin/questions')->with('success', 'Questions Updated Successfully');
    }
}
