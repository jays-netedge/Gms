<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\GmsBookingDtls;
use App\Models\GmsOffice;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\GmsBookCustIssue;
use App\Models\GmsBookEpodGenerate;
use App\Models\GmsBookBoTransfer;
use App\Models\GmsBookRoIssue;
use App\Models\GmsBookBoissue;
use App\Models\GmsBookRoTransfer;
use App\Http\Traits\BookTrait;
use Carbon\Carbon;


class BookController extends Controller
{
    use BookTrait;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * @OA\Post(
     * path="/editCusBookIssue",
     * summary="editCustomer Book Issue",
     * operationId="editCusBookIssue",
     *  tags={"Book"},
     * @OA\Parameter(
     *   name="cusBookIssue_id",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="description",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="qauantity",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cnno_start",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cnno_end",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="total_allotted",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_ro",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="created_by",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="rate_per_cnno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\MediaType(
     * mediaType="application/json",
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized"
     * ),
     * @OA\Response(
     * response=400,
     * description="Invalid request"
     * ),
     * @OA\Response(
     * response=404,
     * description="not found"
     * ),
     * )
     */
    public function editCusBookIssue()
    {
        $validator = Validator::make($this->request->all(), [
            'cusBookIssue_id' => 'required|exists:gms_book_cust_issue,id',
            'description' => 'required',
            'qauantity' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getCusBookIssue = GmsBookCustIssue::where('id', $input['cusBookIssue_id'])->where('is_deleted', 0)->first();
        if ($getCusBookIssue) {
            $editCusBookIssue = GmsBookCustIssue::find($getCusBookIssue->id);
            $editCusBookIssue->update($input);
            return $this->successResponse(self::CODE_OK, "Customer Book Update Successfully!!", $editCusBookIssue);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Customer Book Not Found");
        }
    }

    /**
     * @OA\Post(
     * path="/viewCustBookIssue",
     * summary="View Customer Book Issue",
     * operationId="viewCusBookIssue",
     *  tags={"Book"},
     * @OA\Parameter(
     *   name="cusBookIssue_id",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\MediaType(
     * mediaType="application/json",
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized"
     * ),
     * @OA\Response(
     * response=400,
     * description="Invalid request"
     * ),
     * @OA\Response(
     * response=404,
     * description="not found"
     * ),
     * )
     */
    public function viewCustBookIssue()
    {
        return $this->viewCusBookIssues();
    }

    /**
     * @OA\Post(
     * path="/deleteCustBookReturn",
     * summary="Delete Customer Book Return",
     * operationId="deleteCustBookReturn",
     *  tags={"Book"},
     * @OA\Parameter(
     *   name="cusBookReturn_id",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\MediaType(
     * mediaType="application/json",
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized"
     * ),
     * @OA\Response(
     * response=400,
     * description="Invalid request"
     * ),
     * @OA\Response(
     * response=404,
     * description="not found"
     * ),
     * )
     */
    public function deleteCustBookReturn()
    {
        return $this->deleteCusReturn();
    }


    /**
     * @OA\Post(
     * path="/addBookBoTransfer",
     * summary="add BookBo Transfer",
     * operationId="addBookBoTransfer",
     *  tags={"BookEpodGen"},
     * @OA\Parameter(
     *   name="iss_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="iss_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cnno_start",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cnno_end",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_ro",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="dest_office_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="description",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="tranfer_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\MediaType(
     * mediaType="application/json",
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized"
     * ),
     * @OA\Response(
     * response=400,
     * description="Invalid request"
     * ),
     * @OA\Response(
     * response=404,
     * description="not found"
     * ),
     * )
     */
    public function addBookBoTransfer(Request $request)
    {
        $adminSession = session()->get('session_token');
        $admin = Admin::where('id', $adminSession->admin_id)->where('is_deleted', 0)->first();
        $office = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();

        $validator = Validator::make($this->request->all(), [
            'cnno_start' => 'required',
            'cnno_end' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        //  $addBookBoTransfer->iss_bo_id = $office->id;
        $input['entry_date'] = Carbon::now()->toDateTimeString();
        $input['recieved_date'] = Carbon::now()->toDateTimeString();
        $input['user_id'] = $adminSession->admin_id;
        $addBookBoTransfer = new GmsBookBoTransfer($input);
        $addBookBoTransfer->save();
        return $this->successResponse(self::CODE_OK, "Book Out Transfer Successfully!!", $addBookBoTransfer);
    }


    /**
     * @OA\Post(
     * path="/viewRoBook",
     * summary="view RoBook",
     * operationId="viewRoBook",
     *  tags={"RoBook"},
     * @OA\Parameter(
     *   name="roBook_id",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\MediaType(
     * mediaType="application/json",
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized"
     * ),
     * @OA\Response(
     * response=400,
     * description="Invalid request"
     * ),
     * @OA\Response(
     * response=404,
     * description="not found"
     * ),
     * )
     */
    public function viewRoBook()
    {
        return $this->roBook();
    }

    /**
     * @OA\Post(
     * path="/deleteRoBook",
     * summary="deleteRoBook",
     * operationId="deleteRoBook",
     *  tags={"RoBook"},
     * @OA\Parameter(
     *   name="roBook_id",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\MediaType(
     * mediaType="application/json",
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized"
     * ),
     * @OA\Response(
     * response=400,
     * description="Invalid request"
     * ),
     * @OA\Response(
     * response=404,
     * description="not found"
     * ),
     * )
     */
    public function deleteRoBook()
    {
        return $this->delRoBook();
    }

    /**
     * @OA\Post(
     * path="/addBookDtls",
     * summary="Add BookDtl",
     * operationId="addBookDtls",
     *  tags={"Book"},
     * @OA\Parameter(
     *   name="book_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_br_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_emp_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_cust_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_cust_type_orginal",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_mfno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="max_no",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_mfdate",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_mftime",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_mftime1",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_srno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_refno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\MediaType(
     * mediaType="application/json",
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized"
     * ),
     * @OA\Response(
     * response=400,
     * description="Invalid request"
     * ),
     * @OA\Response(
     * response=404,
     * description="not found"
     * ),
     * )
     */
    public function addBookDtls(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $user_check = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();

        // $validator = Validator::make($this->request->all(), [
        //     'book_type' => 'required',
        //     'book_br_code' => 'required',
        //     'book_mfrefno' => 'required',
        //     'book_mfdate' => 'required',
        //     'book_mftime' => 'required',
        //     'book_cust_type' => 'required',
        //     'book_cust_code' => 'required',
        //     'book_vol_weight' => 'required'
        //     ]);
        //     if ($validator->fails()) {
        //         return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        //     }

        $input = $this->request->all();
        $datetime = Carbon::now()->toDateTimeString();
        $last_book_mfno = GmsBookingDtls::select('book_mfno', 'book_srno')->orderBy('created_at', 'desc')->first();
        if (empty($last_book_mfno)) {
            $firstNumber = 10000;
            $mf_no = "BM_" . $user_check->office_code . "_" . $firstNumber;
            $input['book_mfno'] = $mf_no;
        } else {
            $lastNumber = substr($last_book_mfno->book_mfno, -5);
            $newNum = $lastNumber + 1;
            if ($this->request->book_srno == 1) {
                $mf_no1 = "BM_" . $user_check->office_code . "_" . $newNum;
                $input['book_mfno'] = $mf_no1;
            } else {
                $lastNumber1 = substr($last_book_mfno->book_mfno, -5);
                $mf_no = "BM_" . $user_check->office_code . "_" . $lastNumber1;
                $input['book_mfno'] = $mf_no;
            }
        }
        $format1 = 'Y-m-d';
        $format2 = 'H:i:s';
        $date = Carbon::parse($datetime)->format($format1);
        // $time = Carbon::parse($datetime)->format($format2);
        $input['book_emp_code'] = $user_check->office_code;
        $input['book_org'] = $user_check->city;
        $input['user_id'] = $sessionObject->admin_id;
        $input['book_mfdate'] = $date;
        //  $input['book_mftime'] = $time;

        $addBookDtls = new GmsBookingDtls($input);
        $addBookDtls->save();
        return $this->successResponse(self::CODE_OK, "Book Details Added Successfully!!", $addBookDtls);
    }


    /**
     * @OA\Post(
     * path="/editBookingDetails",
     * summary="Edit BookingDetails",
     * operationId="editBookingDetails",
     *  tags={"Book"},
     * @OA\Parameter(
     *   name="book_id",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_br_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_emp_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_cust_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_cust_type_orginal",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_mfno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="max_no",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_mfdate",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_mftime",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_mftime1",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_srno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_refno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\MediaType(
     * mediaType="application/json",
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized"
     * ),
     * @OA\Response(
     * response=400,
     * description="Invalid request"
     * ),
     * @OA\Response(
     * response=404,
     * description="not found"
     * ),
     * )
     */
    public function editBookingDetails()
    {
        $sessionObject = session()->get('session_token');
        $office = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();
        $validator = Validator::make($this->request->all(), [
            'book_cnno' => 'required|exists:gms_booking_dtls,book_cnno',
            'book_type' => 'required',
            'book_cust_type' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getBookDtls = GmsBookingDtls::where('book_cnno', $input['book_cnno'])->where('book_br_code', $office->office_code)->where('is_deleted', 0)->first();
        if ($getBookDtls) {
            $editBookDtls = GmsBookingDtls::find($getBookDtls->book_cnno);
            $editBookDtls->update($input);
            return $this->successResponse(self::CODE_OK, "Book Update Successfully!!", $editBookDtls);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Customer Book Not Found");
        }

    }


    public function viewBookingDetails(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $office = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();
        $input = $this->request->all();
        $query = GmsBookingDtls::select(
            'gms_booking_dtls.book_mfno as booking_mf_no',
            'gms_booking_dtls.book_cust_type as customer',
            'gms_booking_dtls.book_mfdate as booking_date',

            DB::raw('COUNT(gms_booking_dtls.book_cnno) as total_cnno'),
            DB::raw('SUM(gms_booking_dtls.book_weight) as total_weight'),
            DB::raw('SUM(gms_booking_dtls.book_pcs) as total_pcs'),
            DB::raw('SUM(gms_booking_dtls.book_billamt) as total_amount'),
        );
        $query->where('gms_booking_dtls.is_deleted', 0);
        $query->where('book_br_code', $office->office_code);
        $query->groupBy('gms_booking_dtls.book_mfno');
        $query->orderBy('created_at', 'DESC');

        if ($request->isMethod('get')) {
            return $query->get();
        }
        if ($request->has('b')) {
            $query->where('book_mfno', 'LIKE', '%' . $request->b . '%');
            return $data = $query->paginate($request->per_page);
        }
        $data = array();
        $data['cnno'] = GmsBookingDtls::select('book_cnno as cnno', 'book_weight as weight', 'book_vol_weight as Volweight', 'book_pcs as pcs', 'book_pin as pincode', 'book_product_type as product_type', 'book_mode as mode_type', 'book_doc as doc_type', 'book_billamt as bill_amount', 'book_topay as topay_value', 'book_cod as cod_value', 'book_remarks as remarks')->where('book_mfno', $input['book_mfno'])->where('is_deleted', 0)->latest()->get();
        $data['total'] = GmsBookingDtls::select(DB::raw('SUM(gms_booking_dtls.book_weight) as total_weight'), DB::raw('COUNT(gms_booking_dtls.book_cnno) as total_cnno'), DB::raw('SUM(gms_booking_dtls.book_weight) as total_weight'), DB::raw('SUM(gms_booking_dtls.book_pcs) as total_pcs'), DB::raw('SUM(gms_booking_dtls.book_billamt) as total_amount'), DB::raw('SUM(gms_booking_dtls.book_vol_weight) as total_vol_amount'))->where('book_mfno', $input['book_mfno'])->where('is_deleted', 0)->first();
        $data['customerDetails'] = GmsBookingDtls::select('gms_booking_dtls.book_emp_code as booking_by', 'gms_booking_dtls.book_mfno as manifest_no', 'gms_booking_dtls.book_cust_code as customer_name', 'gms_booking_dtls.book_mfdate as manifest_date')->where('book_mfno', $input['book_mfno'])->where('is_deleted', 0)->first();
        return $data;

    }

    public function deleteBookingDetails()
    {
        $validator = Validator::make($this->request->all(), [
            'cnno' => 'required|exists:gms_booking_dtls,book_cnno',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getBookDtls = GmsBookingDtls::where('book_cnno', $input['cnno'])->first();
        if ($getBookDtls != null) {
            $getBookDtls->is_deleted = 1;
            $getBookDtls->save();
            return $this->successResponse(self::CODE_OK, "Booking Details Deleted Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "book Mf No. Not Found");
        }
    }


    /**
     * @OA\Post(
     * path="/boIssueStatus",
     * summary="Bo IssueStatus",
     * operationId="boIssueStatus",
     *  tags={"Book"},
     * @OA\Parameter(
     *   name="bookIssue_id",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\MediaType(
     * mediaType="application/json",
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized"
     * ),
     * @OA\Response(
     * response=400,
     * description="Invalid request"
     * ),
     * @OA\Response(
     * response=404,
     * description="not found"
     * ),
     * )
     */
    public function boIssueStatus()
    {
        $validator = Validator::make($this->request->all(), [
            'bookIssue_id' => 'required|exists:gms_book_bo_issue,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $issueBook = GmsBookBoissue::where('id', $input['bookIssue_id'])->first();
        if ($issueBook) {
            $issueBook->status = 0;
            $issueBook->save();

            return $this->successResponse(self::CODE_OK, "Close Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found!');
        }
    }

    /**
     * @OA\Post(
     * path="/boIssueStatusStart",
     * summary="Bo IssueStatusStart",
     * operationId="boIssueStatusStart",
     *  tags={"Book"},
     * @OA\Parameter(
     *   name="bookIssue_id",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\MediaType(
     * mediaType="application/json",
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized"
     * ),
     * @OA\Response(
     * response=400,
     * description="Invalid request"
     * ),
     * @OA\Response(
     * response=404,
     * description="not found"
     * ),
     * )
     */
    public function boIssueStatusStart()
    {
        $validator = Validator::make($this->request->all(), [
            'bookIssue_id' => 'required|exists:gms_book_bo_issue,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $issueBook = GmsBookBoissue::where('id', $input['bookIssue_id'])->first();
        if ($issueBook) {
            $issueBook->status = 1;
            $issueBook->save();

            return $this->successResponse(self::CODE_OK, "Start Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found!');
        }
    }


    /**
     * @OA\Post(
     * path="/viewRoTransfer",
     * summary="View RoTransfer",
     * operationId="View RoTransfer",
     *  tags={"Transfer"},
     * @OA\Parameter(
     *   name="book_Id",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\MediaType(
     * mediaType="application/json",
     * )
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized"
     * ),
     * @OA\Response(
     * response=400,
     * description="Invalid request"
     * ),
     * @OA\Response(
     * response=404,
     * description="not found"
     * ),
     * )
     */
    public function viewRoTransfer()
    {
        return $this->viewBookRoTrans();
    }


    public function addCusBookIssue(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();
        $office = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();

        $validator = Validator::make($this->request->all(), [
            'cust_type' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['office_code'] = $admin->office_code;
        $input['office_ro'] = $office->office_code;
        $input['created_by'] = $admin->user_type;
        $input['entry_date'] = Carbon::now()->toDateTimeString();
        $input['recieved_date'] = Carbon::now()->toDateTimeString();
        $input['user_id'] = $sessionObject->admin_id;
        $addCusBookIssue = new GmsBookCustIssue($input);
        $addCusBookIssue->save();
        return $this->successResponse(self::CODE_OK, "Customer Book Issue Created Successfully!!", $addCusBookIssue);
    }


    public function addBookCustRoReturn(Request $request)
    {
        $validator = Validator::make($this->request->all(), [
            'description' => 'required',
            'cnno_start' => 'required',
            'cnno_end' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addBookCustRoReturn = new GmsBookBoTransfer($input);
        $addBookCustRoReturn->save();
        return $this->successResponse(self::CODE_OK, "Book Cust Ro Return Added Successfully!!", $addBookCustRoReturn);

    }

    public function viewBookCustRoReturn(Request $request)
    {
        $adminSession = session()->get('session_token');
        $getBookRoReturn = GmsBookBoTransfer::query();
        $getBookRoReturn->where('tranfer_type', '=', 'R');
        $getBookRoReturn->select(
            'id',
            DB::raw('CONCAT("RO-SRN",id) As Type'),
            'description',
            'cnno_start',
            'cnno_end',
            'tranfer_type',
            'entry_date',
            'status'
        );
        $getBookRoReturn->where('is_deleted', 0);
        return $getBookRoReturn->paginate($request->per_page);
    }


    public function viewAllBookBoReturn(Request $request)
    {
        $adminSession = session()->get('session_token');
        $getBookBoReturn = GmsBookBoTransfer::query();
        $getBookBoReturn->where('tranfer_type', '=', 'R');
        $getBookBoReturn->select(
            'id',
            DB::raw('CONCAT("Bo-SRN",id) As Type'),
            'description',
            'cnno_start',
            'cnno_end',
            'entry_date',
            'recieved_date',
            'status'
        );
        $getBookBoReturn->where('is_deleted', 0);
        return $getBookBoReturn->paginate($request->per_page);
    }

    public function addBookBoReturn()
    {
        $adminSession = session()->get('session_token');
        $admin = Admin::where('id', $adminSession->admin_id)->where('is_deleted', 0)->first();
        $office = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();

        $validator = Validator::make($this->request->all(), [
            'description' => 'required'

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['office_ro'] = $office->office_code;
        $input['office_code'] = $office->office_code;
        $input['entry_date'] = Carbon::now()->toDateTimeString();
        $input['recieved_date'] = Carbon::now()->toDateTimeString();
        $input['user_id'] = $admin->id;
        $input['tranfer_type'] = 'R';
        $addBookBoReturn = new GmsBookBoTransfer($input);
        $addBookBoReturn->save();
        return $this->successResponse(self::CODE_OK, "Book Return Successfully!!", $addBookBoReturn);

    }


    public function getRoOffice()
    {
        $getRoOffice = GmsOffice::select(
            DB::raw('CONCAT(office_name,"(",office_code,")") AS originRo'), 'office_city'
        );
        $getRoOffice->where('office_type', "RO");
        $getRoOffice->where('is_deleted', 0);
        $query2 = $getRoOffice->get()->toArray();
        return $this->successResponse(self::CODE_OK, $query2);
    }

    public function getBoOffice()
    {
        $input = $this->request->all();
        $getRoOffice = GmsOffice::select(
            DB::raw('CONCAT(office_name,"(",office_code,")") AS originBo'),
        );
        $getRoOffice->where('office_city', $input['originBo']);
        $getRoOffice->where('is_deleted', 0);
        $query2 = $getRoOffice->get()->toArray();
        return $this->successResponse(self::CODE_OK, $query2);
    }

    public function onlyBoOffice()
    {
        $getBoOffice = GmsOffice::select(
            DB::raw('CONCAT(office_name,"(",office_code,")") AS originBo')
        );
        $getBoOffice->where('office_type', "BO");
        $getBoOffice->where('is_deleted', 0);
        $query2 = $getBoOffice->get()->toArray();
        return $query2;
    }


    public function searchBookingDetails(Request $request)
    {
        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $book_cust_code = $this->request->book_cust_code;
        $book_cust_type = $this->request->book_cust_type;
        $mf_no = $this->request->mf_no;
        //For Date wise Search
        $dataSearch = GmsBookingDtls::select('book_mfno as booking_mf_no',
            'book_cust_type as customer',
            'book_mfdate as booking_date',

            DB::raw('COUNT(book_cnno) as total_cnno'),
            DB::raw('SUM(book_weight) as total_weight'),
            DB::raw('SUM(book_pcs) as total_pcs'),
            DB::raw('SUM(book_billamt) as total_amount')
        );

        $dataSearch->where('is_deleted', 0);
        $dataSearch->groupBy('book_mfno');

        if ($request->has('from_date') && $request->has('to_date')) {
            $dataSearch->whereBetween('book_mfdate', [$from_date, $to_date]);
        }
        if ($request->has('book_cust_code')) {
            $dataSearch->Where('book_cust_code', $book_cust_code);
        }
        if ($request->has('book_cust_type')) {
            $dataSearch->Where('book_cust_type', $book_cust_type);
        }
        if ($request->has('mf_no')) {
            $dataSearch->where('book_mfno', $mf_no);
        }
        $dataSearch->where('is_deleted', 0);
        // $query2[] = $dataSearch->get()->toArray();

        return $dataSearch->paginate($request->per_page);
    }

    public function viewAllCustBookIssue(Request $request)
    {
        $gmsAllCustBookIssue = GmsBookCustIssue::join('gms_customer', 'gms_customer.cust_code', '=', 'gms_book_cust_issue.cust_code')->select('gms_book_cust_issue.id', 'gms_book_cust_issue.cust_code', 'gms_customer.cust_la_ent as cust_name', 'gms_book_cust_issue.cnno_start', 'gms_book_cust_issue.cnno_end', 'gms_book_cust_issue.qauantity', 'gms_book_cust_issue.rate_per_cnno', 'gms_book_cust_issue.entry_date');
        return $gmsAllCustBookIssue->paginate($request->per_page);
    }

    /**
     * @return mixed
     */
    public function viewAllCnnoCustlist()
    {
        $gmsCnnolist = GmsBookBoissue::where('is_deleted', 0)->select('cnno_start', 'cnno_end', 'qauantity as total', 'total_allotted', DB::raw('SUM(qauantity - total_allotted) as left_cnno'), 'rate_per_cnno', 'entry_date')->groupBy('cnno_start', 'cnno_end')->limit(100)->get();
        return $gmsCnnolist;
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function viewAllBookingDetails(Request $request)
    {
        $input = $this->request->all();
        $query = GmsBookingDtls::select(
            'gms_booking_dtls.book_mfno as booking_mf_no',
            'gms_booking_dtls.book_cust_type as customer',
            DB::raw('COUNT(gms_booking_dtls.book_cnno) as total_cnno'),
            DB::raw('SUM(gms_booking_dtls.book_weight) as total_weight'),
            DB::raw('SUM(gms_booking_dtls.book_pcs)As total_pcs'),
            DB::raw('SUM(gms_booking_dtls.book_billamt) As Amt'),
            DB::raw('DATE_FORMAT(gms_booking_dtls.book_mfdate,"%d %b, %Y") as booking_date'),

        );
        $query->where('gms_booking_dtls.is_deleted', 0);
        $query->groupBy('gms_booking_dtls.book_mfno');
        if ($request->isMethod('get')) {
            if ($request->has('q')) {
                $q = $request->q;
                $query->where('gms_booking_dtls.book_mfno', 'LIKE', '%' . $q . '%')
                    ->orWhere('gms_booking_dtls.book_cust_type', 'LIKE', '%' . $q . '%');
            }
            return $query->paginate($request->per_page);
        } else {
            $query1 = GmsBookingDtls::select('book_cnno as cnno', 'book_weight as weight', 'book_vol_weight as vol_weight', 'book_pcs as pcs', 'book_pin as pincode', 'book_location as city', 'book_product_type as product_type', 'book_mode as mode_type', 'book_doc as doc_type', 'book_billamt as bill_amount', 'book_topay as bill_amount', 'book_topay as topay_value', 'book_cod as code_value', 'delivery_t_remarks');
            $query1->where('book_mfno', $input['book_mfno']);
            $cnnoData = $query1->orderBy('created_at', 'desc')->get();
            return $cnnoData;
        }
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function viewAllStockIn(Request $request)
    {
        $viewAllStockIn = GmsBookCustIssue::where('is_deleted', 0)->where('created_by', 'BO')->select(DB::raw('concat("BO-SIN",gms_book_cust_issue.id) as type'), 'id', 'cnno_start', 'cnno_end', 'qauantity', 'entry_date as issue_date', 'entry_date as recieved_date', 'status');
        return $viewAllStockIn->paginate($request->per_page);
    }

    public function stockInRo()
    {
        $roStockIn = GmsBookBoTransfer::join('gms_book_bo_issue', 'gms_book_bo_transfer.iss_bo_id', '=', 'gms_book_bo_issue.id')->select('gms_book_bo_issue.id', 'gms_book_bo_transfer.description', 'gms_book_bo_transfer.cnno_start', 'gms_book_bo_transfer.cnno_end', 'gms_book_bo_issue.qauantity', 'gms_book_bo_transfer.entry_date', 'gms_book_bo_transfer.recieved_date', 'gms_book_bo_transfer.status')->orderBy('entry_date')->get();
        return $roStockIn;
    }

    public function stockSearchRo()
    {
        $searchStockInRo = GmsBookBoissue::select('office_code as bo_sf', 'cnno_start', 'cnno_end')->groupBy('office_code', 'office_type')->where('is_deleted', 0)->get();
        return $searchStockInRo;
    }
}


