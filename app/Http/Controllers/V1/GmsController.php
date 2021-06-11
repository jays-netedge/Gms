<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\GmsBookingDtls;
use App\Models\GmsCustomer;
use App\Models\GmsDmfDtls;
use App\Models\GmsEmp;
use App\Models\GmsMfDtls;
use App\Models\GmsOffice;
use App\Models\GmsPincode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\GmsNews;
use App\Models\GmsRateBillingDiscount;
use App\Http\Traits\MasterTrait;
use App\Models\GmsAlert;
use App\Models\GmsBookBlock;
use App\Models\GmsComplaint;
use App\Models\GmsCnnoStock;
use App\Models\GmsColoader;
use App\Models\GmsRateMasterBbsro;
use Illuminate\Support\Facades\Crypt;
use App\Models\GmsPayment;
use App\Models\GmsCountries;
use App\Models\GmsCity;
use App\Models\GmsState;
use App\Models\GmsZone;
use App\Models\GmsPmfDtls;
use App\Models\GmsColoaderDtls;
use Carbon\Carbon;


class GmsController extends Controller
{

    use MasterTrait;

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
     * path="/viewComplaints",
     * summary="View Complaints",
     * operationId="viewComplaints",
     *  tags={"Complaints"},
     * @OA\Parameter(
     *   name="complaint_id",
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
    public function viewComplaints()
    {
        return $this->view_complaints();
    }


    /**
     * @OA\Post(
     * path="/addBookBlock",
     * summary="addBookBlock",
     * operationId="viewComplaints",
     *  tags={"Book"},
     * @OA\Parameter(
     *   name="description",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="multiple_cnno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cnno_start",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cnno_end",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="block_type",
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

    public function addBookBlock()
    {
        $validator = Validator::make($this->request->all(), [
            'description' => 'required',
            'multiple_cnno' => 'required',
            'block_type' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addBookBlock = new GmsBookBlock($input);
        $addBookBlock->save();
        return $this->successResponse(self::CODE_OK, "Book Block Added Successfully!!", $addBookBlock);
    }

    /**
     * @OA\Post(
     * path="/viewBookBlock",
     * summary="View Book Block",
     * operationId="viewBookBlock",
     *  tags={"BookBlock"},
     * @OA\Parameter(
     *   name="block_id",
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
    public function viewBookBlock()
    {
        return $this->view_bookBlock();
    }

    /**
     * @OA\Post(
     * path="/deleteBlockBook",
     * summary="Delete Block Book",
     * operationId="deleteBookBlock",
     *  tags={"BookBlock"},
     * @OA\Parameter(
     *   name="block_id",
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
    public function deleteBlockBook()
    {
        return $this->delete_bookBlock();
    }

    /**
     * @OA\Post(
     * path="/addNews",
     * summary="Add News",
     * operationId="addNews",
     *  tags={"News"},
     * @OA\Parameter(
     *   name="title",
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
     *   name="image",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="type",
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
    public function addNews(Request $request)
    {
        $validator = Validator::make($this->request->all(), [
            'title' => 'required',
            'description' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        if ($request->hasfile('image')) {
            //getting the file from view
            $image = $request->file('image');
            //getting the extension of the file
            $image_ext = $image->getClientOriginalExtension();
            //changing the name of the file
            $new_image_name = rand(123456, 999999) . "." . $image_ext;
            $destination_path = public_path('/public/news/');
            $image->move($destination_path, $new_image_name);
            $input['image'] = $new_image_name;
        }
        $addNews = new GmsNews($input);
        $addNews->save();
        return $this->successResponse(self::CODE_OK, "News Created Successfully!!", $addNews);
    }

    /**
     * @OA\Post(
     * path="/viewPayment",
     * summary="View Payment",
     * operationId="viewPayment",
     *  tags={"Payment"},
     * @OA\Parameter(
     *   name="payment_id",
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

    public function addPayment()
    {
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('is_deleted', 0)->where('id', $sessionObject->admin_id)->first();
        $validator = Validator::make($this->request->all(), [
            'invoice_receipt' => 'required',
            'amount' => 'required',
            'description' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['created_office'] = $admin->office_code;
        $addPayment = new GmsPayment($input);
        $addPayment->save();
        return $this->successResponse(self::CODE_OK, "Payment Added Successfully!!", $addPayment);

    }

    public function viewPayment()
    {
        return $this->view_payment();
    }

    public function viewAllPayment(Request $request)
    {
        $gmsPayment = GmsPayment::where('is_deleted', 0)->select('id', 'cust_code', 'paid_through', 'deposit_DD', 'amount', 'posted_date');
        $gmsPayment->orderBy('id', 'desc');
        return $gmsPayment->paginate($request->per_page);
    }

    public function editPayment()
    {
        $validator = Validator::make($this->request->all(), [
            'payment_id' => 'required|exists:gms_payment,id',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getpayment = GmsPayment::where('id', $input['payment_id'])->where('is_deleted', 0)->first();
        if ($getpayment) {
            $editpayment = GmsPayment::find($getpayment->id);
            $editpayment->update($input);
            return $this->successResponse(self::CODE_OK, "Payment Update Successfully!!", $editpayment);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function deletePayment()
    {
        $validator = Validator::make($this->request->all(), [
            'payment_id' => 'required|exists:gms_payment,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getpayment = GmsPayment::where('id', $input['payment_id'])->first();;
        if ($getpayment != null) {
            $getpayment->is_deleted = 1;
            $getpayment->save();
            return $this->successResponse(self::CODE_OK, "Payment Deleted Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Payment ID Not Found");
        }
    }

    /**
     * @OA\Post(
     * path="/cnnoViewBlock",
     * summary="cnno ViewBlock",
     * operationId="Cnno viewBlock",
     *  tags={"CnnoBook"},
     * @OA\Parameter(
     *   name="cnnoBook_id",
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
    public function cnnoViewBlock()
    {
        return $this->cnno_viewBlock();
    }

    /**
     * @OA\Post(
     * path="/addDiscount",
     * summary="add Discount",
     * operationId="addDiscount",
     *  tags={"Book"},
     * @OA\Parameter(
     *   name="max_no",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="billing_rate_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="billing_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="delivery_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="discount_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
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
     * @OA\Parameter(
     *   name="rate_per_weight",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="invoice_value_range",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="invoice_value_percentage",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="flat_rate",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="slab_rate",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="from_wt",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="to_wt",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="rate",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="addnl",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="addnl_wt",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="addnl_rate",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="non_from_wt",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="non_to_wt",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="non_rate",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="non_addnl",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="non_addnl_wt",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="non_addnl_rate",
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
    public function addDiscount()
    {
        $validator = Validator::make($this->request->all(), [
            'billing_rate_code' => 'required',
            'billing_type' => 'required',
            'delivery_type' => 'required',
            'discount_type' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addDiscount = new GmsRateBillingDiscount($input);
        $addDiscount->save();
        return $this->successResponse(self::CODE_OK, "Discount Added Successfully!!", $addDiscount);
    }

    public function viewAmdro()
    {
        return $this->masterAmdro();
    }


    public function addMasterAmdro()
    {
        return $this->addAmdro();
    }

    public function deleteMasterAmdro()
    {
        return $this->deleteAmdro();
    }

    public function statusAmdro()
    {
        return $this->checkStatusAmdro();
    }

    public function viewAllCity(Request $request)
    {
        $city = GmsCity::select(
            'city_code',
            'city_name',
            'state_id',
            'state_code',
            'metro',
            'status',
            'entry_date',
            'user_id',
            'sys_id',
            DB::raw('CONCAT(city_name ,"/",city_code ,"/", state_code) AS city_rep_bo'),
        );
        $cityList = $city->get();
        return $this->successResponse(self::CODE_OK, "View All City Listed!!", $cityList);
    }


    public function addmasterBbsro(Request $request)
    {
        $validator = Validator::make($this->request->all(), [
            'product_code' => 'required',
            'cust_type' => 'required',
            'entry_date' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['entry_date'] = date('d-m-Y', strtotime($input['entry_date']));
        $addrateBbsro = new GmsRateMasterBbsro($input);
        $addrateBbsro->save();

        return $this->successResponse(self::CODE_OK, "RateMaster Bbsro Added Successfully!!", $addrateBbsro);
    }

    public function deletemasterBbsro()
    {
        $validator = Validator::make($this->request->all(), [
            'bbsro_id' => 'required|exists:gms_rate_master_bbsro,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getBbsro = GmsRateMasterBbsro::where('id', $input['bbsro_id'])->first();;
        if ($getBbsro != null) {
            $getBbsro->is_deleted = 1;
            $getBbsro->save();
            return $this->successResponse(self::CODE_OK, "Delete Rate Master Bbsro Delete Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Bbsro ID Not Found");
        }
    }

    /**
     * @OA\Post(
     * path="/assignPinCode",
     * summary="assign PinCode",
     * operationId="assign PinCode",
     *  tags={"PinCode"},
     * @OA\Parameter(
     *   name="pincode_value",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="service",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="city_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="rep_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="courier",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="gold",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="logistics",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="intracity",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="international",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="regular",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="topay",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cod",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="topay_cod",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="oda",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mentioned_piece",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fov_or",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fov_cr",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="isc",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="edl",
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
    public function assignPinCode()
    {
        $validator = Validator::make($this->request->all(), [
            'service' => 'required',
            'city_code' => 'required',
            'rep_code' => 'required',
            'courier' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addPinCode = new GmsPincode($input);
        $addPinCode->save();
        return $this->successResponse(self::CODE_OK, "Pin Assign Successfully!!", $addPinCode);
    }

    public function viewPincode()
    {
        $validator = Validator::make($this->request->all(), [
            'gms_Citycode' => 'required|exists:gms_pincode,city_code',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewPincode = GmsPincode::where('city_code', $input['gms_Citycode'])->paginate(5)->first();
        if (!$viewPincode) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Show Data with Pincode  Successfully!!", $viewPincode);
        }
    }

    public function getCityPincode(Request $request)
    {
        $pincode = GmsPincode::join('gms_city', 'gms_city.city_code', '=', 'gms_pincode.city_code')
            ->join('gms_office', 'gms_office.office_code', '=', 'gms_pincode.branch_id')
            ->select('gms_city.city_name', DB::raw('concat(gms_office.office_name,"/",gms_office.office_code) As reporting_branch'), 'gms_pincode.pincode_value', 'gms_pincode.service', 'gms_pincode.courier', 'gms_pincode.gold', 'gms_pincode.logistics', 'gms_pincode.topay', 'gms_pincode.cod');

        if ($request->has('pincode_value')) {
            $pincode->where('gms_pincode.pincode_value', 'like', '%' . $request->pincode_value . '%');
            $pinCode = $pincode->get();
            return $this->successResponse(self::CODE_OK, "Show Data with Pincode Successfully!!", $pinCode);
        }

        $city = GmsCity::join('gms_state', 'gms_state.state_code', '=', 'gms_city.state_code')
            ->join('gms_zone', 'gms_zone.id', '=', 'gms_state.zone_id')
            ->join('gms_pincode', 'gms_pincode.city_code', '=', 'gms_city.city_code')
            ->select('gms_zone.zone_name', DB::raw('CONCAT(gms_state.state_name," - ",gms_state.state_code) As state'), DB::raw('CONCAT(gms_city.city_name,"/",gms_city.city_code) As city'), 'gms_pincode.city_code as pincode', 'gms_pincode.service', 'gms_pincode.courier', 'gms_pincode.gold', 'gms_pincode.logistics', 'gms_pincode.topay', 'gms_pincode.cod');
        if ($request->has('city_name')) {
            $city->where('gms_city.city_name', 'like', '%' . $request->city_name . '%');
        }
        $city = $city->get();
        return $this->successResponse(self::CODE_OK, "Show Data with City Successfully!!", $city);
    }


    /**
     * @OA\Post(
     * path="/addAlert",
     * summary="Add Alert",
     * operationId="addAlert",
     *  tags={"Alert"},
     * @OA\Parameter(
     *   name="branch_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="branch_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="alert_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="sms_status",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="email_status",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="email1",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="email2",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mobile1",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mobile2",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="entry_date",
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
    public function addAlert()
    {
        // $validator = Validator::make($this->request->all(), [
        //     'alert_type' => 'required',
        //     'name' => 'required',
        //     'email1' => 'required',
        //     'mobile1' => 'required',
        // ]);
        // if ($validator->fails()) {
        //     return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        // }
        $input = $this->request->all();
        $gmsAlert = new GmsAlert($input);
        $gmsAlert->save();

        return $this->successResponse(self::CODE_OK, "Alert Added Successfully!!", $gmsAlert);
    }

    /**
     * @OA\Post(
     * path="/viewAlert",
     * summary="View Alert",
     * operationId="viewAlert",
     *  tags={"Alert"},
     * @OA\Parameter(
     *   name="exr_id",
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
    public function viewAlert()
    {
        return $this->view_alert();
    }

    public function deleteAlert()
    {

        return $this->del_Alert();
    }

    public function editAlert()
    {
        $validator = Validator::make($this->request->all(), [
            'alert_id' => 'required|exists:gms_alerts,id',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getEmailAlert = GmsAlert::where('id', $input['alert_id'])->where('is_deleted', 0)->first();
        if ($getEmailAlert) {
            $editEmail = GmsAlert::find($getEmailAlert->id);
            $editEmail->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editEmail);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }


    public function advanceSearchIpmf(Request $request)
    {
        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $pmf_ro = $this->request->pmf_ro;
        $pmf_origin = $this->request->pmf_origin;
        $pmf_mode = $this->request->pmf_mode;
        $pmf_no = $this->request->pmf_no;

        $advanceSearchIpmf = GmsPmfDtls::select(
            'gms_pmf_dtls.pmf_no as opmf',
            'gms_pmf_dtls.pmf_origin as created_by',
            'gms_pmf_dtls.pmf_mode as mode',

            DB::raw('concat("[",pmf_origin,",",pmf_dest,"]")As Type'),
            DB::raw('DATE_FORMAT(gms_pmf_dtls.pmf_date, "%d %b, %Y") as date'),
            DB::raw('DATE_FORMAT(gms_pmf_dtls.pmf_time, "%l:%i %p") as time'),
            DB::raw('SUM(gms_pmf_dtls.pmf_wt) as total_weight'),
            DB::raw('SUM(gms_pmf_dtls.pmf_vol_wt) as VolWt'),
            DB::raw('SUM(gms_pmf_dtls.pmf_pcs)As Pcs'),
            DB::raw('concat(count(pmf_no),"/",sum(pmf_pcs)- sum(pmf_received_pcs)) As cnno_status'),
            DB::raw('SUM(gms_pmf_dtls.pmf_amt) As Amt'),
        );
        $advanceSearchIpmf->groupBy('gms_pmf_dtls.pmf_no');

        if ($request->has('from_date') && $request->has('to_date')) {
            $advanceSearchIpmf->whereBetween('pmf_date', [$from_date, $to_date]);
        }
        if ($request->has('pmf_ro')) {
            $advanceSearchIpmf->Where('pmf_ro', $pmf_ro);
        }
        if ($request->has('pmf_origin')) {
            $advanceSearchIpmf->Where('pmf_origin', $pmf_origin);
        }
        if ($request->has('pmf_mode')) {
            $advanceSearchIpmf->where('pmf_mode', $pmf_mode);
        }
        if ($request->has('pmf_no')) {
            $advanceSearchIpmf->where('pmf_no', $pmf_no);
        }
        return $advanceSearchIpmf->paginate($request->per_page);

        // $query2[] = $advanceSearchIpmf->get()->toArray();
        // return $this->successResponse(self::CODE_OK, $query2);
    }


    public function advanceSearchOpmf(Request $request)
    {
        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $pmf_ro = $this->request->pmf_ro;
        $pmf_origin = $this->request->pmf_origin;
        $pmf_mode = $this->request->pmf_mode;
        $pmf_no = $this->request->pmf_no;
        $query = GmsPmfDtls::select(
            'gms_pmf_dtls.pmf_no as opmf',
            'gms_pmf_dtls.pmf_date as created_date',
            'gms_pmf_dtls.pmf_time as time',
            'gms_pmf_dtls.pmf_emp_code as emp',
            'gms_pmf_dtls.pmf_mode as mode_type',
            DB::raw('COUNT(CASE WHEN gms_pmf_dtls.pmf_received_pcs <> 0 THEN 1 END) AS total_recevied'),
            DB::raw('COUNT(gms_pmf_dtls.pmf_no) As total_cnno'),
            DB::raw('SUM(gms_pmf_dtls.pmf_wt) as total_weight'),
            DB::raw('SUM(gms_pmf_dtls.pmf_vol_wt) as vol_weight'),
            DB::raw('SUM(gms_pmf_dtls.pmf_actual_wt) as actual_weight'),
            DB::raw('SUM(gms_pmf_dtls.pmf_pcs)As pcs'),
            DB::raw('SUM(gms_pmf_dtls.pmf_amt) As Amt'),
            DB::raw('concat("[",gms_pmf_dtls.pmf_origin,",",gms_pmf_dtls.pmf_dest,"]")As ManifestType'),
            DB::raw('DATE_FORMAT(gms_pmf_dtls.updated_at,"%d %b, %Y") as last_update_date'),

        );
        $query->groupBy('gms_pmf_dtls.pmf_no');

        if ($request->has('from_date') && $request->has('to_date')) {
            $query->whereBetween('pmf_date', [$from_date, $to_date]);
        }

        if ($request->has('pmf_ro')) {
            $query->Where('pmf_ro', $pmf_ro);
        }
        if ($request->has('pmf_origin')) {
            $query->Where('pmf_origin', $pmf_origin);
        }
        if ($request->has('pmf_mode')) {
            $query->where('pmf_mode', $pmf_mode);
        }
        if ($request->has('pmf_no')) {
            $query->where('pmf_no', $pmf_no);
        }
        $query->where('gms_pmf_dtls.is_deleted', 0);
        //$query2[] = $query->get()->toArray();

        return $query->paginate($request->per_page);

    }

    

    public function searchDpmf(Request $request)
    {
        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $dmf_type = $this->request->dmf_type;
        $dmf_emp = $this->request->dmf_emp;
        $fran_cust_name = $this->request->fran_cust_name;
        $dmf_cnno_type = $this->request->dmf_cnno_type;
        $dmf_mfno = $this->request->dmf_mfno;
        $dataSearch = GmsDmfDtls::select(
            DB::raw('CONCAT(dmf_type,"-",dmf_emp) as customer_type'),
            'dmf_mfno as mnf_code',
            'dmf_cnno_type as dmf_type',
            DB::raw('COUNT(dmf_cnno) as total_cnno'),
            DB::raw('DATE_FORMAT(dmf_mfdate, "%d %b, %Y") as date'),
            DB::raw('DATE_FORMAT(dmf_mftime, "%l:%i %p") as time'),
            DB::raw('SUM(dmf_wt) as total_weight'),
            DB::raw('SUM(dmf_pcs) as total_pcs'),

        );
        if ($request->has('from_date') && $request->has('to_date')) {
            $dataSearch->whereBetween('dmf_mfdate', [$from_date, $to_date]);
        }
        if ($request->has('dmf_type')) {
            $dataSearch->Where('dmf_type', $dmf_type);
        }
        if ($request->has('dmf_emp')) {
            $dataSearch->Where('dmf_emp', $dmf_emp);
        }
        if ($request->has('dmf_cnno_type')) {
            $dataSearch->Where('dmf_cnno_type', $dmf_cnno_type);
        }
        if ($request->has('dmf_mfno')) {
            $dataSearch->where('dmf_mfno', $dmf_mfno);
        }
        $dataSearch->where('is_deleted', 0);
        $dataSearch->groupBy('dmf_mfno');
        //  $query2[] = $dataSearch->get()->toArray();
        return $dataSearch->paginate($request->per_page);
    }

    public function advancedSearchDpmfUpdate(Request $request)
    {
        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $dmf_type = $this->request->dmf_type;
        $dmf_emp = $this->request->dmf_emp;
        $dmf_cnno_type = $this->request->dmf_cnno_type;
        $dmf_mfno = $this->request->dmf_mfno;
        $dataSearch = GmsDmfDtls::select(
            DB::raw('CONCAT(dmf_type,"-",dmf_emp) as customer_type'),
            'dmf_mfno as mnf_code',
            'dmf_cnno_type as dmf_type',
            DB::raw('COUNT(dmf_cnno) as total_cnno'),
            DB::raw('DATE_FORMAT(dmf_mfdate, "%d %b, %Y") as date'),
            DB::raw('DATE_FORMAT(dmf_mftime, "%l:%i %p") as time'),
            DB::raw('SUM(dmf_wt) as weight'),
            DB::raw('SUM(dmf_pcs) as pcs'),
        );

        // join('gms_emp', 'gms_emp.emp_code', '=', 'gms_dmf_dtls.dmf_emp')
        // ->join('gms_customer_franchisee', 'gms_customer_franchisee.cust_code', '=', 'gms_dmf_dtls.dmf_fr_code')

        if ($request->has('from_date') && $request->has('to_date')) {
            $dataSearch->whereBetween('dmf_mfdate', [$from_date, $to_date]);
        }
        if ($request->has('dmf_type')) {
            $dataSearch->Where('dmf_type', $dmf_type);
        }
        if ($request->has('dmf_emp')) {
            $dataSearch->Where('dmf_emp', $dmf_emp);
        }
        if ($request->has('dmf_cnno_type')) {
            $dataSearch->Where('dmf_cnno_type', $dmf_cnno_type);
        }
        if ($request->has('dmf_mfno')) {
            $dataSearch->where('dmf_mfno', $dmf_mfno);
        }
        $dataSearch->where('is_deleted', 0);
        $dataSearch->where('dmf_cn_status', "=", "D");
        $dataSearch->groupBy('dmf_mfno');
        //$query2[] = $dataSearch->get()->toArray();
        return $dataSearch->paginate($request->per_page);

    }

    public function coloaderCustomers(Request $request)
    {
        $mode = $this->request->mode;
        $adminSession = session()->get('session_token');
        $coloader = GmsColoader::where('coloader_ro', 'BLRRO')->whereIn('coloader_type', ['AR', 'SF', 'TR'])->select(DB::raw('CONCAT(coloader_name," - ",coloader_code) As customers'));
        if ($request->has('mode')) {
            $coloader->Where('coloader_type', $mode);
        }
        return $coloader->paginate($request->per_page);
    }

    public function getOpmfDetailsForColoaders(Request $request)
    {
        // $pmf_origin = $this->request->pmf_origin;
        $pmf_dest_ro = $request->pmf_dest_ro;
        $pmf_dest = $request->pmf_dest;
        $pmf_cd_no = $request->pmf_cd_no;
        $pmf_mode = $request->pmf_mode;
        $pmf_city = $request->pmf_city;
        $getPmfDetails = GmsPmfDtls::join('gms_city', 'gms_city.city_code', '=', 'gms_pmf_dtls.pmf_city')->select(
            'gms_pmf_dtls.pmf_no',
            'gms_pmf_dtls.pmf_date',
            DB::raw('COUNT(gms_pmf_dtls.pmf_cnno) as totalcnno'),
            DB::raw('SUM(gms_pmf_dtls.pmf_wt) as weight'),
            DB::raw('SUM(gms_pmf_dtls.pmf_vol_wt) as vol_weight'),
            'gms_city.city_name',
            'gms_pmf_dtls.pmf_dest',
            'gms_pmf_dtls.pmf_dest_ro'
        );
        $getPmfDetails->groupBy('pmf_no');
        if ($request->has('pmf_dest')) {
            $getPmfDetails->Where('gms_pmf_dtls.pmf_dest', $pmf_dest);
        }
        if ($request->has('pmf_dest_ro')) {
            $getPmfDetails->Where('gms_pmf_dtls.pmf_dest_ro', $pmf_dest_ro);
        }
        if ($request->has('pmf_cd_no')) {
            $getPmfDetails->Where('gms_pmf_dtls.pmf_cd_no', $pmf_cd_no);
        }
        if ($request->has('pmf_mode')) {
            $getPmfDetails->where('gms_pmf_dtls.pmf_mode', $pmf_mode);
        }
        if ($request->has('pmf_city')) {
            $getPmfDetails->where('gms_pmf_dtls.pmf_city', $pmf_city);
        }
        return $getPmfDetails->paginate($request->per_page);
    }

    public function coloaderOrderAdd()
    {
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();
        $input = $this->request->all();
        for ($i = 0; $i < count($input['pmf_no']); $i++) {
            $gmsColoaderDtls = new GmsColoaderDtls([

                'branch_code' => $input['branch_code'],
                'branch_ro' => $input['branch_ro'],
                'coloader_code' => $input['coloader_code'],
                'cd_no' => isset($input['cd_no']) ? $input['cd_no'] : NULL,
                'cd_bags' => $input['cd_bags'],
                'coloader_wt' => $input['coloader_wt'],
                'coloader_mode' => $input['coloader_mode'],
                'coloader_date' => date('Y-m-d', strtotime($input['coloader_date'])),
                'coloader_type' => $input['coloader_type'],
                'coloader_cust_type' => $input['coloader_cust_type'],
                'coloader_cust_code' => $input['coloader_cust_code'],
                'manifest_no' => $input['pmf_no'][$i],
                'manifest_date' => date('Y-m-d', strtotime($input['pmf_date'][$i])),
                'total_cnno' => $input['totalcnno'][$i],
                'total_wt' => $input['weight'][$i],
                'remark' => $input['remark'][$i],
                'entry_date' => Carbon::now()->toDateTimeString(),
                'userid' => $sessionObject->admin_id
            ]);

            $gmsColoaderDtls->save();
        }
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $gmsColoaderDtls);
    }

    public function viewColoadersDetails(Request $request)
    {
        $input = $this->request->all();
        $viewColoadersDetails = GmsColoaderDtls::select(
            'cd_no',
            'c_type',
            'branch_code',
            DB::raw('CONCAT(coloader_name," - ",coloader_code) As customers'),
            'cd_bags',
            'total_wt',
            'coloader_mode',
            'coloader_dest',
            'coloader_type',
            'coloader_code',
            'manifest_no',
            'total_cnno',
        );
        if ($request->isMethod('get')) {
            return $viewColoadersDetails->paginate($request->per_page);
        } else {
            $query1 = GmsColoaderDtls::select('cd_no', DB::raw('CONCAT(coloader_name," - ",coloader_code) As coloaders'), 'cd_bags', 'total_wt', 'coloader_mode', 'branch_code', 'coloader_dest', 'coloader_date', 'remark', 'coloader_dest', 'manifest_no', 'total_cnno', 'total_wt');
            $query1->where('cd_no', $input['cd_no']);
            return $query1->get();
        }
    }

    public function advanceSearchColoader(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $coloader_dest_ro = $request->coloader_dest_ro;
        $coloader_dest_bo = $request->coloader_dest_bo;
        $coloader_mode = $request->coloader_mode;
        $coloaders = $request->coloaders;
        $cd_no = $request->cd_no;
        $manifest_no = $request->manifest_no;
        $coloader_name = $request->coloader_name;

        $GmsColoaderDtls = GmsColoaderDtls::join('gms_coloader', 'gms_coloader_dtls.coloader_code', '=', 'gms_coloader.coloader_code')->select(
            'gms_coloader_dtls.c_type',
            'gms_coloader_dtls.cd_no',
            'gms_coloader_dtls.branch_code',
            DB::raw('concat(gms_coloader.coloader_code ,"(",gms_coloader.coloader_name,")")As coloaders'),
            'gms_coloader_dtls.cd_bags',
            'gms_coloader_dtls.coloader_wt',
            'gms_coloader_dtls.coloader_mode',
            'gms_coloader_dtls.coloader_dest',
            'gms_coloader_dtls.coloader_type',
            'gms_coloader_dtls.coloader_cust_code',
            'gms_coloader_dtls.manifest_no',
            'gms_coloader_dtls.total_cnno',
            'gms_coloader_dtls.total_wt'
        );
        if ($request->has('from_date') && $request->has('to_date')) {
            $GmsColoaderDtls->whereBetween('gms_coloader_dtls.entry_date', [$from_date, $to_date]);
        }
        if ($request->has('coloader_dest_ro')) {
            $GmsColoaderDtls->Where('gms_coloader_dtls.coloader_dest_ro', $coloader_dest_ro);
        }
        if ($request->has('coloader_dest_bo')) {
            $GmsColoaderDtls->Where('gms_coloader_dtls.coloader_dest_bo', $coloader_dest_bo);
        }
        if ($request->has('coloader_mode')) {
            $GmsColoaderDtls->Where('gms_coloader_dtls.coloader_mode', $coloader_mode);
        }
        if ($request->has('coloaders')) {
            $GmsColoaderDtls->where('gms_coloader_dtls.coloader_code', $coloaders);
        }
        if ($request->has('cd_no')) {
            $GmsColoaderDtls->where('gms_coloader_dtls.cd_no', $cd_no);
        }
        if ($request->has('manifest_no')) {
            $GmsColoaderDtls->where('gms_coloader_dtls.manifest_no', $manifest_no);
        }
        if ($request->has('coloader_name')) {
            $GmsColoaderDtls->where('gms_coloader.coloader_name', $coloader_name);
        }
        return $GmsColoaderDtls->paginate($request->per_page);
    }

    public function drsReportCust(Request $request)
    {
        if ($request->has('dmf_mfdate') or $request->has('dmf_type') or $request->has('dmf_emp')) {
            return GmsDmfDtls::where('dmf_mfdate', $request->dmf_mfdate)
                ->orWhere('dmf_type', $request->dmf_type)
                ->orWhere('dmf_emp', $request->dmf_emp)
                ->select('dmf_type', 'dmf_fr_code', DB::raw('count(dmf_mfno)as totalcnno'), DB::raw("(SELECT COUNT(dmf_cnno_current_status) WHERE dmf_cnno_current_status ='WTD') as Delivered"), DB::raw("(SELECT COUNT(dmf_cn_status)  WHERE dmf_cn_status ='N') as NotDelivered"), DB::raw("(SELECT COUNT(dmf_cn_status) WHERE dmf_cn_status ='D') as updated"), DB::raw("(SELECT COUNT(dmf_cn_status)  WHERE dmf_cn_status ='N') as Notupdated"))
                ->groupBy('dmf_type', 'dmf_fr_code', 'dmf_cnno_current_status', 'dmf_cn_status')
                ->first();
        } else {
            return "No Data Found";
        }
    }

    public function tracking()
    {
        $input = $this->request->all();
        $booking_details[] = GmsBookingDtls::join('gms_customer',
            'gms_booking_dtls.book_cust_code', '=', 'gms_customer.cust_code')->
        join('gms_office', 'gms_booking_dtls.book_br_code', '=', 'gms_office.office_code')->
        join('gms_emp', 'gms_booking_dtls.book_emp_code', '=', 'gms_emp.emp_code')->
        join('gms_city', 'gms_booking_dtls.book_dest', '=', 'gms_city.city_code')->select('gms_booking_dtls.book_cnno as conginment_no', 'gms_booking_dtls.book_mfdate as booking_date', 'gms_booking_dtls.book_refno as refNo', 'gms_booking_dtls.book_weight as weight', 'gms_booking_dtls.book_vol_weight as vol_weight', DB::raw('concat(gms_booking_dtls.book_cust_code,"/",gms_customer.cust_la_ent) As customer'), 'gms_booking_dtls.book_mfno', DB::raw('concat(gms_booking_dtls.book_br_code,"/",gms_office.office_name) As booking_branch'), DB::raw('concat(gms_booking_dtls.book_emp_code,"/",gms_emp.emp_name) As booking_branch'), 'gms_booking_dtls.book_invno as invoice_dtls', 'gms_city.city_name', 'gms_booking_dtls.book_pin as pincode', 'gms_booking_dtls.book_mode as mode', 'gms_booking_dtls.book_cod as cod_value', 'gms_booking_dtls.book_topay as topay_value', 'gms_booking_dtls.book_doc as doc_type', 'gms_booking_dtls.book_pcs as pcs', 'gms_booking_dtls.book_cons_dtl as consignor_dtls', 'gms_booking_dtls.book_cn_name as consignor_name', 'gms_booking_dtls.book_cn_name as consignor_name', 'gms_booking_dtls.book_cn_mobile as consignor_number', 'gms_booking_dtls.book_cons_mobile as consignee_name', 'gms_booking_dtls.book_remarks as remark')->where('gms_booking_dtls.book_cnno', $input['cnno_no'])->first();

        $gmsPmfDtls[] = GmsPmfDtls::join('gms_office', 'gms_pmf_dtls.pmf_origin', '=', 'gms_office.office_code')->join('gms_emp', 'gms_pmf_dtls.pmf_emp_code', '=', 'gms_emp.emp_code')->select('gms_pmf_dtls.pmf_no as opmf', DB::raw('concat(gms_pmf_dtls.pmf_origin,"/",gms_office.office_name) As origin_branch'), 'gms_pmf_dtls.pmf_date as opmf_date', 'gms_pmf_dtls.pmf_time as opmf_time', 'gms_pmf_dtls.pmf_pcs as pcs', DB::raw('concat(gms_pmf_dtls.pmf_wt,"/",gms_pmf_dtls.pmf_vol_wt) As wt_vol_wt'), DB::raw('concat(gms_pmf_dtls.pmf_dest,"/",gms_office.office_name) As dest_branch'), 'gms_pmf_dtls.pmf_received_date as in_date_time', DB::raw('concat(gms_pmf_dtls.pmf_received_wt,"/",gms_pmf_dtls.pmf_vol_received_wt) As receive_vol_wt'), DB::raw('concat(gms_pmf_dtls.pmf_origin,"/",gms_pmf_dtls.pmf_emp_code) As dest_branch'))->where('gms_pmf_dtls.pmf_cnno', $input['cnno_no'])->first();
        $gmsDmfDtls[] = GmsDmfDtls::join('gms_customer_franchisee', 'gms_dmf_dtls.dmf_fr_code', '=', 'gms_customer_franchisee.fran_cust_code')->select('gms_dmf_dtls.dmf_mfdate as dmf_date', 'gms_dmf_dtls.dmf_mfno as drs_no', 'gms_dmf_dtls.dmf_cnno_current_status as status', 'gms_dmf_dtls.modify_date as updated_by', 'gms_dmf_dtls.dmf_atmpt_date as attemp_date', 'gms_dmf_dtls.dmf_ndel_reason as reasone', 'gms_dmf_dtls.dmf_remarks as remark', 'gms_dmf_dtls.dmf_delv_remarks as info')->where('gms_dmf_dtls.dmf_cnno', $input['cnno_no'])->first();

        return $this->successResponse(self::CODE_OK, ["booking_details" => $booking_details,
            "gmsPmfDtls" => $gmsPmfDtls,
            "gmsDmfDtls" => $gmsDmfDtls
        ]);
    }

    public function viewAllComplaints(Request $request)
    {
        $input = $this->request->all();
        $query = GmsComplaint::
        join('gms_booking_dtls AS book', 'book.book_cnno', '=', 'gms_complaint.log_cnno')
            ->join('admin AS create', 'create.id', '=', 'gms_complaint.userid')
            ->join('admin AS closed', 'closed.id', '=', 'gms_complaint.closed_by')
            ->join('gms_city AS origin', 'origin.city_code', '=', 'book.book_org')
            ->join('gms_city AS dest', 'dest.city_code', '=', 'book.book_dest')
            ->select(
                'book.book_cnno',
                'origin.city_name AS origin',
                'dest.city_name AS destination',
                'gms_complaint.consignee_name',
                'gms_complaint.consignee_mobile_no',
                'gms_complaint.consignor_name',
                'gms_complaint.consignor_mobile_no',
                'gms_complaint.description',
                'create.username AS crated_by',
                DB::raw('DATE_FORMAT(gms_complaint.created_at, "%d %b, %Y") as created_date'),
                'closed.username AS closed_by',
                DB::raw('DATE_FORMAT(gms_complaint.closed_date, "%d %b, %Y") as closed_date'),
                'gms_complaint.status',
            );
        $query->where('gms_complaint.is_deleted', 0);
        if ($request->has('status')) {
            $query->where('gms_complaint.status', $request->status);
        }
        return $query->paginate($request->per_page);
    }

    public function getAgent(Request $request)
    {
        $adminSession = session()->get('session_token');
        $admin = Admin::where('id', $adminSession->admin_id)->first();
        $office = GmsOffice::where('id', $admin->office_id)->first();
        $type = $this->request->type;
        if ($type == "BD") {
            $emp = GmsEmp::select('emp_name as cust_name', 'emp_code as cust_code', 'emp_code'
            )->where('emp_city', $office->office_city)->where('emp_rep_offtype', $office->office_type)->where('status', 'A')->where('is_deleted', 0);
            return $emp->paginate($request->per_page);
        } elseif ($type == "DA") {
            $cust = GmsCustomer::select(DB::raw('concat(cust_name,"(",cust_code,")")As agent'), 'cust_code'
            )->where('cust_city', $office->office_city)->where('cust_type', 'DA')->where('is_deleted', 0);
            return $cust->paginate($request->per_page);
        } elseif ($type == "DF") {
            $cust = GmsCustomer::select(DB::raw('concat(cust_name,"(",cust_code,")")As agent'), 'cust_code'
            )->where('cust_city', $office->office_city)->where('cust_type', "DF")->where('is_deleted', 0);
            return $cust->paginate($request->per_page);
        } elseif ($type == "CF") {
            $cust = GmsCustomer::select(DB::raw('concat(cust_name,"(",cust_code,")")As agent'), 'cust_code'
            )->where('cust_city', $office->office_city)->where('cust_type', "CF")->where('is_deleted', 0);
            return $cust->paginate($request->per_page);
        } else {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Data Not Found');
        }
    }


}


