<?php

namespace App\Http\Traits;

use App\Models\GmsRateCode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

trait RateTrait
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view_rateCard()
    {
        $validator = Validator::make($this->request->all(), [
            'card_id' => 'required|exists:gms_emp,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewRateCard = GmsRateCode::where('id', $input['card_id'])->where('is_deleted', 0)->paginate(5)->first();
        if (!$viewRateCard) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Rate Card Successfully!!", $viewRateCard);
        }
    }

    public function rateCode()
    {
        # code...
        $validator = Validator::make($this->request->all(), [
            'code_id' => 'required|exists:gms_rate_code,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewRateCode = GmsRateCode::where('id', $input['code_id'])->where('is_deleted', 0)->paginate(5)->first();

        if (!$viewRateCode) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "RateCode Show Successfully!!", $viewRateCode);
        }
    }
}
