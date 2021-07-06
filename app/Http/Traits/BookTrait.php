<?php

namespace App\Http\Traits;

use App\Models\GmsBookBoissue;
use App\Models\GmsBookBoTransfer;
use App\Models\GmsBookRoIssue;
use App\Models\GmsBookCustIssue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\GmsBookEpodGenerate;
use App\Models\GmsBookingDtls;
use App\Models\GmsBookRoTransfer;

trait BookTrait
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function viewCusBookIssues()
    {
        $validator = Validator::make($this->request->all(), [
            'cusBookIssue_id' => 'required|exists:gms_book_cust_issue,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewCustBookIssue[] = GmsBookCustIssue::join('gms_office', 'gms_book_cust_issue.office_code', '=', 'gms_office.office_code')->select('cust_code', 'gms_book_cust_issue.cnno_start', 'gms_book_cust_issue.cnno_end', 'gms_book_cust_issue.qauantity', 'gms_book_cust_issue.rate_per_cnno', 'gms_book_cust_issue.entry_date', DB::raw('CONCAT(gms_office.office_add1,"-",gms_office.office_phone) as issueFrom'), 'gms_office.office_name as for')->where('gms_book_cust_issue.id', $input['cusBookIssue_id'])->first();
        if (!$viewCustBookIssue) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Show Customer Book Issue receipt Successfully!!", $viewCustBookIssue);
        }
    }

    public function deleteCusReturn()
    {
        $validator = Validator::make($this->request->all(), [
            'cusBookReturn_id' => 'required|exists:gms_book_bo_transfer,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getCustomerReturn = GmsBookBoTransfer::where('id', $input['cusBookReturn_id'])->first();;
        if ($getCustomerReturn != null) {
            $getCustomerReturn->is_deleted = 1;
            $getCustomerReturn->save();
            return $this->successResponse(self::CODE_OK, "CustomerReturn Book Delete Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "CustomerReturn ID Not Found");
        }
    }


    public function roBook()
    {
        # code...
        $validator = Validator::make($this->request->all(), [
            'roBook_id' => 'required|exists:gms_book_ro_issue,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getRoBook = GmsBookRoIssue::where('id', $input['roBook_id'])->with('gmsbookboissue')->paginate(5)->first();
        if (!$getRoBook) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Get Ro Book Details Successfully!!", $getRoBook);
        }
    }

    public function delRoBook()
    {
        # code...
        $validator = Validator::make($this->request->all(), [
            'roBook_id' => 'required|exists:gms_book_ro_issue,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getRoBook = GmsBookRoIssue::where('id', $input['roBook_id'])->first();;
        if ($getRoBook != null) {
            $getRoBook->is_deleted = 1;
            $getRoBook->save();
            return $this->successResponse(self::CODE_OK, "RoBook Delete Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Customer ID Not Found");
        }
    }

}
