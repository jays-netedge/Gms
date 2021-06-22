<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\GmsBookingDtls;
use App\Models\GmsColoaderDtls;
use App\Models\GmsDmfDtls;
use App\Models\GmsEmp;
use App\Models\GmsMfDtls;
use App\Models\GmsPmfDtls;
use App\Models\GmsZone;
use App\Models\GmsState;
use App\Models\GmsCity;
use App\Models\GmsOffice;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Exports\BookingReportExport;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class ReportController
 * @package App\Http\Controllers\V1
 */
class ReportController extends Controller
{

    /**
     * @var Request
     */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function bookingReportExport()
    {
        return Excel::store(new bookingReportExport, 'booking.xlsx');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function bookingReport(Request $request)
    {
        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $book_product_type = $this->request->book_product_type;
        $book_doc = $this->request->book_doc;
        $book_mode = $this->request->book_mode;
        $book_service_type = $this->request->book_service_type;
        $book_cust_type = $this->request->book_cust_type;
        $book_cust_code = $this->request->book_cust_code;
        $book_cnno = $this->request->book_cnno;
        $book_pin = $this->request->book_pin;


        // $query = GmsBookingDtls::
        // leftJoin('gms_customer as book_customer', 'book_customer.cust_code', '=', 'gms_booking_dtls.book_cust_code')
        //     ->leftJoin('gms_customer as book_fr_customer', 'book_fr_customer.cust_code', '=', 'gms_booking_dtls.book_fr_cust_code')
        //     ->leftJoin('gms_city', 'gms_city.city_code', '=', 'gms_booking_dtls.book_org')
        //     ->select(
        //         'gms_booking_dtls.id',
        //         'gms_booking_dtls.book_br_code',
        //         'gms_booking_dtls.book_emp_code',
        //         'gms_booking_dtls.book_cust_type',
        //         'gms_booking_dtls.book_cust_code',
        //         'book_customer.cust_name',
        //         'gms_booking_dtls.book_fr_cust_code',
        //         'book_fr_customer.cust_name',
        //         'gms_booking_dtls.book_mfno',
        //         'gms_booking_dtls.book_mfrefno',
        //         'gms_booking_dtls.book_mfdate',
        //         'gms_booking_dtls.book_mftime',
        //         'gms_booking_dtls.book_refno',
        //         'gms_booking_dtls.book_pin',
        //         'gms_booking_dtls.book_org',
        //         'gms_booking_dtls.book_dest',
        //         'gms_booking_dtls.book_cons_addr',
        //         'gms_booking_dtls.book_cn_dtl',
        //         'gms_booking_dtls.book_product_type',
        //         'gms_booking_dtls.book_mode',
        //         'gms_booking_dtls.book_doc',
        //         'gms_booking_dtls.book_weight',
        //         'gms_booking_dtls.book_vol_weight',
        //         DB::raw("CONCAT('gms_booking_dtls.book_vol_lenght,book_vol_height,book_vol_breight') AS book_vol_weight_LBH"),
        //         // 'gms_booking_dtls.book_vol_lenght',
        //         // 'gms_booking_dtls.book_vol_height',
        //         // 'gms_booking_dtls.book_vol_breight',
        //         'gms_booking_dtls.book_pcs',
        //         'gms_booking_dtls.book_remarks',
        //         'gms_booking_dtls.book_service_type',
        //         'gms_booking_dtls.book_current_status',
        //         'gms_booking_dtls.book_pod_scan',
        //         'gms_booking_dtls.book_billamt',
        //         'gms_booking_dtls.book_total_amount'
        //     );

        $query2 = GmsBookingDtls::join('gms_customer', 'gms_customer.cust_code', '=', 'gms_booking_dtls.book_cust_code')->join('gms_city', 'gms_city.city_code', '=', 'gms_booking_dtls.book_org')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('concat(COUNT(CASE WHEN gms_booking_dtls.delivery_status <> 0 THEN 1 END)) As notDelivered'),
            DB::raw('concat(COUNT(CASE WHEN gms_booking_dtls.delivery_status <> 1 THEN 0 END)) As delivered'),

        );

        $count['totalcnnno'] = 0;
        $count['notDelivered'] = 0;
        $count['delivered'] = 0;

        if (isset($from_date) || isset($to_date) || isset($book_product_type) || isset($book_doc) || isset($book_mode) || isset($book_service_type) || isset($book_cust_type) || isset($book_cust_code) || isset($book_cnno) || isset($book_pin)) {
            if ($from_date && $to_date) {

                // $query->whereBetween('gms_booking_dtls.book_mfdate', [$from_date, $to_date]);
                $query2->whereBetween('gms_booking_dtls.book_mfdate', [$from_date, $to_date]);
            }
            if (isset($book_product_type)) {
                //   $query->where('gms_booking_dtls.book_product_type', $book_product_type);
                $query2->where('gms_booking_dtls.book_product_type', $book_product_type);
            }
            if (isset($book_doc)) {
                // $query->where('gms_booking_dtls.book_doc', $book_doc);
                $query2->where('gms_booking_dtls.book_doc', $book_doc);

            }
            if (isset($book_mode)) {
                // $query->where('gms_booking_dtls.book_mode', $book_mode);
                $query2->where('gms_booking_dtls.book_mode', $book_mode);
            }
            if (isset($book_service_type)) {
                // $query->where('gms_booking_dtls.book_service_type', $book_service_type);
                $query2->where('gms_booking_dtls.book_service_type', $book_service_type);
            }
            if (isset($book_cust_type)) {
                // $query->where('gms_booking_dtls.book_cust_type', $book_cust_type);
                $query2->where('gms_booking_dtls.book_cust_type', $book_cust_type);
            }
            if (isset($book_cust_code)) {
                //  $query->where('gms_booking_dtls.book_cust_code', $book_cust_code);
                $query2->where('gms_booking_dtls.book_cust_code', $book_cust_code);
            }
            if (isset($book_cnno)) {
                //  $query->where('gms_booking_dtls.book_cnno', $book_cnno);
                $query2->where('gms_booking_dtls.book_cnno', $book_cnno);
            }
            if (isset($book_pin)) {
                //  $query->where('gms_booking_dtls.book_pin', $book_pin);
                $query2->where('gms_booking_dtls.book_pin', $book_pin);
            }

            $query2->orderBy('book_mfdate', 'DESC');
        }
        //  $response['Status']['report'] = $query->get();
        $response['Status']['count'] = $query2->get();
        return $response;

    }

    public function bookingStats()
    {
        //BLRRO
        $response['bllro'] = GmsBookingDtls::where('book_br_code', 'BLRRO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //CHERO
        $response['chero'] = GmsBookingDtls::where('book_br_code', 'CHERO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //HYDRO
        $response['hydro'] = GmsBookingDtls::where('book_br_code', 'HYDRO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //DELRO
        $response['delro'] = GmsBookingDtls::where('book_br_code', 'DELRO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //AMDRO
        $response['amdro'] = GmsBookingDtls::where('book_br_code', 'AMDRO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //CCURO
        $response['ccuro'] = GmsBookingDtls::where('book_br_code', 'CCURO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //NGPRO
        $response['ngpro'] = GmsBookingDtls::where('book_br_code', 'NGPRO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //MUMRO
        $response['mumro'] = GmsBookingDtls::where('book_br_code', 'MUMRO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //PNQRO
        $response['pnqro'] = GmsBookingDtls::where('book_br_code', 'PNQRO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //COKRO
        $response['cokro'] = GmsBookingDtls::where('book_br_code', 'COKRO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //RAIRO
        $response['rairo'] = GmsBookingDtls::where('book_br_code', 'RAIRO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //JAIRO
        $response['jairo'] = GmsBookingDtls::where('book_br_code', 'JAIRO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //GAURO
        $response['gauro'] = GmsBookingDtls::where('book_br_code', 'GAURO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //VJARO
        $response['vjaro'] = GmsBookingDtls::where('book_br_code', 'VJARO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //DENRO
        $response['denro'] = GmsBookingDtls::where('book_br_code', 'DENRO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //KOLRO
        $response['kolro'] = GmsBookingDtls::where('book_br_code', 'KOLRO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //BOMRO
        $response['bomro'] = GmsBookingDtls::where('book_br_code', 'BOMRO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //PNERO
        $response['pnero'] = GmsBookingDtls::where('book_br_code', 'PNERO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //PATRO
        $response['patro'] = GmsBookingDtls::where('book_br_code', 'PATRO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //BBSRO
        $response['bbsro'] = GmsBookingDtls::where('book_br_code', 'BBSRO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        //BHPRO
        $response['bhpro'] = GmsBookingDtls::where('book_br_code', 'BHPRO')->select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('round((count(book_cnno)/(select count(*) from gms_booking_dtls)*100),2) as cnno_percentage'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('round(((book_weight)/(SUM(book_weight))*100),2) as wt_percentage'),
            DB::raw('count(book_billamt) as totalamt'),
            DB::raw('round(((book_billamt)/(SUM(book_billamt))*100),2) as amt_percentage'),
        )->get();

        return $response;
        // $percent = $wl / $total * 100;
    }

    public function boWiseBookingReport(Request $request)
    {
        $adminSession = session()->get('session_token');
        $admin = Admin::where('id', $adminSession->admin_id)->where('is_deleted', 0)->first();
        $office = GmsOffice::where('office_code', $admin->office_code)->where('is_deleted', 0)->first();
        $input = $this->request->all();
        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $bo_sf = $this->request->bo_sf;

        $boWiseBookingReport = GmsBookingDtls::select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('count(book_weight) as totalweight'),
            DB::raw('count(book_billamt) as totalamt'),
        );
        ///  $boWiseBookingReport->groupBy('book_br_code');

        if ($request->has('from_date') && $request->has('to_date')) {
            $boWiseBookingReport->whereBetween('book_mfdate', [$from_date, $to_date]);
        }
        if ($request->has('bo_sf')) {
            $boWiseBookingReport->where('book_br_code', $bo_sf);
        }

        $boWiseBookingReport->where('is_deleted', 0);
        return $boWiseBookingReport->paginate($request->per_page);

    }

    public function bookingAnalyticRep(Request $request)
    {   
         $from_date = $this->request->from_date;
         $to_date = $this->request->to_date;
         $branch_type = $this->request->branch_type;
         $branch_name = $this->request->branch_name;
         $booking_type = $this->request->booking_type;
         $customer = $this->request->customer;


         $bookingAnalyticRep = GmsBookingDtls::leftjoin('gms_customer','gms_booking_dtls.book_cust_code','=','gms_customer.cust_code')->select(
            'book_cust_type',
            DB::raw('concat("[",gms_booking_dtls.book_cust_code,",",gms_customer.cust_name,"]")As Customers'),
            DB::raw('count(book_cnno) as totalCnno'),
            DB::raw('count(book_weight) as totalWeight'),
            DB::raw('count(book_vol_weight) as totalVolWeight'),
            DB::raw('count(book_pcs) as totalPcs'),
            DB::raw('count(book_billamt) as totalAmt'),
            'book_mfdate'
           )->get();

        if ($request->has('from_date') && $request->has('to_date')) {
            $bookingAnalyticRep->whereBetween('book_mfdate', [$from_date, $to_date]);
        }
        if ($request->has('branch_name')) {
            $bookingAnalyticRep->where('book_br_code', $branch_name);
        }
        if ($request->has('customer')) {
            $bookingAnalyticRep->where('book_cust_code', $customer);
        }
        if ($request->has('booking_type')) {
            $bookingAnalyticRep->where('book_cust_type', $booking_type);
        }
        
        $bookingAnalyticRep->group_by('book_cust_code');
        $bookingAnalyticRep->where('is_deleted', 0);
        return $bookingAnalyticRep->paginate($request->per_page);
    }

    public function deliveryAnalyticRep()
    {
         $from_date = $this->request->from_date;
         $to_date = $this->request->to_date;
         $branch_type = $this->request->branch_type;
         $branch_name = $this->request->branch_name;
         $booking_type = $this->request->booking_type;
         $customer = $this->request->customer;


         $bookingAnalyticRep = GmsDmfDtls::leftjoin('gms_customer','gms_dmf_dtls.dmf_fr_code','=','gms_customer.cust_code')->select(
            'dmf_type',
            DB::raw('concat("[",gms_dmf_dtls.dmf_fr_code,",",gms_customer.cust_name,"]")As Customers'),
            DB::raw('count(dmf_mfno) as totalMf'),
            DB::raw('count(dmf_cnno) as totalCnno'),
            DB::raw('sum(dmf_pcs) as updateCnno'),
            DB::raw('concat(sum(dmf_cnno)- sum(dmf_pcs)) As Notupdated'),
            DB::raw('count(dmf_pod_status) as podsupdated'),
            DB::raw('count(dmf_cn_status) as Delivery')
        );

        if ($request->has('from_date') && $request->has('to_date')) {
            $bookingAnalyticRep->whereBetween('book_mfdate', [$from_date, $to_date]);
        }
        if ($request->has('branch_name')) {
            $bookingAnalyticRep->where('book_br_code', $branch_name);
        }
        if ($request->has('customer')) {
            $bookingAnalyticRep->where('book_cust_code', $customer);
        }
        if ($request->has('booking_type')) {
            $bookingAnalyticRep->where('book_cust_type', $booking_type);
        }
        
        $bookingAnalyticRep->group_by('dmf_fr_code');
        $bookingAnalyticRep->where('is_deleted', 0);
        return $bookingAnalyticRep->paginate($request->per_page);
    }

    public function codTopayReport(Request $request)
    {
        $adminSession = session()->get('session_token');

        /*$admin = Admin::where('id', $adminSession->admin_id)->first();
        $office = GmsOffice::where('id', $admin->office_id)->first();*/

        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $serv_type = $this->request->serv_type;
        $out = $this->request->out;
        $in = $this->request->in;
        $cust_type = $this->request->cust_type;
        $cust_code = $this->request->cust_code;

        $dataSearch = GmsBookingDtls::join('gms_customer', 'gms_customer.cust_code', '=', 'gms_booking_dtls.book_cust_code')
            ->join('gms_city as org', 'org.city_code', '=', 'gms_booking_dtls.book_org')
            ->join('gms_office as org_ro', 'org_ro.office_city', '=', 'gms_booking_dtls.book_org')
            ->join('gms_city as dest', 'dest.city_code', '=', 'gms_booking_dtls.book_dest')
            ->join('gms_office as dest_ro', 'dest_ro.office_city', '=', 'gms_booking_dtls.book_dest')
            /*->join('gms_dmf_dtls', 'gms_dmf_dtls.dmf_cnno', '=', 'gms_booking_dtls.book_cnno')*/
            ->join('gms_emp', 'gms_emp.emp_code', '=', 'gms_booking_dtls.book_emp_code')
            ->select(
                'gms_booking_dtls.book_cnno',
                DB::raw('DATE_FORMAT(gms_booking_dtls.book_mfdate, "%d %b, %Y") as date'),
                'gms_booking_dtls.book_br_code',
                'gms_booking_dtls.book_emp_code',
                'gms_booking_dtls.book_cust_code',
                'gms_customer.cust_la_ent',
                'gms_booking_dtls.book_pin',
                'org.city_name as book_org',
                'org_ro.office_code as origin_ro',
                'dest.city_name as book_dest',
                'dest_ro.office_code as dest_ro',
                DB::raw('COUNT(gms_booking_dtls.book_topay_inv) as book_topay_inv'),
                DB::raw('COUNT(gms_booking_dtls.book_cod) as book_cod_inv'),
                'gms_emp.emp_name as dlv_agent',

            /*DB::raw('CONCAT(CASE WHEN gms_dmf_dtls.dmf_cn_status = D THEN "DELIVERED" END) AS total_recevied'),*/

            /*DB::raw('CONCAT(gms_dmf_dtls.dmf_cn_status,"(",gms_dmf_dtls.dmf_pod_status,")") as book_curr_status')*/
            );

        if ($request->has('from_date') && $request->has('to_date')) {
            $dataSearch->whereBetween('gms_booking_dtls.book_mfdate', [$from_date, $to_date]);
            $dataSearch->where('gms_booking_dtls.is_deleted', 0);
            $query = $dataSearch->get();
            return $query;
        }
        /*if ($request->has('from_date') && $request->has('to_date')) {
            $dataSearch->whereBetween('gms_booking_dtls.book_mfdate', [$from_date, $to_date]);
        }
        return $dataSearch->get($request->per_page);*/
        /*if ($request->has('cust_type') && $request->has('cust_code')) {

               $dataSearch->where('gms_booking_dtls.book_cust_type', $cust_type);
               $dataSearch->where('gms_booking_dtls.book_cust_code', $cust_code);
               $dataSearch->where('is_deleted', 0);
               return $dataSearch;
        }*/

        /*if ($request->has('dmf_mfno')) {
            $dataSearch->where('dmf_mfno', $dmf_mfno);
        }

        $dataSearch->groupBy('dmf_mfno');


        return $dataSearch->paginate($request->per_page);*/

    }

    public function outGoingReport(Request $request)
    {
        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $group_by = $this->request->group_by;

        if ($request->type == 'opmf') {
            if (isset($from_date) || isset($to_date)) {
                if (isset($group_by)) {
                    $query2 = GmsPmfDtls::select(
                        DB::raw('count(pmf_cnno)as totalCnno'),
                        DB::raw('sum(pmf_wt) As totalWight'),
                    );
                    $response['Status'] = $query2->get();
                } else {
                    $reportQuery = GmsPmfDtls::select(
                        'gms_pmf_dtls.pmf_no',
                        DB::raw('concat("[",gms_pmf_dtls.pmf_date,",",gms_pmf_dtls.pmf_time,"]") As dateTime'),
                        'gms_pmf_dtls.pmf_dest',
                        'gms_pmf_dtls.pmf_mode',
                        'gms_pmf_dtls.pmf_cnno',
                        'gms_pmf_dtls.pmf_city',
                        'gms_pmf_dtls.pmf_wt',
                        'gms_pmf_dtls.pmf_pin',
                        'gms_pmf_dtls.pmf_doc'
                    );
                    $query = GmsPmfDtls::select(
                        DB::raw('count(DISTINCT pmf_no)as totalOPmf'),
                        DB::raw('count(pmf_cnno)as totalCnno'),
                        DB::raw('sum(pmf_wt) As totalWight'),
                    );
                    if ($from_date && $to_date) {
                        $query->whereBetween('gms_pmf_dtls.pmf_date', [$from_date, $to_date]);
                        $reportQuery->whereBetween('gms_pmf_dtls.pmf_date', [$from_date, $to_date]);
                    }
                    $response['Status']['count'] = $query->get();
                    $response['Status']['report'] = $reportQuery->get();
                    return $response;
                }
            } else {
                $response['Status']['count'] = ['totalOpmf' => 0, 'totalCnno' => 0, 'totalWight' => 0];
                $response['Status']['report'] = "No Data Found";
            }

        } elseif ($request->type == 'omf') {
            if (isset($from_date) || isset($to_date)) {
                $opm = GmsMfDtls::select('mf_no', 'mf_date', 'mf_mode', 'mf_wt');
                if ($from_date && $to_date) {
                    $opm->whereBetween('gms_mf_dtls.mf_date', [$from_date, $to_date]);
                    $response['Status'] = $opm->get();
                    return $response;
                } else {
                    return "no data found";
                }
            }
        }

    }


    public function inComingReport(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $group_by = $request->group_by;

        if (isset($from_date) || isset($to_date)) {
            if (isset($group_by)) {
                $query2 = GmsPmfDtls::select(
                    DB::raw('count(pmf_cnno)as totalCnno'),
                    DB::raw('sum(pmf_wt) As totalWight'),
                );
                $response['Status'] = $query2->get();
            } else {
                $reportQuery = GmsPmfDtls::select(
                    'gms_pmf_dtls.pmf_no',
                    DB::raw('concat("[",gms_pmf_dtls.pmf_date,",",gms_pmf_dtls.pmf_time,"]") As dateTime'),
                    'gms_pmf_dtls.pmf_dest',
                    'gms_pmf_dtls.pmf_mode',
                    'gms_pmf_dtls.pmf_cnno',
                    'gms_pmf_dtls.pmf_city',
                    'gms_pmf_dtls.pmf_wt',
                    'gms_pmf_dtls.pmf_actual_wt',
                    'gms_pmf_dtls.pmf_actual_received_wt',
                    'gms_pmf_dtls.pmf_pin',
                    'gms_pmf_dtls.pmf_cd_no',
                    'gms_pmf_dtls.pmf_status',
                    'gms_pmf_dtls.pmf_doc',

                );
                $query = GmsPmfDtls::select(
                    DB::raw('count(DISTINCT pmf_no)as totalIPmf'),
                    DB::raw('count(pmf_cnno)as totalCnno'),
                    DB::raw('sum(pmf_wt) As totalWight'),
                );
                if ($from_date && $to_date) {
                    $query->whereBetween('gms_pmf_dtls.pmf_date', [$from_date, $to_date]);
                    $reportQuery->whereBetween('gms_pmf_dtls.pmf_date', [$from_date, $to_date]);
                }
                $response['Status']['count'] = $query->get();
                $response['Status']['report'] = $reportQuery->get();
            }
        } else {
            $response['Status']['count'] = ['totalIPmf' => 0, 'totalCnno' => 0, 'totalWight' => 0];
            $response['Status']['report'] = "No Data Found";
        }
        return $response;
    }

    public function drsReport(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;

        if (isset($from_date) || isset($to_date)) {
            if (isset($group_by)) {

                $reportQuery2 = GmsDmfDtls::join('gms_emp', 'gms_dmf_dtls.dmf_emp', '=', 'gms_emp.emp_code')->join('gms_city', 'gms_dmf_dtls.dmf_dest', '=', 'gms_city.city_code')->select(
                    'dmf_mfno',
                    DB::raw('concat("[",gms_dmf_dtls.dmf_date,",",gms_dmf_dtls.dmf_time,"]") As dateTime'),
                    'gms_dmf_dtls.dmf_emp',
                    'gms_emp.emp_name',
                    'gms_dmf_dtls.dmf_branch',
                    'gms_dmf_dtls.dmf_cnno_current_status',
                    'gms_dmf_dtls.dmf_cnno',
                    'gms_dmf_dtls.dmf_pin',
                    'gms_city.city_name',
                    'gms_dmf_dtls.dmf_wt',
                    'gms_dmf_dtls.dmf_delv_amt',
                    'gms_dmf_dtls.dmf_atmpt_date',
                    'gms_dmf_dtls.dmf_actual_date',
                    'gms_dmf_dtls.dmf_ndel_reason',
                    'gms_dmf_dtls.dmf_remarks',
                    'gms_dmf_dtls.dmf_delv_remarks',
                    'gms_dmf_dtls.dmf_pod_status'

                );
                $query2 = GmsDmfDtls::select(
                    DB::raw('count(DISTINCT dmf_mfno)as totalDrs'),
                    DB::raw("IFNULL((SELECT COUNT(dmf_cn_status) WHERE dmf_cn_status ='D'), 0)  as updated"),
                    /*DB::raw("(SELECT COUNT(dmf_cn_status) WHERE dmf_cn_status ='D') as updated"),*/
                    DB::raw("IFNULL((SELECT COUNT(dmf_cn_status)  WHERE dmf_cn_status ='N'), 0)  as Notupdated"),
                    /*DB::raw("(SELECT COUNT(dmf_cn_status)  WHERE dmf_cn_status ='N') as Notupdated"),*/
                    DB::raw("IFNULL((SELECT COUNT(dmf_pod_status) WHERE dmf_pod_status = '0' ), 0)  as podsupdated"),
                /*DB::raw("(SELECT COUNT(dmf_pod_status) WHERE dmf_pod_status = '0' ) as podsupdated"),*/
                );
                $response['Status']['count'] = $query2->get();
                $response['Status']['report'] = $reportQuery2->get();
            } else {

                $reportQuery = GmsDmfDtls::join('gms_emp', 'gms_dmf_dtls.dmf_emp', '=', 'gms_emp.emp_code')->join('gms_city', 'gms_dmf_dtls.dmf_dest', '=', 'gms_city.city_code')->select(
                    'dmf_mfno',
                    DB::raw('concat("[",gms_dmf_dtls.dmf_mfdate,",",gms_dmf_dtls.dmf_mftime,"]") As dateTime'),
                    'gms_dmf_dtls.dmf_emp',
                    'gms_emp.emp_name',
                    'gms_dmf_dtls.dmf_branch',
                    'gms_dmf_dtls.dmf_cnno_current_status',
                    'gms_dmf_dtls.dmf_cnno',
                    'gms_dmf_dtls.dmf_pin',
                    'gms_city.city_name',
                    'gms_dmf_dtls.dmf_wt',
                    'gms_dmf_dtls.dmf_delv_amt',
                    'gms_dmf_dtls.dmf_atmpt_date',
                    'gms_dmf_dtls.dmf_actual_date',
                    'gms_dmf_dtls.dmf_ndel_reason',
                    'gms_dmf_dtls.dmf_remarks',
                    'gms_dmf_dtls.dmf_delv_remarks',
                    'gms_dmf_dtls.dmf_pod_status'
                );
                $query = GmsDmfDtls::select(
                    DB::raw('count(DISTINCT dmf_mfno)as totalDrs'),
                    DB::raw("IFNULL((SELECT COUNT(dmf_cn_status) WHERE dmf_cn_status ='D'), 0)  as updated"),
                    /*DB::raw("(SELECT COUNT(dmf_cn_status) WHERE dmf_cn_status ='D') as updated"),*/
                    DB::raw("IFNULL((SELECT COUNT(dmf_cn_status)  WHERE dmf_cn_status ='N'), 0)  as Notupdated"),
                    /*DB::raw("COUNT(CASE WHEN (SELECT COUNT(dmf_cn_status)  WHERE dmf_cn_status = 'N') <> null THEN 0 END) AS total_recevied"),*/
                    DB::raw("IFNULL((SELECT COUNT(dmf_pod_status) WHERE dmf_pod_status = '0' ), 0)  as podsupdated"),
                /*DB::raw("(SELECT COUNT(dmf_pod_status) WHERE dmf_pod_status = '0' ) as podsupdated"),*/

                );
                if ($from_date && $to_date) {
                    $query->whereBetween('gms_dmf_dtls.dmf_mfdate', [$from_date, $to_date]);

                }
                $response['Status']['count'] = $query->get();
                $response['Status']['report'] = $reportQuery->get();
            }
        } else {
            $response['Status'] = "Data Not Found";
        }
        return $response;
    }

    public function drsBoWiseReports(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $office_type = $request->office_type;
        $branch_type = $request->branch_type;

         $query = GmsDmfDtls::select(
                    DB::raw('count(dmf_mfno)as totalDmf'),
                    DB::raw('count(DISTINCT dmf_cnno)as totalCnno'),
                    DB::raw('count(DISTINCT dmf_wt)as totalWight'),
                    DB::raw("IFNULL((SELECT COUNT(dmf_cn_status) WHERE dmf_cn_status ='D'), 0)  as updated"),
                    /*DB::raw("(SELECT COUNT(dmf_cn_status) WHERE dmf_cn_status ='D') as updated"),*/
                    DB::raw("IFNULL((SELECT COUNT(dmf_cn_status)  WHERE dmf_cn_status ='N'), 0)  as Notupdated"),
                    /*DB::raw("(SELECT COUNT(dmf_cn_status)  WHERE dmf_cn_status ='N') as Notupdated"),*/
                    DB::raw("IFNULL((SELECT COUNT(dmf_pod_status) WHERE dmf_pod_status = '0' ), 0)  as podsupdated"),
                /*DB::raw("(SELECT COUNT(dmf_pod_status) WHERE dmf_pod_status = '0' ) as podsupdated"),*/
                );

            if ($from_date && $to_date) {
                    $query->whereBetween('gms_dmf_dtls.dmf_mfdate', [$from_date, $to_date]);
                }
            if ($office_type) {
                $query->whereBetween('gms_dmf_dtls.dmf_branch', $office_type);
            }

            $query->groupBy('gms_dmf_dtls.dmf_branch');
            $response['Status'] = $query->get();
    }

    public function empReport(Request $request)
    {
        $emp_type = $this->request->emp_type;
        $emp_work_type = $this->request->emp_work_type;
        $emp_rep_offtype = $this->request->emp_rep_offtype;
        $emp_rep_office = $this->request->emp_rep_office;
        $emp_code = $this->request->emp_code;
        $emp_name = $this->request->emp_name;

        $query = GmsEmp::select(
            'emp_code',
            'emp_name',
            'emp_add1',
            'emp_email',
        );
        if ($request->has('emp_type') || $request->has('emp_work_type') || $request->has('emp_rep_offtype') || $request->has('emp_rep_office') || $request->has('emp_code') || $request->has('emp_name')) {

            if ($request->has('emp_type') && isset($emp_type)) {
                $query->where('emp_type', $emp_type);
            }
            if ($request->has('emp_work_type') && isset($emp_work_type)) {
                $query->where('emp_work_type', $emp_work_type);
            }
            if ($request->has('emp_rep_offtype') && isset($emp_rep_offtype)) {
                $query->where('emp_rep_offtype', $emp_rep_offtype);
            }
            if ($request->has('emp_rep_office') && isset($emp_rep_office)) {
                $query->where('emp_rep_office', $emp_rep_office);
            }
            if ($request->has('emp_code') && isset($emp_code)) {
                $query->where('emp_code', $emp_code);
            }
            if ($request->has('emp_name') && isset($emp_name)) {
                $query->where('emp_name', $emp_name);
            }
            $response = $query->get();
        }
        if (isset($response) && !empty($response)) {
            return $response;
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Employee Not Found");
        }

    }


    public function coloaderReport(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $colo_mode = $request->colo_mode;
        $coloders = $request->coloders;
        $group_by = $request->group_by;
        $cn_no = $request->cn_no;
        $mf_no = $request->mf_no;

        if (isset($from_date) || isset($to_date)) {
            if (isset($group_by)) {
                $coloders2 = GmsColoaderDtls::select('coloader_code', 'cd_no', 'coloader_date', 'coloader_name', 'coloader_mode', 'coloader_dest_bo', 'coloader_dest_city');

                $response['Status'] = $coloders2->get();
            } else {
                $coloders = GmsColoaderDtls::select('coloader_code', 'cd_no', 'coloader_date', 'coloader_name', 'coloader_mode', 'coloader_dest_bo', 'coloader_dest_city');

                if ($from_date && $to_date) {
                    $coloders->whereBetween('gms_coloader_dtls.coloader_date', [$from_date, $to_date]);
                }
                $response['Status'] = $coloders->get();
            }
        } else {
            $response['Status'] = 'No Data Found';
        }
        return $response;
    }


    public function drsNoInfo(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $response = array();
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->first();

        if (isset($from_date) || isset($to_date)) {
            $response['branchDelivery'] = GmsDmfDtls::where('dmf_branch', $admin->office_code)->where('dmf_type', 'BD')->select(
                'dmf_emp',
                DB::raw('count(dmf_cnno)as totalCnno'),
                DB::raw("IFNULL((SELECT COUNT(dmf_cn_status) WHERE dmf_cn_status ='D'), 0)  as updated"),
                DB::raw("IFNULL((SELECT COUNT(dmf_cn_status)  WHERE dmf_cn_status ='N'), 0)  as Notupdated"),
                DB::raw("IFNULL((SELECT COUNT(dmf_pod_status) WHERE dmf_pod_status = '0' ), 0)  as podsupdated"),

            )->get();
            $response['deliveryAgent'] = GmsDmfDtls::where('dmf_branch', $admin->office_code)->where('dmf_type', 'DA')->select(
                'dmf_emp',
                DB::raw('count(dmf_cnno)as totalCnno'),
                DB::raw("IFNULL((SELECT COUNT(dmf_cn_status) WHERE dmf_cn_status ='D'), 0)  as updated"),
                DB::raw("IFNULL((SELECT COUNT(dmf_cn_status)  WHERE dmf_cn_status ='N'), 0)  as Notupdated"),
                DB::raw("IFNULL((SELECT COUNT(dmf_pod_status) WHERE dmf_pod_status = '0' ), 0)  as podsupdated"),

            )->get();
            $response['cityFra'] = GmsDmfDtls::where('dmf_branch', $admin->office_code)->where('dmf_type', 'CF')->select(
                'dmf_emp',
                DB::raw('count(dmf_cnno)as totalCnno'),
                DB::raw("IFNULL((SELECT COUNT(dmf_cn_status) WHERE dmf_cn_status ='D'), 0)  as updated"),
                DB::raw("IFNULL((SELECT COUNT(dmf_cn_status)  WHERE dmf_cn_status ='N'), 0)  as Notupdated"),
                DB::raw("IFNULL((SELECT COUNT(dmf_pod_status) WHERE dmf_pod_status = '0' ), 0)  as podsupdated"),

            )->get();
            return $response;
        } else {
            return "Data Not Found";
        }
    }

    public function dmfCustomerWise(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $office_type = $request->office_type;
        $branch_type = $request->branch_type;
        $delivery_type = $request->delivery_type;

        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();

        $query = GmsDmfDtls::where('dmf_branch', $admin->office_code)->select(
            'dmf_type',
            'dmf_fr_code',
            DB::raw('count(dmf_cnno)as totalCnno'),
            DB::raw("IFNULL((SELECT COUNT(dmf_cn_status) WHERE dmf_cn_status ='D'), 0)  as updated"),
            DB::raw("IFNULL((SELECT COUNT(dmf_cn_status)  WHERE dmf_cn_status ='N'), 0)  as Notupdated"),
        );

        if (isset($from_date) || isset($to_date)) {
            $query->whereBetween('dmf_mfdate', [$from_date, $to_date]);
            return $query->get();
        } else {
            return 'No Data Found';
        }
    }

    public function bookingAdminReport(Request $request)
    {
        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $book_product_type = $this->request->book_product_type;
        $book_doc = $this->request->book_doc;
        $book_mode = $this->request->book_mode;
        $book_service_type = $this->request->book_service_type;
        $office_type = $this->request->office_type;
        $office = $this->request->office;
        $book_cust_type = $this->request->book_cust_type;
        $book_cust_code = $this->request->book_cust_code;
        $book_cnno = $this->request->book_cnno;
        $book_pin = $this->request->book_pin;
        $booked_type = $this->request->booked_type;

        $query = GmsBookingDtls::select(
            DB::raw('count(book_cnno) as totalcnno'),
            DB::raw('concat(COUNT(CASE WHEN gms_booking_dtls.delivery_status <> 0 THEN 1 END)) As notDelivered'),
            DB::raw('concat(COUNT(CASE WHEN gms_booking_dtls.delivery_status <> 1 THEN 0 END)) As delivered'),
        );

        if (isset($from_date) || isset($to_date) || isset($book_product_type) || isset($book_doc) || isset($book_mode) || isset($book_service_type) || isset($book_cust_type) || isset($book_cust_code) || isset($book_cnno) || isset($book_pin)) {
            if ($from_date && $to_date) {
                $query->whereBetween('book_mfdate', [$from_date, $to_date]);
            }
            if (isset($book_product_type)) {
                $query->where('book_product_type', $book_product_type);
            }
            if (isset($book_doc)) {
                $query->where('book_doc', $book_doc);
            }
            if (isset($book_mode)) {
                $query->where('book_mode', $book_mode);
            }
            if (isset($book_service_type)) {
                $query->where('book_service_type', $book_service_type);
            }
            if (isset($book_cust_type)) {
                $query->where('book_cust_type', $book_cust_type);
            }
            if (isset($book_cust_code)) {
                $query->where('book_cust_code', $book_cust_code);
            }
            if (isset($book_cnno)) {
                $query->where('book_cnno', $book_cnno);
            }
            if (isset($book_pin)) {
                $query->where('book_pin', $book_pin);
            }
            $query->groupBy('gms_booking_dtls.delivery_t');
            $response['Status'] = $query->get();

        } else {
            $response['Status'] = ['totalcnno' => 0, 'notdelivered' => 0, 'delivered' => 0];
        }
        return $response;
    }

    public function relationship(Request $request)
    {

        // $location = GmsZone::select('id', 'zone_name')->where('is_deleted', 0)->get();
        // if ($request->isMethod('get')) {
        //     return $location;
        // } else {
        //     if ($request->state) {
        //         $getStateList = GmsState::where('is_deleted', 0)->where('zone_id', $request->state)->select('state_code', 'state_name')->get();
        //         return $getStateList;
        //     }
        //     if ($request->city) {
        //         $getCityList = GmsCity::where('is_deleted', 0)->where('state_code', $request->city)->select('city_name', 'state_code')->get();
        //         return $getCityList;
        //     }

        // }

        $getZone = GmsZone::join('gms_state', 'gms_zone.id', '=', 'gms_state.zone_id')
            ->join('gms_city', 'gms_state.state_code', 'gms_city.state_code')
            ->select('gms_zone.id', 'gms_zone.zone_name', 'gms_state.state_name', 'gms_state.state_code', 'gms_city.city_name');

        $getZone->orderBy('gms_zone.id', 'asc');
        $data[] = $getZone->get();
        return $data;
    }

    /**
     * @param Request $request
     * @return string
     */
    public function relationshipOffice(Request $request)
    {
        $office = GmsOffice::select('id', 'office_type', 'office_name', 'office_under')->where('is_deleted', 0)->where('office_type', 'ZO')->get();
        if ($request->isMethod('get')) {
            return $office;
        } else {
            if ($request->Ro) {
                $officeRo = GmsOffice:: select('office_code', 'office_name')->where('office_under', $request->Ro)->where('is_deleted', 0)->get();
                return $officeRo;
            } else {
                return 'office Not Available';
            }
        }
    }

    public function heldUpCnno(Request $request)
    {
        $adminSession = session()->get('session_token');
        $admin = Admin::where('is_deleted', 0)->where('id', $adminSession->admin_id)->first();

        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $cnno = GmsPmfDtls::select(
            DB::raw('SUM(pmf_pcs) - SUM(pmf_received_pcs) as totalPendingCnno'),
        );

        $cnno->where('is_deleted', 0);
        if ($from_date && $to_date) {
            $cnno->whereBetween('pmf_date', [$from_date, $to_date]);
            $response[] = $cnno->first();
        } else {
            $response[] = 'No Data Found';
        }
        return $response;
    }

    public function heldUpBeta(Request $request)
    {
        $adminSession = session()->get('session_token');
        $admin = Admin::where('is_deleted', 0)->where('id', $adminSession->admin_id)->first();

        $month = $this->request->month;
        $cnno = GmsPmfDtls::select(
            DB::raw('SUM(pmf_pcs) - SUM(pmf_received_pcs) as totalPendingCnno'),

        );
        $cnno->where('is_deleted', 0);
        if ($month) {
            $cnno->whereMonth('pmf_date', $month);
            $response[] = $cnno->first();
        } else {
            $response[] = 'No Data Found';
        }
        return $response;
    }
}
