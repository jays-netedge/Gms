<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\GmsCustomerGallery;
use App\Models\GmsInvoice;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\GmsCustomer;
use App\Models\GmsColoader;
use App\Models\GmsBookCustIssue;
use App\Models\GmsCustomerFranchisee;
use App\Models\GmsCustomerContacts;
use App\Models\GmsBookBoissue;
use App\Models\GmsCnnoStock;
use App\Http\Traits\CustomerTrait;
use App\Exports\CustomersExport;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use Image;


class CustomerController extends Controller
{
    use CustomerTrait;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @OA\Post(
     * path="/addCustomer",
     * summary="add Customer",
     * operationId="addCustomer",
     *  tags={"Customer"},
     * @OA\Parameter(
     *   name="cust_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_reach",
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
     *   name="cust_num",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_la_ent",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_location",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_ent",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_account_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_la_address",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_la_pan",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_la_servicetax",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_la_cindate",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_education",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_qualification",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_residen_address",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_pan",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_cin",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_phone",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_email",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_fax",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_telno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_cp_telno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_cp_pan",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_cp_taxno",
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function addCustomer(Request $request)
    {
        $sessionObject = session()->get('session_token');

        $validator = Validator::make($request->all(), [

            'cust_email' => 'required|email:rfc,dns|unique:gms_customer,cust_email',
            'cust_pan' => 'required|regex:/^[A-Z0-9]+$/|size:10|unique:gms_customer,cust_pan',
            'cust_phone' => 'required|regex:/^[A-Z0-9]+$/|size:10|unique:gms_customer,cust_phone',
            'cust_la_pan' => 'unique:gms_customer,cust_la_pan|regex:/^[A-Z0-9]+$/|size:10',
            'cust_la_servicetax' => 'unique:gms_customer,cust_la_servicetax|regex:/^[A-Z0-9]+$/',
            'pincode_value' => 'numeric|size:6',
            'cust_ad_account_no' => 'numeric|size:11',
            'cust_ad_ifsc_code' => 'numeric|size:11',
            'pan_card' => 'image|mimes:jpeg,jpg,png,gif',
            'passport_copy' => 'image|mimes:jpeg,jpg,png,gif',
            'driving_license' => 'image|mimes:jpeg,jpg,png,gif',
            'st_reg_certficate' => 'image|mimes:jpeg,jpg,png,gif',
            'aadhaar_card' => 'image|mimes:jpeg,jpg,png,gif',
            'voter_id' => 'image|mimes:jpeg,jpg,png,gif',
            'telephone_bill' => 'image|mimes:jpeg,jpg,png,gif',
            'photo' => 'image|mimes:jpeg,jpg,png,gif',
            'gallery_photo' => 'image|mimes:jpeg,jpg,png,gif',
            'gallery_photo1' => 'image|mimes:jpeg,jpg,png,gif',
            'gallery_photo2' => 'image|mimes:jpeg,jpg,png,gif',


        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }


        if ($request->hasfile('telephone_bill')) {
            $image = $request->file('telephone_bill');
            $image_ext = $image->getClientOriginalExtension();
            $telephone_bill = rand(123456, 999999) . "." . $image_ext;
            //  $data['telephone_bill'] = $telephone_bill;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $telephone_bill);
        }

        if ($request->hasfile('voter_id')) {
            $image = $request->file('voter_id');
            $image_ext = $image->getClientOriginalExtension();
            $voter_id = rand(123456, 999999) . "." . $image_ext;
            // $data['voter_id'] = $voter_id;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $voter_id);
        }
        if ($request->hasfile('aadhaar_card')) {
            $image = $request->file('aadhaar_card');
            $image_ext = $image->getClientOriginalExtension();
            $aadhaar_card = rand(123456, 999999) . "." . $image_ext;
            //  $data['aadhaar_card'] = $aadhaar_card;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $aadhaar_card);
        }
        if ($request->hasfile('st_reg_certficate')) {
            $image = $request->file('st_reg_certficate');
            $image_ext = $image->getClientOriginalExtension();
            $st_reg_certficate = rand(123456, 999999) . "." . $image_ext;
            //  $data['st_reg_certficate'] = $st_reg_certficate;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $st_reg_certficate);
        }

        if ($request->hasfile('driving_license')) {
            $image = $request->file('driving_license');
            $image_ext = $image->getClientOriginalExtension();
            $driving_license = rand(123456, 999999) . "." . $image_ext;
            // $data['driving_license'] = $driving_license;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $driving_license);
        }
        if ($request->hasfile('passport_copy')) {
            $image = $request->file('passport_copy');
            $image_ext = $image->getClientOriginalExtension();
            $passport_copy = rand(123456, 999999) . "." . $image_ext;
            //   $data['passport_copy'] = $passport_copy;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $passport_copy);
        }
        if ($request->hasfile('pan_card')) {
            $image = $request->file('pan_card');
            $image_ext = $image->getClientOriginalExtension();
            $pan_card = rand(123456, 999999) . "." . $image_ext;
            //  $data['pan_card'] = $pan_card;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $pan_card);
        }
        if ($request->hasfile('photo')) {
            $image = $request->file('photo');
            $image_ext = $image->getClientOriginalExtension();
            $photo = rand(123456, 999999) . "." . $image_ext;
            // $data['photo'] = $photo;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $photo);
        }
        if ($request->hasfile('gallery_photo')) {
            $image = $request->file('gallery_photo');
            $image_ext = $image->getClientOriginalExtension();
            $gallery_photo = rand(123456, 999999) . "." . $image_ext;
            //  $data['gallery_photo'] = $gallery_photo;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $gallery_photo);
        }
        if ($request->hasfile('gallery_photo1')) {
            $image = $request->file('gallery_photo1');
            $image_ext = $image->getClientOriginalExtension();
            $gallery_photo1 = rand(123456, 999999) . "." . $image_ext;
            // $data['gallery_photo1'] = $gallery_photo1;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $gallery_photo1);
        }
        if ($request->hasfile('gallery_photo2')) {
            $image = $request->file('gallery_photo2');
            $image_ext = $image->getClientOriginalExtension();
            $gallery_photo2 = rand(123456, 999999) . "." . $image_ext;
            //  $data['gallery_photo2'] = $gallery_photo2;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $gallery_photo2);
        }

        $customer = GmsCustomer::insert([

            'cust_type' => $request->cust_type,
            'cust_code' => $request->cust_code,
            'user_id' => $sessionObject->id,
            'sms_status' => $request->sms_status,
            'email_status' => $request->email_status,
            'multi_region' => $request->multi_region,
            'cust_la_ent' => $request->cust_la_ent,
            'cust_location' => $request->cust_location,
            'monthly_bill_type' => $request->monthly_bill_type,
            'service_courier' => $request->service_courier,
            'service_logistics' => $request->service_logistics,
            'service_gold' => $request->service_gold,
            'cust_account_type' => $request->cust_account_type,
            'cust_la_address' => $request->cust_la_address,
            'cust_la_pan' => $request->cust_la_pan,
            'cust_la_servicetax' => $request->cust_la_servicetax,
            'service_intracity' => $request->service_intracity,
            'service_international' => $request->service_international,
            'gst_applicable' => $request->gst_applicable,
            'pincode_value' => $request->pincode_value,
            'cust_name' => $request->cust_name,
            'cust_dob' => $request->cust_dob,
            'cust_email' => $request->cust_email,
            'cust_education' => $request->cust_education,
            'cust_qualification' => $request->cust_qualification,
            'cust_residen_address' => $request->cust_residen_address,
            'cust_fat_wife_name' => $request->cust_fat_wife_name,
            'cust_pan' => $request->cust_pan,
            'cust_phone' => $request->cust_phone,
            'cust_cd_contact_name' => $request->cust_cd_contact_name,
            'cust_cd_contract_date' => $request->cust_cd_contract_date,
            'cust_cd_renewal_date' => $request->cust_cd_renewal_date,
            'cust_cd_exp_date' => $request->cust_cd_exp_date,
            'cust_cd_remarks' => $request->cust_cd_remarks,
            'cust_sd_fixed' => $request->cust_sd_fixed,
            'cust_pb_nature' => $request->cust_pb_nature,
            'cust_pb_empdeployed' => $request->cust_pb_empdeployed,
            'cust_pb_vehdeployed' => $request->cust_pb_vehdeployed,
            'cust_pb_turnover' => $request->cust_pb_turnover,
            'cust_ad_bank_name' => $request->cust_ad_bank_name,
            'cust_ad_bank_branch' => $request->cust_ad_bank_branch,
            'cust_ad_account_no' => $request->cust_ad_account_no,
            'cust_ad_ifsc_code' => $request->cust_ad_ifsc_code,
            'cust_br_name' => $request->cust_br_name,
            'cust_br_address' => $request->cust_br_address,
            'cust_br_contact' => $request->cust_br_contact,
            'cust_br_name1' => $request->cust_br_name1,
            'cust_br_address1' => $request->cust_br_address1,
            'cust_br_contact1' => $request->cust_br_contact1,
            'telephone_bill' => isset($telephone_bill) ? $telephone_bill : '',
            'aadhaar_card' => isset($aadhaar_card) ? $aadhaar_card : '',
            'voter_id' => isset($voter_id) ? $voter_id : '',
            'st_reg_certficate' => isset($st_reg_certficate) ? $st_reg_certficate : '',
            'driving_license' => isset($driving_license) ? $driving_license : '',
            'passport_copy' => isset($passport_copy) ? $passport_copy : '',
            'pan_card' => isset($pan_card) ? $pan_card : '',
            'photo' => isset($photo) ? $photo : '',
            'gallery_photo' => isset($gallery_photo) ? $gallery_photo : '',
            'gallery_photo1' => isset($gallery_photo1) ? $gallery_photo1 : '',
            'gallery_photo2' => isset($gallery_photo2) ? $gallery_photo2 : ''

        ]);

        return $customer;
    }


    /**
     * @OA\Post(
     * path="/editCustomer",
     * summary="edit Customer",
     * operationId="editCustomer",
     *  tags={"Customer"},
     * @OA\Parameter(
     *   name="cus_id",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
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
     *   name="cust_reach",
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
     *   name="cust_num",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_la_ent",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_location",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_ent",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_account_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_la_address",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_la_pan",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_la_servicetax",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_la_cindate",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_education",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_qualification",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_residen_address",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_pan",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_cin",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_phone",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_email",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_fax",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_telno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_cp_telno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_cp_pan",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_cp_taxno",
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
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */

    public function editCustomer(Request $request)
    {

        $validatedData = $request->validate([
            'pan_card' => 'image|mimes:jpeg,jpg,png,gif',
            'passport_copy' => 'image|mimes:jpeg,jpg,png,gif',
            'driving_license' => 'image|mimes:jpeg,jpg,png,gif',
            'st_reg_certficate' => 'image|mimes:jpeg,jpg,png,gif',
            'aadhaar_card' => 'image|mimes:jpeg,jpg,png,gif',
            'voter_id' => 'image|mimes:jpeg,jpg,png,gif',
            'telephone_bill' => 'image|mimes:jpeg,jpg,png,gif',
            'photo' => 'image|mimes:jpeg,jpg,png,gif',
            'gallery_photo' => 'image|mimes:jpeg,jpg,png,gif',
            'gallery_photo1' => 'image|mimes:jpeg,jpg,png,gif',
            'gallery_photo2' => 'image|mimes:jpeg,jpg,png,gif',
        ]);
        $getCustomer = GmsCustomer::where('id', $request->id)->where('is_deleted', 0)->first();
        $sessionObject = session()->get('session_token');
        $editCustomer = GmsCustomer::find($getCustomer->id);

        if ($request->hasfile('telephone_bill')) {
            $image = $request->file('telephone_bill');
            $image_ext = $image->getClientOriginalExtension();
            $telephone_bill = rand(123456, 999999) . "." . $image_ext;
            $data['telephone_bill'] = $telephone_bill;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $telephone_bill);
        }

        if ($request->hasfile('voter_id')) {
            $image = $request->file('voter_id');
            $image_ext = $image->getClientOriginalExtension();
            $voter_id = rand(123456, 999999) . "." . $image_ext;
            $data['voter_id'] = $voter_id;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $voter_id);
        }
        if ($request->hasfile('aadhaar_card')) {
            $image = $request->file('aadhaar_card');
            $image_ext = $image->getClientOriginalExtension();
            $aadhaar_card = rand(123456, 999999) . "." . $image_ext;
            $data['aadhaar_card'] = $aadhaar_card;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $aadhaar_card);
        }
        if ($request->hasfile('st_reg_certficate')) {
            $image = $request->file('st_reg_certficate');
            $image_ext = $image->getClientOriginalExtension();
            $st_reg_certficate = rand(123456, 999999) . "." . $image_ext;
            $data['st_reg_certficate'] = $st_reg_certficate;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $st_reg_certficate);
        }

        if ($request->hasfile('driving_license')) {
            $image = $request->file('driving_license');
            $image_ext = $image->getClientOriginalExtension();
            $driving_license = rand(123456, 999999) . "." . $image_ext;
            $data['driving_license'] = $driving_license;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $driving_license);
        }
        if ($request->hasfile('passport_copy')) {
            $image = $request->file('passport_copy');
            $image_ext = $image->getClientOriginalExtension();
            $passport_copy = rand(123456, 999999) . "." . $image_ext;
            $data['passport_copy'] = $passport_copy;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $passport_copy);
        }
        if ($request->hasfile('pan_card')) {
            $image = $request->file('pan_card');
            $image_ext = $image->getClientOriginalExtension();
            $pan_card = rand(123456, 999999) . "." . $image_ext;
            $data['pan_card'] = $pan_card;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $pan_card);
        }
        if ($request->hasfile('photo')) {
            $image = $request->file('photo');
            $image_ext = $image->getClientOriginalExtension();
            $photo = rand(123456, 999999) . "." . $image_ext;
            $data['photo'] = $photo;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $photo);
        }
        if ($request->hasfile('gallery_photo')) {
            $image = $request->file('gallery_photo');
            $image_ext = $image->getClientOriginalExtension();
            $gallery_photo = rand(123456, 999999) . "." . $image_ext;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $gallery_photo);
        }
        if ($request->hasfile('gallery_photo1')) {
            $image = $request->file('gallery_photo1');
            $image_ext = $image->getClientOriginalExtension();
            $gallery_photo1 = rand(123456, 999999) . "." . $image_ext;
            $data['gallery_photo1'] = $gallery_photo1;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $gallery_photo1);
        }
        if ($request->hasfile('gallery_photo2')) {
            $image = $request->file('gallery_photo2');
            $image_ext = $image->getClientOriginalExtension();
            $gallery_photo2 = rand(123456, 999999) . "." . $image_ext;
            $data['gallery_photo2'] = $gallery_photo2;
            $destination_path = public_path('/customer');
            $image->move($destination_path, $gallery_photo2);
        }

        $editCustomer->user_id = $sessionObject->id;
        $editCustomer->cust_type = $request->cust_type;
        $editCustomer->cust_code = $request->cust_code;
        $editCustomer->user_id = $sessionObject->id;
        $editCustomer->sms_status = $request->sms_status;
        $editCustomer->email_status = $request->email_status;
        $editCustomer->multi_region = $request->multi_region;
        $editCustomer->cust_la_ent = $request->cust_la_ent;
        $editCustomer->cust_location = $request->cust_location;
        $editCustomer->monthly_bill_type = $request->monthly_bill_type;
        $editCustomer->service_courier = $request->service_courier;
        $editCustomer->service_logistics = $request->service_logistics;
        $editCustomer->service_gold = $request->service_gold;
        $editCustomer->cust_account_type = $request->cust_account_type;
        $editCustomer->cust_la_address = $request->cust_la_address;
        $editCustomer->cust_la_pan = $request->cust_la_pan;
        $editCustomer->cust_la_servicetax = $request->cust_la_servicetax;
        $editCustomer->service_intracity = $request->service_intracity;
        $editCustomer->service_international = $request->service_international;
        $editCustomer->gst_applicable = $request->gst_applicable;
        $editCustomer->pincode_value = $request->pincode_value;
        $editCustomer->cust_name = $request->cust_name;
        $editCustomer->cust_dob = $request->cust_dob;
        $editCustomer->cust_email = $request->cust_email;
        $editCustomer->cust_education = $request->cust_education;
        $editCustomer->cust_qualification = $request->cust_qualification;
        $editCustomer->cust_residen_address = $request->cust_residen_address;
        $editCustomer->cust_fat_wife_name = $request->cust_fat_wife_name;
        $editCustomer->cust_pan = $request->cust_pan;
        $editCustomer->cust_phone = $request->cust_phone;
        $editCustomer->cust_cd_contact_name = $request->cust_cd_contact_name;
        $editCustomer->cust_cd_contract_date = $request->cust_cd_contract_date;
        $editCustomer->cust_cd_renewal_date = $request->cust_cd_renewal_date;
        $editCustomer->cust_cd_exp_date = $request->cust_cd_exp_date;
        $editCustomer->cust_cd_remarks = $request->cust_cd_remarks;
        $editCustomer->cust_sd_fixed = $request->cust_sd_fixed;
        $editCustomer->cust_pb_nature = $request->cust_pb_nature;
        $editCustomer->cust_pb_empdeployed = $request->cust_pb_empdeployed;
        $editCustomer->cust_pb_vehdeployed = $request->cust_pb_vehdeployed;
        $editCustomer->cust_pb_turnover = $request->cust_pb_turnover;
        $editCustomer->cust_ad_bank_name = $request->cust_ad_bank_name;
        $editCustomer->cust_ad_bank_branch = $request->cust_ad_bank_branch;
        $editCustomer->cust_ad_account_no = $request->cust_ad_account_no;
        $editCustomer->cust_ad_ifsc_code = $request->cust_ad_ifsc_code;
        $editCustomer->cust_br_name = $request->cust_br_name;
        $editCustomer->cust_br_address = $request->cust_br_address;
        $editCustomer->cust_br_contact = $request->cust_br_contact;
        $editCustomer->cust_br_name1 = $request->cust_br_name1;
        $editCustomer->cust_br_address1 = $request->cust_br_address1;
        $editCustomer->cust_br_contact1 = $request->cust_br_contact1;
        $editCustomer->telephone_bill = isset($telephone_bill) ? $telephone_bill : '';
        $editCustomer->aadhaar_card = isset($aadhaar_card) ? $aadhaar_card : '';
        $editCustomer->voter_id = isset($voter_id) ? $voter_id : '';
        $editCustomer->st_reg_certficate = isset($st_reg_certficate) ? $st_reg_certficate : '';
        $editCustomer->driving_license = isset($driving_license) ? $driving_license : '';
        $editCustomer->passport_copy = isset($passport_copy) ? $passport_copy : '';
        $editCustomer->pan_card = isset($pan_card) ? $pan_card : '';
        $editCustomer->photo = isset($photo) ? $photo : '';
        $editCustomer->gallery_photo = isset($gallery_photo) ? $gallery_photo : '';
        $editCustomer->gallery_photo1 = isset($gallery_photo1) ? $gallery_photo1 : '';
        $editCustomer->gallery_photo2 = isset($gallery_photo2) ? $gallery_photo2 : '';
        $data[] = $editCustomer->toArray();
        /*print_r($data);die;*/

        $editCustomer->update($data);
        return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editCustomer);


    }


    /**
     * @OA\Post(
     * path="/viewCustomer",
     * summary="View Customer",
     * operationId="viewCustomer",
     *  tags={"Customer"},
     * @OA\Parameter(
     *   name="cus_id",
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
    public function viewCustomer()
    {
        return $this->gms_customer();
    }

    public function viewAllCustomer(Request $request)
    {
        $gmsCustomer = GmsCustomer::
        select('gms_customer.id', 'gms_customer.cust_name', 'gms_customer.cust_code', 'gms_customer.cust_type', 'gms_customer.cust_city', 'gms_customer.email_status', 'gms_customer.sms_status', 'gms_customer.approved_status', 'gms_city.state_code')
            ->leftJoin("gms_city", "gms_city.city_code", "=", "gms_customer.cust_city")
            ->where('gms_customer.is_deleted', 0);

        if ($request->has('q')) {
            $q = $request->q;
            $gmsCustomer->where('gms_customer.cust_name', 'LIKE', '%' . $q . '%')
                ->orWhere('gms_customer.cust_code', 'LIKE', '%' . $q . '%');

        }
        $gmsCustomer->orderBy('id', 'desc');
        return $gmsCustomer->paginate($request->per_page);
    }


    /**
     * @OA\Post(
     * path="/deleteCustomer",
     * summary="Delete Customer",
     * operationId="deleteCustomer",
     *  tags={"Customer"},
     * @OA\Parameter(
     *   name="cus_id",
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
    public function deleteCustomer()
    {
        return $this->delete_customer();
    }

    /**
     * @OA\Post(
     * path="/addFranchiseeCus",
     * summary="add FranchiseeCus",
     * operationId="add Franchisee Customer",
     *  tags={"CustomerFranchisee"},
     * @OA\Parameter(
     *   name="fran_cust_inc",
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
     *   name="fran_cust_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fran_cust_city",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fran_cust_name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fran_cust_email",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="created_branch",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
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
    public function addFranchiseeCus()
    {
        $session_token = session()->get('session_token');
        $user_type = Admin::where('id', $session_token->admin_id)->first();

        $input = $this->request->all();
        $custCodeArr = explode("_", $input['cust_code']);
        $cust_code = $custCodeArr[0];
        $city_code = $custCodeArr[1];
        $input['cust_code'] = $cust_code;
        $input['fran_cust_code'] = "";
        $input['fran_cust_city'] = $city_code;
        $input['created_branch'] = $user_type->user_type;
        $input['user_id'] = $session_token->admin_id;
        $addFranCus = new GmsCustomerFranchisee($input);
        $addFranCus->save();
        return $this->successResponse(self::CODE_OK, "Franchisee Customer Created Successfully!!", $addFranCus);

    }


    /**
     * @OA\Post(
     * path="/editFranchiseeCus",
     * summary="edit FranchiseeCus",
     * operationId="edit Franchisee Customer",
     *  tags={"CustomerFranchisee"},
     * @OA\Parameter(
     *   name="fraCus_id",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fran_cust_inc",
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
     *   name="fran_cust_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fran_cust_city",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fran_cust_name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fran_cust_email",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="created_branch",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
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
    public function editFranchiseeCus()
    {
        $session_token = session()->get('session_token');
        $user_type = Admin::where('id', $session_token->admin_id)->first();
        // $validator = Validator::make($this->request->all(), [
        //     'fraCus_id' => 'required|exists:gms_customer_franchisee,id',
        //     'fran_cust_name' => 'required',
        //     'fran_cust_email' => 'required|email',
        //     'fran_cust_city' => 'required'
        // ]);
        // if ($validator->fails()) {
        //     return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        // }
        $input = $this->request->all();
        $getFraCustomer = GmsCustomerFranchisee::where('id', $input['fraCus_id'])->where('is_deleted', 0)->first();
        if ($getFraCustomer) {
            $editFraCustomer = GmsCustomerFranchisee::find($getFraCustomer->id);
            $custCodeArr = explode("_", $input['cust_code']);
            $cust_code = $custCodeArr[0];
            $city_code = $custCodeArr[1];
            $input['cust_code'] = $cust_code;
            $input['fran_cust_code'] = "";
            $input['fran_cust_city'] = $city_code;
            $editFraCustomer->update($input);
            return $this->successResponse(self::CODE_OK, "Franchisee Customer Update Successfully!!", $editFraCustomer);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Franchisee Customer Not Found");
        }
    }

    /**
     * @OA\Post(
     * path="/viewFranchiseeCus",
     * summary="View Franchisee Customer",
     * operationId="viewFranchisee",
     *  tags={"CustomerFranchisee"},
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
    public function viewFranchiseeCus()
    {
        $viewFranchiseCus = GmsCustomer::where('cust_type', '=', 'CF')->select('cust_code', 'cust_la_ent', 'cust_city')->paginate(5)->all();
        return $this->successResponse(self::CODE_OK, "View Franchisee Customer Successfully!!", $viewFranchiseCus);
    }

    public function viewAllFranchisee(Request $request)
    {
        $gmsCusFra = GmsCustomerFranchisee::select('fran_cust_code', 'cust_code', 'fran_cust_name', 'entry_date')->where('is_deleted', 0);
        return $data = $gmsCusFra->paginate($request->per_page);
    }


    public function viewFranchiseeDetails(Request $request)
    {
        $customerFranchisee = GmsCustomerFranchisee::where('is_deleted', 0)->select('fran_cust_code', 'cust_code', 'fran_cust_name', 'entry_date');

        if ($request->has('q')) {
            $q = $request->q;
            $customerFranchisee->where('fran_cust_code', 'LIKE', '%' . $q . '%')
                ->orWhere('cust_code', 'LIKE', '%' . $q . '%')
                ->orWhere('fran_cust_name', 'LIKE', '%' . $q . '%');

        }
        $customerFranchisee->orderBy('id', 'desc');
        return $customerFranchisee->paginate($request->per_page);
    }

    public function viewDetailsFraCus()
    {
        $validator = Validator::make($this->request->all(), [
            'fraCus_id' => 'required|exists:gms_customer_franchisee,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $GmsCustomerFranchisee = GmsCustomerFranchisee::where('id', $input['fraCus_id'])->where('is_deleted', 0)->select('fran_cust_inc', 'cust_code', 'fran_cust_code', 'fran_cust_city', 'fran_cust_name')->paginate(5)->first();
        if (!$GmsCustomerFranchisee) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Show Customer Franchisee Details Successfully!!", $GmsCustomerFranchisee);
        }
    }

    /**
     * @OA\Post(
     * path="/deleteFraCustomer",
     * summary="Delete Franchisee Customer",
     * operationId="deleteFranchisee",
     *  tags={"CustomerFranchisee"},
     * @OA\Parameter(
     *   name="fraCus_id",
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
    public function deleteFraCustomer()
    {
        return $this->delete_fraCustomer();
    }

    /**
     * @OA\Post(
     * path="/addCusContact",
     * summary="add Customer Contact",
     * operationId="addCustomerContact",
     *  tags={"CustomerContact"},
     * @OA\Parameter(
     *   name="cust_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
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
     *   name="cust_name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_dob",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_education",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_qualification",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_residen_address",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_fat_wife_name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_pan",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_cin",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_phone",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_email",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_fax",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_telno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_cp_name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_cp_pan",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_cp_taxno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_cp_vattinno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_cp_exciseno",
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
    public function addCusContact()
    {
        $validator = Validator::make($this->request->all(), [
            'cust_name' => 'required',
            'cust_dob' => 'required',
            'cust_phone' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addCusContact = new GmsCustomerContacts($input);
        $addCusContact->save();
        return $this->successResponse(self::CODE_OK, "Customer Contact Created Successfully!!", $addCusContact);
    }

    public function customerType()
    {
        return $this->typeOfCustomer();
    }

    public function custCode()
    {
        return $this->customerCode();
    }

    public function getCustByCustType()
    {
        $validator = Validator::make($this->request->all(), [
            'cust_id' => 'required|exists:gms_customer,cust_type',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getCustByCustType = GmsCustomer::where('is_deleted', 0)->where('cust_type', $input['cust_id'])->get();

        if (!$getCustByCustType) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Customer Type Show Successfully!!", $getCustByCustType);
        }
    }

    public function getCusPinCode()
    {
        $validator = Validator::make($this->request->all(), [
            'cust_code' => 'required|exists:gms_customer,cust_code',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getCustByPincode = GmsCustomer::where('is_deleted', 0)->where('cust_code', $input['cust_code'])->select('pincode_value')->get();

        if (!$getCustByPincode) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Pincode Show Successfully!!", $getCustByPincode);
        }
    }

    public function custReport(Request $request)
    {
        $cust = GmsInvoice::join('gms_customer', 'gms_customer.cust_code', '=', 'gms_invoice.customer_code')->select('gms_customer.cust_code', 'gms_customer.cust_name', 'gms_invoice.total_weight', 'gms_invoice.fr_grand_total', DB::raw("(SELECT gms_invoice.invoice_status WHERE invoice_status = 'Y') as delivered"), DB::raw("(SELECT gms_invoice.invoice_status WHERE gms_invoice.invoice_status = 'N') as NDR"), 'grand_total');

        if ($request->has('gms_invoice.month')) {
            $cust->where('month', $request->month);
        }
        if ($request->has('gms_invoice.year')) {
            $cust->where('year', $request->year);
        }
        if ($request->has('gms_customer.cust_type')) {
            $cust->where('gms_customer.cust_type', $request->cust_type);
        }
        return $data = $cust->paginate($request->per_page);
    }


    public function exportCustomer()
    {
        return Excel::download(new CustomersExport, 'customers.xlsx');
    }

    public function cnnoBookingStockView(Request $request)
    {
        $cust = GmsBookCustIssue::join('gms_book_bo_issue', 'gms_book_cust_issue.iss_bo_id', '=', 'gms_book_bo_issue.id')->select('gms_book_cust_issue.cust_code as bo_sf', 'gms_book_cust_issue.cnno_start', 'gms_book_cust_issue.cnno_end', 'gms_book_cust_issue.qauantity', 'gms_book_cust_issue.total_allotted',
        //DB::raw('concat(sum(gms_book_cust_issue.qauantity)- sum(gms_book_cust_issue.total_allotted)) As qty_left')
        );

        if ($request->has('cust_type')) {
            $cust->where('gms_book_cust_issue.cust_type', $request->cust_type);
        }
        if ($request->has('cust_code')) {
            $cust->where('gms_book_cust_issue.cust_code', $request->cust_code);
        }
        $result = $cust->get()->toArray();
        $qauantity = 0;
        $total_allotted = 0;
        foreach ($result as $row) {
            # code...

            $qauantity = $qauantity + $row['qauantity'];
            $total_allotted = $total_allotted + $row['total_allotted'];
        }

        $data['left_qty'] = $qauantity - $total_allotted;
        $collection = new Collection([$result, $data]);
        return $collection;
    }

    public function getBoSinDropDown()
    {
        $getSinNo = GmsBookBoissue::join('gms_book_cust_issue', 'gms_book_bo_issue.id', '=', 'gms_book_cust_issue.iss_bo_id')->select(
            DB::raw('concat("SIN",gms_book_cust_issue.id," (",gms_book_bo_issue.cnno_start," - " ,gms_book_bo_issue.cnno_end,")",gms_book_cust_issue.qauantity) As CNNO'))->where('gms_book_bo_issue.status', 'A')->take(100)->get();
        return $getSinNo;
    }

    public function getCustSinDropDown()
    {
        $getSinNo = GmsBookCustIssue::select(
            DB::raw('concat("CUST",gms_book_cust_issue.id," (",gms_book_cust_issue.cnno_start," - " ,gms_book_cust_issue.cnno_end,")",gms_book_cust_issue.qauantity) As CNNO'))->where('gms_book_cust_issue.status', 'A')->take(100)->get();
        return $getSinNo;
    }

    public function getCustSinDetails()
    {
        $getSin = GmsBookCustIssue::select(
            DB::raw('concat("CUST","-","SIN",gms_book_cust_issue.id) As type'),
            'gms_book_cust_issue.cust_code as customer',
            'gms_book_cust_issue.cnno_start',
            'gms_book_cust_issue.cnno_end',
            'gms_book_cust_issue.qauantity',
            'gms_book_cust_issue.status',
        // DB::raw('concat((gms_book_cust_issue.qauantity) - (gms_book_cust_issue.total_allotted)) As left'),
        )->where('gms_book_cust_issue.id', $this->request->id)->where('gms_book_cust_issue.status', 'A')->get();

        $rangeAvailable = GmsCnnoStock::select(
            DB::raw('CONCAT("[",stock_cnno,"]") As cnno')
        )->where('stock_iss_cust_id', $this->request->id)->get();

        $data1 = DB::table('gms_cnno_stock')->select('stock_cnno')->where('stock_iss_cust_id', $this->request->id)->orderBy('stock_cnno', 'desc')->first();
        $add = $data1->stock_cnno;

        $data2 = DB::table('gms_cnno_stock')->select('stock_cnno')->where('stock_iss_cust_id', $this->request->id)->orderBy('stock_cnno', 'asc')->first();
        $min = $data2->stock_cnno;

        $left = $add - $min;
        $data['cnno_details'] = $getSin;
        $data['rangeAvailable'] = $rangeAvailable;
        $data['left'] = $left;
        $collection = new Collection([$data]);
        return $collection;
    }

    public function getBoSinDetails()
    {
        $getSin = GmsBookBoissue::join('gms_book_bo_transfer', 'gms_book_bo_issue.id', '=', 'gms_book_bo_transfer.iss_bo_id')
            ->select(
                DB::raw('concat("CUST","-","SIN",gms_book_bo_issue.id) As type'),
                'gms_book_bo_issue.cnno_start',
                'gms_book_bo_issue.cnno_end',
                'gms_book_bo_issue.qauantity',
                'gms_book_bo_issue.status',
                'gms_book_bo_transfer.office_code as from',
                'gms_book_bo_transfer.dest_office_code as to',
            )->where('gms_book_bo_issue.id', $this->request->id)->where('gms_book_bo_issue.status', 'A')->get();
        return $getSin;
    }

    public function viewAllRoColoader()
    {
        $viewRoColoader = GmsColoader::where('coloader_code','coloader_name','coloader_type')->where('is_deleted', 0)->where('coloader_ro', 'BLRRO')->where('status', 'A')->get();
        return $viewRoColoader;
    }

    public function deleteRoColoader()
    {
        return $this->coloaderDelete();
    }

    public function getColoaderDetails()
    {
        return $this->coloaderDetails();
    }

    public function editColoaderDetails()
    {
        return $this->coloaderEdit();
    }


}
