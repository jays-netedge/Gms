<?php

namespace App\Http\Controllers\V1;

use App\Exports\BulkExport;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\GmsOffice;
use Illuminate\Support\Facades\Validator;
use App\Http\Traits\EmpTrait;
use Illuminate\Http\Request;
use App\Models\GmsEmp;
use Image;
use PDF;
use Dompdf;
use Maatwebsite\Excel\Facades\Excel;


class EmpController extends Controller
{
    use EmpTrait;

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
     * path="/addEmployee",
     * summary="add Employee",
     * operationId="addEmployee",
     *  tags={"Employee"},
     * @OA\Parameter(
     *   name="emp_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_num",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_city",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_add1",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_add2",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_phone",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_email",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_sex",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_bldgrp",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_dob",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_education",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_qualification",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_doj",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_dept",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_dsg",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_work_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_status",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_dor",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_rep_offtype",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_rep_office",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_rep_office_ro",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="delivery_code",
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

    public function addEmployee(Request $request)
    {
        $validator = Validator::make($this->request->all(), [
            'emp_num' => 'required',
            'emp_phone' => 'required|min:10|numeric',
            'emp_name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        if ($request->hasfile('profile_image')) {
            //getting the file from view
            $image = $request->file('profile_image');
            //getting the extension of the file
            $image_ext = $image->getClientOriginalExtension();
            //changing the name of the file
            $new_image_name = rand(123456, 999999) . "." . $image_ext;
            $destination_path = public_path('/employee');
            $image->move($destination_path, $new_image_name);
            $input['profile_image'] = $new_image_name;
        }
//==================================Crop Image ==================================================//
        if ($request->hasfile('profile_image_small')) {
            $image = $request->file('profile_image_small');
            $input['profile_image_small'] = time() . '.' . $image->extension();
            $destinationPath = public_path('/employee/thumbnail');
            $img = Image::make($image->path());

// ====================================[ Resize Image ]=========================================//
            $img->resize(150, 100, function ($constraint) {
                $constraint->aspectRatio();
            })->save($destinationPath . '/' . $input['profile_image_small']);

        }
        $addEmployee = new GmsEmp($input);
        $addEmployee->save();
        return $this->successResponse(self::CODE_OK, "Employee Created Successfully!!", $addEmployee);
    }

    /**
     * @OA\Post(
     * path="/viewEmployee",
     * summary="View Employee",
     * operationId="viewEmployee",
     *  tags={"Employee"},
     * @OA\Parameter(
     *   name="emp_id",
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
    public function viewEmployeeId()
    {
        return $this->view_employee();
    }


    public function viewAllEmployeeRo(Request $request)
    {
        $emp = GmsEmp::join('gms_office', 'gms_office.office_type', '=', 'gms_emp.emp_rep_offtype')->select('gms_emp.emp_name', 'gms_emp.emp_code', 'gms_emp.emp_rep_office', 'gms_emp.emp_rep_offtype', 'gms_emp.emp_city', 'gms_emp.emp_phone', 'gms_emp.emp_dept', 'gms_emp.emp_work_type', 'gms_emp.emp_status', 'gms_emp.status');

        if ($request->has('office_type')) {
            $emp->where('gms_office.office_type', $request->office_type);
        }
        if ($request->has('office_name')) {
            $emp->where('gms_office.office_name', $request->office_name);
        }
        if ($request->has('status')) {
            $emp->where('gms_emp.status', $request->status);
        }
        if ($request->has('q')) {
            $q = $request->q;
            $emp->where('gms_emp.emp_name', 'LIKE', '%' . $q . '%')
                ->orWhere('gms_emp.emp_code', 'LIKE', '%' . $q . '%')
                ->orWhere('gms_emp.emp_rep_office', 'LIKE', '%' . $q . '%')
                ->orWhere('gms_emp.emp_status', 'LIKE', '%' . $q . '%')
                ->orWhere('gms_emp.status', 'LIKE', '%' . $q . '%');
        }
        return $data = $emp->paginate($request->per_page);
    }

    public function viewAllEmployee(Request $request)
    {

        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();
        $office_code = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();

        $emp = GmsEmp::join('gms_office', 'gms_office.office_type', '=', 'gms_emp.emp_rep_offtype')->select('gms_emp.emp_name', 'gms_emp.emp_code', 'gms_emp.emp_rep_office', 'gms_emp.emp_rep_offtype', 'gms_emp.emp_city', 'gms_emp.emp_phone', 'gms_emp.emp_dept', 'gms_emp.emp_work_type', 'gms_emp.emp_status', 'gms_emp.status');

        if ($request->has('office_type')) {
            $emp->where('gms_office.office_type', $request->office_type);
        }
        if ($request->has('office_name')) {
            $emp->where('gms_office.office_name', $request->office_name);
        }
        if ($request->has('status')) {
            $emp->where('gms_emp.status', $request->status);
        }
        if ($request->has('q')) {
            $q = $request->q;
            $emp->where('gms_emp.emp_name', 'LIKE', '%' . $q . '%')
                ->orWhere('gms_emp.emp_code', 'LIKE', '%' . $q . '%')
                ->orWhere('gms_emp.emp_rep_office', 'LIKE', '%' . $q . '%')
                ->orWhere('gms_emp.emp_status', 'LIKE', '%' . $q . '%')
                ->orWhere('gms_emp.status', 'LIKE', '%' . $q . '%');
        }
        // $emp->where('emp_rep_offtype',);
        // $emp->where('emp_rep_office',);
        return $data = $emp->paginate($request->per_page);
    }

    public function empDetailsExport()
    {
        return Excel::download(new BulkExport, 'employee.xlsx');
    }

    public function empDetailsPdfExport(Request $request)
    {
        if (session()->has('session_token')) {
            $adminSession = session()->get('session_token');
            $admin = Admin::where('id', $adminSession->admin_id)->where('is_deleted', 0)->first();
            $office_code = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();
            $empDetails = GmsEmp::where('emp_rep_office', $office_code->office_code)->select(
                'id',
                'emp_code',
                'emp_name',
                'emp_add1',
                'emp_add2',
                'emp_phone',
                'emp_email',
                'emp_sex',
                'emp_bldgrp',
                'emp_dob',
                'emp_doj',
                'emp_dept',
                'emp_dsg',
                'emp_status',
                'emp_dor',
                'emp_rep_offtype',
                'emp_rep_office',)->where('is_deleted', 0)->get();

            $pdf = PDF::loadView('employee', $empDetails);
            return $pdf->download('employee.pdf');
        }
    }
}
