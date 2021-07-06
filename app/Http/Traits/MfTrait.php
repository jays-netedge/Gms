<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\DB;
use App\Models\GmsDmfDtls;
use App\Models\GmsPmfDtls;
use Illuminate\Http\Request;

trait MfTrait
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function viewManifestNormal()
    {
        $input = $this->request->all();
        $query = GmsDmfDtls::select(

            DB::raw('CONCAT(gms_dmf_dtls.dmf_emp ,\' - \',gms_customer_franchisee.fran_cust_name) AS agent'),
            'gms_dmf_dtls.dmf_mfno as delv_mf_no',
            'gms_dmf_dtls.dmf_mfdate as delv_mf_date',
            'gms_dmf_dtls.dmf_mftime as delv_mf_time',
            /*'gms_dmf_dtls.dmf_fr_code as prepared_by',*/
            DB::raw('CONCAT(gms_dmf_dtls.dmf_fr_code ,\' - \',gms_customer.cust_la_ent) AS prepared_by'),
            //total-weight-count of mf//
            DB::raw('SUM(gms_dmf_dtls.dmf_wt) as total_weight'),
            DB::raw('COUNT(gms_dmf_dtls.dmf_cnno) as total_cnno'),
        );
        $query->Join('gms_customer_franchisee', 'gms_customer_franchisee.cust_code', '=', 'gms_dmf_dtls.dmf_fr_code');
        $query->Join('gms_customer', 'gms_customer.cust_code', '=', 'gms_customer_franchisee.cust_code');
        $query->where('gms_dmf_dtls.is_deleted', 0);
        $query->where('gms_dmf_dtls.dmf_mfno', $input['dmf_mfno']);
        $query->groupBy('gms_dmf_dtls.dmf_mfno');
        $result['fr_cust_details'] = $query->first();
        //cnno-details query//
        $mfDetails = GmsDmfDtls::Join('gms_city', 'gms_city.city_code', '=', 'gms_dmf_dtls.dmf_dest')->where('gms_dmf_dtls.dmf_mfno', $input['dmf_mfno'])->select('gms_dmf_dtls.dmf_cnno as cnno_no', 'gms_dmf_dtls.dmf_wt as weight', 'gms_dmf_dtls.dmf_pcs as no_of_pcs', 'gms_dmf_dtls.dmf_pin as pincode', 'gms_city.city_name as destination', 'gms_dmf_dtls.dl_chash_amt as topay', 'gms_dmf_dtls.dmf_remarks as remark');
        $cnno_details = $mfDetails->get();
        $result['cnno_details'] = $cnno_details;
        return $result;
    }

    public function viewManifestDetailsPrint()
    {
        $input = $this->request->all();
        $query = GmsDmfDtls::select(

            DB::raw('CONCAT(gms_dmf_dtls.dmf_emp ,\' - \',gms_customer_franchisee.fran_cust_name) AS agent'),
            'gms_dmf_dtls.dmf_mfno as delv_mf_no',
            'gms_dmf_dtls.dmf_mfdate as delv_mf_date',
            'gms_dmf_dtls.dmf_mftime as delv_mf_time',
            /*'gms_dmf_dtls.dmf_fr_code as prepared_by',*/
            DB::raw('CONCAT(gms_dmf_dtls.dmf_fr_code ,\' - \',gms_customer.cust_la_ent) AS prepared_by')

        );
        $query->Join('gms_customer_franchisee', 'gms_customer_franchisee.cust_code', '=', 'gms_dmf_dtls.dmf_fr_code');
        $query->Join('gms_customer', 'gms_customer.cust_code', '=', 'gms_customer_franchisee.cust_code');
        $query->where('gms_dmf_dtls.is_deleted', 0);
        $query->where('gms_dmf_dtls.dmf_mfno', $input['dmf_mfno']);
        $query->groupBy('gms_dmf_dtls.dmf_mfno');
        $result['fr_cust_details'] = $query->first();
        //cnno-details query//
        $mfDetails = GmsDmfDtls::where('dmf_mfno', $input['dmf_mfno'])->select('dmf_cnno as cnno_no', 'dmf_wt as weight', 'dmf_pcs as no_of_pcs', 'dmf_pin as pincode', 'dmf_consgn_add as consignee_name_address', 'dmf_dest as destination', 'dl_chash_amt as topay', 'dmf_remarks as remark', 'dl_signature as signature', DB::raw('CONCAT(gms_dmf_dtls.dl_name , \' \',  gms_dmf_dtls.dl_phone) AS received_name_and_phone_no'), 'dmf_cn_status as status');
        $mfDetails->where('is_deleted', 0);
        $cnno_details = $mfDetails->get();
        $result['cnno_details'] = $cnno_details;
        $total = GmsDmfDtls::select(DB::raw('SUM(dmf_wt) as total_weight'), DB::raw('count(dmf_cnno) as total_cnno'))->where('dmf_mfno', $input['dmf_mfno'])->where('is_deleted', 0)->first();
        $result['total'] = $total;
        return $result;
    }
}

