<?php

namespace App\Http\Controllers\V1;

use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GmsRateMaster;
use App\Models\GmsRateCode;
use App\Models\GmsBookingDtls;
use App\Models\GmsEmp;
use App\Models\GmsCustomer;
use App\Models\GmsRateServiceFdc;
use App\Http\Traits\RateTrait;
use App\Imports\XlUpdateImport;
use App\Models\GmsRateBillingDiscount;
use App\Models\GmsRateMasterDelivery;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\DB;


class RateController extends Controller
{

    use RateTrait;

    /**
     * @var Request
     */

    private $request;


    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    public function addRateMaster()
    {
        $input = $this->request->all();
        $GmsRateMaster = new GmsRateMaster($input);
        $GmsRateMaster->save();
        return $this->successResponse(self::CODE_OK, "Rate Master Created Successfully!!", $GmsRateMaster);
    }

    public function addBillingDiscountRateCard()
    {
        $input = $this->request->all();
        $addBillingDiscountRateCard = new GmsRateBillingDiscount($input);
        $addBillingDiscountRateCard->save();
        return $this->successResponse(self::CODE_OK, "Discount Rate Card Created Successfully!!", $addBillingDiscountRateCard);
    }

    /**
     * @OA\Post(
     * path="/addRateCode",
     * summary="add RateCode",
     * operationId="addRateCode",
     *  tags={"RateCode"},
     * @OA\Parameter(
     *   name="rate_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="rate_type_card",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="rate_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="rate_name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_code",
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
     *   name="description",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fuel_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="flat_fuel_percentage",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="slab_fuel_from",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="slab_fuel_to",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="slab_fuel_percentage",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="monthly_bill_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="docket_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="docket_dx",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="docket_nx",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_upto_weight",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="book_upto_amt",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="effect_date_from",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="effect_date_to",
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
    public function addRateCard()
    {
        $validator = Validator::make($this->request->all(), [
            'rate_code' => 'required',
            'rate_type_card' => 'required',
            'rate_type' => 'required',
            'rate_name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addRateCode = new GmsRateCode($input);
        $addRateCode->save();

        return $this->successResponse(self::CODE_OK, "Rate Card Created Successfully!!", $addRateCode);
    }

    /**
     * @OA\Post(
     * path="/getRateCode",
     * summary="Get RateCode",
     * operationId="getRateCode",
     *  tags={"RateCode"},
     * @OA\Parameter(
     *   name="code_id",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     * )
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
    public function viewRateCard()
    {
        return $this->rateCode();
    }

    public function addDeliveryRateCard()
    {
        $input = $this->request->all();
        $addDeliveryRateCard = new GmsRateMasterDelivery($input);
        $addDeliveryRateCard->save();
        return $this->successResponse(self::CODE_OK, " Delivery Rate Card Created Successfully!!", $addDeliveryRateCard);
    }

    public function deliveryRateCard()
    {
        $deliveryRateCard = GmsRateMasterDelivery::select('del_rate_code as discount_code',
            DB::raw('CONCAT(from_wt,"(",to_wt,")","(",rate,")") AS cr_doc_rate'),
            'addnl_rate as cr_doc_adt_rate',
            'non_from_wt', 'non_to_wt',
            DB::raw('CONCAT(non_addnl_wt,"(",non_addnl_rate,")") AS cr_non_doc_adl_rate'),
            DB::raw('CONCAT(gd_from_wt,"(",gd_to_wt,")","(",gd_rate,")") AS gd_doc_rate'),
            DB::raw('CONCAT(gd_addnl_wt,"(",gd_addnl_rate,")") AS gd_doc_addnl_rate'),
            DB::raw('CONCAT(gd_non_from_wt,"(",gd_non_to_wt,")","(",gd_non_rate,")") AS gd_non_doc_rate'),
            DB::raw('CONCAT(gd_non_addnl_wt,"(",gd_non_addnl_rate,")") AS gd_non_doc_addnl_rate'),
            DB::raw('CONCAT(max_limit_wt,"(",max_limit_price,")") AS max_wt_price'),
            'tpy',
            'cod',
            'mps',
            'fvo',
            'fov',
            'edl',
            'isc',
            'oda',
            'entry_date'
        );
        $data = $deliveryRateCard->get();
        return $data;
    }

    public function editDeliveryRateCard()
    {
        return $this->deliveryRateCardEdit();
    }

    public function deleteDeliveryRateCard()
    {
        return $this->deliveryRateCardDelete();
    }

    public function billingDiscountRateCard()
    {
        $billingDiscountRateCard = GmsRateBillingDiscount::where('created_by', 'BLRRO')->select('billing_rate_code', 'billing_type', 'rate_per_cnno', 'rate_per_weight', DB::raw('CONCAT(invoice_value_range,"(",invoice_value_percentage,")") AS service_range_value'), 'created_at')->get();
        return $billingDiscountRateCard;
    }

    public function assignEmp(Request $request)
    {
        $input = $this->request->all();
        $getAssignEmp = GmsEmp::where('emp_work_type', 'EXE')->select(DB::raw('CONCAT(emp_name,"(",emp_code,")") AS employee_name'), 'emp_work_type', 'delivery_code')->where('is_deleted', 0)->where('emp_rep_office', $input['bo_sf']);
        if ($request->isMethod('get')) {
            return $getAssignEmp->paginate($request->per_page);
        }
        if ($request->isMethod('POST')) {

            $updateEmpDeliveryRate = GmsEmp::where('emp_work_type', 'EXE')->where('is_deleted', 0)->first();
            $updateEmpDeliveryRate->delivery_code = $input['delivery_code'];
            $updateEmpDeliveryRate->update($input);
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $updateEmpDeliveryRate);
        }
    }

    public function addAssignFranBoSf()
    {
        $input = $this->request->all();
        $getCustomer = GmsCustomer::where('cust_sf_reporting', $input['cust_sf_reporting'])->where('is_deleted', 0)->first();

        $getCustomer->cust_sf_reporting = $input['cust_sf_reporting'];
        $getCustomer->sf_from_date = $input['sf_from_date'];
        $getCustomer->update($input);
        return $this->successResponse(self::CODE_OK, "Update Successfully!!", $getCustomer);
    }


    public function viewAssignFranBoSf(Request $request)
    {
        $getAssignBoSF = GmsCustomer::join('gms_office', 'gms_customer.created_office_code', '=', 'gms_office.office_code')->select('gms_office.office_type', 'gms_office.office_code', 'gms_office.office_name', 'gms_customer.cust_code', 'gms_customer.cust_la_ent', 'gms_customer.sf_from_date', 'gms_customer.sf_to_date');
        $getAssignBoSF->where('gms_office.office_type', 'BO');
        $getAssignBoSF->orderBy('gms_customer.id', 'desc');
        return $getAssignBoSF->paginate($request->per_page);
    }

    /**
     * @OA\Post(
     * path="/addServiceFdc",
     * summary="add ServiceFdc",
     * operationId="addServiceFdc",
     *  tags={"ServiceFdc"},
     * @OA\Parameter(
     *   name="fran_cust_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="service_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="oda_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="min_weight",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="max_weight",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="percentage",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="amount",
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
    public function addServiceFdc()
    {
        $validator = Validator::make($this->request->all(), [
            'service_type' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addMfDtls = new GmsRateServiceFdc($input);
        $addMfDtls->save();

        return $this->successResponse(self::CODE_OK, "Master Manifest Created Successfully!!", $addMfDtls);
    }

    public function getCustomerAssign(Request $request)
    {
        $cust_type = $request->cust_type;
        $reporting_office = $request->reporting_office;
        $reporting_bo_office = $request->reporting_bo_office;

        $getCustomerAssign = GmsCustomer::leftjoin('gms_rate_code', 'gms_customer.cust_code', '=', 'gms_rate_code.cust_code')->leftjoin('gms_office', 'gms_customer.created_office_code', '=', 'gms_office.office_code')->select(
            DB::raw('CONCAT(gms_customer.created_office_code,"(",gms_office.office_name,")") AS BO_name'),
            DB::raw('concat(gms_customer.cust_code,"(",gms_customer.cust_name,")")As Direct_Customer'),
            'gms_rate_code.rate_code',
            'gms_customer.delivery_code',
            'gms_customer.discount_code'
        );


        if ($request->has('cust_type')) {
            $getCustomerAssign->where('gms_customer.cust_type', $cust_type);
        }
        if ($request->has('reporting_office')) {
            $getCustomerAssign->where('gms_customer.cust_rep_office', $reporting_office);
        }
        if ($request->has('reporting_bo_office')) {
            $getCustomerAssign->where('gms_customer.created_office_code', $reporting_bo_office);
        }
        $getCustomerAssign->where('gms_customer.is_deleted', 0);
        $getCustomerAssign->where('gms_customer.created_office_code', 'BLRRO'); //OFFICE SESSION ADD HERE
        return $getCustomerAssign->paginate($request->per_page);

    }

    public function discountRateDropDown()
    {                                                                                //HERE ADD OFFICE SESSION
        $discountRateDropDown = GmsRateBillingDiscount::select('billing_rate_code')->where('created_by', 'BLRRO')->get();
        return $discountRateDropDown;
    }

    public function updateAssignCustomer()
    {
        $input = $this->request->all();
        $updateAssignCustomer = GmsCustomer::where('cust_code', $input['cust_code'])->where('is_deleted', 0)->first();
        if ($updateAssignCustomer != null) {
            $updateAssignCustomer->discount_code = isset($input['discount_code']) ? $input['discount_code'] : "";
            $updateAssignCustomer->save();
            return $this->successResponse(self::CODE_OK, "Update Rate Card Successfully!!", $updateAssignCustomer);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function xlUpdate(Request $request)
    {

        $rows = Excel::toArray(new XlUpdateImport, $request->file('sampledata'));
        $cnt = count($rows[0]);
        $value = array();
        for ($x = 0; $x < $cnt; $x++) {
            array_push($value, $rows[0][$x][0]);
        }

        $getDataFromTable = GmsBookingDtls::leftjoin('gms_office', 'gms_booking_dtls.book_br_code', '=', 'gms_office.office_code')->leftjoin('gms_customer', 'gms_booking_dtls.book_cust_code', '=', 'gms_customer.cust_code')
            ->leftjoin('gms_city', 'gms_booking_dtls.book_org', '=', 'gms_city.city_code')
            ->leftjoin('gms_dmf_dtls', 'gms_booking_dtls.book_cnno', '=', 'gms_dmf_dtls.dmf_cnno')
            ->leftjoin('gms_emp', 'gms_dmf_dtls.dmf_fr_code', '=', 'gms_emp.emp_code')
            ->select('book_cnno',
                DB::raw('concat(gms_booking_dtls.book_mfdate,"(",gms_booking_dtls.book_mftime,")")As booking_date_time'),
                'gms_booking_dtls.book_refno',
                'gms_booking_dtls.book_br_code',
                'gms_office.office_name',
                'gms_booking_dtls.book_emp_code',
                'gms_booking_dtls.book_cust_code',
                //  'gms_booking_dtls.book_cust_code',
                'gms_customer.cust_la_ent',
                'gms_booking_dtls.book_mfno',
                'gms_booking_dtls.book_mfdate',
                'gms_booking_dtls.book_mftime',
                'gms_booking_dtls.book_pin',
                'gms_city.city_name',
                //  'gms_city.book_dest',
                'gms_booking_dtls.book_cons_addr',
                'gms_booking_dtls.book_cn_dtl',
                'gms_booking_dtls.book_product_type',
                'gms_booking_dtls.book_mode',
                'gms_booking_dtls.book_doc',
                'gms_booking_dtls.book_weight',
                'gms_booking_dtls.book_vol_weight',
                DB::raw('CONCAT(gms_booking_dtls.book_vol_lenght," (",gms_booking_dtls.book_vol_breight,")"," ", gms_booking_dtls.book_vol_height) AS book_vol_lbt'),
                'gms_booking_dtls.book_pcs',
                'gms_booking_dtls.book_remarks',
                'gms_booking_dtls.book_service_type',
                'gms_booking_dtls.book_pod_scan',
                'gms_booking_dtls.book_topay',
                'gms_booking_dtls.book_cod',
                'gms_booking_dtls.book_billamt',
                'gms_booking_dtls.book_total_amount',
                'gms_city.city_name',
                'gms_dmf_dtls.dmf_fr_code',
                'gms_emp.emp_name',
                'gms_dmf_dtls.dmf_emp',
                'gms_emp.emp_name',
                'gms_dmf_dtls.dmf_drsno',
                'gms_dmf_dtls.dmf_atmpt_date',
                'gms_dmf_dtls.dmf_cnno_remarks',
                'gms_dmf_dtls.dmf_remarks',
                'gms_dmf_dtls.dmf_ndel_reason'
            )->whereIn('book_cnno', $value)->get();

        return response()->json(["getDataFromTable" => $getDataFromTable]);
    }

}
