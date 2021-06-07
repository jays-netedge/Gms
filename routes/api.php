<?php

use App\Http\Middleware\CustomerMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\V1\EmpController;
use App\Http\Controllers\V1\CustomerController;
use App\Http\Controllers\V1\BookController;
use App\Http\Controllers\V1\InvoiceController;
use App\Http\Controllers\V1\AdminController;
use App\Http\Controllers\V1\GmsController;
use App\Http\Controllers\V1\RateController;
use App\Http\Controllers\V1\MfController;
use App\Http\Controllers\V1\DashBoardController;
use App\Http\Controllers\V1\PodController;
use App\Http\Controllers\V1\ReportController;
use App\Models\GmsBookBoTransfer;
use App\Models\GmsBookEpodGenerate;
use App\Models\GmsBookRoIssue;
use App\Models\GmsCustomer;
use App\Models\GmsBookCustIssue;
use App\Models\GmsEmp;
use App\Models\GmsPayment;
use App\Models\GmsCustomerFranchisee;
use App\Models\GmsBookingDtls;
use App\Models\GmsBookBoissue;
use App\Models\GmsBookRoTransfer;
use App\Models\GmsInvoice;
use App\Models\GmsInvoiceSf;
use App\Models\GmsInvoiceCust;
use App\Models\GmsMfDtls;
use App\Models\GmsColoader;
use App\Models\GmsRtoDtls;
use App\Models\GmsExrDtls;
use App\Models\GmsComplaint;
use App\Models\GmsBookBlock;
use App\Models\GmsCnnoStock;
use App\Models\GmsAlert;
use App\Models\GmsPincode;
use App\Models\GmsRateMasterAmdro;
use App\Models\GmsRateCode;
use App\Models\GmsCountries;
use App\Models\GmsCity;
use App\Http\Middleware\UserCheck;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\UserMiddleware;
use App\Http\Middleware\Cors;
use App\Http\Middleware\SuperAdminMiddleware;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


//====================================ADMIN ROUTE LIST===========================================//
Route::get('/generateDefaultAdmin', [AdminController::class, 'generateDefaultAdmin']);
Route::post('/adminRegister', [AdminController::class, 'createAdmin']);
Route::post('/adminLogin', [AdminController::class, 'adminLogin']);
Route::post('/validateSession', [UserController::class, 'validateSession']);
Route::get('/getRoOffice', [BookController::class, 'getRoOffice']);
Route::get('/getBoOffice', [BookController::class, 'getBoOffice']);
Route::post('/editProfile', [AdminController::class, 'editProfile']);
Route::get('/adminOfficeTypeList', [AdminController::class, 'adminOfficeTypeList']);
Route::post('/adminOfficeList', [AdminController::class, 'adminOfficeList']);
Route::get('/adminOfficeRoTypeList', [AdminController::class, 'adminOfficeRoTypeList']);
Route::post('/adminBOList', [AdminController::class, 'adminBOList']);
Route::post('/adminROList', [AdminController::class, 'adminROList']);

//=================================================================================================//


Route::middleware([AdminMiddleware::class])->group(function () {
    Route::post('logout', [AdminController::class, 'logout']);

    Route::get('/adminList', [AdminController::class, 'adminList']);
    Route::post('/createNewoffice', [AdminController::class, 'createNewoffice']);
    Route::post('/updateUserProfile', [AdminController::class, 'updateUserProfile']);
    Route::post('/deleteAdmin', [AdminController::class, 'deleteAdmin']);
    Route::post('/passwordUpdate', [AdminController::class, 'passwordUpdate']);

//===============================================Pincode/City/Country=========================================//
    Route::prefix('Pincode/')->group(function () {
//===============================================Pincode=====================================================//
        Route::post('/assignPinCode', [GmsController::class, 'assignPinCode']);
        Route::get('/getCityPincode', [GmsController::class, 'getCityPincode']);
        Route::get('/getCountryStateZone', [AdminController::class, 'getCountryStateZone']);
        Route::post('/addCountryStateCity', [AdminController::class, 'addCountryStateCity']);
        Route::get('/viewZone', [AdminController::class, 'viewZone']);
        Route::get('/viewPincodeList', [AdminController::class, 'viewPincodeList']);
        Route::post('/adminEditPincode', [AdminController::class, 'adminEditPincode']);
        Route::post('/adminAddState', [AdminController::class, 'adminAddState']);
        Route::post('/adminEditState', [AdminController::class, 'adminEditState']);
        Route::post('/adminAddCountry', [AdminController::class, 'adminAddCountry']);
        Route::post('/adminEditCountry', [AdminController::class, 'adminEditCountry']);
        Route::post('/adminAddZone', [AdminController::class, 'adminAddZone']);
        Route::post('/adminEditZone', [AdminController::class, 'adminEditZone']);
        Route::get('/adminCountryList', [AdminController::class, 'adminCountryList']);
        Route::get('/adminZoneList', [AdminController::class, 'adminZoneList']);
        Route::post('/adminAddCity', [AdminController::class, 'adminAddCity']);
        Route::post('/adminEditCity', [AdminController::class, 'adminEditCity']);
        Route::get('/adminPincodeCityList', [AdminController::class, 'adminPincodeCityList']);
        Route::get('/adminStateList', [AdminController::class, 'adminStateList']);

        Route::get('/viewAllPincode', function (Request $request) {
            $gmsPincode = GmsPincode::where('is_deleted', 0);
            return $gmsPincode->paginate($request->per_page);
        });
        Route::get('/viewAllCountry', function (Request $request) {
            $gmsCountry = GmsCountries::where('is_deleted', 0)->select('id', 'countries_name', 'countries_iso_code_2');
            return $gmsCountry->paginate($request->per_page);
        });

        Route::get('/viewAllCityList', function (Request $request) {
            $gmsCity = GmsCity::join('gms_state', 'gms_city.state_code', '=', 'gms_state.state_code')->select('gms_city.id','gms_state.state_name', 'gms_state.state_code', 'gms_city.city_code', 'gms_city.city_name', 'gms_city.metro');
            return $gmsCity->paginate($request->per_page);

        });

    });

    //========================================Office===================================================//
    Route::prefix('Office/')->group(function () {
        //=================================================================================================//
        Route::get('/viewAllOfficeList', [AdminController::class, 'viewAllOfficeList']);
        Route::post('/deleteOffice', [AdminController::class, 'deleteOffice']);
        Route::post('/editOfficeStatus', [AdminController::class, 'editOfficeStatus']);
        Route::post('/editoffice', [AdminController::class, 'editoffice']);
        Route::get('/adminGenerateRoTypeList', [AdminController::class, 'adminGenerateRoTypeList']);
        Route::post('/getOfficeUnder', [AdminController::class, 'getOfficeUnder']);

    });

    //=======================================AdminPayment=============================================//
    Route::prefix('Payment/')->group(function () {
        //=================================================================================================//

        Route::get('/adminViewPayment', [AdminController::class, 'adminViewPayment']);
        Route::post('/adminAddPayment', [AdminController::class, 'adminAddPayment']);
        Route::post('/adminEditPayment', [AdminController::class, 'adminEditPayment']);
        Route::post('/adminDeletePayment', [AdminController::class, 'adminDletePayment']);

    });

    //========================================Admin/NonDelReason=============================================//
    Route::prefix('NonDelReason/')->group(function () {
        //=================================================================================================//
        Route::post('/adminAddReason', [AdminController::class, 'adminAddReason']);
        Route::get('/viewAllReasonList', [AdminController::class, 'viewAllReasonList']);
        Route::post('/deleteReason', [AdminController::class, 'deleteReason']);
        Route::post('/adminEditReason', [AdminController::class, 'adminEditReason']);
    });
    //========================================Admin/Customer===================================================//
    Route::prefix('Customer/')->group(function () {
        //=======================================================================================================//
        Route::get('/viewAllCustomerList', [AdminController::class, 'viewAllCustomerList']);
        //Route::post('/deleteCustomer',[AdminController::class, 'deleteReason']);
        Route::post('/editCustomerApprovedStatus', [AdminController::class, 'editCustomerApprovedStatus']);
        Route::post('/searchAdminCustomer', [AdminController::class, 'searchAdminCustomer']);
        Route::get('stashCustomer', [AdminController::class, 'stashCustomer']);
    });

    //========================================Complaint===================================================//
    Route::prefix('Complaints/')->group(function () {
        //====================================================================================================//
        Route::post('/addCnnoComplaints', [AdminController::class, 'addCnnoComplaints']);
        Route::post('/editComplaintStatus', [AdminController::class, 'editComplaintStatus']);
        Route::match(['get', 'post'], '/viewAllAdminComplaints', [AdminController::class, 'viewAllAdminComplaints']);
        Route::post('/adminComplainReply', [AdminController::class, 'adminComplainReply']);
        Route::post('/adminViewComplainReply', [AdminController::class, 'adminViewComplainReply']);
        Route::post('/adminEditComplaints', [AdminController::class, 'adminEditComplaints']);
        Route::post('/complaintDelete', [AdminController::class, 'complaintDelete']);
        Route::post('/editWeight', [AdminController::class, 'editWeight']);

    });

    //========================================BookCategory===================================================//
    Route::prefix('BookCategory/')->group(function () {
        //======================================================================================================//
        Route::post('/addBookCategory', [AdminController::class, 'addBookCategory']);
        Route::get('/viewAllBookCategory', [AdminController::class, 'viewAllBookCategory']);
        Route::post('/editBookCategory', [AdminController::class, 'editBookCategory']);
        Route::post('/deleteBookCat', [AdminController::class, 'deleteBookCat']);
        Route::get('/catType', [AdminController::class, 'catType']);
        Route::get('/adminPurchaseList', [AdminController::class, 'adminPurchaseList']);

    });

    //========================================BookCatRange===================================================//
    Route::prefix('BookCatRange/')->group(function () {
        //========================================================================================================//
        Route::post('/addBookCatRange', [AdminController::class, 'addBookCatRange']);
        Route::get('/viewAllBookCatRange', [AdminController::class, 'viewAllBookCatRange']);
        Route::post('/editBookCatRange', [AdminController::class, 'editBookCatRange']);
        Route::post('/deleteBookCatRange', [AdminController::class, 'deleteBookCatRange']);

    });

    //========================================BookPurOrder===================================================//
    Route::prefix('BookPurOrder/')->group(function () {
        //========================================================================================================//
        Route::post('/addBookPurOrder', [AdminController::class, 'addBookPurOrder']);
        Route::get('/viewAllBookPurOrder', [AdminController::class, 'viewAllBookPurOrder']);
        Route::post('/deleteBookPurOrder', [AdminController::class, 'deleteBookPurOrder']);

    });

    //========================================BookPurOrder===================================================//
    Route::prefix('BookInward/')->group(function () {
        //========================================================================================================//
        Route::post('/addBookInward', [AdminController::class, 'addBookInward']);
        Route::get('/viewAllBookInward', [AdminController::class, 'viewAllBookInward']);
        Route::post('/deleteBookInward', [AdminController::class, 'deleteBookInward']);
        Route::post('/editBookInward', [AdminController::class, 'editBookInward']);
        Route::get('/adminPurchaseItemList', [AdminController::class, 'adminPurchaseItemList']);
        Route::post('/adminPurchaseItem', [AdminController::class, 'adminPurchaseItem']);

    });


    //========================================BookVendor==========================================================//
    Route::prefix('BookVendor/')->group(function () {
        //============================================================================================================//
        Route::post('/addBookVendor', [AdminController::class, 'addBookVendor']);
        Route::get('/viewAllBookVendor', [AdminController::class, 'viewAllBookVendor']);
        Route::post('/deleteBookVendor', [AdminController::class, 'deleteBookVendor']);
        Route::post('/viewVendorDetails', [AdminController::class, 'viewVendorDetails']);
        Route::post('/editBookVendor', [AdminController::class, 'editBookVendor']);
        Route::get('/adminVendorList', [AdminController::class, 'adminVendorList']);


    });

    //========================================BookRoIssue========================================================//
    Route::prefix('BookRoIssue')->group(function () {
        //==========================================================================================================//
        Route::post('/addBookRoIssue', [AdminController::class, 'addBookRoIssue']);
        Route::get('/viewBookRoIssue', [AdminController::class, 'viewBookRoIssue']);
        Route::post('/editBookRoIssueStatus', [AdminController::class, 'editBookRoIssueStatus']);

    });

    //=======================================BookTransfer=====================================================//
    Route::prefix('BookTransfer')->group(function () {
        //=========================================================================================================//
        Route::post('/addBookTransfer', [AdminController::class, 'addBookTransfer']);
        Route::get('/viewBookTransfer', [AdminController::class, 'viewBookTransfer']);
        Route::get('/viewBookReturn', [AdminController::class, 'viewBookReturn']);
        Route::post('/editBookRoTransferStatus', [AdminController::class, 'editBookRoTransferStatus']);
    });

    //=====================================BookRelease=======================================================//
    Route::prefix('BookRelease')->group(function () {
        //========================================================================================================//
        Route::post('/addBookRelease', [AdminController::class, 'addBookRelease']);
        Route::get('/viewBookRelease', [AdminController::class, 'viewBookRelease']);
        Route::post('/deleteBookRelease', [AdminController::class, 'deleteBookRelease']);

    });

    //=====================================BookControl=====================================================//
    Route::prefix('CnnoBookingStock')->group(function () {
        //======================================================================================================//
        Route::get('/viewCnnoBookingStock', [AdminController::class, 'viewCnnoBookingStock']);


    });

    Route::post('/AdminAdvanceSearchIpmf', [AdminController::class, 'AdminAdvanceSearchIpmf']);
    Route::post('/AdminViewIpmf', [AdminController::class, 'AdminViewIpmf']);
    Route::post('/AdminViewTableIpmf', [AdminController::class, 'AdminViewTableIpmf']);

    //=====================================GenRoBill=========================================================//
    Route::get('/viewAdminGenerateRoBill', [AdminController::class, 'viewAdminGenerateRoBill']);
    //========================================================================================================//


    //=====================================BookControl=====================================================//
    Route::prefix('BookControl')->group(function () {
        //======================================================================================================//
        Route::get('/viewBookControl', [AdminController::class, 'viewBookControl']);
        Route::post('/deleteBookControl', [AdminController::class, 'deleteBookControl']);

    });

    //==========================================Department=========================================================//
    Route::prefix('Department')->group(function () {
        //=======================================================================================================//
        Route::post('/addDepartment', [AdminController::class, 'addDepartment']);
        Route::get('/viewDepartment', [AdminController::class, 'viewDepartment']);
        Route::post('/deleteDepartment', [AdminController::class, 'deleteDepartment']);
        Route::post('/editDepartment', [AdminController::class, 'editDepartment']);
        Route::get('/viewDepartmentList', [AdminController::class, 'viewDepartmentList']);
        
    });

    //==========================================Designation=========================================================//
    Route::prefix('Designation')->group(function () {
        //=======================================================================================================//
        Route::post('/addDesignation', [AdminController::class, 'addDesignation']);
        Route::get('/viewDesignation', [AdminController::class, 'viewDesignation']);
        Route::post('/deleteDesignation', [AdminController::class, 'deleteDesignation']);
        Route::post('/editDesignation', [AdminController::class, 'editDesignation']);
        Route::get('/viewDesignationList', [AdminController::class, 'viewDesignationList']);
    });

    //==========================================Employee-Type================================================//
    Route::prefix('EmpType')->group(function () {
        //=======================================================================================================//
        Route::post('/addEmpType', [AdminController::class, 'addEmpType']);
        Route::get('/viewEmpType', [AdminController::class, 'viewEmpType']);
        Route::post('/deleteEmpType', [AdminController::class, 'deleteEmpType']);
        Route::post('/editEmpType', [AdminController::class, 'editEmpType']);
        Route::get('/adminEmpTypeList', [AdminController::class, 'adminEmpTypeList']);
    });

    //==========================================Employee-Type================================================//
    Route::prefix('Employee')->group(function () {
        //=======================================================================================================//
        Route::post('/addAdminEmployee', [AdminController::class, 'addAdminEmployee']);
        Route::post('/viewEmployee', [AdminController::class, 'viewEmployee']);
        Route::post('/deleteAdminEmployee', [AdminController::class, 'deleteAdminEmployee']);
        Route::post('/editAdminEmployee', [AdminController::class, 'editAdminEmployee']);
        Route::post('/editEmployeeAdminStatus', [AdminController::class, 'editEmployeeAdminStatus']);
        Route::post('/getEmpCode', [AdminController::class, 'getEmpCode']);

        
    });

    //=========================================Login==========================================================//
    Route::prefix('Login')->group(function () {
        //=======================================================================================================//
        Route::post('/addLogin', [AdminController::class, 'addLogin']);
        Route::post('/viewLogin', [AdminController::class, 'viewLogin']);
        Route::post('/deleteLogin', [AdminController::class, 'deleteLogin']);
        Route::post('/editLogin', [AdminController::class, 'editLogin']);

    });

    //==========================================Tax-Type================================================//
    Route::prefix('TaxType')->group(function () {
        //=======================================================================================================//
        Route::post('/addTaxType', [AdminController::class, 'addTaxType']);
        Route::get('/viewTaxType', [AdminController::class, 'viewTaxType']);
        Route::post('/deleteTaxType', [AdminController::class, 'deleteTaxType']);
        Route::post('/editTaxType', [AdminController::class, 'editTaxType']);
        Route::post('/editTaxTypeStatus', [AdminController::class, 'editTaxTypeStatus']);
        Route::get('/viewTaxTypeHistory', [AdminController::class, 'viewTaxTypeHistory']);
    });
    //==========================================Fuel Charges================================================//
    Route::prefix('FuelCharges')->group(function () {
        //=======================================================================================================//
        Route::post('/addFuelCharges', [AdminController::class, 'addFuelCharges']);
        Route::get('/viewFuelCharges', [AdminController::class, 'viewFuelCharges']);
        Route::post('/deleteFuelCharges', [AdminController::class, 'deleteFuelCharges']);
        Route::post('/editFuelCharges', [AdminController::class, 'editFuelCharges']);
        Route::get('/viewFuelDefaultCharges', [AdminController::class, 'viewFuelDefaultCharges']);
        Route::post('/adminAddFuelDefaultCharges', [AdminController::class, 'adminAddFuelDefaultCharges']);
    });

    //==========================================Fuel Charges================================================//
    Route::prefix('zoneRate')->group(function () {
        //=======================================================================================================//
        Route::post('/addZoneRateCard', [AdminController::class, 'addZoneRateCard']);
        Route::post('/addRate', [AdminController::class, 'addRate']);

    });

    //==========================================Rate Master================================================//
    Route::prefix('RateMaster')->group(function () {
        //=======================================================================================================//
        Route::post('/addRateMaster', [AdminController::class, 'addRateMaster']);
        Route::get('/viewRateMaster', [AdminController::class, 'viewRateMaster']);
        Route::post('/editRateMaster', [AdminController::class, 'editRateMaster']);
    });
    //==========================================Gst================================================//
    Route::prefix('Gst')->group(function () {
        //=======================================================================================================//
        Route::post('/addGst', [AdminController::class, 'addGst']);
        Route::post('/editGst', [AdminController::class, 'editGst']);
        Route::get('/viewGST', [AdminController::class, 'viewGST']);
    });

    //==========================================Pincode================================================//
    Route::prefix('Assign-Pincode')->group(function () {
        //=======================================================================================================//
        Route::post('/addPincode', [AdminController::class, 'addPincode']);
        Route::post('/editPincode', [AdminController::class, 'editPincode']);
        Route::post('/pincodeStatus', [AdminController::class, 'editPincodeStatus']);
    });

    //==========================================Pincode================================================//
    Route::prefix('GenInToCus')->group(function () {
        //=======================================================================================================//
        Route::post('/deleteInvoice', [AdminController::class, 'deleteInvoice']);
        Route::get('/viewInvoice', [AdminController::class, 'viewInvoice']);
    });

    //==========================================Api Tracking================================================//
    Route::prefix('ApiTracking')->group(function () {
        //=======================================================================================================//
        Route::post('/addApiTracking', [AdminController::class, 'addApiTracking']);
        Route::get('/viewApiTracking', [AdminController::class, 'viewApiTracking']);
    });
    //==========================================Alerts Config================================================//
    Route::prefix('Alerts-Config')->group(function () {
        //=======================================================================================================//
        Route::post('/addAlertsConfig', [AdminController::class, 'addAlertsConfig']);
        Route::post('/editAlertsConfig', [AdminController::class, 'editAlertsConfig']);
    });

    //=================================================Report===============================================//
    Route::prefix('AdminReports/')->group(function () {
        //=========================================================================================================//

        Route::post('/bookingAdminReport', [ReportController::class, 'bookingReport']);
        Route::post('/codTopayAdminReport', [ReportController::class, 'codTopayReport']);
        Route::post('/outgoingAdminReport', [ReportController::class, 'outgoingReport']);
        Route::post('/incommingAdminReport', [ReportController::class, 'incommingReport']);
        Route::post('/drsAdminReport', [ReportController::class, 'drsReport']);
        Route::post('/drsAdminReportCust', [ReportController::class, 'drsReportCust']);
        Route::post('/coloaderAdminReport', [ReportController::class, 'coloaderReport']);
        Route::post('/empAdminReport', [ReportController::class, 'empReport']);

    });

    //===============================================CutofTime=========================================//
    Route::prefix('CutofTime/')->group(function () {
        //===============================================Pincode=====================================================//
        Route::post('/addCutofTime', [AdminController::class, 'addCutofTime']);
        Route::get('/viewCutofTime', [AdminController::class, 'viewCutofTime']);
    });

    Route::post('/AdminConsignmentTracking', [AdminController::class, 'AdminConsignmentTracking']);


});

//=======================================End Admin Route==========================================================//
//===============================================================================================================//
//===============================================================================================================//
//===============================================================================================================//
//=========//===========//===========//==========//============//==========//===========//============//====//====//


//=======================================BO/EMP ROUTES LIST=====================================================//

Route::middleware([UserCheck::class])->group(function () {
    Route::post('/getMyOfficeProfile', [AdminController::class, 'getMyOfficeProfile']);
    Route::post('/changePassword', [AdminController::class, 'changePassword']);
    Route::post('logout', [AdminController::class, 'logout']);
    Route::post('/updateUserProfile', [AdminController::class, 'updateUserProfile']);
    Route::post('/passwordUpdate', [AdminController::class, 'passwordUpdate']);
    Route::get('/totalCount', [DashBoardController::class, 'totalCount']);
    Route::post('/getAgent', [GmsController::class, 'getAgent']);
   

    //=====================================Franchisee-Customer================================================//
    Route::prefix('FranchiseeCustomer/')->group(function () {
        //===========================================FranchiseeRoute==========================================//
        Route::post('/addFranchiseeCus', [CustomerController::class, 'addFranchiseeCus']);
        Route::post('/editFranchiseeCus', [CustomerController::class, 'editFranchiseeCus']);
        Route::post('/deleteFraCustomer', [CustomerController::class, 'deleteFraCustomer']);
        Route::get('/viewFranchiseeCus', [CustomerController::class, 'viewFranchiseeCus']);
        Route::get('/serachFr', [CustomerController::class, 'serachFr']);
        Route::get('/viewFranchiseeDetails', [CustomerController::class, 'viewFranchiseeDetails']);
        Route::post('/viewAllFranchisee', [CustomerController::class, 'viewAllFranchisee']);
        Route::post('/viewDetailsFraCus', [CustomerController::class, 'viewDetailsFraCus']);


    });
    //===========================================City========================================================//
    Route::get('/viewAllCity', [GmsController::class, 'viewAllCity']);
    //=======================================================================================================//

    //=====================================Customer==========================================================//
    Route::prefix('Customer/')->group(function () {
        //======================================Customer-Routes==============================================//
        Route::post('/addCustomer', [CustomerController::class, 'addCustomer']);
        Route::post('/addRoCustomer2', [CustomerController::class, 'addRoCustomer2']);
        Route::post('/editCustomer', [CustomerController::class, 'editCustomer']);
        Route::post('/deleteCustomer', [CustomerController::class, 'deleteCustomer']);
        Route::post('/viewCustomer', [CustomerController::class, 'viewCustomer']);
        Route::post('/addCusContact', [CustomerController::class, 'addCusContact']);
        Route::get('/viewAllCustomer', [CustomerController::class, 'viewAllCustomer']);
        Route::get('/customerType', [CustomerController::class, 'customerType']);
        Route::get('/custCode', [CustomerController::class, 'custCode']);
        Route::post('/getCustByCustType', [CustomerController::class, 'getCustByCustType']);
        Route::post('/getCusPinCode',[CustomerController::class, 'getCusPinCode']);
        Route::post('/custReport', [CustomerController::class, 'custReport']);
        Route::match(['get', 'post'], '/viewGenerateRoBill', [AdminController::class, 'viewGenerateRoBill']);
        Route::get('/exportCustomer', [AdminController::class, 'exportCustomer']);
        Route::post('/viewAllRoColoader', [CustomerController::class, 'viewAllRoColoader']);
        Route::post('/deleteRoColoader', [CustomerController::class, 'deleteRoColoader']);
        Route::post('/getColoaderDetails',[CustomerController::class, 'getColoaderDetails']);
        Route::post('/editColoaderDetails',[CustomerController::class, 'editColoaderDetails']);

    });

    //===========================================Pod=======================================================//
    Route::get('/viewScanUpdate', [PodController::class, 'viewScanUpdate']);
    Route::post('/manualUpdate', [PodController::class, 'podUpdate']);
    Route::post('/deletePodUpdate', [PodController::class, 'deletePodUpdate']);
    Route::post('/podSearch', [PodController::class, 'podSearch']);
    Route::get('/allCnno', [PodController::class, 'allCnno']);
    Route::post('/bulkUpdate', [PodController::class, 'bulkUpdate']);

    //====================================================================================================///

    //================================================Book==================================================//
    Route::prefix('Book/')->group(function () {
        //=====================================================================================================//
        Route::post('/addCusBookIssue', [BookController::class, 'addCusBookIssue']);
        Route::get('/viewAllCustBookIssue', [BookController::class, 'viewAllCustBookIssue']);
        Route::post('/viewCustBookIssue', [BookController::class, 'viewCustBookIssue']);
        //Transfer Routes//
        Route::get('/getBoSinDropDown', [CustomerController::class, 'getBoSinDropDown']);
        Route::post('/getBoSinDetails', [CustomerController::class, 'getBoSinDetails']);

        Route::get('/viewAllInBookBoTransfer', function (Request $request) {
            $gmsAllInBookBoTransfer = GmsBookBoTransfer::where('is_deleted', 0)->select('id', 'iss_type', 'office_ro', 'cnno_start', 'cnno_end', 'description', 'entry_date', 'status')->orderBy('id', 'DESC');
            return $gmsAllInBookBoTransfer->paginate($request->per_page);
        });

        Route::get('/viewAllOutBookBoTransfer', function (Request $request) {
            $gmsAllOutBookBoTransfer = GmsBookBoTransfer::where('is_deleted', 0)->select('id', 'iss_type', 'office_ro', 'cnno_start', 'cnno_end', 'description', 'entry_date', 'status')->orderBy('id', 'DESC');
            return $gmsAllOutBookBoTransfer->paginate($request->per_page);
        });

        Route::get('/getCustSinDropDown', [CustomerController::class, 'getCustSinDropDown']);
        Route::post('/getCustSinDetails', [CustomerController::class, 'getCustSinDetails']);
        Route::post('/addBookBoTransfer', [BookController::class, 'addBookBoTransfer']);

        Route::post('/addBookBoReturn', [BookController::class, 'addBookBoReturn']);
        Route::get('/viewAllBookBoReturn', [BookController::class, 'viewAllBookBoReturn']);
        Route::post('/deleteCustBookReturn', [BookController::class, 'deleteCustBookReturn']);
        Route::get('/viewBookCustRoReturn', [BookController::class, 'viewBookCustRoReturn']);

        Route::post('/addBookCustRoReturn', [BookController::class, 'addBookCustRoReturn']);

        Route::post('/editCusBookIssue', [BookController::class, 'editCusBookIssue']);
        Route::get('/viewAllStockIn', [BookController::class, 'viewAllStockIn']);
        Route::get('/viewAllCnnoCustlist', [BookController::class, 'viewAllCnnoCustlist']);
        Route::get('/cnnoBookingStockView', [CustomerController::class, 'cnnoBookingStockView']);
        Route::get('/onlyBoOffice', [BookController::class, 'onlyBoOffice']);

        Route::get('/stockInRo',[BookController::class, 'stockInRo']);
        Route::get('/stockSearchRo', [BookController::class, 'stockSearchRo']);

    });

    //=====================================Incoming-Details===============================================//
    Route::prefix('Incoming/')->group(function () {
        //===================================================================================================//
        Route::post('/getCnnoDetails', [MfController::class, 'getCnnoDetails']);
        Route::post('/getMfDetails', [MfController::class, 'getMfDetails']);
        Route::match(['get', 'post'], '/addInComingPacketMf', [MfController::class, 'addInComingPacketMf']);
        Route::match(['get', 'post'], '/addInComingMasterMf', [MfController::class, 'addInComingMasterMf']);
        Route::match(['get', 'post'], '/allIncomingPcMf', [MfController::class, 'allIncomingPcMf']);
        Route::post('/viewIncomingPcMfDetails', [MfController::class, 'viewIncomingPcMfDetails']);
        Route::match(['get', 'post'], '/ipmfPending', [MfController::class, 'ipmfPending']);
        Route::post('/advanceSearch', [GmsController::class, 'advanceSearchIpmf']);
        Route::post('/advanceSearchInMaster', [MfController::class, 'advanceSearchInMaster']);
        Route::post('/empPmfDelete', [MfController::class, 'empPmfDelete']);
        Route::post('/generateMfNo',[MfController::class, 'generateMfNo']);
        Route::post('/generateOPMfNo',[MfController::class, 'generateOPMfNo']);
         Route::post('/generateDmfNo',[MfController::class, 'generateDmfNo']);
        Route::post('/getOutGoingMasterMFDetails',[MfController::class, 'getOutGoingMasterMFDetails']);
        Route::match(['get', 'post'], '/viewIncomingMasterManifest', [MfController::class, 'viewIncomingMasterManifest']);
        Route::get('/viewAllExr', function (Request $request) {
            return GmsExrDtls::where('is_deleted', 0)->get(['exr_cnno', 'exr_date', 'exr_time', 'exr_origin_branch', 'exr_receieved_emp']);


        });
    });

    //======================================OutGoing-Details ========================================================//
    Route::prefix('OutGoing/')->group(function () {
        //================================================================================================================//
        Route::match(['get', 'post'], '/opmfPending', [MfController::class, 'opmfPending']);
        Route::match(['get', 'post'], '/opmfCompleted', [MfController::class, 'opmfCompleted']);
        Route::match(['get', 'post'], '/opmfMisroute', [MfController::class, 'opmfMisroute']);
        Route::match(['get', 'post'], '/viewOpmf', [MfController::class, 'viewOpmf']);
        Route::post('/viewOpmfDetails', [MfController::class, 'viewOpmfDetails']);
        Route::get('/advanceSearchOpmf', [GmsController::class, 'advanceSearchOpmf']);
        Route::post('/advanceSearchOutMaster', [MfController::class, 'advanceSearchOutMaster']);
        Route::match(['get', 'post'], '/viewAllOutMasterManifest', [MfController::class, 'viewAllOutMasterManifest']);
        Route::post('/addOutGoingPcMf', [MfController::class, 'addOutGoingPcMf']);
        Route::post('/addOutGoingMasterMf', [MfController::class, 'addOutGoingMasterMf']);
        Route::get('/coloaderCustomers', [GmsController::class, 'coloaderCustomers']);
        Route::get('/getOpmfDetailsForColoaders', [GmsController::class, 'getOpmfDetailsForColoaders']);
        Route::post('/coloaderOrderAdd', [GmsController::class, 'coloaderOrderAdd']);
        Route::match(['get', 'post'], '/viewColoadersDetails', [GmsController::class, 'viewColoadersDetails']);
        Route::get('/advanceSearchColoader', [GmsController::class, 'advanceSearchColoader']);


    });

    //=======================================Delivery-Details====================================================//
    Route::prefix('/Delivery')->group(function () {
        //==============================================================================================================//
        Route::match(['get', 'post'], '/allDeliveryMf', [MfController::class, 'allDeliveryMf']);
        Route::post('/viewMfNormal', [MfController::class, 'viewMfNormal']);
        Route::post('/viewDetailsPrint', [MfController::class, 'viewDetailsPrint']);
        Route::post('/searchDpmf', [GmsController::class, 'searchDpmf']);
        Route::post('/deleteDmfCnno', [MfController::class, 'deleteDmfCnno']);
        Route::post('/getDmfCnnoDetails',[MfController::class, 'getDmfCnnoDetails']);

        //=======================================Delivery Update===========================================================//
        Route::match(['get', 'post'], '/viewAllDeliveryUpdate', [MfController::class, 'viewAllDeliveryUpdate']);
        Route::post('/viewDeliveryDetails', [MfController::class, 'viewDeliveryDetails']);
        Route::get('/advancedSearchDpmfUpdate', [GmsController::class, 'advancedSearchDpmfUpdate']);
        Route::post('/addDeliveryMf', [MfController::class, 'addDeliveryMf']);
        Route::post('/getDmfDetails',[MfController::class, 'getDmfDetails']);
        Route::post('/addDeliveryUpdate', [MfController::class, 'addDeliveryUpdate']);
        Route::get('/nonDeliveryDropDown',[MfController::class, 'nonDeliveryDropDown']);

        //==============================================================================================================//

    });
    //==========================================RTO=============================================================//
    Route::prefix('/Rto')->group(function () {
        //=================================================================================================================//
        Route::get('/rtoCnno', [InvoiceController::class, 'rtoCnno']);
        Route::post('/addRto', [InvoiceController::class, 'addRto']);
        Route::post('/conIpmfToRto', [InvoiceController::class, 'conIpmfToRto']);

    });

    //======================================SearchPincode=========================================================//
    Route::prefix('/searchPincode')->group(function () {
        //===============================================================================================================//
        Route::post('/getCityPincode', [GmsController::class, 'getCityPincode']);
        Route::post('assignPinCodeToBranch', [GmsController::class, 'assignPinCodeToBranch']);

    });
    //============================================Payment======================================================//
    Route::prefix('Payment/')->group(function () {
        //=========================================================================================================//
        Route::post('/addPayment', [GmsController::class, 'addPayment']);
        Route::get('/viewAllPayment', [GmsController::class, 'viewAllPayment']);
        Route::post('/viewPayment', [GmsController::class, 'viewPayment']);
        Route::post('/editPayment', [GmsController::class, 'editPayment']);
        Route::post('/deletePayment', [GmsController::class, 'deletePayment']);
    });

    //============================================Booking-Details==============================================//
    Route::prefix('Booking/')->group(function () {
        //========================================================================================================//
        Route::post('/addBookDtls', [BookController::class, 'addBookDtls']);
        Route::post('/editBookingDetails', [BookController::class, 'editBookingDetails']);
        Route::match(['get', 'post'], '/viewBookingDetails', [BookController::class, 'viewBookingDetails']);
        Route::post('/deleteBookingDetails', [BookController::class, 'deleteBookingDetails']);
        Route::match(['get', 'post'], '/viewAllBookingDetails', [BookController::class, 'viewAllBookingDetails']);
        Route::post('searchBookingDetails', [BookController::class, 'searchBookingDetails']);
        Route::post('/addCoMail', [MfController::class, 'addCoMail']);
        Route::match(['get', 'post'], '/viewAllCoMailList', [MfController::class, 'viewAllCoMailList']);
        Route::post('/editManifestDate', [InvoiceController::class, 'editManifestDate']);
        Route::post('/loadAlert' ,[MfController::class, 'loadAlert']);
    });

    //=============================================Employee===================================================//
    Route::prefix('Employee/')->group(function () {
        //========================================================================================================//
        Route::post('/addEmployee', [EmpController::class, 'addEmployee']);
        Route::post('/searchEmp', [EmpController::class, 'searchEmp']);
        Route::post('/viewEmployeeId', [EmpController::class, 'viewEmployeeId']);
        Route::post('/viewAllEmployee', [EmpController::class, 'viewAllEmployee']);
        Route::get('/empDetailsExport', [EmpController::class, 'empDetailsExport']);
        Route::get('/empDetailsPdfExport', [EmpController::class, 'empDetailsPdfExport']);


    });

    //==============================================Invoice===============================================//
    Route::prefix('Invoice/')->group(function () {
        //==============================================Invoice===============================================//
        Route::post('/addInvoice', [InvoiceController::class, 'addInvoice']);
        Route::post('/viewInvoice', [InvoiceController::class, 'viewInvoice']);
        Route::get('/viewSearchInvoice', [InvoiceController::class, 'viewSearchInvoice']);
        Route::post('/viewInvoiceScPrint',[InvoiceController::class, 'viewInvoiceScPrint']);
        Route::post('/viewAdditional',[InvoiceController::class, 'viewAdditional']);
        Route::post('/viewSfAdditional',[InvoiceController::class, 'viewSfAdditional']);
        // Route::post('/viewAllInvoice', [InvoiceController::class, 'viewAllInvoice']);
        Route::post('/deleteInvoice', [InvoiceController::class, 'deleteInvoice']);
        Route::post('/viewInvoiceSf', [InvoiceController::class, 'viewInvoiceSf']);
        Route::post('/viewSalesRegister', [InvoiceController::class, 'viewSalesRegister']);
        Route::post('/viewSearchInvoiceSf', [InvoiceController::class, 'viewSearchInvoiceSf']);
        //==============================================CusInvoice===========================================//
        Route::post('/addCusInvoice', [InvoiceController::class, 'addCusInvoice']);
        Route::post('/viewCusInvoice', [InvoiceController::class, 'viewCusInvoice']);
        Route::post('/deleteCusInvoice', [InvoiceController::class, 'deleteCusInvoice']);
        Route::get('/viewAllCusInvoice', function (Request $request) {
            $gmsAllCusInvoice = GmsInvoiceCust::where('is_deleted', 0)->orderBy('id', 'DESC');
            return $gmsAllCusInvoice->paginate($request->per_page);
        });

    });

    //============================================Alert=======================================================//
    Route::prefix('Alert/')->group(function () {
        //========================================================================================================//
        Route::post('/addAlert', [GmsController::class, 'addAlert']);
        Route::post('/viewAlert', [GmsController::class, 'viewAlert']);
        Route::post('/deleteAlert', [GmsController::class, 'deleteAlert']);
        Route::post('/editAlert', [GmsController::class, 'editAlert']);
        Route::get('/viewAllAlert', function (Request $request) {
            $gmsAlert = GmsAlert::select('id', 'name', 'email1', 'email2', 'mobile1', 'mobile2', 'email_status', 'sms_status', 'alert_type', 'entry_date')->where('is_deleted', 0)->orderBy('id', 'DESC');
            return $gmsAlert->paginate($request->per_page);
        });
    });

    //=================================================Report=====================================================//
    Route::prefix('Reports/')->group(function () {
        //=========================================================================================================//

        Route::post('/bookingReport', [ReportController::class, 'bookingReport']);
        Route::post('/codTopayReport', [ReportController::class, 'codTopayReport']);
        Route::post('/outGoingReport', [ReportController::class, 'outGoingReport']);
        Route::post('/inComingReport', [ReportController::class, 'inComingReport']);
        Route::post('/drsReport', [ReportController::class, 'drsReport']);
        Route::post('/drsNoInfo', [ReportController::class, 'drsNoInfo']);
        Route::post('/dmfCustomerWise', [ReportController::class, 'dmfCustomerWise']);
        Route::post('/coloaderReport', [ReportController::class, 'coloaderReport']);
        Route::post('/empReport', [ReportController::class, 'empReport']);
        Route::post('/heldUpCnno', [ReportController::class, 'heldUpCnno']);
        Route::post('/heldUpBeta', [ReportController::class, 'heldUpBeta']);
        Route::match(['get', 'post'], '/relationship', [ReportController::class, 'relationship']);
        Route::match(['get', 'post'], '/relationshipOffice', [ReportController::class, 'relationshipOffice']);
    });

    //===============================================Complaints=============================================//
    Route::prefix('Complaints/')->group(function () {
        //===================================================================================================//
        Route::post('/viewComplaints', [GmsController::class, 'viewComplaints']);
        Route::post('/totalComplaints', [GmsController::class, 'totalComplaints']);
        Route::get('/viewAllComplaints', [GmsController::class, 'viewAllComplaints']);
    });

    //============================================= Tracking================================================//
    Route::post('/tracking', [GmsController::class, 'tracking']);


});
//======================================End Customer Routes===================================================//
//============================================================================================================//
//============================================================================================================//
//============================================================================================================//
//============================================================================================================//


//================================================RoBook=============================================//

Route::post('/viewRoBook', [BookController::class, 'viewRoBook']);
Route::post('/deleteRoBook', [BookController::class, 'deleteRoBook']);
Route::get('/viewAllRoBook', function (Request $request) {
    $gmsAllRoBook = GmsBookRoIssue::where('is_deleted', 0);
    return $gmsAllRoBook->paginate($request->per_page);
});
//===============================================EpodGen=============================================//


Route::get('/getAllEpodGen', function (Request $request) {
    $gmsAllEpodGen = GmsBookEpodGenerate::where('is_deleted', 0);
    return $gmsAllEpodGen->paginate($request->per_page);
});
//============================================BoBookTransfer==========================================//



Route::get('/viewAllBookBoTransfer', function (Request $request) {
    $gmsAllBookBoTransfer = GmsBookBoTransfer::where('is_deleted', 0)->select('iss_type', 'office_ro', 'cnno_start', 'cnno_end', 'description', 'entry_date', 'status')->orderBy('id', 'DESC');
    return $gmsAllBookBoTransfer->paginate($request->per_page);
});

//============================================BoBookIssue=============================================//

Route::post('/boIssueStatus', [BookController::class, 'boIssueStatus']);
Route::post('/boIssueStatusStart', [BookController::class, 'boIssueStatusStart']);

Route::get('/viewAllBoBookIssue', function (Request $request) {
    $gmsAllBoBookIssue = GmsBookBoissue::where('is_deleted', 0)->select('office_type', 'cnno_start', 'cnno_end', 'entry_date', 'qauantity')->where('is_deleted', 0);
    return $gmsAllBoBookIssue->paginate($request->per_page);
});
//==============================================RoBookIssue============================================//


Route::get('/viewAllRoTransfer', function (Request $request) {
    $gmsAllRoTransfer = GmsBookRoTransfer::select('tranfer_type', 'description', 'cnno_start', 'cnno_end', 'entry_date', 'recieved_date')->where('is_deleted', 0);
    return $gmsAllRoTransfer->paginate($request->per_page);
});

//================================================BookBlock==============================================//
Route::post('/addBookBlock', [GmsController::class, 'addBookBlock']);
Route::post('/viewBookBlock', [GmsController::class, 'viewBookBlock']);
Route::post('/deleteBlockBook', [GmsController::class, 'deleteBlockBook']);
Route::get('/viewAllBookBlock', function (Request $request) {
    $gmsAllBookBlock = GmsBookBlock::where('is_deleted', 0);
    return $gmsAllBookBlock->paginate($request->per_page);
});




//================================================Manifest==========================================//

//================================================Manifest==========================================//

    Route::get('/viewAllManifest', function (Request $request) {
        $gmsAllManifest = GmsMfDtls::select('mf_type', 'mf_emp_code', 'mf_dest_type', 'mf_wt', 'mf_pcs', 'mf_entry_date', 'mf_cd_no')->where('is_deleted', 0);
        return $gmsAllManifest->paginate($request->per_page);
    });

   


Route::post('/viewAllRtoDetails', [InvoiceController::class, 'viewAllRtoDetails']);


//============================================RateCard===========================================//
Route::prefix('RateCard/')->group(function () {
//============================================RateCard===========================================//
    Route::post('/addRateCard', [RateController::class, 'addRateCard']);
    Route::post('/viewRateCard', [RateController::class, 'viewRateCard']);
    Route::post('/getRateCard', [RateController::class, 'getRateCard']);
});

//==============================================RateCode===============================================//
Route::prefix('RateCode/')->group(function () {
//==============================================RateCode===============================================//
    Route::post('/addRateCode', [RateController::class, 'addRateCode']);
    Route::post('/getRateCode', [RateController::class, 'getRateCode']);
    Route::get('/getAllRateCode', function () {
        return GmsRateCode::where('is_deleted', 0)->paginate(5);
    });
});

//===================================================================================================//
Route::post('/addServiceFdc', [RateController::class, 'addServiceFdc']);

//========================================================================================================//
Route::post('/addNews', [GmsController::class, 'addNews']);
//=======================================================================================================//
Route::post('/cnnoViewBlock', [GmsController::class, 'cnnoViewBlock']);

Route::get('/cnnoAllViewBlock', function (Request $request) {
    $gmsAllViewBlock = GmsCnnoStock::all();
    return $gmsAllViewBlock->paginate($request->per_page);
});

Route::post('/addDiscount', [GmsController::class, 'addDiscount']);

//==============================================Amdro==================================================//
Route::post('/viewAmdro', [GmsController::class, 'viewAmdro']);
Route::post('/addMasterAmdro', [GmsController::class, 'addMasterAmdro']);
Route::post('/deleteMasterAmdro', [GmsController::class, 'deleteMasterAmdro']);
Route::post('/statusAmdro', [GmsController::class, 'statusAmdro']);


Route::get('/viewAllAmdro', function (Request $request) {
    $gmsAllAmdro = GmsRateMasterAmdro::where('is_deleted', 0);
    return $gmsAllAmdro->paginate($request->per_page);
});


//       Ekta api           //

Route::post('/addOpmf', [GmsController::class, 'addOpmf']);

Route::match(['get', 'post'], '/viewDpmf', [GmsController::class, 'viewDpmf']);
Route::get('/viewAllCnnoStock', function () {
    return GmsCnnoStock::all()->paginate(5);
});


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
