<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\GmsEmp;
use App\Models\GmsInvoiceCust;
use App\Models\GmsBookingDtls;
use App\Models\GmsInvoiceSf;
use App\Models\GmsPmfDtls;
use App\Models\GmsRtoDtls;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\GmsMfDtls;
use App\Models\GmsInvoice;
use App\Models\GmsColoader;
use App\Models\GmsDmfDtls;
use App\Models\GmsColoaderDtls;
use App\Http\Traits\InvoiceTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class InvoiceController extends Controller
{
    use InvoiceTrait;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @OA\Post(
     * path="/addInvoice",
     * summary="add Invoice",
     * operationId="addInvoice",
     *  tags={"Invoice"},
     * @OA\Parameter(
     *   name="invoice_date",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="month",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="year",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="esugun_no",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
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
     *   name="branch_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="branch_ro",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="customer_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="from_address",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="to_address",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="from_date",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="to_date",
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
     *   name="ac_invoice_no",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fr_invoice_no",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fr_invoice_date",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fr_actual_service_charge",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fr_service_charge",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fr_less_billing_discount",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fr_net_service_charge",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fr_fuel_percentage",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fr_fuel_amount",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fr_sub_total",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fr_actual_less_delivery_discount",
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
    public function addInvoice()
    {
        $validator = Validator::make($this->request->all(), [
            'branch_code' => 'required',
            'branch_ro' => 'required',
            'from_address' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addInvoice = new GmsInvoice($input);
        $addInvoice->save();

        return $this->successResponse(self::CODE_OK, "Invoice Created Successfully!!", $addInvoice);
    }

    /**
     * @OA\Post(
     * path="/viewInvoice",
     * summary="View Invoice",
     * operationId="viewInvoice",
     *  tags={"Invoice"},
     * @OA\Parameter(
     *   name="invoice_id",
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
    public function viewInvoice()
    {
        return $this->view_invoice();
    }


    /**
     * @OA\Post(
     * path="/deleteInvoice",
     * summary="Delete Invoice",
     * operationId="DeleteInvoice",
     *  tags={"Invoice"},
     * @OA\Parameter(
     *   name="invoice_id",
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
    public function deleteInvoice()
    {
        return $this->delete_invoice();
    }

    /**
     * @OA\Post(
     * path="/viewInvoiceSf",
     * summary="View InvoiceSf",
     * operationId="viewInvoiceSf",
     *  tags={"Invoice"},
     * @OA\Parameter(
     *   name="insf_id",
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
    public function viewInvoiceSf()
    {
        return $this->view_invoiceSf();
    }


    public function editManifestDate(Request $request)
    {
        $validator = Validator::make($this->request->all(), [
            'mfNo' => 'required|exists:gms_booking_dtls,book_mfno',
            'book_mfdate' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getDate = GmsBookingDtls::where('book_mfno', $input['mfNo'])->get();
        foreach ($getDate as $value) {
            # code...
            $value['book_mfdate'] = date('Y-m-d', strtotime($input['book_mfdate']));
            $value->update($input);
        }
        return $this->successResponse(self::CODE_OK, " Update Manifest Date Successfully!!", $value);

    }


    /**
     * @OA\Post(
     * path="/viewManifest",
     * summary="View Manifest",
     * operationId="viewManifest",
     *  tags={"Manifest"},
     * @OA\Parameter(
     *   name="mani_id",
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
    public function viewManifest()
    {
        return $this->view_manifest();
    }

    public function viewAllManifest()
    {
        return GmsMfDtls::select('mf_type', 'mf_emp_code', 'mf_dest_type', 'mf_wt', 'mf_pcs', 'mf_entry_date', 'mf_cd_no')->where('is_deleted', 0)->paginate(5);
    }

    /**
     * @OA\Get(
     * path="/searchManifest",
     * summary="Search Manifest",
     * operationId="searchManifest",
     *  tags={"Manifest"},
     * @OA\Parameter(
     *   name="mani_id",
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
    public function searchManifest()
    {
        $data = $this->request->get('data');
        $gmsMfDtls = GmsMfDtls::where('mf_type', 'like', "%{$data}%")
            ->orWhere('mf_emp_code', 'like', "%{$data}%")
            ->orWhere('mf_origin_type', 'like', "%{$data}%")
            ->orWhere('mf_ro', 'like', "%{$data}%")
            ->orWhere('mf_dest_ro', 'like', "%{$data}%")
            ->get();

        return $this->successResponse(self::CODE_OK, "Manifest Data Show Successfully!!", $gmsMfDtls);
    }


    /**
     * @OA\Post(
     * path="/viewColoader",
     * summary="View Coloader",
     * operationId="viewColoader",
     *  tags={"Coloader"},
     * @OA\Parameter(
     *   name="coloader_id",
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
    public function viewColoader()
    {
        return $this->view_coloader();
    }


    public function deleteColoader()
    {
        $validator = Validator::make($this->request->all(), [
            'id' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $deleteColoader = GmsColoader::where('id', $input['id'])->first();;
        if ($deleteColoader != null) {
            $deleteColoader->is_deleted = 1;
            $deleteColoader->save();
            return $this->successResponse(self::CODE_OK, "Delete Coloader Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }


    public function viewAllRtoDetails(Request $request)
    {
        $rto = GmsRtoDtls::where('is_deleted', 0)->select('rto_cnno', 'rto_mfdate', 'rto_mftime');
        if ($request->has('rto_cnno')) {
            $rto->where('rto_cnno', $request->pincode_value);
            $rto = $rto->first();
        } else {
            $rto = $rto->get();
        }
        return $this->successResponse(self::CODE_OK, "Show Rto Details Successfully!!", $rto);
    }


    /**
     * @OA\Post(
     * path="/addCusInvoice",
     * summary="Add CusInvoice",
     * operationId="addCusInvoice",
     *  tags={"Customer invoice"},
     * @OA\Parameter(
     *   name="cust_invoice_no",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_invoice_date",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cust_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fran_cust_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="from_date",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="to_date",
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
    public function addCusInvoice()
    {
        $validator = Validator::make($this->request->all(), [
            'cust_invoice_date' => 'required',
            'cust_code' => 'required',
            'from_date' => 'required',
            'to_date' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addCusInvoice = new GmsInvoiceCust($input);
        $addCusInvoice->save();
        return $this->successResponse(self::CODE_OK, "Customer Invoice Created Successfully!!", $addCusInvoice);
    }

    public function viewAllInvoice(Request $request)
    {
        $viewAllInvoice = GmsInvoice::where('is_deleted', 0)->select('id', 'invoice_no', 'invoice_date', 'cust_type', 'customer_code', 'fr_fuel_amount', 'fr_sub_total', 'fr_grand_total');
        if ($request->has('month')) {
            $month = $request->month;
            $viewAllInvoice->where('month', $month);
        }
        if ($request->has('year')) {
            $year = $request->year;
            $viewAllInvoice->where('year', $year);
        }
        if ($request->has('cust_type')) {
            $cust_type = $request->cust_type;
            $viewAllInvoice->where('cust_type', $cust_type);
        }
        if ($request->has('customer_code')) {
            $customer_code = $request->customer_code;
            $viewAllInvoice->where('customer_code', $customer_code);
        }
        return $data = $viewAllInvoice->paginate($request->per_page);
    }

    public function viewSalesRegister(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $cust_type = $request->cust_type;
        $viewReportOfSalesReg = GmsInvoice::select('gms_invoice.fr_invoice_no',
            'gms_invoice.invoice_date',
            'gms_invoice.customer_code',
            'gms_invoice.total_cnno',
            'gms_invoice.total_weight',
            'gms_invoice.fr_actual_service_charge',
            'gms_invoice.fr_net_service_charge',
            'gms_invoice.fr_less_billing_discount',
            'gms_invoice.fr_sub_total',
            'gms_invoice.fr_fuel_amount',
            'gms_invoice.fr_total',
            'gms_invoice.fr_grand_total'

        );
        $viewSalesRegister = GmsInvoice::select('cust_type', DB::raw('count(cust_type) as NoOfCustomer'), DB::raw('count(invoice_no) as NoOfInvoice'));
        if ($request->has('year')) {
            $viewSalesRegister->where('gms_invoice.year', $year);
            $viewReportOfSalesReg->where('gms_invoice.year', $year);
        }
        if ($request->has('month')) {
            $viewSalesRegister->where('gms_invoice.month', $month);
            $viewReportOfSalesReg->where('gms_invoice.month', $month);
        }
        if ($request->has('cust_type')) {
            $viewSalesRegister->where('gms_invoice.cust_type', $cust_type);
            $viewReportOfSalesReg->where('gms_invoice.cust_type', $cust_type);
        }
        $viewSalesRegister->groupBy('gms_invoice.cust_type');

        $response['Count'] = $viewSalesRegister->get();
        $response['Report'] = $viewReportOfSalesReg->get();
        return $response;
    }

    public function rtoCnno(Request $request)
    {
        if ($request->has('rto_cnno')) {
            $searchRto = GmsRtoDtls::where('rto_cnno', '=', $request->rto_cnno)->join('gms_dmf_dtls', 'gms_dmf_dtls.dmf_cnno', '=', 'gms_rto_dtls.rto_cnno')
                ->select('gms_rto_dtls.rto_cnno', 'gms_rto_dtls.rto_mfdate', 'gms_rto_dtls.rto_mftime', 'gms_dmf_dtls.dmf_wt', 'gms_dmf_dtls.dmf_pcs', 'gms_dmf_dtls.dmf_delv_amt');
            return $searchRto->paginate($request->per_page);
        } else {
            $getRto = GmsRtoDtls::join('gms_dmf_dtls', 'gms_dmf_dtls.dmf_cnno', '=', 'gms_rto_dtls.rto_cnno')->select('gms_rto_dtls.rto_cnno', 'gms_rto_dtls.rto_mfdate', 'gms_rto_dtls.rto_mftime', 'gms_dmf_dtls.dmf_wt', 'gms_dmf_dtls.dmf_pcs', 'gms_dmf_dtls.dmf_delv_amt');
        }
        return $getRto->paginate($request->per_page);
    }

    /**
     * @OA\Post(
     * path="/addRto",
     * summary="add Rto",
     * operationId="addRto",
     *  tags={"Rto"},
     * @OA\Parameter(
     *   name="mf_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_date",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_time",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_emp_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_origin_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_origin",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_dest_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_dest",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_mode",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_srno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_pmfno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_wt",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_vol_wt",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_actual_wt",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_pcs",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_pmf_dest",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_remarks",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_entry_date",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_receieved_emp",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_received_by",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_received_date",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_transport_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_ro",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mf_dest_ro",
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

    public function addRto(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $user_type = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();

        $input = $this->request->all();
        $getRtoUpdate = GmsRtoDtls::where('rto_cnno', '=', $request->rto_cnno)->where('is_deleted', 0)->first();
        if ($getRtoUpdate) {
            $editRtoUpdate = GmsRtoDtls::find($getRtoUpdate->id);
            $editRtoUpdate->rto_reason = $request->rto_reason;
            $editRtoUpdate->rto_remarks = $request->rto_remarks;
            $editRtoUpdate->userid = $sessionObject->admin_id;
            $editRtoUpdate->rto_branch = $user_type->office_code;
            $editRtoUpdate->update($input);
            return $this->successResponse(self::CODE_OK, "Rto Details Update Successfully!!", $editRtoUpdate);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Cnno Not Found");
        }
    }

    public function viewSearchInvoiceSf(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $cust_type = $request->cust_type;
        $viewAllInvoicesf = GmsInvoice::select('fr_invoice_no', 'invoice_date', 'cust_type', 'customer_code', 'fr_actual_service_charge', 'fr_service_charge', 'fr_less_billing_discount', 'fr_net_service_charge', 'fr_sub_total', 'fr_fuel_amount', 'fr_actual_less_delivery_discount', 'fr_less_sf_discount', 'fr_total', 'fr_grand_total', 'basic_value');

        if ($request->has('month')) {
            $viewAllInvoicesf->where('gms_invoice.month', $month);
        }
        if ($request->has('year')) {
            $viewAllInvoicesf->where('gms_invoice.year', $year);
        }
        if ($request->has('cust_type')) {
            $viewAllInvoicesf->where('gms_invoice.branch_code', $cust_type);
        }

        $viewAllInvoicesf->orderBy('id', 'DESC');
        return $data = $viewAllInvoicesf->paginate($request->per_page);
    }

    public function viewSearchInvoice(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
        $cust_type = $request->cust_type;
        $customer_code = $request->customer_code;
        $viewbill = GmsInvoice::select('fr_invoice_no', 'invoice_date', 'cust_type', 'customer_code', 'fr_actual_service_charge', 'fr_service_charge', 'fr_less_billing_discount', 'fr_net_service_charge', 'fr_sub_total', 'fr_fuel_amount', 'fr_actual_less_delivery_discount', 'fr_less_sf_discount', 'fr_total', 'fr_grand_total', 'basic_value');

        if ($request->has('month')) {
            $viewbill->where('month', $month);
        }
        if ($request->has('year')) {
            $viewbill->where('year', $year);
        }
        if ($request->has('cust_type')) {
            $viewbill->where('cust_type', $cust_type);
        }
        if ($request->has('customer_code')) {
            $viewbill->where('customer_code', $customer_code);
        }

        //  $viewbill->orderBy('id', 'DESC');
        return $viewbill->paginate($request->per_page);
    }

    public function viewInvoiceScPrint()
    {
        $input = $this->request->all();
        $actual_invoice = GmsInvoice::join('gms_customer', 'gms_invoice.customer_code', '=', 'gms_customer.cust_code')->where('gms_invoice.ac_invoice_no', $input['invoice_no'])->select('gms_invoice.customer_code as customer', 'gms_customer.cust_la_ent', 'gms_invoice.fr_service_charge', 'gms_invoice.fr_invoice_no')->first();

        $data['actual_invoice'] = $actual_invoice;
        $collection = new Collection([$data]);
        return $collection;
    }

    public function viewAdditional()
    {
        $input = $this->request->all();
        $response['actual'] = GmsInvoice::where('ac_invoice_no', $input['invoice_no'])->select('fr_less_billing_discount as booking_discount', 'fr_less_delivery_discount as delivery_discount', 'fr_less_sf_discount as sf_discount')->first();
        $response['discount'] = GmsInvoice::where('ac_invoice_no', $input['invoice_no'])->select('fr_less_billing_discount as booking_discount', 'fr_actual_less_delivery_discount as delivery_discount', 'fr_actual_less_sf_discount as sf_discount')->first();
        $response['voucher'] = GmsInvoice::where('ac_invoice_no', $input['invoice_no'])->select('fr_voucher_amount')->first();
        return $response;
    }

    public function viewSfAdditional()
    {
        $input = $this->request->all();
        $viewSfDetails = GmsInvoice::join('gms_invoice_sf', 'gms_invoice.invoice_no', '=', 'gms_invoice_sf.invoice_no')->where('gms_invoice_sf.customer_code', 'gms_invoice_sf.customer_type', 'gms_invoice_sf.reg_booking', 'gms_invoice_sf.reg_amt', 'gms_invoice_sf.direct_booking', 'gms_invoice_sf.direct_amt', 'gms_invoice_sf.total_cnno', 'total_amt')->where('gms_invoice_sf.invoice_no', $input['invoice_no'])->first();
        return $viewSfDetails;
    }

    public function empReport(Request $request)
    {
        if ($request->has('emp_type') or $request->has('emp_work_type') or $request->has('emp_rep_offtype') or $request->has('emp_rep_office') or $request->has('emp_code') or $request->has('emp_name')) {
            return GmsEmp::where('emp_type', $request->emp_type)
                ->orWhere('emp_work_type', $request->emp_work_type)
                ->orWhere('emp_rep_offtype', $request->emp_rep_offtype)
                ->orWhere('emp_rep_office', $request->emp_rep_office)
                ->orWhere('emp_code', $request->emp_code)
                ->orWhere('emp_name', $request->emp_name)
                ->select('emp_code', 'emp_name', 'emp_city', 'emp_add1', 'emp_add2', 'emp_phone', 'emp_email', 'emp_sex', 'emp_bldgrp', 'emp_dob', 'emp_education', 'emp_doj', 'emp_dept', 'emp_dsg', 'emp_work_type', 'emp_status', 'emp_dor', 'emp_type', 'emp_rep_office')->get();
        } else {
            return GmsEmp::select('id', 'emp_code', 'emp_name')->get();
        }
    }

    public function conIpmfToRto(Request $request)
    {
        $conIpmfToRto = GmsPmfDtls::where('is_deleted', 0)->where('pmf_cnno', $request->cnno)->select('pmf_type', 'pmf_origin', 'pmf_date', 'pmf_time', 'pmf_wt', 'pmf_pcs')->first();
        if ($conIpmfToRto) {
            return $this->successResponse(self::CODE_OK, "Get Cnno Details Successfully!!", $conIpmfToRto);
        } else {
            return 'Check Cnno No';
        }
    }
}
