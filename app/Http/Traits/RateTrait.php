<?php

namespace App\Http\Traits;

use App\Models\GmsRateCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\AdminSession;
use App\Models\GmsOffice;

trait RateTrait

{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function rateCode()
    {
        # code...
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->first();
        $office = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();

        $input = $this->request->all();
        $response['card_for'] = GmsRateCode::where('office_code', 'BLRRO')->where('rate_type', 'CCBR')->select('rate_code', 'description')->where('is_deleted', 0)->get();
        $response['city_fran_rates_booking_card'] = GmsRateCode::where('office_code', 'BLRRO')->where('rate_type', 'CFBR')->select('rate_code', 'description')->where('is_deleted', 0)->get();
        $response['direct_customer_rates_contract_rate_card'] = GmsRateCode::where('office_code', 'BLRRO')->where('rate_type', 'DCBR')->select('rate_code', 'description')->where('is_deleted', 0)->get();

        if (!$response) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Rate Card Show Successfully!!", $response);
        }
    }

    public function deliveryRateCardEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'rate_card_id' => 'required|exists:gms_rate_master_delivery,id',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getDeliveryRateCard = GmsRateMasterDelivery::where('id', $input['rate_card_id'])->where('is_deleted', 0)->first();
        if ($getDeliveryRateCard) {
            $editRateCard = GmsRateMasterDelivery::find($getDeliveryRateCard->id);
            $editRateCard->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editRateCard);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function deliveryRateCardDelete()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'rate_card_id' => 'required|exists:gms_rate_master_delivery,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsRateMasterDelivery = GmsRateMasterDelivery::where('id', $input['rate_card_id'])->where('is_deleted', 0)->first();
        if ($getGmsRateMasterDelivery != null) {
            $getGmsRateMasterDelivery->is_deleted = 1;
            $getGmsRateMasterDelivery->save();
            return $this->successResponse(self::CODE_OK, "Delete Rate Card Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

}
