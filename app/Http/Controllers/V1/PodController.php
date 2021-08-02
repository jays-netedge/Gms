<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\GmsOffice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\GmsBookingDtls;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class PodController extends Controller
{

    // if ($_SERVER['HTTP_HOST'] == 'localhost') {
    //    define('IMAGEPATH', asset(''));
    // } else {
    //     define('IMAGEPATH', asset('') . 'public/');
    // }

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;

    }

    public function viewScanUpdate(Request $request)
    {
        $podScan = GmsBookingDtls::select('book_cnno', 'book_pod_scan', DB::raw('concat(book_pod_scan_office,"/",book_pod_scan_emp) As scaned_by'), 'book_pod_scan_date')->where('is_deleted', 0);
        return $podScan->paginate($request->per_page);
    }

    public function podUpdate(Request $request)
    {
        if (session()->has('session_token')) {
            $sessionObject = session()->get('session_token');
            $admin = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();
            $office_code = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();

            $date = Carbon::now();
            $date_formate = $date->format("Ymd");

            $validator = Validator::make($this->request->all(), [
                'book_cnno' => 'required',
                'book_pod_scan' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
            }
            $input = $this->request->all();
            $getBookingDtls = GmsBookingDtls::where('book_cnno', $input['book_cnno'])->where('is_deleted', 0)->first();
            $editBookingDtls = GmsBookingDtls::find($getBookingDtls->id);
            if ($request->hasfile('book_pod_scan')) {
                //getting the file from view
                $image = $request->file('book_pod_scan');
                //getting the extension of the file
                $image_ext = $image->getClientOriginalExtension();
                //changing the name of the file
                $book_pod_scan = $office_code->office_code . $date_formate . rand(123456, 999999) . "." . $image_ext;
                $destination_path = public_path('/pod');
                $image->move($destination_path, $book_pod_scan);

            }

            $editBookingDtls->book_pod_scan = isset($book_pod_scan) ? $book_pod_scan : '';
            $input['book_pod_scan'] = $book_pod_scan;
            $editBookingDtls->update($input);
            $input['book_pod_scan'] = $editBookingDtls->book_pod_scan;

            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $input);
        } else {
            return $this->errorResponse(self::CODE_UNAUTHORIZED, "Session not found.");

        }
    }

    public function deletePodUpdate()
    {
        $input = $this->request->all();
        $getCnno = GmsBookingDtls::where('book_cnno', $input['cnno'])->where('is_deleted', 0)->first();
        if ($getCnno != null) {
            $getCnno->book_pod_scan = "";
            $getCnno->save();
            return $this->successResponse(self::CODE_OK, "Delete Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function allCnno()
    {
        return GmsBookingDtls::select('book_cnno')->where('is_deleted', 0)->get();
    }

    public function podSearch(Request $request)
    {
        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $book_cnno = $this->request->book_cnno;
        $destination_path = public_path('/pod');
        $query = GmsBookingDtls::select(
            'book_cnno', 'book_pod_scan_emp',
            DB::raw("CONCAT('$destination_path','/',book_pod_scan) As image"),
            DB::raw('book_pod_scan_date AS date')
        );
        if ($request->has('from_date') && ($request->has('to_date'))) {
            $query->whereBetween('book_pod_scan_date', [$from_date, $to_date]);
        }
        if ($request->has('book_cnno')) {
            $query->where('book_cnno', $book_cnno);
        }
        return $query->paginate($request->per_page);
    }

    public function bulkUpdate(Request $request)
    {
        if (session()->has('session_token')) {
            $sessionObject = session()->get('session_token');
            $admin = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();
            $office_code = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();

            $date = Carbon::now();
            $date_formate = $date->format("Ymd");
            $input = $this->request->all();

            $validator = Validator::make($this->request->all(), [
                'cnno_no' => 'required',
                'book_pod_scan' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
            }
            if (!is_array($input['cnno_no'])) {
                return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, ['book_pod_scan' => "image must be a array"]);
            }
            $uploaded = array();

            for ($i = 0; $i < count(array($input['cnno_no'][$i])); $i++) {
                $items = DB::table('gms_booking_dtls')->whereIn('book_cnno', array($input['cnno_no'][$i]))->get();
                foreach ($items as $key => $value) {
                    if ($request->hasfile('book_pod_scan')) {
                        foreach ($request->file('book_pod_scan') as $file) {
                            $name = $file->getClientOriginalName();
                            $file->move(public_path() . '/pod/', $name);
                            $uploaded[] = $name;
                        }
                    }

                }
            }

            return $this->successResponse(self::CODE_OK, "Update Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_UNAUTHORIZED, "Session not found.");

        }
    }


}
