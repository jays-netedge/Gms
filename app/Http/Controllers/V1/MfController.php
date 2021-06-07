<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\GmsBookingDtls;
use App\Models\GmsCoMail;
use App\Models\GmsOffice;
use App\Models\GmsPmfDtls;
use App\Models\GmsNdelReason;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\MfTrait;
use Illuminate\Http\Request;
use App\Models\GmsDmfDtls;
use App\Models\GmsMfDtls;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Collection;


class MfController extends Controller
{
    use MfTrait;

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function allDeliveryMf(Request $request)
    {
        $input = $this->request->all();
        $query = GmsDmfDtls::join('gms_customer', 'gms_dmf_dtls.dmf_type', '=', 'gms_customer.cust_type')
            ->select(DB::raw('concat(gms_dmf_dtls.dmf_type," (",gms_dmf_dtls.dmf_emp,") ",gms_customer.cust_la_ent)As customer_type'),

                'gms_dmf_dtls.dmf_fr_code as fr_code',
                'gms_dmf_dtls.dmf_branch as branch',
                'gms_dmf_dtls.dmf_emp as emp',
                'gms_dmf_dtls.dmf_mfno as mnf_code',
                'gms_dmf_dtls.dmf_cnno_type as dmf_type',
                DB::raw('DATE_FORMAT(gms_dmf_dtls.dmf_mfdate, "%d %b, %Y") as date'),
                DB::raw('DATE_FORMAT(gms_dmf_dtls.dmf_mftime, "%l:%i %p") as time'),
                DB::raw('SUM(gms_dmf_dtls.dmf_wt) as total_weight'),
                DB::raw('COUNT(gms_dmf_dtls.dmf_cnno) as total_cnno'),
                DB::raw('SUM(gms_dmf_dtls.dmf_pcs) as total_pcs'),
                DB::raw('SUM(gms_dmf_dtls.dmf_delv_amt) as total_amount'),
            );

        $query->where('gms_dmf_dtls.is_deleted',0);
        $query->groupBy('gms_dmf_dtls.dmf_mfno');
        if ($request->isMethod('get')) {
            return $query->paginate($request->per_page);
        } else {
            $query1 = GmsDmfDtls::select('dmf_cnno as cnno', 'dmf_ref_no as ref_no', 'dmf_wt as weight', 'dmf_pcs as pcs', 'dmf_pin as pincode', 'dmf_consgn_add as consignee_name_address', 'dmf_remarks as remark', DB::raw('CONCAT(gms_dmf_dtls.dl_name , \' \',  gms_dmf_dtls.dl_phone) AS received_name_and_phone_no'), 'dl_signature as signature', 'dmf_cn_status');
            $query1->where('is_deleted', 0);
            $query1->where('dmf_mfno', $input['dmf_mfno']);
            $cnnoData = $query1->orderBy('created_at', 'desc')->get();
            return $cnnoData;
        }
    }

    public function viewMfNormal()
    {
        return $this->viewManifestNormal();
    }

    public function viewDetailsPrint()
    {
        return $this->viewManifestDetailsPrint();
    }

    public function viewAllDeliveryUpdate(Request $request)
    {
        $input = $this->request->all();
        $query = GmsDmfDtls::join('gms_emp', 'gms_emp.emp_code', '=', 'gms_dmf_dtls.dmf_emp')->select(
            'gms_dmf_dtls.dmf_cnno_type as dmf_type',
            'gms_dmf_dtls.dmf_mfno as mnf_code',
            DB::raw('CONCAT(gms_dmf_dtls.dmf_type,"(",gms_dmf_dtls.dmf_emp,")"," ",gms_emp.emp_name) AS customer_type'),
            DB::raw('SUM(gms_dmf_dtls.dmf_wt) as dmf_delv_amt'),
            DB::raw('count(gms_dmf_dtls.dmf_cnno) as total_cnno'),
            DB::raw('DATE_FORMAT(gms_dmf_dtls.dmf_mfdate, "%d %b, %Y") as date'),
            DB::raw('DATE_FORMAT(gms_dmf_dtls.dmf_mftime, "%l:%i %p") as time'),
            DB::raw('SUM(gms_dmf_dtls.dmf_wt) as weight'),
            DB::raw('SUM(gms_dmf_dtls.dmf_pcs) as pcs'),
        );
        $query->where('gms_dmf_dtls.is_deleted',0);
        $query->where('gms_dmf_dtls.dmf_cn_status', 'D');
        $query->groupBy('gms_dmf_dtls.dmf_mfno');
        if ($request->isMethod('get')) {
            return $query->paginate($request->per_page);
        } else {
            $query1 = GmsDmfDtls::select('dmf_cnno as cnno', 'dmf_ref_no as ref_no', 'dmf_wt as weight', 'dmf_pcs as pcs', 'dmf_pin as pincode', 'dmf_consgn_add as consignee_name_address', 'dmf_remarks as remark', DB::raw('CONCAT(gms_dmf_dtls.dl_name , \' \',  gms_dmf_dtls.dl_phone) AS received_name_and_phone_no'), 'dl_signature as signature', 'dmf_cn_status');
            $query1->where('is_deleted',0);
            $query1->where('dmf_mfno', $input['dmf_mfno']);
            $query1->where('dmf_cn_status', '=', 'D');
            $cnnoData = $query1->get();
            return $cnnoData;
        }
    }

    public function viewDeliveryDetails(Request $request)
    {
        $input = $this->request->all();
        $response = array();
        $response['details'] = GmsDmfDtls::select('dmf_fr_code as agent', 'dmf_mfno as manifest_no', 'dmf_emp as prepared_by', 'dmf_mfdate as date', 'dmf_mftime as time')->where('is_deleted',0)->where('dmf_mfno', $input['dmf_no'])->first();
        $response['cnno'] = GmsDmfDtls::select('dmf_cnno as cnno', 'dmf_wt as weight', 'dmf_pcs as pcs', 'dmf_pin as pincode', 'dmf_dest as destination', 'dmf_remarks as remark')->where('is_deleted',0)->where('dmf_mfno', $input['dmf_no'])->get();
        $response['actual_packet_wight'] = GmsDmfDtls::select(DB::raw('sum(dmf_wt) as totalWt'), DB::raw('count(dmf_cnno) as total_cnno'))->where('is_deleted',0)->where('dmf_mfno', $input['dmf_no'])->first();
        return $response;
    }


    public function addDeliveryMf()
    {
        $sessionObject = session()->get('session_token');
        $input = $this->request->all();
        $input['userid'] = $sessionObject->admin_id;
        $addGmsPmfDtls = new GmsDmfDtls($input);
        $addGmsPmfDtls->save();
        return $this->successResponse(self::CODE_OK, "Delivery PcMf Added Successfully!!", $addGmsPmfDtls);
    }

    public function deleteDmfCnno()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'dmf_cnno' => 'required|exists:gms_dmf_dtls,dmf_cnno',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getgmsDmfDtls = GmsDmfDtls::where('dmf_cnno', $input['dmf_cnno'])->where('is_deleted', 0)->where('userid', $sessionObject->admin_id)->get();
        /*$getgmsDmfDtls = gmsPmfDtls::where('pmf_cnno', $input['pmf_cnno'])->where('user_id', $sessionObject->admin_id)->first();*/
        if ($getgmsDmfDtls) {
            foreach ($getgmsDmfDtls as $value) {
                # code...
                $value['is_deleted'] = 1;
                $value['dmf_cnno'] = '';
                $value['dmf_srno'] = '';
                $value->save();
            }
            // $getgmsPmfDtls->is_deleted = 1;
            // $getgmsPmfDtls->pmf_cnno = '';
            // $getgmsPmfDtls->pmf_srno = '';
            // $getgmsPmfDtls->save();
            return $this->successResponse(self::CODE_OK, "Delivery Packet Delete Successfully!!");
        } else {
            return "No data Found";
        }
    }

    public function getDmfCnnoDetails()
    {
        $input = $this->request->all();
        $getDmfDetails = GmsDmfDtls::where('dmf_cnno', $input['dmf_cnno'])->Orwhere('dmf_drsno',$input['dmf_drsno'])->where('is_deleted',0)->get();
        return $getDmfDetails;
    }

    public function getDmfDetails()
    {
        $input = $this->request->all();
        $getDmfDetails = GmsDmfDtls::where('dmf_ref_no', $input['dmf_ref_no'])->orWhere('dmf_cnno', $input['dmf_cnno'])->where('is_deleted', 0)->first();
        return $getDmfDetails;
    }

    public function addDeliveryUpdate()
    {
        $input = $this->request->all();
        $getDmfData = GmsDmfDtls::where('dmf_ref_no', $input['dmf_ref_no'])->orWhere('dmf_cnno', $input['dmf_cnno'])->where('is_deleted', 0)->first();
        $getDmfDate->dmf_mfdate = $input['dmf_mfdate'];
        $getDmfDate->dmf_mftime = $input['dmf_mftime'];
        $getDmfData->dmf_cn_status = isset($input['dmf_cn_status']) ? $input['dmf_cn_status']:"";
        $getDmfData->dmf_ndel_reason = isset($input['dmf_ndel_reason']) ? $input['dmf_ndel_reason']:"";
        $getDmfData->update($input);
        return $this->successResponse(self::CODE_OK, "Update Successfully!!", $getDmfData);

    }

    public function nonDeliveryDropDown()
    {
        $getNonDeliveryData = GmsNdelReason::select('ndel_code as value','ndel_desc as label')->where('is_deleted', 0)->get();

        $data['label'] = 'ndel_desc';
        $data['options'] = $getNonDeliveryData;
        $collection = new Collection([$data]);
        return $collection;
    }

    public function loadAlert(Request $request)
    {
        $mf_ro = $this->request->mf_ro;
        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $opmf_no = $this->request->opmf_no;
        $getMfDetailsNotReceived = GmsPmfDtls::join('gms_office', 'gms_pmf_dtls.pmf_origin', '=', 'gms_office.office_code')->select(
            'gms_pmf_dtls.pmf_date',
            'gms_pmf_dtls.pmf_no as opmf',
            // DB::raw('concat(gms_pmf_dtls.pmf_origin ,"(",gms_office.office_name,")")As from'),
            DB::raw('concat(gms_pmf_dtls.pmf_origin,"(",gms_office.office_name,")")As agent'),
            // 'gms_pmf_dtls.pmf_origin AS from',
            DB::raw('count(pmf_cnno) As total_cnno'),
            DB::raw('SUM(gms_pmf_dtls.pmf_wt) as total_weight')
        );
        $getMfDetailsNotReceived->groupBy('gms_pmf_dtls.pmf_no');
        $getMfDetailsNotReceived->whereNull('pmf_received_pcs');
        $getMfDetailsReceived = GmsPmfDtls::join('gms_office', 'gms_pmf_dtls.pmf_origin', '=', 'gms_office.office_code')->select(
            'gms_pmf_dtls.pmf_date',
            'gms_pmf_dtls.pmf_no as opmf',
            // DB::raw('concat("[",gms_pmf_dtls.pmf_origin,",",gms_office.office_name,"]")As from'),
            //DB::raw('concat(gms_pmf_dtls.pmf_origin,"(",gms_office.office_name,")") AS from'),
            DB::raw('concat(gms_pmf_dtls.pmf_origin,"(",gms_office.office_name,")")As agent'),
            // 'gms_pmf_dtls.pmf_origin AS from',
            DB::raw('count(pmf_cnno) As total_cnno'),
            DB::raw('SUM(gms_pmf_dtls.pmf_wt) as total_weight')
        );
        $getMfDetailsReceived->groupBy('gms_pmf_dtls.pmf_no');
        $getMfDetailsReceived->whereNotNull('pmf_received_pcs');

        if ($request->has('from_date') && $request->has('to_date')) {
            $getMfDetailsNotReceived->whereBetween('gms_pmf_dtls.pmf_date', [$from_date, $to_date]);
            $getMfDetailsReceived->whereBetween('gms_pmf_dtls.pmf_date', [$from_date, $to_date]);
        }
        if ($request->has('mf_ro')) {
            $getMfDetailsNotReceived->Where('gms_pmf_dtls.mf_ro', $mf_ro);
            $getMfDetailsReceived->Where('gms_pmf_dtls.mf_ro', $mf_ro);
        }
        if ($request->has('opmf_no')) {
            $getMfDetailsNotReceived->Where('gms_pmf_dtls.pmf_no', $opmf_no);
            $getMfDetailsReceived->Where('gms_pmf_dtls.pmf_no', $opmf_no);
        }
        $response['Status']['notReceived'] = $getMfDetailsNotReceived->paginate($request->per_page);
        $response['Status']['received'] = $getMfDetailsReceived->paginate($request->per_page);
        return $response;
    }

    public function allIncomingPcMf(Request $request)
    {
        $input = $this->request->all();
        $query = GmsPmfDtls::select(
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
        $query->where('gms_pmf_dtls.is_deleted', 0);
        $query->groupBy('gms_pmf_dtls.pmf_no');


        if ($request->isMethod('get')) {
            return $query->paginate($request->per_page);

        } else {

            $query1 = GmsPmfDtls::select('pmf_cnno as cnno', 'pmf_cnno_type as cnno_type', 'pmf_wt as weight', 'pmf_pcs as pcs', 'pmf_pin as pincode', 'pmf_city as city', 'pmf_remarks as remark', 'pmf_origin', 'pmf_status', 'created_at', 'pmf_received_date');
            $query1->where('pmf_no', $input['pmf_no'])->where('gms_pmf_dtls.is_deleted', 0);
            $cnnoData = $query1->orderBy('created_at', 'desc')->get();
            return $cnnoData;
        }
    }

    public function viewIncomingPcMfDetails()
    {
        $input = $this->request->all();
        $response = array();

        $response['ipmf'] = GmsPmfDtls::join('gms_city', 'gms_pmf_dtls.pmf_city', '=', 'gms_city.city_code')->select('gms_pmf_dtls.pmf_no as manifest_no', 'gms_pmf_dtls.pmf_origin as origin_branch', 'gms_pmf_dtls.pmf_dest as dest_branch', 'gms_pmf_dtls.pmf_mode as mode',
            DB::raw('concat(gms_pmf_dtls.pmf_date," ",gms_pmf_dtls.pmf_time )As date'),
            DB::raw('count(gms_pmf_dtls.pmf_wt) as packet_wt'), 'gms_pmf_dtls.pmf_type as manifest_type',
            DB::raw('concat(gms_city.city_name,"(",gms_city.city_code,")")As city')
        )->where('gms_pmf_dtls.pmf_no', $input['pmf_no'])->first();
        $response['pending'] = GmsPmfDtls::whereColumn('pmf_pcs', '<>', 'pmf_received_pcs')->where('pmf_no', $input['pmf_no'])->select('pmf_cnno as cnno', 'pmf_cnno_type as cnno_type', 'pmf_wt as weight', 'pmf_vol_wt as vol_wt', 'pmf_pcs as pcs', 'pmf_pin as pincode', 'pmf_doc as doc', 'pmf_status', 'pmf_remarks as remark')->where('gms_pmf_dtls.is_deleted', 0)->get();
        $response['Complete'] = GmsPmfDtls::whereColumn('pmf_pcs', '=', 'pmf_received_pcs')->where('pmf_no', $input['pmf_no'])->select('pmf_cnno as cnno', 'pmf_cnno_type as cnno_type', 'pmf_wt as weight', 'pmf_vol_wt as vol_wt', 'pmf_pcs as pcs', 'pmf_pin as pincode', 'pmf_remarks as remark', 'pmf_doc as doc', 'pmf_status', 'pmf_status as status')->where('gms_pmf_dtls.is_deleted', 0)->get();
        $response['total'] = GmsPmfDtls::select(DB::raw('SUM(pmf_wt) as total_weight'), DB::raw('COUNT(pmf_pcs) as total_cnno'), DB::raw('SUM(pmf_pcs) as total_pcs'), DB::raw('SUM(pmf_vol_wt) as total_vol_amount'))->where('pmf_no', $input['pmf_no'])->where('is_deleted', 0)->first();
        $response['actua_packet_wight'] = GmsPmfDtls::select(DB::raw('sum(pmf_actual_wt) as totalActualwt'))->where('pmf_no', $input['pmf_no'])->where('gms_pmf_dtls.is_deleted', 0)->first();
        return $response;

    }

    public function advanceSearchInMaster(Request $request)
    {
        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $mf_ro = $this->request->mf_ro;
        $mf_origin = $this->request->mf_origin;
        $mf_mode = $this->request->mf_mode;
        $mf_no = $this->request->opmf_no;

        $advanceSearchImf = GmsMfDtls::select(
            'mf_no as mf',
            'mf_mode as mode',
            'mf_time as time',
            DB::raw('count(mf_pmfno)As totalPacket'),
            DB::raw('concat("[",mf_origin,",",mf_dest,"]")As ManifestType'),
            DB::raw('DATE_FORMAT(mf_date,"%d %b, %Y") as date'),
            DB::raw('SUM(mf_wt) as total_weight'),
            DB::raw('SUM(mf_vol_wt) as vol_weight'),
            DB::raw('SUM(mf_pcs)As pcs'),
        );
        $advanceSearchImf->groupBy('mf_no');

        if ($request->has('from_date') && $request->has('to_date')) {
            $advanceSearchImf->whereBetween('mf_date', [$from_date, $to_date]);
        }
        if ($request->has('mf_ro')) {
            $advanceSearchImf->Where('mf_ro', $mf_ro);
        }
        if ($request->has('mf_origin')) {
            $advanceSearchImf->Where('mf_origin', $mf_origin);
        }
        if ($request->has('mf_mode')) {
            $advanceSearchImf->where('mf_mode', $mf_mode);
        }
        if ($request->has('opmf_no')) {
            $advanceSearchImf->where('mf_no', $mf_no);
        }

        $advanceSearchImf->where('gms_pmf_dtls.is_deleted', 0);
        return $advanceSearchImf->paginate($request->per_page);
    }

    public function viewIncomingMasterManifest(Request $request)
    {
        $input = $this->request->all();
        $viewIncomingMasterManifest = GmsMfDtls::select(
            'mf_no as mf',
            'mf_mode as mode',
            'mf_time as time',
            DB::raw('count(mf_pmfno)As totalPacket'),
            DB::raw('concat("[",mf_origin,",",mf_dest,"]")As ManifestType'),
            DB::raw('DATE_FORMAT(mf_date,"%d %b, %Y") as date'),
            DB::raw('SUM(mf_wt) as total_weight'),
            DB::raw('SUM(mf_vol_wt) as vol_weight'),
            DB::raw('SUM(mf_pcs)As pcs'),
        );
        $viewIncomingMasterManifest->groupBy('mf_no');
        if ($request->isMethod('get')) {
            return $viewIncomingMasterManifest->paginate($request->per_page);
        } else {

            $query1 = GmsMfDtls::select('mf_pmfno as pmf_no', 'mf_wt', 'mf_pcs', 'mf_pmf_dest', 'mf_remarks', 'mf_ro', 'mf_status', 'mf_date', 'mf_received_date');
            $query1->where('mf_no', $input['mf_no']);
            $cnnoData = $query1->orderBy('created_at', 'desc')->get();
            return $cnnoData;
        }
    }

    public function addOutGoingPcMf(Request $request)
    {
        $input = $this->request->all();
        $sessionObject = session()->get('session_token');
        $user_office = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();
      
        $input['pmf_no'] = $input['pmf_no'];
        $input['pmf_type'] = "OPMF";
        $input['pmf_actual_wt'] = isset($input['pmf_actual_wt']) ? $input['pmf_actual_wt']: "";
        $input['userid'] = $sessionObject->admin_id;
        $addGmsPmfDtls = new GmsPmfDtls($input);
        $addGmsPmfDtls->save();
     
            // $addGmsMfDtls = new GmsMfDtls();
            // $addGmsMfDtls->mf_no = $input['mf_no'];
            // $addGmsMfDtls->mf_type = "OMF";
            // $addGmsMfDtls->mf_time = $addGmsPmfDtls->pmf_time;
            // $addGmsMfDtls->mf_origin = $addGmsPmfDtls->pmf_origin;
            // $addGmsMfDtls->mf_dest = $addGmsPmfDtls->pmf_dest;
            // $addGmsMfDtls->mf_mode = $addGmsPmfDtls->pmf_mode;
            // $addGmsMfDtls->mf_srno = 1;
            // $addGmsMfDtls->mf_pmfno = $addGmsPmfDtls->pmf_no;
            // $addGmsMfDtls->mf_wt = isset($input['mf_wt']) ? $input['mf_wt']:"";
            // $addGmsMfDtls->mf_actual_wt = isset($input['mf_actual_wt']) ? $input['mf_actual_wt']:"";
            // $addGmsMfDtls->mf_vol_wt = isset($input['mf_vol_wt']) ? $input['mf_vol_wt']:"";
            // $addGmsMfDtls->mf_pcs = isset($input['mf_pcs']) ? $input['mf_pcs']:"";
            // $addGmsMfDtls->mf_pmf_dest = 1;
            // $addGmsMfDtls->mf_remarks = isset($input['mf_remarks']) ? $input['mf_remarks']:"" ;
            // $addGmsMfDtls->mf_status = isset($input['mf_status']) ? $input['mf_status']:"" ;
            // $addGmsMfDtls->mf_receieved_emp = 1;
            // $addGmsMfDtls->mf_received_by = 1;
            // $addGmsMfDtls->mf_ro = $addGmsPmfDtls->pmf_dest_ro;
            // $addGmsMfDtls->mf_date = Carbon::now()->toDateTimeString();
            // $addGmsMfDtls->mf_received_date = Carbon::now()->toDateTimeString();
            // $addGmsMfDtls->mf_entry_date = Carbon::now()->toDateTimeString();
            // $addGmsMfDtls->mf_cd_no = 0;
            // $addGmsMfDtls->mf_misroute = 0;
            // $addGmsMfDtls->userid = $addGmsPmfDtls->userid;
            // $addGmsMfDtls->save();
            // return $this->successResponse(self::CODE_OK, "OutGoung Added Successfully!!", $addGmsPmfDtls);
        

    }

    public function addOutGoingMasterMf(Request $request)
    {
        $input = $this->request->all();
        $sessionObject = session()->get('session_token');
        $user_check = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();
            
          
          
            $addGmsMfDtls = new GmsMfDtls();
            $addGmsMfDtls->mf_no = $input['mf_no'];
            $addGmsMfDtls->mf_type = "OMF";
            $addGmsMfDtls->manifest_type = isset($input['manifest_type']) ? $input['manifest_type']:"" ;
            $addGmsMfDtls->mf_time = $input['mf_time'];
            $addGmsMfDtls->mf_origin =  isset($input['mf_origin']) ? $input['mf_origin']:"" ;
            $addGmsMfDtls->mf_dest =  isset($input['mf_dest']) ? $input['mf_dest']:"" ;
            $addGmsMfDtls->mf_mode = $input['mf_mode'];
            $addGmsMfDtls->mf_srno =  $input['mf_srno'];
            $addGmsMfDtls->mf_pmfno = $input['mf_pmfno'];
            $addGmsMfDtls->mf_wt = isset($input['mf_wt']) ? $input['mf_wt']:"";
            $addGmsMfDtls->mf_actual_wt = isset($input['mf_actual_wt']) ? $input['mf_actual_wt']:"";
            $addGmsMfDtls->mf_vol_wt = isset($input['mf_vol_wt']) ? $input['mf_vol_wt']:"";
            $addGmsMfDtls->mf_pcs = isset($input['mf_pcs']) ? $input['mf_pcs']:"";
            $addGmsMfDtls->mf_pmf_dest = isset($input['mf_pmf_dest']) ? $input['mf_pmf_dest']:"" ;
            $addGmsMfDtls->mf_remarks = isset($input['mf_remarks']) ? $input['mf_remarks']:"" ;
            $addGmsMfDtls->mf_status = isset($input['mf_status']) ? $input['mf_status']:"" ;
            $addGmsMfDtls->mf_receieved_emp = $input['mf_receieved_emp'];
            $addGmsMfDtls->mf_received_by = $input['mf_received_by'];
            $addGmsMfDtls->mf_ro = isset($input['mf_ro']) ? $input['mf_ro']:"";
            $addGmsMfDtls->mf_date = Carbon::now()->toDateTimeString();
            $addGmsMfDtls->mf_received_date = Carbon::now()->toDateTimeString();
            $addGmsMfDtls->mf_entry_date = Carbon::now()->toDateTimeString();
            $addGmsMfDtls->mf_cd_no = isset($input['mf_cd_no']) ? $input['mf_cd_no']:"";
            $addGmsMfDtls->mf_misroute = isset($input['mf_misroute']) ? $input['mf_misroute']: "";
            $addGmsMfDtls->userid = $sessionObject->admin_id;
            $addGmsMfDtls->save();
            return $this->successResponse(self::CODE_OK, "Outgoing Mf Added Successfully!!", $addGmsMfDtls);
    }

    public function getCnnoDetails()
    {
        $input = $this->request->all();
        $getCnnoDetails = GmsBookingDtls::select('book_mfno', 'book_mfdate', 'book_mftime', 'book_cnno', 'book_weight', 'book_vol_weight', 'book_pcs', 'book_pin', 'book_dest', 'book_doc', 'book_topay', 'book_cod', 'book_remarks', 'book_mode')->where('book_cnno', $input['book_cnno'])->first();
        return $getCnnoDetails;
    }

    public function generateMfNo()
    {

        if (session()->has('session_token')) {

        $sessionObject = session()->get('session_token');
        /*$user_check = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();*/
        $last_In_mfno = GmsMfDtls::select('id','mf_no')->where('mf_origin', $this->request->office_code )->orderBy('created_at', 'desc')->first();
        if(empty($last_In_mfno)){
             return 0;

        }else{
           return $last_In_mfno;
        }
    }
       
}

    public function generateOPMfNo()
    {

        if (session()->has('session_token')) {

        $sessionObject = session()->get('session_token');
        /*$user_check = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();*/
        $last_In_mfno = GmsPmfDtls::select('id','pmf_no')->where('pmf_origin', $this->request->office_code )->orderBy('created_at', 'desc')->first();
        if(empty($last_In_mfno)){
             return 0;

        }else{
           return $last_In_mfno;
        }
    }
       
}

    public function generateDmfNo()
    {

        if (session()->has('session_token')) {

        $sessionObject = session()->get('session_token');
        /*$user_check = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();*/
        $last_In_mfno = GmsDmfDtls::select('id','dmf_mfno')->where('dmf_branch', $this->request->office_code )->orderBy('created_at', 'desc')->first();
        if(empty($last_In_mfno)){
             return 0;

        }else{
           return $last_In_mfno;
        }
    }
       
}

    public function getOutGoingMasterMFDetails()
    {
        $input = $this->request->all();
         $response['outGoingMasterMfDetails'] = GmsPmfDtls::select(
            'pmf_no',
            DB::raw('SUM(pmf_wt) as mf_wt'),
            DB::raw('COUNT(pmf_cnno) as mf_cnno'),
            'pmf_dest as mf_dest',
           'pmf_remarks as mf_remarks'

        )->where('pmf_no', $input['pmf_no'])->where('pmf_type','OPMF')->get();
        return $response;
    }

    public function addInComingPacketMf(Request $request)
    {
        $input = $this->request->all();
        $sessionObject = session()->get('session_token');
        $user_office = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();
        $getlastId = GmsPmfDtls::select('id', 'pmf_no', 'pmf_srno')->orderBy('created_at', 'desc')->first();
        if (empty($getlastId)) {
            $firstNumber = 10000;
            $mf_no = $user_office->office_code . "IPMF" . $firstNumber;
            $input['pmf_no'] = $mf_no;
        } else {
            $lastNumber = substr($getlastId->pmf_no, -5);
            $newNum = $lastNumber + 1;
            if ($this->request->pmf_srno == 1) {
                $mf_no1 = $user_office->office_code . "IPMF " . $newNum;
                $input['pmf_no'] = $mf_no1;
            } else {
                $lastNumber1 = substr($getlastId->pmf_no, -5);
                $mf_no = $user_office->office_code . "IPMF" . $lastNumber1;
                $input['pmf_no'] = $mf_no;
            }
        }
        $input['pmf_type'] = "IPMF";
        $input['userid'] = $sessionObject->admin_id;
        $addGmsPmfDtls = new GmsPmfDtls($input);
        $addGmsPmfDtls->save();
        // if (!empty($addGmsPmfDtls)) {
        //     $getlastMfId = GmsMfDtls::select('id', 'mf_no', 'mf_srno')->orderBy('created_at', 'desc')->first();
        //     if (empty($getlastMfId)) {
        //         $firstNumber = 10000;
        //         $firstTimeMfNo = $user_office->office_code . "IMF" . $firstNumber;
        //         $input['mf_no'] = $firstTimeMfNo;
        //     } else {
        //         $lastNumberFirstTime = substr($getlastMfId->mf_no, -5);
        //         $newMfNum = $lastNumberFirstTime + 1;
        //         if ($this->request->pmf_srno == 1) {
        //             $autoInMfNo = $user_office->office_code . "IMF " . $newMfNum;
        //             $input['mf_no'] = $autoInMfNo;
        //         } else {
        //             $lastNumberMf = substr($getlastMfId->mf_no, -5);
        //             $lastMfNo = $user_office->office_code . "IMF" . $lastNumberMf;
        //             $input['mf_no'] = $lastMfNo;
        //         }
        //     }
        //     $addGmsMfDtls = new GmsMfDtls();
        //     $addGmsMfDtls->mf_time = $addGmsPmfDtls->pmf_time;
        //     $addGmsMfDtls->mf_origin = $addGmsPmfDtls->pmf_origin;
        //     $addGmsMfDtls->mf_dest = $addGmsPmfDtls->pmf_dest;
        //     $addGmsMfDtls->mf_mode = $addGmsPmfDtls->pmf_mode;
        //     $addGmsMfDtls->mf_srno = 1;
        //     $addGmsMfDtls->mf_pmfno = $addGmsPmfDtls->pmf_no;
        //     $addGmsMfDtls->mf_wt = isset($input['pmf_wt']) ? $input['pmf_wt']:"";
        //     $addGmsMfDtls->mf_actual_wt = isset($input['pmf_actual_wt']) ? $input['pmf_actual_wt']:"";
        //     $addGmsMfDtls->mf_pcs = isset($input['pmf_pcs']) ? $input['pmf_pcs']:"" ;
        //     $addGmsMfDtls->mf_pmf_dest = 1;
        //     $addGmsMfDtls->mf_remarks = isset($input['mf_remarks']) ? $input['mf_remarks']: "";
        //     $addGmsMfDtls->mf_status = isset($input['mf_status']) ? $input['mf_status']: "";
        //     $addGmsMfDtls->mf_receieved_emp = 1;
        //     $addGmsMfDtls->mf_received_by = 1;
        //     $addGmsMfDtls->mf_ro = $addGmsPmfDtls->pmf_dest_ro;
        //     $addGmsMfDtls->mf_date = Carbon::now()->toDateTimeString();
        //     $addGmsMfDtls->mf_received_date = Carbon::now()->toDateTimeString();
        //     $addGmsMfDtls->mf_entry_date = Carbon::now()->toDateTimeString();
        //     $addGmsMfDtls->mf_cd_no = 0;
        //     $addGmsMfDtls->mf_misroute = 0;
        //     $addGmsMfDtls->userid = $addGmsPmfDtls->userid;
        //     $addGmsMfDtls->mf_no = $input['mf_no'];
        //     $addGmsMfDtls->save();
        //     return $this->successResponse(self::CODE_OK, "InComing Added Successfully!!", $addGmsPmfDtls);
        // }
    }

    public function getMfDetails()
    {
        $input = $this->request->all();
        $response['getCnnoDetails'] = GmsBookingDtls::select(

            'book_mfno as mf_pmfno',
            'book_mfdate as pmf_date',
            'book_mftime as pmf_time',
            'book_srno as pmf_srno',
            'book_cnno as pmf_cnno',
            'book_weight as pmf_wt',
            'book_vol_weight as pmf_vol_wt',
            'book_pcs as pmf_pcs',
            'book_org as pmf_origin',
            'book_dest as pmf_dest',
            'book_mode as pmf_mode',
            'book_remarks as pmf_remarks',
        )->where('book_mfno', $input['book_mfno'])->get();

        $response['mf_wt'] = GmsBookingDtls::select(DB::raw('SUM(book_weight) as mf_wt'),
            DB::raw('SUM(book_vol_weight) as mf_vol_wt'),
            DB::raw('SUM(book_pcs) as mf_pcs'),
            'book_mfdate as mf_date',
            'book_mftime as mf_time',
            'book_srno as mf_srno',
            'book_org as mf_origin',
            'book_dest as mf_dest',
            'book_mode as mf_mode',
            'book_remarks as remarks',
            DB::raw('count(book_cnno) As totalCnnoCount')

        )->where('book_mfno', $input['book_mfno'])->get();
        return $response;
    }

    public function addInComingMasterMf(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();
        $office = GmsOffice::where('office_code', $admin->office_code)->first();
        $input = $this->request->all();
        $addGmsMfDtls = new GmsMfDtls();

     
        $addGmsMfDtls->mf_no = $input['mf_no'];
        $addGmsMfDtls->mf_srno = $input['mf_srno'];
        $addGmsMfDtls->mf_type = 'IMF';
        $addGmsMfDtls->mf_emp_code = $input['mf_emp_code'];
        $addGmsMfDtls->mf_origin_type = $input['mf_origin_type'];
        $addGmsMfDtls->mf_dest_type = $input['mf_dest_type'];
        $addGmsMfDtls->mf_actual_wt = $input['mf_actual_wt'];
        $addGmsMfDtls->mf_pmfno = $office->office_code . "OPMF" . $first;
        $addGmsMfDtls->mf_pmf_dest = $input['mf_pmf_dest'];
        $addGmsMfDtls->mf_entry_date = $input['mf_entry_date'];
        $addGmsMfDtls->mf_received_date = $input['mf_received_date'];
        $addGmsMfDtls->mf_status = $input['mf_status'];
        $addGmsMfDtls->mf_receieved_emp = $input['mf_receieved_emp'];
        $addGmsMfDtls->mf_received_by = $input['mf_received_by'];
        $addGmsMfDtls->mf_transport_type = $input['mf_transport_type'];
        $addGmsMfDtls->mf_ro = $input['mf_ro'];
        $addGmsMfDtls->mf_dest_ro = $input['mf_dest_ro'];
        $addGmsMfDtls->mf_recevied_ro = $input['mf_recevied_ro'];
        $addGmsMfDtls->mf_cd_no = $input['mf_cd_no'];
        $addGmsMfDtls->mf_misroute = $input['mf_misroute'];
        $addGmsMfDtls->changed_direct_emp = $input['changed_direct_emp'];
        $addGmsMfDtls->changed_original_dest_location = $input['changed_original_dest_location'];
        $addGmsMfDtls->mf_date = $input['mf_date'];
        $addGmsMfDtls->mf_time = $input['mf_time'];
        $addGmsMfDtls->mf_wt = $input['mf_wt'];
        $addGmsMfDtls->mf_vol_wt = $input['mf_vol_wt'];
        $addGmsMfDtls->mf_pcs = $input['mf_pcs'];
        $addGmsMfDtls->mf_origin = $input['mf_origin'];
        $addGmsMfDtls->mf_dest = $input['mf_dest'];
        $addGmsMfDtls->mf_mode = $input['mf_mode'];
        $addGmsMfDtls->mf_remarks = isset($input['mf_remarks']) ? $input['mf_remarks'] : "";
        $addGmsMfDtls->userid = $sessionObject->admin_id;
        $addGmsMfDtls->save();

        for ($i = 0; $i < count($input['pmf_cnno']); $i++) {

            $addGmsPmfDtls = new GmsPmfDtls ([
                // 'max_no' => $input['max_no'],
                'pmf_no' => $addGmsMfDtls->mf_pmfno,
                'pmf_type' => "IPMF",
                'pmf_date' => $input['pmf_date'][$i],
                'pmf_time' => $input['pmf_time'][$i],
                'pmf_emp_code' => $addGmsMfDtls->mf_emp_code,
                'pmf_origin' => $input['pmf_origin'][$i],
                'pmf_dest' => $input['pmf_dest'][$i],
                'pmf_mode' => $input['pmf_mode'][$i],
                'pmf_srno' => $input['pmf_srno'][$i],
                'pmf_cnno' => $input['pmf_cnno'][$i],
                'pmf_cnno_type' => "WTD",
                'pmf_wt' => $input['pmf_wt'][$i],
                'pmf_vol_wt' => $input['pmf_vol_wt'][$i],
                'pmf_actual_wt' => $addGmsMfDtls->mf_actual_wt,
                'pmf_received_wt' => $addGmsMfDtls->mf_actual_wt,
                'pmf_vol_received_wt' => $addGmsMfDtls->mf_vol_received_wt,
                'pmf_actual_received_wt' => $addGmsMfDtls->mf_actual_wt,
                'pmf_pcs' => $input['pmf_pcs'][$i],
                'pmf_received_pcs' => isset($input['pmf_received_pcs'][$i]) ? $input['pmf_received_pcs'][$i] : "",
                'pmf_pin' => isset($input['pmf_pin'][$i]) ? $input['pmf_pin'][$i] : "",
                'pmf_city' => isset($input['pmf_city'][$i]) ? $input['pmf_city'][$i] : "",
                'pmf_remarks' => isset($input['pmf_remarks'][$i]) ? $input['pmf_remarks'][$i] : "",
                'pmf_entry_date' => Carbon::now()->toDateTimeString(),
                'pmf_receieved_emp' => $addGmsMfDtls->mf_receieved_emp,
                'pmf_received_by' => $addGmsMfDtls->mf_received_by,
                'pmf_received_date' => $addGmsMfDtls->mf_received_date,
                //'pmf_recieved_type' => $addGmsMfDtls->mf_transport_type,
                //'pmf_mfed' => $input['pmf_mfed'],
                'pmf_transport_type' => $addGmsMfDtls->mf_transport_type,
                'pmf_dest_ro' => $addGmsMfDtls->mf_dest_ro,
                'pmf_recevied_ro' => $addGmsMfDtls->mf_recevied_ro,
                'pmf_cd_no' => $addGmsMfDtls->mf_cd_no,
                'pmf_misroute' => $addGmsMfDtls->mf_misroute,
                'userid' => $sessionObject->admin_id
            ]);
            $addGmsPmfDtls->save();
        }


        $data['MfDetails'] = $addGmsMfDtls;
        $data['PcDetails'] = $addGmsPmfDtls;
        $collection = new Collection([$data]);
        return $collection;

    }

    public function empPmfDelete()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'pmf_cnno' => 'required|exists:gms_pmf_dtls,pmf_cnno',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getgmsPmfDtls = GmsPmfDtls::where('pmf_cnno', $input['pmf_cnno'])->where('is_deleted', 0)->where('userid', $sessionObject->admin_id)->get();
        /*$getgmsPmfDtls = gmsPmfDtls::where('pmf_cnno', $input['pmf_cnno'])->where('user_id', $sessionObject->admin_id)->first();*/
        if ($getgmsPmfDtls) {
            foreach ($getgmsPmfDtls as $value) {
                # code...
                $value['is_deleted'] = 1;
                $value['pmf_cnno'] = '';
                $value['pmf_srno'] = '';
                $value->save();
            }
            // $getgmsPmfDtls->is_deleted = 1;
            // $getgmsPmfDtls->pmf_cnno = '';
            // $getgmsPmfDtls->pmf_srno = '';
            // $getgmsPmfDtls->save();
            return $this->successResponse(self::CODE_OK, "InComing Packet Delete Successfully!!");
        } else {
            return "No data Found";
        }

    }


    public function opmfPending(Request $request)
    {
        $input = $this->request->all();
        $opmfPending = GmsPmfDtls::select(
            'gms_pmf_dtls.pmf_no as opmf',
            DB::raw('DATE_FORMAT(gms_pmf_dtls.created_at, "%d %b, %Y") as date'),
            'gms_pmf_dtls.pmf_time as time',
            'gms_pmf_dtls.pmf_emp_code as emp',
            'gms_pmf_dtls.pmf_mode as mode',
            DB::raw('DATE_FORMAT(gms_pmf_dtls.updated_at, "%d %b, %Y") as last_update_date'),
            DB::raw('concat("[",pmf_origin,",",pmf_dest,"]")As ManifestType'),
            DB::raw('concat(count(pmf_no),"/",COUNT(CASE WHEN gms_pmf_dtls.pmf_received_pcs <> 0 THEN 1 END),"/",sum(pmf_pcs)- sum(pmf_received_pcs),"/",sum(pmf_misroute)) As cnno_status'),
            DB::raw('sum(pmf_actual_wt)As ActualWt'),
            DB::raw('sum(pmf_wt)As Wt'),
            DB::raw('sum(pmf_vol_wt)As VolWt'),
            DB::raw('sum(pmf_pcs)As Pcs'),
            DB::raw('sum(pmf_amt)As Amt'),
        );
        $opmfPending->groupBy('pmf_no');
        $opmfPending->having(DB::raw("sum(pmf_pcs)-sum(pmf_received_pcs)"), "!=", 0);
        if ($request->isMethod('get')) {
            return $opmfPending->paginate($request->per_page);
        } else {
            $response = array();
            $response['opmf'] = GmsPmfDtls::join('gms_office', 'gms_pmf_dtls.pmf_origin', '=', 'gms_office.office_code')->join('gms_office as pmf_dest_tbl', 'gms_pmf_dtls.pmf_dest', '=', 'pmf_dest_tbl.office_code')->where('gms_pmf_dtls.pmf_no', $input['pmf_no'])->select('gms_pmf_dtls.pmf_no as manifest_no',
                DB::raw('concat(gms_office.office_code,"(",gms_office.office_name,")")As origin_branch'),
                DB::raw('concat(pmf_dest_tbl.office_code,"(",pmf_dest_tbl.office_name,")")As dest_branch'),
                DB::raw('concat(gms_pmf_dtls.pmf_date," ",gms_pmf_dtls.pmf_time)As manifest_date'),
                'gms_pmf_dtls.pmf_type as manifest_type', 'gms_pmf_dtls.pmf_mode as mode')->first();

            $response['Pending'] = GmsPmfDtls::whereColumn('pmf_pcs', '<>', 'pmf_received_pcs')->where('pmf_no', $input['pmf_no'])->select('pmf_cnno as cnno', 'pmf_cnno_type as cnno_type', 'pmf_wt as weight', 'pmf_pcs as pcs', 'pmf_pin as pincode', 'pmf_city as city', 'pmf_doc', 'pmf_remarks as remark', 'pmf_received_by as incomed_by', 'pmf_status as status', 'created_at as created_date', 'pmf_received_date as received_date')->get();
            $response['Complete'] = GmsPmfDtls::whereColumn('pmf_pcs', '=', 'pmf_received_pcs')->where('pmf_no', $input['pmf_no'])->select('pmf_cnno as cnno', 'pmf_cnno_type as cnno_type', 'pmf_wt as weight', 'pmf_pcs as pcs', 'pmf_pin as pincode', 'pmf_city as city', 'pmf_doc', 'pmf_remarks as remark', 'pmf_received_by as incomed_by', 'pmf_status as status', 'created_at as created_date', 'pmf_received_date as received_date')->get();
            $response['Misroute'] = GmsPmfDtls::where('pmf_misroute', '!=', 0)->where('pmf_no', $input['pmf_no'])->select('pmf_cnno as cnno', 'pmf_cnno_type as cnno_type', 'pmf_wt as weight', 'pmf_pcs as pcs', 'pmf_pin as pincode', 'pmf_city as city', 'pmf_remarks as remark', 'pmf_received_by as incomed_by', 'pmf_status as status', 'created_at as created_date', 'pmf_doc', 'pmf_received_date as received_date')->get();
            $response['total'] = GmsPmfDtls::select(DB::raw('SUM(pmf_wt) as total_weight'), DB::raw('COUNT(pmf_pcs) as total_cnno'), DB::raw('SUM(pmf_pcs) as total_pcs'), DB::raw('SUM(pmf_vol_wt) as total_vol_amount'))->where('pmf_no', $input['pmf_no'])->where('is_deleted', 0)->first();
            return $response;
        }
    }

    public function opmfCompleted(Request $request)
    {
        $input = $this->request->all();
        $query = GmsPmfDtls::select(
            'gms_pmf_dtls.pmf_no as opmf',
            DB::raw('DATE_FORMAT(gms_pmf_dtls.created_at, "%d %b, %Y") as date'),
            'gms_pmf_dtls.pmf_time as time',
            'gms_pmf_dtls.pmf_emp_code as emp',
            'gms_pmf_dtls.pmf_mode as mode',
            DB::raw('DATE_FORMAT(gms_pmf_dtls.updated_at, "%d %b, %Y") as last_update_date'),
            DB::raw('concat("[",pmf_origin,",",pmf_dest,"]")As ManifestType'),
            DB::raw('concat(COUNT(CASE WHEN gms_pmf_dtls.pmf_received_pcs <> 0 THEN 1 END),"/",count(pmf_no))As cnno_status'),
            DB::raw('sum(pmf_actual_wt)As actual_weight'),
            DB::raw('sum(pmf_wt)As weight'),
            DB::raw('sum(pmf_vol_wt)As vol_wt'),
            DB::raw('sum(pmf_pcs)As pcs'),
            DB::raw('sum(pmf_amt)As amount'),
        );

        $query->groupBy('gms_pmf_dtls.pmf_no');
        $query->having(DB::raw("sum(pmf_pcs)-sum(pmf_received_pcs)"), "=", 0);

        if ($request->isMethod('get')) {
            return $query->paginate($request->per_page);
        } else {
            $response = array();
            $response['opmf'] = GmsPmfDtls::join('gms_office', 'gms_pmf_dtls.pmf_origin', '=', 'gms_office.office_code')->
            join('gms_office as pmf_dest_tbl', 'gms_pmf_dtls.pmf_dest', '=', 'pmf_dest_tbl.office_code')->where('gms_pmf_dtls.pmf_no', $input['pmf_no'])->select('gms_pmf_dtls.pmf_no as manifest_no',
                DB::raw('concat(gms_office.office_code,"(",gms_office.office_name,")")As origin_branch'),
                DB::raw('concat(pmf_dest_tbl.office_code,"(",pmf_dest_tbl.office_name,")")As dest_branch'),
                DB::raw('concat(gms_pmf_dtls.pmf_date," ",gms_pmf_dtls.pmf_time)As manifest_date'),
                'gms_pmf_dtls.pmf_type as manifest_type', 'gms_pmf_dtls.pmf_mode as mode')->first();

            $response['Completed'] = GmsPmfDtls::whereColumn('pmf_pcs', '=', 'pmf_received_pcs')->where('pmf_no', $input['pmf_no'])->select('id', 'pmf_cnno', 'pmf_cnno_type', 'pmf_wt', 'pmf_pcs', 'pmf_pin', 'pmf_city', 'pmf_doc', 'pmf_remarks', 'pmf_received_by', 'pmf_status', 'created_at', 'pmf_received_date')->get();
            $response['total'] = GmsPmfDtls::select(DB::raw('SUM(pmf_wt) as total_weight'), DB::raw('COUNT(pmf_pcs) as total_cnno'), DB::raw('SUM(pmf_pcs) as total_pcs'), DB::raw('SUM(pmf_vol_wt) as total_vol_amount'))->where('pmf_no', $input['pmf_no'])->where('is_deleted', 0)->first();
            return $response;
        }
    }

    public function opmfMisroute(Request $request)
    {
        $input = $this->request->all();
        $opmfMisroute = GmsPmfDtls::select(

            'gms_pmf_dtls.pmf_no as opmf',
            DB::raw('DATE_FORMAT(gms_pmf_dtls.created_at, "%d %b, %Y") as date'),
            'gms_pmf_dtls.pmf_time as time',
            'gms_pmf_dtls.pmf_emp_code as emp',
            'gms_pmf_dtls.pmf_mode as mode',
            DB::raw('DATE_FORMAT(gms_pmf_dtls.updated_at, "%d %b, %Y") as last_update_date'),
            DB::raw('concat("[",pmf_origin,",",pmf_dest,"]")As ManifestType'),
            DB::raw('concat(COUNT(CASE WHEN gms_pmf_dtls.pmf_received_pcs <> 0 THEN 1 END),"/",count(pmf_pcs),"/",sum(pmf_pcs)- sum(pmf_received_pcs),"/",sum(pmf_misroute))As cnno_status'),
            DB::raw('sum(pmf_actual_wt)As ActualWt'),
            DB::raw('sum(pmf_wt)As Wt'),
            DB::raw('sum(pmf_vol_wt)As VolWt'),
            DB::raw('sum(pmf_pcs)As Pcs'),
            DB::raw('sum(pmf_amt)As Amt'),

        );
        $opmfMisroute->groupBy('pmf_no');
        $opmfMisroute->having(DB::raw("sum(pmf_misroute)"), "!=", 0);
        if ($request->isMethod('get')) {
            return $opmfMisroute->paginate($request->per_page);
        } else {
            $response = array();
            $response['opmf'] = GmsPmfDtls::join('gms_office', 'gms_pmf_dtls.pmf_origin', '=', 'gms_office.office_code')->join('gms_office as pmf_dest_tbl', 'gms_pmf_dtls.pmf_dest', '=', 'pmf_dest_tbl.office_code')->where('gms_pmf_dtls.pmf_no', $input['pmf_no'])->select('gms_pmf_dtls.pmf_no as manifest_no',
                DB::raw('concat(gms_office.office_code,"(",gms_office.office_name,")")As origin_branch'),
                DB::raw('concat(pmf_dest_tbl.office_code,"(",pmf_dest_tbl.office_name,")")As dest_branch'),
                DB::raw('concat(gms_pmf_dtls.pmf_date," ",gms_pmf_dtls.pmf_time)As manifest_date'),
                'gms_pmf_dtls.pmf_type as manifest_type', 'gms_pmf_dtls.pmf_mode as mode')->first();
            $response['Pending'] = GmsPmfDtls::whereColumn('pmf_pcs', '<>', 'pmf_received_pcs')->where('pmf_no', $input['pmf_no'])->select('pmf_cnno as cnno', 'pmf_cnno_type as cnno_type', 'pmf_wt as weight', 'pmf_pcs as pcs', 'pmf_pin as pincode', 'pmf_city as city', 'pmf_doc', 'pmf_remarks as remark', 'pmf_received_by as incomed_by', 'pmf_status as status', 'created_at as created_date', 'pmf_received_date as received_date')->get();
            $response['Complete'] = GmsPmfDtls::where('pmf_misroute', '=', 0)->whereColumn('pmf_pcs', '=', 'pmf_received_pcs')->where('pmf_no', $input['pmf_no'])->select('pmf_cnno as cnno', 'pmf_cnno_type as cnno_type', 'pmf_wt as weight', 'pmf_pcs as pcs', 'pmf_pin as pincode', 'pmf_city as city', 'pmf_doc', 'pmf_remarks as remark', 'pmf_received_by as incomed_by', 'pmf_status as status', 'created_at as created_date', 'pmf_received_date as received_date')->get();
            $response['Misroute'] = GmsPmfDtls::where('pmf_misroute', '!=', 0)->where('pmf_no', $input['pmf_no'])->select('pmf_cnno as cnno', 'pmf_cnno_type as cnno_type', 'pmf_wt as weight', 'pmf_pcs as pcs', 'pmf_pin as pincode', 'pmf_city as city', 'pmf_doc', 'pmf_remarks as remark', 'pmf_received_by as incomed_by', 'pmf_status as status', 'created_at as created_date', 'pmf_received_date as received_date')->get();
            $response['total'] = GmsPmfDtls::select(DB::raw('SUM(pmf_wt) as total_weight'), DB::raw('COUNT(pmf_pcs) as total_cnno'), DB::raw('SUM(pmf_pcs) as total_pcs'), DB::raw('SUM(pmf_vol_wt) as total_vol_amount'))->where('pmf_no', $input['pmf_no'])->where('is_deleted', 0)->first();
            return $response;

        }
    }

    public function viewOpmf(Request $request)
    {
        $input = $this->request->all();
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
        $query->where('gms_pmf_dtls.pmf_type','OPMF');
        $query->where('gms_pmf_dtls.is_deleted', 0);
        $query->groupBy('gms_pmf_dtls.pmf_no');
        if ($request->isMethod('get')) {
            return $query->paginate($request->per_page);
        } else {
            $outPcMfAll = GmsPmfDtls::where('pmf_no', $input['pmf_no'])->select('id', 'pmf_cnno', 'pmf_cnno_type', 'pmf_wt', 'pmf_pcs', 'pmf_pin', 'pmf_city', 'pmf_remarks', 'pmf_received_by', 'pmf_status', 'created_at', 'pmf_received_date')->where('gms_pmf_dtls.is_deleted', 0)->get();
            return $outPcMfAll;
        }
    }

    public function viewOpmfDetails()
    {
        $input = $this->request->all();
        $response = array();
        $response['opmf'] = GmsPmfDtls::join('gms_office', 'gms_pmf_dtls.pmf_origin', '=', 'gms_office.office_code')->
        join('gms_office as pmf_dest_tbl', 'gms_pmf_dtls.pmf_dest', '=', 'pmf_dest_tbl.office_code')->where('gms_pmf_dtls.pmf_no', $input['pmf_no'])->select('gms_pmf_dtls.pmf_no as manifest_no',
            DB::raw('concat(gms_office.office_code,"(",gms_office.office_name,")")As origin_branch'),
            DB::raw('concat(pmf_dest_tbl.office_code,"(",pmf_dest_tbl.office_name,")")As dest_branch'),
            DB::raw('concat(gms_pmf_dtls.pmf_date," ",gms_pmf_dtls.pmf_time)As manifest_date'),
            'gms_pmf_dtls.pmf_type as manifest_type', 'gms_pmf_dtls.pmf_mode as mode')->first();
        $response['pending'] = GmsPmfDtls::whereColumn('pmf_pcs', '<>', 'pmf_received_pcs')->where('pmf_no', $input['pmf_no'])->select('pmf_cnno as cnno', 'pmf_cnno_type as cnno_type', 'pmf_wt as weight', 'pmf_vol_wt as vol_wt', 'pmf_pcs as pcs', 'pmf_pin as pincode', 'pmf_city as city', 'pmf_doc as doc', 'pmf_remarks as remark')->get();
        $response['complete'] = GmsPmfDtls::whereColumn('pmf_pcs', '=', 'pmf_received_pcs')->where('pmf_no', $input['pmf_no'])->select('pmf_cnno as cnno', 'pmf_cnno_type as cnno_type', 'pmf_wt as weight', 'pmf_vol_wt as vol_wt', 'pmf_pcs as pcs', 'pmf_pin as pincode', 'pmf_city as city', 'pmf_remarks as remark', 'pmf_doc as doc', 'pmf_status as status')->get();
        $response['misroute'] = GmsPmfDtls::where('pmf_misroute', '!=', 0)->where('pmf_no', $input['pmf_no'])->select('pmf_cnno as cnno', 'pmf_cnno_type as cnno_type', 'pmf_wt as weight', 'pmf_vol_wt as vol_wt', 'pmf_pcs as pcs', 'pmf_pin as pincode', 'pmf_city as city', 'pmf_remarks as remark', 'pmf_doc as doc', 'pmf_status as status')->get();
        $response['total'] = GmsPmfDtls::select(DB::raw('SUM(pmf_wt) as total_weight'), DB::raw('COUNT(pmf_pcs) as total_cnno'), DB::raw('SUM(pmf_pcs) as total_pcs'), DB::raw('SUM(pmf_vol_wt) as total_vol_amount'))->where('pmf_no', $input['pmf_no'])->where('is_deleted', 0)->first();
        $response['actua_packet_wight'] = GmsPmfDtls::select(DB::raw('sum(pmf_actual_wt) as totalActualwt'))->where('pmf_no', $input['pmf_no'])->first();
        if (!$response) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Data Not Found');
        } else {
            return $response;
        }
    }

    public function ipmfPending(Request $request)
    {
        $input = $this->request->all();
        $query = GmsPmfDtls::select(
            'pmf_no as opmf',
            'pmf_origin as created_by',
            'pmf_time as time',
            'pmf_mode as mode_type',
            DB::raw('CONCAT("[",pmf_origin,",",pmf_dest,"]") As ManifestType'),
            DB::raw('concat(SUM(pmf_pcs) - sum(pmf_received_pcs),"/",SUM(pmf_pcs)) As Total_cnno'),
            DB::raw('DATE_FORMAT(pmf_date,"%d %b, %Y") as date'),
            DB::raw('SUM(pmf_wt) as total_weight'),
            DB::raw('SUM(pmf_vol_wt) as vol_weight'),
            DB::raw('SUM(pmf_pcs)As pcs'),
            DB::raw('SUM(pmf_amt) As Amt'),
        );
        $query->whereColumn('pmf_pcs', '<>', 'pmf_received_pcs');
        $query->groupBy('pmf_no');

        if ($request->isMethod('get')) {
            return $query->paginate($request->per_page);
        } else {
            $response = array();
            $response['ipmf'] = GmsPmfDtls::join('gms_city', 'gms_pmf_dtls.pmf_city', '=', 'gms_city.city_code')->select('gms_pmf_dtls.pmf_no as manifest_no', 'gms_pmf_dtls.pmf_origin as origin_branch', 'gms_pmf_dtls.pmf_dest as dest_branch', 'gms_pmf_dtls.pmf_mode as mode',
                DB::raw('concat(gms_pmf_dtls.pmf_date," ",gms_pmf_dtls.pmf_time )As date'),
                DB::raw('count(gms_pmf_dtls.pmf_wt) as packet_wt'), 'gms_pmf_dtls.pmf_type as manifest_type',
                DB::raw('concat(gms_city.city_name,"(",gms_city.city_code,")")As city')

            )->where('gms_pmf_dtls.pmf_no', $input['pmf_no'])->first();

            $response['Pending'] = GmsPmfDtls::whereColumn('pmf_pcs', '<>', 'pmf_received_pcs')->where('pmf_no', $input['pmf_no'])->select('id', 'pmf_cnno', 'pmf_cnno_type', 'pmf_wt', 'pmf_vol_wt', 'pmf_pcs', 'pmf_pin', 'pmf_doc', 'pmf_city', 'pmf_remarks', 'pmf_received_by', 'pmf_status', 'created_at', 'pmf_received_date')->get();
            $response['Completed'] = GmsPmfDtls::whereColumn('pmf_pcs', '=', 'pmf_received_pcs')->where('pmf_no', $input['pmf_no'])->select('id', 'pmf_cnno', 'pmf_cnno_type', 'pmf_wt', 'pmf_vol_wt', 'pmf_pcs', 'pmf_pin', 'pmf_doc', 'pmf_city', 'pmf_remarks', 'pmf_received_by', 'pmf_status', 'created_at', 'pmf_received_date')->get();
            $response['total'] = GmsPmfDtls::select(DB::raw('SUM(pmf_wt) as total_weight'), DB::raw('COUNT(pmf_pcs) as total_cnno'), DB::raw('SUM(pmf_pcs) as total_pcs'), DB::raw('SUM(pmf_vol_wt) as total_vol_amount'))->where('pmf_no', $input['pmf_no'])->where('is_deleted', 0)->first();
            $response['actua_packet_wight'] = GmsPmfDtls::select(DB::raw('sum(pmf_actual_wt) as totalActualwt'))->where('pmf_no', $input['pmf_no'])->first();
            return $response;
        }
    }

    public function viewAllCoMailList(Request $request)
    {
        $input = $this->request->all();
        $query = GmsCoMail::select(
            'gms_co_mail.book_mfno as booking_mf_no',
            'gms_co_mail.book_cnno as cnno',
            'gms_co_mail.book_br_code as origin_branch',
            'gms_co_mail.book_org as origin_origin',
            'gms_co_mail.book_dest as origin_desti',
            'gms_co_mail.book_doc as doc_type',
            DB::raw('SUM(gms_co_mail.book_weight) as total_weight'),
            DB::raw('SUM(gms_co_mail.book_pcs)As total_pcs'),
            DB::raw('SUM(gms_co_mail.book_billamt) As Amt'),
            DB::raw('DATE_FORMAT(gms_co_mail.book_mfdate,"%d %b, %Y") as booking_date'),
        );
        $query->groupBy('gms_co_mail.book_mfno');
        if ($request->isMethod('get')) {
            return $query->paginate($request->per_page);
        } else {
            $response = array();
            $response['customer'] = GmsCoMail::select('book_mfno as manifest_no', 'book_cust_code as customer', 'book_br_code as branch', 'book_mfdate as manifest_date')->where('book_mfno', $input['book_mfno'])->first();
            $response['details'] = GmsCoMail::select('book_cnno as cnno', 'book_weight as weight', 'book_vol_weight as vol_weight', 'book_pcs as pcs', 'book_pin as pincode', 'book_location as city', 'book_product_type as product_type', 'book_mode as mode_type', 'book_doc as doc_type', 'book_billamt as bill_amount', 'book_topay as bill_amount', 'book_topay as topay_value', 'book_cod as code_value', 'delivery_t_remarks')->where('book_mfno', $input['book_mfno'])->get();
            $response['total'] = GmsCoMail::select(DB::raw('SUM(book_weight) as total_weight'), DB::raw('COUNT(book_pcs) as total_cnno'), DB::raw('SUM(book_pcs) as total_pcs'), DB::raw('SUM(book_vol_weight) as total_vol_amount'), DB::raw('SUM(book_total_amount) as total_amount'))->where('book_mfno', $input['book_mfno'])->first();
            $response['booking_by'] = GmsCoMail::join('gms_emp', 'gms_emp.emp_code', '=', 'gms_co_mail.book_emp_code')->select('gms_emp.emp_name')->where('book_mfno', $input['book_mfno'])->first();
            return $response;
        }
    }

    public function addCoMail()
    {
        $adminSession = session()->get('session_token');
        $admin = Admin::where('id', $adminSession->admin_id)->first();
        $office = GmsOffice::where('office_code', $admin->office_code)->first();

        // $validator = Validator::make($this->request->all(), [
        //     'book_type' => 'required',
        //     'book_br_code' => 'required',
        //     'book_mfrefno' => 'required',
        //     'book_mfdate' => 'required',
        //     'book_mftime' => 'required',
        //     'book_cust_type' => 'required',
        //     'book_cust_code' => 'required',
        //     'book_vol_weight' => 'required'
        // ]);
        // if ($validator->fails()) {
        //     return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        // }
        $input = $this->request->all();
        $input['book_type'] = 'CO';
        $input['book_br_code'] = $admin->office_code;
        $addCoMail = new GmsCoMail($input);
        $addCoMail->save();

        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addCoMail);
    }

    public function viewAllOutMasterManifest(Request $request)
    {
        $input = $this->request->all();
        $query = GmsMfDtls::select(
            'gms_mf_dtls.mf_no as mf',
            'gms_mf_dtls.mf_emp_code as emp',
            'gms_mf_dtls.mf_mode as mode',
            'gms_mf_dtls.mf_time as time',
            DB::raw('count(mf_pmfno)As totalPacket'),
            DB::raw('concat("[",gms_mf_dtls.mf_origin,",",gms_mf_dtls.mf_dest,"]")As ManifestType'),
            DB::raw('DATE_FORMAT(gms_mf_dtls.mf_date,"%d %b, %Y") as date'),
            DB::raw('SUM(gms_mf_dtls.mf_wt) as total_weight'),
            DB::raw('SUM(gms_mf_dtls.mf_vol_wt) as vol_weight'),
            DB::raw('SUM(gms_mf_dtls.mf_pcs)As pcs'),

        );
        $query->where('gms_mf_dtls.is_deleted',0);
        $query->groupBy('gms_mf_dtls.mf_no');
        if ($request->isMethod('get')) {
            return $query->paginate($request->per_page);
        } else {
            $response['count'] = GmsMfDtls::select(
                DB::raw('SUM(gms_mf_dtls.mf_cnno) as total_cnno'),
                DB::raw('SUM(gms_mf_dtls.mf_wt) as total_weight'),
                DB::raw('SUM(gms_mf_dtls.mf_vol_wt) as total_vol_weight'),
                DB::raw('SUM(gms_mf_dtls.mf_actual_wt) as total_actual_vol'),
                DB::raw('SUM(gms_mf_dtls.mf_pcs)As pcs'))->where('mf_no', $input['mf_no'])->where('is_deleted', 0)->first();

            $response['mf_pmfDetails'] = GmsMfDtls::select('mf_pmf_dest','mf_pmfno', 'mf_remarks', 'mf_origin_type as incomed_by', 'mf_entry_date', 'mf_origin', 'mf_dest', 'mf_mode','mf_pcs', 'mf_srno','mf_wt', 'mf_actual_wt', 'mf_vol_wt')->where('mf_no', $input['mf_no'])->where('is_deleted', 0)->get();

         return $response;
        }
    }

    public function advanceSearchOutMaster(Request $request)
    {
        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $mf_ro = $this->request->mf_ro;
        $mf_origin = $this->request->mf_origin;
        $mf_mode = $this->request->mf_mode;
        $mf_no = $this->request->opmf_no;

        $advanceSearchImf = GmsMfDtls::select(
            'mf_no as mf',
            'mf_emp_code as emp',
            'mf_mode as mode',
            'mf_time as time',
            DB::raw('count(mf_pmfno)As totalPacket'),
            DB::raw('concat("[",mf_origin,",",mf_dest,"]")As ManifestType'),
            DB::raw('DATE_FORMAT(mf_date,"%d %b, %Y") as date'),
            DB::raw('SUM(mf_wt) as total_weight'),
            DB::raw('SUM(mf_vol_wt) as vol_weight'),
            DB::raw('SUM(mf_pcs)As pcs'),
        );
        $advanceSearchImf->groupBy('mf_no');

        if ($request->has('from_date') && $request->has('to_date')) {
            $advanceSearchImf->whereBetween('mf_date', [$from_date, $to_date]);
        }
        if ($request->has('mf_ro')) {
            $advanceSearchImf->Where('mf_ro', $mf_ro);
        }
        if ($request->has('mf_origin')) {
            $advanceSearchImf->Where('mf_origin', $mf_origin);
        }
        if ($request->has('mf_mode')) {
            $advanceSearchImf->where('mf_mode', $mf_mode);
        }
        if ($request->has('opmf_no')) {
            $advanceSearchImf->where('mf_no', $mf_no);
        }
        return $advanceSearchImf->paginate($request->per_page);

        // $query2[] = $advanceSearchImf->get()->toArray();
        // return $this->successResponse(self::CODE_OK, $query2);
    }

}
