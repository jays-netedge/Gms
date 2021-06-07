<?php

namespace App\Http\Traits;

use App\Models\GmsAlert;
use App\Models\GmsExrDtls;
use App\Models\GmsRateMasterAmdro;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\GmsComplaint;
use App\Models\GmsBookBlock;
use App\Models\GmsPayment;
use App\Models\GmsCnnoStock;
use Illuminate\Support\Facades\Crypt;

trait MasterTrait
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function masterAmdro()
    {
        $validator = Validator::make($this->request->all(), [
            'amdro_id' => 'required|exists:gms_rate_master_amdro,id',

        ]);

        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewDetails = GmsRateMasterAmdro::where('id', $input['amdro_id'])->paginate(5)->first();

        if (!$viewDetails) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Details Show Successfully!!", $viewDetails);
        }
    }

    public function addAmdro()
    {
        // echo 'hello';
        $validator = Validator::make($this->request->all(), [
            'product_code' => 'required',
            'cust_type' => 'required',
            'dest' => 'required',
            'min_charge_wt' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addrateAmdro = new GmsRateMasterAmdro($input);
        $addrateAmdro->save();

        return $this->successResponse(self::CODE_OK, "RateMaster Amdro Added Successfully!!", $addrateAmdro);

    }

    public function deleteAmdro()
    {
        $validator = Validator::make($this->request->all(), [
            'masterAmdro_id' => 'required|exists:gms_rate_master_amdro,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getAmdro = GmsRateMasterAmdro::where('id', $input['masterAmdro_id'])->first();
        if ($getAmdro != null) {
            $getAmdro->is_deleted = 1;
            $getAmdro->save();
            return $this->successResponse(self::CODE_OK, "Amdro Delete Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Amdro ID Not Found");
        }

    }

    public function del_Alert()
    {
        $validator = Validator::make($this->request->all(), [
            'alert_id' => 'required|exists:gms_alerts,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getAlert = GmsAlert::where('id', $input['alert_id'])->first();
        if ($getAlert != null) {
            $getAlert->is_deleted = 1;
            $getAlert->save();
            return $this->successResponse(self::CODE_OK, "Delete Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function checkStatusAmdro()
    {
        $validator = Validator::make($this->request->all(), [
            'statusAmdro_id' => 'required|exists:gms_rate_master_amdro,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getstatusAmdro = GmsRateMasterAmdro::where('id', $input['statusAmdro_id'])->first();
        if ($getstatusAmdro != null) {
            $getstatusAmdro->status = 1;
            $getstatusAmdro->save();
            return $this->successResponse(self::CODE_OK, "Status Deactivate Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Amdro ID Not Found");
        }
    }

    public function view_complaints()
    {
        $validator = Validator::make($this->request->all(), [
            'complaint_id' => 'required|exists:gms_complaint,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewComplaints = GmsComplaint::where('id', $input['complaint_id'])->where('is_deleted', 0)->paginate(5)->first();
        if (!$viewComplaints) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Show Complaints Successfully!!", $viewComplaints);
        }
    }

    public function view_bookBlock()
    {
        $validator = Validator::make($this->request->all(), [
            'block_id' => 'required|exists:gms_book_block,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewBookBlock = GmsBookBlock::where('id', $input['block_id'])->paginate(5)->first();
        if (!$viewBookBlock) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Show Block Book Successfully!!", $viewBookBlock);
        }
    }

    public function delete_bookBlock()
    {
        $input = $this->request->all();
        $adminSession = session()->get('session_token');
        $getBlockBook = GmsBookBlock::where('id', $input['block_id'])->where('is_deleted', 0)->first();
        if ($getBlockBook != null) {
            $getBlockBook->is_deleted = 1;
            $getBlockBook->save();
            return $this->successResponse(self::CODE_OK, "Block Book Deleted Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Admin Id Not Found");
        }
    }

    public function view_payment()
     {
        $validator = Validator::make($this->request->all(), [
            'payment_id' => 'required|exists:gms_payment,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewPayment = GmsPayment::where('id', $input['payment_id'])->select('invoice_receipt','cust_code','type','amount','description','posted_date')->paginate(5)->first();

        if (!$viewPayment) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Payment Details Show Successfully!!", $viewPayment);
        }
    }

    public function cnno_viewBlock()
    {
        $validator = Validator::make($this->request->all(), [
            'cnnoBook_id' => 'required|exists:gms_cnno_stock,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewCnnoStock = GmsCnnoStock::where('id', $input['cnnoBook_id'])->paginate(5)->first();

        if (!$viewCnnoStock) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Cnno Book Details Show Successfully!!", $viewCnnoStock);
        }
    }

   

    public function view_alert()
    {
        # code...
        $validator = Validator::make($this->request->all(), [
            'alert_id' => 'required|exists:gms_alerts,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewAlert = GmsAlert::where('id', $input['exr_id'])->paginate(5)->first();

        if (!$viewAlert) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Alert Show Successfully!!", $viewAlert);
        }
    }

}
