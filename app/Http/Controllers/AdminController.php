<?php
namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
// Zipped
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
// Session
use ZipArchive;



class AdminController extends Controller
{
    protected $date;
    protected $today_date;
    public function __construct()
    {
        $kolkataDateTime = Carbon::now('Asia/Kolkata');
        $this->date = $kolkataDateTime->format('Y-m-d h:i A');
        $this->today_date = $kolkataDateTime->format('Y-m-d');
    }

    ### Feed 
    public function add_feed(Request $request)
    {
        $path1 = "";
        if ($request->hasFile('image')) {
            $file = $request->file("image");
            $uniqid = uniqid();
            $name = $uniqid . "." . $file->getClientOriginalExtension();
            $request->image->move(public_path('storage/Admin/feed/'), $name);
            $path1 = "https://rupiyamaker.m-bit.org.in//storage/Admin/feed/$name";
        } else {
        }
        $data = [
            'admin_id' => $request->admin_id,
            'message' => $request->message,
            'image' => $path1,
            'permition' => implode(',', $request->permition),
            'date' => $this->date
        ];
        if (!empty($data)) {
            $res = DB::table('tbl_feed')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }

    // Update pin Value By Login User 
    // public function updateFeedStatus(Request $request)
    // {
    //     $feed_id = $request->id;
    //     $admin_id = $request->admin_id; 

    //     if (!$feed_id || !$admin_id) {
    //         return response()->json(['status' => 0, 'message' => 'Invalid Request']);
    //     }

    //     // ✅ Step 1: Check if the clicked feed is already pinned by this admin
    //     $currentPinned = DB::table('tbl_feed')
    //         ->where('id', $feed_id)
    //         ->where('admin_id', $admin_id)
    //         ->where('pinned', 1)
    //         ->first();

    //     // ✅ Step 2: If already pinned, unpin it
    //     if ($currentPinned) {
    //         DB::table('tbl_feed')
    //             ->where('id', $feed_id)
    //             ->where('admin_id', $admin_id)
    //             ->update(['pinned' => 0]);

    //         return response()->json(['status' => 1, 'message' => 'Feed unpinned successfully', 'pinned' => 0]);
    //     }

    //     // ✅ Step 3: Unpin any previously pinned feed before pinning the new one
    //     DB::table('tbl_feed')
    //         ->where('admin_id', $admin_id)
    //         ->where('pinned', 1)
    //         ->update(['pinned' => 0]);

    //     // ✅ Step 4: Pin the new feed
    //     DB::table('tbl_feed')
    //         ->where('id', $feed_id)
    //         ->where('admin_id', $admin_id)
    //         ->update(['pinned' => 1]);

    //     return response()->json(['status' => 1, 'message' => 'Feed pinned successfully', 'pinned' => 1]);
    // }


    public function updateFeedStatus(Request $request)
    {
        $adminSession = collect(session()->get('admin_login'))->first();
        if (!$adminSession) {
            return response()->json(['status' => 0, 'message' => 'Session Expired'], 401);
        }

        $admin_id = $adminSession->id;
        $admin_role = $adminSession->role;
        $feedId = $request->id;

        $feed = DB::table('tbl_feed')->where('id', $feedId)->first();
        if (!$feed) {
            return response()->json(['status' => 0, 'message' => 'Feed not found'], 404);
        }

        // ✅ Admin और HR किसी भी feed को Pin/Unpin कर सकते हैं
        if (in_array($admin_role, ['Admin', 'HR'])) {
            return $this->togglePinnedFeed($feedId);
        }

        // ✅ Admin/HR के अलावा, permission कॉलम में role चेक करें
        $allowed_roles = explode(',', $feed->permition); // Permission को array में convert करें
        if (in_array($admin_role, $allowed_roles)) {
            return $this->togglePinnedFeed($feedId);
        }

        return response()->json(['status' => 0, 'message' => 'You are not authorized to pin this feed']);
    }
    private function togglePinnedFeed($feedId)
    {
        $feed = DB::table('tbl_feed')->where('id', $feedId)->first();
        if (!$feed) {
            return response()->json(['status' => 0, 'message' => 'Feed not found'], 404);
        }

        $newPinnedStatus = $feed->pinned == 1 ? 0 : 1; // Toggle pinned status

        // अगर पिन हो रहा है, तो पहले से पिन की हुई किसी भी feed को unpin कर दें
        if ($newPinnedStatus == 1) {
            DB::table('tbl_feed')->update(['pinned' => 0]); // सभी पिन हटा दें
        }

        // नई feed को update करें
        DB::table('tbl_feed')
            ->where('id', $feedId)
            ->update(['pinned' => $newPinnedStatus]);

        return response()->json(['status' => 1, 'message' => 'Pinned Updated', 'pinned' => $newPinnedStatus]);
    }




    // Feed Add Comment Here 
    public function storeComment(Request $request)
    {
        DB::table('tbl_feed_comment')->insert([
            'feed_id' => $request->feed_id,
            'admin_id' => $request->admin_id,
            'comment' => $request->comment,
            'date' => $this->date
        ]);
        return response()->json(['status' => 1, 'message' => 'Comment added successfully']);
    }



    #################### Company Category Excel Upload Start Here ####################
    public function companyCategory()
    {
        return view('Admin.pages.company_category');
    }
    // show company_category
    public function showCompanyCategory(Request $request)
    {
        $search = $request->search;
        $query = DB::table('tbl_company_category');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', '%' . $search . '%');
            });
        }
        $enquiries = $query->orderBy('id', 'desc')->paginate(100);
        return response()->json([
            'data' => $enquiries->items(),
            'current_page' => $enquiries->currentPage(),
            'last_page' => $enquiries->lastPage(),
            'per_page' => $enquiries->perPage(),
            'total' => $enquiries->total(),
            'success' => 'success'
        ]);
    }

    // add company_category Excel Upload normal upload
    public function addCompanyCategory1(Request $request)
    {
        if ($request->hasFile('file_import')) {
            $file = $request->file('file_import');
            // Generate a unique name for the uploaded file
            $unique_id = uniqid();
            $fileName = $unique_id . '.' . $file->getClientOriginalExtension();
            $filePath = public_path('/storage/csv/' . $fileName);
            // Move the uploaded file to the storage directory
            $file->move(public_path('/storage/csv/'), $fileName);
            // Open the CSV file for reading
            $csvFile = fopen($filePath, 'r');
            $isFirstRow = true; // Flag to skip the first row (header)
            while (($line = fgetcsv($csvFile)) !== false) {
                if ($isFirstRow) {
                    $isFirstRow = false; // Skip header
                    continue;
                }
                // Extract values from CSV columns
                $company_name = trim($line[0] ?? '');     // Column 1 - Company Name
                $company_category = trim($line[1] ?? ''); // Column 2 - Company Category
                $company_bank = trim($line[2] ?? '');     // Column 3 - Company Bank
                // Insert only if all values are present
                if (!empty($company_name) && !empty($company_category) && !empty($company_bank)) {
                    DB::table('tbl_company_category')->insert([
                        'company_name' => $company_name,
                        'company_category' => $company_category,
                        'company_bank' => $company_bank
                    ]);
                }
            }
            fclose($csvFile); // Close the file
            // Fetch all records after import
            $result = DB::table('tbl_company_category')->orderBy('id', 'desc')->get();
            return response()->json([
                'data' => $result,
                'success' => 'success',
                'message' => 'CSV imported successfully!',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
                'message' => 'No file uploaded',
            ]);
        }
    }

    // Excel Upload Morethan 591467 data a time Using Batch 
    public function addCompanyCategory(Request $request)
    {
        // Increase execution time to 5 minutes (300 seconds)
        ini_set('max_execution_time', 300);

        if ($request->hasFile('file_import')) {
            $file = $request->file('file_import');

            // Generate a unique file name
            $unique_id = uniqid();
            $fileName = $unique_id . '.' . $file->getClientOriginalExtension();
            $filePath = public_path('/storage/csv/' . $fileName);

            // Move the file
            $file->move(public_path('/storage/csv/'), $fileName);

            // Open the CSV file
            $csvFile = fopen($filePath, 'r');

            $isFirstRow = true; // Skip first row (header)
            $batchData = []; // Store records for batch insert
            $batchSize = 1000; // Insert 1000 records at a time

            while (($line = fgetcsv($csvFile)) !== false) {
                if ($isFirstRow) {
                    $isFirstRow = false;
                    continue;
                }

                $company_name = trim($line[0] ?? '');
                $company_category = trim($line[1] ?? '');
                $company_bank = trim($line[2] ?? '');

                if (!empty($company_name) && !empty($company_category) && !empty($company_bank)) {
                    $batchData[] = [
                        'company_name' => $company_name,
                        'company_category' => $company_category,
                        'company_bank' => $company_bank
                    ];
                }

                // Insert in batches of 1000 records
                if (count($batchData) >= $batchSize) {
                    DB::table('tbl_company_category')->insert($batchData);
                    $batchData = []; // Clear batch data
                }
            }

            // Insert remaining records
            if (!empty($batchData)) {
                DB::table('tbl_company_category')->insert($batchData);
            }

            fclose($csvFile); // Close file after processing

            return response()->json([
                'success' => 'success',
                'message' => 'CSV imported successfully!',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
                'message' => 'No file uploaded',
            ]);
        }
    }






    // delete company_category
    public function deleteCompanyCategory(Request $request)
    {
        if (!empty($request->id)) {
            $delete_data = DB::table("tbl_company_category")->where("id", $request->id)->delete();
            $data = DB::table("tbl_company_category")->orderBy('id', 'desc')->get();
            return response()->json(['success' => 'success', 'data' => $data]);
        } else {
        }
    }

    // update company_category 
    public function updateCompanyCategory(Request $request)
    {
        $data = [
            'id' => $request->id,
            'company_name' => $request->company_name,
            'company_category' => $request->company_category,
            'company_bank' => $request->company_bank
        ];

        // Update data in the database
        if (!empty($data)) {
            $update = DB::table('tbl_company_category')->where('id', $request->id)->update($data);
            $company_data = DB::table('tbl_company_category')->orderBy('id', 'desc')->get();
            return response()->json([
                'data' => $company_data,
                'success' => 'success'
            ]);
        } else {
            return response()->json([
                'data' => '1',
                'success' => 'error'
            ]);
        }
    }










    // Excel Upload Here
    public function importExcel(Request $request)
    {
        if ($request->hasFile('file_import')) {
            $file = $request->file('file_import');

            // Generate a unique name for the uploaded file
            $unique_id = uniqid();
            $fileName = $unique_id . '.' . $file->getClientOriginalExtension();
            $filePath = public_path('/storage/csv/' . $fileName);

            // Move the uploaded file to the storage directory
            $file->move(public_path('/storage/csv/'), $fileName);

            // Open the CSV file for reading
            $csvFile = fopen($filePath, 'r');

            $isFirstRow = true; // Flag to skip the first row (header)

            while (($line = fgetcsv($csvFile)) !== false) {
                if ($isFirstRow) {
                    $isFirstRow = false; // Skip header
                    continue;
                }

                $name = trim($line[0] ?? '');    // Column 1 - Name
                $email = trim($line[1] ?? '');   // Column 2 - Email
                $mobile = trim($line[2] ?? '');  // Column 3 - Mobile

                if (!empty($name) && !empty($email) && !empty($mobile)) {
                    DB::table('contacts')->insert([
                        'name' => $name,
                        'email' => $email,
                        'mobile' => $mobile
                    ]);
                }
            }

            fclose($csvFile); // Close the file

            return response()->json([
                'success' => 'success',
                'message' => 'CSV imported successfully!',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
                'message' => 'No file uploaded',
            ]);
        }
    }















    // checkMobileExistence
    public function checkMobileExistence(Request $request)
    {
        $mobile = $request->mobile;
        $alternate_mobile = $request->alternate_mobile;
        $product_id = $request->product_id;

        $existingMobile = DB::table('tbl_lead')
            ->where('product_id', $product_id)
            ->where(function ($query) use ($mobile) {
                $query->where('mobile', $mobile)
                    ->orWhere('alternate_mobile', $mobile);
            })
            ->exists();

        $existingAlternateMobile = DB::table('tbl_lead')
            ->where('product_id', $product_id) // Ensure product_id matches
            ->orWhere('mobile', $alternate_mobile)
            ->orWhere('alternate_mobile', $alternate_mobile)
            ->first();

        // Return a response indicating which field already exists
        if ($existingMobile) {
            return response()->json(['exists' => true, 'message' => 'Mobile Number Already Exists for this Lead', 'field' => 'mobile']);
        } elseif ($existingAlternateMobile) {
            return response()->json(['exists' => true, 'message' => 'Mobile Number Already Exists for this Lead', 'field' => 'alternate_mobile']);
        }
        return response()->json(['exists' => false]);
    }

    // Attachments Show Start Here All Content
    public function getLeadData(Request $request)
    {
        $data = DB::table('tbl_lead')
            ->where('admin_id', $request->admin_id)
            ->where('id', $request->lead_id)
            ->first();
        if ($data) {
            return response()->json($data);
        } else {
            return response()->json(['message' => 'No data found'], 404);
        }
    }

    // Attachments Show End Here All Content

    // Zipped only images or pdf download
    public function downloadZipnew($id)
    {
        $item = DB::table('tbl_lead')->where('id', $id)->first();
        if (!$item) {
            return response()->json(['error' => 'Record not found'], 404);
        }
        $imageFields = [
            'cibil_report_image',
            'passport_image',
            'pan_card_image',
            'aadhar_card_image',
            'salary_3month_image',
            'salary_account_image',
            'bt_loan_image',
            'credit_card_image',
            'electric_bill_image',
            'form_16_image',
            'other_document_image',
        ];
        // Files ko collect karein (jo null ya missing nahi hain)
        $files = [];
        foreach ($imageFields as $field) {
            if (!empty($item->$field)) {
                $filePath = public_path(parse_url($item->$field, PHP_URL_PATH));
                if (File::exists($filePath)) {
                    $files[$field] = $filePath;
                }
            }
        }
        if (empty($files)) {
            return response()->json(['error' => 'No files found to download'], 404);
        }
        $zipFileName = 'attachments-' . $id . '.zip';
        $zipPath = storage_path($zipFileName);

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($files as $field => $filePath) {
                $zip->addFile($filePath, $field . '-' . basename($filePath));
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'Failed to create zip file'], 500);
        }
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    // image or pdf with all password file download code
    public function downloadZipWithPdf($id)
    {
        $item = DB::table('tbl_lead')->where('id', $id)->first();
        if (!$item) {
            return response()->json(['error' => 'Record not found'], 404);
        }

        $imageFields = [
            'cibil_report_image',
            'passport_image',
            'pan_card_image',
            'aadhar_card_image',
            'salary_3month_image',
            'salary_account_image',
            'bt_loan_image',
            'credit_card_image',
            'electric_bill_image',
            'form_16_image',
            'other_document_image',
        ];

        // Collect files (that are not null or missing)
        $files = [];
        foreach ($imageFields as $field) {
            if (!empty($item->$field)) {
                $filePath = public_path(parse_url($item->$field, PHP_URL_PATH));
                if (File::exists($filePath)) {
                    $files[$field] = $filePath;
                }
            }
        }

        if (empty($files)) {
            return response()->json(['error' => 'No files found to download'], 404);
        }

        // Add the `all_file_password` field as a text file if it exists
        $passwordFilePath = null;
        if (!empty($item->all_file_password)) {
            $passwordFileName = 'all_file_password.txt';
            $passwordFilePath = storage_path($passwordFileName);
            File::put($passwordFilePath, $item->all_file_password);
        }

        $zipFileName = 'attachments-' . $id . '.zip';
        $zipPath = storage_path($zipFileName);

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($files as $field => $filePath) {
                $zip->addFile($filePath, $field . '-' . basename($filePath));
            }

            // Add the password file to the zip if it exists
            if ($passwordFilePath && File::exists($passwordFilePath)) {
                $zip->addFile($passwordFilePath, 'all_file_password.txt');
            }

            $zip->close();
        } else {
            return response()->json(['error' => 'Failed to create zip file'], 500);
        }

        // Delete the password file after adding it to the zip
        if ($passwordFilePath && File::exists($passwordFilePath)) {
            File::delete($passwordFilePath);
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    // image or pdf and all password folder ke andar file upload ho
    public function downloadZip($id)
    {
        $item = DB::table('tbl_lead')->where('id', $id)->first();
        if (!$item) {
            return response()->json(['error' => 'Record not found'], 404);
        }
        $imageFields = [
            'cibil_report_image',
            'passport_image',
            'pan_card_image',
            'aadhar_card_image',
            'salary_3month_image',
            'salary_account_image',
            'bt_loan_image',
            'credit_card_image',
            'electric_bill_image',
            'form_16_image',
            'other_document_image',
        ];
        $files = [];
        foreach ($imageFields as $field) {
            if (!empty($item->$field)) {
                $filePath = public_path(parse_url($item->$field, PHP_URL_PATH));
                if (File::exists($filePath)) {
                    $files[$field] = $filePath;
                }
            }
        }
        if (empty($files)) {
            return response()->json(['error' => 'No files found to download'], 404);
        }
        $passwordFilePath = null;
        if (!empty($item->all_file_password)) {
            $passwordFileName = 'all_file_password.txt';
            $passwordFilePath = storage_path($passwordFileName);
            File::put($passwordFilePath, $item->all_file_password);
        }
        // $zipFileName = 'attachments-' . $id . '.zip';
        $zipFileName = $item->name . '-' . $item->mobile . '.zip';

        $zipPath = storage_path($zipFileName);
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($files as $field => $filePath) {
                $folderName = str_replace('_image', '', $field);
                $zip->addFile($filePath, $folderName . '/' . basename($filePath));
            }
            if ($passwordFilePath && File::exists($passwordFilePath)) {
                $zip->addFile($passwordFilePath, 'password/all_file_password.txt');
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'Failed to create zip file'], 500);
        }
        if ($passwordFilePath && File::exists($passwordFilePath)) {
            File::delete($passwordFilePath);
        }
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    // Admin Login Here
    public function admin_login(Request $request)
    {
        // dd($request->all());
        $email = $request->email;
        $password = $request->password;

        $login = DB::table('admin')->where(['email' => $email, 'password' => $password, 'status' => '1'])->first();
        if (is_null($login)) {
            return response()->json(['success' => '0']);
        } else {
            $data = DB::table('admin')->where(['email' => $email, 'password' => $password, 'status' => '1'])->get();
            session()->put("admin_login", $data);
            return response()->json([
                'data' => $data,
                'success' => '1',
            ]);
        }
    }

    // change password set Start Here
    public function changepasswordset(Request $request)
    {
        $admin_id = $request->admin_id;
        $opass = $request->opass;
        $npass = $request->npass;
        $cpass = $request->cpass;

        $updatedata = [
            'password' => $npass,
        ];

        $check = DB::table("admin")->where('id', $admin_id)->first();

        if ($check->password == $opass) {
            if ($npass == $cpass) {
                $up = DB::table("admin")->where('id', $admin_id)->update($updatedata);
                if ($up) {
                    return response()->json(['success' => 'success']);
                } else {
                    return response()->json(['success' => 'not_match']);
                }
            } else {
                return response()->json(['success' => 'opass_npass']);
            }
        } else {
            return response()->json(['success' => 'old_pass']);
        }
    }

    // update profile
    public function updateprofile(Request $request)
    {
        $admin_id = $request->admin_id;
        $updatedata = [
            'name' => $request->name,
            'email' => $request->email,
            'mobile' => $request->mobile,
        ];

        if ($admin_id) {
            $up = DB::table("admin")->where('id', $admin_id)->update($updatedata);
            $data = DB::table('admin')->where('id', $admin_id)->get();
            session()->forget('admin_login');
            session()->put('admin_login', $data);
            if ($up) {
                return response()->json(['success' => 'success']);
            }
        }
    }

    // Admin Logout Here
    public function admin_logout(Request $request)
    {
        session()->forget('admin_login');
        return redirect('/login');
    }

    // this code is switch status active update
    public function switch_status_update_active(Request $request, $tableName)
    {
        $id = $request->id;
        $status = $request->status;

        if (!empty($id)) {
            $data = [
                'status' => $status,
                'crm_status' => $status
            ];

            $update = DB::table($tableName)->where('id', $id)->update($data);
            return response()->json([
                'data' => $update,
                'success' => 'success',
            ]);
        }
    }




    // this code is switch status update
    public function switch_status_update(Request $request, $tableName)
    {

        $id = $request->id;
        $data = [];
        $data['status'] = $request->status;
        if (!empty($id)) {

            $update = DB::table($tableName)->where('id', $id)->update($data);

            return response()->json([
                'data' => $update,
                'success' => 'success',
            ]);
        }
    }
    // this code is switch crm_status update
    public function switch_crm_access_update(Request $request, $tableName)
    {

        $id = $request->id;
        $data = [];
        $data['crm_status'] = $request->crm_status;
        if (!empty($id)) {
            $update = DB::table($tableName)->where('id', $id)->update($data);
            return response()->json([
                'data' => $update,
                'success' => 'success',
            ]);
        }
    }

    ### Users Show Here ###
    public function show_user()
    {
        $data['users'] = DB::table('users')->orderBy('id', 'desc')->get();
        return view('Admin.pages.user', $data);
    }

    // user delete here
    public function delete_user(Request $request)
    {
        $imagedata = DB::table('users')->where('id', $request->id)->first();
        $url1 = $imagedata->image;
        $image_name1 = basename($url1);
        if ($image_name1) {
            $image_path1 = public_path("storage/Admin/user/" . $image_name1);
            if (file_exists($image_path1)) {
                unlink($image_path1);
            }
        }
        if (!empty($request->id)) {
            $delete_data = DB::table("users")->where("id", $request->id)->delete();
            return response()->json(['success' => 'success']);
        } else {
        }
    }

    ############## Setting Start Here All Crud Manage Here ################
    public function Settings()
    {
        return view('Admin.pages.Settings');
    }

    #### Manage Product ####
    public function product()
    {
        $data['products'] = DB::table('tbl_product')->orderBy('id', 'desc')->get();
        return view('Admin.pages.product', $data);
    }
    // add product
    public function add_product(Request $request)
    {
        $data = [
            'status' => '1',
            'product_name' => $request->product_name,
            'date' => $this->date,
        ];
        if (!empty($data)) {
            $res = DB::table('tbl_product')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }
    // product delete here
    public function delete_product(Request $request)
    {
        if (!empty($request->id)) {
            $delete_data = DB::table("tbl_product")->where("id", $request->id)->delete();
            return response()->json(['success' => 'success']);
        } else {
        }
    }
    // update product
    public function update_product(Request $request)
    {
        $data = [
            'id' => $request->id,
            'product_name' => $request->product_name,
        ];

        if (!empty($data)) {
            $update = DB::table('tbl_product')->where('id', $request->id)->update($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    #### Manage Important Question Start Here ####
    public function imp_question()
    {
        // $data['tbl_imp_question'] = DB::table('tbl_imp_question')->orderBy('id', 'desc')->get();
        $data['imp_questions'] = DB::table('tbl_imp_question')
            ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_imp_question.product_id')
            ->select('tbl_imp_question.*', 'tbl_product.product_name')
            ->orderBy('tbl_imp_question.id', 'desc')
            ->get(); // इसे जोड़ना जरूरी है

        return view('Admin.pages.imp_question', $data);

    }

    // add bank
    public function add_imp_question(Request $request)
    {
        $path1 = "";
        if ($request->hasFile('audio')) {
            $file = $request->file("audio");
            $uniqid = uniqid();
            $name = $uniqid . "." . $file->getClientOriginalExtension();
            $request->audio->move(public_path('storage/Admin/audio/'), $name);
            $path1 = "https://rupiyamaker.m-bit.org.in//storage/Admin/audio/$name";
        } else {
        }

        $data = [
            'product_id' => $request->product_id,
            'imp_question_name' => $request->imp_question_name,
            'audio' => $path1,
            'date' => $this->date,
        ];

        if (!empty($data)) {
            $res = DB::table('tbl_imp_question')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }

    // bank delete here
    public function delete_imp_question(Request $request)
    {
        $audiodata = DB::table('tbl_imp_question')->where('id', $request->id)->first();
        $url1 = $audiodata->audio;
        $audio_name1 = basename($url1);
        if ($audio_name1) {
            $audio_path1 = public_path("storage/Admin/audio/" . $audio_name1);
            if (file_exists($audio_path1)) {
                unlink($audio_path1);
            }
        }
        if (!empty($request->id)) {
            $delete_data = DB::table("tbl_imp_question")->where("id", $request->id)->delete();
            return response()->json(['success' => 'success']);
        } else {
        }
    }

    // update bank
    public function update_imp_question(Request $request)
    {
        $audiodata = DB::table('tbl_imp_question')->where('id', $request->id)->first();
        // Update image1 if it exists in the request
        if ($request->hasFile('audio')) {
            $url = $audiodata->audio;
            // Remove old image if it exists
            if ($url) {
                $audio_path = public_path("storage/Admin/audio/" . basename($url));
                if (file_exists($audio_path)) {
                    unlink($audio_path);
                }
            }
            $file = $request->file('audio');
            $unique_id = uniqid();
            $name = $unique_id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/Admin/audio/'), $name);
            $path = "https://rupiyamaker.m-bit.org.in//storage/Admin/audio/$name";
        } else {
            $path = $imagedata->audio;
        }
        $data = [
            'id' => $request->id,
            'product_id' => $request->product_id,
            'imp_question_name' => $request->imp_question_name,
        ];
        if ($path !== "") {
            $data['audio'] = $path;
        }
        if (!empty($data)) {
            $update = DB::table('tbl_imp_question')->where('id', $request->id)->update($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    ###### Manage Campaign ######
    public function campaign()
    {
        $data['campaigns'] = DB::table('tbl_campaign')->orderBy('id', 'desc')->get();
        return view('Admin.pages.campaign', $data);
    }
    // add campaign
    public function add_campaign(Request $request)
    {
        $data = [
            'status' => '1',
            'campaign_name' => $request->campaign_name,
            'date' => $this->date,
        ];
        if (!empty($data)) {
            $res = DB::table('tbl_campaign')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }
    // campaign delete here
    public function delete_campaign(Request $request)
    {
        if (!empty($request->id)) {
            $delete_data = DB::table("tbl_campaign")->where("id", $request->id)->delete();
            return response()->json(['success' => 'success']);
        } else {
        }
    }
    // update campaign
    public function update_campaign(Request $request)
    {
        $data = [
            'id' => $request->id,
            'campaign_name' => $request->campaign_name,
        ];

        if (!empty($data)) {
            $update = DB::table('tbl_campaign')->where('id', $request->id)->update($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    #### Manage bank ####
    public function bank()
    {
        $data['banks'] = DB::table('tbl_bank')->orderBy('id', 'desc')->get();
        return view('Admin.pages.bank', $data);
    }

    // add bank
    public function add_bank(Request $request)
    {
        $path1 = "";
        if ($request->hasFile('image')) {
            $file = $request->file("image");
            $uniqid = uniqid();
            $name = $uniqid . "." . $file->getClientOriginalExtension();
            $request->image->move(public_path('storage/Admin/bank/'), $name);
            $path1 = "https://rupiyamaker.m-bit.org.in//storage/Admin/bank/$name";
        } else {
        }

        $data = [
            'status' => '1',
            'bank_name' => $request->bank_name,
            'image' => $path1,
            'date' => $this->date,
        ];

        if (!empty($data)) {
            $res = DB::table('tbl_bank')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }

    // bank delete here
    public function delete_bank(Request $request)
    {
        $imagedata = DB::table('tbl_bank')->where('id', $request->id)->first();
        $url1 = $imagedata->image;
        $image_name1 = basename($url1);
        if ($image_name1) {
            $image_path1 = public_path("storage/Admin/bank/" . $image_name1);
            if (file_exists($image_path1)) {
                unlink($image_path1);
            }
        }
        if (!empty($request->id)) {
            $delete_data = DB::table("tbl_bank")->where("id", $request->id)->delete();
            return response()->json(['success' => 'success']);
        } else {
        }
    }

    // update bank
    public function update_bank(Request $request)
    {
        $imagedata = DB::table('tbl_bank')->where('id', $request->id)->first();
        // Update image1 if it exists in the request
        if ($request->hasFile('image')) {
            $url = $imagedata->image;

            // Remove old image if it exists
            if ($url) {
                $image_path = public_path("storage/Admin/bank/" . basename($url));
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }

            $file = $request->file('image');
            $unique_id = uniqid();
            $name = $unique_id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/Admin/bank/'), $name);

            $path = "https://rupiyamaker.m-bit.org.in//storage/Admin/bank/$name";
        } else {
            $path = $imagedata->image;
        }

        $data = [
            'id' => $request->id,
            'bank_name' => $request->bank_name,
        ];

        if ($path !== "") {
            $data['image'] = $path;
        }

        if (!empty($data)) {
            $update = DB::table('tbl_bank')->where('id', $request->id)->update($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    #### Manage Product Need ####
    public function product_need()
    {
        $data['product_needs'] = DB::table('tbl_product_need')->orderBy('id', 'desc')->get();
        return view('Admin.pages.product_need', $data);
    }
    // add product_need
    public function add_product_need(Request $request)
    {
        $data = [
            'status' => '1',
            'product_need' => $request->product_need,
            'date' => $this->date,
        ];
        if (!empty($data)) {
            $res = DB::table('tbl_product_need')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }
    // product_need delete here
    public function delete_product_need(Request $request)
    {
        if (!empty($request->id)) {
            $delete_data = DB::table("tbl_product_need")->where("id", $request->id)->delete();
            return response()->json(['success' => 'success']);
        } else {
        }
    }
    // update product_need
    public function update_product_need(Request $request)
    {
        $data = [
            'id' => $request->id,
            'product_need' => $request->product_need,
        ];

        if (!empty($data)) {
            $update = DB::table('tbl_product_need')->where('id', $request->id)->update($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    ###### Manage casetype  ######
    public function casetype()
    {
        $data['casetypes'] = DB::table('tbl_casetype')->orderBy('id', 'desc')->get();
        return view('Admin.pages.casetype', $data);
    }
    // add casetype
    public function add_casetype(Request $request)
    {
        $data = [
            'status' => '1',
            'casetype' => $request->casetype,
            'date' => $this->date,
        ];
        if (!empty($data)) {
            $res = DB::table('tbl_casetype')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }
    // casetype delete here
    public function delete_casetype(Request $request)
    {
        if (!empty($request->id)) {
            $delete_data = DB::table("tbl_casetype")->where("id", $request->id)->delete();
            return response()->json(['success' => 'success']);
        } else {
        }
    }
    // update casetype
    public function update_casetype(Request $request)
    {
        $data = [
            'id' => $request->id,
            'casetype' => $request->casetype,
        ];

        if (!empty($data)) {
            $update = DB::table('tbl_casetype')->where('id', $request->id)->update($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    ##### Datacode Start Here ######
    public function datacode()
    {
        $data['datacodes'] = DB::table('tbl_datacode')->orderBy('id', 'desc')->get();
        return view('Admin.pages.datacode', $data);
    }
    // add datacode
    public function add_datacode(Request $request)
    {
        $data = [
            'datacode_name' => $request->datacode_name,
            'date' => $this->date,
        ];
        if (!empty($data)) {
            $res = DB::table('tbl_datacode')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }
    // datacode delete here
    public function delete_datacode(Request $request)
    {
        if (!empty($request->id)) {
            $delete_data = DB::table("tbl_datacode")->where("id", $request->id)->delete();
            return response()->json(['success' => 'success']);
        } else {
        }
    }
    // update datacode
    public function update_datacode(Request $request)
    {
        $data = [
            'id' => $request->id,
            'datacode_name' => $request->datacode_name,
        ];

        if (!empty($data)) {
            $update = DB::table('tbl_datacode')->where('id', $request->id)->update($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    ##### warning_type Start Here ######
    public function warning_type()
    {
        $data['warnings'] = DB::table('tbl_warning_type')->orderBy('id', 'desc')->get();
        return view('Admin.pages.warning_type', $data);
    }
    // add warning_type
    public function add_warning_type(Request $request)
    {
        $data = [
            'warning_name' => $request->warning_name,
            'date' => $this->date,
        ];
        if (!empty($data)) {
            $res = DB::table('tbl_warning_type')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }
    // warning_type delete here
    public function delete_warning_type(Request $request)
    {
        if (!empty($request->id)) {
            $delete_data = DB::table("tbl_warning_type")->where("id", $request->id)->delete();
            return response()->json(['success' => 'success']);
        } else {
        }
    }
    // update warning_type
    public function update_warning_type(Request $request)
    {
        $data = [
            'id' => $request->id,
            'warning_name' => $request->warning_name,
        ];

        if (!empty($data)) {
            $update = DB::table('tbl_warning_type')->where('id', $request->id)->update($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    #### Channel Name Here ######
    public function channel_name()
    {
        $data['channelnames'] = DB::table('tbl_channel_name')->orderBy('id', 'desc')->get();
        return view('Admin.pages.channelname', $data);
    }
    // add channelname
    public function add_channel_name(Request $request)
    {
        $data = [
            'channel_name' => $request->channel_name,
            'date' => $this->date,
        ];
        if (!empty($data)) {
            $res = DB::table('tbl_channel_name')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }
    // channelname delete here
    public function delete_channel_name(Request $request)
    {
        if (!empty($request->id)) {
            $delete_data = DB::table("tbl_channel_name")->where("id", $request->id)->delete();
            return response()->json(['success' => 'success']);
        } else {
        }
    }
    // update channelname
    public function update_channel_name(Request $request)
    {
        $data = [
            'id' => $request->id,
            'channel_name' => $request->channel_name,
        ];

        if (!empty($data)) {
            $update = DB::table('tbl_channel_name')->where('id', $request->id)->update($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    ########### Lead Form #########
    public function lead_form()
    {
        return view('Admin.pages.lead_form');
    }

    ### leads ###
    // public function leads()
    // {
    //     return view('Admin.pages.leads');
    // }

    public function leads(Request $request)
    {
        $selectedProductId = $request->get('product_id');
        $mobileNumber = $request->get('mobile');
        return view('Admin.pages.leads', compact('selectedProductId', 'mobileNumber'));
    }

    // add leads old
    public function add_leads111(Request $request)
    {
        $existingLead = DB::table('tbl_lead')->where('mobile', $request->mobile)->first();
        if ($existingLead) {
            return response()->json([
                'success' => 'exists',
                'message' => 'Mobile number already exists for this Lead',
            ]);
        }

        $data = [
            'status' => '1',
            'lead_status' => 'NEW LEAD',
            'admin_id' => $request->admin_id,
            'product_id' => $request->product_id,
            'campaign_id' => $request->campaign_id,
            'data_code' => $request->data_code,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'alternate_mobile' => $request->alternate_mobile,
            'pincode' => $request->pincode,
            'bank_id' => $request->bank_id,
            'product_need_id' => $request->product_need_id,
            'casetype_id' => $request->casetype_id,
            'loan_amount' => $request->loan_amount,
            'tenure' => $request->tenure,
            'year' => $request->year,
            'process' => $request->process,
            'date' => $this->date,
        ];
        if (!empty($data)) {
            $res = DB::table('tbl_lead')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }

    // Delete Lead
    public function DeleteLead(Request $request)
    {
        if (!empty($request->id)) {
            $delete_data = DB::table("tbl_lead")->where("id", $request->id)->delete();
            return response()->json(['success' => 'success']);
        } else {
        }
    }

    // Multiple Deleted Leads
    public function MultipleDeleteLead(Request $request)
    {

        if (!empty($request->id)) {
            $ids = explode(",", $request->id);
            $delete_data = DB::table("tbl_lead")->whereIn("id", $ids)->delete();
            return response()->json(['success' => 'success']);
        } else {
        }
    }

    // add leads
    public function addLeadsOLD(Request $request)
    {
        $existingLead = DB::table('tbl_lead')->where('mobile', $request->mobile)->first();

        if ($existingLead) {
            return response()->json([
                'success' => 'exists',
                'message' => 'Mobile number already exists for this Lead',
            ]);
        }

        $admin_id = $request->admin_id;

        $data = [
            'status' => '1',
            'lead_login_status' => 'Lead',
            'lead_status' => 'NEW LEAD',
            'admin_id' => $admin_id,
            'product_id' => $request->product_id,
            'campaign_id' => $request->campaign_id,
            'data_code' => $request->data_code,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'alternate_mobile' => $request->alternate_mobile,
            'pincode' => $request->pincode,
            'bank_id' => $request->bank_id,
            'product_need_id' => $request->product_need_id,
            'casetype_id' => $request->casetype_id,
            'loan_amount' => $request->loan_amount,
            'tenure' => $request->tenure,
            'year' => $request->year,
            'process' => $request->process,
            'date' => $this->date,
        ];

        if (!empty($data)) {
            // Insert into tbl_lead and get the last inserted ID
            $leadId = DB::table('tbl_lead')->insertGetId($data);

            // Concatenate 'PL' with $leadId in PHP
            $leadIdWithPrefix = 'PL' . $leadId;
            DB::table('tbl_lead')->where('id', $leadId)->update(['leadid' => $leadIdWithPrefix]);

            // Insert into tbl_lead_status using the retrieved leadId
            $leadStatusData = [
                'admin_id' => $admin_id,
                'lead_id' => $leadId,
                'lead_status' => 'NEW LEAD',
                'date' => $this->date,
            ];
            DB::table('tbl_lead_status')->insert($leadStatusData);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }

    public function addLeads(Request $request)
    {
        // Check if lead already exists
        $existingLead = DB::table('tbl_lead')->where('mobile', $request->mobile)->first();

        if ($existingLead) {
            return response()->json([
                'success' => 'exists',
                'message' => 'Mobile number already exists for this Lead',
            ]);
        }

        $admin_id = $request->admin_id;

        // Get product name from tbl_product
        $product = DB::table('tbl_product')->where('id', $request->product_id)->first();
        $productName = $product ? $product->product_name : '';

        // Sanitize numeric fields
        $obligation = preg_replace('/[^0-9]/', '', $request->obligation);
        $pos = preg_replace('/[^0-9]/', '', $request->pos);

        $loan_amount = preg_replace('/[^0-9]/', '', $request->loan_amount);
        $salary = preg_replace('/[^0-9]/', '', $request->salary);
        $yearly_bonus = preg_replace('/[^0-9]/', '', $request->yearly_bonus);

        // Ensure single values are stored properly
        $data = [
            'status' => '1',
            'lead_login_status' => $productName, // Store product name instead of 'Lead'
            'lead_status' => 'NEW LEAD',
            'admin_id' => $admin_id,
            'product_id' => $request->product_id,
            'campaign_id' => $request->campaign_id,
            'data_code' => $request->data_code,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'alternate_mobile' => $request->alternate_mobile,
            'pincode' => $request->pincode,
            'product_need_id' => $request->product_need_id,
            'casetype_id' => $request->casetype_id,
            'loan_amount' => $loan_amount,
            'tenure' => $request->tenure,
            'year' => $request->year,
            'process' => $request->process,
            'date' => $request->date,
            'salary' => $salary,
            'yearly_bonus' => $yearly_bonus,
            'company_name' => $request->company_name,
            'company_type' => $request->company_type,
            'company_category_id' => $request->company_category_id,
            'obligation' => $obligation,
            'pos' => $pos,
            'cibil_score' => $request->cibil_score,
            'date' => $this->date
        ];
        // Rest of the code remains the same
        $leadId = DB::table('tbl_lead')->insertGetId($data);

        $leadIdWithPrefix = 'PL' . $leadId;
        DB::table('tbl_lead')->where('id', $leadId)->update(['leadid' => $leadIdWithPrefix]);

        DB::table('tbl_lead_status')->insert([
            'admin_id' => $admin_id,
            'lead_id' => $leadId,
            'lead_status' => 'NEW LEAD',
            'date' => $this->date,
        ]);

        if (!empty($request->product_idd) && is_array($request->product_idd)) {
            $obligationData = [];

            foreach ($request->product_idd as $index => $productId) {
                $obligationData[] = [
                    'admin_id' => $admin_id,
                    'lead_id' => $leadId,
                    'product_id' => $productId,
                    'bank_id' => $request->bank_id[$index] ?? null,
                    'total_loan_amount' => preg_replace('/[^0-9]/', '', $request->total_loan_amount[$index] ?? null),
                    'bt_pos' => preg_replace('/[^0-9]/', '', $request->bt_pos[$index] ?? null),
                    'bt_emi' => preg_replace('/[^0-9]/', '', $request->bt_emi[$index] ?? null),
                    'bt_obligation' => $request->bt_obligation[$index] ?? null,
                    'date' => $this->date,
                ];
            }

            DB::table('tbl_obligation')->insert($obligationData);
        }

        return response()->json([
            'success' => 'success',
        ]);
    }





    ### Check Lead Already Exist ###
    public function checkLeadOLD(Request $request)
    {
        $admin_id = $request->input('admin_id');
        $mobile = $request->input('mobile');
        $productId = $request->input('product_id');

        $existingLead = DB::table('tbl_lead')
            ->where('admin_id', $admin_id)
            ->where('mobile', $mobile)
            ->orWhere('alternate_mobile', $mobile)
            ->where('product_id', $productId)
            ->first();

        if ($existingLead) {
            return response()->json([
                'success' => 'exists',
                'existing_lead' => $existingLead,
            ]);
        } else {
            return response()->json(['success' => 'not_found']);
        }
    }

    public function checkLead1(Request $request)
    {
        $admin_id = $request->input('admin_id');
        $mobile = $request->input('mobile');
        $productId = $request->input('product_id');

        $existingLead = DB::table('tbl_lead')
            ->where('admin_id', $admin_id)
            ->where('mobile', $mobile)
            ->orWhere('alternate_mobile', $mobile)
            ->where('product_id', $productId)
            ->first();

        if ($existingLead) {
            return response()->json([
                'success' => 'exists',
                'existing_lead' => $existingLead,
                'lead_status' => $existingLead->lead_status,  // addon key
                'date' => $existingLead->date                //  addon key 
            ]);
        } else {
            return response()->json(['success' => 'not_found']);
        }
    }

    // isko krna hai abhi 
    // public function checkLead(Request $request)
    // {
    //     $admin_id  = $request->input('admin_id');
    //     $mobile    = $request->input('mobile');
    //     $productId = $request->input('product_id');

    //     $existingLead = DB::table('tbl_lead')
    //         ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_lead.product_id')
    //         ->leftJoin('tbl_reassignment_lead', 'tbl_reassignment_lead.lead_id', '=', 'tbl_lead.id') // Join with tbl_reassignment_lead
    //         ->where('tbl_lead.admin_id', $admin_id)
    //         ->where(function ($query) use ($mobile) {
    //             $query->where('tbl_lead.mobile', $mobile)
    //                 ->orWhere('tbl_lead.alternate_mobile', $mobile);
    //         })
    //         ->where('tbl_lead.product_id', $productId)
    //         ->select('tbl_lead.*', 'tbl_product.product_name', 'tbl_reassignment_lead.lead_request_status') // Selecting lead_request_status
    //         ->first();

    //     if ($existingLead) {
    //         $kolkataDateTime = \Carbon\Carbon::now('Asia/Kolkata')->format('Y-m-d h:i A');

    //         $response = [
    //             'success' => 'exists',
    //             'existing_lead' => $existingLead,
    //             'lead_status' => $existingLead->lead_status,
    //             'date' => $existingLead->date, // Lead date from database
    //             'remaining_day' => 0,  // Default value
    //             'morethan_15days' => false,
    //             'lead_request_status' => $existingLead->lead_request_status  // lead_request_status
    //         ];

    //         // Check if 'date' exists and is valid
    //         if (!empty($existingLead->date)) {
    //             // Convert both dates to Carbon objects
    //             $leadDate = \Carbon\Carbon::createFromFormat('Y-m-d h:i A', $existingLead->date, 'Asia/Kolkata');
    //             $currentDate = \Carbon\Carbon::createFromFormat('Y-m-d h:i A', $kolkataDateTime, 'Asia/Kolkata');
    //             // Calculate the absolute difference in days
    //             $remainingDays = $currentDate->diffInDays($leadDate, false);
    //             $remainingDays = abs($remainingDays); 

    //             $response['remaining_day'] = $remainingDays;
    //             $response['morethan_15days'] = ($remainingDays > 15);
    //         }
    //         return response()->json($response);
    //     } else {
    //         return response()->json(['success' => 'not_found']);
    //     }
    // }


    public function checkLead(Request $request)
    {
        $admin_id = $request->input('admin_id');
        $mobile = $request->input('mobile');
        $productId = $request->input('product_id');

        $existingLead = DB::table('tbl_lead')
            ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_lead.product_id')
            ->leftJoin('tbl_reassignment_lead', 'tbl_reassignment_lead.lead_id', '=', 'tbl_lead.id')
            // ->where('tbl_lead.admin_id', $admin_id)
            ->where(function ($query) use ($mobile) {
                $query->where('tbl_lead.mobile', $mobile)
                    ->orWhere('tbl_lead.alternate_mobile', $mobile);
            })
            ->where('tbl_lead.product_id', $productId)
            ->select('tbl_lead.*', 'tbl_product.product_name', 'tbl_reassignment_lead.lead_request_status')
            ->first();
        // dd($existingLead);

        if ($existingLead) {
            $kolkataDateTime = \Carbon\Carbon::now('Asia/Kolkata')->format('Y-m-d h:i A');

            $lostlead = [
                'NOT INTERESTED',
                'FAKE LEAD',
                'LOST LEAD-SELF EMPLOYED',
                'LOST LEAD-OVERLEVRAGED',
                'LOST LEAD-CIBIL LOW',
                'LOST LEAD-BOUNCING IN LATEST 3 MONTHS',
                'LOST LEAD-REGULAR BOUNCING',
                'LOST LEAD-ALREADY DISBURSED BY OTHER',
                'LOST LEAD-OTHER REASON'
            ];

            $response = [
                'success' => 'exists',
                'existing_lead' => $existingLead,
                'lead_status' => $existingLead->lead_status,
                'date' => $existingLead->date,
                'remaining_day' => 0,
                'morethan_15days' => false,
                'lead_request_status' => $existingLead->lead_request_status
            ];

            // Agar lead_status $lostlead array mein hai toh `lostleads` key add karo
            if (in_array($existingLead->lead_status, $lostlead)) {
                $response['lostleads'] = $existingLead->lead_status;
            }
            if (!empty($existingLead->date)) {
                $leadDate = \Carbon\Carbon::createFromFormat('Y-m-d h:i A', $existingLead->date, 'Asia/Kolkata');
                $currentDate = \Carbon\Carbon::createFromFormat('Y-m-d h:i A', $kolkataDateTime, 'Asia/Kolkata');
                $remainingDays = abs($currentDate->diffInDays($leadDate, false));
                $response['remaining_day'] = $remainingDays;
                $response['morethan_15days'] = ($remainingDays > 15);
            }
            return response()->json($response);
        } else {
            return response()->json(['success' => 'not_found']);
        }
    }




    // Reassign Lead Request Here 
    public function GenerateReassignmentRequest(Request $request)
    {
        $exists = DB::table('tbl_reassignment_lead')
            ->where('lead_id', $request->lead_id)
            ->where('sender_id', $request->sender_id)
            ->exists();
        if ($exists) {
            return response()->json(['success' => 'exist', 'message' => 'Reassignment request already Send You']);
        }
        $data = [
            'lead_id' => $request->lead_id,
            'sender_id' => $request->sender_id,
            'admin_id' => '1', // Static Admin ID
            'lead_request_status' => 'Pending', // Default Status
            'date' => $this->date
        ];
        $insert = DB::table('tbl_reassignment_lead')->insert($data);
        if ($insert) {
            return response()->json(['success' => 'success', 'message' => 'Reassignment request added successfully']);
        } else {
            return response()->json(['success' => 'error', 'message' => 'Something went wrong. Please try again']);
        }
    }

    // Assign to me 
    public function AssignToMe(Request $request)
    {
        $data = [
            'id' => $request->lead_id,
            'admin_id' => $request->admin_id,
            'data_code' => $request->data_code,
            'campaign_id' => $request->campaign_id,
            'date' => $this->date
        ];
        // dd($data);
        // ✅ Update the lead in tbl_lead
        $update = DB::table('tbl_lead')
            ->where('id', $request->lead_id)
            ->update($data);

        if ($update) {
            return response()->json(['success' => 'success', 'message' => 'Lead Assign To Me successfully']);
        } else {
            return response()->json(['success' => 'error', 'message' => 'Something Went Wrong']);
        }
    }


    // Accept Or Reject Request by admin 
    public function AcceptRejectRequest(Request $request)
    {
        // Check if the lead exists in tbl_lead
        $leadExists = DB::table('tbl_lead')
            ->where('id', $request->lead_id)
            ->exists();

        $updateReassignment = DB::table('tbl_reassignment_lead')
            ->where('lead_id', $request->lead_id)
            ->where('sender_id', $request->sender_id)
            ->update([
                'lead_request_status' => $request->action // Accept or Reject
            ]);

        // ✅ Agar "Accept" hai tabhi `tbl_lead` update hoga 
        if ($request->action === "Accept") {
            $updateLead = DB::table('tbl_lead')
                ->where('id', $request->lead_id)
                ->update([
                    'admin_id' => $request->sender_id,
                    'date' => $this->date
                ]);
        }

        // ✅ Response based on updates
        if ($updateReassignment) {
            return response()->json(['success' => 'success', 'message' => 'Lead ' . $request->action . ' successfully']);
        } else {
            return response()->json(['success' => 'error', 'message' => 'Something went wrong']);
        }
    }










    // Obligation Update Here
    // public function updateobligation(Request $request)
    // {
    //     $obligations = $request->input('obligations');
    //     foreach ($obligations as $obligation) {
    //         DB::table('tbl_obligation')
    //             ->where('id', $obligation['obligation_id'])
    //             ->update([
    //                 'admin_id' => $obligation['admin_id'],
    //                 'lead_id' => $obligation['lead_id'],
    //                 'product_id' => $obligation['product_id'],
    //                 'bank_id' => $obligation['bank_id'],
    //                 'total_loan_amount' => $obligation['total_loan_amount'],
    //                 'bt_pos' => $obligation['bt_pos'],
    //                 'bt_emi' => $obligation['bt_emi'],
    //                 'bt_obligation' => $obligation['bt_obligation']
    //             ]);
    //     }
    //     return response()->json(['message' => 'Obligations updated successfully!']);
    // }

    // public function updateobligation(Request $request)
    // {
    //     $obligations = $request->input('obligations');
    //     $leadTotals = [];

    //     foreach ($obligations as $obligation)
    //     {
    //         $oldObligation = DB::table('tbl_obligation')
    //             ->where('id', $obligation['obligation_id'])
    //             ->first();

    //         if (!$oldObligation) {
    //             continue; // Skip if not found
    //         }

    //         // Calculate differences (ensure numeric values)
    //         $btPosDifference = ((int) $obligation['bt_pos']) - ((int) $oldObligation->bt_pos);
    //         $btEmiDifference = ((int) $obligation['bt_emi']) - ((int) $oldObligation->bt_emi);

    //         // Update `tbl_obligation`
    //         DB::table('tbl_obligation')
    //             ->where('id', $obligation['obligation_id'])
    //             ->update([
    //                 'admin_id' => $obligation['admin_id'],
    //                 'lead_id' => $obligation['lead_id'],
    //                 'product_id' => $obligation['product_id'],
    //                 'bank_id' => $obligation['bank_id'],
    //                 'total_loan_amount' => $obligation['total_loan_amount'],
    //                 'bt_pos' => $obligation['bt_pos'],
    //                 'bt_emi' => $obligation['bt_emi'],
    //                 'bt_obligation' => $obligation['bt_obligation']
    //             ]);

    //         // Store lead-wise total changes
    //         if (!isset($leadTotals[$obligation['lead_id']])) {
    //             $leadTotals[$obligation['lead_id']] = [
    //                 'bt_pos_change' => 0,
    //                 'bt_emi_change' => 0,
    //             ];
    //         }

    //         $leadTotals[$obligation['lead_id']]['bt_pos_change'] += $btPosDifference;
    //         $leadTotals[$obligation['lead_id']]['bt_emi_change'] += $btEmiDifference;
    //     }

    //     // Update `tbl_lead` dynamically if there's a change
    //     foreach ($leadTotals as $lead_id => $totals) {
    //         if ($totals['bt_pos_change'] != 0 || $totals['bt_emi_change'] != 0) {
    //             DB::table('tbl_lead')
    //                 ->where('id', $lead_id)
    //                 ->update([
    //                     'pos' => DB::raw("pos + {$totals['bt_pos_change']}"),
    //                     'obligation' => DB::raw("obligation + {$totals['bt_emi_change']}")
    //                 ]);
    //         }
    //     }

    //     return response()->json(['message' => 'Obligations updated successfully!']);
    // }

    public function updateobligation(Request $request)
    {
        $obligations = $request->input('obligations');
        $leadTotals = [];

        foreach ($obligations as $obligation) {
            $oldObligation = DB::table('tbl_obligation')
                ->where('id', $obligation['obligation_id'])
                ->first();

            if (!$oldObligation) {
                continue; // Skip if not found
            }

            // Calculate differences (ensure numeric values)
            $btPosDifference = ((int) $obligation['bt_pos']) - ((int) $oldObligation->bt_pos);
            $btEmiDifference = ((int) $obligation['bt_emi']) - ((int) $oldObligation->bt_emi);

            // Handle changes in bt_obligation
            if ($oldObligation->bt_obligation != $obligation['bt_obligation']) {
                // If the bt_obligation type changes, adjust the lead totals accordingly
                if ($oldObligation->bt_obligation == 'BT') {
                    // If it was BT and now it's Obligation, subtract from POS and add to Obligation
                    $btPosDifference -= $oldObligation->bt_pos;
                    $btEmiDifference += $oldObligation->bt_emi;
                } else {
                    // If it was Obligation and now it's BT, subtract from Obligation and add to POS
                    $btPosDifference += $oldObligation->bt_pos;
                    $btEmiDifference -= $oldObligation->bt_emi;
                }
            }

            // Update `tbl_obligation`
            DB::table('tbl_obligation')
                ->where('id', $obligation['obligation_id'])
                ->update([
                    'admin_id' => $obligation['admin_id'],
                    'lead_id' => $obligation['lead_id'],
                    'product_id' => $obligation['product_id'],
                    'bank_id' => $obligation['bank_id'],
                    'total_loan_amount' => $obligation['total_loan_amount'],
                    'bt_pos' => $obligation['bt_pos'],
                    'bt_emi' => $obligation['bt_emi'],
                    'bt_obligation' => $obligation['bt_obligation'],
                ]);

            // Store lead-wise total changes
            if (!isset($leadTotals[$obligation['lead_id']])) {
                $leadTotals[$obligation['lead_id']] = [
                    'bt_pos_change' => 0,
                    'bt_emi_change' => 0,
                ];
            }

            $leadTotals[$obligation['lead_id']]['bt_pos_change'] += $btPosDifference;
            $leadTotals[$obligation['lead_id']]['bt_emi_change'] += $btEmiDifference;
        }

        // Update `tbl_lead` dynamically if there's a change
        foreach ($leadTotals as $lead_id => $totals) {
            if ($totals['bt_pos_change'] != 0 || $totals['bt_emi_change'] != 0) {
                DB::table('tbl_lead')
                    ->where('id', $lead_id)
                    ->update([
                        'pos' => DB::raw("pos + {$totals['bt_pos_change']}"),
                        'obligation' => DB::raw("obligation + {$totals['bt_emi_change']}"),
                    ]);
            }
        }

        return response()->json(['message' => 'Obligations updated successfully!']);
    }

    ### For Pagination show_Pl_Od_LeadAPI ###
    public function show_Pl_Od_LeadAPIOLD(Request $request)
    {
        $search = $request->search;      // for searching
        $from_date = $request->from_date;   //  for fromdate
        $to_date = $request->to_date;     //  for todate
        $lead_status = $request->lead_status; //  for leadstatus

        $query = DB::table('tbl_lead')
            ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_lead.product_id')
            ->leftJoin('tbl_campaign', 'tbl_campaign.id', '=', 'tbl_lead.campaign_id')
            ->leftJoin('tbl_product_need', 'tbl_product_need.id', '=', 'tbl_lead.product_need_id')
            ->select('tbl_lead.*', 'tbl_product.product_name', 'tbl_campaign.campaign_name', 'tbl_product_need.product_need')
            ->orderBy('tbl_lead.id', 'desc');

        // Apply filters fields here for searching
        if (!empty($search) && ($search != 'undefined')) {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_lead.name', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.mobile', 'like', '%' . $search . '%');
            });
        }
        // Apply from_date to_date filter
        if ($from_date && $to_date) {
            if ($from_date == $to_date) {
                // If both dates are the same, fetch records for that single date
                $query->whereDate(DB::raw('DATE(tbl_lead.date)'), '=', $from_date);
            } else {
                // If dates are different, apply range filter (ignore time)
                $query->whereBetween(DB::raw('DATE(tbl_lead.date)'), [$from_date, $to_date]);
            }
        } elseif ($from_date) {
            $query->whereDate(DB::raw('DATE(tbl_lead.date)'), '>=', $from_date);
        } elseif ($to_date) {
            $query->whereDate(DB::raw('DATE(tbl_lead.date)'), '<=', $to_date);
        }
        // Apply lead_status filter
        if ($lead_status) {
            $query->where('tbl_lead.lead_status', $lead_status);
        }
        // Show only 'Lead' status leads
        $query->where('tbl_lead.lead_login_status', 'Lead');
        // Apply pagination limit here
        $leads = $query->paginate(8);
        // return response
        return response()->json([
            'data' => $leads->items(),
            'current_page' => $leads->currentPage(),
            'last_page' => $leads->lastPage(),
            'per_page' => $leads->perPage(),
            'total' => $leads->total(),
            'success' => 'success',
        ]);
    }

    // show pl od login here  OLD
    public function show_Pl_Od_LoginOLD(Request $request)
    {
        $query = DB::table('tbl_lead')
            ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_lead.product_id')
            ->leftJoin('tbl_campaign', 'tbl_campaign.id', '=', 'tbl_lead.campaign_id')
            ->leftJoin('tbl_product_need', 'tbl_product_need.id', '=', 'tbl_lead.product_need_id')
            ->select('tbl_lead.*', 'tbl_product.product_name', 'tbl_campaign.campaign_name', 'tbl_product_need.product_need')
            ->orderBy('tbl_lead.id', 'desc');

        // Check if fromdate and todate are provided in the request
        if ($request->has('fromdate') && $request->has('todate')) {
            $fromdate = $request->input('fromdate');
            $todate = $request->input('todate');
            // Filter using both 'date' and 'disbursment_date'
            $query->where(function ($q) use ($fromdate, $todate) {
                $q->whereRaw('DATE(tbl_lead.date) BETWEEN ? AND ?', [$fromdate, $todate])
                    ->orWhereRaw('DATE(tbl_lead.disbursment_date) BETWEEN ? AND ?', [$fromdate, $todate]);
            });
        }
        // Add condition to check for lead_login_status being 'Login'
        $query->where('tbl_lead.lead_login_status', 'Login');
        $data["fromdatenew"] = $request->input('fromdate', '');
        $data["enddatenew"] = $request->input('todate', '');
        $data['pl_od_login'] = $query->get();
        return view('Admin.pages.show_pl_od_login', $data);
    }

    ### show_pl_od_leads ###
    public function show_Pl_Od_Lead(Request $request)
    {
        return view('Admin.pages.show_pl_od_leads');
    }

    // today 2025-03-21
    public function show_Pl_Od_LeadAPINEW(Request $request)
    {
        $search = $request->search;
        $lead_status = $request->lead_status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $activity_from_date = $request->activity_from_date;

        $valid_statuses = [
            'NEW LEAD',
            'IN PROGRESS',
            'FOLLOW UP',
            'CALLBACK',
            'IMPORTANT LEAD',
            'LONG FOLLOW UP',
            'IN DOCUMENTATION',
            'FILE COMPLETED',
        ];

        // Base Query
        $query = DB::table('tbl_lead')
            ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_lead.product_id')
            ->leftJoin('tbl_campaign', 'tbl_campaign.id', '=', 'tbl_lead.campaign_id')
            ->leftJoin('tbl_product_need', 'tbl_product_need.id', '=', 'tbl_lead.product_need_id')
            ->leftJoin('tbl_reassignment_lead', 'tbl_reassignment_lead.lead_id', '=', 'tbl_lead.id')
            ->select(
                'tbl_lead.*',
                'tbl_product.product_name',
                'tbl_campaign.campaign_name',
                'tbl_product_need.product_need'
            )
            ->where('tbl_lead.lead_login_status', 'PL & OD LEADS') // Show only 'PL & OD LEADS'
            ->where(function ($query) {
                $query->where('tbl_reassignment_lead.lead_request_status', 'Accept')
                    ->orWhereNull('tbl_reassignment_lead.lead_id'); // Include leads not reassigned
            })
            ->orderBy('tbl_lead.id', 'desc');

        // Search Filter
        if (!empty($search) && $search != 'undefined') {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_lead.name', 'like', "%$search%")
                    ->orWhere('tbl_lead.mobile', 'like', "%$search%")
                    ->orWhere('tbl_product.product_name', 'like', "%$search%")
                    ->orWhere('tbl_lead.lead_status', 'like', "%$search%")
                    ->orWhere('tbl_lead.pincode', 'like', "%$search%")
                    ->orWhere('tbl_lead.company_name', 'like', "%$search%")
                    ->orWhere('tbl_lead.loan_amount', 'like', "%$search%");
            });
        }

        // Lead Status Filter
        if (!empty($lead_status)) {
            $query->where('tbl_lead.lead_status', $lead_status);
        }

        // Date Filters
        if (!empty($from_date) && !empty($to_date)) {
            if ($from_date == $to_date) {
                $query->whereDate('tbl_lead.date', '=', $from_date);
            } else {
                $query->whereBetween('tbl_lead.date', [$from_date, date('Y-m-d', strtotime($to_date . ' +1 day'))]);
            }
        } elseif (!empty($from_date)) {
            $query->whereDate('tbl_lead.date', '>=', $from_date);
        } elseif (!empty($to_date)) {
            $query->whereDate('tbl_lead.date', '<=', $to_date);
        }

        // Activity Date Filter (Exclude Leads That Have Activity in Given Range)
        if (!empty($activity_from_date)) {
            $current_date = date('Y-m-d');
            $activity_from_date = date('Y-m-d', strtotime($activity_from_date));

            // Fetch all unique lead IDs that have any activity in the given date range
            $leads_with_activity = DB::table('tbl_lead_status')
                ->whereBetween(DB::raw("STR_TO_DATE(date, '%Y-%m-%d %h:%i %p')"), [$activity_from_date, $current_date])
                ->distinct()
                ->pluck('lead_id')
                ->toArray();
            // Exclude all leads that had any activity in the given range
            if (!empty($leads_with_activity)) {
                $query->whereNotIn('tbl_lead.id', $leads_with_activity);
            }
            // Lead Statuses Filter
            if (!empty($valid_statuses)) {
                $query->whereIn('tbl_lead.lead_status', $valid_statuses);
            }
        }

        // Pagination
        $leads = $query->paginate(8);

        // Return JSON Response
        return response()->json([
            'data' => $leads->items(),
            'current_page' => $leads->currentPage(),
            'last_page' => $leads->lastPage(),
            'per_page' => $leads->perPage(),
            'total' => $leads->total(),
            'success' => 'success',
        ]);
    }


    public function show_Pl_Od_LeadAPINEW2(Request $request)
    {
        // get session admin login 
        $adminSession = collect(session()->get('admin_login'))->first();
        $admin_team = $adminSession->team ?? '';

        $search = $request->search;
        $lead_status = $request->lead_status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $activity_from_date = $request->activity_from_date;

        $valid_statuses = [
            'NEW LEAD',
            'IN PROGRESS',
            'FOLLOW UP',
            'CALLBACK',
            'IMPORTANT LEAD',
            'LONG FOLLOW UP',
            'IN DOCUMENTATION',
            'FILE COMPLETED',
        ];

        // Base Query
        $query = DB::table('tbl_lead')
            ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_lead.product_id')
            ->leftJoin('tbl_campaign', 'tbl_campaign.id', '=', 'tbl_lead.campaign_id')
            ->leftJoin('tbl_product_need', 'tbl_product_need.id', '=', 'tbl_lead.product_need_id')
            ->leftJoin('tbl_reassignment_lead', 'tbl_reassignment_lead.lead_id', '=', 'tbl_lead.id')
            ->select(
                'tbl_lead.*',
                'tbl_product.product_name',
                'tbl_campaign.campaign_name',
                'tbl_product_need.product_need'
            )
            ->where('tbl_lead.lead_login_status', 'PL & OD LEADS') // Show only 'PL & OD LEADS'
            ->where(function ($query) {
                $query->where('tbl_reassignment_lead.lead_request_status', 'Accept')
                    ->orWhereNull('tbl_reassignment_lead.lead_id'); // Include leads not reassigned
            })
            ->orderBy('tbl_lead.id', 'desc');

        // Search Filter
        if (!empty($search) && $search != 'undefined') {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_lead.name', 'like', "%$search%")
                    ->orWhere('tbl_lead.mobile', 'like', "%$search%")
                    ->orWhere('tbl_product.product_name', 'like', "%$search%")
                    ->orWhere('tbl_lead.lead_status', 'like', "%$search%")
                    ->orWhere('tbl_lead.pincode', 'like', "%$search%")
                    ->orWhere('tbl_lead.company_name', 'like', "%$search%")
                    ->orWhere('tbl_lead.loan_amount', 'like', "%$search%");
            });
        }

        // Lead Status Filter
        if (!empty($lead_status)) {
            $query->where('tbl_lead.lead_status', $lead_status);
        }

        // Date Filters
        if (!empty($from_date) && !empty($to_date)) {
            if ($from_date == $to_date) {
                $query->whereDate('tbl_lead.date', '=', $from_date);
            } else {
                $query->whereBetween('tbl_lead.date', [$from_date, date('Y-m-d', strtotime($to_date . ' +1 day'))]);
            }
        } elseif (!empty($from_date)) {
            $query->whereDate('tbl_lead.date', '>=', $from_date);
        } elseif (!empty($to_date)) {
            $query->whereDate('tbl_lead.date', '<=', $to_date);
        }

        // Activity Date Filter (Check only Y-m-d, exclude leads with activity in range)
        if (!empty($activity_from_date)) {
            $current_date = date('Y-m-d'); // Current date in 'Y-m-d' format
            $activity_from_date = date('Y-m-d', strtotime($activity_from_date)); // Selected date in 'Y-m-d' format

            // Fetch all unique lead IDs that have activity between $activity_from_date and now (date only)
            $leads_with_activity = DB::table('tbl_lead_status')
                ->whereBetween(
                    DB::raw("DATE(STR_TO_DATE(date, '%Y-%m-%d %h:%i %p'))"),
                    [$activity_from_date, $current_date]
                )
                ->distinct()
                ->pluck('lead_id')
                ->toArray();

            // Exclude leads that have activity in the given range
            if (!empty($leads_with_activity)) {
                $query->whereNotIn('tbl_lead.id', $leads_with_activity);
            }

            // Apply lead status filter if valid statuses are provided
            if (!empty($valid_statuses)) {
                $query->whereIn('tbl_lead.lead_status', $valid_statuses);
            }
        }

        // Pagination
        $leads = $query->paginate(8);

        // Return JSON Response
        return response()->json([
            'data' => $leads->items(),
            'current_page' => $leads->currentPage(),
            'last_page' => $leads->lastPage(),
            'per_page' => $leads->perPage(),
            'total' => $leads->total(),
            'success' => 'success',
        ]);
    }


    public function show_Pl_Od_LeadAPI(Request $request)
    {
        // Get session admin login 
        $adminSession = collect(session()->get('admin_login'))->first();
        $team_name = $adminSession->team ?? null;
        $admin_id = $adminSession->id ?? null;
        $admin_role = strtolower($adminSession->role ?? '');

        $search = $request->search;
        $lead_status = $request->lead_status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $activity_from_date = $request->activity_from_date;

        $valid_statuses = [
            'NEW LEAD',
            'IN PROGRESS',
            'FOLLOW UP',
            'CALLBACK',
            'IMPORTANT LEAD',
            'LONG FOLLOW UP',
            'IN DOCUMENTATION',
            'FILE COMPLETED',
        ];

        // Start query to fetch leads
        $query = DB::table('tbl_lead')
            ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_lead.product_id')
            ->leftJoin('tbl_campaign', 'tbl_campaign.id', '=', 'tbl_lead.campaign_id')
            ->leftJoin('tbl_product_need', 'tbl_product_need.id', '=', 'tbl_lead.product_need_id')
            ->leftJoin('admin AS assigned_admin', 'assigned_admin.id', '=', 'tbl_lead.admin_id') // Assigned Admin
            ->leftJoin('admin AS manager_admin', 'manager_admin.id', '=', 'assigned_admin.manager') // Manager Name
            ->leftJoin('admin AS team_leader_admin', 'team_leader_admin.id', '=', 'assigned_admin.team_leader') // Team Leader Name
            ->select(
                'tbl_lead.*',
                'tbl_product.product_name',
                'tbl_campaign.campaign_name',
                'tbl_product_need.product_need',
                'assigned_admin.name AS admin_name', // Assigned Admin Name
                'assigned_admin.role AS admin_role',
                'manager_admin.name AS manager_name', // Manager Name
                'team_leader_admin.name AS team_leader_name' // Team Leader Name
            )
            // ->where('assigned_admin.department', 'Sales')
            ->where('tbl_lead.lead_login_status', 'PL & OD LEADS');

        // **Case: If logged-in user is a Manager**
        if ($admin_role === 'manager' && !empty($admin_id)) {
            $query->where(function ($q) use ($admin_id) {
                $q->where('tbl_lead.admin_id', $admin_id); // Manager's own leads

                // Fetch all TLs where this Manager is assigned as their Manager
                $tl_ids = DB::table('admin')
                    ->where('manager', $admin_id)
                    ->where('role', 'TL') // Only TL roles
                    ->pluck('id')
                    ->toArray();

                if (!empty($tl_ids)) {
                    // Get leads where TL's ID is in admin_id
                    $q->orWhereIn('tbl_lead.admin_id', $tl_ids);

                    // Fetch all Agents under these TLs
                    $agent_ids = DB::table('admin')
                        ->whereIn('team_leader', $tl_ids)
                        ->where('role', 'Agent') // Only Agent roles
                        ->pluck('id')
                        ->toArray();

                    if (!empty($agent_ids)) {
                        // Get leads where Agent's ID is in admin_id
                        $q->orWhereIn('tbl_lead.admin_id', $agent_ids);
                    }
                }
            });
        }

        // **Case: If logged-in user is a TL**
        elseif ($admin_role === 'tl' && !empty($admin_id)) {
            $query->where(function ($q) use ($admin_id) {
                $q->where('tbl_lead.admin_id', $admin_id); // TL's own leads

                // Fetch all Agents where this TL is assigned as their Team Leader
                $agent_ids = DB::table('admin')
                    ->where('team_leader', $admin_id)
                    ->where('role', 'Agent') // Only Agent roles
                    ->pluck('id')
                    ->toArray();

                if (!empty($agent_ids)) {
                    // Get leads where Agent's ID is in admin_id
                    $q->orWhereIn('tbl_lead.admin_id', $agent_ids);
                }
            });
        }

        // **Case: If logged-in user is an Agent**
        elseif ($admin_role === 'agent' && !empty($admin_id)) {
            $query->where('tbl_lead.admin_id', $admin_id); // Agent's own leads
        }

        // Order by latest leads
        $query->orderBy('tbl_lead.id', 'desc');

        // **Search Filter**
        if (!empty($search) && $search != 'undefined') {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_lead.name', 'like', "%$search%")
                    ->orWhere('tbl_lead.mobile', 'like', "%$search%")
                    ->orWhere('tbl_product.product_name', 'like', "%$search%")
                    ->orWhere('tbl_lead.lead_status', 'like', "%$search%")
                    ->orWhere('tbl_lead.pincode', 'like', "%$search%")
                    ->orWhere('tbl_lead.company_name', 'like', "%$search%")
                    ->orWhere('tbl_lead.loan_amount', 'like', "%$search%");
            });
        }

        // **Lead Status Filter**
        if (!empty($lead_status)) {
            $query->where('tbl_lead.lead_status', $lead_status);
        }

        // **Date Filters**
        if (!empty($from_date) && !empty($to_date)) {
            if ($from_date == $to_date) {
                $query->whereDate('tbl_lead.date', '=', $from_date);
            } else {
                $query->whereBetween('tbl_lead.date', [$from_date, date('Y-m-d', strtotime($to_date . ' +1 day'))]);
            }
        } elseif (!empty($from_date)) {
            $query->whereDate('tbl_lead.date', '>=', $from_date);
        } elseif (!empty($to_date)) {
            $query->whereDate('tbl_lead.date', '<=', $to_date);
        }


        // Activity Date Filter (Check only Y-m-d, exclude leads with activity in range)
        if (!empty($activity_from_date)) {
            $current_date = date('Y-m-d'); // Current date in 'Y-m-d' format
            $activity_from_date = date('Y-m-d', strtotime($activity_from_date)); // Selected date in 'Y-m-d' format

            // Fetch all unique lead IDs that have activity between $activity_from_date and now (date only)
            $leads_with_activity = DB::table('tbl_lead_status')
                ->whereBetween(
                    DB::raw("DATE(STR_TO_DATE(date, '%Y-%m-%d %h:%i %p'))"),
                    [$activity_from_date, $current_date]
                )
                ->distinct()
                ->pluck('lead_id')
                ->toArray();
            // Exclude leads that have activity in the given range
            if (!empty($leads_with_activity)) {
                $query->whereNotIn('tbl_lead.id', $leads_with_activity);
            }
        }
        // Apply lead status filter if valid statuses are provided
        // if (!empty($valid_statuses)) {
        //     $query->whereIn('tbl_lead.lead_status', $valid_statuses);
        // }

        // **Get Leads with Pagination**
        $leads = $query->paginate(8);

        // **Return JSON Response**
        return response()->json([
            'data' => $leads->items(),
            'current_page' => $leads->currentPage(),
            'last_page' => $leads->lastPage(),
            'per_page' => $leads->perPage(),
            'total' => $leads->total(),
            'team_name' => $team_name,
            'role' => $admin_role,
            'success' => 'success',
        ]);
    }






    // Pl Od Login
    public function show_Pl_Od_Login(Request $request)
    {
        return view('Admin.pages.show_pl_od_login');
    }
    public function show_Pl_Od_LoginAPI000(Request $request)
    {
        $search = $request->search;             // for searching
        $from_date = $request->from_date;          // for fromdate
        $to_date = $request->to_date;            // for todate
        $login_status = $request->login_status;       // for leadstatus
        $activity_from_date = $request->activity_from_date; // for activity_from_date

        $valid_statuses = [
            'NEW FILE',
            'SENT TO BANK',
            'UNDERWRITING',
            'REELOK',
            'REELOK-HIGH PRIORITY',
            'APPROVED',
            'DISBURSED',
        ];

        // Base query
        $query = DB::table('tbl_lead')
            ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_lead.product_id')
            ->leftJoin('tbl_campaign', 'tbl_campaign.id', '=', 'tbl_lead.campaign_id')
            ->leftJoin('tbl_product_need', 'tbl_product_need.id', '=', 'tbl_lead.product_need_id')
            ->select('tbl_lead.*', 'tbl_product.product_name', 'tbl_campaign.campaign_name', 'tbl_product_need.product_need')
            // ->where('tbl_lead.lead_login_status', 'Login') // Show only 'Login' status leads
            ->where('tbl_lead.lead_login_status', 'PL & OD LOGIN') // Show only 'Login' status leads
            ->orderBy('tbl_lead.move_login_date', 'desc');

        // Apply search filter (works independently)
        if (!empty($search) && ($search != 'undefined')) {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_lead.name', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.mobile', 'like', '%' . $search . '%');
            });
        }

        // Apply login_status filter (if provided)
        if ($login_status) {
            $query->where('tbl_lead.login_status', $login_status);
        }

        // Apply date filters (if provided)
        if ($from_date && $to_date) {
            if ($from_date == $to_date) {
                // Single date ke liye records fetch kare
                $query->where(function ($q) use ($from_date) {
                    $q->whereDate('tbl_lead.date', '=', $from_date)
                        ->orWhereDate('tbl_lead.disbursment_date', '=', $from_date);
                });
            } else {
                // `to_date` ko ek din badhaya gaya taaki poora din consider ho
                $adjusted_to_date = date('Y-m-d', strtotime($to_date . ' +1 day'));
                $query->where(function ($q) use ($from_date, $adjusted_to_date) {
                    $q->whereBetween('tbl_lead.date', [$from_date, $adjusted_to_date])
                        ->orWhereBetween('tbl_lead.disbursment_date', [$from_date, $adjusted_to_date]);
                });
            }
        } elseif ($from_date) {
            $query->where(function ($q) use ($from_date) {
                $q->whereDate('tbl_lead.date', '>=', $from_date)
                    ->orWhereDate('tbl_lead.disbursment_date', '>=', $from_date);
            });
        } elseif ($to_date) {
            $query->where(function ($q) use ($to_date) {
                $q->whereDate('tbl_lead.date', '<=', $to_date)
                    ->orWhereDate('tbl_lead.disbursment_date', '<=', $to_date);
            });
        }

        // Apply Activity Date Filter only for specific lead statuses
        // if ($activity_from_date) {
        //     $current_date = date('Y-m-d h:i A');
        //     $excluded_leads = DB::table('tbl_lead_status')
        //         ->whereBetween('date', [$activity_from_date, $current_date])
        //         ->pluck('lead_id')
        //         ->toArray();
        //     if (!empty($excluded_leads)) {
        //         $query->whereNotIn('tbl_lead.id', $excluded_leads);
        //     } else {
        //         $query->whereDate('tbl_lead.date', '<=', $current_date);
        //     }
        //    // lead_status show only 'NEW FILE', 'SENT TO BANK', 'UNDERWRITING', 'REELOK', 'REELOK-HIGH PRIORITY', 'APPROVED', 'DISBURSED'
        //     $query->whereIn('tbl_lead.login_status', $valid_statuses);
        // }

        if ($activity_from_date) {
            $current_date = date('Y-m-d h:i A');
            $activity_from_date = date('Y-m-d h:i A', strtotime($activity_from_date));
            $excluded_leads = DB::table('tbl_lead_status')
                ->whereBetween(DB::raw("STR_TO_DATE(date, '%Y-%m-%d %h:%i %p')"), [$activity_from_date, $current_date])
                ->pluck('lead_id')
                ->toArray();
            if (!empty($excluded_leads)) {
                $query->whereNotIn('tbl_lead.id', $excluded_leads);
            } else {
                $query->whereDate(DB::raw("STR_TO_DATE(tbl_lead.date, '%Y-%m-%d %h:%i %p')"), '<=', $current_date);
            }
            // lead_status show only 'NEW FILE', 'SENT TO BANK', 'UNDERWRITING', 'REELOK', 'REELOK-HIGH PRIORITY', 'APPROVED', 'DISBURSED'
            $query->whereIn('tbl_lead.login_status', $valid_statuses);
        }

        // Apply pagination
        $leads = $query->paginate(5);

        // Return response
        return response()->json([
            'data' => $leads->items(),
            'current_page' => $leads->currentPage(),
            'last_page' => $leads->lastPage(),
            'per_page' => $leads->perPage(),
            'total' => $leads->total(),
            'success' => 'success',
        ]);
    }

    public function show_Pl_Od_LoginAPI(Request $request)
    {
        // Get session admin login 
        $adminSession = collect(session()->get('admin_login'))->first();
        $team_name = $adminSession->team ?? null;
        $admin_id = $adminSession->id ?? null;
        $admin_role = strtolower($adminSession->role ?? '');

        // Request parameters
        $search = $request->search;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $login_status = $request->login_status;
        $activity_from_date = $request->activity_from_date;

        $valid_statuses = [
            'NEW FILE',
            'SENT TO BANK',
            'UNDERWRITING',
            'REELOK',
            'REELOK-HIGH PRIORITY',
            'APPROVED',
            'DISBURSED',
        ];

        // Base query
        $query = DB::table('tbl_lead')
            ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_lead.product_id')
            ->leftJoin('tbl_campaign', 'tbl_campaign.id', '=', 'tbl_lead.campaign_id')
            ->leftJoin('tbl_product_need', 'tbl_product_need.id', '=', 'tbl_lead.product_need_id')
            ->leftJoin('admin AS assigned_admin', 'assigned_admin.id', '=', 'tbl_lead.admin_id')
            ->leftJoin('admin AS manager_admin', 'manager_admin.id', '=', 'assigned_admin.manager')
            ->leftJoin('admin AS team_leader_admin', 'team_leader_admin.id', '=', 'assigned_admin.team_leader')
            ->select(
                'tbl_lead.*',
                'tbl_product.product_name',
                'tbl_campaign.campaign_name',
                'tbl_product_need.product_need',
                'assigned_admin.name AS admin_name',
                'assigned_admin.role AS admin_role',
                'manager_admin.name AS manager_name',
                'team_leader_admin.name AS team_leader_name'
            )
            ->where('tbl_lead.lead_login_status', 'PL & OD LOGIN');

        // Role-Based Filtering
        if ($admin_role === 'manager' && !empty($admin_id)) {
            $query->where(function ($q) use ($admin_id) {
                $q->where('tbl_lead.admin_id', $admin_id)
                    ->orWhereIn('tbl_lead.admin_id', function ($subQuery) use ($admin_id) {
                        // Fetch TLs under this Manager
                        $subQuery->select('id')
                            ->from('admin')
                            ->where('manager', $admin_id)
                            ->where('role', 'TL')
                            ->union(
                                DB::table('admin')
                                    ->select('admin.id')
                                    ->whereIn('team_leader', function ($tlQuery) use ($admin_id) {
                                    $tlQuery->select('id')
                                        ->from('admin')
                                        ->where('manager', $admin_id)
                                        ->where('role', 'TL');
                                })
                                    ->where('role', 'Agent')
                            );
                    });
            });
        } elseif ($admin_role === 'tl' && !empty($admin_id)) {
            $query->where(function ($q) use ($admin_id) {
                $q->where('tbl_lead.admin_id', $admin_id)
                    ->orWhereIn('tbl_lead.admin_id', function ($subQuery) use ($admin_id) {
                        $subQuery->select('id')
                            ->from('admin')
                            ->where('team_leader', $admin_id)
                            ->where('role', 'Agent');
                    });
            });
        } elseif ($admin_role === 'agent' && !empty($admin_id)) {
            $query->where('tbl_lead.admin_id', $admin_id);
        }

        $query->orderBy('tbl_lead.move_login_date', 'desc');

        // Search Filter
        if (!empty($search) && $search !== 'undefined') {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_lead.name', 'like', "%{$search}%")
                    ->orWhere('tbl_lead.mobile', 'like', "%{$search}%");
            });
        }

        // Login Status Filter
        if (!empty($login_status)) {
            $query->where('tbl_lead.login_status', $login_status);
        }

        // Date Filters
        if (!empty($from_date) && !empty($to_date)) {
            if ($from_date === $to_date) {
                $query->where(function ($q) use ($from_date) {
                    $q->whereDate('tbl_lead.date', '=', $from_date)
                        ->orWhereDate('tbl_lead.disbursment_date', '=', $from_date);
                });
            } else {
                $to_date_plus_one = date('Y-m-d', strtotime($to_date . ' +1 day'));
                $query->where(function ($q) use ($from_date, $to_date_plus_one) {
                    $q->whereBetween('tbl_lead.date', [$from_date, $to_date_plus_one])
                        ->orWhereBetween('tbl_lead.disbursment_date', [$from_date, $to_date_plus_one]);
                });
            }
        } elseif (!empty($from_date)) {
            $query->where(function ($q) use ($from_date) {
                $q->whereDate('tbl_lead.date', '>=', $from_date)
                    ->orWhereDate('tbl_lead.disbursment_date', '>=', $from_date);
            });
        } elseif (!empty($to_date)) {
            $query->where(function ($q) use ($to_date) {
                $q->whereDate('tbl_lead.date', '<=', $to_date)
                    ->orWhereDate('tbl_lead.disbursment_date', '<=', $to_date);
            });
        }

        // Activity Date Filter
        if (!empty($activity_from_date)) {
            $current_date = date('Y-m-d');
            $activity_from_date = date('Y-m-d', strtotime($activity_from_date));

            $leads_with_activity = DB::table('tbl_lead_status')
                ->whereBetween(
                    DB::raw("DATE(STR_TO_DATE(date, '%Y-%m-%d %h:%i %p'))"),
                    [$activity_from_date, $current_date]
                )
                ->distinct()
                ->pluck('lead_id')
                ->toArray();

            if (!empty($leads_with_activity)) {
                $query->whereNotIn('tbl_lead.id', $leads_with_activity);
            }
        }

        // Pagination
        $leads = $query->paginate(5);

        // Return JSON Response
        return response()->json([
            'data' => $leads->items(),
            'current_page' => $leads->currentPage(),
            'last_page' => $leads->lastPage(),
            'per_page' => $leads->perPage(),
            'total' => $leads->total(),
            'team_name' => $team_name,
            'role' => $admin_role,
            'success' => 'success',
        ]);
    }






    // ExcelExportPlOdLead
    public function ExcelExportPlOdLead(Request $request)
    {
        $search = $request->search;             // for search
        $lead_status = $request->lead_status;        // for lead_status
        $from_date = $request->from_date;          // for from_date
        $to_date = $request->to_date;            // for to_date
        $activity_from_date = $request->activity_from_date; // for activity_from_date
        $valid_statuses = [
            'NEW LEAD',
            'IN PROGRESS',
            'FOLLOW UP',
            'CALLBACK',
            'IMPORTANT LEAD',
            'LONG FOLLOW UP',
            'IN DOCUMENTATION',
            'FILE COMPLETED',
        ];

        // Base query with required joins 
        $query = DB::table('tbl_lead')
            ->leftJoin('admin', 'admin.id', '=', 'tbl_lead.admin_id')
            ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_lead.product_id')
            ->leftJoin('tbl_campaign', 'tbl_campaign.id', '=', 'tbl_lead.campaign_id')
            ->leftJoin('tbl_product_need', 'tbl_product_need.id', '=', 'tbl_lead.product_need_id')
            ->leftJoin('tbl_datacode', 'tbl_datacode.id', '=', 'tbl_lead.data_code')
            ->leftJoin('tbl_bank', 'tbl_bank.id', '=', 'tbl_lead.bank_id')
            ->leftJoin('tbl_casetype', 'tbl_casetype.id', '=', 'tbl_lead.casetype_id')
            ->leftJoin('tbl_channel_name', 'tbl_channel_name.id', '=', 'tbl_lead.channel_id')
            ->select([
                'tbl_lead.*',
                'admin.name as admin_name',
                'tbl_product.product_name as product_name',
                'tbl_campaign.campaign_name as campaign_name',
                'tbl_product_need.product_need as product_need',
                'tbl_datacode.datacode_name as datacode_name',
                'tbl_bank.bank_name as bank_name',
                'tbl_casetype.casetype as casetype',
                'tbl_channel_name.channel_name as channel_name'
            ])
            ->where('tbl_lead.lead_login_status', 'Lead')
            ->orderBy('tbl_lead.id', 'desc');

        // Apply search filter
        if (!empty($search) && ($search != 'undefined')) {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_lead.name', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.mobile', 'like', '%' . $search . '%')
                    ->orWhere('tbl_product.product_name', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.lead_status', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.pincode', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.company_name', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.loan_amount', 'like', '%' . $search . '%');
            });
        }

        // Apply lead_status filter (if provided)
        if ($lead_status) {
            $query->where('tbl_lead.lead_status', $lead_status);
        }

        // Apply date filters (if provided)
        if ($from_date && $to_date) {
            if ($from_date == $to_date) {
                // Fetch records for a single date
                $query->whereDate('tbl_lead.date', '=', $from_date);
            } else {
                // Adjust `to_date` to include the entire day
                $query->whereBetween('tbl_lead.date', [$from_date, date('Y-m-d', strtotime($to_date . ' +1 day'))]);
            }
        } elseif ($from_date) {
            $query->whereDate('tbl_lead.date', '>=', $from_date);
        } elseif ($to_date) {
            $query->whereDate('tbl_lead.date', '<=', $to_date);
        }

        // Apply Activity Date Filter only for specific lead statuses
        if ($activity_from_date) {
            $current_date = date('Y-m-d h:i A');
            $activity_from_date = date('Y-m-d h:i A', strtotime($activity_from_date));
            $excluded_leads = DB::table('tbl_lead_status')
                ->whereBetween(DB::raw("STR_TO_DATE(date, '%Y-%m-%d %h:%i %p')"), [$activity_from_date, $current_date])
                ->pluck('lead_id')
                ->toArray();
            if (!empty($excluded_leads)) {
                $query->whereNotIn('tbl_lead.id', $excluded_leads);
            } else {
                $query->whereDate(DB::raw("STR_TO_DATE(tbl_lead.date, '%Y-%m-%d %h:%i %p')"), '<=', $current_date);
            }
            // lead_status show only 'NEW LEAD', 'IN PROGRESS', 'FOLLOW UP', 'CALLBACK','IMPORTANT LEAD', 'LONG FOLLOW UP', 'IN DOCUMENTATION', 'FILE COMPLETED'
            $query->whereIn('tbl_lead.lead_status', $valid_statuses);
        }

        // Apply pagination
        // $leads = $query->get();
        // ✅ Get results and remove unwanted keys
        $leads = $query->get()->map(function ($item) {
            return collect($item)->except([
                'admin_id',
                'product_id',
                'campaign_id',
                'product_need_id',
                'data_code',
                'bank_id',
                'casetype_id',
                'channel_id'
            ]);
        });
        // Return response
        echo json_encode(['data' => $leads]);
    }

    // ExcelExportPlOdLogin
    public function ExcelExportPlOdLogin(Request $request)
    {
        $search = $request->search;             // for searching
        $from_date = $request->from_date;          // for fromdate
        $to_date = $request->to_date;            // for todate
        $login_status = $request->login_status;       // for leadstatus
        $activity_from_date = $request->activity_from_date; // for activity_from_date

        $valid_statuses = [
            'NEW FILE',
            'SENT TO BANK',
            'UNDERWRITING',
            'REELOK',
            'REELOK-HIGH PRIORITY',
            'APPROVED',
            'DISBURSED',
        ];

        // Base query with required joins
        $query = DB::table('tbl_lead')
            ->leftJoin('admin', 'admin.id', '=', 'tbl_lead.admin_id')
            ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_lead.product_id')
            ->leftJoin('tbl_campaign', 'tbl_campaign.id', '=', 'tbl_lead.campaign_id')
            ->leftJoin('tbl_product_need', 'tbl_product_need.id', '=', 'tbl_lead.product_need_id')
            ->leftJoin('tbl_datacode', 'tbl_datacode.id', '=', 'tbl_lead.data_code')
            ->leftJoin('tbl_bank', 'tbl_bank.id', '=', 'tbl_lead.bank_id')
            ->leftJoin('tbl_casetype', 'tbl_casetype.id', '=', 'tbl_lead.casetype_id')
            ->leftJoin('tbl_channel_name', 'tbl_channel_name.id', '=', 'tbl_lead.channel_id')
            ->select([
                'tbl_lead.*',
                'admin.name as admin_name',
                'tbl_product.product_name as product_name',
                'tbl_campaign.campaign_name as campaign_name',
                'tbl_product_need.product_need as product_need',
                'tbl_datacode.datacode_name as datacode_name',
                'tbl_bank.bank_name as bank_name',
                'tbl_casetype.casetype as casetype',
                'tbl_channel_name.channel_name as channel_name'
            ])
            ->where('tbl_lead.lead_login_status', 'Login')
            ->orderBy('tbl_lead.id', 'desc');

        // Apply search filter
        if (!empty($search) && ($search != 'undefined')) {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_lead.name', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.mobile', 'like', '%' . $search . '%')
                    ->orWhere('tbl_product.product_name', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.lead_status', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.pincode', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.company_name', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.loan_amount', 'like', '%' . $search . '%');
            });
        }

        // Apply login_status filter (if provided)
        if ($login_status) {
            $query->where('tbl_lead.login_status', $login_status);
        }

        // Apply date filters (if provided)
        if ($from_date && $to_date) {
            if ($from_date == $to_date) {
                // Single date ke liye records fetch kare
                $query->where(function ($q) use ($from_date) {
                    $q->whereDate('tbl_lead.date', '=', $from_date)
                        ->orWhereDate('tbl_lead.disbursment_date', '=', $from_date);
                });
            } else {
                // `to_date` ko ek din badhaya gaya taaki poora din consider ho
                $adjusted_to_date = date('Y-m-d', strtotime($to_date . ' +1 day'));
                $query->where(function ($q) use ($from_date, $adjusted_to_date) {
                    $q->whereBetween('tbl_lead.date', [$from_date, $adjusted_to_date])
                        ->orWhereBetween('tbl_lead.disbursment_date', [$from_date, $adjusted_to_date]);
                });
            }
        } elseif ($from_date) {
            $query->where(function ($q) use ($from_date) {
                $q->whereDate('tbl_lead.date', '>=', $from_date)
                    ->orWhereDate('tbl_lead.disbursment_date', '>=', $from_date);
            });
        } elseif ($to_date) {
            $query->where(function ($q) use ($to_date) {
                $q->whereDate('tbl_lead.date', '<=', $to_date)
                    ->orWhereDate('tbl_lead.disbursment_date', '<=', $to_date);
            });
        }

        // Apply Activity Date Filter only for specific lead status
        if ($activity_from_date) {
            $current_date = date('Y-m-d h:i A');
            $activity_from_date = date('Y-m-d h:i A', strtotime($activity_from_date));
            $excluded_leads = DB::table('tbl_lead_status')
                ->whereBetween(DB::raw("STR_TO_DATE(date, '%Y-%m-%d %h:%i %p')"), [$activity_from_date, $current_date])
                ->pluck('lead_id')
                ->toArray();
            if (!empty($excluded_leads)) {
                $query->whereNotIn('tbl_lead.id', $excluded_leads);
            } else {
                $query->whereDate(DB::raw("STR_TO_DATE(tbl_lead.date, '%Y-%m-%d %h:%i %p')"), '<=', $current_date);
            }
            // lead_status show only 'NEW FILE', 'SENT TO BANK', 'UNDERWRITING', 'REELOK', 'REELOK-HIGH PRIORITY', 'APPROVED', 'DISBURSED'
            $query->whereIn('tbl_lead.login_status', $valid_statuses);
        }

        // Apply pagination
        // $logins = $query->get();
        $logins = $query->get()->map(function ($item) {
            return collect($item)->except([
                'admin_id',
                'product_id',
                'campaign_id',
                'product_need_id',
                'data_code',
                'bank_id',
                'casetype_id',
                'channel_id'
            ]);
        });
        // Return response
        echo json_encode(['data' => $logins]);
    }




    ################# HOME LOAN LEADS START HERE #################
    ### show_Home_Loan_Lead ###
    public function show_Home_Loan_Lead(Request $request)
    {
        return view('Admin.pages.show_home_loan_leads');
    }

    public function show_Home_Loan_LeadAPI000(Request $request)
    {
        $search = $request->search;             // for search
        $lead_status = $request->lead_status;        // for lead_status
        $from_date = $request->from_date;          // for from_date
        $to_date = $request->to_date;            // for to_date
        $activity_from_date = $request->activity_from_date; // for activity_from_date
        $valid_statuses = [
            'NEW LEAD',
            'IN PROGRESS',
            'FOLLOW UP',
            'CALLBACK',
            'IMPORTANT LEAD',
            'LONG FOLLOW UP',
            'IN DOCUMENTATION',
            'FILE COMPLETED',
        ];

        // Base query
        $query = DB::table('tbl_lead')
            ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_lead.product_id')
            ->leftJoin('tbl_campaign', 'tbl_campaign.id', '=', 'tbl_lead.campaign_id')
            ->leftJoin('tbl_product_need', 'tbl_product_need.id', '=', 'tbl_lead.product_need_id')
            ->leftJoin('tbl_reassignment_lead', 'tbl_reassignment_lead.lead_id', '=', 'tbl_lead.id')
            ->select(
                'tbl_lead.*',
                'tbl_product.product_name',
                'tbl_campaign.campaign_name',
                'tbl_product_need.product_need'
            )
            // ->where('tbl_lead.lead_login_status', 'Lead') // Show only 'Lead' status leads
            ->where('tbl_lead.lead_login_status', 'HOME LOAN LEADS') // Show only 'HOME LEADS' status leads
            ->where(function ($query) {
                $query->where('tbl_reassignment_lead.lead_request_status', 'Accept')   // Lead Request Status 'Accept'
                    ->orWhereNull('tbl_reassignment_lead.lead_id'); // Lead ID jo insert nahi hui ho
            })
            ->orderBy('tbl_lead.id', 'desc');


        // Apply search filter
        if (!empty($search) && ($search != 'undefined')) {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_lead.name', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.mobile', 'like', '%' . $search . '%')
                    ->orWhere('tbl_product.product_name', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.lead_status', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.pincode', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.company_name', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.loan_amount', 'like', '%' . $search . '%');
            });
        }

        // Apply lead_status filter (if provided)
        if ($lead_status) {
            $query->where('tbl_lead.lead_status', $lead_status);
        }

        // Apply date filters (if provided)
        if ($from_date && $to_date) {
            if ($from_date == $to_date) {
                // Fetch records for a single date
                $query->whereDate('tbl_lead.date', '=', $from_date);
            } else {
                // Adjust `to_date` to include the entire day
                $query->whereBetween('tbl_lead.date', [$from_date, date('Y-m-d', strtotime($to_date . ' +1 day'))]);
            }
        } elseif ($from_date) {
            $query->whereDate('tbl_lead.date', '>=', $from_date);
        } elseif ($to_date) {
            $query->whereDate('tbl_lead.date', '<=', $to_date);
        }

        // Apply Activity Date Filter only for specific lead statuses
        if ($activity_from_date) {
            $current_date = date('Y-m-d h:i A');
            $activity_from_date = date('Y-m-d h:i A', strtotime($activity_from_date));
            $excluded_leads = DB::table('tbl_lead_status')
                ->whereBetween(DB::raw("STR_TO_DATE(date, '%Y-%m-%d %h:%i %p')"), [$activity_from_date, $current_date])
                ->pluck('lead_id')
                ->toArray();
            if (!empty($excluded_leads)) {
                $query->whereNotIn('tbl_lead.id', $excluded_leads);
            } else {
                $query->whereDate(DB::raw("STR_TO_DATE(tbl_lead.date, '%Y-%m-%d %h:%i %p')"), '<=', $current_date);
            }
            // lead_status show only 'NEW LEAD', 'IN PROGRESS', 'FOLLOW UP', 'CALLBACK','IMPORTANT LEAD', 'LONG FOLLOW UP', 'IN DOCUMENTATION', 'FILE COMPLETED'
            $query->whereIn('tbl_lead.lead_status', $valid_statuses);
        }

        // Apply pagination
        $leads = $query->paginate(2);

        // Return response
        return response()->json([
            'data' => $leads->items(),
            'current_page' => $leads->currentPage(),
            'last_page' => $leads->lastPage(),
            'per_page' => $leads->perPage(),
            'total' => $leads->total(),
            'success' => 'success',
        ]);
    }


    public function show_Home_Loan_LeadAPI(Request $request)
    {
        // Get session admin login 
        $adminSession = collect(session()->get('admin_login'))->first();
        $team_name = $adminSession->team ?? null;
        $admin_id = $adminSession->id ?? null;
        $admin_role = strtolower($adminSession->role ?? '');

        $search = $request->search;
        $lead_status = $request->lead_status;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $activity_from_date = $request->activity_from_date;

        $valid_statuses = [
            'NEW LEAD',
            'IN PROGRESS',
            'FOLLOW UP',
            'CALLBACK',
            'IMPORTANT LEAD',
            'LONG FOLLOW UP',
            'IN DOCUMENTATION',
            'FILE COMPLETED',
        ];

        // Start query to fetch leads
        $query = DB::table('tbl_lead')
            ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_lead.product_id')
            ->leftJoin('tbl_campaign', 'tbl_campaign.id', '=', 'tbl_lead.campaign_id')
            ->leftJoin('tbl_product_need', 'tbl_product_need.id', '=', 'tbl_lead.product_need_id')
            ->leftJoin('admin AS assigned_admin', 'assigned_admin.id', '=', 'tbl_lead.admin_id') // Assigned Admin
            ->leftJoin('admin AS manager_admin', 'manager_admin.id', '=', 'assigned_admin.manager') // Manager Name
            ->leftJoin('admin AS team_leader_admin', 'team_leader_admin.id', '=', 'assigned_admin.team_leader') // Team Leader Name
            ->select(
                'tbl_lead.*',
                'tbl_product.product_name',
                'tbl_campaign.campaign_name',
                'tbl_product_need.product_need',
                'assigned_admin.name AS admin_name', // Assigned Admin Name
                'assigned_admin.role AS admin_role',
                'manager_admin.name AS manager_name', // Manager Name
                'team_leader_admin.name AS team_leader_name' // Team Leader Name
            )
            ->where('tbl_lead.lead_login_status', 'HOME LOAN LEADS');

        // **Case: If logged-in user is a Manager**
        if ($admin_role === 'manager' && !empty($admin_id)) {
            $query->where(function ($q) use ($admin_id) {
                $q->where('tbl_lead.admin_id', $admin_id); // Manager's own leads

                // Fetch all TLs where this Manager is assigned as their Manager
                $tl_ids = DB::table('admin')
                    ->where('manager', $admin_id)
                    ->where('role', 'TL') // Only TL roles
                    ->pluck('id')
                    ->toArray();

                if (!empty($tl_ids)) {
                    // Get leads where TL's ID is in admin_id
                    $q->orWhereIn('tbl_lead.admin_id', $tl_ids);

                    // Fetch all Agents under these TLs
                    $agent_ids = DB::table('admin')
                        ->whereIn('team_leader', $tl_ids)
                        ->where('role', 'Agent') // Only Agent roles
                        ->pluck('id')
                        ->toArray();

                    if (!empty($agent_ids)) {
                        // Get leads where Agent's ID is in admin_id
                        $q->orWhereIn('tbl_lead.admin_id', $agent_ids);
                    }
                }
            });
        }

        // **Case: If logged-in user is a TL**
        elseif ($admin_role === 'tl' && !empty($admin_id)) {
            $query->where(function ($q) use ($admin_id) {
                $q->where('tbl_lead.admin_id', $admin_id); // TL's own leads

                // Fetch all Agents where this TL is assigned as their Team Leader
                $agent_ids = DB::table('admin')
                    ->where('team_leader', $admin_id)
                    ->where('role', 'Agent') // Only Agent roles
                    ->pluck('id')
                    ->toArray();

                if (!empty($agent_ids)) {
                    // Get leads where Agent's ID is in admin_id
                    $q->orWhereIn('tbl_lead.admin_id', $agent_ids);
                }
            });
        }

        // **Case: If logged-in user is an Agent**
        elseif ($admin_role === 'agent' && !empty($admin_id)) {
            $query->where('tbl_lead.admin_id', $admin_id); // Agent's own leads
        }

        // Order by latest leads
        $query->orderBy('tbl_lead.id', 'desc');

        // **Search Filter**
        if (!empty($search) && $search != 'undefined') {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_lead.name', 'like', "%$search%")
                    ->orWhere('tbl_lead.mobile', 'like', "%$search%")
                    ->orWhere('tbl_product.product_name', 'like', "%$search%")
                    ->orWhere('tbl_lead.lead_status', 'like', "%$search%")
                    ->orWhere('tbl_lead.pincode', 'like', "%$search%")
                    ->orWhere('tbl_lead.company_name', 'like', "%$search%")
                    ->orWhere('tbl_lead.loan_amount', 'like', "%$search%");
            });
        }

        // **Lead Status Filter**
        if (!empty($lead_status)) {
            $query->where('tbl_lead.lead_status', $lead_status);
        }

        // **Date Filters**
        if (!empty($from_date) && !empty($to_date)) {
            if ($from_date == $to_date) {
                $query->whereDate('tbl_lead.date', '=', $from_date);
            } else {
                $query->whereBetween('tbl_lead.date', [$from_date, date('Y-m-d', strtotime($to_date . ' +1 day'))]);
            }
        } elseif (!empty($from_date)) {
            $query->whereDate('tbl_lead.date', '>=', $from_date);
        } elseif (!empty($to_date)) {
            $query->whereDate('tbl_lead.date', '<=', $to_date);
        }


        // Activity Date Filter (Check only Y-m-d, exclude leads with activity in range)
        if (!empty($activity_from_date)) {
            $current_date = date('Y-m-d'); // Current date in 'Y-m-d' format
            $activity_from_date = date('Y-m-d', strtotime($activity_from_date)); // Selected date in 'Y-m-d' format

            // Fetch all unique lead IDs that have activity between $activity_from_date and now (date only)
            $leads_with_activity = DB::table('tbl_lead_status')
                ->whereBetween(
                    DB::raw("DATE(STR_TO_DATE(date, '%Y-%m-%d %h:%i %p'))"),
                    [$activity_from_date, $current_date]
                )
                ->distinct()
                ->pluck('lead_id')
                ->toArray();
            // Exclude leads that have activity in the given range
            if (!empty($leads_with_activity)) {
                $query->whereNotIn('tbl_lead.id', $leads_with_activity);
            }
        }
        // Apply lead status filter if valid statuses are provided
        // if (!empty($valid_statuses)) {
        //     $query->whereIn('tbl_lead.lead_status', $valid_statuses);
        // }

        // **Get Leads with Pagination**
        $leads = $query->paginate(8);

        // **Return JSON Response**
        return response()->json([
            'data' => $leads->items(),
            'current_page' => $leads->currentPage(),
            'last_page' => $leads->lastPage(),
            'per_page' => $leads->perPage(),
            'total' => $leads->total(),
            'team_name' => $team_name,
            'role' => $admin_role,
            'success' => 'success',
        ]);
    }







    ################# Home Loan Login Start Here #################
    // Pl Od Login
    public function show_Home_Loan_Login(Request $request)
    {
        return view('Admin.pages.show_home_loan_login');
    }
    public function show_Home_Loan_LoginAPI000(Request $request)
    {
        $search = $request->search;             // for searching
        $from_date = $request->from_date;          // for fromdate
        $to_date = $request->to_date;            // for todate
        $login_status = $request->login_status;       // for leadstatus
        $activity_from_date = $request->activity_from_date; // for activity_from_date

        $valid_statuses = [
            'NEW FILE',
            'SENT TO BANK',
            'UNDERWRITING',
            'REELOK',
            'REELOK-HIGH PRIORITY',
            'APPROVED',
            'DISBURSED',
        ];

        // Base query
        $query = DB::table('tbl_lead')
            ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_lead.product_id')
            ->leftJoin('tbl_campaign', 'tbl_campaign.id', '=', 'tbl_lead.campaign_id')
            ->leftJoin('tbl_product_need', 'tbl_product_need.id', '=', 'tbl_lead.product_need_id')
            ->select('tbl_lead.*', 'tbl_product.product_name', 'tbl_campaign.campaign_name', 'tbl_product_need.product_need')
            ->where('tbl_lead.lead_login_status', 'HOME LOAN LOGIN') // Show only 'Login' status leads
            ->orderBy('tbl_lead.move_login_date', 'desc');

        // Apply search filter (works independently)
        if (!empty($search) && ($search != 'undefined')) {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_lead.name', 'like', '%' . $search . '%')
                    ->orWhere('tbl_lead.mobile', 'like', '%' . $search . '%');
            });
        }

        // Apply login_status filter (if provided)
        if ($login_status) {
            $query->where('tbl_lead.login_status', $login_status);
        }

        // Apply date filters (if provided)
        if ($from_date && $to_date) {
            if ($from_date == $to_date) {
                // Single date ke liye records fetch kare
                $query->where(function ($q) use ($from_date) {
                    $q->whereDate('tbl_lead.date', '=', $from_date)
                        ->orWhereDate('tbl_lead.disbursment_date', '=', $from_date);
                });
            } else {
                // `to_date` ko ek din badhaya gaya taaki poora din consider ho
                $adjusted_to_date = date('Y-m-d', strtotime($to_date . ' +1 day'));
                $query->where(function ($q) use ($from_date, $adjusted_to_date) {
                    $q->whereBetween('tbl_lead.date', [$from_date, $adjusted_to_date])
                        ->orWhereBetween('tbl_lead.disbursment_date', [$from_date, $adjusted_to_date]);
                });
            }
        } elseif ($from_date) {
            $query->where(function ($q) use ($from_date) {
                $q->whereDate('tbl_lead.date', '>=', $from_date)
                    ->orWhereDate('tbl_lead.disbursment_date', '>=', $from_date);
            });
        } elseif ($to_date) {
            $query->where(function ($q) use ($to_date) {
                $q->whereDate('tbl_lead.date', '<=', $to_date)
                    ->orWhereDate('tbl_lead.disbursment_date', '<=', $to_date);
            });
        }

        // Apply Activity Date Filter only for specific lead statuses
        if ($activity_from_date) {
            $current_date = date('Y-m-d h:i A');
            $excluded_leads = DB::table('tbl_lead_status')
                ->whereBetween(DB::raw("STR_TO_DATE(date, '%Y-%m-%d %h:%i %p')"), [
                    DB::raw("STR_TO_DATE('$activity_from_date', '%Y-%m-%d %h:%i %p')"),
                    DB::raw("STR_TO_DATE('$current_date', '%Y-%m-%d %h:%i %p')")
                ])
                ->pluck('lead_id')
                ->toArray();
            // Show only leads that had NO activity in the given period
            if (!empty($excluded_leads)) {
                $query->whereNotIn('tbl_lead.id', $excluded_leads);
            }
            $query->whereIn('tbl_lead.login_status', $valid_statuses);
        }


        // Apply pagination
        $leads = $query->paginate(5);
        // Return response
        return response()->json([
            'data' => $leads->items(),
            'current_page' => $leads->currentPage(),
            'last_page' => $leads->lastPage(),
            'per_page' => $leads->perPage(),
            'total' => $leads->total(),
            'success' => 'success',
        ]);
    }


    public function show_Home_Loan_LoginAPI(Request $request)
    {
        // Get session admin login 
        $adminSession = collect(session()->get('admin_login'))->first();
        $team_name = $adminSession->team ?? null;
        $admin_id = $adminSession->id ?? null;
        $admin_role = strtolower($adminSession->role ?? '');

        // Request parameters
        $search = $request->search;
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $login_status = $request->login_status;
        $activity_from_date = $request->activity_from_date;

        $valid_statuses = [
            'NEW FILE',
            'SENT TO BANK',
            'UNDERWRITING',
            'REELOK',
            'REELOK-HIGH PRIORITY',
            'APPROVED',
            'DISBURSED',
        ];

        // Base query
        $query = DB::table('tbl_lead')
            ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_lead.product_id')
            ->leftJoin('tbl_campaign', 'tbl_campaign.id', '=', 'tbl_lead.campaign_id')
            ->leftJoin('tbl_product_need', 'tbl_product_need.id', '=', 'tbl_lead.product_need_id')
            ->leftJoin('admin AS assigned_admin', 'assigned_admin.id', '=', 'tbl_lead.admin_id')
            ->leftJoin('admin AS manager_admin', 'manager_admin.id', '=', 'assigned_admin.manager')
            ->leftJoin('admin AS team_leader_admin', 'team_leader_admin.id', '=', 'assigned_admin.team_leader')
            ->select(
                'tbl_lead.*',
                'tbl_product.product_name',
                'tbl_campaign.campaign_name',
                'tbl_product_need.product_need',
                'assigned_admin.name AS admin_name',
                'assigned_admin.role AS admin_role',
                'manager_admin.name AS manager_name',
                'team_leader_admin.name AS team_leader_name'
            )
            ->where('tbl_lead.lead_login_status', 'HOME LOAN LOGIN');

        // Role-Based Filtering
        if ($admin_role === 'manager' && !empty($admin_id)) {
            $query->where(function ($q) use ($admin_id) {
                $q->where('tbl_lead.admin_id', $admin_id)
                    ->orWhereIn('tbl_lead.admin_id', function ($subQuery) use ($admin_id) {
                        // Fetch TLs under this Manager
                        $subQuery->select('id')
                            ->from('admin')
                            ->where('manager', $admin_id)
                            ->where('role', 'TL')
                            ->union(
                                DB::table('admin')
                                    ->select('admin.id')
                                    ->whereIn('team_leader', function ($tlQuery) use ($admin_id) {
                                    $tlQuery->select('id')
                                        ->from('admin')
                                        ->where('manager', $admin_id)
                                        ->where('role', 'TL');
                                })
                                    ->where('role', 'Agent')
                            );
                    });
            });
        } elseif ($admin_role === 'tl' && !empty($admin_id)) {
            $query->where(function ($q) use ($admin_id) {
                $q->where('tbl_lead.admin_id', $admin_id)
                    ->orWhereIn('tbl_lead.admin_id', function ($subQuery) use ($admin_id) {
                        $subQuery->select('id')
                            ->from('admin')
                            ->where('team_leader', $admin_id)
                            ->where('role', 'Agent');
                    });
            });
        } elseif ($admin_role === 'agent' && !empty($admin_id)) {
            $query->where('tbl_lead.admin_id', $admin_id);
        }

        // $query->orderBy('tbl_lead.move_homeloan_login_date', 'desc');
        $query->orderByRaw("STR_TO_DATE(tbl_lead.move_homeloan_login_date, '%Y-%m-%d %h:%i %p') DESC");

        // Search Filter
        if (!empty($search) && $search !== 'undefined') {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_lead.name', 'like', "%{$search}%")
                    ->orWhere('tbl_lead.mobile', 'like', "%{$search}%");
            });
        }

        // Login Status Filter
        if (!empty($login_status)) {
            $query->where('tbl_lead.login_status', $login_status);
        }

        // Date Filters
        if (!empty($from_date) && !empty($to_date)) {
            if ($from_date === $to_date) {
                $query->where(function ($q) use ($from_date) {
                    $q->whereDate('tbl_lead.date', '=', $from_date)
                        ->orWhereDate('tbl_lead.disbursment_date', '=', $from_date);
                });
            } else {
                $to_date_plus_one = date('Y-m-d', strtotime($to_date . ' +1 day'));
                $query->where(function ($q) use ($from_date, $to_date_plus_one) {
                    $q->whereBetween('tbl_lead.date', [$from_date, $to_date_plus_one])
                        ->orWhereBetween('tbl_lead.disbursment_date', [$from_date, $to_date_plus_one]);
                });
            }
        } elseif (!empty($from_date)) {
            $query->where(function ($q) use ($from_date) {
                $q->whereDate('tbl_lead.date', '>=', $from_date)
                    ->orWhereDate('tbl_lead.disbursment_date', '>=', $from_date);
            });
        } elseif (!empty($to_date)) {
            $query->where(function ($q) use ($to_date) {
                $q->whereDate('tbl_lead.date', '<=', $to_date)
                    ->orWhereDate('tbl_lead.disbursment_date', '<=', $to_date);
            });
        }

        // Activity Date Filter
        if (!empty($activity_from_date)) {
            $current_date = date('Y-m-d');
            $activity_from_date = date('Y-m-d', strtotime($activity_from_date));

            $leads_with_activity = DB::table('tbl_lead_status')
                ->whereBetween(
                    DB::raw("DATE(STR_TO_DATE(date, '%Y-%m-%d %h:%i %p'))"),
                    [$activity_from_date, $current_date]
                )
                ->distinct()
                ->pluck('lead_id')
                ->toArray();

            if (!empty($leads_with_activity)) {
                $query->whereNotIn('tbl_lead.id', $leads_with_activity);
            }
        }

        // Pagination
        $leads = $query->paginate(5);

        // Return JSON Response
        return response()->json([
            'data' => $leads->items(),
            'current_page' => $leads->currentPage(),
            'last_page' => $leads->lastPage(),
            'per_page' => $leads->perPage(),
            'total' => $leads->total(),
            'team_name' => $team_name,
            'role' => $admin_role,
            'success' => 'success',
        ]);
    }











    // updateFileSenttoLogin
    public function updateFileSenttoLoginOLD(Request $request)
    {
        $leadId = $request->input('lead_id');
        $lead = DB::table('tbl_lead')->where('id', $leadId)->first();
        if ($lead && $lead->imp_question == 1) { // Check if imp_question is 1
            DB::table('tbl_lead')
                ->where('id', $leadId)
                ->update([
                    'lead_status' => 'FILE SENT TO LOGIN',
                    'lead_login_status' => 'Login', // Lead to Login
                ]);
            // Insert into tbl_lead_status after updating the task status
            DB::table('tbl_lead_status')->insert([
                'admin_id' => $lead->admin_id, // Assuming logged-in admin
                'lead_id' => $leadId,
                'lead_status' => 'Change FILE SENT TO LOGIN',
                'date' => $this->date,
            ]);
            return response()->json(['status' => 'success', 'message' => 'Lead status and login status updated successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Please fill all fields are Requied']);
        }
    }

    // File Sent to PlOdLogin
    public function updateFileSenttoLogin(Request $request)
    {
        $leadId = $request->input('lead_id');
        $adminIds = $request->input('admin_ids'); // Array of Admin IDs

        if (!$leadId || empty($adminIds)) {
            return response()->json(['status' => 'error', 'message' => 'Please select at least one admin.']);
        }

        // Update Lead Status
        DB::table('tbl_lead')->where('id', $leadId)->update([
            'lead_status' => 'FILE SENT TO LOGIN',
            'lead_login_status' => 'PL & OD LOGIN',
            'login_status' => 'NEW FILE',
            'assign_operation' => implode(',', $adminIds),
            'move_login_date' => $this->date,
        ]);

        // Assign Admins to Lead in tbl_lead_status
        foreach ($adminIds as $adminId) {
            DB::table('tbl_lead_status')->insert([
                'admin_id' => $adminId,
                'lead_id' => $leadId,
                'lead_status' => 'Assigned FILE SENT TO LOGIN',
                'date' => $this->date,
            ]);
        }
        return response()->json(['status' => 'success', 'message' => 'Lead Moved to Login Assigned By Operations Department Successfully']);
    }

    // updateFileSenttoHomeLogin
    public function updateFileSenttoHomeLogin(Request $request)
    {
        $leadId = $request->input('lead_id');
        $adminIds = $request->input('admin_ids'); // Array of Admin IDs

        if (!$leadId || empty($adminIds)) {
            return response()->json(['status' => 'error', 'message' => 'Please select at least one admin.']);
        }

        // Update Lead Status
        DB::table('tbl_lead')->where('id', $leadId)->update([
            'lead_status' => 'FILE SENT TO HOME LOAN LOGIN',
            'lead_login_status' => 'HOME LOAN LOGIN',
            'login_status' => 'NEW FILE',
            'assign_operation' => implode(',', $adminIds),
            'move_homeloan_login_date' => $this->date,
        ]);

        // Assign Admins to Lead in tbl_lead_status
        foreach ($adminIds as $adminId) {
            DB::table('tbl_lead_status')->insert([
                'admin_id' => $adminId,
                'lead_id' => $leadId,
                'lead_status' => 'Assigned FILE SENT TO HOME LOAN LOGIN',
                'date' => $this->date,
            ]);
        }
        return response()->json(['status' => 'success', 'message' => 'Lead Moved to Login Assigned By Operations Department Successfully']);
    }



    // get operation Department
    public function getAdminUsers()
    {
        $users = DB::table('admin')->where('role', '=', 'Operation Manager')->select('id', 'name')->get();
        if ($users->isEmpty()) {
            return response()->json(['status' => 'error', 'message' => 'No users found']);
        }
        return response()->json(['status' => 'success', 'users' => $users]);
    }

    ### User Profile ###
    public function user_profile($id)
    {
        $data['leads'] = DB::table('tbl_lead')
            ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_lead.product_id')
            ->leftJoin('tbl_campaign', 'tbl_campaign.id', '=', 'tbl_lead.campaign_id')
            ->leftJoin('tbl_product_need', 'tbl_product_need.id', '=', 'tbl_lead.product_need_id')
            ->leftJoin('tbl_bank', 'tbl_bank.id', '=', 'tbl_lead.bank_id')
            ->leftJoin('tbl_casetype', 'tbl_casetype.id', '=', 'tbl_lead.casetype_id')
            ->leftJoin('tbl_datacode', 'tbl_datacode.id', '=', 'tbl_lead.data_code')          // Datacode
            ->leftJoin('tbl_channel_name', 'tbl_channel_name.id', '=', 'tbl_lead.channel_id') // Channel Name
            ->select('tbl_lead.*', 'tbl_product.product_name', 'tbl_campaign.campaign_name', 'tbl_product_need.product_need', 'tbl_bank.bank_name', 'tbl_casetype.casetype', 'tbl_datacode.datacode_name', 'tbl_channel_name.channel_name')
            ->where('tbl_lead.id', $id) // Filter by $id
            ->orderBy('tbl_lead.id', 'desc')
            ->first();
        // Remark Data
        $data['remarks'] = DB::table('tbl_remark')
            ->leftJoin('admin', 'admin.id', '=', 'tbl_remark.admin_id')
            ->leftJoin('tbl_lead', 'tbl_lead.id', '=', 'tbl_remark.lead_id')
            ->select('tbl_remark.*', 'admin.name as createdby')
            ->where('tbl_remark.lead_id', $id) // Match lead_id with $id from tbl_lead
            ->orderBy('tbl_remark.id', 'desc')
            ->get();

        // Task Data
        $tasks = DB::table('tbl_task')
            ->leftJoin('admin', 'admin.id', '=', 'tbl_task.admin_id')
            ->leftJoin('tbl_lead', 'tbl_lead.id', '=', 'tbl_task.lead_id')
            ->select('tbl_task.*', 'tbl_lead.name as lead_name', 'admin.name as createdby')
            ->where('tbl_task.lead_id', $id) // Filter by lead_id
            ->orderBy('tbl_task.id', 'desc')
            ->get();

        // Map tasks to include assigned admin names
        foreach ($tasks as $task) {
            $adminNames = DB::table('admin')
                ->whereIn('id', explode(',', $task->assign))
                ->pluck('name')
                ->toArray();
            $task->assigned_names = implode(', ', $adminNames);
        }
        $data['tasks'] = $tasks;
        // Remark Data
        $data['lead_activity'] = DB::table('tbl_lead_status')
            ->leftJoin('tbl_lead', 'tbl_lead.id', '=', 'tbl_lead_status.lead_id')
            ->select('tbl_lead_status.*')
            ->where('tbl_lead_status.lead_id', $id) // Match lead_id with $id from tbl_lead
            ->orderBy('tbl_lead_status.id', 'desc')
            ->get();

        if ($data) {
            return view('Admin.pages.user_profile', $data);
        }
    }

    // Task Comment Here in Task Tab
    public function getTaskComments(Request $request)
    {
        $taskId = $request->task_id;
        $comments = DB::table('tbl_task_comment')
            ->where('task_id', $taskId)
            ->leftJoin('admin', 'tbl_task_comment.admin_id', '=', 'admin.id') // Assuming 'admins' table exists
            ->select('tbl_task_comment.*', 'admin.name as createdby')
            ->orderBy('tbl_task_comment.id', 'asc')
            ->get();
        return response()->json($comments);
    }

    // Task History Here in Task Tab
    public function getTaskHistory(Request $request)
    {
        $taskId = $request->task_id;
        $history = DB::table('tbl_task_history')
            ->where('task_id', $taskId)
            ->leftJoin('admin', 'tbl_task_history.admin_id', '=', 'admin.id') // Assuming 'admins' table exists
            ->select('tbl_task_history.*', 'admin.name as createdby')
            ->orderBy('tbl_task_history.id', 'asc')
            ->get();
        // dd($history);
        return response()->json($history);
    }

    // Get Task Status
    public function getTaskStatus(Request $request)
    {
        $task = DB::table('tbl_task')->where('id', $request->task_id)->first();
        return response()->json(['task_status' => $task->task_status]);
    }

    // update Task Status
    public function updateTaskStatusOLD(Request $request)
    {
        $task_id = $request->task_id;
        $admin_id = $request->admin_id;
        $new_status = $request->task_status;

        $task = DB::table('tbl_task')->where('id', $task_id)->first();
        $lead_id = $task->lead_id;
        $data = [
            'task_status' => $new_status,
            'admin_id' => $admin_id,
        ];

        if (!empty($data)) {
            $update = DB::table('tbl_task')->where('id', $task->id)->update($data);
            $historyData = [
                'admin_id' => $admin_id,
                'lead_id' => $lead_id,
                'changes' => $new_status,
                'date' => $this->date,
            ];
            DB::table('tbl_task_history')->insert($historyData);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    public function updateTaskStatus(Request $request)
    {
        $task_id = $request->task_id;
        $admin_id = $request->admin_id;
        $new_status = $request->task_status;

        if ($new_status === 'Close Task') {
            $task_status = 'Close Task';
            $task_history_status = 'Task Closed';
        } else {
            $task_status = 'Open Task';
            $task_history_status = 'Reopen';
        }

        $task = DB::table('tbl_task')->where('id', $task_id)->first();
        $lead_id = $task->lead_id;
        $data = [
            'task_status' => $task_status,
            'admin_id' => $admin_id,
        ];

        if (!empty($data)) {
            $update = DB::table('tbl_task')->where('id', $task->id)->update($data);
            $historyData = [
                'admin_id' => $admin_id,
                'lead_id' => $lead_id,
                'task_id' => $task_id,
                'changes' => $task_history_status,
                'date' => $this->date,
            ];
            DB::table('tbl_task_history')->insert($historyData);

            // Insert into tbl_lead_status after updating the task status
            DB::table('tbl_lead_status')->insert([
                'admin_id' => $admin_id,
                'lead_id' => $lead_id,
                'lead_status' => 'Change Task Status',
                'date' => $this->date,
            ]);

            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    // update_aboutsection
    public function update_aboutsectionOLD(Request $request)
    {
        $data = [
            'id' => $request->id,
            'data_code' => $request->data_code,
            'name' => $request->name,
            'pincode' => $request->pincode,
        ];
        $update = DB::table('tbl_lead')->where('id', $request->id)->update($data);
        if ($update) {
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    public function update_aboutsection(Request $request)
    {
        $id = $request->id;
        $lead = DB::table('tbl_lead')->where('id', $id)->first();
        $admin_id = $lead->admin_id ?? '';
        $data = [
            'id' => $id,
            'data_code' => $request->data_code,
            'name' => $request->name,
            'pincode' => $request->pincode,
        ];
        $update = DB::table('tbl_lead')->where('id', $id)->update($data);
        if ($update) {
            DB::table('tbl_lead_status')->insert([
                'admin_id' => $admin_id,
                'lead_id' => $id,
                'lead_status' => 'Change About Section',
                'date' => $this->date,
            ]);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    // Update Operation Section Here
    public function updateOperationSection(Request $request)
    {
        $id = $request->id;
        $lead = DB::table('tbl_lead')->where('id', $id)->first();
        $admin_id = $lead->admin_id ?? '';

        // ₹ aur comma ko remove karke sirf numeric value rakhna
        $amount_approved = preg_replace('/[^0-9]/', '', $request->amount_approved);
        $amount_disbursed = preg_replace('/[^0-9]/', '', $request->amount_disbursed);
        $internal_top = preg_replace('/[^0-9]/', '', $request->internal_top);
        $cashback_to_customer = preg_replace('/[^0-9]/', '', $request->cashback_to_customer);

        $data = [
            'id' => $id,
            'channel_id' => $request->channel_id,
            'los_number' => $request->los_number,
            'amount_approved' => $amount_approved,
            'rate' => $request->rate,
            'pf_insurance' => $request->pf_insurance,
            'tenure_given' => $request->tenure_given,
            'tenure_year' => $request->tenure_year,
            'amount_disbursed' => $amount_disbursed,
            'internal_top' => $internal_top,
            'cashback_to_customer' => $cashback_to_customer,
            'disbursment_date' => $request->disbursment_date,
        ];
        $update = DB::table('tbl_lead')->where('id', $id)->update($data);
        if ($update) {
            DB::table('tbl_lead_status')->insert([
                'admin_id' => $admin_id,
                'lead_id' => $id,
                'lead_status' => 'Change Operation Section',
                'date' => $this->date,
            ]);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    // update_process
    public function update_processOLD(Request $request)
    {
        $id = $request->id;
        $data = [
            'id' => $id,
            'loan_amount' => $request->loan_amount,
            'process' => $request->process,
            'bank_id' => $request->bank_id,
            'product_need_id' => $request->product_need_id,
            'casetype_id' => $request->casetype_id,
            'tenure' => $request->tenure,
            'year' => $request->year,
        ];

        $update = DB::table('tbl_lead')->where('id', $id)->update($data);
        if ($update) {
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    public function update_process(Request $request)
    {
        $id = $request->id;
        $lead = DB::table('tbl_lead')->where('id', $id)->first();
        $admin_id = $lead->admin_id ?? '';

        // ₹ aur comma ko remove karke sirf numeric value rakhna
        $loan_amount = preg_replace('/[^0-9]/', '', $request->loan_amount);

        $data = [
            'id' => $id,
            'loan_amount' => $loan_amount,
            'process' => $request->process,
            'bank_id' => $request->bank_id,
            'product_need_id' => $request->product_need_id,
            'casetype_id' => $request->casetype_id,
            'tenure' => $request->tenure,
            'year' => $request->year,
        ];
        $update = DB::table('tbl_lead')->where('id', $id)->update($data);
        if ($update) {
            DB::table('tbl_lead_status')->insert([
                'admin_id' => $admin_id,
                'lead_id' => $id,
                'lead_status' => 'Change How To Process Section',
                'date' => $this->date,
            ]);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    // update Checkebox Start Here
    public function updateImpQuestionNEW(Request $request)
    {
        $id = $request->id;
        $lead = DB::table('tbl_lead')->where('id', $id)->first();
        $admin_id = $lead->admin_id ?? '';

        // Update imp_question
        $updated = DB::table('tbl_lead')
            ->where('id', $id)
            ->update(['imp_question' => $request->imp_question]);

        if ($updated) {
            DB::table('tbl_lead_status')->insert([
                'admin_id' => $admin_id,
                'lead_id' => $id,
                'lead_status' => 'Change Important Question Section',
                'date' => $this->date,
            ]);
            return response()->json(['success' => 'success']);
        }
        return response()->json(['success' => 'error']);
    }

    public function updateImpQuestion(Request $request)
    {
        $id = $request->id;
        $lead = DB::table('tbl_lead')->where('id', $id)->first();
        $admin_id = $lead->admin_id ?? '';

        // Check if imp_que exists
        $impQueValue = $request->imp_que ?? '';

        // Update imp_question and imp_que in tbl_lead
        $updated = DB::table('tbl_lead')
            ->where('id', $id)
            ->update([
                'imp_question' => $request->imp_question,
                'imp_que' => $impQueValue, // Updating imp_que with selected IDs
            ]);

        if ($updated) {
            DB::table('tbl_lead_status')->insert([
                'admin_id' => $admin_id,
                'lead_id' => $id,
                'lead_status' => 'Change Important Question Section',
                'date' => $this->date,
            ]);
            return response()->json(['success' => 'success']);
        }
        return response()->json(['success' => 'error']);
    }

    // Delete deleteObligation From History
    public function deleteObligation(Request $request)
    {
        $id = $request->id;
        $obligation = DB::table('tbl_obligation')->where('id', $id)->first();
        if ($obligation) {
            $lead_id = $obligation->lead_id;
            $bt_obligation = $obligation->bt_obligation;
            $bt_pos = $obligation->bt_pos;
            $bt_emi = $obligation->bt_emi;

            $lead = DB::table('tbl_lead')->where('id', $lead_id)->first();

            if ($lead) {
                if ($bt_obligation == "BT") {
                    DB::table('tbl_lead')
                        ->where('id', $lead_id)
                        ->update([
                            'pos' => DB::raw("pos - $bt_pos"),
                        ]);
                } elseif ($bt_obligation == "Obligation") {
                    DB::table('tbl_lead')
                        ->where('id', $lead_id)
                        ->update([
                            'obligation' => DB::raw("obligation - $bt_emi"),
                        ]);
                }
            }

            // tbl_obligation से डेटा delete करना
            DB::table('tbl_obligation')->where('id', $id)->delete();

            return response()->json(['success' => 'success']);
        }

        return response()->json(['success' => 'failed']);
    }

    // update_obligationsection
    public function update_obligationsection(Request $request)
    {
        $id = $request->id;
        $lead = DB::table('tbl_lead')->where('id', $id)->first();
        $admin_id = $lead->admin_id ?? '';

        // Update the `tbl_lead` table
        $obligation = preg_replace('/[^0-9]/', '', $request->obligation);
        $pos = preg_replace('/[^0-9]/', '', $request->pos);

        $data = [
            'id' => $id,
            'salary' => $request->salary,
            'yearly_bonus' => $request->yearly_bonus,
            'loan_amount' => $request->loan_amount,
            'company_name' => $request->company_name,
            'company_type' => $request->company_type,
            'company_category_id' => $request->company_category_id,
            'market_value' => $request->market_value,
            'property_type' => $request->property_type,
            'case_type' => $request->case_type,
            'property_registary_status' => $request->property_registary_status,
            'obligation' => $obligation,
            'pos' => $pos,
            'cibil_score' => $request->cibil_score
        ];

        $update = DB::table('tbl_lead')->where('id', $id)->update($data);

        if ($update) {
            // Insert data into `tbl_obligation` table
            if (is_array($request->product_id) && count($request->product_id) > 0) {
                foreach ($request->product_id as $index => $productId) {
                    DB::table('tbl_obligation')->insert([
                        'admin_id' => $admin_id, // From tbl_lead
                        'lead_id' => $id,
                        'product_id' => $productId,
                        'bank_id' => $request->bank_id[$index] ?? null,
                        'total_loan_amount' => $request->total_loan_amount[$index] ?? null,
                        'bt_pos' => $request->bt_pos[$index] ?? null,
                        'bt_emi' => $request->bt_emi[$index] ?? null,
                        'bt_obligation' => $request->bt_obligation[$index] ?? null,
                        'date' => $this->date,
                    ]);
                }
            }

            // Insert into tbl_lead_status after the update
            DB::table('tbl_lead_status')->insert([
                'admin_id' => $admin_id,
                'lead_id' => $id,
                'lead_status' => 'Change Obligation Section',
                'date' => $this->date,
            ]);

            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    // update_loginform
    public function update_loginform(Request $request)
    {
        $id = $request->id;
        $lead = DB::table('tbl_lead')->where('id', $id)->first();
        $admin_id = $lead->admin_id ?? '';

        // Prepare data to update the tbl_lead table
        $data = [
            'id' => $id,
            'reference_name' => $request->reference_name,
            'aadhar_no' => $request->aadhar_no,
            'qualification' => $request->qualification,
            'name' => $request->name,
            'pan_card_no' => $request->pan_card_no,
            'product_id' => $request->product_id,
            'mobile' => $request->mobile,
            'dob' => $request->dob,
            'account_no' => $request->account_no,
            'alternate_mobile' => $request->alternate_mobile,
            'father_name' => $request->father_name,
            'ifsc_code' => $request->ifsc_code,
            'mother_name' => $request->mother_name,
            'marital_status' => $request->marital_status,
            'spouse_name' => $request->spouse_name,
            'spouse_dob' => $request->spouse_dob,
            'current_address' => $request->current_address,
            'current_address_landmark' => $request->current_address_landmark,
            'current_address_type' => $request->current_address_type,
            'current_address_proof' => $request->current_address_proof,
            'living_current_address_year' => $request->living_current_address_year,
            'living_current_city_year' => $request->living_current_city_year,
            'permanent_address' => $request->permanent_address,
            'permanent_address_landmark' => $request->permanent_address_landmark,
            'company_name' => $request->company_name,
            'designation' => $request->designation,
            'department' => $request->department,
            'current_company' => $request->current_company,
            'current_work_experience' => $request->current_work_experience,
            'total_work_experience' => $request->total_work_experience,
            'personal_email' => $request->personal_email,
            'work_email' => $request->work_email,
            'office_address' => $request->office_address,
            'office_address_landmark' => $request->office_address_landmark,
            'reference_name1' => $request->reference_name1,
            'reference_mobile1' => $request->reference_mobile1,
            'reference_relation1' => $request->reference_relation1,
            'reference_address1' => $request->reference_address1,
            'reference_name2' => $request->reference_name2,
            'reference_mobile2' => $request->reference_mobile2,
            'reference_relation2' => $request->reference_relation2,
            'reference_address2' => $request->reference_address2,
        ];

        // Update the tbl_lead table
        $update = DB::table('tbl_lead')->where('id', $id)->update($data);
        if ($update) {
            DB::table('tbl_lead_status')->insert([
                'admin_id' => $admin_id,
                'lead_id' => $id,
                'lead_status' => 'Change Login Form Section',
                'date' => $this->date,
            ]);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    // update Attachment
    public function update_attachmentOLD(Request $request)
    {
        $imagedata = DB::table('tbl_lead')->where('admin_id', $request->admin_id)->where('id', $request->lead_id)->first();
        $imageFields = [
            'cibil_report_image',
            'passport_image',
            'pan_card_image',
            'aadhar_card_image',
            'salary_3month_image',
            'salary_account_image',
            'bt_loan_image',
            'credit_card_image',
            'electric_bill_image',
            'form_16_image',
            'other_document_image',
        ];

        // Initialize data array
        $data = [
            'all_file_password' => $request->all_file_password,
        ];

        foreach ($imageFields as $field) {
            // Check if the request contains a file for the current field
            if ($request->hasFile($field)) {
                // Remove the old image if it exists and is not null
                if ($imagedata && !empty($imagedata->$field)) {
                    $oldImagePath = public_path("storage/Admin/$field/" . basename($imagedata->$field));
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Save the new image
                $file = $request->file($field);
                $unique_id = uniqid();
                $name = $unique_id . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("storage/Admin/$field/"), $name);

                // Update the data array with the new image URL
                $data[$field] = "https://rupiyamaker.m-bit.org.in//storage/Admin/$field/$name";
            } else {
                // Retain the existing image URL or set to null if no file is uploaded
                $data[$field] = $imagedata->$field ?? null;
            }
        }

        // Update the database
        DB::table('tbl_lead')->where('admin_id', $request->admin_id)->where('id', $request->lead_id)->update($data);

        return response()->json([
            'success' => 'success',
        ]);
    }

    // latest
    public function update_attachmentOLDNEW(Request $request)
    {
        $imagedata = DB::table('tbl_lead')->where('admin_id', $request->admin_id)->where('id', $request->lead_id)->first();
        $imageFields = [
            'cibil_report_image',
            'passport_image',
            'pan_card_image',
            'aadhar_card_image',
            'salary_3month_image',
            'salary_account_image',
            'bt_loan_image',
            'credit_card_image',
            'electric_bill_image',
            'form_16_image',
            'other_document_image',
        ];

        // Initialize data array
        $data = [
            'all_file_password' => $request->all_file_password,
        ];

        foreach ($imageFields as $field) {
            // Check if the request contains a file for the current field
            if ($request->hasFile($field)) {
                // Remove the old image if it exists and is not null
                if ($imagedata && !empty($imagedata->$field)) {
                    $oldImagePath = public_path("storage/Admin/$field/" . basename($imagedata->$field));
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Save the new image
                $file = $request->file($field);
                $unique_id = uniqid();
                $name = $unique_id . '.' . $file->getClientOriginalExtension();
                $file->move(public_path("storage/Admin/$field/"), $name);

                // Update the data array with the new image URL
                $data[$field] = "https://rupiyamaker.m-bit.org.in//storage/Admin/$field/$name";
            } else {
                // Retain the existing image URL or set to null if no file is uploaded
                $data[$field] = $imagedata->$field ?? null;
            }
        }

        // Update the database
        DB::table('tbl_lead')->where('admin_id', $request->admin_id)->where('id', $request->lead_id)->update($data);

        // Insert into tbl_lead_status after updating the attachment
        DB::table('tbl_lead_status')->insert([
            'admin_id' => $request->admin_id,
            'lead_id' => $request->lead_id,
            'lead_status' => 'Change Attachment Section', // You can adjust this message as needed
            'date' => $this->date,
        ]);

        return response()->json([
            'success' => 'success',
        ]);
    }

    public function update_attachment(Request $request)
    {
        $imagedata = DB::table('tbl_lead')->where('admin_id', $request->admin_id)->where('id', $request->lead_id)->first();
        $imageFields = [
            'cibil_report_image',
            'passport_image',
            'pan_card_image',
            'aadhar_card_image',
            'salary_3month_image',
            'salary_account_image',
            'bt_loan_image',
            'credit_card_image',
            'electric_bill_image',
            'form_16_image',
            'other_document_image',
        ];

        // Initialize data array
        $data = [
            'all_file_password' => $request->all_file_password,
        ];

        foreach ($imageFields as $field) {
            // Check if the request contains files for the current field
            if ($request->hasFile($field)) {
                $existingFiles = [];

                // If there are existing files in the database, fetch them
                if ($imagedata && !empty($imagedata->$field)) {
                    $existingFiles = explode(',', $imagedata->$field); // Assuming files are stored in the DB as comma-separated
                }

                // Process each uploaded file
                $files = $request->file($field);
                $newFilePaths = [];

                foreach ($files as $file) {
                    $unique_id = uniqid();
                    $name = $unique_id . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path("storage/Admin/$field/"), $name);

                    // Store the new file path
                    $newFilePaths[] = "https://rupiyamaker.m-bit.org.in//storage/Admin/$field/$name";
                }

                // Combine existing and new file paths
                $allFiles = array_merge($existingFiles, $newFilePaths);

                // Update the data array with all the file paths (comma-separated)
                $data[$field] = implode(',', $allFiles);
            } else {
                // Retain the existing files if no new files are uploaded
                $data[$field] = $imagedata->$field ?? null;
            }
        }

        // Update the database
        DB::table('tbl_lead')->where('admin_id', $request->admin_id)->where('id', $request->lead_id)->update($data);

        // Insert into tbl_lead_status after updating the attachment
        DB::table('tbl_lead_status')->insert([
            'admin_id' => $request->admin_id,
            'lead_id' => $request->lead_id,
            'lead_status' => 'Change Attachment Section', // Adjust this message as needed
            'date' => $this->date,
        ]);

        return response()->json([
            'success' => 'success',
        ]);
    }

    // Attachment Delete Single
    public function delete_attachment_single_fileOLD(Request $request)
    {
        if (!empty($request->admin_id) && !empty($request->lead_id) && !empty($request->column_name)) {
            // Fetch the lead record
            $lead = DB::table("tbl_lead")
                ->where("id", $request->lead_id)
                ->where("admin_id", $request->admin_id)
                ->first();
            if (!$lead) {
                return response()->json(['success' => false, 'message' => 'Record not found.']);
            }
            // Delete the file and update the column
            $filePath = $lead->{$request->column_name};
            if ($filePath && \File::exists(public_path($filePath))) {
                \File::delete(public_path($filePath));
            }
            DB::table("tbl_lead")
                ->where("id", $request->lead_id)
                ->update([$request->column_name => null]);
            return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
        }
        return response()->json(['success' => false, 'message' => 'Invalid request parameters.']);
    }

    public function delete_attachment_single_file(Request $request)
    {
        if (!empty($request->admin_id) && !empty($request->lead_id) && !empty($request->column_name) && !empty($request->file_url)) {
            // Fetch the lead record
            $lead = DB::table("tbl_lead")
                ->where("id", $request->lead_id)
                ->where("admin_id", $request->admin_id)
                ->first();

            if (!$lead) {
                return response()->json(['success' => false, 'message' => 'Record not found.']);
            }

            // Get the column data and split it into an array
            $columnData = $lead->{$request->column_name};

            // Check if the column has any files and split the values by comma
            if ($columnData) {
                $files = explode(',', $columnData);
                $files = array_map('trim', $files); // Trim any extra spaces

                // Remove the specified file URL from the array
                $files = array_filter($files, function ($file) use ($request) {
                    return $file !== $request->file_url;
                });

                // Re-index the array and join back into a comma-separated string
                $updatedFiles = implode(',', array_values($files));

                // Update the column with the new file list (or null if no files remain)
                DB::table("tbl_lead")
                    ->where("id", $request->lead_id)
                    ->update([$request->column_name => $updatedFiles ?: null]);

                // Delete the specified file from storage if it exists
                if (\File::exists(public_path($request->file_url))) {
                    \File::delete(public_path($request->file_url));
                }

                return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
            }

            return response()->json(['success' => false, 'message' => 'No files found in the specified column.']);
        }

        return response()->json(['success' => false, 'message' => 'Invalid request parameters.']);
    }

    // Link share from user
    public function showUserProfile($id)
    {
        $data['leads'] = DB::table('tbl_lead')->where('id', $id)->first();
        return view('Admin.pages.user_profile_link', $data);
    }

    // update_loginformlogin
    public function update_loginformlink(Request $request)
    {
        $data = [
            'id' => $request->id,
            'qualification' => $request->qualification,
            'pan_card_no' => $request->pan_card_no,
            'product_id' => $request->product_id,
            'dob' => $request->dob,
            'account_no' => $request->account_no,
            'father_name' => $request->father_name,
            'ifsc_code' => $request->ifsc_code,
            'mother_name' => $request->mother_name,
            'marital_status' => $request->marital_status,
            'spouse_name' => $request->spouse_name,
            'spouse_dob' => $request->spouse_dob,
            'current_address' => $request->current_address,
            'current_address_landmark' => $request->current_address_landmark,
            'current_address_type' => $request->current_address_type,
            'current_address_proof' => $request->current_address_proof,
            'living_current_address_year' => $request->living_current_address_year,
            'living_current_city_year' => $request->living_current_city_year,
            'permanent_address' => $request->permanent_address,
            'permanent_address_landmark' => $request->permanent_address_landmark,
            'company_name' => $request->company_name,
            'designation' => $request->designation,
            'department' => $request->department,
            'current_company' => $request->current_company,
            'current_work_experience' => $request->current_work_experience,
            'total_work_experience' => $request->total_work_experience,
            'personal_email' => $request->personal_email,
            'work_email' => $request->work_email,
            'office_address' => $request->office_address,
            'office_address_landmark' => $request->office_address_landmark,
            'reference_name1' => $request->reference_name1,
            'reference_mobile1' => $request->reference_mobile1,
            'reference_relation1' => $request->reference_relation1,
            'reference_address1' => $request->reference_address1,
            'reference_name2' => $request->reference_name2,
            'reference_mobile2' => $request->reference_mobile2,
            'reference_relation2' => $request->reference_relation2,
            'reference_address2' => $request->reference_address2,
        ];

        $updatedata = DB::table('tbl_lead')->where('id', $request->id)->update($data);
        if ($updatedata) {
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    // changeLeadStatus
    public function changeLeadStatus(Request $request)
    {
        $admin_id = $request->admin_id;
        $id = $request->id;
        $lead_status = $request->lead_status;
        $remark = $request->remark;
        $data = [
            'lead_status' => $lead_status,
            'lead_status_note' => $remark,
        ];
        if (!empty($id)) {
            DB::table("tbl_lead")->where('id', $id)->update($data);
            // Lead status Activity
            DB::table('tbl_lead_status')->insert([
                'admin_id' => $admin_id,
                'lead_id' => $id,
                'lead_status' => $lead_status,
                'date' => $this->date,
            ]);
            // Remark Activity
            DB::table('tbl_remark')->insert([
                'admin_id' => $admin_id,
                'lead_id' => $id,
                'remark' => $remark,
                'date' => $this->date,
            ]);
            return response()->json([
                'success' => 'success',
            ]);
        }
    }

    // Change Login Status Start Here
    public function changeLoginStatus(Request $request)
    {
        $admin_id = $request->admin_id;
        $id = $request->id;
        $login_status = $request->login_status;
        $remark = $request->remark;
        $data = [
            'login_status' => $login_status,
            'lead_status_note' => $remark,
        ];
        if (!empty($id)) {
            DB::table("tbl_lead")->where('id', $id)->update($data);
            // Lead status Activity
            DB::table('tbl_lead_status')->insert([
                'admin_id' => $admin_id,
                'lead_id' => $id,
                'lead_status' => $login_status,
                'date' => $this->date,
            ]);
            // Remark Activity
            DB::table('tbl_remark')->insert([
                'admin_id' => $admin_id,
                'lead_id' => $id,
                'remark' => $remark,
                'date' => $this->date,
            ]);
            return response()->json([
                'success' => 'success',
            ]);
        }
    }
    // Change Login Status End Here

    // changeLostLeadStatus
    public function changeLostLeadStatus(Request $request)
    {
        $admin_id = $request->admin_id;
        $id = $request->id;
        $lost_lead_status = $request->lost_lead_status;
        $lost_lead_status_note = $request->lost_lead_status_note;
        $data = [
            'lost_lead_status' => $lost_lead_status,
            'lost_lead_status_note' => $lost_lead_status_note,
        ];
        if (!empty($id)) {
            DB::table("tbl_lead")->where('id', $id)->update($data);
            return response()->json([
                'success' => 'success',
            ]);
        }
    }

    // onchange company type to company category
    // public function fetchCompanyCategory(Request $request)
    // {
    //     $companyType = $request->input('company_type');
    //     $validTypes  = ['LLP FIRM', 'PARTENERSHIP FIRM', 'PROPRITER'];
    //     if (in_array($companyType, $validTypes)) {
    //         $categories = DB::table('tbl_company_category')
    //             ->select('id', 'company_name', 'company_category', 'company_bank')
    //             ->get();
    //         return response()->json($categories);
    //     } else {
    //         return response()->json([]);
    //     }
    // }

    public function fetchCompanyCategory(Request $request)
    {
        $companyType = $request->input('company_type');
        $validTypes = ['LLP FIRM', 'PARTENERSHIP FIRM', 'PROPRITER']; // JavaScript ke saath match kiya
        if (in_array($companyType, $validTypes)) {
            $categories = DB::table('tbl_company_category')
                ->select('id', 'company_name', 'company_category', 'company_bank')
                ->get();
            return response()->json($categories, 200); // Success response
        }
        return response()->json([], 200); // Empty response agar company type match nahi karta
    }


    // show pl od leads lead status onchange krne pr
    public function filterLeads(Request $request)
    {
        $leadStatus = $request->input('lead_status');
        $leads = DB::table('tbl_lead')
            ->leftJoin('tbl_product', 'tbl_product.id', '=', 'tbl_lead.product_id')
            ->leftJoin('tbl_campaign', 'tbl_campaign.id', '=', 'tbl_lead.campaign_id')
            ->leftJoin('tbl_product_need', 'tbl_product_need.id', '=', 'tbl_lead.product_need_id')
            ->select('tbl_lead.*', 'tbl_product.product_name', 'tbl_campaign.campaign_name', 'tbl_product_need.product_need')
            ->when($leadStatus, function ($query, $leadStatus) {
                return $query->where('lead_status', $leadStatus);
            })
            ->get();

        if ($leads->isNotEmpty()) {
            return response()->json(['data' => $leads]);
        } else {
            return response()->json(['data' => []]);
        }
    }

    // Add Remark Here
    public function addRemarkOLD(Request $request)
    {
        $data = [
            'admin_id' => $request->admin_id,
            'lead_id' => $request->lead_id,
            'remark' => $request->remark,
            'date' => $this->date,
        ];
        if (!empty($data)) {
            $res = DB::table('tbl_remark')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }

    public function addRemark(Request $request)
    {
        $data = [
            'admin_id' => $request->admin_id,
            'lead_id' => $request->lead_id,
            'remark' => $request->remark,
            'date' => $this->date,
        ];

        if (!empty($data)) {
            $res = DB::table('tbl_remark')->insert($data);
            if ($res) {
                DB::table('tbl_lead_status')->insert([
                    'admin_id' => $request->admin_id,
                    'lead_id' => $request->lead_id,
                    'lead_status' => 'Change Remark Added Section',
                    'date' => $this->date,
                ]);

                return response()->json([
                    'success' => 'success',
                ]);
            }
        }
        return response()->json([
            'success' => 'error',
        ]);
    }

    // Add Comment
    public function addCommentOLD(Request $request)
    {
        $data = [
            'admin_id' => $request->admin_id,
            'task_id' => $request->task_id,
            'comment' => $request->comment,
            'date' => $this->date,
        ];
        if (!empty($data)) {
            $res = DB::table('tbl_task_comment')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }

    public function addComment(Request $request)
    {
        $task = DB::table('tbl_task')->where('id', $request->task_id)->first();
        if ($task) {
            $data = [
                'admin_id' => $request->admin_id,
                'task_id' => $request->task_id,
                'comment' => $request->comment,
                'date' => $this->date,
            ];

            // Insert comment into tbl_task_comment
            DB::table('tbl_task_comment')->insert($data);

            // Insert into tbl_lead_status after adding the comment
            DB::table('tbl_lead_status')->insert([
                'admin_id' => $request->admin_id,
                'lead_id' => $task->lead_id,
                'lead_status' => 'Change Comment Added Section',
                'date' => $this->date,
            ]);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }

    // task
    public function taskOLD()
    {
        $tasks = DB::table('tbl_task')
            ->leftJoin('admin', 'admin.id', '=', 'tbl_task.admin_id')
            ->leftJoin('tbl_lead', 'tbl_lead.id', '=', 'tbl_task.lead_id')
            ->select('tbl_task.*', 'tbl_lead.name as lead_name', 'admin.name as createdby')
            ->orderBy('tbl_task.id', 'desc')
            ->get();

        // Map tasks to include assigned admin names
        foreach ($tasks as $task) {
            $adminNames = DB::table('admin')
                ->whereIn('id', explode(',', $task->assign))
                ->pluck('name')
                ->toArray();
            $task->assigned_names = implode(', ', $adminNames);
        }
        // dd($tasks);
        return view('Admin.pages.task', ['tasks' => $tasks]);
    }

    public function task()
    {
        $today = Carbon::today()->toDateString(); // Get today's date in 'YYYY-MM-DD' format

        $tasks = DB::table('tbl_task')
            ->leftJoin('admin', 'admin.id', '=', 'tbl_task.admin_id')
            ->leftJoin('tbl_lead', 'tbl_lead.id', '=', 'tbl_task.lead_id')
            ->select('tbl_task.*', 'tbl_lead.name as lead_name', 'admin.name as createdby')
            ->orderBy('tbl_task.id', 'desc')
            ->get();

        $task_completed = []; // Completed tasks
        $duetoday = []; // Tasks due today
        $upcoming = []; // Upcoming tasks
        $overdues = []; // Overdue tasks

        foreach ($tasks as $task) {
            $adminNames = DB::table('admin')
                ->whereIn('id', explode(',', $task->assign))
                ->pluck('name')
                ->toArray();
            $task->assigned_names = implode(', ', $adminNames);

            $taskDate = Carbon::parse($task->date)->toDateString();

            // Completed tasks
            // if ($task->task_status == 'Completed') {
            if ($task->task_status == 'Close Task') {
                $task_completed[] = $task;
            }
            // Due today
            elseif ($taskDate === $today) {
                $duetoday[] = $task;
            }
            // Upcoming tasks (future tasks)
            elseif ($taskDate < $today) {
                $upcoming[] = $task;
            }
            // Overdue tasks (Past tasks that are not completed)
            // elseif ($taskDate > $today && $task->task_status !== 'Completed') {
            elseif ($taskDate > $today && $task->task_status !== 'Close Task') {
                $overdues[] = $task;
            }
        }

        // Calculate totals
        $total_tasks = count($tasks);          // Total tasks
        $total_task_completed = count($task_completed); // Total completed tasks
        $total_duetoday = count($duetoday);       // Total tasks due today
        $total_upcoming = count($upcoming);       // Total upcoming tasks
        $total_overdues = count($overdues);       // Total overdue tasks

        return view('Admin.pages.task', [
            'tasks' => $tasks,                // All tasks
            'task_completed' => $task_completed,       // Completed tasks
            'duetoday' => $duetoday,             // Tasks due today
            'upcoming' => $upcoming,             // Upcoming tasks
            'overdues' => $overdues,             // Overdue tasks
            'total_tasks' => $total_tasks,          // Total tasks
            'total_duetoday' => $total_duetoday,       // Total tasks due today
            'total_upcoming' => $total_upcoming,       // Total upcoming tasks
            'total_overdues' => $total_overdues,       // Total overdue tasks
            'total_task_completed' => $total_task_completed, // Total completed tasks
        ]);
    }

    // Lead Login Status
    public function LeadLoginStatus(Request $request)
    {
        $lead_login_status = $request->lead_login_status;
        $leads = DB::table('tbl_lead')->select('id', 'name')->where('lead_login_status', $lead_login_status)->get();
        return response()->json($leads);
    }

    // Add Task
    public function addTaskOLD(Request $request)
    {
        $data = [
            'task_status' => 'Open Task',
            'admin_id' => $request->admin_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'task_type' => implode(',', $request->task_type),
            'lead_type' => $request->lead_type,
            'lead_id' => $request->lead_id,
            'date' => date('Y-m-d', strtotime($request->date)),
            'time' => date('h:i A', strtotime($request->time)),
            'assign' => implode(',', $request->assign),
        ];

        if (!empty($data)) {
            $task_id = DB::table('tbl_task')->insertGetId($data);
            if ($task_id) {
                $historyData = [
                    'task_id' => $task_id,
                    'admin_id' => $request->admin_id,
                    'lead_id' => $request->lead_id,
                    'changes' => 'Open Task',
                    'date' => $this->date,
                ];
                // Insert into tbl_task_history
                DB::table('tbl_task_history')->insert($historyData);
                return response()->json([
                    'success' => 'success',
                ]);
            }
        } else {
        }
    }
    // Update Task
    public function updateTaskOLD(Request $request)
    {
        $data = [
            'id' => $request->task_id,
            'admin_id' => $request->admin_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'task_type' => $request->task_type,
            'lead_type' => $request->lead_type,
            'lead_id' => $request->lead_id,
            'date' => date('Y-m-d', strtotime($request->date)),
            'time' => date('h:i A', strtotime($request->time)),
            'assign' => implode(',', $request->assign),
        ];

        if (!empty($data)) {
            $res = DB::table('tbl_task')->where('id', $request->task_id)->update($data);
            if ($res) {
                $historyData = [
                    'task_id' => $request->task_id,
                    'admin_id' => $request->admin_id,
                    'lead_id' => $request->lead_id,
                    'changes' => 'Changes Task',
                    'date' => $this->date,
                ];
                // Insert into tbl_task_history
                DB::table('tbl_task_history')->insert($historyData);
                return response()->json([
                    'success' => 'success',
                ]);
            }
        } else {
        }
    }

    public function addTask(Request $request)
    {
        $data = [
            'task_status' => 'Open Task',
            'admin_id' => $request->admin_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'task_type' => implode(',', $request->task_type),
            'lead_type' => $request->lead_type,
            'lead_id' => $request->lead_id,
            'date' => date('Y-m-d', strtotime($request->date)),
            'time' => date('h:i A', strtotime($request->time)),
            'assign' => implode(',', $request->assign),
        ];

        if (!empty($data)) {
            $task_id = DB::table('tbl_task')->insertGetId($data);
            if ($task_id) {
                $historyData = [
                    'task_id' => $task_id,
                    'admin_id' => $request->admin_id,
                    'lead_id' => $request->lead_id,
                    'changes' => 'Open Task',
                    'date' => $this->date,
                ];
                // Insert into tbl_task_history
                DB::table('tbl_task_history')->insert($historyData);

                // Insert into tbl_lead_status after task is created
                DB::table('tbl_lead_status')->insert([
                    'admin_id' => $request->admin_id,
                    'lead_id' => $request->lead_id,
                    'lead_status' => 'Change Add Task Section',
                    'date' => $this->date,
                ]);

                return response()->json([
                    'success' => 'success',
                ]);
            }
        } else {
        }
    }

    public function updateTask(Request $request)
    {
        $data = [
            'id' => $request->task_id,
            'admin_id' => $request->admin_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'task_type' => $request->task_type,
            'lead_type' => $request->lead_type,
            'lead_id' => $request->lead_id,
            'date' => date('Y-m-d', strtotime($request->date)),
            'time' => date('h:i A', strtotime($request->time)),
            'assign' => implode(',', $request->assign),
        ];

        if (!empty($data)) {
            $res = DB::table('tbl_task')->where('id', $request->task_id)->update($data);
            if ($res) {
                $historyData = [
                    'task_id' => $request->task_id,
                    'admin_id' => $request->admin_id,
                    'lead_id' => $request->lead_id,
                    'changes' => 'Changes Task',
                    'date' => $this->date,
                ];
                // Insert into tbl_task_history
                DB::table('tbl_task_history')->insert($historyData);

                // Insert into tbl_lead_status after task is updated
                DB::table('tbl_lead_status')->insert([
                    'admin_id' => $request->admin_id,
                    'lead_id' => $request->lead_id,
                    'lead_status' => 'Change Task Updated Section',
                    'date' => $this->date,
                ]);

                return response()->json([
                    'success' => 'success',
                ]);
            }
        } else {
        }
    }

    // copyThisLead
    public function copyThisLead(Request $request)
    {
        $leadId = $request->input('lead_id');
        $lead = DB::table('tbl_lead')->where('id', $leadId)->first();
        if ($lead) {
            $leadData = (array) $lead;
            unset($leadData['id']);
            $newLeadId = DB::table('tbl_lead')->insertGetId($leadData);
            return response()->json(['status' => 'success', 'message' => 'Lead copied successfully']);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Lead not found']);
        }
    }

    // Ticket Show Start Here
    public function ticket()
    {
        $tickets = DB::table('tbl_ticket')
            ->leftJoin('admin', 'admin.id', '=', 'tbl_ticket.admin_id')
            ->select('tbl_ticket.*', 'admin.name as createdby')
            ->orderBy('tbl_ticket.id', 'desc')
            ->get();

        $openTickets = []; // Tickets with status "Open Ticket"
        $closedTickets = []; // Tickets with status "Close Ticket"

        foreach ($tickets as $ticket) {
            // Fetch assigned admin names
            $adminNames = DB::table('admin')
                ->whereIn('id', explode(',', $ticket->assign))
                ->pluck('name')
                ->toArray();
            $ticket->assigned_names = implode(', ', $adminNames);

            // Categorize tickets based on status
            // if ($ticket->task_status === 'Open Ticket') {
            if ($ticket->task_status === 'Open Ticket') {
                $openTickets[] = $ticket;
            } elseif ($ticket->task_status === 'Close Ticket') {
                $closedTickets[] = $ticket;
            }
        }

        // Calculate totals
        $totalTickets = count($tickets);       // Total number of tickets
        $totalOpenTickets = count($openTickets);   // Total number of open tickets
        $totalClosedTickets = count($closedTickets); // Total number of closed tickets

        return view('Admin.pages.ticket', [
            'tickets' => $tickets,            // All tickets
            'open_tickets' => $openTickets,        // Open Tickets
            'closed_tickets' => $closedTickets,      // Closed Tickets
            'totalTickets' => $totalTickets,       // Total tickets
            'totalOpenTickets' => $totalOpenTickets,   // Total open tickets
            'totalClosedTickets' => $totalClosedTickets, // Total closed tickets
        ]);
    }

    // Add Ticket Here
    public function addTicket(Request $request)
    {
        $kolkataDateTime = Carbon::now('Asia/Kolkata');
        $date = $kolkataDateTime->format('Y-m-d');
        $time = $kolkataDateTime->format('h:i A');

        $data = [
            'task_status' => 'Open Ticket',
            'admin_id' => $request->admin_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'date' => $date,
            'time' => $time,
            'assign' => implode(',', $request->assign)
        ];

        if (!empty($data)) {
            $ticket_id = DB::table('tbl_ticket')->insertGetId($data);
            if ($ticket_id) {
                $historyData = [
                    'ticket_id' => $ticket_id,
                    'admin_id' => $request->admin_id,
                    'changes' => 'Ticket Created',
                    'date' => $this->date,
                ];
                // Insert into tbl_task_history
                DB::table('tbl_ticket_history')->insert($historyData);
                return response()->json([
                    'success' => 'success',
                ]);
            }
        } else {
        }
    }

    // Get Ticket Status
    public function getTicketStatus(Request $request)
    {
        $ticket = DB::table('tbl_ticket')->where('id', $request->ticket_id)->first();
        return response()->json(['task_status' => $ticket->task_status]);
    }

    // getTicketHistory
    public function getTicketHistory(Request $request)
    {
        $ticketId = $request->ticket_id;
        $history = DB::table('tbl_ticket_history')
            ->where('ticket_id', $ticketId)
            ->leftJoin('admin', 'tbl_ticket_history.admin_id', '=', 'admin.id') // Assuming 'admins' table exists
            ->select('tbl_ticket_history.*', 'admin.name as createdby')
            ->orderBy('tbl_ticket_history.id', 'asc')
            ->get();
        return response()->json($history);
    }

    // Ticket Comment Here in Task Tab
    public function getTicketComments(Request $request)
    {
        $ticketId = $request->ticket_id;
        $ticket_comments = DB::table('tbl_ticket_comment')
            ->where('ticket_id', $ticketId)
            ->leftJoin('admin', 'tbl_ticket_comment.admin_id', '=', 'admin.id') // Assuming 'admins' table exists
            ->select('tbl_ticket_comment.*', 'admin.name as createdby')
            ->orderBy('tbl_ticket_comment.id', 'asc')
            ->get();
        // dd($ticket_comments);
        return response()->json($ticket_comments);
    }

    // Add Ticket Comment
    public function addTicketComment(Request $request)
    {
        $ticket = DB::table('tbl_ticket')->where('id', $request->ticket_id)->first();
        if ($ticket) {
            $data = [
                'admin_id' => $request->admin_id,
                'ticket_id' => $request->ticket_id,
                'comment' => $request->comment,
                'date' => $this->date,
            ];
            // Insert comment into tbl_task_comment
            DB::table('tbl_ticket_comment')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }

    // Update Ticket Start Here
    public function updateTicket(Request $request)
    {
        $data = [
            'id' => $request->ticket_id,
            'admin_id' => $request->admin_id,
            'subject' => $request->subject,
            'message' => $request->message,
            'assign' => implode(',', $request->assign),
        ];

        if (!empty($data)) {
            $res = DB::table('tbl_ticket')->where('id', $request->ticket_id)->update($data);
            if ($res) {
                $historyData = [
                    'ticket_id' => $request->ticket_id,
                    'admin_id' => $request->admin_id,
                    'changes' => 'Changes Ticket',
                    'date' => $this->date,
                ];
                // Insert into tbl_task_history
                DB::table('tbl_ticket_history')->insert($historyData);
                return response()->json([
                    'success' => 'success',
                ]);
            }
        } else {
        }
    }
    // Update Ticket End Here

    // updateTicketStatus
    public function updateTicketStatus(Request $request)
    {
        $ticket_id = $request->ticket_id;
        $admin_id = $request->admin_id;
        $new_status = $request->task_status;
        if ($new_status === 'Close Ticket') {
            $ticket_status = 'Close Ticket';
            $ticket_history_status = 'Ticket Closed';
        } else {
            $ticket_status = 'Open Ticket';
            $ticket_history_status = 'Reopen';
        }
        // dd($new_status);

        $ticket = DB::table('tbl_ticket')->where('id', $ticket_id)->first();
        $data = [
            // 'task_status' => $new_status,
            'task_status' => $ticket_status,
            'admin_id' => $admin_id,
        ];

        if (!empty($data)) {
            $update = DB::table('tbl_ticket')->where('id', $ticket->id)->update($data);
            $historyData = [
                'admin_id' => $admin_id,
                'ticket_id' => $ticket_id,
                'changes' => $ticket_history_status,
                // 'changes'  => $new_status,
                'date' => $this->date,
            ];
            DB::table('tbl_ticket_history')->insert($historyData);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }


    // warning check warning form 
    public function issuedTotalWarning(Request $request)
    {
        $warningTypeId = $request->warningtype_id;
        $issuedTo = $request->issued_to;
        // Fetch warnings with warning type
        $warnings = DB::table('tbl_warning')
            ->leftJoin('tbl_warning_type', 'tbl_warning.warningtype_id', '=', 'tbl_warning_type.id')
            ->leftJoin('admin as assign_admin', 'tbl_warning.assign', '=', 'assign_admin.id') // Assign ke liye
            ->leftJoin('admin as issued_admin', 'tbl_warning.admin_id', '=', 'issued_admin.id') // Admin_id ke liye
            ->where('tbl_warning.warningtype_id', $warningTypeId)
            ->where('tbl_warning.assign', $issuedTo)
            ->select(
                'tbl_warning.*',
                'tbl_warning_type.warning_name',
                'assign_admin.name as assign_name',
                'issued_admin.name as issued_by_name'
            )
            ->get();
        // Group by warning_name
        $groupedWarnings = $warnings->groupBy('warning_name');
        // Format response
        $formattedWarnings = [];
        foreach ($groupedWarnings as $warningName => $warningList) {
            $formattedWarnings[$warningName] = [];
            foreach ($warningList as $warning) {
                // Null handling
                $assignName = $warning->assign_name ?? 'Unknown';
                $issuedByName = $warning->issued_by_name ?? 'Unknown';
                $warningDate = !empty($warning->date) ? date('m/d/Y', strtotime($warning->date)) : 'N/A';
                $penaltyAmount = $warning->penalty ?? 0;
                // Format output
                $formattedWarnings[$warningName][] = [
                    'id' => $warning->id,
                    'assign' => "{$assignName} - {$warningDate} - {$issuedByName} - ₹{$penaltyAmount} - {$warning->message}"
                ];
            }
        }
        return response()->json([
            'status' => 1,
            'warnings' => $formattedWarnings
        ]);
    }

    ########################## Warning Here ##########################
    // warning Show Start Here
    public function warning000()
    {
        $warnings = DB::table('tbl_warning')
            ->leftJoin('admin', 'admin.id', '=', 'tbl_warning.admin_id')
            ->leftJoin('tbl_warning_type', 'tbl_warning_type.id', '=', 'tbl_warning.warningtype_id')
            ->select('tbl_warning.*', 'admin.name as createdby', 'tbl_warning_type.warning_name')
            ->orderBy('tbl_warning.id', 'desc')
            ->get();
        foreach ($warnings as $warning) {
            if (!empty($warning->assign)) {
                $adminNames = DB::table('admin')
                    ->whereIn('id', explode(',', $warning->assign))
                    ->pluck('name')
                    ->toArray();
                $warning->assigned_names = implode(', ', $adminNames);
            } else {
                $warning->assigned_names = '';
            }
        }
        return view('Admin.pages.warning', [
            'warnings' => $warnings,
        ]);
    }

    public function Deletewarning00(Request $request)
    {
        try {
            if (!empty($request->id)) {
                $delete_data = DB::table("tbl_warning")->where("id", $request->id)->delete();
                return response()->json(['success' => 'success']);
            } else {
                return response()->json(['success' => 'error', 'message' => 'ID is required']);
            }
        } catch (\Exception $e) {
            \Log::error('Delete warning error: ' . $e->getMessage());
            return response()->json(['success' => 'error', 'message' => 'Database error']);
        }
    }



    public function Getwarning()
    {
        $warnings = DB::table('tbl_warning')
            ->leftJoin('admin', 'admin.id', '=', 'tbl_warning.admin_id')
            ->leftJoin('tbl_warning_type', 'tbl_warning_type.id', '=', 'tbl_warning.warningtype_id')
            ->select('tbl_warning.*', 'admin.name as createdby', 'tbl_warning_type.warning_name')
            ->orderBy('tbl_warning.id', 'desc')
            ->get();
        foreach ($warnings as $warning) {
            if (!empty($warning->assign)) {
                $adminNames = DB::table('admin')
                    ->whereIn('id', explode(',', $warning->assign))
                    ->pluck('name')
                    ->toArray();
                $warning->assigned_names = implode(', ', $adminNames);
            } else {
                $warning->assigned_names = '';
            }
        }
        return view('Admin.pages.getwarning', [
            'warnings' => $warnings,
        ]);
    }



    // public function warning()
    // {

    //     $adminSession = collect(session()->get('admin_login'))->first();

    //     if (!$adminSession) {
    //         return redirect('/login');
    //     }

    //     $admin_id = $adminSession->id;
    //     $admin_role = strtolower($adminSession->role);

    //     // Get My Warnings (for all roles)
    //     $myWarnings = DB::table('tbl_warning')
    //         ->join('tbl_warning_type', 'tbl_warning.warningtype_id', '=', 'tbl_warning_type.id')
    //         ->leftJoin('admin', 'tbl_warning.assign', '=', 'admin.id')
    //         ->leftJoin('admin as issued_admin', 'tbl_warning.admin_id', '=', 'issued_admin.id')
    //         ->select(
    //             'tbl_warning.*', 
    //             'tbl_warning_type.warning_name', 
    //             'admin.name as assign_name', 
    //             'issued_admin.name as issued_by_name'
    //         )
    //         ->where('tbl_warning.assign', $admin_id)
    //         ->get();

    //     // Get warnings by type for the user
    //     $myWarningsByType = $myWarnings->groupBy('warningtype_id');

    //     // Initialize empty data for different tabs
    //     $all_warnings = collect();
    //     $team_warnings = collect();
    //     $team_members = [];
    //     $warning_types = DB::table('tbl_warning_type')->get();

    //     // Admin & HR get access to all warnings
    //     if ($admin_role === 'admin' || $admin_role === 'hr') {
    //         $all_warnings = DB::table('tbl_warning')
    //             ->join('admin as warned_admin', 'tbl_warning.assign', '=', 'warned_admin.id')
    //             ->join('admin as created_by', 'tbl_warning.admin_id', '=', 'created_by.id')
    //             ->join('tbl_warning_type', 'tbl_warning.warningtype_id', '=', 'tbl_warning_type.id')
    //             ->select(
    //                 'tbl_warning.*',
    //                 'created_by.name as createdby',
    //                 'warned_admin.name as warned_to',
    //                 'tbl_warning_type.warning_name'
    //             )
    //             ->orderBy('tbl_warning.id', 'desc')
    //             ->get();

    //         // Get warning counts by type for dashboard
    //         $warningCounts = DB::table('tbl_warning')
    //             ->join('tbl_warning_type', 'tbl_warning.warningtype_id', '=', 'tbl_warning_type.id')
    //             ->select('tbl_warning_type.warning_name', DB::raw('count(*) as total'))
    //             ->groupBy('tbl_warning_type.warning_name')
    //             ->orderBy('total', 'desc')
    //             ->limit(5)
    //             ->get();

    //     } elseif ($admin_role === 'manager' || $admin_role === 'tl') {
    //         // Get team members
    //         if ($admin_role === 'manager') {
    //             $team_members_query = DB::table('admin')
    //                 ->where('manager', $admin_id)
    //                 ->pluck('name', 'id');
    //         } else { // TL
    //             $team_members_query = DB::table('admin')
    //                 ->where('team_leader', $admin_id)
    //                 ->pluck('name', 'id');
    //         }

    //         $team_members = $team_members_query->toArray();
    //         $team_member_ids = array_keys($team_members);

    //         // Get warnings for team members
    //         if (!empty($team_member_ids)) {
    //             $team_warnings = DB::table('tbl_warning')
    //                 ->join('admin as warned_admin', 'tbl_warning.assign', '=', 'warned_admin.id')
    //                 ->join('admin as created_by', 'tbl_warning.admin_id', '=', 'created_by.id')
    //                 ->join('tbl_warning_type', 'tbl_warning.warningtype_id', '=', 'tbl_warning_type.id')
    //                 ->select(
    //                     'tbl_warning.*',
    //                     'created_by.name as createdby',
    //                     'warned_admin.name as warned_to',
    //                     'tbl_warning_type.warning_name'
    //                 )
    //                 ->whereIn('tbl_warning.assign', $team_member_ids)
    //                 ->orderBy('tbl_warning.id', 'desc')
    //                 ->get();
    //         }
    //     }

    //     // Format the data to be returned to the view
    //     return view('Admin.pages.warning', [
    //         'admin_login' => $adminSession,
    //         'all_warnings' => $all_warnings,
    //         'team_warnings' => $team_warnings,
    //         'team_members' => $team_members,
    //         'myWarnings' => $myWarningsByType,
    //         'warning_types' => $warning_types,
    //         'warningCounts' => $warningCounts ?? collect(), // For admin dashboard
    //     ]);
    // }



    public function warning()
    {
        $adminSession = collect(session()->get('admin_login'))->first();

        if (!$adminSession) {
            return redirect('/login');
        }

        $admin_id = $adminSession->id;
        $admin_role = strtolower($adminSession->role);

        // Get My Warnings (for non-admin roles)
        $myWarnings = collect();
        $myWarningsByType = collect();

        if ($admin_role !== 'admin') {
            $myWarnings = DB::table('tbl_warning')
                ->join('tbl_warning_type', 'tbl_warning.warningtype_id', '=', 'tbl_warning_type.id')
                ->leftJoin('admin', 'tbl_warning.assign', '=', 'admin.id')
                ->leftJoin('admin as issued_admin', 'tbl_warning.admin_id', '=', 'issued_admin.id')
                ->select(
                    'tbl_warning.*',
                    'tbl_warning_type.warning_name',
                    'admin.name as assign_name',
                    'issued_admin.name as issued_by_name'
                )
                ->where('tbl_warning.assign', $admin_id)
                ->get();

            // Get warnings by type for the user
            $myWarningsByType = $myWarnings->groupBy('warningtype_id');
        }

        # // Initialize empty data for different tabs
        $all_warnings = collect();
        $team_warnings = collect();
        $team_members = [];
        $warning_types = DB::table('tbl_warning_type')->get();
        $warningCounts = collect();

        # // Admin & HR get access to all warnings
        if ($admin_role === 'admin' || $admin_role === 'hr') {
            $all_warnings = DB::table('tbl_warning')
                ->join('admin as warned_admin', 'tbl_warning.assign', '=', 'warned_admin.id')
                ->join('admin as created_by', 'tbl_warning.admin_id', '=', 'created_by.id')
                ->join('tbl_warning_type', 'tbl_warning.warningtype_id', '=', 'tbl_warning_type.id')
                ->select(
                    'tbl_warning.*',
                    'created_by.name as createdby',
                    'warned_admin.name as warned_to',
                    'tbl_warning_type.warning_name'
                )
                ->orderBy('tbl_warning.id', 'desc')
                ->get();

            # // Get warning counts by type for dashboard
            $warningCounts = DB::table('tbl_warning')
                ->join('tbl_warning_type', 'tbl_warning.warningtype_id', '=', 'tbl_warning_type.id')
                ->select('tbl_warning_type.id', 'tbl_warning_type.warning_name', DB::raw('count(*) as total'))
                ->groupBy('tbl_warning_type.id', 'tbl_warning_type.warning_name')
                ->orderBy('total', 'desc')
                ->get();

        } elseif ($admin_role === 'manager' || $admin_role === 'tl') {
            # // Get team members
            if ($admin_role === 'manager') {
                $team_members_query = DB::table('admin')
                    ->where('manager', $admin_id)
                    ->pluck('name', 'id');
            } else {
                # // TL
                $team_members_query = DB::table('admin')
                    ->where('team_leader', $admin_id)
                    ->pluck('name', 'id');
            }

            $team_members = $team_members_query->toArray();
            $team_member_ids = array_keys($team_members);

            # // Get warnings for team members
            if (!empty($team_member_ids)) {
                $team_warnings = DB::table('tbl_warning')
                    ->join('admin as warned_admin', 'tbl_warning.assign', '=', 'warned_admin.id')
                    ->join('admin as created_by', 'tbl_warning.admin_id', '=', 'created_by.id')
                    ->join('tbl_warning_type', 'tbl_warning.warningtype_id', '=', 'tbl_warning_type.id')
                    ->select(
                        'tbl_warning.*',
                        'created_by.name as createdby',
                        'warned_admin.name as warned_to',
                        'tbl_warning_type.warning_name'
                    )
                    ->whereIn('tbl_warning.assign', $team_member_ids)
                    ->orderBy('tbl_warning.id', 'desc')
                    ->get();

                # // Get warning counts for dashboard - including both team and personal warnings
                $warningCounts = DB::table('tbl_warning')
                    ->join('tbl_warning_type', 'tbl_warning.warningtype_id', '=', 'tbl_warning_type.id')
                    ->where(function ($query) use ($team_member_ids, $admin_id) {
                        $query->whereIn('tbl_warning.assign', $team_member_ids)
                            ->orWhere('tbl_warning.assign', $admin_id);
                    })
                    ->select('tbl_warning_type.id', 'tbl_warning_type.warning_name', DB::raw('count(*) as total'))
                    ->groupBy('tbl_warning_type.id', 'tbl_warning_type.warning_name')
                    ->orderBy('total', 'desc')
                    ->get();
            }
        } elseif ($admin_role === 'agent') {
            # // For agent, just get their warning counts for dashboard
            $warningCounts = DB::table('tbl_warning')
                ->join('tbl_warning_type', 'tbl_warning.warningtype_id', '=', 'tbl_warning_type.id')
                ->where('tbl_warning.assign', $admin_id)
                ->select('tbl_warning_type.id', 'tbl_warning_type.warning_name', DB::raw('count(*) as total'))
                ->groupBy('tbl_warning_type.id', 'tbl_warning_type.warning_name')
                ->orderBy('total', 'desc')
                ->get();
        }

        # // Get all employees for filter
        $all_employees = DB::table('admin')
            ->where('role', '!=', 'Admin')
            ->orderBy('name')
            ->get();

        # // Format the data to be returned to the view
        return view('Admin.pages.warning', [
            'admin_login' => $adminSession,
            'all_warnings' => $all_warnings,
            'team_warnings' => $team_warnings,
            'team_members' => $team_members,
            'myWarnings' => $myWarningsByType,
            'warning_types' => $warning_types,
            'warningCounts' => $warningCounts,
            'all_employees' => $all_employees,
        ]);
    }

    public function getFilteredWarningCounts(Request $request)
    {
        $filter = $request->filter;
        $adminSession = collect(session()->get('admin_login'))->first();
        $admin_id = $adminSession->id;
        $admin_role = strtolower($adminSession->role);

        $query = DB::table('tbl_warning')
            ->join('tbl_warning_type', 'tbl_warning.warningtype_id', '=', 'tbl_warning_type.id');

        if ($filter !== 'all' && is_array($filter)) {
            $query->whereIn('tbl_warning.assign', $filter);
        } elseif ($admin_role === 'manager' || $admin_role === 'tl') {
            # // For managers/TLs, filter by team members and self by default
            $team_member_ids = [];

            if ($admin_role === 'manager') {
                $team_member_ids = DB::table('admin')
                    ->where('manager', $admin_id)
                    ->pluck('id')
                    ->toArray();
            } else {
                #  // TL
                $team_member_ids = DB::table('admin')
                    ->where('team_leader', $admin_id)
                    ->pluck('id')
                    ->toArray();
            }

            $query->where(function ($q) use ($team_member_ids, $admin_id) {
                $q->whereIn('tbl_warning.assign', $team_member_ids)
                    ->orWhere('tbl_warning.assign', $admin_id);
            });
        }

        # // Get total count
        $total = $query->count();

        # // Get counts by warning type
        $typeCounts = DB::table('tbl_warning')
            ->join('tbl_warning_type', 'tbl_warning.warningtype_id', '=', 'tbl_warning_type.id');

        if ($filter !== 'all' && is_array($filter)) {
            $typeCounts->whereIn('tbl_warning.assign', $filter);
        } elseif ($admin_role === 'manager' || $admin_role === 'tl') {
            # // For managers/TLs, filter by team members and self by default
            $team_member_ids = [];

            if ($admin_role === 'manager') {
                $team_member_ids = DB::table('admin')
                    ->where('manager', $admin_id)
                    ->pluck('id')
                    ->toArray();
            } else {
                # // TL
                $team_member_ids = DB::table('admin')
                    ->where('team_leader', $admin_id)
                    ->pluck('id')
                    ->toArray();
            }

            $typeCounts->where(function ($q) use ($team_member_ids, $admin_id) {
                $q->whereIn('tbl_warning.assign', $team_member_ids)
                    ->orWhere('tbl_warning.assign', $admin_id);
            });
        }

        $typeCountsResult = $typeCounts
            ->select('tbl_warning_type.id', DB::raw('count(*) as total'))
            ->groupBy('tbl_warning_type.id')
            ->pluck('total', 'id')
            ->toArray();

        return response()->json([
            'total' => $total,
            'type_counts' => $typeCountsResult
        ]);
    }












    // Add Warning Here
    public function addWarning(Request $request)
    {
        $data = [
            'task_status' => 'Open Ticket',
            'admin_id' => $request->admin_id,
            'warningtype_id' => $request->warningtype_id,
            'penalty' => $request->penalty,
            'message' => $request->message,
            'assign' => $request->assign,
            'date' => $this->date
        ];

        if (!empty($data)) {
            $warning_id = DB::table('tbl_warning')->insertGetId($data);
            if ($warning_id) {
                $historyData = [
                    'warning_id' => $warning_id,
                    'admin_id' => $request->admin_id,
                    'changes' => 'Warning Created',
                    'date' => $this->date,
                ];
                // Insert into tbl_task_history
                DB::table('tbl_warning_history')->insert($historyData);
                return response()->json([
                    'success' => 'success',
                ]);
            }
        } else {
        }
    }

    // Get Warning Status
    public function getWarningStatus(Request $request)
    {
        $ticket = DB::table('tbl_warning')->where('id', $request->ticket_id)->first();
        return response()->json(['task_status' => $ticket->task_status]);
    }

    // getWarningHistory
    public function getWarningHistory(Request $request)
    {
        $warningId = $request->warning_id;
        $history = DB::table('tbl_warning_history')
            ->where('warning_id', $warningId)
            ->leftJoin('admin', 'tbl_warning_history.admin_id', '=', 'admin.id') // Assuming 'admins' table exists
            ->select('tbl_warning_history.*', 'admin.name as createdby')
            ->orderBy('tbl_warning_history.id', 'asc')
            ->get();
        return response()->json($history);
    }

    // Warning Comment Here in Task Tab
    public function getWarningComments(Request $request)
    {
        $warningId = $request->warning_id;
        $warning_comments = DB::table('tbl_warning_comment')
            ->where('warning_id', $warningId)
            ->leftJoin('admin', 'tbl_warning_comment.admin_id', '=', 'admin.id') // Assuming 'admins' table exists
            ->select('tbl_warning_comment.*', 'admin.name as createdby')
            ->orderBy('tbl_warning_comment.id', 'asc')
            ->get();
        return response()->json($warning_comments);
    }

    // Add Warning Comment
    public function addWarningComment(Request $request)
    {
        $warning = DB::table('tbl_warning')->where('id', $request->warning_id)->first();
        if ($warning) {
            $data = [
                'admin_id' => $request->admin_id,
                'warning_id' => $request->warning_id,
                'comment' => $request->comment,
                'date' => $this->date,
            ];
            // Insert comment into tbl_warning_comment
            DB::table('tbl_warning_comment')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }

    // Update Warning Start Here
    public function updateWarning(Request $request)
    {
        $data = [
            'id' => $request->warning_id,
            'admin_id' => $request->admin_id,
            'warningtype_id' => $request->warningtype_id,
            'message' => $request->message,
            'assign' => implode(',', $request->assign),
        ];

        if (!empty($data)) {
            $res = DB::table('tbl_warning')->where('id', $request->warning_id)->update($data);
            if ($res) {
                $historyData = [
                    'warning_id' => $request->warning_id,
                    'admin_id' => $request->admin_id,
                    'changes' => 'Changes Warning',
                    'date' => $this->date,
                ];
                // Insert into tbl_warning_history
                DB::table('tbl_warning_history')->insert($historyData);
                return response()->json([
                    'success' => 'success',
                ]);
            }
        } else {
        }
    }
    // Update Ticket End Here

    // deleteWarning delete here
    public function deleteWarning(Request $request)
    {
        if (!empty($request->id)) {
            $delete_data = DB::table("tbl_warning")->where("id", $request->id)->delete();
            return response()->json(['success' => 'success']);
        } else {
        }
    }


    ################# Add Employee Start Here #################
    public function Employee()
    {
        return view('Admin.pages.add_employee');
    }

    // checkmobile for employee
    public function checkMobileEmployeeExistence(Request $request)
    {
        $mobile = $request->mobile;
        $alternate_mobile = $request->alternate_mobile;

        $existingMobile = DB::table('admin')
            ->where('mobile', $mobile)
            ->orWhere('alternate_mobile', $mobile)
            ->exists();

        $existingAlternateMobile = DB::table('admin')
            ->where('mobile', $alternate_mobile)
            ->orWhere('alternate_mobile', $alternate_mobile)
            ->exists();

        // Return a response indicating which field already exists
        if ($existingMobile) {
            return response()->json(['exists' => true, 'message' => 'Mobile Number Already Exists', 'field' => 'mobile']);
        } elseif ($existingAlternateMobile) {
            return response()->json(['exists' => true, 'message' => 'Alternate Mobile Number Already Exists', 'field' => 'alternate_mobile']);
        }
        return response()->json(['exists' => false]);
    }

    // Add Employee
    public function addEmployee(Request $request)
    {
        $path1 = "";
        if ($request->hasFile('image')) {
            $file = $request->file("image");
            $uniqid = uniqid();
            $name = $uniqid . "." . $file->getClientOriginalExtension();
            $request->image->move(public_path('storage/Admin/employee'), $name);
            $path1 = "https://rupiyamaker.m-bit.org.in/storage/Admin/employee/$name";
        }

        $data = [
            'created_by' => $request->created_by,
            'department' => $request->department,
            'role' => $request->role,
            'team' => $request->team,
            'manager' => $request->manager,
            'team_leader' => $request->team_leader,
            'name' => $request->name,
            'email' => $request->personal_email,
            'password' => '123456',
            'mobile' => $request->mobile,
            'alternate_mobile' => $request->alternate_mobile,
            'gender' => $request->gender,
            'personal_email' => $request->personal_email,
            'official_email' => $request->official_email,
            'pan_no' => $request->pan_no,
            'aadhar_no' => $request->aadhar_no,
            'dob' => $request->dob,
            'experience' => $request->experience,
            'experience_in_years' => $request->experience_in_years,
            'qualification' => $request->qualification,
            'permanent_address' => $request->permanent_address,
            'current_address' => $request->current_address,
            'emergency_name1' => $request->emergency_name1,
            'emergency_mobile1' => $request->emergency_mobile1,
            'emergency_relation1' => $request->emergency_relation1,
            'emergency_name2' => $request->emergency_name2,
            'emergency_mobile2' => $request->emergency_mobile2,
            'emergency_relation2' => $request->emergency_relation2,
            'employee_id' => $request->employee_id,
            'joining_date' => $request->joining_date,
            'salary' => $request->salary,
            'monthly_salary_target' => $request->monthly_salary_target,
            'salary_bank' => $request->salary_bank,
            'salary_account_no' => $request->salary_account_no,
            'ifsc_code' => $request->ifsc_code,
            'status' => '1',
            'image' => $path1
        ];

        if (!empty($data)) {
            DB::table('admin')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        }
    }

    // select manager 
    public function getManagersByTeam(Request $request)
    {
        $team = $request->input('team');
        $managers = DB::table('admin')
            ->select('id', 'name')
            ->where('team', $team)
            ->where('role', 'Manager')
            ->get();
        return response()->json($managers);
    }


    public function getTeamLeaders(Request $request)
    {
        $team = $request->team;
        $manager = $request->manager;

        $teamLeaders = DB::table("admin")->where('team', $team)
            ->where('manager', $manager)
            ->where('role', 'TL')
            ->get();
        return response()->json($teamLeaders);
    }

    ### showEmployee ###
    public function showEmployee(Request $request)
    {
        return view('Admin.pages.show_employee');
    }

    // Show All Active Inactive Employee 
    public function fetchEmployee(Request $request)
    {
        $status = $request->status; // 1 for Active, 0 for Inactive
        $search = $request->search; // Search keyword

        // Query Admin Data excluding role 'Admin'
        $query = DB::table('admin')
            ->where('status', $status)
            ->where('role', '!=', 'Admin'); // Exclude Admin role

        // Apply search filter if search keyword exists
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%')
                    ->orWhere('mobile', 'like', '%' . $search . '%')
                    ->orWhere('employee_id', 'like', '%' . $search . '%');
            });
        }

        // Pagination
        $admins = $query->orderBy('id', 'desc')->paginate(2);

        // Count Active & Inactive Users excluding Admin role
        $total_active = DB::table('admin')->where('status', 1)->where('role', '!=', 'Admin')->count();
        $total_inactive = DB::table('admin')->where('status', 0)->where('role', '!=', 'Admin')->count();

        return response()->json([
            'data' => $admins->items(),
            'current_page' => $admins->currentPage(),
            'last_page' => $admins->lastPage(),
            'per_page' => $admins->perPage(),
            'total' => $admins->total(),
            'total_active' => $total_active,
            'total_inactive' => $total_inactive,
            'success' => 'success',
        ]);
    }


    public function EmployeeProfile($id)
    {
        $data['employee_profile'] = DB::table('admin')->where('id', $id)->first();  // employee profile details 
        // Fetch lead status data for this admin
        $data['lead_activity'] = DB::table('tbl_employee_status')->where('admin_id', $id)->get();
        return view('Admin.pages.employee_profile', $data);
    }

    // show data remar,activity,employee details
    public function showData1(Request $request)
    {
        $search = $request->search;
        $admin_id = $request->admin_id;

        // Fetch employee remarks with admin name
        $remarks = DB::table('tbl_employee_remark')
            ->leftJoin('admin', 'tbl_employee_remark.admin_id', '=', 'admin.id')
            ->select('tbl_employee_remark.*', 'admin.name')
            ->where('tbl_employee_remark.admin_id', $admin_id)
            ->when($search, function ($query) use ($search) {
                $query->where('tbl_employee_remark.remark', 'like', '%' . $search . '%');
            })
            ->orderBy('tbl_employee_remark.id', 'asc')
            ->get();

        // Fetch status updates from tbl_employee_status
        $statuses = DB::table('tbl_employee_status')
            ->where('admin_id', $admin_id)
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'res' => 'success',
            'remarks' => $remarks,
            'statuses' => $statuses,
        ]);
    }

    public function showData(Request $request)
    {
        $search = $request->search;
        $admin_id = $request->admin_id;

        // Fetch employee remarks with admin name
        $remarks = DB::table('tbl_employee_remark')
            ->leftJoin('admin', 'tbl_employee_remark.admin_id', '=', 'admin.id')
            ->select('tbl_employee_remark.*', 'admin.name')
            ->where('tbl_employee_remark.admin_id', $admin_id)
            ->when($search, function ($query) use ($search) {
                $query->where('tbl_employee_remark.remark', 'like', '%' . $search . '%');
            })
            ->orderBy('tbl_employee_remark.id', 'asc')
            ->get();

        // Fetch status updates from tbl_employee_status
        $statuses = DB::table('tbl_employee_status')
            ->where('admin_id', $admin_id)
            ->orderBy('id', 'asc')
            ->get();

        // Fetch full employee profile data from admin table
        $employee_profile = DB::table('admin')
            ->where('id', $admin_id)
            ->first(); // Fetch all columns for the given admin_id

        return response()->json([
            'res' => 'success',
            'remarks' => $remarks,
            'statuses' => $statuses,
            'profile' => $employee_profile,
        ]);
    }




    // Add Employee remark
    public function addEmployeeRemark(Request $request)
    {
        $data = [
            'admin_id' => $request->admin_id,
            'remark' => $request->remark,
            'date' => $this->date
        ];

        if (!empty($data)) {
            $res = DB::table('tbl_employee_remark')->insert($data);
            if ($res) {
                DB::table('tbl_employee_status')->insert([
                    'admin_id' => $request->admin_id,
                    'lead_status' => $request->remark,
                    'date' => $this->date
                ]);

                // Fetch the latest data from tbl_employee_remark
                $result = DB::table('tbl_employee_remark')->orderBy('id', 'desc')->get();

                return response()->json([
                    'data' => $result,
                    'success' => 'success',
                ]);
            }
        }
        return response()->json([
            'success' => 'error',
        ]);
    }

    // Edit Employee 
    public function editEmployee(Request $request)
    {
        $employeeId = $request->query('id'); // Get ID from URL
        $employee = DB::table('admin')->where('id', $employeeId)->first();
        return view('Admin.pages.edit_employee', compact('employee'));
    }

    public function updateEmployee1(Request $request)
    {
        $data = [
            'id' => $request->id,
            'name' => $request->name,
            'mobile' => $request->mobile,
            'alternate_mobile' => $request->alternate_mobile,
            'gender' => $request->gender,
            'email' => $request->email,
            'official_email' => $request->official_email,
            'pan_no' => $request->pan_no,
            'aadhar_no' => $request->aadhar_no,
            'dob' => $request->dob,
            'experience' => $request->experience,
            'qualification' => $request->qualification,
            'permanent_address' => $request->permanent_address,
            'current_address' => $request->current_address
        ];

        if (!empty($data)) {
            $update = DB::table('admin')->where('id', $request->id)->update($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    // Update Employee 
    public function updateEmployee(Request $request)
    {
        // Fetch existing employee data
        $employeeData = DB::table('admin')->where('id', $request->id)->first();

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $existingImage = $employeeData->image;

            // Remove old image if it exists
            if ($existingImage) {
                $image_path = public_path("storage/Admin/employees/" . basename($existingImage));
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
            // Upload new image
            $file = $request->file('image');
            $unique_id = uniqid();
            $name = $unique_id . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('storage/Admin/employees/'), $name);
            $path = "https://rupiyamaker.m-bit.org.in/storage/Admin/employees/$name";
        } else {
            $path = $employeeData->image;
        }

        // Prepare data for update
        $data = [
            'name' => $request->name,
            'mobile' => $request->mobile,
            'alternate_mobile' => $request->alternate_mobile,
            'gender' => $request->gender,
            'email' => $request->email,
            'official_email' => $request->official_email,
            'pan_no' => $request->pan_no,
            'aadhar_no' => $request->aadhar_no,
            'dob' => $request->dob,
            'experience' => $request->experience,
            'qualification' => $request->qualification,
            'permanent_address' => $request->permanent_address,
            'current_address' => $request->current_address,
        ];

        if ($path !== "") {
            $data['image'] = $path;
        }
        if (!empty($data)) {
            $update = DB::table('admin')->where('id', $request->id)->update($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
            return response()->json([
                'success' => 'error',
            ]);
        }
    }

    // Employee Update Attachment 
    public function EmployeeUpdateAttachment(Request $request)
    {
        $imagedata = DB::table('admin')->where('id', $request->admin_id)->first();
        $imageFields = [
            'cibil_report_image',
            'passport_image',
            'pan_card_image',
            'aadhar_card_image',
            'salary_3month_image',
            'salary_account_image',
            'bt_loan_image',
            'credit_card_image',
            'electric_bill_image',
            'form_16_image',
            'other_document_image',
        ];

        // Initialize data array
        $data = [
            'all_file_password' => $request->all_file_password,
        ];

        foreach ($imageFields as $field) {
            // Check if the request contains files for the current field
            if ($request->hasFile($field)) {
                $existingFiles = [];

                // If there are existing files in the database, fetch them
                if ($imagedata && !empty($imagedata->$field)) {
                    $existingFiles = explode(',', $imagedata->$field); // Assuming files are stored in the DB as comma-separated
                }

                // Process each uploaded file
                $files = $request->file($field);
                $newFilePaths = [];

                foreach ($files as $file) {
                    $unique_id = uniqid();
                    $name = $unique_id . '.' . $file->getClientOriginalExtension();
                    $file->move(public_path("storage/Admin/employees/$field/"), $name);

                    // Store the new file path
                    $newFilePaths[] = "https://rupiyamaker.m-bit.org.in//storage/Admin/employees/$field/$name";
                }

                // Combine existing and new file paths
                $allFiles = array_merge($existingFiles, $newFilePaths);

                // Update the data array with all the file paths (comma-separated)
                $data[$field] = implode(',', $allFiles);
            } else {
                // Retain the existing files if no new files are uploaded
                $data[$field] = $imagedata->$field ?? null;
            }
        }

        // Update the database
        DB::table('admin')->where('id', $request->admin_id)->update($data);

        // Insert into tbl_lead_status after updating the attachment
        DB::table('tbl_employee_status')->insert([
            'admin_id' => $request->admin_id,
            'lead_status' => 'Change Attachment Section',
            'date' => $this->date,
        ]);

        return response()->json([
            'success' => 'success',
        ]);
    }

    // Employee Attachments Show All Content
    public function EmployeeAttachmentShow(Request $request)
    {
        $data = DB::table('admin')
            ->where('id', $request->admin_id)
            ->first();
        if ($data) {
            return response()->json($data);
        } else {
            return response()->json(['message' => 'No data found'], 404);
        }
    }

    // Employee Attachments Single File Deleted 
    public function EmployeeDeleteAttachmentSingleFile(Request $request)
    {
        if (!empty($request->admin_id) && !empty($request->column_name) && !empty($request->file_url)) {
            // Fetch the lead record
            $admins = DB::table("admin")
                ->where("id", $request->admin_id)
                ->first();

            if (!$admins) {
                return response()->json(['success' => false, 'message' => 'Record not found.']);
            }

            // Get the column data and split it into an array
            $columnData = $admins->{$request->column_name};

            // Check if the column has any files and split the values by comma
            if ($columnData) {
                $files = explode(',', $columnData);
                $files = array_map('trim', $files); // Trim any extra spaces
                // Remove the specified file URL from the array
                $files = array_filter($files, function ($file) use ($request) {
                    return $file !== $request->file_url;
                });
                // Re-index the array and join back into a comma-separated string
                $updatedFiles = implode(',', array_values($files));
                // Update the column with the new file list (or null if no files remain)
                DB::table("admin")
                    ->where("id", $request->admin_id)
                    ->update([$request->column_name => $updatedFiles ?: null]);
                // Delete the specified file from storage if it exists
                if (\File::exists(public_path($request->file_url))) {
                    \File::delete(public_path($request->file_url));
                }
                return response()->json(['success' => true, 'message' => 'File deleted successfully.']);
            }
            return response()->json(['success' => false, 'message' => 'No files found in the specified column.']);
        }
        return response()->json(['success' => false, 'message' => 'Invalid request parameters.']);
    }

    // Employee Attachment image or pdf and all password folder ke andar file upload ho
    public function EmployeeDownloadZip($id)
    {
        $item = DB::table('admin')->where('id', $id)->first();
        if (!$item) {
            return response()->json(['error' => 'Record not found'], 404);
        }
        $imageFields = [
            'cibil_report_image',
            'passport_image',
            'pan_card_image',
            'aadhar_card_image',
            'salary_3month_image',
            'salary_account_image',
            'bt_loan_image',
            'credit_card_image',
            'electric_bill_image',
            'form_16_image',
            'other_document_image',
        ];
        $files = [];
        foreach ($imageFields as $field) {
            if (!empty($item->$field)) {
                $filePath = public_path(parse_url($item->$field, PHP_URL_PATH));
                if (File::exists($filePath)) {
                    $files[$field] = $filePath;
                }
            }
        }
        if (empty($files)) {
            return response()->json(['error' => 'No files found to download'], 404);
        }
        $passwordFilePath = null;
        if (!empty($item->all_file_password)) {
            $passwordFileName = 'all_file_password.txt';
            $passwordFilePath = public_path($passwordFileName);
            File::put($passwordFilePath, $item->all_file_password);
        }
        // $zipFileName = 'attachments-' . $id . '.zip';
        $zipFileName = $item->name . '-' . $item->mobile . '.zip';

        $zipPath = storage_path($zipFileName);
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($files as $field => $filePath) {
                $folderName = str_replace('_image', '', $field);
                $zip->addFile($filePath, $folderName . '/' . basename($filePath));
            }
            if ($passwordFilePath && File::exists($passwordFilePath)) {
                $zip->addFile($passwordFilePath, 'password/all_file_password.txt');
            }
            $zip->close();
        } else {
            return response()->json(['error' => 'Failed to create zip file'], 500);
        }
        if ($passwordFilePath && File::exists($passwordFilePath)) {
            File::delete($passwordFilePath);
        }
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }



    ########### Leave Start Here ###########
    ### showLeave ###
    public function showLeave(Request $request)
    {
        return view('Admin.pages.show_leave');
    }

    // Show All Active Inactive Employee 
    public function fetchLeave1(Request $request)
    {
        $search = $request->search; // Search keyword
        $status = $request->status; // Get status from request
        // Query Leave Data
        $query = DB::table('tbl_leave');
        // Apply status filter if provided
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        // Apply search filter if search keyword exists
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', '%' . $search . '%')
                    ->orWhere('leave_type', 'like', '%' . $search . '%')
                    ->orWhere('from_date', 'like', '%' . $search . '%')
                    ->orWhere('to_date', 'like', '%' . $search . '%')
                    ->orWhere('note', 'like', '%' . $search . '%');
            });
        }
        // Get counts for stats
        $all_leave = DB::table('tbl_leave')->count();
        $total_pending = DB::table('tbl_leave')->where('status', 'pending')->count();
        $total_approved = DB::table('tbl_leave')->where('status', 'approved')->count();
        $total_rejected = DB::table('tbl_leave')->where('status', 'rejected')->count();
        // Pagination
        $leaves = $query->orderBy('id', 'desc')->paginate(10);
        return response()->json([
            'data' => $leaves->items(),
            'current_page' => $leaves->currentPage(),
            'last_page' => $leaves->lastPage(),
            'per_page' => $leaves->perPage(),
            'total' => $leaves->total(),
            'counts' => [
                'pending' => $total_pending,
                'approved' => $total_approved,
                'rejected' => $total_rejected,
                'all' => $all_leave
            ],
            'success' => 'success',
        ]);
    }

    public function fetchLeave(Request $request)
    {
        // Get admin session
        $adminSession = collect(session()->get('admin_login'))->first();

        if (!$adminSession) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $admin_id = $adminSession->id;
        $admin_role = strtolower($adminSession->role);

        $search = $request->search; // Search keyword
        $status = $request->status; // Get status from request

        // Query Leave Data with admin names
        $query = DB::table('tbl_leave')
            ->leftJoin('admin', 'tbl_leave.admin_id', '=', 'admin.id')
            ->leftJoin('admin as approver', 'tbl_leave.approved_by', '=', 'approver.id')
            ->select('tbl_leave.*', 'admin.name as admin_name', 'admin.role as role', 'approver.name as approved_by');

        // Apply role-based filtering
        if ($admin_role === 'admin' || $admin_role === 'hr') {
            // Admin & HR see all leave requests
            // No additional filters needed
        } elseif ($admin_role === 'manager') {
            // Managers see their own leaves and leaves of team members they manage
            $team_member_ids = DB::table('admin')
                ->where('manager', $admin_id)
                ->pluck('id')
                ->toArray();

            $query->where(function ($q) use ($team_member_ids, $admin_id) {
                $q->whereIn('tbl_leave.admin_id', $team_member_ids)
                    ->orWhere('tbl_leave.admin_id', $admin_id);
            });
        } elseif ($admin_role === 'tl') {
            // Team Leaders see their own leaves and leaves of team members they lead
            $team_member_ids = DB::table('admin')
                ->where('team_leader', $admin_id)
                ->pluck('id')
                ->toArray();

            $query->where(function ($q) use ($team_member_ids, $admin_id) {
                $q->whereIn('tbl_leave.admin_id', $team_member_ids)
                    ->orWhere('tbl_leave.admin_id', $admin_id);
            });
        } else {
            // Agent or other roles only see their own leave requests
            $query->where('tbl_leave.admin_id', $admin_id);
        }

        // Apply status filter if provided
        if ($status && $status !== 'all') {
            $query->where('tbl_leave.status', $status);
        }

        // Apply search filter if search keyword exists
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('tbl_leave.status', 'like', '%' . $search . '%')
                    ->orWhere('tbl_leave.leave_type', 'like', '%' . $search . '%')
                    ->orWhere('tbl_leave.from_date', 'like', '%' . $search . '%')
                    ->orWhere('tbl_leave.to_date', 'like', '%' . $search . '%')
                    ->orWhere('tbl_leave.note', 'like', '%' . $search . '%')
                    ->orWhere('admin.name', 'like', '%' . $search . '%'); // Also search by admin name
            });
        }

        // Get counts for stats based on role filters
        if ($admin_role === 'admin' || $admin_role === 'hr') {
            // For admin/HR, show counts of all leaves
            $all_leave = DB::table('tbl_leave')->count();
            $total_pending = DB::table('tbl_leave')->where('status', 'pending')->count();
            $total_approved = DB::table('tbl_leave')->where('status', 'approved')->count();
            $total_rejected = DB::table('tbl_leave')->where('status', 'rejected')->count();
        } else {
            // For other roles, show counts of relevant leaves only
            $roleFilterQuery = DB::table('tbl_leave');

            if ($admin_role === 'manager') {
                $team_member_ids = DB::table('admin')
                    ->where('manager', $admin_id)
                    ->pluck('id')
                    ->toArray();

                $roleFilterQuery->where(function ($q) use ($team_member_ids, $admin_id) {
                    $q->whereIn('admin_id', $team_member_ids)
                        ->orWhere('admin_id', $admin_id);
                });
            } elseif ($admin_role === 'tl') {
                $team_member_ids = DB::table('admin')
                    ->where('team_leader', $admin_id)
                    ->pluck('id')
                    ->toArray();

                $roleFilterQuery->where(function ($q) use ($team_member_ids, $admin_id) {
                    $q->whereIn('admin_id', $team_member_ids)
                        ->orWhere('admin_id', $admin_id);
                });
            } else {
                $roleFilterQuery->where('admin_id', $admin_id);
            }

            $all_leave = $roleFilterQuery->count();
            $total_pending = $roleFilterQuery->clone()->where('status', 'pending')->count();
            $total_approved = $roleFilterQuery->clone()->where('status', 'approved')->count();
            $total_rejected = $roleFilterQuery->clone()->where('status', 'rejected')->count();
        }

        // Pagination
        $leaves = $query->orderBy('tbl_leave.id', 'desc')->paginate(10);

        return response()->json([
            'data' => $leaves->items(),
            'current_page' => $leaves->currentPage(),
            'last_page' => $leaves->lastPage(),
            'per_page' => $leaves->perPage(),
            'total' => $leaves->total(),
            'counts' => [
                'pending' => $total_pending,
                'approved' => $total_approved,
                'rejected' => $total_rejected,
                'all' => $all_leave
            ],
            'success' => 'success',
        ]);
    }

    // addLeave
    public function addLeave(Request $request)
    {
        $data = [
            'admin_id' => $request->admin_id,
            'status' => 'pending',
            'leave_type' => $request->leave_type,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'duration' => $request->duration,
            'note' => $request->note,
            'date' => date('Y-m-d', strtotime($this->date)),
            'time' => date('h:i A', strtotime($this->date)),
        ];

        if (!empty($data)) {
            DB::table('tbl_leave')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }



    public function updateLeaveStatus(Request $request)
    {
        // Get admin session 
        $adminSession = collect(session()->get('admin_login'))->first();

        $admin_id = $adminSession->id;
        $leave_id = $request->leave_id;
        $status = $request->status;

        $updated = DB::table('tbl_leave')
            ->where('id', $leave_id)
            ->update([
                'status' => $status,
                'approved_by' => $admin_id
            ]);

        if ($updated) {
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }

    // update employee 
    public function updateEmployeeLeave(Request $request)
    {
        $data = [
            'id' => $request->leave_id,
            'leave_type' => $request->leave_type,
            'from_date' => $request->from_date,
            'to_date' => $request->to_date,
            'duration' => $request->duration,
            'note' => $request->note,
            'date' => date('Y-m-d', strtotime($request->date)),
            'time' => date('h:i A', strtotime($request->time))
        ];

        if (!empty($data)) {
            $res = DB::table('tbl_leave')->where('id', $request->leave_id)->update($data);
            if ($res) {
                return response()->json([
                    'success' => 'success',
                ]);
            }
        } else {
        }
    }

    // Leave Comment Here in Task Tab
    public function getLeaveComments(Request $request)
    {
        $leaveId = $request->leave_id;
        $comments = DB::table('tbl_leave_comment')
            ->where('leave_id', $leaveId)
            ->leftJoin('admin', 'tbl_leave_comment.admin_id', '=', 'admin.id') // Assuming 'admins' table exists
            ->select('tbl_leave_comment.*', 'admin.name as createdby')
            ->orderBy('tbl_leave_comment.id', 'desc')
            ->get();
        return response()->json($comments);
    }


    public function addLeaveComment(Request $request)
    {
        $task = DB::table('tbl_leave')->where('id', $request->leave_id)->first();
        if ($task) {
            $data = [
                'admin_id' => $request->admin_id,
                'leave_id' => $request->leave_id,
                'comment' => $request->comment,
                'date' => $this->date
            ];
            // Insert comment into tbl_task_comment
            DB::table('tbl_leave_comment')->insert($data);
            // Insert into tbl_lead_status after adding the comment
            DB::table('tbl_employee_status')->insert([
                'admin_id' => $request->admin_id,
                'lead_status' => 'Change Comment Added Section',
                'date' => $this->date,
            ]);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }


    ########### Attendance Start Here ###########
    public function showAttendance(Request $request)
    {
        // Get the current month dates
        $now = Carbon::now();
        $daysInMonth = $now->daysInMonth;
        $month = $now->format('F');
        $year = $now->year;

        // Create array of all dates in current month
        $dates = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = Carbon::createFromDate($year, $now->month, $day);
            $dates[] = [
                'day' => $day,
                'date' => $date->format('Y-m-d'),
                'dayOfWeek' => $date->format('D'),
                'isSunday' => $date->dayOfWeek === 0
            ];
        }

        // Fetch all users from admin table
        $users = DB::table('admin')
            ->select(['id', 'name', 'department', 'team', 'role', 'image'])
            ->get();

        // Get attendance records for the entire month
        $monthStart = Carbon::createFromDate($year, $now->month, 1)->format('Y-m-d');
        $monthEnd = Carbon::createFromDate($year, $now->month, $daysInMonth)->format('Y-m-d');

        $attendanceRecords = DB::table('tbl_attendance')
            ->whereBetween(DB::raw('DATE(date)'), [$monthStart, $monthEnd])
            ->get();

        // Group attendance records by admin_id and date
        $groupedRecords = [];
        foreach ($attendanceRecords as $record) {
            $date = Carbon::parse($record->date)->format('Y-m-d');
            $groupedRecords[$record->admin_id][$date] = $record;
        }

        // Format the attendance data
        $attendanceData = $users->map(function ($user) use ($groupedRecords, $dates) {
            $userData = [
                'admin_id' => $user->id,
                'name' => $user->name,
                'department' => $user->department ?? '',
                'team' => $user->team ?? '',
                'role' => $user->role ?? '',
                'image' => $user->image ?? '',
                'attendance' => []
            ];

            // Add attendance for each day
            foreach ($dates as $date) {
                $record = $groupedRecords[$user->id][$date['date']] ?? null;

                $userData['attendance'][$date['date']] = [
                    'punch_in_time' => $record && isset($record->punchin_time)
                        ? Carbon::parse($record->punchin_time)->format('g:i:s A')
                        : null,
                    'punch_out_time' => $record && isset($record->punch_out_time)
                        ? Carbon::parse($record->punch_out_time)->format('g:i:s A')
                        : null,
                    'attendance_status' => $record ? $record->attendance_status : 'absent',
                    'punchin_img' => $record ? $record->punchin_img : null,
                    'punchout_img' => $record ? $record->punchout_img : null,
                    'punchin_datetime' => $record && isset($record->punchin_time)
                        ? Carbon::parse($record->punchin_time)->format('Y-m-d g:i:s A')
                        : null,
                    'punchout_datetime' => $record && isset($record->punch_out_time)
                        ? Carbon::parse($record->punch_out_time)->format('Y-m-d g:i:s A')
                        : null,
                ];
            }

            return $userData;
        });

        return view('Admin.pages.attendance', [
            'attendanceData' => $attendanceData,
            'dates' => $dates,
            'month' => $month,
            'year' => $year
        ]);
    }

    // update attendance status after punchout 
    public function UpdateAttendanceafterPunchOut(Request $request)
    {
        dd($request->all());
        $data = [
            'attendance_status' => $request->attendance_status,
            'punchout_datetime' => $request->punchout_datetime,
            'punchout_note' => $request->punchout_note,
            'punchout_status' => 'true',
            'date' => date('Y-m-d', strtotime($this->date)),
        ];

        if (!empty($data)) {
            DB::table('tbl_attendance')->where('admin_id', $request->admin_id)->update($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }
    // add attendance 
    public function addAttendance(Request $request)
    {
        $path1 = ""; // Default empty path

        if ($request->hasFile('punchin_img')) {
            $file = $request->file("punchin_img");
            $uniqid = uniqid();
            $name = $uniqid . "." . $file->getClientOriginalExtension();
            $file->move(public_path('storage/Admin/attendance/'), $name);
            $path1 = "https://rupiyamaker.m-bit.org.in/storage/Admin/attendance/$name";
        }

        $data = [
            'admin_id' => $request->admin_id,
            'punchin_datetime' => $request->punchin_datetime,
            'punchin_note' => $request->punchin_note,
            'punchin_status' => 'true',
            'punchout_status' => 'false',
            'attendance_status' => '0.5',
            'punchin_img' => $path1, // Store image URL in punchin_img
            'date' => $this->today_date
        ];

        DB::table('tbl_attendance')->insert($data);
        return response()->json([
            'success' => 'success',
        ]);
    }

    // New function to update punch-out information
    public function updatePunchOutAttendance(Request $request)
    {
        $path2 = "";
        if ($request->hasFile('punchout_img')) {
            $file = $request->file("punchout_img");
            $uniqid = uniqid();
            $name = $uniqid . "." . $file->getClientOriginalExtension();
            $file->move(public_path('storage/Admin/attendance/'), $name);
            $path2 = "https://rupiyamaker.m-bit.org.in/storage/Admin/attendance/$name";
        }

        // Format directly in the query
        $today = $this->today_date;

        DB::table('tbl_attendance')
            ->where('admin_id', $request->admin_id)
            ->where('punchin_status', 'true')
            ->where('punchout_status', 'false')
            ->where('date', $today)
            ->update([
                'punchout_datetime' => $request->punchout_datetime,
                'punchout_note' => $request->punchout_note,
                'punchout_status' => 'true',
                'attendance_status' => '1',
                'punchout_img' => $path2
            ]);
        return response()->json([
            'success' => 'success',
        ]);
    }

    // Puncout Commitment
    public function punchoutComitment()
    {
        $data['products'] = DB::table('tbl_punchoutcommitment')->orderBy('id', 'desc')->get();
        return view('Admin.pages.commitment', $data);
    }
    public function addPunchoutComitment(Request $request)
    {
        $data = [
            'status' => '1',
            'product_name' => $request->product_name,
            'type' => $request->type,
            'duration' => $request->duration,
            'datetime' => $this->date,
        ];
        if (!empty($data)) {
            $res = DB::table('tbl_punchoutcommitment')->insert($data);
            return response()->json([
                'success' => 'success',
            ]);
        } else {
        }
    }

    public function deleteCommitment(Request $request)
    {
        if (!empty($request->id)) {
            $delete_data = DB::table("tbl_punchoutcommitment")->where("id", $request->id)->delete();
            return response()->json(['success' => 'success']);
        } else {
        }
    }

    // daily perforamce 
    public function dailyPerformace()
    {
        // Get current month's dates
        $now = now();
        $currentMonth = $now->month;
        $currentYear = $now->year;
        $monthName = $now->format('F Y');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);
        
        $dates = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $date = \Carbon\Carbon::createFromDate($currentYear, $currentMonth, $day);
            $dayOfWeek = $date->format('D'); // Mon, Tue, etc.
            
            $dates[] = [
                'day' => $day,
                'dayOfWeek' => $dayOfWeek
            ];
        }
        
        // Fetch admin users from the admin table with correct field names
        $admins = DB::table('admin')
            ->select('id', 'name', 'department', 'team', 'role', 'image')
            ->get();
        
        return view('Admin.pages.daily_performace', compact('dates', 'monthName', 'admins', 'currentYear', 'currentMonth'));
    }
    public function getDailyPerformanceData(Request $request)
    {
        
        // Get commitments from tbl_punchoutcommitment filtered by date and employee ID
        $commitments = DB::table('tbl_punchoutcommitment')
            ->get();
        
        // Separate count and time records
        $countRecords = $commitments->where('type', 'Count');
        $timeRecords = $commitments->where('type', 'Time');
        
        return response()->json([
            'countRecords' => $countRecords,
            'timeRecords' => $timeRecords
        ]);
    }



    // end here
}
