<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use App\Models\GmsInvoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Http\Request;

class SalesRegisterExport implements FromCollection, WithHeadings
{
    protected $columns;
    protected $request;

     public function __construct(Request $request)
    {
        $this->request = $request;
    }   

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $year = $this->request->year;
        $month = $this->request->month;
        $cust_type = $this->request->cust_type;
       
        $viewReportOfSalesReg = GmsInvoice::leftJoin('gms_customer', 'gms_invoice.customer_code', '=', 'gms_customer.cust_code')
            ->select(
            'gms_invoice.id',
            'gms_invoice.fr_invoice_no',
            'gms_invoice.invoice_date',
            'gms_invoice.cust_type',
            DB::raw('CONCAT(gms_invoice.customer_code,"(",gms_customer.cust_la_ent) AS cust_code'),
            DB::raw('COUNT(gms_invoice.total_cnno) AS total_cnno'),
            DB::raw('SUM(gms_invoice.total_weight) AS total_weight'),
            'gms_invoice.fr_actual_service_charge',
            'gms_invoice.fr_actual_service_charge AS edited_service_charge',
            'gms_invoice.fr_less_billing_discount AS less_billing_discount',
            'gms_invoice.fr_sub_total AS sub_total',
            'gms_invoice.fr_fuel_amount',
            'gms_invoice.fr_total',
            'gms_invoice.fr_less_delivery_discount',
            'gms_invoice.fr_less_sf_discount',
            'gms_invoice.fr_grand_total',
            'gms_invoice.fr_net_service_charge',
            'gms_invoice.grand_total',
            'gms_invoice.fr_voucher_amount',           
        );
        
        if (isset($year)) {
            $viewReportOfSalesReg->where('gms_invoice.year', $year);
        }
        if (isset($month)) {
            $viewReportOfSalesReg->where('gms_invoice.month', $month);
        }
        if (isset($cust_type)) {
            $viewReportOfSalesReg->where('gms_invoice.cust_type', $cust_type);
        }
        $viewReportOfSalesReg->groupBy('gms_invoice.cust_type');

        $getExportData = $viewReportOfSalesReg->get();
        return $getExportData;

    }

    public function headings(): array
    {
        return [
            
            'NO',
            'invoice no',
            'invoice date',
            'customer type',
            'customer code',
            'Total CNNO',
            'Total WEIGHT',
            'Actual sc value',
            'EDITED sc value',
            'Less BDD',
            'Subtotal',
            'Fuel charges',
            'total',
            'Less DRD',
            'Less SFD',
            'Total',
            'SERVICE TAX',
            'Grand total',
            'VOUCHER VALUE',
        ];
    }
}
