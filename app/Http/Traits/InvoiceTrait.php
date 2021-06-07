<?php

namespace App\Http\Traits;

use App\Models\GmsColoader;
use App\Models\GmsInvoice;
use App\Models\GmsInvoiceSf;
use App\Models\GmsMfDtls;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

trait InvoiceTrait
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view_invoice()
    {
        $validator = Validator::make($this->request->all(), [
            'invoice_id' => 'required|exists:gms_invoice,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewInvoice = GmsInvoice::where('id', $input['invoice_id'])->where('is_deleted', 0)->paginate(5)->first();

        if (!$viewInvoice) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Invoice Details Show Successfully!!", $viewInvoice);
        }
    }

    public function delete_invoice()
    {
        $validator = Validator::make($this->request->all(), [
            'invoice_no' => 'required|exists:gms_invoice,invoice_no',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $deleteInvoice = GmsInvoice::where('invoice_no', $input['invoice_no'])->first();;
        if ($deleteInvoice != null) {
            $deleteInvoice->is_deleted = 1;
            $deleteInvoice->save();
            return $this->successResponse(self::CODE_OK, "Delete Invoice Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function view_invoiceSf()
    {
        $validator = Validator::make($this->request->all(), [
            'insf_id' => 'required|exists:gms_invoice_sf,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewInvoiceSf = GmsInvoiceSf::where('id', $input['insf_id'])->select('invoice_no', 'invoice_date', 'cust_type', 'customer_code', 'fr_service_charge', 'fr_net_service_charge', 'fr_actual_service_charge', 'fr_less_billing_discount', 'fr_sub_total', 'fr_fuel_amount', 'fr_total', 'fr_service_tax_name', 'fr_grand_total', 'print_type')->paginate(5)->first();

        if (!$viewInvoiceSf) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Invoice Sf Details Show Successfully!!", $viewInvoiceSf);
        }
    }

    public function view_manifest()
    {
        $validator = Validator::make($this->request->all(), [
            'mani_id' => 'required|exists:gms_mf_dtls,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewManifest = GmsMfDtls::where('id', $input['mani_id'])->paginate(5)->first();

        if (!$viewManifest) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Manifest Details Show Successfully!!", $viewManifest);
        }
    }

    public function view_coloader()
    {
        $validator = Validator::make($this->request->all(), [
            'coloader_id' => 'required|exists:gms_coloader,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewColoader = GmsColoader::where('id', $input['coloader_id'])->first();

        explode(',', $viewColoader->coloader_type);

        $viewColoaderData = GmsColoader::where('id', $input['coloader_id'])->select('coloader_type', 'coloader_code', 'coloader_name', 'coloader_add1', 'coloader_add2', 'coloader_contact', 'coloader_phone', 'coloader_rep_offtype', 'coloader_rep_office')->paginate(5)->first();

        if (!$viewColoaderData) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Coloader Show Successfully!!", $viewColoaderData);
        }
    }


}

