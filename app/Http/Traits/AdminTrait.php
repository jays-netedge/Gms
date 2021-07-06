<?php

namespace App\Http\Traits;

use App\Models\Admin;
use App\Models\GmsAlertConfig;
use App\Models\GmsBookCategory;
use App\Models\GmsBookCatRange;
use App\Models\GmsBookControls;
use App\Models\GmsBookPurchase;
use App\Models\GmsBookPurchaseDc;
use App\Models\GmsBookRelease;
use App\Models\GmsBookVendor;
use App\Models\GmsDept;
use App\Models\GmsDesg;
use App\Models\GmsEmpType;
use App\Models\GmsFuelCharges;
use App\Models\GmsGst;
use App\Models\GmsNdelReason;
use App\Models\GmsOffice;
use App\Models\GmsPincode;
use App\Models\GmsRateMaster;
use App\Models\GmsTaxType;
use App\Models\GmsBookRoIssue;
use App\Models\GmsCustomer;
use App\Models\GmsComplaint;
use App\Models\GmsInvoice;
use App\Models\GmsBookRoTransfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;


trait AdminTrait
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function bookCategoryEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'cat_id' => 'required|exists:gms_book_category,id',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getBookCategory = GmsBookCategory::where('id', $input['cat_id'])->where('is_deleted', 0)->first();
        if ($getBookCategory) {
            $editCategory = GmsBookCategory::find($getBookCategory->id);
            $editCategory->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editCategory);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }


    public function bookCatRangeEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'catRange_id' => 'required|exists:gms_book_cat_range,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getBookCatRange = GmsBookCatRange::where('id', $input['catRange_id'])->where('is_deleted', 0)->first();
        if ($getBookCatRange) {
            $editCatRange = GmsBookCatRange::find($getBookCatRange->id);
            $editCatRange->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editCatRange);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function officeDelete()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'office_id' => 'required|exists:gms_office,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getoffice = GmsOffice::where('id', $input['office_id'])->where('user_id', $sessionObject->admin_id)->first();
        if ($getoffice != null) {
            $getoffice->is_deleted = 1;
            $getoffice->save();
            return $this->successResponse(self::CODE_OK, "Delete Office Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function reasonDelete()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'reason_id' => 'required|exists:gms_ndel_reason,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getReason = GmsNdelReason::where('id', $input['reason_id'])->where('user_id', $sessionObject->admin_id)->first();
        if ($getReason != null) {
            $getReason->is_deleted = 1;
            $getReason->save();
            return $this->successResponse(self::CODE_OK, "Delete Reason Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function bookingCategoryDelete()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'book_id' => 'required|exists:gms_book_category,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getBookCat = GmsBookCategory::where('id', $input['book_id'])->where('is_deleted', 0)->first();
        if ($getBookCat != null) {
            $getBookCat->is_deleted = 1;
            $getBookCat->save();
            return $this->successResponse(self::CODE_OK, "Delete Booking Category Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }

    }

    public function bookInwardEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'inward_id' => 'required|exists:gms_book_purchase_dc,id',
            'dc_no' => 'required|numeric',
            'dc_date' => 'required|date',
            'purchase_no' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getBookPurchaseDC = GmsBookPurchaseDc::where('id', $input['inward_id'])->first();
        if ($getBookPurchaseDC) {
            $editCatPurDC = GmsBookPurchaseDc::find($getBookPurchaseDC->id);
            $editCatPurDC->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editCatPurDC);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function deleteVendor()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'vendor_id' => 'required|exists:gms_book_vendor,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getBookCat = GmsBookVendor::where('id', $input['vendor_id'])->where('is_deleted', 0)->first();
        if ($getBookCat != null) {
            $getBookCat->is_deleted = 1;
            $getBookCat->save();
            return $this->successResponse(self::CODE_OK, "Delete Booking Vendor Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function vendorDetails()
    {
        $validator = Validator::make($this->request->all(), [
            'vndr_id' => 'required|exists:gms_book_vendor,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }

        $viewVendor = GmsBookVendor::select('id', 'vendor_code', 'person', 'company', 'address1', 'address2', 'city', 'pincode', 'con_num1', 'con_num2', 'email1', 'email AS email2', 'tin_no', 'fax', 'bank_name', 'bank_branch_name', 'bank_account_no')->where('id', $this->request->vndr_id)->where('is_deleted', 0)->first();
        if (!$viewVendor) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Show Book Vendor Successfully!!", $viewVendor);
        }
    }

    public function bookVendorEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'vendor_id' => 'required|exists:gms_book_vendor,id',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getVendor = GmsBookVendor::where('id', $input['vendor_id'])->where('is_deleted', 0)->first();
        if ($getVendor) {
            $editVendor = GmsBookVendor::find($getVendor->id);
            $editVendor->update($input);
            return $this->successResponse(self::CODE_OK, "Vendor Update Successfully!!", $editVendor);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function bookDelete()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'book_id' => 'required|exists:gms_book_release,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getBookRelease = GmsBookRelease::where('id', $input['book_id'])->where('is_deleted', 0)->first();
        if ($getBookRelease != null) {
            $getBookRelease->is_deleted = 1;
            $getBookRelease->save();
            return $this->successResponse(self::CODE_OK, "Delete Booking Vendor Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function bookPurDelete()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'book_id' => 'required|exists:gms_book_purchase,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getBookPur = GmsBookPurchase::where('id', $input['book_id'])->where('is_deleted', 0)->first();
        if ($getBookPur != null) {
            $getBookPur->is_deleted = 1;
            $getBookPur->save();
            return $this->successResponse(self::CODE_OK, "Delete Booking Purchase Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function bookControl()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'book_id' => 'required|exists:gms_book_controls,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getBookControl = GmsBookControls::where('id', $input['book_id'])->where('is_deleted', 0)->first();
        if ($getBookControl != null) {
            $getBookControl->is_deleted = 1;
            $getBookControl->save();
            return $this->successResponse(self::CODE_OK, "Delete Booking Control Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function deprtEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'dept_id' => 'required|exists:gms_dept,id',
            'dept_code' => 'required|size:3|unique:gms_dept,dept_code,' . $this->request->dept_id,
            'dept_name' => 'required|unique:gms_dept,dept_name,' . $this->request->dept_id,

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getDeprt = GmsDept::where('id', $input['dept_id'])->where('is_deleted', 0)->first();
        if ($getDeprt) {
            $editDeprt = GmsDept::find($getDeprt->id);
            $editDeprt->update($input);
            return $this->successResponse(self::CODE_OK, "Department Update Successfully!!", $editDeprt);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function deptDelete()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'dept_id' => 'required|exists:gms_dept,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getDeprt = GmsDept::where('id', $input['dept_id'])->where('is_deleted', 0)->first();
        if ($getDeprt != null) {
            $getDeprt->is_deleted = 1;
            $getDeprt->save();
            return $this->successResponse(self::CODE_OK, "Delete Department Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function designationEdit()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'desig_id' => 'required|exists:gms_desg,id',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsDesg = GmsDesg::where('id', $input['desig_id'])->where('is_deleted', 0)->first();

        $getGmsDesg->desg_code = $input['desg_code'];
        $getGmsDesg->desg_name = $input['desg_name'];
        $getGmsDesg->user_id = $adminSession->admin_id;
        $getGmsDesg->entry_date = Carbon::now()->toDateTimeString();
        $getGmsDesg->update($input);
        return $this->successResponse(self::CODE_OK, "Designation Update Successfully!!", $getGmsDesg);

    }

    public function designationDelete()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'desig_id' => 'required|exists:gms_desg,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getDesi = GmsDesg::where('id', $input['desig_id'])->where('is_deleted', 0)->first();
        if ($getDesi != null) {
            $getDesi->is_deleted = 1;
            $getDesi->save();
            return $this->successResponse(self::CODE_OK, "Delete Designation Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function empTypeDelete()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'emp_id' => 'required|exists:gms_emp_type,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getEmpType = GmsEmpType::where('id', $input['emp_id'])->where('is_deleted', 0)->first();
        if ($getEmpType != null) {
            $getEmpType->is_deleted = 1;
            $getEmpType->save();
            return $this->successResponse(self::CODE_OK, "Delete Employee Type Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function empTypeEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'emp_id' => 'required|exists:gms_emp_type,id',
            'emp_type_code' => 'required|max:2|unique:gms_emp_type,emp_type_code,' . $this->request->emp_id,
            'emp_type_name' => 'required|unique:gms_emp_type,emp_type_name,' . $this->request->emp_id,

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsEmpType = GmsEmpType::where('id', $input['emp_id'])->where('is_deleted', 0)->first();
        if ($getGmsEmpType) {
            $editGmsEmpType = GmsEmpType::find($getGmsEmpType->id);
            $editGmsEmpType->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editGmsEmpType);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function loginDelete()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'login_id' => 'required|exists:admin,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getAdmin = Admin::where('id', $input['login_id'])->where('is_deleted', 0)->first();
        foreach ($getAdmin as $data) {
            DB::table('admin_session')->where('is_deleted', $data->is_deleted)->get();
        }
        if ($getAdmin != null) {
            $getAdmin->is_deleted = 1;
            $getAdmin->save();
            return $this->successResponse(self::CODE_OK, "Delete Login User Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function loginEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'login_id' => 'required|exists:admin,id',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getAdmin = Admin::where('id', $input['login_id'])->where('is_deleted', 0)->first();
        if ($getAdmin) {
            $editAdmin = Admin::find($getAdmin->id);
            $editAdmin->update($input);
            return $this->successResponse(self::CODE_OK, "User Update Successfully!!", $editAdmin);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function taxTypeEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'tax_id' => 'required|exists:gms_tax_type,id',
            'tax_name' => 'unique:gms_tax_type,tax_name,' . $this->request->tax_id,

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsTaxType = GmsTaxType::where('id', $input['tax_id'])->where('is_deleted', 0)->first();
        if ($getGmsTaxType) {
            $editGmsTaxType = GmsTaxType::find($getGmsTaxType->id);
            $editGmsTaxType->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editGmsTaxType);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function taxTypeDelete()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'tax_id' => 'required|exists:gms_tax_type,id',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getTaxType = GmsTaxType::where('id', $input['tax_id'])->where('is_deleted', 0)->first();
        if ($getTaxType != null) {
            $getTaxType->is_deleted = 1;
            $getTaxType->save();
            return $this->successResponse(self::CODE_OK, "Delete Tax Type Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function fuelChargesEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'fuel_id' => 'required|exists:gms_fuel_charges,id',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsFuelCharges = GmsFuelCharges::where('id', $input['fuel_id'])->where('is_deleted', 0)->first();
        if ($getGmsFuelCharges) {
            $editGmsFuelCharges = GmsFuelCharges::find($getGmsFuelCharges->id);
            $editGmsFuelCharges->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editGmsFuelCharges);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function fuelChargesDelete()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'fuel_id' => 'required|exists:gms_fuel_charges,id',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsFuelCharges = GmsFuelCharges::where('id', $input['fuel_id'])->where('is_deleted', 0)->first();
        if ($getGmsFuelCharges != null) {
            $getGmsFuelCharges->is_deleted = 1;
            $getGmsFuelCharges->save();
            return $this->successResponse(self::CODE_OK, "Delete Fuel Charges Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function rateMasterEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'rate_mast_id' => 'required|exists:gms_rate_master,id',
            'unique_rate_id' => 'numeric',
            'scheme_rate_id' => 'numeric',
            'flat_rate' => 'numeric',
            'min_charge_wt' => 'numeric',
            'from_wt' => 'numeric',
            'to_wt' => 'numeric',
            'rate' => 'numeric',
            'tranship_rate' => 'numeric',
            'addnl_type' => 'numeric',
            'addnl_wt' => 'numeric',
            'addnl_min' => 'numeric',
            'addnl_max' => 'numeric',
            'addnl_fixed' => 'numeric',
            'addnl_rate' => 'numeric',
            'extra_rate' => 'numeric',
            'tat' => 'numeric',
            'status' => 'numeric',
            'approved_status' => 'numeric',
            'entry_date' => 'date'

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsRateMaster = GmsRateMaster::where('id', $input['rate_mast_id'])->where('is_deleted', 0)->first();
        if ($getGmsRateMaster) {
            $editGmsRateMaster = GmsRateMaster::find($getGmsRateMaster->id);
            $editGmsRateMaster->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editGmsRateMaster);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function gstEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'gst_id' => 'required|exists:gms_gst,id',
            'gst_code' => 'unique:gms_gst,gst_code,' . $this->request->gst_id,
            'gst_rate' => 'numeric',
            'from_date' => 'date'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsGst = GmsGst::where('id', $input['gst_id'])->where('is_deleted', 0)->first();
        if ($getGmsGst) {
            $editGmsGst = GmsGst::find($getGmsGst->id);
            $editGmsGst->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editGmsGst);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function pincodeEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'pincode_id' => 'required|exists:gms_pincode,id',
            'pincode_value' => 'numeric|digits:6',
            'service' => 'size:1',
            'city_code' => 'max:5',
            'rep_code' => 'max:5',
            'courier' => 'max:1',
            'gold' => 'max:1',
            'logistics' => 'max:1',
            'intracity' => 'max:1',
            'international' => 'max:1',
            'regular' => 'max:1',
            'topay' => 'max:1',
            'cod' => 'max:1',
            'topay_cod' => 'max:1',
            'oda' => 'max:1',
            'mentioned_piece' => 'max:1',
            'fov_or' => 'max:1',
            'fov_cr' => 'max:1',
            'isc' => 'max:1',
            'edl' => 'max:1',
            'pin_status' => 'max:1',
            'posted' => 'date'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }

        $input = $this->request->all();
        $getGmsPincode = GmsPincode::where('id', $input['pincode_id'])->where('is_deleted', 0)->first();

        if ($getGmsPincode) {
            $editGmsPincode = GmsPincode::find($getGmsPincode->id);

            $editGmsPincode->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editGmsPincode);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function profileEdit()
    {
        $sessionObject = session()->get('session_token');
        $getoffice = GmsOffice::where('user_id', $sessionObject->admin_id)->first();
        if ($getoffice != null) {

            $validator = Validator::make($this->request->all(), [
                'office_code' => 'unique:gms_office,office_code,' . $getoffice->id,
                'office_name' => 'unique:gms_office,office_name,' . $getoffice->id,
                'office_ent' => 'unique:gms_office,office_ent,' . $getoffice->id,
                'office_add1' => 'unique:gms_office,office_add1,' . $getoffice->id,
                'office_add2' => 'unique:gms_office,office_add2,' . $getoffice->id,
                'office_pin' => 'unique:gms_office,office_pin,' . $getoffice->id,
                'office_phone' => 'numeric|min:10,office_phone,' . $getoffice->id,
                'office_email' => 'email|unique:gms_office,office_email,' . $getoffice->id,
                'office_pan' => 'min:10',
                'office_bank_ifsc' => 'min:11',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
            }
            $input = $this->request->all();
            $getGmsOffice = GmsOffice::where('id', $getoffice->id)->where('is_deleted', 0)->first();

            if ($getGmsOffice) {
                $editGmsOffice = GmsOffice::find($getGmsOffice->id);

                $editGmsOffice->update($input);

                if ($editGmsOffice->id) {

                    $getAdmin = Admin::where('office_id', $editGmsOffice->id)->where('is_deleted', 0)->first();
                    $header = $this->commonMgr->headerDetails();
                    $data['office_id'] = $editGmsOffice->id;
                    $data['username'] = $this->request->username;
                    $data['name'] = $this->request->office_contact;
                    $data['password'] = Hash::make($this->request->password);
                    $data['office_code'] = $this->request->office_code;
                    $data['city'] = $this->request->office_city;
                    $data['email'] = $this->request->office_email;
                    $data['phone'] = $this->request->office_phone;
                    $data['fax'] = $this->request->office_fax;
                    $data['address'] = $this->request->office_add1;
                    $data['user_type'] = strtoupper($this->request->office_type);
                    $data['status'] = 1;
                    $data['password_status'] = 1;

                    if ($getAdmin) {
                        $editAdmin = Admin::find($getAdmin->id);
                        $editAdmin->update($data);
                    }
                    return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editGmsOffice);
                }
            }
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function alertsConfigEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'alert_id' => 'required|exists:gms_alerts_config,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsAlertConfig = GmsAlertConfig::where('id', $input['alert_id'])->where('is_deleted', 0)->first();
        if ($getGmsAlertConfig) {
            $editGmsAlertConfig = GmsAlertConfig::find($getGmsAlertConfig->id);
            $editGmsAlertConfig->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editGmsAlertConfig);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function pincodeStatusEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'pincode_id' => 'required|exists:gms_pincode,id',
            'pin_status' => 'required|max:1',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsPincode = GmsPincode::where('id', $input['pincode_id'])->where('is_deleted', 0)->first();
        if ($getGmsPincode) {
            $editGmsPincode = GmsPincode::find($getGmsPincode->id);
            $editGmsPincode->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editGmsPincode);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function officeStatusEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'office_id' => 'required|exists:gms_office,id',
            'status' => 'required|max:1',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsOffice = GmsOffice::where('id', $input['office_id'])->where('is_deleted', 0)->first();
        if ($getGmsOffice) {
            $editGmsOffice = GmsOffice::find($getGmsOffice->id);
            $editGmsOffice->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editGmsOffice);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function customerApprovedStatusEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'customer_id' => 'required|exists:gms_customer,id',
            'approved_status' => 'required|max:1',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsCustomer = GmsCustomer::where('id', $input['customer_id'])->where('is_deleted', 0)->first();
        if ($getGmsCustomer) {
            $editGmsCustomer = GmsCustomer::find($getGmsCustomer->id);
            $editGmsCustomer->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editGmsCustomer);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function complaintStatusStatusEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'complaint_id' => 'required|exists:gms_complaint,id',
            'status' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsComplaint = GmsComplaint::where('id', $input['complaint_id'])->where('is_deleted', 0)->first();
        if ($getGmsComplaint) {
            $editGmsComplaint = GmsComplaint::find($getGmsComplaint->id);
            $editGmsComplaint->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editGmsComplaint);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function bookRoIssueStatusEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'book_issue_id' => 'required|exists:gms_book_ro_issue,id',
            'status' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsBookRoIssue = GmsBookRoIssue::where('id', $input['book_issue_id'])->where('is_deleted', 0)->first();
        if ($getGmsBookRoIssue) {
            $editGmsBookRoIssue = GmsBookRoIssue::find($getGmsBookRoIssue->id);
            $editGmsBookRoIssue->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editGmsBookRoIssue);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function bookRoTransferStatusEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'book_ro_transfer_id' => 'required|exists:gms_book_ro_transfer,id',
            'status' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsBookRoTransfer = GmsBookRoTransfer::where('id', $input['book_ro_transfer_id'])->where('is_deleted', 0)->first();
        if ($getGmsBookRoTransfer) {
            $editGmsBookRoTransfer = GmsBookRoTransfer::find($getGmsBookRoTransfer->id);
            $editGmsBookRoTransfer->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editGmsBookRoTransfer);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function employeeAdminStatusEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'admin_id' => 'required|exists:admin,id',
            'status' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getAdmin = Admin::where('id', $input['admin_id'])->where('user_type', 'E')->where('is_deleted', 0)->first();
        if ($getAdmin) {
            $editAdmin = Admin::find($getAdmin->id);
            $editAdmin->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editAdmin);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function taxTypeStatusEdit()
    {
        $validator = Validator::make($this->request->all(), [
            'tax_type_id' => 'required|exists:gms_tax_type,id',
            'status' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsTaxType = GmsTaxType::where('id', $input['tax_type_id'])->where('is_deleted', 0)->first();
        if ($getGmsTaxType) {
            $editGmsTaxType = GmsTaxType::find($getGmsTaxType->id);
            $editGmsTaxType->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editGmsTaxType);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

}
