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
use App\Models\Admin;
use App\Models\GmsOffice;
use Illuminate\Support\Collection;
use App\Http\Traits\RateTrait;
use App\Imports\XlUpdateImport;
use App\Models\GmsRateCodeHistory;
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

    public function addRateMaster(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->first();
        $input = $this->request->all();
        $input['user_id'] = $admin->id;
        $input['ro_code'] = $admin->username;
        $input['to_wt'] = $request->to_wt;
        if ($request->sevice_type == 1) {
            $product_code = $input['product_code'];
            $doc_type = $input['doc_type_id'];
            $mode = $input['mode_type_id'];
            $loc_type = $input['loc_type'];
            if (($loc_type == 1) || ($loc_type == 2) || ($loc_type == 3) || ($loc_type == 4) || ($loc_type == 5) || ($loc_type == 6)
                || ($loc_type == 7) || ($loc_type == 8)) {
                if (($product_code == 'CR') && ($doc_type == 'DX') && ($mode == 'AR') || ($mode == 'DF')) {
                    $min_wt = $input['min_wt'];
                    $max_wt = $input['max_wt'];
                    $rate = $input['rate'];

                    DB::table('gms_rate_master')->insert([
                        'ro_code' => $input['ro_code'],
                        'user_id' => $input['user_id'],
                        'product_code' => $input['product_code'],
                        'scheme_rate_id' => $input['scheme_rate_id'],
                        'mode' => $input['mode_type_id'],
                        'doc_type' => $input['doc_type_id'],
                        'loc_type' => $input['loc_type'],
                        'flat_rate' => 'N',
                        'slab_rate' => 'Y',
                        'from_wt' => $min_wt,
                        'to_wt' => $max_wt,
                        'rate' => $rate,
                        'addnl_rate' => $input['addnl_rate'],
                        'addnl_wt' => $input['addnl_wt']
                    ]);
                }
                if (($product_code == 'CR') && ($doc_type == 'NX') && ($mode == 'AR') || ($mode == 'AC') || ($mode == 'SF') || ($mode == 'DF')) {
                    if ($input['type_rate'] == 'F') {
                        DB::table('gms_rate_master')->insert([
                            'ro_code' => $input['ro_code'],
                            'user_id' => $input['user_id'],
                            'product_code' => $input['product_code'],
                            'scheme_rate_id' => $input['scheme_rate_id'],
                            'mode' => $input['mode_type_id'],
                            'doc_type' => $input['doc_type_id'],
                            'flat_rate' => 'Y',
                            'slab_rate' => 'N',
                            'loc_type' => $input['loc_type'],
                            'min_charge_wt' => $input['min_charge_wt'],
                            'rate' => $input['rate'],
                            'addnl_rate' => $input['addnl_rate'],
                            'addnl_wt' => $input['addnl_wt']
                        ]);
                    } else {
                        DB::table('gms_rate_master')->insert([
                            'ro_code' => $input['ro_code'],
                            'user_id' => $input['user_id'],
                            'product_code' => $input['product_code'],
                            'scheme_rate_id' => $input['scheme_rate_id'],
                            'mode' => $input['mode_type_id'],
                            'doc_type' => $input['doc_type_id'],
                            'flat_rate' => 'N',
                            'slab_rate' => 'Y',
                            'loc_type' => $input['loc_type'],
                            'from_wt' => $min_wt,
                            'to_wt' => $max_wt,
                            'rate' => $rate,
                            'addnl_rate' => $input['addnl_rate'],
                            'addnl_wt' => $input['addnl_wt']
                        ]);
                    }
                }
                if (($product_code == 'GD') && ($doc_type == 'DX') || ($doc_type == 'NX') && ($mode == 'AR')) {
                    DB::table('gms_rate_master')->insert([
                        'ro_code' => $input['ro_code'],
                        'user_id' => $input['user_id'],
                        'product_code' => $input['product_code'],
                        'scheme_rate_id' => $input['scheme_rate_id'],
                        'mode' => $input['mode_type_id'],
                        'doc_type' => $input['doc_type_id'],
                        'flat_rate' => 'N',
                        'slab_rate' => 'Y',
                        'loc_type' => $input['loc_type'],
                        'from_wt' => $min_wt,
                        'to_wt' => $max_wt,
                        'rate' => $rate,
                        'addnl_rate' => $input['addnl_rate'],
                        'addnl_wt' => $input['addnl_wt']
                    ]);
                }
                if (($product_code == 'LG') && ($doc_type == 'NX') && ($mode == 'SF')) {
                    DB::table('gms_rate_master')->insert([
                        'ro_code' => $input['ro_code'],
                        'user_id' => $input['user_id'],
                        'product_code' => $input['product_code'],
                        'scheme_rate_id' => $input['scheme_rate_id'],
                        'mode' => $input['mode_type_id'],
                        'doc_type' => $input['doc_type_id'],
                        'flat_rate' => 'Y',
                        'slab_rate' => 'N',
                        'loc_type' => $input['loc_type'],
                        'min_charge_wt' => $input['min_charge_wt'],
                        'rate' => $input['rate'],
                        'addnl_rate' => $input['addnl_rate'],
                        'addnl_wt' => $input['addnl_wt']
                    ]);
                }
            }
        } elseif ($request->sevice_type == 2) {
            if ($request->service_code == 'TPY' || $request->service_code == 'COD' || $request->service_code == 'MPS'
                || $request->service_code == 'FVO' || $request->service_code == 'FVR' || $request->service_code == 'ISC') {
                $input['percentage'] = $request->percentage;
                $input['amount'] = $request->amount;
                $input['scheme_rate_id'] = $request->scheme_rate_id;
                DB::table('gms_rate_service')->insert([
                    'percentage' => $input['percentage'],
                    'amount' => $input['amount'],
                    'service_type' => $request->service_code,
                    'oda_type' => 'F',
                    'scheme_rate_id' => $input['scheme_rate_id']
                ]);
            } elseif ($request->service_code == 'ODA' || $request->service_code == 'EDL' || $request->service_code == 'NSL')
                if ($input['oda_type'] == 'F') {
                    $input['amount'] = $request->amount;
                    DB::table('gms_rate_service')->insert([
                        'amount' => $input['amount'],
                        'service_type' => $request->service_code,
                        'oda_type' => 'F',
                        'scheme_rate_id' => $input['scheme_rate_id']
                    ]);
                } elseif ($input['oda_type'] == 'S') {
                    $input['amount'] = $request->amount;
                    $input['percentage'] = $request->percentage;
                    DB::table('gms_rate_service')->insert([
                        'percentage' => $input['percentage'],
                        'amount' => $input['amount'],
                        'service_type' => $request->service_code,
                        'oda_type' => 'S',
                        'scheme_rate_id' => $input['scheme_rate_id']
                    ]);
                }
        } elseif ($request->sevice_type == 3) {
            if ($request->fuel_type == '1') {
                $input['percentage'] = $request->percentage;
                echo "Storing in database is pending";
            } else {
                $posted_month = Carbon::now()->format('Y-m-d');
                $cnt = count($input['from_price']);
                for ($i = 0; $i < $cnt; $i++) {
                    DB::table('gms_fuel_charges')->insert([
                        'from_price' => $input['from_price'][$i],
                        'to_price' => $input['to_price'][$i],
                        'charged_percentage' => $input['charged_percentage'][$i],
                        'posted_month' => $posted_month
                    ]);
                }
            }
        } elseif ($request->sevice_type == 4) {


        } elseif ($request->sevice_type == 5) {
            $input['to_wt'] = $request->to_wt;
            $input['amount'] = $request->amount;
            DB::table('gms_rate_master')->insert([
                'to_wt' => $input['percentage'],
                'rate' => $input['amount'],
                'user_id' => $input['user_id']
            ]);
        }
        // $GmsRateMaster = new GmsRateMaster($input);
        //$GmsRateMaster->save();
        return $this->successResponse(self::CODE_OK, "Rate Master Created Successfully!!");
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
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'rate_type' => 'required',
            'rate_code' => 'required',
            'description' => 'required',
            'customer_code' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['user_id'] = $adminSession->admin_id;
        $addRateCode = new GmsRateCode($input);
        $addRateCode->save();
        $updt = GmsRateCode::find($addRateCode->id);
        $updt->scheme_rate_num = $addRateCode->id;
        $updt->save();
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

    public function viewFranchDcservice(Request $request)
    {
        $viewfranchDcservice = GmsRateServiceFdc::leftJoin('gms_customer_franchisee', 'gms_rate_service_fdc.fran_cust_code', '=', 'gms_customer_franchisee.fran_cust_code')
            ->select('gms_rate_service_fdc.fran_cust_code',
                'gms_customer_franchisee.cust_code',
                'gms_customer_franchisee.fran_cust_name',
                DB::raw('Group_Concat(gms_rate_service_fdc.service_type) AS service_type'),
                DB::raw('Group_Concat(gms_rate_service_fdc.amount) AS service_amount'))
            ->groupBy('gms_rate_service_fdc.fran_cust_code')
            ->get();
        if ($viewfranchDcservice != null) {
            return $this->successResponse(self::CODE_OK, "Show Data Successfully", $viewfranchDcservice);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "No record Found");
        }
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
        $getAssignEmp = GmsEmp::where('emp_work_type', 'EXE')->select(
            DB::raw('CONCAT(emp_name,"(",emp_code,")") AS employee_name'),
            'emp_work_type',
            'delivery_code')->where('is_deleted', 0)->where('emp_rep_office', $input['bo_sf']);
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
    public function addFranchDcservice()
    {
        $validator = Validator::make($this->request->all(), [
            'service_type' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addFranchDcservice = new GmsRateServiceFdc($input);
        $addFranchDcservice->save();

        return $this->successResponse(self::CODE_OK, " Add FranchDcservice Successfully!!", $addFranchDcservice);
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

    public function updateBookingRate(Request $request)
    {
        $cust_type = $request->cust_type;
        $book_cust_code = $request->book_cust_code;
        $product_type = $request->product_type;
        $document_type = $request->document_type;
        $mode_type = $request->mode_type;
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        $upbkrate = GmsRateCode::where('is_deleted', 0)
            ->where('rate_code', $book_cust_code)
            ->update([
                'docket_type' => $product_type,
                'docket_dx' => $document_type,
                'docket_nx' => $mode_type,
                'effect_date_from' => $from_date,
                'effect_date_to' => $to_date
            ]);
        if ($upbkrate) {
            return $this->successResponse(self::CODE_OK, "Booking Rate Update Successfully!!", $upbkrate);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function viewAssignFranBoSfEmpList(Request $request)
    {
        $adminSession = session()->get('session_token');
        $admin = Admin::where('id', $adminSession->admin_id)->where('is_deleted', 0)->first();
        $office = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();

        $GmsEmp = GmsEmp::select(
            'emp_code AS value',
            DB::raw('CONCAT(emp_name,"(",emp_code,")") AS label'))
            ->where('delivery_branch_status', 1)
            ->where('is_deleted', 0)
            ->where('emp_rep_office', $request->emp_rep_office)
            ->orderBy('emp_id', 'asc')->get();
        $data['label'] = 'GmsEmp';
        $data['options'] = $GmsEmp;
        $collection = new Collection([$data]);
        if (isset($collection)) {
            return $this->successResponse(self::CODE_OK, "Show Successfully!!", $collection);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Something went Wrong!!");
        }
    }

    public function viewAssignFranBoSfAgentList(Request $request)
    {
        $adminSession = session()->get('session_token');
        $admin = Admin::where('id', $adminSession->admin_id)->where('is_deleted', 0)->first();
        $office = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();

        $GmsCustomer = GmsCustomer::select(
            'cust_code AS value',
            DB::raw('CONCAT(cust_la_ent,"(",cust_code,")") AS label'))
            ->where('delivery_branch_status', 1)
            ->where('is_deleted', 0)
            ->where('created_office_code', $request->created_office_code)
            ->orderBy('id', 'asc')->get();
        $data['label'] = 'GmsCustomer';
        $data['options'] = $GmsCustomer;
        $collection = new Collection([$data]);
        if (isset($collection)) {
            return $this->successResponse(self::CODE_OK, "Show Successfully!!", $collection);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Something went Wrong!!");
        }
    }

    public function addAssignFranBoSfView()
    {
        $adminSession = session()->get('session_token');
        $admin = Admin::where('id', $adminSession->admin_id)->where('is_deleted', 0)->first();
        $office = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();

        if (isset($this->request->emp_code)) {
            $cnt = count($this->request->emp_code);
            for ($x = 0; $x < $cnt; $x++) {
                $getGmsEmpUpdate = GmsEmp::where('emp_code', $this->request->emp_code[$x])->where('is_deleted', 0)->first();
                if ($getGmsEmpUpdate) {

                    GmsEmp::where('emp_id', $getGmsEmpUpdate->emp_id)
                        ->update([
                            'delivery_branch_status' => $this->request->emp_status[$x]
                        ]);
                }
            }
        }

        if (isset($this->request->cust_code)) {
            $cnt_cust = count($this->request->cust_code);
            for ($x = 0; $x < $cnt_cust; $x++) {
                $getGmsCustUpdate = GmsCustomer::where('cust_code', $this->request->cust_code[$x])->where('is_deleted', 0)->first();
                if ($getGmsCustUpdate) {
                    $editCustUpdate = GmsCustomer::find($getGmsCustUpdate->id);
                    $input['delivery_branch_status'] = $this->request->cust_status[$x];
                    $input['sf_discount_status'] = $this->request->sf_discount_status;
                    $editCustUpdate->update($input);
                }
            }
            return $this->successResponse(self::CODE_OK, "Added Successfully!!");
        }
    }

    public function selectSchemeRevisedRateCard()
    {
        $adminSession = session()->get('session_token');
        $admin = Admin::where('id', $adminSession->admin_id)->where('is_deleted', 0)->first();
        $office = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();

        $type = $this->request->type;
        $type_split = explode('_', $type);
        $rate_type_card = $type_split[0];
        $rate_type = $type_split[1];

        $rateCode = GmsRateCode::select('id as value', 'rate_code AS label', 'effect_date_from AS valid_from', 'effect_date_to AS valid_to')
            ->where('rate_type_card', $rate_type_card)
            ->where('rate_type', $rate_type)
            ->where('office_code', $admin->office_code)
            ->where('is_deleted', 0)
            ->orderBy('id', 'asc')
            ->get();
        $data['label'] = 'rateCode';
        $data['options'] = $rateCode;
        $collection = new Collection([$data]);
        if (isset($collection)) {
            return $this->successResponse(self::CODE_OK, "Show Successfully!!", $collection);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Something went Wrong!!");
        }
    }

    public function updateSchemeRevisedRateCard()
    {
        $adminSession = session()->get('session_token');
        $admin = Admin::where('id', $adminSession->admin_id)->where('is_deleted', 0)->first();
        $office = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();
        $data = array();
        $getRateCodeUpdate = GmsRateCode::where('id', $this->request->scheme)->where('is_deleted', 0)->first();
        //print_r($getRateCodeUpdate->rate_code);die;
        if ($getRateCodeUpdate) {
            $editRateCode = GmsRateCode::find($getRateCodeUpdate->id);
            $input['user_id'] = $adminSession->admin_id;
            $input['effect_date_to'] = $this->request->valid_to;
            $editRateCode->update($input);
            $data['RateCode'] = $editRateCode;
        }
        $getRateCodeHistoryUpdate = GmsRateCodeHistory::where('rate_code', $getRateCodeUpdate->rate_code)->where('is_deleted', 0)->first();
        if ($getRateCodeHistoryUpdate) {

            $editRateCodeHistory = GmsRateCodeHistory::where('scheme_rate_id', $getRateCodeHistoryUpdate->scheme_rate_id)
                ->update([
                    'user_id' => $adminSession->admin_id,
                    'to_date' => $this->request->valid_to,
                    'effect_date_to' => $this->request->valid_to
                ]);

            /*$editRateCodeHistory = GmsRateCodeHistory::find($getRateCodeHistoryUpdate->scheme_rate_id);
            $input['user_id']  = $adminSession->admin_id;
            $input['to_date']  = $this->request->valid_to;
            $input['effect_date_to']  = $this->request->valid_to;
            $editRateCodeHistory->update($input);*/
            $data['RateCodeHistory'] = $editRateCodeHistory;
        }
        if (isset($data)) {
            return $this->successResponse(self::CODE_OK, "Updated Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Something went Wrong!!");
        }
    }

    public function listSchemeRevisedRateCard()
    {
        $adminSession = session()->get('session_token');
        $admin = Admin::where('id', $adminSession->admin_id)->where('is_deleted', 0)->first();
        $office = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();

        $getRateCode = GmsRateCode::where('id', $this->request->scheme)->where('is_deleted', 0)->first();
        $getRateCodeHistory = GmsRateCodeHistory::select(
            'rate_code',
            'effect_date_from AS date_from',
            'effect_date_to AS date_to'
        )->where('rate_code', $getRateCode->rate_code)->where('is_deleted', 0)->first();

        $data['label'] = 'RateCodeHistory';
        $data['options'] = $getRateCodeHistory;
        $collection = new Collection([$data]);
        if (isset($collection)) {
            return $this->successResponse(self::CODE_OK, "Show Successfully!!", $collection);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Something went Wrong!!");
        }
    }

    public function schemeRateCardDuplicate()
    {
        $adminSession = session()->get('session_token');
        $admin = Admin::where('id', $adminSession->admin_id)->where('is_deleted', 0)->first();
        $office = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();

        $type = $this->request->type;
        $type_split = explode('_', $type);
        $rate_type_card = $type_split[0];
        $rate_type = $type_split[1];

        $rateCode = GmsRateCode::join('gms_customer', 'gms_rate_code.cust_code', '=', 'gms_customer.cust_code')->select(
            'gms_rate_code.id as value',
            'gms_rate_code.rate_code AS label',
        )
            ->where('gms_rate_code.rate_type_card', $rate_type_card)
            ->where('gms_rate_code.rate_type', $rate_type)
            ->where('gms_rate_code.office_code', $admin->office_code)
            ->where('gms_customer.scheme_rate_id', '!=', 0)
            ->where('gms_rate_code.is_deleted', 0)
            ->orderBy('gms_rate_code.id', 'asc')
            ->get();
        $data['label'] = 'rateCode';
        $data['options'] = $rateCode;
        $collection = new Collection([$data]);
        if (isset($collection)) {
            return $this->successResponse(self::CODE_OK, "Show Successfully!!", $collection);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Something went Wrong!!");
        }
    }

    public function duplicateRateCard()
    {
        $adminSession = session()->get('session_token');
        $admin = Admin::where('id', $adminSession->admin_id)->where('is_deleted', 0)->first();
        $office = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();

        $type = $this->request->type;
        $type_split = explode('_', $type);
        $rate_type_card = $type_split[0];
        $rate_type = $type_split[1];

        $rateCode = GmsRateCode::join('gms_customer', 'gms_rate_code.cust_code', '=', 'gms_customer.cust_code')->select(
            'gms_rate_code.id as value',
            'gms_rate_code.rate_code AS label',
        )
            ->where('gms_rate_code.rate_type_card', $rate_type_card)
            ->where('gms_rate_code.rate_type', $rate_type)
            ->where('gms_rate_code.office_code', $admin->office_code)
            ->where('gms_customer.scheme_rate_id', 0)
            ->where('gms_rate_code.is_deleted', 0)
            ->orderBy('gms_rate_code.id', 'asc')
            ->get();
        $data['label'] = 'rateCode';
        $data['options'] = $rateCode;
        $collection = new Collection([$data]);
        if (isset($collection)) {
            return $this->successResponse(self::CODE_OK, "Show Successfully!!", $collection);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Something went Wrong!!");
        }
    }

    public function updateDuplicateRateCard()
    {
        $adminSession = session()->get('session_token');
        $admin = Admin::where('id', $adminSession->admin_id)->where('is_deleted', 0)->first();
        $office = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();
        $data = array();
        $getRateCodeUpdate = GmsRateCode::where('id', $this->request->duplicate_scheme)->where('is_deleted', 0)->first();

        if ($getRateCodeUpdate) {

            $editRateCode = GmsCustomer::where('cust_code', $getRateCodeUpdate->cust_code)
                ->update([
                    'user_id' => $adminSession->admin_id,
                    'scheme_rate_id' => $this->request->duplicate_scheme
                ]);

        }
        if (isset($editRateCode)) {
            return $this->successResponse(self::CODE_OK, "Updated Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Something went Wrong!!");
        }
    }
}
