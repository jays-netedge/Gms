<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\GmsBookingDtls;
use App\Models\GmsColoaderDtls;
use App\Models\GmsDmfDtls;
use App\Models\GmsCustomer;
use App\Models\GmsEmp;
use App\Models\GmsMfDtls;
use App\Models\GmsPmfDtls;
use App\Models\GmsZone;
use App\Models\GmsState;
use App\Models\GmsCity;
use App\Models\GmsOffice;
use App\Imports\CnnoUpdateImport;
use App\Imports\UpdateCustomerNameReport;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Carbon\Carbon;
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
        $office = $this->request->office;

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


                $query2->whereBetween('gms_booking_dtls.book_mfdate', [$from_date, $to_date]);
            }
            if (isset($book_product_type)) {

                $query2->where('gms_booking_dtls.book_product_type', $book_product_type);
            }
            if (isset($book_doc)) {

                $query2->where('gms_booking_dtls.book_doc', $book_doc);

            }
            if (isset($book_mode)) {

                $query2->where('gms_booking_dtls.book_mode', $book_mode);
            }
            if (isset($book_service_type)) {

                $query2->where('gms_booking_dtls.book_service_type', $book_service_type);
            }
            if (isset($office)) {

                $query2->where('gms_booking_dtls.book_br_code', $office);
            }
            if (isset($book_cust_type)) {

                $query2->where('gms_booking_dtls.book_cust_type', $book_cust_type);
            }
            if (isset($book_cust_code)) {

                $query2->where('gms_booking_dtls.book_cust_code', $book_cust_code);
            }
            if (isset($book_cnno)) {

                $query2->where('gms_booking_dtls.book_cnno', $book_cnno);
            }
            if (isset($book_pin)) {

                $query2->where('gms_booking_dtls.book_pin', $book_pin);
            }

            $query2->orderBy('book_mfdate', 'DESC');
        }

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
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->first();
        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $branch_type = $this->request->branch_type;
        $branch_name = $this->request->branch_name;
        $booking_type = $this->request->booking_type;
        $customer = $this->request->customer;

        $bookingAnalyticRep = GmsBookingDtls::leftjoin('gms_customer', 'gms_booking_dtls.book_cust_code', '=', 'gms_customer.cust_code')->select(
            'gms_booking_dtls.id',
            'gms_booking_dtls.book_cust_type',

            DB::raw('concat(gms_booking_dtls.book_cust_code,"-",gms_customer.cust_la_ent)As Customers'),
            DB::raw('count(gms_booking_dtls.book_cnno) as totalCnno'),
            /*DB::raw('count(gms_booking_dtls.book_weight) as totalWeight'),
            DB::raw('count(gms_booking_dtls.book_vol_weight) as totalVolWeight'),
            DB::raw('count(gms_booking_dtls.book_pcs) as totalPcs'),
            DB::raw('count(gms_booking_dtls.book_billamt) as totalAmt'),*/
            'gms_booking_dtls.book_mfdate'
        );

        if ($request->has('from_date') && $request->has('to_date')) {

            $bookingAnalyticRep->whereBetween('gms_booking_dtls.book_mfdate', [$from_date, $to_date]);
        }
        if ($request->has('branch_name')) {
            $bookingAnalyticRep->where('gms_booking_dtls.book_br_code', $branch_name);
        }
        if ($request->has('customer')) {
            $bookingAnalyticRep->where('gms_booking_dtls.book_cust_code', $customer);
        }
        if ($request->has('booking_type')) {
            $bookingAnalyticRep->where('gms_booking_dtls.book_cust_type', $booking_type);
        }

        $bookingAnalyticRep->groupBy('gms_booking_dtls.book_mfdate');
        $bookingAnalyticRep->where('gms_booking_dtls.is_deleted', 0);

        $data['header'] = array(
            'username' => $admin->username,
            'reportng_date' => Carbon::now()->toDateTimeString(),
            'from_date' => $from_date,
            'to_date' => $to_date,

        );
        $data['details'] = $bookingAnalyticRep->get();
        $qauantity = 0;

        foreach ($data['details'] as $row) {
            # code...
            $qauantity = $qauantity + $row['totalCnno'];
        }
        $data['grand_tot_cnno'] = $qauantity;

        return $this->successResponse(self::CODE_OK, "Successfully!!", $data);
        /*return $bookingAnalyticRep->paginate($request->per_page);*/
    }

    public function deliveryAnalyticRep(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->first();

        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $branch_type = $this->request->branch_type;
        $branch_name = $this->request->branch_name;
        $booking_type = $this->request->booking_type;
        $customer = $this->request->customer;


        $bookingAnalyticRep = GmsDmfDtls::leftjoin('gms_customer', 'gms_dmf_dtls.dmf_fr_code', '=', 'gms_customer.cust_code')->select(
            'gms_dmf_dtls.dmf_type AS cust_type',
            DB::raw('concat(gms_dmf_dtls.dmf_fr_code,"-",gms_customer.cust_name)As Customers'),
            DB::raw('count(DISTINCT gms_dmf_dtls.dmf_mfno) as totalMf'),
            DB::raw('count(gms_dmf_dtls.dmf_cnno) as totalCnno'),
            /*DB::raw('sum(gms_dmf_dtls.dmf_pcs) as updateCnno'),*/
            DB::raw("COUNT(CASE WHEN gms_dmf_dtls.dmf_invoice_no <> '0' THEN gms_dmf_dtls.dmf_invoice_no END) AS updateCnno"),

            /*DB::raw('concat(sum(gms_dmf_dtls.dmf_cnno)- sum(gms_dmf_dtls.dmf_pcs)) As Notupdated'),*/
            DB::raw("COUNT(CASE WHEN gms_dmf_dtls.dmf_invoice_no = '0' THEN gms_dmf_dtls.dmf_invoice_no END) AS Notupdated"),
            //DB::raw('count(gms_dmf_dtls.dmf_pod_status) as podsupdated'),
            DB::raw("COUNT(CASE WHEN gms_dmf_dtls.dmf_pod_status <> '0' THEN gms_dmf_dtls.dmf_pod_status END) AS podsupdated"),
            /*DB::raw('count(gms_dmf_dtls.dmf_cn_status) as Delivery'),*/
            DB::raw("COUNT(CASE WHEN gms_dmf_dtls.dl_signature <> '' THEN gms_dmf_dtls.dmf_cn_status END) AS delivered"),
        );

        if ($request->has('from_date') && $request->has('to_date')) {
            $bookingAnalyticRep->whereBetween('gms_dmf_dtls.dmf_mfdate', [$from_date, $to_date]);
        }
        if ($request->has('branch_name')) {
            $bookingAnalyticRep->where('gms_dmf_dtls.dmf_branch', $branch_name);
        }
        if ($request->has('customer')) {
            $bookingAnalyticRep->where('gms_dmf_dtls.dmf_fr_code', $customer);
        }
        if ($request->has('booking_type')) {
            $bookingAnalyticRep->where('gms_dmf_dtls.dmf_type', $booking_type);
        }

        $bookingAnalyticRep->groupBy('gms_dmf_dtls.dmf_fr_code');
        /*$bookingAnalyticRep->where('gms_dmf_dtls.is_deleted', 0);*/
        $bookingAnalyticRep->where('gms_dmf_dtls.dmf_cnno_current_status', 'WTD');
        //return $bookingAnalyticRep->paginate($request->per_page);

        $data['header'] = array(
            'username' => $admin->username,
            'reportng_date' => Carbon::now()->toDateTimeString(),
            'from_date' => $from_date,
            'to_date' => $to_date,

        );
        $data['details'] = $bookingAnalyticRep->get();

        return $this->successResponse(self::CODE_OK, "Successfully!!", $data);
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
        $type = $this->request->type;

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
        //print_r($type);die;

        if ($request->has('from_date') && $request->has('to_date')) {
            $dataSearch->whereBetween('gms_booking_dtls.book_mfdate', [$from_date, $to_date]);
            $dataSearch->where('gms_booking_dtls.is_deleted', 0);
            if (empty($out) && isset($in)) {

                //$dataSearch->where('gms_booking_dtls.book_org', $admin->office_code);
                $data['in'] = $dataSearch->get();
            } elseif (isset($out) && empty($in)) {

                //$dataSearch->where('gms_booking_dtls.book_dest', $admin->office_code);
                $data['out'] = $dataSearch->get();
            } elseif (isset($out) && isset($in)) {
                $data['in'] = $dataSearch->get();
                $data['out'] = $dataSearch->get();
            } else {
                $dataSearch->whereBetween('gms_booking_dtls.book_mfdate', [$from_date, $to_date]);
                $dataSearch->where('gms_booking_dtls.is_deleted', 0);

                $data = $dataSearch->get();
                // return $query;
            }

            return $this->successResponse(self::CODE_OK, "Successfully!!", $data);
        }
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
        $dox_type = $request->dox_type;
        $origin_zo = $request->origin_zo;

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
                if (isset($dox_type)) {
                    $query->where('gms_pmf_dtls.pmf_doc',$dox_type);
                    $reportQuery->where('gms_pmf_dtls.pmf_doc', $dox_type);
                }
                if (isset($origin_bo)) {
                    $query->where('gms_pmf_dtls.pmf_ro',$origin_zo);
                    $reportQuery->where('gms_pmf_dtls.pmf_ro', $origin_zo);
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

        $agent = $request->agent;
        $branch = $request->branch;
        $group_by = $request->group_by;
        $status = $request->status;


        

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
                if (isset($agent)) {
                    $query->where('gms_dmf_dtls.dmf_fr_code',$agent);
                }
                if (isset($branch)) {
                    $query->where('gms_dmf_dtls.dmf_branch',$branch);
                }
                if (isset($status) && $status=="N") {
                    $query->where('gms_dmf_dtls.dmf_cn_status',"D");
                }
                if (isset($status) && $status=="U") {
                    $query->where('gms_dmf_dtls.dmf_cn_status',"!=","D");
                }
                if (isset($group_by)) {
                    $query->groupBy('gms_dmf_dtls.dmf_mfdate');
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
        $office = $request->office;

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
            if ($office) {
                $query->where('gms_dmf_dtls.dmf_branch', $office);
            }

            $query->groupBy('gms_dmf_dtls.dmf_branch');
            $response['Status'] = $query->get();
    }
    
        public function dmfCustomerWise(Request $request)
    {
        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $agent = $request->agent;
        $branch = $request->branch;
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
            if (isset($agent)) {
                    $query->where('gms_dmf_dtls.dmf_fr_code',$agent);
            }
            if (isset($branch)) {
                $query->where('gms_dmf_dtls.dmf_branch',$branch);
            }
            if (isset($status) && $status=="N") {
                $query->where('gms_dmf_dtls.dmf_cn_status',"D");
            }
            if (isset($status) && $status=="U") {
                $query->where('gms_dmf_dtls.dmf_cn_status',"!=","D");
            }
            if (isset($group_by)) {
                $query->groupBy('gms_dmf_dtls.dmf_mfdate');
            }
            return $query->get();
        } else {
            return 'No Data Found';
        }
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

    // public function dmfCustomerWise(Request $request)
    // {
    //     $from_date = $request->from_date;
    //     $to_date = $request->to_date;
    //     $office_type = $request->office_type;
    //     $branch_type = $request->branch_type;
    //     $delivery_type = $request->delivery_type;

    //     $sessionObject = session()->get('session_token');
    //     $admin = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();
    //     $query = GmsDmfDtls::where('dmf_branch', $admin->office_code)->select(
    //         'dmf_type',
    //         'dmf_fr_code',
    //         DB::raw('count(dmf_cnno)as totalCnno'),
    //         DB::raw("IFNULL((SELECT COUNT(dmf_cn_status) WHERE dmf_cn_status ='D'), 0)  as updated"),
    //         DB::raw("IFNULL((SELECT COUNT(dmf_cn_status)  WHERE dmf_cn_status ='N'), 0)  as Notupdated"),
    //     );
    //     if (isset($from_date) || isset($to_date)) {
    //         $query->whereBetween('dmf_mfdate', [$from_date, $to_date]);
    //         return $query->get();
    //     } else {
    //         return 'No Data Found';
    //     }
    // }

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

    public function cnnoUpdate(Request $request)
    {
        $rows = Excel::toArray(new CnnoUpdateImport, $request->file('sampledata'));
        $cnt = count($rows[0]);
        $value = array();
        for ($x = 0; $x < $cnt; $x++) {
            array_push($value, $rows[0][$x][0]);
        }
        //->whereIn('pmf_cnno',$value);
        $getDataFromTable = DB::table('gms_pmf_dtls')
            ->leftJoin('gms_office', 'gms_pmf_dtls.pmf_origin', '=', 'gms_office.office_code')
            ->leftJoin('gms_city', 'gms_pmf_dtls.pmf_city', '=', 'gms_city.city_code')
            ->where('gms_pmf_dtls.is_deleted', 0)
            ->whereIn('pmf_cnno', $value)
            ->select('gms_pmf_dtls.pmf_cnno', 'gms_pmf_dtls.pmf_origin', 'gms_office.office_name', 'gms_pmf_dtls.pmf_wt', 'gms_pmf_dtls.pmf_mode', 'gms_pmf_dtls.pmf_pin', 'gms_pmf_dtls.pmf_city', 'gms_city.city_name', DB::raw('Count(gms_pmf_dtls.pmf_pcs) AS count_pmf_pcs'))
            ->groupBy('gms_pmf_dtls.pmf_cnno')
            ->get();
        return response()->json(["getDataFromTable" => $getDataFromTable]);
    }

    public function deliveryAgentRep(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->first();
        //print_r($admin->username);die;
        $report_type = $this->request->report_type;
        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $branch_type = $this->request->branch_type;
        $branch_name = $this->request->branch_name;
        $delivery_type = $this->request->delivery_type;
        $customer = $this->request->customer;
        $groupby = $this->request->groupby;

        if ($report_type == "D") {

            $bookingAnalyticRep = GmsDmfDtls::leftjoin('gms_customer', 'gms_dmf_dtls.dmf_fr_code', '=', 'gms_customer.cust_code')->select(

                'gms_dmf_dtls.dmf_type AS customer_type',
                DB::raw('concat(gms_dmf_dtls.dmf_fr_code,"-",gms_customer.cust_la_ent)As customer_name'),
                DB::raw('count(gms_dmf_dtls.dmf_mfno) as totalMf'),
                DB::raw('count(gms_dmf_dtls.dmf_cnno) as totalCnno'),
                DB::raw('count(gms_dmf_dtls.dmf_cn_status) as total_delv'),
            );
            $days = GmsDmfDtls::select(
                'gms_dmf_dtls.dmf_atmpt_date',
                'gms_dmf_dtls.dmf_mfdate',
                DB::raw('count(gms_dmf_dtls.dmf_atmpt_date) as count_days'),
                DB::raw('CONCAT(DATEDIFF(gms_dmf_dtls.dmf_atmpt_date, gms_dmf_dtls.dmf_mfdate)+1) as days'),
            );

            if ($request->has('from_date') && $request->has('to_date')) {
                $days->whereBetween('gms_dmf_dtls.dmf_mfdate', [$from_date, $to_date]);
            }
            if ($request->has('branch_name')) {
                $days->where('gms_dmf_dtls.dmf_branch', $branch_name);
            }
            if ($request->has('customer')) {
                $days->where('gms_dmf_dtls.dmf_fr_code', $customer);
            }
            if ($request->has('delivery_type')) {
                $days->where('gms_dmf_dtls.dmf_type', $delivery_type);
            }
            $bookingAnalyticRep->where('gms_dmf_dtls.dmf_cn_status', "D");
            $days->where('gms_dmf_dtls.dmf_cn_status', "D");
            //$days->where('gms_dmf_dtls.dmf_cnno_type', $report_type);
            $days->groupBy('gms_dmf_dtls.dmf_atmpt_date');
            $data['days'] = $days->get();

        } elseif ($report_type == "R") {
            $bookingAnalyticRep = GmsDmfDtls::leftjoin('gms_customer', 'gms_dmf_dtls.dmf_fr_code', '=', 'gms_customer.cust_code')->select(

                'gms_dmf_dtls.dmf_mfdate AS dmf_date',
                'gms_dmf_dtls.dmf_cnno AS cnno',
                DB::raw('CONCAT(DATEDIFF(gms_dmf_dtls.dmf_atmpt_date, gms_dmf_dtls.dmf_mfdate)+1) as days'),
                DB::raw("(CASE WHEN gms_dmf_dtls.dmf_cnno_current_status <> 'RTO' THEN gms_dmf_dtls.dmf_mfdate END) AS bk_date"),
                'gms_dmf_dtls.dmf_remarks AS remark',
            );
        } elseif ($report_type == "DATE") {
            $validator = Validator::make($this->request->all(), [
                'customer' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
            }
            $bookingAnalyticRep = GmsDmfDtls::leftjoin('gms_customer', 'gms_dmf_dtls.dmf_fr_code', '=', 'gms_customer.cust_code')->select(
                DB::raw('concat(gms_dmf_dtls.dmf_fr_code,"-",gms_customer.cust_la_ent)As customer_name'),
                'gms_dmf_dtls.dmf_mfdate AS dmf_date',
                DB::raw('count(gms_dmf_dtls.dmf_mfno) as totalMf'),
                DB::raw('count(gms_dmf_dtls.dmf_cnno) as totalCnno'),
                DB::raw("count(CASE WHEN gms_dmf_dtls.dmf_cnno_type = 'R' THEN gms_dmf_dtls.dmf_cnno_type END) as total_un_delv"),
            );
        } else {
            $bookingAnalyticRep = GmsDmfDtls::leftjoin('gms_customer', 'gms_dmf_dtls.dmf_fr_code', '=', 'gms_customer.cust_code')->select(
                'gms_dmf_dtls.dmf_type AS customer_type',
                DB::raw('concat(gms_dmf_dtls.dmf_fr_code,"-",gms_customer.cust_la_ent)As customer_name'),
                'gms_dmf_dtls.dmf_mfdate AS dmf_date',
            );
        }
        if ($request->has('from_date') && $request->has('to_date')) {
            $bookingAnalyticRep->whereBetween('gms_dmf_dtls.dmf_mfdate', [$from_date, $to_date]);
        }
        if ($request->has('branch_name')) {
            $bookingAnalyticRep->where('gms_dmf_dtls.dmf_branch', $branch_name);
        }
        if ($request->has('customer')) {
            $bookingAnalyticRep->where('gms_dmf_dtls.dmf_fr_code', $customer);
        }
        if ($request->has('delivery_type')) {
            $bookingAnalyticRep->where('gms_dmf_dtls.dmf_type', $delivery_type);
        }
        /*if ($request->has('report_type')) {
            $bookingAnalyticRep->where('gms_dmf_dtls.dmf_cnno_type', $report_type);
        }*/

        if ($request->has('groupby')) {
            $bookingAnalyticRep->groupBy('gms_dmf_dtls.dmf_mfdate');
        } elseif ($report_type == "R") {
            $bookingAnalyticRep->whereRaw('DATEDIFF(gms_dmf_dtls.dmf_atmpt_date, gms_dmf_dtls.dmf_mfdate) < 10');
            $bookingAnalyticRep->groupBy('gms_dmf_dtls.dmf_cnno');
        } elseif ($report_type == "DATE") {
            $bookingAnalyticRep->orderBy('gms_dmf_dtls.dmf_mfdate', 'DESC');
            $bookingAnalyticRep->groupBy('gms_dmf_dtls.dmf_mfdate');

        } else {
            $bookingAnalyticRep->groupBy('gms_dmf_dtls.dmf_fr_code');
        }
        $data['header'] = array(
            'username' => $admin->username,
            'reportng_date' => Carbon::now()->toDateTimeString(),
            'from_date' => $from_date,
            'to_date' => $to_date,
            'customer_code' => $customer
        );
        $data['details'] = $bookingAnalyticRep->get();
        return $this->successResponse(self::CODE_OK, "Successfully!!", $data);
    }

    public function bookingCustNoInfoRep(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->first();

        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $branch_name = $this->request->branch_name;
        $cust_type = $this->request->cust_type;
        $customer = $this->request->customer;
        $groupby = $this->request->groupby;
        $validator = Validator::make($this->request->all(), [
            'customer' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $bookingCustNoInfoRep = GmsBookingDtls::leftjoin('gms_customer', 'gms_booking_dtls.book_cust_code', '=', 'gms_customer.cust_code')->leftjoin('gms_dmf_dtls', 'gms_booking_dtls.book_cnno', '=', 'gms_dmf_dtls.dmf_cnno')->leftjoin('gms_customer AS dmf_cust', 'gms_dmf_dtls.dmf_fr_code', '=', 'dmf_cust.cust_code')->select(
            DB::raw('count(gms_booking_dtls.book_cust_code) as total_booked'),
            DB::raw('count(gms_dmf_dtls.dmf_cnno) as total_drs'),
            DB::raw("count(CASE WHEN gms_dmf_dtls.dmf_cn_status = 'D' THEN gms_dmf_dtls.dmf_cn_status END) as total_delivered")
        );
        $cnno = GmsBookingDtls::select(
            'gms_booking_dtls.book_cnno'
        );
        if ($request->has('from_date') && $request->has('to_date')) {
            $bookingCustNoInfoRep->whereBetween('gms_booking_dtls.book_mfdate', [$from_date, $to_date]);
            $cnno->whereBetween('gms_booking_dtls.book_mfdate', [$from_date, $to_date]);
        }
        if ($request->has('branch_name')) {
            $bookingCustNoInfoRep->where('gms_booking_dtls.book_br_code', $branch_name);
            $cnno->where('gms_booking_dtls.book_br_code', $branch_name);
        }
        if ($request->has('customer')) {
            $bookingCustNoInfoRep->where('gms_booking_dtls.book_cust_code', $customer);
            $cnno->where('gms_booking_dtls.book_cust_code', $customer);
        }
        if ($request->has('groupby')) {
            $bookingCustNoInfoRep->groupBy('gms_booking_dtls.book_mfdate');
        } else {
            $bookingCustNoInfoRep->groupBy('gms_booking_dtls.book_cust_code');
        }
        $cnno_booking = $cnno->get();
        $data['details'] = $bookingCustNoInfoRep->get();
        $dmf = GmsDmfDtls::leftjoin('gms_customer', 'gms_dmf_dtls.dmf_fr_code', '=', 'gms_customer.cust_code')->leftjoin('gms_booking_dtls', 'gms_dmf_dtls.dmf_cnno', '=', 'gms_booking_dtls.book_cnno')->select(
            'gms_dmf_dtls.dmf_cnno AS cnno',
            'gms_booking_dtls.book_mfdate AS booking_date',
            'gms_booking_dtls.book_pin AS pincode',
            'gms_dmf_dtls.dmf_mfdate AS fdm_data',
            'gms_dmf_dtls.dmf_fr_code AS fr_code',
            'gms_customer.cust_la_ent AS fr_name',
            'gms_booking_dtls.book_cons_dtl AS cons_dtls',
        // DB::raw('count(gms_dmf_dtls.dmf_cnno) as total_cnno'),
        );
        //print_r($cnno_booking);
        foreach ($cnno_booking as $value) {
            # code...
            $cn_list[] = $value['book_cnno'];
        }
        $dmf->where('gms_dmf_dtls.dmf_cn_status', 'D')->whereIn('gms_dmf_dtls.dmf_cnno', $cn_list);
        $data['delivered'] = $dmf->get();
        $data['total_cnno'] = count($data['delivered']);
        $cust = GmsCustomer::select('cust_la_ent')->where('cust_code', $customer)->where('cust_type', $cust_type)->where('is_deleted', 0)->first();
        $data['header'] = array(
            'username' => $admin->username,
            'reportng_date' => Carbon::now()->toDateTimeString(),
            'from_date' => $from_date,
            'to_date' => $to_date,
            'customer_type' => $cust_type,
            'customer_name' => $cust->cust_la_ent
        );
        return $this->successResponse(self::CODE_OK, "Successfully!!", $data);
    }

    public function UpdateCustomerNameReport(Request $request)
    {
        $rows = Excel::toArray(new UpdateCustomerNameReport, $request->file('sampledata'));
        $cnt = count($rows[0]);

        $consignorname = array();
        $cnno = array();
        for ($x = 1; $x < $cnt; $x++) {
            array_push($cnno, $rows[0][$x][0]);
            array_push($consignorname, $rows[0][$x][1]);
        }
        $book_total = count($cnno);
        for ($x = 0; $x < $book_total; $x++) {
            GmsBookingDtls::where('book_cnno', $cnno[$x])
                ->update(['book_cn_name' => $consignorname[$x]]);
        }
        return response()->json(["message" => 'Successfully Upated']);
    }

    public function bookingCustDlvPerRep(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->first();

        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $branch_name = $this->request->branch_name;
        $cust_type = $this->request->cust_type;
        $customer = $this->request->customer;
        $groupby = $this->request->groupby;
        $validator = Validator::make($this->request->all(), [
            'customer' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $bookingCustNoInfoRep = GmsBookingDtls::leftjoin('gms_customer', 'gms_booking_dtls.book_cust_code', '=', 'gms_customer.cust_code')->leftjoin('gms_dmf_dtls', 'gms_booking_dtls.book_cnno', '=', 'gms_dmf_dtls.dmf_cnno')->leftjoin('gms_customer AS dmf_cust', 'gms_dmf_dtls.dmf_fr_code', '=', 'dmf_cust.cust_code')->select(
            DB::raw('count(gms_booking_dtls.book_cust_code) as total_booked'),
            DB::raw('count(gms_dmf_dtls.dmf_cnno) as drs_done'),
            DB::raw("CONCAT(count(gms_booking_dtls.book_cust_code)- count(gms_dmf_dtls.dmf_cnno)) as drs_not_done")
        );
        $cnno = GmsBookingDtls::select(
            'gms_booking_dtls.book_cnno'
        );
        if ($request->has('from_date') && $request->has('to_date')) {
            $bookingCustNoInfoRep->whereBetween('gms_booking_dtls.book_mfdate', [$from_date, $to_date]);
            $cnno->whereBetween('gms_booking_dtls.book_mfdate', [$from_date, $to_date]);
        }
        /*if ($request->has('branch_name')) {
            $bookingCustNoInfoRep->where('gms_booking_dtls.book_br_code', $branch_name);
            $cnno->where('gms_booking_dtls.book_br_code', $branch_name);
        }*/
        if ($request->has('customer')) {
            $bookingCustNoInfoRep->where('gms_booking_dtls.book_cust_code', $customer);
            $cnno->where('gms_booking_dtls.book_cust_code', $customer);
        }
        if ($request->has('groupby')) {
            $bookingCustNoInfoRep->groupBy('gms_booking_dtls.book_mfdate');
        } else {
            $bookingCustNoInfoRep->groupBy('gms_booking_dtls.book_cust_code');
        }
        $cnno_booking = $cnno->get();
        $data['details'] = $bookingCustNoInfoRep->get();
        $dmf = GmsDmfDtls::leftjoin('gms_customer', 'gms_dmf_dtls.dmf_fr_code', '=', 'gms_customer.cust_code')->leftjoin('gms_booking_dtls', 'gms_dmf_dtls.dmf_cnno', '=', 'gms_booking_dtls.book_cnno')->select(
            'gms_dmf_dtls.dmf_cnno AS cnno',
            'gms_booking_dtls.book_type AS book_type',
            'gms_booking_dtls.book_mfdate AS booking_date',
            'gms_booking_dtls.book_pin AS pincode',
            'gms_dmf_dtls.dmf_mfdate AS fdm_data',
            'gms_dmf_dtls.dmf_fr_code AS fr_code',
            'gms_customer.cust_la_ent AS fr_name',
            'gms_booking_dtls.book_cons_dtl AS cons_dtls',
            DB::raw('COUNT(gms_dmf_dtls.dmf_fr_code) AS tot'),
            DB::raw('concat(COUNT(CASE WHEN gms_dmf_dtls.dmf_cn_status = "D" THEN 1 END)) As dlv'),
            DB::raw('concat(COUNT(CASE WHEN gms_dmf_dtls.dmf_cn_status <> "D" THEN 1 END)) As unDlv'),
            DB::raw('concat(COUNT(CASE WHEN gms_dmf_dtls.dmf_cnno_current_status = "RTO" THEN 1 END)) As rto'),
            DB::raw('concat(COUNT(CASE WHEN gms_dmf_dtls.dmf_cnno_current_status = "WTD" && gms_dmf_dtls.dmf_cn_status <> "D" THEN 1 END)) As outDlv'),
            DB::raw('CONCAT(round(((COUNT(CASE WHEN gms_dmf_dtls.dmf_cn_status = "D" THEN 1 END))/(COUNT(gms_dmf_dtls.dmf_fr_code))*100),0),"%") as perfor'),
        );
        //print_r($cnno_booking);
        foreach ($cnno_booking as $value) {
            # code...
            $cn_list[] = $value['book_cnno'];
        }
        if (isset($cn_list)) {
            $dmf->whereIn('gms_dmf_dtls.dmf_cnno', $cn_list)->groupBy('gms_dmf_dtls.dmf_fr_code');
        }
        $data['delivered'] = $dmf->get();
        //$data['total_cnno'] = count($data['delivered']);
        $tot = 0;
        $tot_dlv = 0;
        $tot_undvl = 0;
        $tot_rto = 0;
        $tot_out_dlv = 0;
        foreach ($data['delivered'] as $value) {
            # code...
            //print_r($value);
            $tot = $tot + $value['tot'];
            $tot_dlv = $tot_dlv + $value['dlv'];
            $tot_undvl = $tot_undvl + $value['unDlv'];
            $tot_rto = $tot_rto + $value['rto'];
            $tot_out_dlv = $tot_out_dlv + $value['outDlv'];
        }
        $data['total'] = array(
            'tot' => $tot,
            'tot_dlv' => $tot_dlv,
            'tot_undvl' => $tot_undvl,
            'tot_rto' => $tot_rto,
            'tot_out_dlv' => $tot_out_dlv,
        );
        $cust = GmsCustomer::select(
            DB::raw('CONCAT(cust_code,"-",cust_la_ent) AS cust_name')
        )->where('cust_code', $customer)->where('cust_type', $cust_type)->where('is_deleted', 0)->first();
        $data['header'] = array(
            'username' => $admin->username,
            'reportng_date' => Carbon::now()->toDateTimeString(),
            'from_date' => $from_date,
            'to_date' => $to_date,
            'customer_type' => $cust_type,
            'customer_name' => (isset($cust->cust_name)) ? $cust->cust_name : ''
        );
        return $this->successResponse(self::CODE_OK, "Successfully!!", $data);
    }

    public function undeliveryAgentRep(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->first();

        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $branch_name = $this->request->branch_name;
        $cust_type = $this->request->cust_type;
        $customer = $this->request->customer;
        $groupby = $this->request->groupby;
        $report_type = $this->request->report_type;
        if ($report_type == "D") {
            $undeliveryAgentRep = GmsDmfDtls::join('gms_customer', 'gms_dmf_dtls.dmf_fr_code', '=', 'gms_customer.cust_code')->select(
                'gms_dmf_dtls.dmf_type AS cust_type',
                DB::raw('concat(gms_customer.cust_code,"-", gms_customer.cust_la_ent) As cust_name'),
                DB::raw('count(DISTINCT gms_dmf_dtls.dmf_mfno) as tot_mf'),
                DB::raw('count(gms_dmf_dtls.dmf_cnno) as tot_cnno'),
                DB::raw('concat(COUNT(CASE WHEN gms_dmf_dtls.dmf_cn_status <> "D" THEN 1 END)) As tot_undlv'),
            );
            $days = GmsDmfDtls::select(
                'gms_dmf_dtls.dmf_atmpt_date',
                'gms_dmf_dtls.dmf_mfdate',
                DB::raw('count(gms_dmf_dtls.dmf_atmpt_date) as count_days'),
                DB::raw('CONCAT(DATEDIFF(gms_dmf_dtls.dmf_atmpt_date, gms_dmf_dtls.dmf_mfdate)+1) as days'),
            );
            if ($request->has('from_date') && $request->has('to_date')) {
                $days->whereBetween('gms_dmf_dtls.dmf_mfdate', [$from_date, $to_date]);
            }
            if ($request->has('branch_name')) {
                $days->where('gms_dmf_dtls.dmf_branch', $branch_name);
            }
            if ($request->has('customer')) {
                $days->where('gms_dmf_dtls.dmf_fr_code', $customer);
            }
            if ($request->has('delivery_type')) {
                $days->where('gms_dmf_dtls.dmf_type', $report_type);
            }
            $days->where('gms_dmf_dtls.dmf_cn_status', '!=', "D");
            $days->groupBy('gms_dmf_dtls.dmf_atmpt_date');
            $data['days'] = $days->get();
        } elseif ($report_type == "R") {
            $undeliveryAgentRep = GmsDmfDtls::leftjoin('gms_customer', 'gms_dmf_dtls.dmf_fr_code', '=', 'gms_customer.cust_code')->select(
                'gms_dmf_dtls.dmf_mfdate AS dmf_date',
                'gms_dmf_dtls.dmf_cnno AS cnno',
                DB::raw('CONCAT(DATEDIFF(gms_dmf_dtls.dmf_atmpt_date, gms_dmf_dtls.dmf_mfdate)+1) as days'),
                DB::raw("(CASE WHEN gms_dmf_dtls.dmf_cnno_current_status <> 'RTO' THEN gms_dmf_dtls.dmf_mfdate END) AS bk_date"),
                'gms_dmf_dtls.dmf_remarks AS remark',
            );
        } elseif ($report_type == "DATE") {
            $validator = Validator::make($this->request->all(), [
                'customer' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
            }
            $undeliveryAgentRep = GmsDmfDtls::leftjoin('gms_customer', 'gms_dmf_dtls.dmf_fr_code', '=', 'gms_customer.cust_code')->select(
                DB::raw('concat(gms_dmf_dtls.dmf_fr_code,"-",gms_customer.cust_la_ent)As customer_name'),
                'gms_dmf_dtls.dmf_mfdate AS dmf_date',
                DB::raw('count(gms_dmf_dtls.dmf_mfno) as totalMf'),
                DB::raw('count(gms_dmf_dtls.dmf_cnno) as totalCnno'),
                DB::raw("count(CASE WHEN gms_dmf_dtls.dmf_cnno_type = 'R' THEN gms_dmf_dtls.dmf_cnno_type END) as total_un_delv"),
            );
        } else {
            $undeliveryAgentRep = GmsDmfDtls::leftjoin('gms_customer', 'gms_dmf_dtls.dmf_fr_code', '=', 'gms_customer.cust_code')->select(
                'gms_dmf_dtls.dmf_type AS customer_type',
                DB::raw('concat(gms_dmf_dtls.dmf_fr_code,"-",gms_customer.cust_la_ent)As customer_name'),
                'gms_dmf_dtls.dmf_mfdate AS dmf_date',
            );
        }
        if ($request->has('from_date') && $request->has('to_date')) {
            $undeliveryAgentRep->whereBetween('gms_dmf_dtls.dmf_mfdate', [$from_date, $to_date]);
        }
        if ($request->has('customer')) {
            $undeliveryAgentRep->where('gms_dmf_dtls.dmf_fr_code', $customer);
        }
        if ($request->has('groupby')) {
            $undeliveryAgentRep->groupBy('gms_dmf_dtls.dmf_fr_code');
        } elseif ($report_type == "R") {
            $undeliveryAgentRep->whereRaw('DATEDIFF(gms_dmf_dtls.dmf_atmpt_date, gms_dmf_dtls.dmf_mfdate) < 10');
            $undeliveryAgentRep->groupBy('gms_dmf_dtls.dmf_cnno');
        } elseif ($report_type == "DATE") {
            $undeliveryAgentRep->orderBy('gms_dmf_dtls.dmf_mfdate', 'desc');
            $undeliveryAgentRep->groupBy('gms_dmf_dtls.dmf_mfdate');
        } else {
            $undeliveryAgentRep->groupBy('gms_dmf_dtls.dmf_mfdate');
        }
        $data['details'] = $undeliveryAgentRep->get();
        $cust = GmsCustomer::where('cust_code', $customer)->where('cust_type', $cust_type)->where('is_deleted', 0)->first();
        $data['header'] = array(
            'username' => $admin->username,
            'reportng_date' => Carbon::now()->toDateTimeString(),
            'from_date' => $from_date,
            'to_date' => $to_date,
        );
        return $this->successResponse(self::CODE_OK, "Successfully!!", $data);
    }

    public function empWisePerReports(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->first();

        $from_date = $request->from_date;
        $to_date = $request->to_date;
        $branch_name = $request->branch_name;
        $branch_type = $request->branch_type;
        $customer = $request->customer;
        $deliver_type = $request->deliver_type;
        $groupby = $request->groupby;

     
        $booking = GmsBookingDtls::select(
            DB::raw('COUNT(CASE WHEN gms_booking_dtls.book_cnno THEN 0 END) As booking'),
            'book_mfdate');
        $incoming = GmsPmfDtls::select(
            DB::raw("count(CASE WHEN gms_pmf_dtls.pmf_type = 'IPMF' THEN gms_pmf_dtls.pmf_cnno END) as incoming"));

        $outgoing = GmsPmfDtls::select(
            DB::raw("count(CASE WHEN gms_pmf_dtls.pmf_type = 'OPMF' THEN gms_pmf_dtls.pmf_cnno END) as outgoing"));

        $delivered = GmsDmfDtls::select(
            DB::raw("count(CASE WHEN gms_dmf_dtls.dmf_cn_status = 'D' THEN gms_dmf_dtls.dmf_cnno END) as delivered"));
        
        $deliveredPod = GmsDmfDtls::select(
            DB::raw("count(CASE WHEN gms_dmf_dtls.dmf_pod_status = '1' THEN gms_dmf_dtls.dmf_pod_status END) as delivered"));

        if ($request->has('from_date') && $request->has('to_date')) {
            $booking->whereBetween('gms_booking_dtls.book_mfdate', [$from_date, $to_date]);
            $outgoing->whereBetween('gms_pmf_dtls.pmf_date', [$from_date, $to_date]);
            $incoming->whereBetween('gms_pmf_dtls.pmf_date', [$from_date, $to_date]);
            $delivered->whereBetween('gms_dmf_dtls.dmf_mfdate', [$from_date, $to_date]);
            $deliveredPod->whereBetween('gms_dmf_dtls.dmf_mfdate', [$from_date, $to_date]);
        }

        if ($request->has('branch_name')) {
            $booking->where('gms_booking_dtls.book_br_code', $branch_name);
            $outgoing->where('gms_pmf_dtls.pmf_origin', $branch_name);
            $incoming->where('gms_pmf_dtls.pmf_origin', $branch_name);
            $delivered->where('gms_dmf_dtls.dmf_branch', $branch_name);
            $deliveredPod->where('gms_dmf_dtls.dmf_branch', $branch_name);
        }

        if ($request->has('customer')) {
            $booking->where('gms_booking_dtls.book_emp_code', $customer);
            $outgoing->where('gms_pmf_dtls.pmf_emp_code', $customer);
            $incoming->where('gms_pmf_dtls.pmf_emp_code', $customer);
            $delivered->where('gms_dmf_dtls.dmf_emp', $customer);
            $deliveredPod->where('gms_dmf_dtls.dmf_emp', $customer);
        }

        $cust = GmsEmp::where('emp_code', $customer)->where('is_deleted', 0)->first();
        $data['header'] = array(
            'username' => $admin->username,
            'reportng_date' => Carbon::now()->toDateTimeString(),
            'from_date' => $from_date,
            'to_date' => $to_date
        );

//Booking
        $data['booking'] = $booking->groupBy('book_mfdate')->get();      
        $totbook = 0;
        for ($x = 0; $x < count($data['booking']); $x++) {
            $data['booking'][$x]->booking;
            $totbook = $totbook + $data['booking'][$x]->booking;
        }
//Outgoing      
        $data['outgoing'] = $outgoing->get();
        $totOut = 0;
        for($x = 0; $x < count($data['outgoing']); $x++) {
            $data['outgoing'][$x]->outgoing;
            $totOut = $totOut + $data['outgoing'][$x]->outgoing;
        }
//Incoming
        $data['incoming'] = $incoming->get();
        $totIn = 0;
        for($x = 0; $x < count($data['incoming']); $x++) {
            $data['incoming'][$x]->incoming;
            $totIn = $totIn + $data['incoming'][$x]->incoming; 
        }
//Delivered
        $data['delivered'] = $delivered->get();
        $totDel = 0;
        for ($x=0; $x < count($data['delivered']); $x++) { 
            $data['delivered'][$x]->delivered;
            $totDel = $totDel + $data['delivered'][$x]->delivered; 
        }
//DeliveredPodS        
        $data['deliveredPod'] = $deliveredPod->get();
        $totDelPod = 0;
        for ($x=0; $x <count($data['deliveredPod']); $x++) { 
            $totDelPod = $totDelPod +  $data['deliveredPod'][$x]->deliveredPod;
        }

        $data1 = $totbook;
        $data2 = $totOut;
        $data3 = $totIn;
        $data4 = $totDel;
        $data5 = $totDelPod;
        
        $data['total'] = intval($data1) + intval($data2) + intval($data3) + intval($data4) + intval($data5);        
        return $this->successResponse(self::CODE_OK, "Successfully!!", $data);

    }

    public function rtoRep(Request $request)
    {
         $sessionObject = session()->get('session_token');
         $admin = Admin::where('id', $sessionObject->admin_id)->first();

         $from_date = $this->request->from_date;
         $to_date = $this->request->to_date;
         $branch_name = $this->request->branch_name;
         $cust_type = $this->request->cust_type;
         $customer = $this->request->customer;
         $groupby = $this->request->groupby;


        if(isset($cust_type) && $cust_type == "BE"){
                $rtoRep = GmsDmfDtls::leftjoin('gms_emp','gms_dmf_dtls.dmf_fr_code','=','gms_emp.emp_code')->leftjoin('gms_rto_dtls','gms_dmf_dtls.dmf_cnno','=','gms_rto_dtls.rto_cnno')->select(
                'gms_dmf_dtls.dmf_type AS cust_type',
                'gms_dmf_dtls.dmf_cnno AS cnno',
                'gms_emp.emp_code AS cust_code',
                DB::raw('CONCAT(gms_emp.emp_code,"-",gms_emp.emp_name) AS cust_name'),
                'gms_dmf_dtls.dmf_mfdate AS date',
                DB::raw('COUNT(gms_dmf_dtls.dmf_cnno) AS total_cnno'),

            );
                $rtoRep1 = GmsDmfDtls::leftjoin('gms_emp','gms_dmf_dtls.dmf_fr_code','=','gms_emp.emp_code')->leftjoin('gms_rto_dtls','gms_dmf_dtls.dmf_cnno','=','gms_rto_dtls.rto_cnno')->select(
                    'gms_emp.emp_code AS cust_code',
                    DB::raw('COUNT(gms_dmf_dtls.dmf_cnno) AS tot'),

                );
        }else{

            $rtoRep = GmsDmfDtls::leftjoin('gms_customer','gms_dmf_dtls.dmf_fr_code','=','gms_customer.cust_code')->leftjoin('gms_rto_dtls','gms_dmf_dtls.dmf_cnno','=','gms_rto_dtls.rto_cnno')->select(

                'gms_dmf_dtls.dmf_type AS cust_type',
                'gms_dmf_dtls.dmf_cnno AS cnno',
                'gms_customer.cust_code AS cust_code',
                DB::raw('CONCAT(gms_customer.cust_code,"-",gms_customer.cust_la_ent) AS cust_name'),
                'gms_dmf_dtls.dmf_mfdate AS date',
                DB::raw('COUNT(gms_dmf_dtls.dmf_cnno) AS total_cnno'),

            );
            $rtoRep1 = GmsDmfDtls::leftjoin('gms_customer','gms_dmf_dtls.dmf_fr_code','=','gms_customer.cust_code')->leftjoin('gms_rto_dtls','gms_dmf_dtls.dmf_cnno','=','gms_rto_dtls.rto_cnno')->select(
                    'gms_customer.cust_code AS cust_code',
                    DB::raw('COUNT(gms_dmf_dtls.dmf_cnno) AS tot'),

                );
        }

        if ($request->has('from_date') && $request->has('to_date')) {
            $rtoRep->whereBetween('gms_dmf_dtls.dmf_mfdate', [$from_date, $to_date]);
            $rtoRep1->whereBetween('gms_dmf_dtls.dmf_mfdate', [$from_date, $to_date]);
        }
        if ($request->has('branch_name')) {
            $rtoRep->where('gms_dmf_dtls.dmf_branch', $branch_name);
            $rtoRep1->where('gms_dmf_dtls.dmf_branch', $branch_name);
        }
        if ($request->has('customer')) {
            $rtoRep->where('gms_dmf_dtls.dmf_fr_code', $customer);
            $rtoRep1->where('gms_dmf_dtls.dmf_fr_code', $customer);
        }else{
            $rtoRep->where('gms_dmf_dtls.dmf_mfdate', $customer);
            $rtoRep1->where('gms_dmf_dtls.dmf_mfdate', $customer);
        }
        $rtoRep->groupBy('gms_dmf_dtls.dmf_mfdate');
        $rtoRep1->groupBy('gms_dmf_dtls.dmf_fr_code');
        $data['details'] = $rtoRep->get();
        $data['tot'] = $rtoRep1->get();

        /*foreach ($data['details'] as $value) {
            # code...
            foreach ($data['tot'] as $value2) {
                # code...
                if($value['cust_code'] == $value2['cust_code']){
                    $tot = $tot +
                }
            }

        }*/
        $data['header'] = array(
            'username' =>$admin->username,
            'reportng_date' =>Carbon::now()->toDateTimeString(),
            'from_date'=>$from_date,
            'to_date' =>$to_date,

        );

        return $this->successResponse(self::CODE_OK, "Successfully!!", $data);
    }
}
