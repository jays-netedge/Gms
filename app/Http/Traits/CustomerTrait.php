<?php

namespace App\Http\Traits;

use App\Models\GmsCustomer;
use App\Models\GmsCustomerFranchisee;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\GmsColoader;
use App\Models\GmsAlert;

trait CustomerTrait
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function gms_customer()
    {
        $validator = Validator::make($this->request->all(), [
            'cus_id' => 'required|exists:gms_customer,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $response = array();
        $response['Profile'] = GmsCustomer::where('id', $input['cus_id'])->where('is_deleted', 0)->select('cust_type', 'cust_code', 'monthly_bill_type', 'service_courier', 'service_logistics', 'multi_region', 'service_gold', 'service_intracity', 'service_international', 'gst_applicable', 'service_reverse_booking', 'email_status', 'sms_status', 'cust_rep_office', 'created_office_code', 'cust_la_ent', 'cust_account_type', 'cust_la_address', 'cust_la_pan', 'cust_la_servicetax', 'cust_la_cin', 'cust_la_cindate', 'cust_name', 'cust_dob', 'cust_education', 'cust_qualification', 'cust_residen_address', 'cust_fat_wife_name', 'cust_pan', 'cust_cin', 'cust_phone', 'cust_email', 'cust_pb_nature', 'cust_pb_empdeployed', 'cust_pb_vehdeployed', 'cust_pb_turnover', 'cust_ad_bank_name', 'cust_ad_bank_branch', 'cust_ad_account_no', 'cust_ad_ifsc_code', 'cust_br_name', 'cust_br_name1', 'pan_card', 'passport_copy', 'driving_license', 'st_reg_certficate', 'aadhaar_card', 'voter_id', 'telephone_bill', 'photo')->first();
        $response['Contract_Details'] = GmsCustomer::where('id', $input['cus_id'])->where('is_deleted', 0)->select('cust_cd_contact_name', 'cust_cd_contract_date', 'cust_cd_renewal_date', 'cust_cd_exp_date', 'cust_cd_remarks')->first();
        $response['Security_Deposit'] = GmsCustomer::where('id', $input['cus_id'])->where('is_deleted', 0)->select('cust_secdip_fixed', 'cust_secdip_paid', 'cust_sec_chequeno', 'cust_sec_chequedate', 'cust_sd_fixed')->first();

        $response['Rate Code'] = GmsCustomer::join('gms_rate_code','gms_customer.cust_code','=','gms_rate_code.cust_code')->select('office_code','cust_type','rate_code','description','effect_date_from')->first();

        if (!$response) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $response;

        }
    }

    public function viewAlert()
    {
        $validator = Validator::make($this->request->all(), [
            'alert_id' => 'required|exists:gms_alerts,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewAlert = GmsAlert::where('id', $input['alert_id'])->where('is_deleted', 0)->paginate(5)->first();
        if (!$viewAlert) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Show Alert Successfully!!", $viewAlert);
        }
    }

    public function deleteAlert()
    {
        $validator = Validator::make($this->request->all(), [
            'alert_id' => 'required|exists:gms_alerts,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getCustomer = GmsAlert::where('id', $input['alert_id'])->first();;
        if ($getCustomer != null) {
            $getCustomer->is_deleted = 1;
            $getCustomer->save();
            return $this->successResponse(self::CODE_OK, "Customer Delete Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Customer ID Not Found");
        }
    }

    public function delete_customer()
    {
        $validator = Validator::make($this->request->all(), [
            'cus_id' => 'required|exists:gms_customer,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getCustomer = GmsCustomer::where('id', $input['cus_id'])->first();;
        if ($getCustomer != null) {
            $getCustomer->is_deleted = 1;
            $getCustomer->save();
            return $this->successResponse(self::CODE_OK, "Customer Delete Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Customer ID Not Found");
        }
    }

    public function delete_fraCustomer()
    {
        $validator = Validator::make($this->request->all(), [
            'fraCus_id' => 'required|exists:gms_customer_franchisee,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getCustomer = GmsCustomerFranchisee::where('id', $input['fraCus_id'])->first();;
        if ($getCustomer != null) {
            $getCustomer->is_deleted = 1;
            $getCustomer->save();
            return $this->successResponse(self::CODE_OK, " Franchisee Customer Delete Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Customer ID Not Found");
        }
    }

    public function typeOfCustomer()
    {
        $customerType = GmsCustomer::where('is_deleted', 0)->select('cust_type')->get();
        if (!$customerType) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Customer Type Show Successfully!!", $customerType);
        }
    }

    public function customerCode()
    {
        $input = $this->request->all();
        $customerType = GmsCustomer::where('is_deleted', 0)->where('cust_type', $input['cust_type'])->select('cust_code')->get();
        if (!$customerType) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Customer Type Show Successfully!!", $customerType);
        }
    }

    public function coloaderDelete()
    {
        $validator = Validator::make($this->request->all(), [
            'coloader_id' => 'required|exists:gms_coloader,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getColoader = GmsColoader::where('id', $input['coloader_id'])->where('is_deleted',0)->first();
        if ($getColoader != null) {
            $getColoader->is_deleted = 1;
            $getColoader->save();
            return $this->successResponse(self::CODE_OK, "Coloader Details Delete Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Customer ID Not Found");
        }
    }

    public function coloaderDetails()
    {
         $validator = Validator::make($this->request->all(), [
            'coloader_id' => 'required|exists:gms_coloader,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewColoader = GmsColoader::where('id', $input['coloader_id'])->where('is_deleted', 0)->first();
        if (!$viewColoader) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Show Coloader Successfully!!", $viewColoader);
        }
    }

    public function coloaderEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'coloader_id' => 'required|exists:gms_coloader,id',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getColoader = GmsColoader::where('id', $input['coloader_id'])->where('is_deleted', 0)->first();
        if ($getColoader) {
            $editColoader = GmsBookCategory::find($getColoader->id);
            $editColoader->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editColoader);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

}
