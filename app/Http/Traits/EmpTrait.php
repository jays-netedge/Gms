<?php

namespace App\Http\Traits;

use App\Models\GmsEmp;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

trait EmpTrait
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view_employee()
    {
        $validator = Validator::make($this->request->all(), [
            'emp_code' => 'required|exists:gms_emp,emp_code',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewEmployee = GmsEmp::where('emp_code', $input['emp_code'])->select('emp_code', 'emp_name', 'emp_add1', 'emp_add2', 'emp_phone', 'emp_email', 'emp_sex', 'emp_bldgrp', 'emp_dob', 'emp_doj', 'emp_dept', 'emp_dsg', 'emp_status', 'emp_dor', 'emp_rep_offtype', 'emp_rep_office')->where('is_deleted', 0)->paginate(5)->first();
        if (!$viewEmployee) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Show Employee Successfully!!", $viewEmployee);
        }
    }
}

