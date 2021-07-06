<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use App\Models\GmsBookingDtls;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BookingReportExport implements FromCollection, WithHeadings
{
    protected $columns;

    public function __construct(array $columns, array $headers, object $request)
    {
        $this->columns = $columns;
        $this->headers = $headers;
        $this->request = $request;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
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
        $group_by = $this->request->group_by;

        $query = GmsBookingDtls::
        leftJoin('gms_customer as book_customer', 'book_customer.cust_code', '=', 'gms_booking_dtls.book_cust_code')
            ->leftJoin('gms_customer as book_fr_customer', 'book_fr_customer.cust_code', '=', 'gms_booking_dtls.book_fr_cust_code')
            ->leftJoin('gms_city', 'gms_city.city_code', '=', 'gms_booking_dtls.book_org');
        if ($from_date && $to_date) {
            $query->whereBetween('gms_booking_dtls.book_mfdate', [$from_date, $to_date]);
        }
        if (isset($book_product_type)) {
            $query->where('gms_booking_dtls.book_product_type', $book_product_type);
        }
        if (isset($book_doc)) {
            $query->where('gms_booking_dtls.book_doc', $book_doc);
        }
        if (isset($book_mode)) {
            $query->where('gms_booking_dtls.book_mode', $book_mode);
        }
        if (isset($book_service_type)) {
            $query->where('gms_booking_dtls.book_service_type', $book_service_type);
        }
        if (isset($book_cust_type)) {
            $query->where('gms_booking_dtls.book_cust_type', $book_cust_type);
        }
        if (isset($book_cust_code)) {
            $query->where('gms_booking_dtls.book_cust_code', $book_cust_code);
        }
        if (isset($book_cnno)) {
            $query->where('gms_booking_dtls.book_cnno', $book_cnno);
        }
        if (isset($book_pin)) {
            $query->where('gms_booking_dtls.book_pin', $book_pin);
        }
        // if(isset($group_by)){
        //     $grpby = "";
        //     if($group_by==="PT"){

        //     }
        //     $query->group_by($group_by);
        // }
        return $query->addSelect($this->columns)->distinct()->get();
    }

    public function headings(): array
    {
        return $this->headers;
    }
}
