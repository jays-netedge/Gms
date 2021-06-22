<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Traits\AdminTrait;
use App\Models\GmsAlertConfig;
use App\Models\GmsApi;
use App\Models\GmsOfficeTimeBlock;
use App\Models\GmsComplaintReply;
use App\Models\GmsBookCategory;
use App\Models\GmsBookCatRange;
use App\Models\GmsBookControls;
use App\Models\GmsBookPurchase;
use App\Models\GmsBookPurchaseDc;
use App\Models\GmsBookingDtls;
use App\Models\GmsBookRelease;
use App\Models\GmsBookRoIssue;
use App\Models\GmsBookRoTransfer;
use App\Models\GmsBookVendor;
use App\Models\GmsCity;
use App\Models\GmsPmfDtls;
use App\Models\GmsComplaint;
use App\Models\GmsCountries;
use App\Models\GmsCustomer;
use App\Models\GmsDept;
use App\Models\GmsDesg;
use App\Models\GmsEmp;
use App\Models\GmsEmpType;
use App\Models\GmsFuelCharges;
use App\Models\GmsGst;
use App\Models\GmsInvoice;
use App\Models\GmsNdelReason;
use App\Models\GmsPincode;
use App\Models\GmsRateMaster;
use App\Models\GmsPaymentOffice;
use App\Models\GmsRateZoneService;
use App\Models\GmsState;
use App\Models\GmsTaxType;
use App\Models\GmsZone;
use App\Models\GmsCnnoStock;
use App\Models\GmsBookPurchaseItem;
use App\Models\GmsTaxTypeHistory;
use App\Rules\MatchOldPassword;
use App\Models\GmsFuelDefaultCharges;
use Carbon\Carbon;
use App\Exports\CountryExport;
use App\Exports\StateExport;
use App\Exports\CityExport;
use App\Exports\PincodeExport;
use App\Exports\OfficeExport;
use App\Exports\ReasonExport;
use App\Exports\AdminCustomerExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;
use App\Models\Admin;
use App\Models\BookCatType;
use App\Manager\CommonMgr;
use App\Models\AdminSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\GmsOffice;
use Image;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{

    use AdminTrait;

    protected $request;
    protected $commonMgr;


    public function __construct(Request $request, CommonMgr $commonMgr)
    {
        $this->request = $request;
        $this->commonMgr = $commonMgr;
    }

    /**
     * @OA\Get(
     * path="/generateDefaultAdmin",
     * summary="Default Admin",
     * operationId="login",
     *  tags={"Admin"},
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
    public function generateDefaultAdmin()
    {
        if (Admin::where('email', "admin@admin.com")->where('user_type', "ADMIN")->count() == 0) {
            try {
                $admin = new Admin([
                    'name' => 'Admin',
                    'username' => 'admin',
                    'email' => 'admin@admin.com',
                    'password' => Hash::make('admin123'),
                    'user_type' => 'admin',
                ]);
                $admin->save();
                return $this->successResponse(self::CODE_OK, self::OPERATION_SUCCESS, $admin);
            } catch (\Exception $e) {
                return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, $e);
            }
        } else {
            return $this->successResponse(self::CODE_OK, "Admin Already Created!!");
        }
    }

    /**
     * @OA\Post(
     * path="/adminLogin",
     * summary="Admin Login",
     * operationId="login",
     *  tags={"Admin"},
     * @OA\Parameter(
     * name="username",
     * in="query",
     * required=true,
     * @OA\Schema(
     * type="string"
     *  )
     * ),
     * @OA\Parameter(
     * name="password",
     * in="query",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    // public function adminLogin()
    // {
    //     $validator = Validator::make($this->request->all(), [
    //         'username' => 'required',
    //         'password' => 'required|min:8',
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
    //     }
    //     $input = $this->request->all();
    //     $header = $this->commonMgr->headerDetails();
    //     $admin = Admin::where('username', $input['username'])->first();
    //     if ($admin != null) {
    //         if (Hash::check($input['password'], $admin->password)) {
    //             DB::beginTransaction();
    //             try {
    //                 $token = $admin->createToken('Gsm')->accessToken;
    //                 $adminSession = new AdminSession([
    //                     'admin_id' => $admin->id,
    //                     'user_agent' => $header['USER-AGENT'],
    //                     'ip_address' => $header['IP_ADDRESS'],
    //                     'session_token' => $token,
    //                 ]);
    //                 $adminSession->save();
    //                 DB::commit();
    //                 if ($admin->user_type = "EMP") {
    //                     $emp_details = GmsEmp::where('id', $admin->office_id)->first();
    //                     $office_details = GmsOffice::where('office_code', $emp_details->emp_rep_office)->first();
    //                     return $this->successResponse(self::CODE_OK, "Login Successfully!!", [
    //                         'session_token' => $adminSession->session_token,
    //                         'admin_name' => $admin->username,
    //                         'id' => $admin->id,
    //                         'email' => $admin->email,
    //                         'office_details' => isset($office_details) ? $office_details = $office_details : '',
    //                         'user_type' => $admin->user_type]);
    //                 } else {
    //                     $office_details = GmsOffice::where('user_id', $admin->id)->first();
    //                     return $this->successResponse(self::CODE_OK, "Login Successfully!!", [
    //                         'session_token' => $adminSession->session_token,
    //                         'admin_name' => $admin->username,
    //                         'id' => $admin->id,
    //                         'email' => $admin->email,
    //                         'office_details' => isset($office_details) ? $office_details = $office_details : '',
    //                         'user_type' => $admin->user_type]);
    //                 }
    //             } catch (\Exception $e) {
    //                 DB::rollback();
    //                 return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, "Something Error!!", $e);
    //             }
    //         } else {
    //             return $this->errorResponse(self::CODE_UNAUTHORIZED, "Password Is Incorrect");
    //         }
    //     } else {
    //         return $this->errorResponse(self::CODE_UNAUTHORIZED, "Admin Is Not Register");
    //     }
    // }

    public function adminLogin()
    {
        $validator = Validator::make($this->request->all(), [
            'username' => 'required',
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $header = $this->commonMgr->headerDetails();
        $admin = Admin::where('username', $input['username'])->first();
        if ($admin != null) {
            if (Hash::check($input['password'], $admin->password)) {
                DB::beginTransaction();
                try {
                    $token = $admin->createToken('Gsm')->accessToken;
                    $adminSession = new AdminSession([
                        'admin_id' => $admin->id,
                        'user_agent' => $header['USER-AGENT'],
                        'ip_address' => $header['IP_ADDRESS'],
                        'session_token' => $token,
                    ]);
                    $adminSession->save();
                    DB::commit();
                    $office_details = GmsOffice::where('user_id', $admin->id)->first();
                    return $this->successResponse(self::CODE_OK, "Login Successfully!!", [
                        'session_token' => $adminSession->session_token,
                        'admin_name' => $admin->username,
                        'id' => $admin->id,
                        'email' => $admin->email,
                        'office_details' => isset($office_details) ? $office_details = $office_details : '',
                        'user_type' => $admin->user_type]);
                } catch (\Exception $e) {
                    DB::rollback();
                    return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, "Something Error!!", $e);
                }
            } else {
                return $this->errorResponse(self::CODE_UNAUTHORIZED, "Password Is Incorrect");
            }
        } else {
            return $this->errorResponse(self::CODE_UNAUTHORIZED, "Admin Is Not Register");
        }
    }

    public function adminViewOfficeId()
    {
        $validator = Validator::make($this->request->all(), [
            'office_id' => 'required|exists:gms_office,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewGmsOffice = GmsOffice::where('id', $input['office_id'])->where('is_deleted', 0)->first();
        if (!$viewGmsOffice) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Show Office Successfully!!", $viewGmsOffice);
        }
    }


    /**
     * @OA\Post(
     * path="/adminLogout",
     * summary="Admin Logout",
     * operationId="logout",
     *  tags={"Admin"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function logout()
    {
        $sessionObject = session()->get('session_token');
        $adminSession = AdminSession::find($sessionObject->id);
        if ($adminSession != null) {
            try {
                $adminSession->is_active = 0;
                $adminSession->save();
                return $this->successResponse(self::CODE_OK, "Logout Successfully!!");
            } catch (\Exception $e) {
                return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, "Something Error!!");
            }
        } else {
            return $this->errorResponse(self::CODE_UNAUTHORIZED, 'Session not found!!');
        }
    }


    /**
     * @OA\Post(
     * path="/updateProfile",
     * summary="Update Profile",
     * operationId="updateProfile",
     *  tags={"Admin"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     * name="admin_id",
     * in="query",
     * required=true,
     * @OA\Schema(
     * type="integer"
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
    public function updateProfile()
    {
        $validator = Validator::make($this->request->all(), [
            'admin_id' => 'required|exists:admin,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $adminSession = session()->get('session_token');
        $input = $this->request->all();
        $updateAdmin = Admin::where('id', $adminSession->admin_id)->where('is_deleted', 0)->first();
        if ($updateAdmin) {
            $editAdmin = Admin::find($updateAdmin->id);
            $editAdmin->update($input);
            return $this->successResponse(self::CODE_OK, "Admin Profile Update Successfully!!", $editAdmin);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Id Not Found");
        }
    }

    /**
     * @OA\Post(
     * path="/updateUserProfile",
     * summary="Update Profile",
     * operationId="updateUserProfile",
     *  tags={"Admin"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     * name="admin_id",
     * in="query",
     * required=true,
     * @OA\Schema(
     * type="integer"
     *  )
     * ),
     * @OA\Parameter(
     * name="username",
     * in="query",
     * required=true,
     * @OA\Schema(
     * type="string"
     *  )
     * ),
     * @OA\Parameter(
     * name="passwprd",
     * in="query",
     * required=true,
     * @OA\Schema(
     * type="integer"
     *  )
     * ),
     * @OA\Parameter(
     * name="email",
     * in="query",
     * required=true,
     * @OA\Schema(
     * type="string"
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

    public function updateUserProfile()
    {
        $this->request->validate(
            [
                'admin_id' => 'required|exists:admin,id',
                'username' => 'unique:admin,username|alpha_dash|min:8,' . $this->request->admin_id,
                'email' => 'email|unique:admin,email,' . $this->request->admin_id,
                'password' => 'min:8|alpha_dash',
            ]
        );
        try {
            $header = $this->commonMgr->headerDetails();
            $data = $this->request->all();
            $data['last_log_ip'] = $header['IP_ADDRESS'];
            $data['password'] = Hash::make($data['password']);

            $userProfile = Admin::findOrFail($this->request->admin_id);
            $userProfile->update($data);
            if ($userProfile) {
                return $this->successResponse(self::CODE_OK, "Admin Profile Update Successfully!!", $userProfile);
            } else {
                return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Id Not Found");
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, "Something Error!!", $e);
        }
    }

    public function adminGenerateRoTypeList()
    {
        $ro_office_name = GmsOffice::select('office_code as value', DB::raw('CONCAT(office_name,"(",office_code ,")") AS label'))->where('is_deleted', 0)->where('office_type', "RO")->orderBy('office_name', 'asc')->get();
        $data['label'] = 'ro_office_name';
        $data['options'] = $ro_office_name;
        $collection = new Collection([$data]);
        return $collection;
    }

    public function adminStateList()
    {
        $state = GmsState::select('state_code as value', DB::raw('CONCAT(state_name,"(",state_code ,")") AS label'))->where('is_deleted', 0)->orderBy('state_name', 'asc')->get();
        $data['label'] = 'state';
        $data['options'] = $state;
        $collection = new Collection([$data]);
        return $collection;
    }


    /**
     * @OA\Post(
     * path="/changePassword",
     * summary="Change Password",
     * operationId="Change Password",
     *  tags={"Admin"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     * name="password",
     * in="query",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function changePassword()
    {
        $this->request->validate(
            [
                'password' => 'required|min:8|alpha_dash',
            ]
        );
        $adminSession = session()->get('session_token');
        $data = $this->request->all();
        try {
            if (isset($data['password']) && $data['password'] != '') {
                $updateAdmin = Admin::where('id', $adminSession->admin_id)->where('is_deleted', 0)->first();
                if ($updateAdmin) {
                    $hashed_random_password['password'] = Hash::make($data['password']);
                    $changePwd = Admin::find($updateAdmin->id);
                    $changePwd->update($hashed_random_password);
                }
                if ($changePwd) {
                    return $this->successResponse(self::CODE_OK, "Password updated successfully.", $data);
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, "Something Error!!", $e);
        }
    }


    /**
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

    public function createNewUser()
    {
        $this->request->validate(
            [
                'username' => 'required|unique:admin',
                'email' => 'required|email|unique:admin',
                'password' => 'required|min:8',
            ]
        );
        try {
            $header = $this->commonMgr->headerDetails();
            $data = $this->request->all();
            $data['last_log_ip'] = $header['IP_ADDRESS'];
            $data['password'] = Hash::make($data['password']);
            $data['last_log_ip'] = $header['IP_ADDRESS'];
            $user = Admin::create($data);
            if ($user) {
                return $this->successResponse(self::CODE_OK, "User Created Successfully!!", $user);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, "Something Error!!", $e);
        }
    }


    /**
     * @OA\Post(
     * path="/createNewoffice",
     * summary="create Newoffice",
     * operationId="createNewoffice",
     *  tags={"Office"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
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
     *   name="office_name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="branch_category",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_under",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_flag",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_ent",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_add1",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_add2",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_city",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_pin",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_location",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_phone",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_fax",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_email",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_contact",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_contractno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_contract_date",
     *   in="query",
     *   required=true,
     *   @OA\Schema(
     *   type="string",
     *   format ="date-time"
     * )
     * ),
     * @OA\Parameter(
     *   name="office_renewal_date",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string",
     *  format ="date-time"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_exp_date",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string",
     *  format ="date-time"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_sec_deposit",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_pan",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_stax_no",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_stax_date",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_bank_name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_bank_branch_name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_bank_accno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_bank_ifsc",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_bank_micrno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_bank_address",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_closed",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_closing_date",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_sf_flag",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_remarks",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_reporting",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_walkin",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_cnnogen",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="office_delt",
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

    public function createNewoffice()
    {
        $this->request->validate(
            [
                'office_type' => 'required',
                'office_under' => 'required',
                'office_city' => 'required',
                'office_code' => 'required|unique:gms_office',
                'office_name' => 'required|unique:gms_office',
                'office_ent' => 'required|unique:gms_office',
                'office_add1' => 'required|unique:gms_office',
                'office_add2' => 'unique:gms_office',
                'office_pin' => 'unique:gms_office',
                'office_phone' => 'required|numeric|min:10',
                'office_email' => 'required|email|unique:gms_office',
                'office_pan' => 'min:10',
                'office_bank_ifsc' => 'min:11',
            ]
        );

        try {
            $office_data = $this->request->all();
            $office_data['office_type'] = strtoupper($this->request->office_type);
            $office_id = GmsOffice::create($office_data)->id;
            if ($office_id) {
                /*$random_user = Str::random(4);
                $office_name_two_char = trim($this->request->office_name);
                $office_ent = trim($this->request->office_ent);
                $string_remove_space = preg_replace('/\s+/', '', $office_name_two_char . $office_ent);
                $name_ent_substr = substr($string_remove_space, 0, 4);
                $user_name = preg_replace('/\s+/', '', $name_ent_substr . $random_user);*/

                $random_password = Str::random(14);
                $user_details['username'] = $office_data['office_code'];
                $user_details['password'] = $random_password;
                /*$office_data['username'] = $user_name;*/
                $office_data['password'] = $random_password;
                $hashed_random_password = Hash::make($random_password);

                $header = $this->commonMgr->headerDetails();
                //$data=$this->request->all();
                $data['username'] = $office_data['office_code'];
                $data['password'] = $hashed_random_password;
                $data['office_id'] = $office_id;
                $data['name'] = $this->request->office_contact;
                $data['office_code'] = $this->request->office_code;
                $data['city'] = $this->request->office_city;
                $data['email'] = $this->request->office_email;
                $data['phone'] = $this->request->office_phone;
                $data['fax'] = $this->request->office_fax;
                $data['address'] = $this->request->office_add1;
                $data['user_type'] = strtoupper($this->request->office_type);
                $data['status'] = 1;
                $data['password_status'] = 1;
                $data['last_log_ip'] = $header['IP_ADDRESS'];
                $data['last_log_ip'] = $header['IP_ADDRESS'];
                $user_id['user_id'] = Admin::create($data)->id;

                if ($user_id) {
                    $GmsOffice = GmsOffice::findOrFail($office_id);
                    $GmsOffice->update($user_id);
                    if ($GmsOffice) {
                        return $this->successResponse(self::CODE_OK, "Office Created Successfully!!", $user_details);
                    }
                }
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, "Something Error!!", $e);
        }
    }

    public function getOfficeCode()
    {
        $input = $this->request->all();

        $office_details = GmsOffice::latest('id')->where('office_type', $input['office_type']);
        $response['code'] = 404;
        $response['massege'] = 'Validator error';
        $response['status'] = 422;

        if ($input['office_type'] == 'HO') {
            $office = $office_details->first();
            if (isset($office)) {

                $response['validator_error'] = 'HO Already Exist';
                $response['office_details'] = $office;

                return $response;
            } else {
                $data['office_code'] = $input['office_city'] . $input['office_type'];
                return $data;
            }
        } elseif ($input['office_type'] == 'ZO' || $input['office_type'] == 'RO') {
            $office = $office_details->where('office_city', $input['office_city'])->first();
            if (isset($office)) {

                $response['validator_error'] = 'ZO Already Exist';
                $response['office_details'] = $office;
                return $response;
            } else {
                $data['office_code'] = $input['office_city'] . $input['office_type'];
                return $data;
            }
        } elseif ($input['office_type'] == 'BO') {
            $office = $office_details->where('office_city', $input['office_city'])->first();

            if (isset($office->office_code)) {
                $office_num = (int)filter_var($office->office_code, FILTER_SANITIZE_NUMBER_INT);
                $new_num = $office_num + 1;
            } else {
                $new_num = 1;
            }
            $data['office_code'] = $input['office_city'] . $new_num;

            return $data;
        } else {
            $data['office_code'] = $input['office_city'] . $input['office_type'];
            return $data;
        }
    }

    public function editoffice()
    {
        $this->request->validate(
            [
                'office_type' => 'required',
                'office_under' => 'required',
                'office_city' => 'required',
                'office_code' => 'required|unique:gms_office',
                'office_name' => 'required|unique:gms_office,office_name,' . $this->request->office_id,
                'office_ent' => 'required|unique:gms_office,office_ent,' . $this->request->office_id,
                'office_add1' => 'required|unique:gms_office,office_add1,' . $this->request->office_id,
                'office_add2' => 'unique:gms_office,office_add2,' . $this->request->office_id,
                'office_phone' => 'required|numeric|digits_between:10,10',
                'office_email' => 'required|email|unique:gms_office,office_email,' . $this->request->office_id,
                'office_pan' => 'size:10',
                'office_bank_ifsc' => 'size:11',
                'office_id' => 'required'
            ]
        );
        try {
            $office_data = $this->request->all();
            $office_data['office_type'] = strtoupper($this->request->office_type);
            $GmsOffice = GmsOffice::findOrFail($this->request->office_id);
            $GmsOffice->update($office_data);
            if ($GmsOffice) {
                $admin = Admin::where('office_id', $this->request->office_id)->first();
                $data = $this->request->all();
                $header = $this->commonMgr->headerDetails();
                $data['office_id'] = $GmsOffice->id;
                $data['name'] = $this->request->office_name;
                $data['office_code'] = $this->request->office_code;
                $data['city'] = $this->request->office_city;
                $data['email'] = $this->request->office_email;
                $data['phone'] = $this->request->office_phone;
                $data['fax'] = $this->request->office_fax;
                $data['address'] = $this->request->office_add1;
                $data['user_type'] = strtoupper($this->request->office_type);
                $data['last_log_ip'] = $header['IP_ADDRESS'];
                $data['last_log_ip'] = $header['IP_ADDRESS'];
                $Admin = Admin::findOrFail($admin->id);
                $Admin->update($data);
                return $this->successResponse(self::CODE_OK, "Update Successfully!!", $GmsOffice);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, "Something Error!!", $e);
        }
    }


    /**
     * @OA\Get(
     * path="/getMyOfficeProfile",
     * summary="get MyOfficeProfile",
     * operationId="getMyOfficeProfile",
     *  tags={"Office"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function getMyOfficeProfile()
    {
        $sessionObject = session()->get('session_token');
        $headers = getallheaders();
        $headers = array_change_key_case($headers, CASE_UPPER);
        if (isset($headers['SESSION-TOKEN']) && !empty($headers['SESSION-TOKEN'])) {
            $user_check = Admin::where('id', $sessionObject->admin_id)->first();
            if ($sessionObject != null) {
                $myOfficeProfile = GmsOffice::where('user_id', $sessionObject->admin_id)->first();
                if ($myOfficeProfile) {
                    return $this->successResponse(self::CODE_OK, "Office Profile", $myOfficeProfile);
                }
            } else {
                $response['code'] = 401;
                $response['message'] = "Invalid Session Token!!";
                return response()->json($response, $response['code']);
            }
        }
    }

    public function getOfficeUnder()
    {
        $office = array();
        if ($this->request->office_type == 'RO') {
            $office = GmsOffice::select('id as value',
                DB::raw('CONCAT(office_name,"(",office_code,")") AS label')
            )->where('office_type', 'ZO')->where('is_deleted', 0)->orderBy('office_name', 'asc')->get();

        } elseif ($this->request->office_type == 'BO' || $this->request->office_type == 'SF') {
            $office = GmsOffice::select('id as value',
                DB::raw('CONCAT(office_name,"(",office_code,")") AS label')
            )->where('office_type', 'RO')->where('is_deleted', 0)->orderBy('office_name', 'asc')->get();
        } else {
            $office['value'] = 0;
            $office['label'] = 'Head Office';
        }
        $data['label'] = 'office';
        $data['options'] = $office;
        $collection = new Collection([$data]);
        return $collection;
    }

    /**
     * @OA\Get(
     * path="/viewPincodeList",
     * summary="view PincodeList",
     * operationId="viewPincodeList",
     *  tags={"Pincode"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewPincodeList(Request $request)
    {
        $viewPincode = GmsPincode::join('gms_city', 'gms_pincode.city_code', '=', 'gms_city.city_code')
            ->select('gms_pincode.pincode_value',
                'gms_pincode.service',
                'gms_pincode.courier',
                'gms_pincode.gold',
                'gms_pincode.logistics',
                'gms_pincode.regular',
                'gms_pincode.topay',
                'gms_pincode.cod',
                'gms_pincode.branch_id',
                'gms_city.city_code',
                'gms_city.city_name',
                'gms_pincode.pin_status'
            );

        return $viewPincode->paginate($request->per_page);

    }

    public function getEmpCode()
    {
        session()->get('session_token');
        $emp = GmsEmp::latest('id')->where('emp_type', $this->request->emp_type)->first();
        if (isset($emp->emp_num)) {
            $new_num = $emp->emp_num + 1;
        } else {
            $new_num = 1;
        }
        $data['emp_code'] = 'GMS' . $this->request->emp_type . $new_num;
        $data['emp_num'] = $new_num;
        return $data;

    }

    public function adminAddState(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->first();
        $validator = Validator::make($request->all(), [
            'zone_id' => 'required|unique:gms_state',
            'state_code' => 'required|unique:gms_state',
            'state_name' => 'required|unique:gms_state',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $split = explode("_", $this->request->zone_id);
        $input['country_id'] = $split[0];
        $input['zone_id'] = $split[1];
        $input['user_id'] = $admin->id;
        $input['status'] = 'A';
        $addGmsState = new GmsState($input);
        $addGmsState->save();
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addGmsState);
    }

    public function adminAddCountry(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($request->all(), [

            'countries_iso_code_2' => 'required|unique:gms_countries',
            'countries_name' => 'required|unique:gms_countries',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['user_id'] = $sessionObject->admin_id;
        $input['status'] = 0;
        $addGmsCountries = new GmsCountries($input);
        $addGmsCountries->save();
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addGmsCountries);
    }

    public function adminEditCountry(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($request->all(), [
            'countries_iso_code_2' => 'max:2|unique:gms_countries,countries_iso_code_2,' . $this->request->country_id,
            'countries_name' => 'unique:gms_countries,countries_name,' . $this->request->country_id,
            'country_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['user_id'] = $sessionObject->admin_id;
        $GmsCountries = GmsCountries::findOrFail($this->request->country_id);
        $GmsCountries->update($input);
        if ($GmsCountries) {
            return $this->successResponse(self::CODE_OK, "Country Update Successfully!!", $GmsCountries);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Id Not Found");
        }
    }

    public function adminAddZone(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($request->all(), [

            'zone_code' => 'required|unique:gms_zone',
            'zone_name' => 'required|unique:gms_zone',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['user_id'] = $sessionObject->admin_id;
        $input['status'] = 'A';
        $addGmsZone = new GmsZone($input);
        $addGmsZone->save();
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addGmsZone);
    }

    /**
     * @OA\Get(
     * path="/viewZone",
     * summary="view Zone",
     * operationId="viewZone",
     *  tags={"Zone"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewZone(Request $request)
    {
        $gmsZone = GmsZone::join('gms_countries', 'gms_zone.country_id', '=', 'gms_countries.id')
            ->select('gms_zone.id',
                'gms_countries.id as country_id',
                DB::raw('CONCAT(gms_countries.countries_name,"(",gms_countries.countries_iso_code_2,")") As country'),
                'gms_zone.zone_code',
                'gms_zone.zone_name',
            );
        return $gmsZone->paginate($request->per_page);

    }

    public function adminEditZone(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($request->all(), [
            'zone_code' => 'max:2|unique:gms_zone,zone_code,' . $this->request->zone_id,
            'zone_name' => 'unique:gms_zone,zone_name,' . $this->request->zone_id,
            'zone_id' => 'required'

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['user_id'] = $sessionObject->admin_id;

        $GmsZone = GmsZone::findOrFail($this->request->zone_id);
        $GmsZone->update($input);
        if ($GmsZone) {
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $GmsZone);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Id Not Found");
        }
    }

    public function addCountryStateCity()
    {
        $input = $this->request->all();
        $addCountry = new GmsCountries($input);
        $addCountry->save();

        $input['country_id'] = $addCountry->id;
        $addState = new GmsState($input);
        $addState->save();

        $input['country_id'] = $addCountry->id;
        $addZone = new GmsZone($input);
        $addZone->save();

        $input['state_id'] = $addState->id;
        $input['state_code'] = $addState->state_code;
        $addCity = new GmsCity($input);
        $addCity->save();

        return response()->json([
            'Country' => array_reverse(array($addCountry)),
            'State' => array_reverse(array($addState)),
            'City' => array_reverse(array($addCity)),
            'Zone' => array_reverse(array($addZone))
        ]);

    }

    public function exportCountry()
    {
        return Excel::download(new CountryExport, 'country.xlsx');
    }

    public function exportState()
    {
        return Excel::download(new StateExport, 'state.xlsx');
    }

    public function exportCity()
    {
        return Excel::download(new CityExport, 'city.xlsx');
    }

    public function exportPincode()
    {
        return Excel::download(new PincodeExport, 'pincode.xlsx');
    }

    public function exportOffice()
    {
        return Excel::download(new OfficeExport, 'office.xlsx');
    }

    public function exportReason()
    {
        return Excel::download(new ReasonExport, 'reason.xlsx');
    }

    public function exportAdminCustomer()
    {
        return Excel::download(new AdminCustomerExport, 'customers.xlsx');
    }

    public function adminEditState(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->first();
        $validator = Validator::make($request->all(), [
            'state_code' => 'max:2|unique:gms_state,state_code,' . $this->request->state_id,
            'state_name' => 'unique:gms_state,state_name,' . $this->request->state_id,
            'zone_id' => 'required',
            'state_id' => 'required'

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $split = explode("_", $this->request->zone_id);
        $input['country_id'] = $split[0];
        $input['zone_id'] = $split[1];
        $input['user_id'] = $admin['username'];

        $GmsState = GmsState::findOrFail($this->request->state_id);
        $GmsState->update($input);
        if ($GmsState) {
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $GmsState);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Id Not Found");
        }

    }

    public function adminAddCity()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'city_code' => 'max:5|required|unique:gms_city',
            'city_name' => 'required|unique:gms_city',
            'state_code' => 'max:5|required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['user_id'] = $adminSession->admin_id;

        $addGmsCity = new GmsCity($input);
        $addGmsCity->save();
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addGmsCity);

    }

    public function adminEditCity(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->first();
        $validator = Validator::make($request->all(), [
            'city_code' => 'max:5|unique:gms_city,city_code,' . $this->request->city_id,
            'city_name' => 'unique:gms_city,city_name,' . $this->request->city_id,
            'state_code' => 'max:5|required',
            'city_id' => 'required'

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['user_id'] = $sessionObject->admin_id;
        $GmsCity = GmsCity::findOrFail($this->request->city_id);
        $GmsCity->update($input);
        if ($GmsCity) {
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $GmsCity);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Id Not Found");
        }

    }

    /**
     * @OA\Get(
     * path="/getCountryStateZone",
     * summary="get CountryStateZone",
     * operationId="getCountryStateZone",
     *  tags={"Zone"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function getCountryStateZone(Request $request)
    {
        $getCountryStateZone = GmsState::select(
            'gms_state.id',
            DB::raw('CONCAT(gms_countries.countries_name,"(",gms_countries.countries_iso_code_2,")") As country'),
            DB::raw('CONCAT(gms_zone.zone_name,"(",gms_zone.zone_code,")") As zone'),
            'gms_state.state_code',
            'gms_state.state_name',
        );
        $getCountryStateZone->join('gms_countries', 'gms_state.country_id', '=', 'gms_countries.id');
        $getCountryStateZone->join('gms_zone', 'gms_state.zone_id', '=', 'gms_zone.id');
        return $getCountryStateZone->paginate($request->per_page);

    }

    /**
     * @OA\Post(
     * path="/viewAllOfficeList",
     * summary="viewAllOfficeList",
     * operationId="viewAllOfficeList",
     *  tags={"Office"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     *   name="q",
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
    public function viewAllOfficeList(Request $request)
    {
        $officeList = GmsOffice::where('is_deleted', 0)->select('id', 'office_code', 'office_name', 'office_type', 'office_under', 'office_city', 'office_phone', 'status');
        if ($request->has('q')) {
            $q = $request->q;
            $officeList->where('office_code', 'LIKE', '%' . $q . '%')
                ->orWhere('office_name', 'LIKE', '%' . $q . '%');
        }
        return $officeList->paginate($request->per_page);
    }

    public function deleteOffice()
    {
        return $this->officeDelete();
    }

    /**
     * @OA\Get(
     * path="/viewAllReasonList",
     * summary="viewAllReasonList",
     * operationId="viewAllReasonList",
     *  tags={"reasones"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewAllReasonList(Request $request)
    {
        $gmsReason = GmsNdelReason::where('is_deleted', 0)->select('id', 'ndel_code', 'ndel_desc');
        return $gmsReason->paginate($request->per_page);
    }

    public function deleteReason()
    {
        return $this->reasonDelete();
    }

    public function adminEditReason(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($request->all(), [
            'ndel_code' => 'unique:gms_ndel_reason,ndel_code,' . $this->request->reason_id,
            'ndel_desc' => 'unique:gms_ndel_reason,ndel_desc,' . $this->request->reason_id,
            'reason_id' => 'required'

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }

        $input = $this->request->all();
        $input['user_id'] = $sessionObject->admin_id;
        $GmsNdelReason = GmsNdelReason::findOrFail($this->request->reason_id);
        $GmsNdelReason->update($input);
        if ($GmsNdelReason) {
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $GmsNdelReason);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Id Not Found");
        }

    }

    public function adminAddReason()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'ndel_code' => 'required|unique:gms_ndel_reason',
            'ndel_desc' => 'required|unique:gms_ndel_reason',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }

        $input = $this->request->all();
        $input['user_id'] = $adminSession->admin_id;

        $addGmsNdelReason = new GmsNdelReason($input);
        $addGmsNdelReason->save();
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addGmsNdelReason);

    }

    public function adminPincodeCityList()
    {
        $office_type = GmsCity::select('city_code as value',
            DB::raw('CONCAT(city_name,"(",city_code,")","(",state_code,")") As label'))->where('is_deleted', 0)->orderBy('city_name', 'asc')->get();
        $data['label'] = 'city';
        $data['options'] = $office_type;
        $collection = new Collection([$data]);
        return $collection;
    }

    /**
     * @OA\Get(
     * path="/viewAllCustomerList",
     * summary="viewAllCustomerList",
     * operationId="viewAllCustomerList",
     *  tags={"Admin/Customer"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewAllCustomerList(Request $request)
    {
        $bookCategory = GmsCustomer::join('gms_office', 'gms_customer.cust_rep_office', '=', 'gms_office.office_code')->where('gms_customer.is_deleted', 0)->select('gms_customer.id', 'gms_customer.cust_code', 'gms_customer.cust_name', 'gms_customer.cust_type',

            DB::raw('CONCAT(gms_customer.cust_rep_office,"-",gms_office.office_name) AS cust_rep_office'),
            'gms_customer.cust_reach', 'gms_customer.approved_status');
        return $bookCategory->paginate($request->per_page);
    }

    public function adminViewCustomerId()
    {
        $validator = Validator::make($this->request->all(), [
            'cus_id' => 'required|exists:gms_customer,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $response = array();
        $response['Profile'] = GmsCustomer::where('id', $input['cus_id'])->where('is_deleted', 0)->select('cust_type', 'cust_code', 'monthly_bill_type', 'service_courier', 'service_logistics', 'multi_region', 'service_gold', 'service_intracity', 'service_international', 'gst_applicable', 'service_reverse_booking', 'email_status', 'sms_status', 'cust_rep_office', 'created_office_code', 'cust_la_ent', 'cust_account_type', 'cust_la_address', 'cust_la_pan', 'cust_la_servicetax', 'cust_la_cin', 'cust_la_cindate', 'cust_name', 'cust_dob', 'cust_education', 'cust_qualification', 'cust_residen_address', 'cust_fat_wife_name', 'cust_pan', 'cust_cin', 'cust_phone', 'cust_email', 'cust_pb_nature', 'cust_pb_empdeployed', 'cust_pb_vehdeployed', 'cust_pb_turnover', 'cust_ad_bank_name', 'cust_ad_bank_branch', 'cust_ad_account_no', 'cust_ad_ifsc_code', 'cust_br_name', 'cust_br_name1', 'pan_card', 'passport_copy', 'driving_license', 'st_reg_certficate', 'aadhaar_card', 'voter_id', 'telephone_bill', 'photo')->first();
        $response['Contract_Details'] = GmsCustomer::where('id', $input['cus_id'])->where('is_deleted', 0)->select('cust_cd_contact_name', 'cust_cd_contract_date', 'cust_cd_renewal_date', 'cust_cd_exp_date', 'cust_cd_remarks')->first();
        $response['Security_Deposit'] = GmsCustomer::where('id', $input['cus_id'])->where('is_deleted', 0)->select('cust_secdip_fixed', 'cust_secdip_paid', 'cust_sec_chequeno', 'cust_sec_chequedate', 'cust_sd_fixed')->first();

        $response['Rate Code'] = GmsCustomer::join('gms_rate_code', 'gms_customer.cust_code', '=', 'gms_rate_code.cust_code')->select('office_code', 'cust_type', 'rate_code', 'description', 'effect_date_from')->first();

        if (!$response) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $response;

        }
    }

    public function searchAdminCustomer(Request $request)
    {
        $ro_office = $request->ro_office;
        $bo_office = $request->bo_office;
        $customer = $request->customer;
        $type = $request->type;

        $getCustomer = GmsCustomer::join('gms_office', 'gms_office.office_code', '=', 'gms_customer.cust_rep_office')
            ->select('gms_customer.id',
                'gms_customer.cust_code',
                'gms_customer.cust_name',
                'gms_customer.cust_type',
                DB::raw('CONCAT(gms_customer.cust_rep_office,"-",gms_office.office_name) AS reporting_office'),
                'gms_customer.cust_reach',
                'gms_customer.approved_status'
            )->where('gms_customer.is_deleted', 0);

        if (isset($ro_office)) {
            $getCustomer->where('gms_customer.cust_ro', $ro_office);
        }
        if (isset($customer)) {
            $getCustomer->where('gms_customer.cust_type', $customer);
        }
        if (isset($type)) {
            $getCustomer->where('gms_customer.approved_status', $type);
        }
        if (isset($bo_office)) {
            $getCustomer->where('gms_customer.created_office_code', $bo_office);
        }
        if ($request->has('search')) {
            $search = $request->search;
            $getCustomer->where('gms_customer.cust_code', 'LIKE', '%' . $search . '%')
                ->orWhere('gms_customer.cust_name', 'LIKE', '%' . $search . '%')
                ->orWhere('gms_customer.cust_type', 'LIKE', '%' . $search . '%');
        }
        return $getCustomer->paginate($request->per_page);
    }

    public function adminBOList()
    {
        $BOList = GmsOffice::select('office_code as value',
            DB::raw('CONCAT(office_type,"-",office_name,"(",office_code,")") AS label')
        )->where('is_deleted', 0)->where('office_under', $this->request->ro_office)->orderBy('office_name', 'asc')->get();
        $data['label'] = 'BOList';
        $data['options'] = $BOList;
        $collection = new Collection([$data]);
        return $collection;
    }

    public function adminROList()
    {
        $ro_office_name = GmsOffice::select('id as value', DB::raw('CONCAT(office_name,"(",office_code,")") AS label'))->where('is_deleted', 0)->where('office_type', "RO")->orderBy('office_name', 'asc')->get();
        $data['label'] = 'ro_office_name';
        $data['options'] = $ro_office_name;
        $collection = new Collection([$data]);
        return $collection;
    }

    public function stashCustomer()
    {
        $data = [
            'totalRo' => \App\Models\GmsOffice::where('is_deleted', 0)->where('office_type', 'RO')->count(),
            'totalBo' => \App\Models\GmsOffice::where('is_deleted', 0)->where('office_type', 'BO')->count(),
            'totalSf' => \App\Models\GmsOffice::where('is_deleted', 0)->where('office_type', 'SF')->count(),
            'totalDc' => \App\Models\GmsCustomer::where('is_deleted', 0)->where('cust_type', 'DC')->count(),
            'totalCf' => \App\Models\GmsCustomer::where('is_deleted', 0)->where('cust_type', 'CF')->count(),
            'totalOf' => \App\Models\GmsCustomer::where('is_deleted', 0)->where('cust_type', 'OF')->count(),
            'totalBa' => \App\Models\GmsCustomer::where('is_deleted', 0)->where('cust_type', 'BA')->count(),
            'totalCc' => \App\Models\GmsCustomer::where('is_deleted', 0)->where('cust_type', 'CC')->count(),
        ];
        return $this->successResponse(self::CODE_OK, "Get Total Successfully!!", $data);
    }

    /**
     * @OA\Post(
     * path="/addCnnoComplaints",
     * summary="addCnnoComplaints",
     * operationId="addCnnoComplaints",
     *  tags={"Admin/Complaints"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     *   name="log_cnno",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="consignee_mobile_no",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name=" consignee_name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="consignor_mobile_no",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="consignor_name",
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
    public function addCnnoComplaints()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'log_cnno' => 'required',
            'consignor_mobile_no' => 'required|numeric|unique:gms_complaint',
            'description' => 'required',
            'status' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['userid'] = $adminSession->admin_id;
        $input['entry_date'] = Carbon::now()->toDateTimeString();
        $addComplaints = new GmsComplaint($input);
        $addComplaints->save();

        return $this->successResponse(self::CODE_OK, "Complaints Added Successfully!!", $addComplaints);
    }


    public function complaintDelete()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'complaint_id' => 'required|exists:gms_complaint,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getComplaint = GmsComplaint::where('id', $input['complaint_id'])->where('userid', $sessionObject->admin_id)->first();
        if ($getComplaint != null) {
            $getComplaint->is_deleted = 1;
            $getComplaint->save();
            return $this->successResponse(self::CODE_OK, "Delete Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    /**
     * @OA\Post(
     * path="/addBookCategory",
     * summary="addBookCategory",
     * operationId="addBookCategory",
     *  tags={"Admin/BookCategory"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     *   name="book_cat_name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name=" book_cat_type",
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
    public function addBookCategory()
    {
        $validator = Validator::make($this->request->all(), [
            'book_cat_name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addCategory = new GmsBookCategory($input);
        $addCategory->save();
        return $this->successResponse(self::CODE_OK, "Book Category Added Successfully!!", $addCategory);
    }

    /**
     * @OA\Get(
     * path="/viewAllBookCategory",
     * summary="viewAllBookCategory",
     * operationId="viewAllBookCategory",
     *  tags={"Admin/BookCategory"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewAllBookCategory(Request $request)
    {
        $bookCategory = GmsBookCategory::where('is_deleted', 0)->select('id', 'book_cat_name', 'book_cat_type');
        return $bookCategory->paginate($request->per_page);
    }

    public function editBookCategory()
    {
        return $this->bookCategoryEdit();
    }

    public function deleteBookCat()
    {
        return $this->bookingCategoryDelete();
    }

    /**
     * @OA\Post(
     * path="/addBookCatRange",
     * summary="addBookCatRange",
     * operationId="addBookCatRange",
     *  tags={"Admin/BookCatRange"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     *   name="book_cat_id",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cnno_start",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cnno_end",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="upto_weight",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="upto_amt",
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
    public function addBookCatRange()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [

            'book_cat_id' => 'required',
            'cnno_start' => 'required',
            'cnno_end' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['user_id'] = $adminSession->admin_id;
        $input['status'] = 'A';
        $addCategory = new GmsBookCatRange($input);
        $addCategory->save();
        return $this->successResponse(self::CODE_OK, "Book Category Added Successfully!!", $addCategory);
    }

    /**
     * @OA\Get(
     * path="/viewAllBookCatRange",
     * summary="viewAllBookCatRange",
     * operationId="viewAllBookCatRange",
     *  tags={"Admin/BookCatRange"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewAllBookCatRange(Request $request)
    {
        $adminSession = session()->get('session_token');
        $bookCatRange = GmsBookCatRange::join('gms_book_category', 'gms_book_cat_range.book_cat_id', '=', 'gms_book_category.id')->join('gms_book_cat_type', 'gms_book_cat_type.id', '=', 'gms_book_category.book_cat_type')->select('gms_book_cat_range.id', 'gms_book_cat_type.book_type as type', 'gms_book_category.book_cat_name', 'gms_book_cat_range.cnno_start', 'gms_book_cat_range.cnno_end');
        return $bookCatRange->paginate($request->per_page);
    }

    public function editBookCatRange()
    {
        return $this->bookCatRangeEdit();
    }


    public function catType()
    {
        $regular = BookCatType::join('gms_book_category', 'gms_book_cat_type.id', '=', 'gms_book_category.book_cat_type')->select('gms_book_category.id as value', 'gms_book_category.book_cat_name as label')->where('gms_book_category.book_cat_type', 0)->get();
        $epod = BookCatType::join('gms_book_category', 'gms_book_cat_type.id', '=', 'gms_book_category.book_cat_type')->select('gms_book_category.id as value', 'gms_book_category.book_cat_name as label')->where('gms_book_category.book_cat_type', 1)->get();
        $prepaid = BookCatType::join('gms_book_category', 'gms_book_cat_type.id', '=', 'gms_book_category.book_cat_type')->select('gms_book_category.id as value', 'gms_book_category.book_cat_name as label')->where('gms_book_category.book_cat_type', 2)->get();
        $data1['label'] = 'regular';
        $data1['options'] = $regular;
        $data2['label'] = 'epod';
        $data2['options'] = $epod;
        $data3['label'] = 'prepaid';
        $data3['options'] = $prepaid;

        $collection = new Collection([$data1, $data2, $data3]);
        return $collection;
    }

    public function addBookPurOrder()
    {
        if (session()->has('session_token')) {
            $sessionObject = session()->get('session_token');
            $admin = Admin::where('id', $sessionObject->admin_id)->where('is_deleted', 0)->first();

            $input = $this->request->all();
            $addBookPurchase = new GmsBookPurchase();
            $addBookPurchase->user_id = $sessionObject->admin_id;
            $addBookPurchase->purchase_invoice_no = $input['purchase_invoice_no'];
            $addBookPurchase->from_address = $input['from_address'];
            $addBookPurchase->from_tin = $input['from_tin'];
            $addBookPurchase->to_address = $input['to_address'];
            $addBookPurchase->to_tin = $input['to_tin'];
            $addBookPurchase->tax_type = $input['tax_type'];
            $addBookPurchase->tax_percentage = $input['tax_percentage'];
            $addBookPurchase->tax_amount = $input['tax_amount'];
            $addBookPurchase->grand_total = $input['grand_total'];
            $addBookPurchase->terms = $input['terms'];
            $addBookPurchase->purchase_invoice_date = Carbon::now()->toDateTimeString();
            $addBookPurchase->save($input);

            $addBookPurchaseitem = new  GmsBookPurchaseitem();
            $addBookPurchaseitem->purchase_id = $addBookPurchase->purchase_invoice_no;
            $addBookPurchaseitem->book_cat_id = $input['book_cat_id'];
            $addBookPurchaseitem->from_range = $input['from_range'];
            $addBookPurchaseitem->to_range = $input['to_range'];
            $addBookPurchaseitem->total_allotted = $input['total_allotted'];
            $addBookPurchaseitem->item_description = $input['item_description'];
            $addBookPurchaseitem->item_cost = $input['item_cost'];
            $addBookPurchaseitem->item_quantity = $input['item_quantity'];
            $addBookPurchaseitem->tax_type = $input['tax_type'];
            $addBookPurchaseitem->tax_percentage = $input['tax_percentage'];
            $addBookPurchaseitem->tax_amount = $input['tax_amount'];
            $addBookPurchaseitem->tax_percentage = $input['tax_percentage'];
            $addBookPurchaseitem->posted_date = Carbon::now()->toDateTimeString();
            $addBookPurchaseitem->save($input);
            return $this->successResponse(self::CODE_OK, "Book Item Added Successfully!!", $addBookPurchaseitem);
        }
    }

    /**
     * @OA\Get(
     * path="/viewAllBookPurOrder",
     * summary="viewAllBookPurOrder",
     * operationId="viewAllBookPurOrder",
     *  tags={"Admin/BookPurOrder"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewAllBookPurOrder(Request $request)
    {
        $adminSession = session()->get('session_token');
        $gmsBookPurchase = GmsBookPurchase::join('gms_book_purchase_item', 'gms_book_purchase_item.purchase_id', '=', 'gms_book_purchase.purchase_invoice_no')->select('gms_book_purchase.id', 'gms_book_purchase.to_address as vendor', 'gms_book_purchase.book_cat_type as book_type', 'gms_book_purchase.purchase_invoice_no as purchase_no', 'gms_book_purchase.purchase_invoice_date as purchase_date', 'gms_book_purchase_item.book_cat_id as category_name', 'gms_book_purchase_item.from_range as from_range', 'gms_book_purchase_item.to_range as to_range', 'gms_book_purchase_item.item_description as description', 'gms_book_purchase_item.item_cost as cost', 'gms_book_purchase_item.item_quantity as quantity', 'gms_book_purchase.grand_total as total');
        $data = $gmsBookPurchase->where('gms_book_purchase.is_deleted', '=', '0');
        return $data->paginate($request->per_page);
    }

    public function deleteBookPurOrder()
    {
        return $this->bookPurDelete();
    }

    public function adminPurchaseItemList()
    {
        $purchase_id = GmsBookPurchaseItem::select('purchase_id as value', 'purchase_id As label')->where('is_deleted', 0)->orderBy('purchase_id', 'asc')->get();
        $data['label'] = 'purchase_id';
        $data['options'] = $purchase_id;
        $collection = new Collection([$data]);
        return $collection;
    }

    public function adminPurchaseItem()
    {
        $purchase_item = GmsBookPurchaseItem::join('gms_book_category', 'gms_book_category.id', '=', 'gms_book_purchase_item.book_cat_id')
            ->select('gms_book_purchase_item.id', 'gms_book_category.book_cat_name AS book_cat', 'gms_book_purchase_item.from_range AS start_cnno', 'gms_book_purchase_item.to_range AS end_cnno', 'gms_book_purchase_item.total_allotted AS quantity')->where('gms_book_purchase_item.purchase_id', $this->request->purchase_id)->where('gms_book_purchase_item.is_deleted', 0)->get();

        return $purchase_item;
    }

    public function addBookInward()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'dc_no' => 'required|numeric',
            'dc_date' => 'required|date',
            'purchase_no' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['user_id'] = $adminSession->admin_id;
        $addBookInward = new GmsBookPurchaseDc($input);
        $addBookInward->save();
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addBookInward);
    }

    /**
     * @OA\Get(
     * path="/viewAllBookInward",
     * summary="viewAllBookInward",
     * operationId="viewAllBookInward",
     *  tags={"Admin/BookInward"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewAllBookInward(Request $request)
    {
        $bookInward = GmsBookPurchaseDc::join('gms_book_purchase_item', 'gms_book_purchase_item.purchase_id', '=', 'gms_book_purchase_dc.purchase_no')->select('gms_book_purchase_dc.id', 'gms_book_purchase_dc.dc_no', 'gms_book_purchase_dc.dc_date AS inward_date', 'gms_book_purchase_dc.purchase_no', 'gms_book_purchase_dc.item_id as category_name', 'gms_book_purchase_dc.from_cnno', 'gms_book_purchase_dc.to_cnno', 'gms_book_purchase_dc.quantity');
        return $bookInward->paginate($request->per_page);
    }


    public function editBookInward()
    {
        return $this->bookInwardEdit();
    }

    /**
     * @OA\Post(
     * path="/addVendor",
     * summary="addVendor",
     * operationId="add Vendor",
     *  tags={"Admin/Vendor"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     *   name="company",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="address1",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="address2",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="city",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="pincode",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="con_num1",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="email",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="email1",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fax",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="tin_no",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="bank_name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="bank_branch_name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="bank_account_no",
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
    public function addBookVendor()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'company' => 'required',
            'address1' => 'required',
            'address2' => 'required',
            'city' => 'required',
            'pincode' => 'required|integer',
            'con_num1' => 'required',
            'email' => 'required|email|unique:gms_book_vendor',
            'email1' => 'required|email',
            'fax' => 'required',
            'tin_no' => 'required',
            'bank_name' => 'required',
            'bank_branch_name' => 'required',
            'bank_account_no' => 'required|max:12',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addVendor = new GmsBookVendor($input);
        $addVendor->save();
        return $this->successResponse(self::CODE_OK, "Vendor Added Successfully!!", $addVendor);
    }

    /**
     * @OA\Get(
     * path="/viewAllBookVendor",
     * summary="viewAllBookVendor",
     * operationId="viewAllBookVendor",
     *  tags={"Admin/BookVendor"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     *   name="q",
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
    public function viewAllBookVendor(Request $request)
    {
        $adminSession = session()->get('session_token');
        $bookVendor = GmsBookVendor::select('id', 'vendor_code', 'person', 'company', 'address1', 'address2', 'city', 'pincode', 'con_num1', 'con_num2', 'email', 'email1', 'fax', 'tin_no', 'bank_name', 'bank_branch_name', 'bank_account_no')->where('is_deleted', 0);
        if ($request->has('q')) {
            $q = $request->q;
            $bookVendor->where('person', 'LIKE', '%' . $q . '%')
                ->orWhere('vendor_code', 'LIKE', '%' . $q . '%');
        }
        return $bookVendor->paginate($request->per_page);
    }

    public function deleteBookVendor()
    {
        return $this->deleteVendor();
    }

    public function viewVendorDetails()
    {
        return $this->vendorDetails();
    }

    public function editBookVendor()
    {
        return $this->bookVendorEdit();
    }

    public function adminVendorList()
    {
        $GmsBookVendor = GmsBookVendor::select('vendor_code as value', DB::raw('CONCAT(company,"(",vendor_code,")") AS label'))->where('is_deleted', 0)->orderBy('company', 'asc')->get();
        $data['label'] = 'GmsBookVendor';
        $data['options'] = $GmsBookVendor;
        $collection = new Collection([$data]);
        return $collection;
    }

    /**
     * @OA\Get(
     * path="/viewBookRoIssue",
     * summary="viewBookRoIssue",
     * operationId="viewBookRoIssue",
     *  tags={"Admin/BookRoIssue"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewBookRoIssue(Request $request)
    {
        $adminSession = session()->get('session_token');
        $bookCatRange = GmsBookRoIssue::query();
        $bookCatRange->select('office_code', 'cnno_start', 'cnno_end', 'qauantity', 'entry_date', 'status', 'transfer_status');
        return $bookCatRange->paginate($request->per_page);
    }

    /**
     * @OA\Post(
     * path="/addBookRoIssue",
     * summary="addBookRoIssue",
     * operationId="add BookRoIssue",
     *  tags={"Admin/BookRoIssue"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     *   name="qauantity",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cnno_start",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cnno_end",
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
    public function addBookRoIssue()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'qauantity' => 'required',
            'cnno_start' => 'required',
            'cnno_end' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addVendor = new GmsBookVendor($input);
        $addVendor->save();

        return $this->successResponse(self::CODE_OK, "Vendor Added Successfully!!", $addVendor);
    }


    /**
     * @OA\Get(
     * path="/viewBookTransfer",
     * summary="viewBookTransfer",
     * operationId="viewBookTransfer",
     *  tags={"Admin/BookTransfer"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewBookTransfer(Request $request)
    {
        $adminSession = session()->get('session_token');
        $bookRoTransfer = GmsBookRoTransfer::join('gms_book_ro_issue', 'gms_book_ro_issue.id', '=', 'gms_book_ro_transfer.iss_ro_id')->select('gms_book_ro_transfer.office_code as from', 'gms_book_ro_transfer.dest_office_code as to', 'gms_book_ro_transfer.cnno_start as from_cnno', 'gms_book_ro_transfer.cnno_end as to_cnno', 'gms_book_ro_issue.qauantity', 'gms_book_ro_transfer.entry_date', 'gms_book_ro_transfer.status')->where('tranfer_type', '=', 'T');
        return $bookRoTransfer->paginate($request->per_page);
    }

    /**
     * @OA\Get(
     * path="/viewBookReturn",
     * summary="viewBookReturn",
     * operationId="viewBookReturn",
     *  tags={"Admin/BookTransfer"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewBookReturn(Request $request)
    {
        $adminSession = session()->get('session_token');
        $bookRoReturn = GmsBookRoTransfer::join('gms_book_ro_issue', 'gms_book_ro_issue.id', '=', 'gms_book_ro_transfer.iss_ro_id')->select('gms_book_ro_transfer.office_code as from', 'gms_book_ro_transfer.dest_office_code as to', 'gms_book_ro_transfer.cnno_start as from_cnno', 'gms_book_ro_transfer.cnno_end as to_cnno', 'gms_book_ro_issue.qauantity', 'gms_book_ro_transfer.entry_date', 'gms_book_ro_transfer.status')->where('tranfer_type', '=', 'R');
        return $bookRoReturn->paginate($request->per_page);
    }

    /**
     * @OA\Get(
     * path="/viewCnnoBookingStock",
     * summary="viewCnnoBookingStock",
     * operationId="viewCnnoBookingStock",
     *  tags={"Admin/CnnoBookingStock"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewCnnoBookingStock(Request $request)
    {
        $getBookingStock = GmsBookPurchaseItem::join('gms_book_category', 'gms_book_purchase_item.book_cat_id', '=', 'gms_book_category.id')->join('gms_book_cat_range', 'gms_book_purchase_item.book_cat_id', '=', 'gms_book_cat_range.book_cat_id')->select('gms_book_category.book_cat_name as category',
            'gms_book_cat_range.cnno_start as from', 'gms_book_cat_range.cnno_end as to', 'gms_book_purchase_item.from_range as from_cnno', 'gms_book_purchase_item.to_range as to_cnno', 'gms_book_purchase_item.item_quantity as quentity_left');
        return $getBookingStock->paginate($request->per_page);

    }

    /**
     * @OA\Post(
     * path="/addBookRelease",
     * summary="addBookRelease",
     * operationId="add BookRelease",
     *  tags={"Admin/BookRelease"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
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
     *   name="cnno_start",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cnno_end",
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
    public function addBookRelease()
    {
        $adminSession = session()->get('session_token');
        if ($this->request->type == "SINGLE") {
            $myArray = explode(',', $this->request->multiple_cnno);
            $data = array();
            foreach ($myArray as $row) {
                # code...
                $data['description'] = $this->request->description;
                $data['user_id'] = $adminSession->admin_id;
                $data['multiple_cnno'] = $row;
                $multiple_cnno = new GmsBookRelease($data);
                $multiple_cnno->save();
                $response[] = $multiple_cnno;
            }
            return $this->successResponse(self::CODE_OK, "Added Successfully!!", $response);
        }
        if ($this->request->type == "RANGE") {
            $input = $this->request->all();
            $input['user_id'] = $adminSession->admin_id;
            $addBook = new GmsBookRelease($input);
            $addBook->save();
            return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addBook);
        }
    }

    /**
     * @OA\Get(
     * path="/viewBookRelease",
     * summary="viewBookRelease",
     * operationId="view BookRelease",
     *  tags={"Admin/BookRelease"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewBookRelease(Request $request)
    {
        if (session()->has('session_token')) {
            $adminSession = session()->get('session_token');
            $bookRelease = GmsBookRelease::query();
            $bookRelease->select('id', 'description', 'cnno_start', 'cnno_end', 'block_type', 'entry_date')->where('is_deleted', 0);
            return $bookRelease->paginate($request->per_page);
        } else {
            return $this->errorResponse(self::CODE_UNAUTHORIZED, "Session not found.");

        }
    }

    public function deleteBookRelease()
    {
        return $this->bookDelete();
    }

    /**
     * @OA\Get(
     * path="/viewBookControl",
     * summary="viewBookControl",
     * operationId="view BookControl",
     *  tags={"Admin/BookControl"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewBookControl(Request $request)
    {
        if (session()->has('session_token')) {
            $adminSession = session()->get('session_token');
            $bookControl = GmsBookControls::query();
            $bookControl->join('gms_customer', 'gms_customer.cust_code', '=', 'gms_book_controls.cust_code')
                ->join('gms_office', 'gms_office.office_code', '=', 'gms_book_controls.office_code')
                ->select('gms_book_controls.id',
                    DB::raw(' null as block_type'),
                    'gms_book_controls.office_type as type',
                    DB::raw("CONCAT(gms_book_controls.office_code,'-',gms_office.office_name) As office"),
                    'gms_book_controls.cust_type',
                    DB::raw("CONCAT(gms_book_controls.cust_code,'-',gms_customer.cust_name) As customer"),

                    'gms_book_controls.from_date', 'gms_book_controls.to_date')->where('gms_book_controls.is_deleted', 0);
            return $bookControl->paginate($request->per_page);
        } else {
            return $this->errorResponse(self::CODE_UNAUTHORIZED, "Session not found.");
        }
    }

    public function deleteBookControl()
    {
        return $this->bookControl();
    }

    public function viewDepartmentList()
    {
        $GmsDept = GmsDept::select('dept_code as value', 'dept_name AS label')->where('is_deleted', 0)->orderBy('dept_name', 'asc')->get();
        $data['label'] = 'GmsDept';
        $data['options'] = $GmsDept;
        $collection = new Collection([$data]);
        return $collection;
    }

    public function viewDesignationList()
    {
        $GmsDesg = GmsDesg::select('desg_code as value', 'desg_name AS label')->where('is_deleted', 0)->orderBy('desg_name', 'asc')->get();
        $data['label'] = 'GmsDesg';
        $data['options'] = $GmsDesg;
        $collection = new Collection([$data]);
        return $collection;
    }

    /**
     * @OA\Get(
     * path="/viewDepartment",
     * summary="viewDepartment",
     * operationId="view Department",
     *  tags={"Admin/Department"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewDepartment(Request $request)
    {
        if (session()->has('session_token')) {
            $adminSession = session()->get('session_token');
            $getDeprt = GmsDept::query();
            $getDeprt->select('id', 'dept_code', 'dept_name')->where('is_deleted', 0);
            return $getDeprt->paginate($request->per_page);
        } else {
            return $this->errorResponse(self::CODE_UNAUTHORIZED, "Session not found.");

        }
    }

    public function viewGenerateRoBill(Request $request)
    {
        $input = $this->request->all();
        $response = array();
        $adminSession = session()->get('session_token');
        $getGmsInvoice = GmsInvoice::query();
        $getGmsInvoice->select(
            'branch_ro',
            'fr_invoice_no',
            DB::raw('DATE_FORMAT(created_at, "%b, %Y") as invoice_date')
        );
        $getGmsInvoice->groupBy('branch_ro', 'invoice_date');
        $getGmsInvoice->where('is_deleted', 0);
        if ($request->isMethod('get')) {
            return $getGmsInvoice->paginate($request->per_page);
        } else {
            $chero[] = GmsInvoice::select(
                DB::raw("IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = 'CHERO'), 0) as total_cnno"),
                DB::raw("IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = 'CHERO'), 0) as total_wt"),
                DB::raw("IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = 'CHERO'), 0) as total_amt"),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $hydro[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "HYDRO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "HYDRO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "HYDRO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $delro[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "DELRO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "DELRO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "DELRO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $amdro[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "AMDRO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "AMDRO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "AMDRO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $ccuro[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "CCURO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "CCURO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "CCURO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $ngpro[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "NGPRO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "NGPRO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "NGPRO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $mumro[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "MUMRO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "MUMRO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "MUMRO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $pnqro[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "PNQRO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "PNQRO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "PNQRO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $cokro[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "COKRO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "COKRO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "COKRO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $rairo[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "RAIRO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "RAIRO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "RAIRO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $jairo[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "JAIRO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "JAIRO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "JAIRO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $gauro[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "GAURO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "GAURO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "GAURO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $vjaro[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "VJARO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "VJARO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "VJARO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $denro[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "DENRO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "DENRO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "DENRO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $kolro[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "KOLRO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "KOLRO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "KOLRO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $bomro[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "BOMRO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "BOMRO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "BOMRO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $pnero[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "PNERO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "PNERO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "PNERO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $patro[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "PATRO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "PATRO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "PATRO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $bbsro[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "BBSRO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "BBSRO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "BBSRO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            $bhpro[] = GmsInvoice::select(
                DB::raw('IFNULL((SELECT count(total_cnno) from gms_invoice WHERE branch_code = "BHPRO"), 0) as total_cnno'),
                DB::raw('IFNULL((SELECT count(total_weight) from gms_invoice WHERE branch_code = "BHPRO"), 0) as total_wt'),
                DB::raw('IFNULL((SELECT count(grand_total) from gms_invoice WHERE branch_code = "BHPRO"), 0) as total_amt'),
            )->where('invoice_date', $input['invoice_date'])->groupBy('total_cnno', 'total_weight', 'grand_total')->first();
            return $this->successResponse(self::CODE_OK, ["CHERO" => $chero,
                "HYDRO" => $hydro,
                "DELRO" => $delro,
                "AMDRO" => $amdro,
                "CCURO" => $ccuro,
                "NGPRO" => $ngpro,
                "MUMRO" => $mumro,
                "PNQRO" => $pnqro,
                "COKRO" => $cokro,
                "RAIRO" => $rairo,
                "JAIRO" => $jairo,
                "GAURO" => $gauro,
                "VJARO" => $vjaro,
                "DENRO" => $denro,
                "KOLRO" => $kolro,
                "BOMRO" => $bomro,
                "PNERO" => $pnero,
                "PATRO" => $patro,
                "BBSRO" => $bbsro,
                "BHPRO" => $bhpro
            ]);
        }
    }

    public function viewAdminGenerateRoBill(Request $request)
    {
        $adminSession = session()->get('session_token');
        $getGmsInvoice = GmsInvoice::query();
        $getGmsInvoice->select(
            'branch_ro',
            'fr_invoice_no',
            DB::raw('DATE_FORMAT(created_at, "%b, %Y") as created_at')
        );
        $getGmsInvoice->where('is_deleted', 0);
        return $getGmsInvoice->paginate($request->per_page);
    }

    /**
     * @OA\Post(
     * path="/addDepartment",
     * summary="addDepartment",
     * operationId="add Department",
     *  tags={"Admin/Department"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     *   name="dept_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="dept_name",
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
    public function addDepartment()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'dept_code' => 'required|size:3',
            'dept_name' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['user_id'] = $adminSession->admin_id;
        $addDepartment = new GmsDept($input);
        $addDepartment->save();
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addDepartment);
    }

    public function editDepartment()
    {
        return $this->deprtEdit();
    }

    public function deleteDepartment()
    {
        return $this->deptDelete();
    }

    /**
     * @OA\Post(
     * path="/addDesignation",
     * summary="addDesignation",
     * operationId="add Designation",
     *  tags={"Admin/Designation"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     *   name="desg_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="desg_name",
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
    public function addDesignation()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'desg_code' => 'required',
            'desg_name' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addGmsDesg = new GmsDesg();
        $addGmsDesg->desg_code = $input['desg_code'];
        $addGmsDesg->desg_name = $input['desg_name'];
        $addGmsDesg->user_id = $adminSession->admin_id;
        $addGmsDesg->entry_date = Carbon::now()->toDateTimeString();
        $addGmsDesg->save($input);

        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addGmsDesg);
    }

    /**
     * @OA\Get(
     * path="/viewDesignation",
     * summary="viewDesignation",
     * operationId="view Designation",
     *  tags={"Admin/Designation"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewDesignation(Request $request)
    {
        $adminSession = session()->get('session_token');
        $getGmsDesg = GmsDesg::query();
        $getGmsDesg->select('id', 'desg_code', 'desg_name')->where('is_deleted', 0);
        return $getGmsDesg->paginate($request->per_page);
    }

    public function editDesignation()
    {
        return $this->designationEdit();
    }

    public function deleteDesignation()
    {
        return $this->designationDelete();
    }

    /**
     * @OA\Post(
     * path="/addEmpType",
     * summary="addEmpType",
     * operationId="add EmpType",
     *  tags={"Admin/EmpType"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     *   name="desg_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="integer"
     *  )
     * ),
     * @OA\Parameter(
     *   name="desg_name",
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
    public function addEmpType()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'emp_type_code' => 'required',
            'emp_type_name' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['user_id'] = $adminSession->admin_id;
        $addGmsEmpType = new GmsEmpType($input);
        $addGmsEmpType->save();

        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addGmsEmpType);
    }

    /**
     * @OA\Post(
     * path="/viewEmpType",
     * summary="viewEmpType",
     * operationId="view EmpType",
     *  tags={"Admin/EmpType"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewEmpType(Request $request)
    {
        $adminSession = session()->get('session_token');
        $getEmpType = GmsEmpType::query();
        $getEmpType->select('id', 'emp_type_code', 'emp_type_name')->where('is_deleted', 0);
        return $getEmpType->paginate($request->per_page);
    }

    public function deleteEmpType()
    {
        return $this->empTypeDelete();
    }

    public function editEmpType()
    {
        return $this->empTypeEdit();
    }


    public function adminEmpTypeList()
    {
        $emp_type_name = GmsEmpType::select('emp_type_code as value', 'emp_type_name AS label')->where('is_deleted', 0)->orderBy('emp_type_name', 'asc')->get();
        $data['label'] = 'emp_type_name';
        $data['options'] = $emp_type_name;
        $collection = new Collection([$data]);
        return $collection;
    }

    public function editAdminEmployeePhoto(Request $request)
    {
        $validator = Validator::make($this->request->all(), [

            'profile_image' => 'required',
            'emp_id' => 'required|exists:gms_emp,id',


        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsEmp = GmsEmp::where('id', $input['emp_id'])->where('is_deleted', 0)->first();

        $profile_name = substr($getGmsEmp->profile_image, strrpos($getGmsEmp->profile_image, '/') + 1);
        $profile_image_small = substr($getGmsEmp->profile_image_small, strrpos($getGmsEmp->profile_image_small, '/') + 1);


        if ($getGmsEmp) {
            if ($request->hasfile('profile_image')) {
                if (file_exists(public_path('/employee/' . $profile_name)) && isset($profile_name)) {
                    unlink(public_path('/employee/' . $profile_name));
                }
                //getting the file from view
                $image = $request->file('profile_image');

                //getting the extension of the file
                $image_ext = $image->getClientOriginalExtension();
                //changing the name of the file
                $new_image_name = rand(123456, 999999) . "." . $image_ext;
                $destination_path = public_path('/employee');
                $image->move($destination_path, $new_image_name);
                $input['profile_image'] = $new_image_name;
            }
            //==================================Crop Image ==================================================//
            if ($request->hasfile('profile_image_small')) {
                if (file_exists(public_path('/employee/thumbnail/' . $profile_image_small)) && isset($profile_image_small)) {
                    unlink(public_path('/employee/thumbnail/' . $profile_image_small));
                }
                $image = $request->file('profile_image_small');
                $input['profile_image_small'] = time() . '.' . $image->extension();
                $destinationPath = public_path('/employee/thumbnail');
                $img = Image::make($image->path());

                // ====================================[ Resize Image ]=========================================//

                $img->resize(150, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['profile_image_small']);

            }
            $editgetGmsEmp = GmsEmp::find($getGmsEmp->id);
            $editgetGmsEmp->update($input);

            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editgetGmsEmp);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }


    public function addAdminEmployee(Request $request)
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'emp_type' => 'required',
            'emp_rep_offtype' => 'required',
            'emp_rep_office' => 'required',
            'emp_code' => 'required|unique:gms_emp',
            'emp_num' => 'required|unique:gms_emp',
            'emp_name' => 'required',
            'emp_add1' => 'required',
            'emp_phone' => 'required|numeric|digits_between:10,10|unique:gms_emp',
            'emp_email' => 'required|email|unique:gms_emp',
            'emp_dob' => 'required|date|unique:gms_emp',
            'emp_education' => 'required',
            'emp_qualification' => 'required',
            'emp_doj' => 'required|date',
            'emp_dept' => 'required',
            'emp_dsg' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['user_id'] = $adminSession->admin_id;
        $input['emp_code'] = $this->request->emp_code;
        $input['emp_num'] = $this->request->emp_num;
        try {
            if ($request->hasfile('profile_image')) {
                //getting the file from view
                $image = $request->file('profile_image');

                //getting the extension of the file
                $image_ext = $image->getClientOriginalExtension();
                //changing the name of the file
                $new_image_name = rand(123456, 999999) . "." . $image_ext;
                $destination_path = public_path('/employee');
                $image->move($destination_path, $new_image_name);
                $input['profile_image'] = $new_image_name;
            }
            //==================================Crop Image ==================================================//
            if ($request->hasfile('profile_image_small')) {
                $image = $request->file('profile_image_small');
                $input['profile_image_small'] = time() . '.' . $image->extension();
                $destinationPath = public_path('/employee/thumbnail');
                $img = Image::make($image->path());

                // ====================================[ Resize Image ]=========================================//
                $img->resize(150, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['profile_image_small']);

            }
            $addGmsEmp = new GmsEmp($input);
            $addGmsEmp->save();
            if ($addGmsEmp->id) {

                $user_name = $input['emp_code'];
                $random_password = Str::random(14);
                $user_details['username'] = $user_name;
                $user_details['password'] = $random_password;
                $hashed_random_password = Hash::make($random_password);

                $header = $this->commonMgr->headerDetails();
                $data = $this->request->all();
                $data['username'] = $user_name;
                $data['password'] = $hashed_random_password;
                $data['office_id'] = $addGmsEmp->id;
                $data['name'] = $input['emp_name'];
                $data['email'] = $input['emp_email'];
                $data['phone'] = $input['emp_phone'];
                $data['address'] = $input['emp_add1'];
                $data['user_type'] = "EMP";
                $data['status'] = 1;
                $data['password_status'] = 1;
                $data['last_log_ip'] = $header['IP_ADDRESS'];
                $Admin = Admin::create($data)->id;

                return $this->successResponse(self::CODE_OK, "Added Successfully!!", $user_details);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, "Something Error!!", $e);
        }
    }

    /**
     * @OA\Get(
     * path="/viewEmployee",
     * summary="viewEmployee",
     * operationId="view Employee",
     *  tags={"Admin/Employee"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     *   name="emp_rep_office",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="emp_rep_offtype",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="status",
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
    public function viewEmployee(Request $request)
    {
        $emp = GmsEmp::join('gms_office', 'gms_office.office_type', '=', 'gms_emp.emp_rep_offtype')->select('gms_emp.id', 'gms_emp.emp_name', 'gms_emp.emp_code',
            DB::raw("CONCAT(gms_emp.emp_rep_offtype,'-',gms_emp.emp_rep_office) As reporting_office"),
            'gms_emp.emp_city', 'gms_emp.emp_phone', 'gms_emp.emp_dept', 'gms_emp.emp_work_type', 'gms_emp.emp_status', 'gms_emp.status');

        if ($request->has('office_type')) {
            $emp->where('gms_emp.emp_rep_offtype', $request->office_type);
        }
        if ($request->has('office_code')) {
            $emp->where('gms_emp.emp_rep_office', $request->office_code);
        }
        if ($request->has('status')) {
            $emp->where('gms_emp.status', $request->status);
        }
        if ($request->has('q')) {
            $q = $request->q;
            $emp->where('gms_emp.emp_name', 'LIKE', '%' . $q . '%')
                ->orWhere('gms_emp.emp_code', 'LIKE', '%' . $q . '%')
                ->orWhere('gms_emp.emp_rep_office', 'LIKE', '%' . $q . '%');
        }
        return $data = $emp->paginate($request->per_page);

    }

    public function editAdminEmployee(Request $request)
    {
        $validator = Validator::make($this->request->all(), [
            'emp_type' => 'required',
            'emp_rep_offtype' => 'required',
            'emp_rep_office' => 'required',
            'emp_code' => 'required|unique:gms_emp,emp_code,' . $this->request->emp_id,
            'emp_num' => 'required|unique:gms_emp,emp_num,' . $this->request->emp_id,
            'emp_name' => 'required',
            'emp_add1' => 'required',
            'emp_phone' => 'required|numeric|digits_between:10,10|unique:gms_emp,emp_phone,' . $this->request->emp_id,
            'emp_email' => 'required|unique:gms_emp,emp_email,' . $this->request->emp_id,
            'emp_dob' => 'required|unique:gms_emp,emp_dob,' . $this->request->emp_id,
            'emp_education' => 'required',
            'emp_qualification' => 'required',
            'emp_doj' => 'required|date',
            'emp_dept' => 'required',
            'emp_dsg' => 'required',
            'emp_id' => 'required',


        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsEmp = GmsEmp::where('id', $input['emp_id'])->where('is_deleted', 0)->first();

        if ($getGmsEmp) {
            if ($request->hasfile('profile_image')) {
                //getting the file from view
                $image = $request->file('profile_image');

                //getting the extension of the file
                $image_ext = $image->getClientOriginalExtension();
                //changing the name of the file
                $new_image_name = rand(123456, 999999) . "." . $image_ext;
                $destination_path = public_path('/employee');
                $image->move($destination_path, $new_image_name);
                $input['profile_image'] = $new_image_name;
            }
            //==================================Crop Image ==================================================//
            if ($request->hasfile('profile_image_small')) {

                $image = $request->file('profile_image_small');
                $input['profile_image_small'] = time() . '.' . $image->extension();
                $destinationPath = public_path('/employee/thumbnail');
                $img = Image::make($image->path());

                // ====================================[ Resize Image ]=========================================//
                $img->resize(150, 100, function ($constraint) {
                    $constraint->aspectRatio();
                })->save($destinationPath . '/' . $input['profile_image_small']);
            }
            $editgetGmsEmp = GmsEmp::find($getGmsEmp->id);
            $editgetGmsEmp->update($input);
            if ($editgetGmsEmp) {
                $admin = Admin::where('office_id', $input['emp_id'])->first();
                $data = $this->request->all();

                $header = $this->commonMgr->headerDetails();
                $data = $this->request->all();
                $data['username'] = $input['emp_code'];
                $data['office_id'] = $input['emp_id'];
                $data['name'] = $input['emp_name'];
                $data['email'] = $input['emp_email'];
                $data['phone'] = $input['emp_phone'];
                $data['address'] = $input['emp_add1'];
                $data['user_type'] = "EMP";
                $data['last_log_ip'] = $header['IP_ADDRESS'];

                $Admin = Admin::findOrFail($admin->id);
                $Admin->update($data);
            }
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editgetGmsEmp);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function adminViewEmployeeId()
    {
        $validator = Validator::make($this->request->all(), [
            'emp_code' => 'required|exists:gms_emp,emp_code',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $viewEmployee = GmsEmp::join('gms_dept', 'gms_dept.dept_code', '=', 'gms_emp.emp_dept')
            ->join('gms_desg', 'gms_desg.desg_code', '=', 'gms_emp.emp_dsg')
            ->where('gms_emp.emp_code', $input['emp_code'])->select('gms_emp.emp_code', 'gms_emp.emp_name', 'gms_emp.emp_add1', 'gms_emp.emp_add2', 'gms_emp.emp_phone', 'gms_emp.emp_email', 'gms_emp.emp_sex', 'gms_emp.emp_bldgrp', 'gms_emp.emp_dob', 'gms_emp.emp_doj', 'gms_dept.dept_name', 'gms_desg.desg_name', 'gms_emp.emp_status', 'gms_emp.emp_dor', 'gms_emp.emp_rep_offtype', 'gms_emp.emp_rep_office')->where('gms_emp.is_deleted', 0)->first();
        if (!$viewEmployee) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, 'Id Not Found');
        } else {
            return $this->successResponse(self::CODE_OK, "Show Employee Successfully!!", $viewEmployee);
        }
    }

    public function deleteAdminEmployee()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'emp_id' => 'required|exists:gms_emp,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsEmp = GmsEmp::where('id', $input['emp_id'])->where('user_id', $sessionObject->admin_id)->first();
        $getAdmin = Admin::where('office_id', $input['emp_id'])->first();
        if ($getGmsEmp != null) {
            $getGmsEmp->is_deleted = 1;
            $getGmsEmp->save();
            if (isset($getGmsEmp) && $getAdmin != null) {
                $getAdmin->is_deleted = 1;
                $getAdmin->save();
            }
            return $this->successResponse(self::CODE_OK, "Delete Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }


    /**
     * @OA\Get(
     * path="/viewLogin",
     * summary="viewLogin",
     * operationId="view Login",
     *  tags={"Admin/Login"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewLogin(Request $request)
    {
        $adminSession = session()->get('session_token');
        $status = $request->status;
        $office_type = $request->office_type;
        $keyword = $request->keyword;
        $getLogin = AdminSession::join('admin', 'admin.id', '=', 'admin_session.admin_id')
            ->join('gms_city', 'gms_city.city_code', '=', 'admin.city')->select(
                'admin_session.id',
                'admin.username',
                'admin.name',
                'admin.email',
                'gms_city.city_name',
                DB::raw("CONCAT(gms_city.city_name,'(',gms_city.city_code,')') As city"),
                'admin.updated_at',
                DB::raw('DATEDIFF(admin.updated_at, NOW()) as days'),
                'admin_session.is_active as login_status'
            )->where('admin_session.is_deleted', 0)->whereNotIn('admin.user_type', ['ADMIN']);

        if ($request->has('status')) {
            $getLogin->where('admin_session.is_active', $status);
        }
        if ($request->has('office_type')) {
            $getLogin->where('admin.user_type', $office_type);
        }
        if ($request->has('keyword')) {
            $keyword = $request->keyword;
            $getLogin->orwhere('admin.name', 'LIKE', '%' . $keyword . '%');
            $getLogin->OrWhere('admin.username', 'LIKE', '%' . $keyword . '%');
            $getLogin->OrWhere('gms_city.city_name', 'LIKE', '%' . $keyword . '%');
            $getLogin->OrWhere('gms_city.city_code', 'LIKE', '%' . $keyword . '%');
        }
        return $getLogin->paginate($request->per_page);
    }

    public function deleteLogin()
    {
        return $this->loginDelete();
    }

    public function editLogin()
    {
        return $this->loginEdit();
    }


    /**
     * @OA\Get(
     * path="/viewTaxType",
     * summary="viewTaxType",
     * operationId="view TaxType",
     *  tags={"Admin/TaxType"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewTaxType(Request $request)
    {
        $adminSession = session()->get('session_token');
        $getTaxType = GmsTaxType::query();
        $getTaxType->select('id', 'tax_name', 'rate', 'from_date', 'status')->where('is_deleted', 0);
        return $getTaxType->paginate($request->per_page);
    }

    /**
     * @OA\Post(
     * path="/addTaxType",
     * summary="addTaxType",
     * operationId="add TaxType",
     *  tags={"Admin/TaxType"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     *   name="tax_name",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="rate",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
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
     *   name="status",
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
    public function addTaxType()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'tax_name' => 'required|unique:gms_tax_type',
            'rate' => 'required|numeric',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['status'] = 1;
        $from_date = Carbon::now()->toDateTimeString();
        $date_from = Carbon::parse($from_date);
        $to_date = $date_from->addMonths(1);
        $input['from_date'] = $from_date;
        $input['to_date'] = $to_date;
        $addGmsTaxType = new GmsTaxType($input);
        $addGmsTaxType->save();
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addGmsTaxType);
    }

    public function editTaxType()
    {
        return $this->taxTypeEdit();
    }

    public function deleteTaxType()
    {
        return $this->taxTypeDelete();
    }

    public function viewTaxTypeHistory(Request $request)
    {
        $adminSession = session()->get('session_token');
        $getGmsTaxTypeHistory = GmsTaxTypeHistory::select(
            'id',
            'tax_name',
            'rate',
            'from_date',
            'to_date',
            'status'

        )->get();
        $getGmsTaxTypeHistory->where('is_deleted', 0);
        return $getGmsTaxTypeHistory;
    }

    /**
     * @OA\Get(
     * path="/viewFuelCharges",
     * summary="viewFuelCharges",
     * operationId="view FuelCharges",
     *  tags={"Admin/FuelCharges"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewFuelCharges(Request $request)
    {
        $adminSession = session()->get('session_token');
        $getFuelCharges = GmsFuelCharges::query();
        $getFuelCharges->select(
            'gms_fuel_charges.id',
            'gms_fuel_charges.from_price',
            'gms_fuel_charges.to_price',
            'gms_fuel_charges.charged_percentage',

            DB::raw('DATE_FORMAT(gms_fuel_charges.posted_month, "%b, %Y") as month')

        );
        $getFuelCharges->where('is_deleted', 0);
        return $getFuelCharges->paginate($request->per_page);
    }

    /**
     * @OA\Post(
     * path="/addFuelCharges",
     * summary="addFuelCharges",
     * operationId="add FuelCharges",
     *  tags={"Admin/FuelCharges"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     *   name="from_price",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="to_price",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="charged_percentage",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="posted_month",
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
    public function addFuelCharges()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'from_price' => 'required|numeric',
            'to_price' => 'required|numeric',
            'charged_percentage' => 'required|numeric',
            'posted_month' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addGmsFuelCharges = new GmsFuelCharges($input);
        $addGmsFuelCharges->save();
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addGmsFuelCharges);
    }

    public function editFuelCharges()
    {
        return $this->fuelChargesEdit();
    }

    public function deleteFuelCharges()
    {
        return $this->fuelChargesDelete();
    }

    public function viewFuelDefaultCharges(Request $request)
    {
        $adminSession = session()->get('session_token');
        $getGmsFuelDefaultCharges = GmsFuelDefaultCharges::select(
            'id',
            'fuel_price as fuel_price_index',
            'fuel_date_from as date_from',
            'fuel_date_to as date_to'

        )->get();
        return $getGmsFuelDefaultCharges;
    }

    public function adminAddFuelDefaultCharges(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($request->all(), [
            'fuel_price' => 'required',
            'fuel_date_from' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $date_from = Carbon::parse($this->request->fuel_date_from);
        $to_date = $date_from->addMonths(1);
        $data = $this->request->all();
        $data['fuel_date_to'] = $to_date->format('Y-m-d');
        $addGmsFuelDefaultCharges = new GmsFuelDefaultCharges($data);
        $addGmsFuelDefaultCharges->save();
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addGmsFuelDefaultCharges);
    }

    public function addZoneRateCard()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'zone_service_type' => 'required',
            'zone_book_service' => 'required',
            'rate' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addRateZoneSer = new GmsRateZoneService($input);
        $addRateZoneSer->save();
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addRateZoneSer);
    }

    public function addRate()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'service_type' => 'required',
            'mode' => 'required',

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['user_id'] = $adminSession->admin_id;
        $addRate = new GmsRateZone($input);
        $addRate->save();
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addRate);
    }

    /**
     * @OA\Get(
     * path="/viewRateMaster",
     * summary="viewRateMaster",
     * operationId="view RateMaster",
     *  tags={"Admin/RateMaster"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewRateMaster(Request $request)
    {
        $adminSession = session()->get('session_token');
        $getRateMaster = GmsRateMaster::query();
        $getRateMaster->where('is_deleted', 0);
        return $getRateMaster->paginate($request->per_page);
    }

    /**
     * @OA\Get(
     * path="/addRateMaster",
     * summary="addRateMaster",
     * operationId="add RateMaster",
     *  tags={"Admin/RateMaster"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     *   name="unique_rate_id",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="scheme_rate_id",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="flat_rate",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="min_charge_wt",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="from_wt",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="to_wt",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="rate",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="tranship_rate",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="addnl_type",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="addnl_wt",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="addnl_min",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="addnl_max",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="addnl_fixed",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="addnl_rate",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="extra_rate",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="tat",
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
    public function addRateMaster()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'unique_rate_id' => 'numeric',
            'scheme_rate_id' => 'numeric',
            'flat_rate' => 'numeric',
            'min_charge_wt' => 'numeric',
            'from_wt' => 'numeric',
            'to_wt' => 'numeric',
            'rate' => 'numeric',
            'tranship_rate' => 'numeric',
            'addnl_type' => 'numeric',
            'addnl_wt' => 'numeric',
            'addnl_min' => 'numeric',
            'addnl_max' => 'numeric',
            'addnl_fixed' => 'numeric',
            'addnl_rate' => 'numeric',
            'extra_rate' => 'numeric',
            'tat' => 'numeric',
            'status' => 'numeric',
            'approved_status' => 'numeric',
            'entry_date' => 'date'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['user_id'] = $adminSession->admin_id;
        $addGmsRateMaster = new GmsRateMaster($input);
        $addGmsRateMaster->save();
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addGmsRateMaster);
    }

    public function editRateMaster()
    {
        return $this->rateMasterEdit();
    }

    /**
     * @OA\Post(
     * path="/addGst",
     * summary="addGst",
     * operationId="add Gst",
     *  tags={"Admin/Gst"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     *   name="gst_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="gst_rate",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
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
    public function addGst()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'gst_code' => 'required|unique:gms_gst',
            'gst_rate' => 'required|numeric',
            'from_date' => 'date'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addGmsGst = new GmsGst($input);
        $addGmsGst->save();
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addGmsGst);
    }

    public function editGst()
    {
        return $this->gstEdit();
    }

    public function viewGST()
    {
        $adminSession = session()->get('session_token');
        $GmsGst = GmsGst::query();
        $GmsGst->select('id', 'gst_code', 'gst_rate', 'from_date');
        $data = $GmsGst->where('is_deleted', 0)->get();
        return $data;
    }

    /**
     * @OA\Post(
     * path="/addPincode",
     * summary="addPincode",
     * operationId="add Pincode",
     *  tags={"Admin/Pincode"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
     * )
     * ),
     * @OA\Parameter(
     *   name="pincode_value",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="service",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="city_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="rep_code",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="courier",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="gold",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="logistics",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="intracity",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="international",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="regular",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="topay",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="cod",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="topay_cod",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="oda",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="mentioned_piece",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fov_or",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="fov_cr",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="isc",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="edl",
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
    public function addPincode()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [

            'pincode_value' => 'numeric|digits:6|unique:gms_pincode',
            'service' => 'size:1',
            'city_code' => 'max:5',
            'rep_code' => 'max:5',
            'courier' => 'max:1',
            'gold' => 'max:1',
            'logistics' => 'max:1',
            'intracity' => 'max:1',
            'international' => 'max:1',
            'regular' => 'max:1',
            'topay' => 'max:1',
            'cod' => 'max:1',
            'topay_cod' => 'max:1',
            'oda' => 'max:1',
            'mentioned_piece' => 'max:1',
            'fov_or' => 'max:1',
            'fov_cr' => 'max:1',
            'isc' => 'max:1',
            'edl' => 'max:1',
            'pin_status' => 'max:1',
            'posted' => 'date'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['user_id'] = $adminSession->admin_id;
        $addPincode = new GmsPincode($input);
        $addPincode->save();

        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addPincode);
    }


    public function adminEditPincode(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $admin = Admin::where('id', $sessionObject->admin_id)->first();
        $validator = Validator::make($request->all(), [
            'city_code' => 'max:5',
            'pincode_value' => 'numeric|digits:6|unique:gms_pincode,pincode_value,' . $this->request->pincode_id,
            'service' => 'size:1',
            'courier' => 'max:1',
            'gold' => 'max:1',
            'logistics' => 'max:1',
            'topay' => 'max:1',
            'cod' => 'max:1',
            'pincode_id' => 'required'

        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['user_id'] = $sessionObject->admin_id;
        $GmsPincode = GmsPincode::findOrFail($this->request->pincode_id);
        $GmsPincode->update($input);
        if ($GmsPincode) {
            return $this->successResponse(self::CODE_OK, "Update Successfully!!", $GmsPincode);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Id Not Found");
        }

    }

    /**
     * @OA\Get(
     * path="/viewInvoice",
     * summary="viewInvoice",
     * operationId="view RateMaster",
     *  tags={"Admin/RateMaster"},
     * @OA\Parameter(
     * name="SESSION-TOKEN",
     * in="header",
     * required=true,
     * @OA\Schema(
     * type="string"
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
    public function viewInvoice(Request $request)
    {
        $adminSession = session()->get('session_token');
        $getInvoice = GmsInvoice::query();
        $getInvoice->select('invoice_no', 'invoice_date', 'cust_type', 'customer_code', 'from_date', 'to_date', 'ac_invoice_no', 'invoice_edit_status', 'invoice_status');
        $getInvoice->where('is_deleted', 0);
        return $getInvoice->paginate($request->per_page);
    }

    public function deleteInvoice()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'invoice_no' => 'required|exists:gms_invoice,invoice_no',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getInvoiceNo = GmsInvoice::where('invoice_no', $input['invoice_no'])->first();
        if ($getInvoiceNo != null) {
            $getInvoiceNo->is_deleted = 1;
            $getInvoiceNo->save();
            return $this->successResponse(self::CODE_OK, "Delete Invoice Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function editPincode()
    {
        return $this->pincodeEdit();
    }

    public function editProfile()
    {
        return $this->profileEdit();
    }

    public function addApiTracking()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'from_time' => 'date_format:H:i',
            'to_time' => 'date_format:H:i|after:from_time',
            'update_date' => 'date',
            'entry_date' => 'date',
            'status' => 'max:1|numeric'
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['customer_code'] = implode(',', $input['customer_code']);
        $addApi = new GmsApi($input);
        $addApi->save();
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addApi);
    }

    public function viewApiTracking(Request $request)
    {
        $adminSession = session()->get('session_token');
        $getGmsApi = GmsApi::query();
        $getGmsApi->select(
            'vendor_name',
            'api_url',
            'customer_code'
        );
        $getGmsApi->where('is_deleted', 0);
        return $getGmsApi->paginate($request->per_page);
    }

    public function addAlertsConfig()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'display' => 'required',
            'message' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $addAlertConfig = new GmsAlertConfig($input);
        $addAlertConfig->save();

        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addAlertConfig);
    }

    public function editAlertsConfig()
    {
        return $this->alertsConfigEdit();
    }

    public function editPincodeStatus()
    {
        return $this->pincodeStatusEdit();
    }

    public function editOfficeStatus()
    {
        return $this->officeStatusEdit();
    }

    public function editCustomerApprovedStatus()
    {
        return $this->customerApprovedStatusEdit();
    }

    public function editComplaintStatus()
    {
        return $this->complaintStatusStatusEdit();
    }

    public function editBookRoIssueStatus()
    {
        return $this->bookRoIssueStatusEdit();
    }

    public function editBookRoTransferStatus()
    {
        return $this->bookRoTransferStatusEdit();
    }

    public function editEmployeeAdminStatus()
    {
        return $this->employeeAdminStatusEdit();
    }

    public function editTaxTypeStatus()
    {
        return $this->taxTypeStatusEdit();
    }

    public function viewAllAdminComplaints(Request $request)
    {
        $input = $this->request->all();
        $getViewComplaintsDetails = GmsComplaint::leftjoin('admin', 'gms_complaint.userid', '=', 'admin.id')
            ->leftjoin('gms_complaint_reply', 'gms_complaint.id', '=', 'gms_complaint_reply.log_no')->select('gms_complaint.id',
                DB::raw('CONCAT("GMSCQ","-",gms_complaint.id) AS complaint_no'),
                'gms_complaint.log_cnno',
                'gms_complaint.consignee_name',
                'gms_complaint.consignee_mobile_no',
                'gms_complaint.consignor_name',
                'gms_complaint.consignor_mobile_no',
                'gms_complaint.description',
                'gms_complaint.entry_date',
                'admin.username',
                'gms_complaint.closed_date',
                'gms_complaint.status',
                DB::raw("count(gms_complaint_reply.log_no) as reply")
            )->groupBy('gms_complaint.id');
        $getViewComplaintsDetails->where('gms_complaint.is_deleted', 0);
        if ($request->has('q')) {
            $q = $request->q;
            $getViewComplaintsDetails->where('gms_complaint.log_cnno', 'LIKE', '%' . $q . '%')
                ->orWhere('gms_complaint.consignee_mobile_no', 'LIKE', '%' . $q . '%')
                ->orWhere('gms_complaint.consignor_mobile_no', 'LIKE', '%' . $q . '%');
            return $getViewComplaintsDetails->paginate($request->per_page);
        } elseif ($request->isMethod('get')) {
            return $getViewComplaintsDetails->paginate($request->per_page);
        } elseif ($request->has('complain_no')) {
            $getComplaintReplay = GmsComplaintReply::leftjoin('admin', 'gms_complaint_reply.userid', '=', 'admin.id')->select('gms_complaint_reply.description', 'admin.username as replied_by', 'gms_complaint_reply.entry_date as replied_date');
            $getComplaintReplay->where('gms_complaint_reply.log_no', $input['complain_no']);
            $data = $getComplaintReplay->get();
            return $data;
        } else {
            return 'Data Not Found';
        }
    }

    public function editWeight(Request $request)
    {
        $year = $request->year;
        $month = $request->month;
        $range = $request->range;

        $value = explode('_', $range);
        $min = $value[0];
        $max = $value[1];
        $getWeightList = GmsBookingDtls::join('gms_customer', 'gms_booking_dtls.book_cust_code', '=', 'gms_customer.cust_name')->select('gms_booking_dtls.book_cnno', 'gms_booking_dtls.book_mfdate', 'gms_booking_dtls.book_mfno', 'gms_booking_dtls.book_br_code', 'gms_booking_dtls.book_emp_code', DB::raw('concat("[",gms_booking_dtls.book_cust_code,",",gms_customer.cust_name,"]")As customer'), 'gms_booking_dtls.book_pcs', 'gms_booking_dtls.book_pin', 'gms_booking_dtls.book_weight', 'gms_booking_dtls.book_service_type', 'gms_booking_dtls.book_billamt', 'gms_booking_dtls.book_total_amount', 'gms_booking_dtls.invoice_no');
        // $getWeightList->groupBy('gms_booking_dtls.book_mfno');
        if ($range) {
            $getWeightList->whereBetween('gms_booking_dtls.book_weight', [$min, $max]);
        }
        if ($request->has('year')) {
            $getWeightList->whereYear('book_mfdate', $year);
        }
        if ($request->has('month')) {
            $getWeightList->whereMonth('book_mfdate', $month);
        }
        return $getWeightList->paginate($request->per_page);
    }

    public function adminOfficeTypeList()
    {
        $office_type = GmsOffice::select('office_type as value', 'office_type as label')->where('is_deleted', 0)->groupBy('office_type')->orderBy('office_type', 'asc')->get();
        $data['label'] = 'office_type';
        $data['options'] = $office_type;
        $collection = new Collection([$data]);
        return $collection;
    }

    public function adminOfficeList()
    {
        $office_name = GmsOffice::select('office_code as value', DB::raw('CONCAT(office_name ,"(",office_code ,")") AS label'))->where('is_deleted', 0)->where('office_type', $this->request->office_type)->orderBy('office_name', 'asc')->get();
        $data['label'] = 'office_name';
        $data['options'] = $office_name;
        $collection = new Collection([$data]);
        return $collection;
    }

    public function adminOfficeRoTypeList()
    {
        $ro_office_name = GmsOffice::select('office_code as value', DB::raw('CONCAT(office_code ,"-",office_name) AS label'))->where('is_deleted', 0)->where('office_type', "RO")->orderBy('office_name', 'asc')->get();
        $data['label'] = 'ro_office_name';
        $data['options'] = $ro_office_name;
        $collection = new Collection([$data]);
        return $collection;
    }

    public function adminComplainReply()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'log_no' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['userid'] = $sessionObject->admin_id;
        $addGmsComplaitReply = new GmsComplaintReply($input);
        $addGmsComplaitReply->save();
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addGmsComplaitReply);
    }

    public function adminViewComplainReply(Request $request)
    {
        $GmsComplaintReply = GmsComplaintReply::where('log_no', $this->request->log_no)->orderBy('entry_date', 'desc')->get();
        return $GmsComplaintReply;
    }

    public function adminAddPayment(Request $request)
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($request->all(), [
            'office_type' => 'required',
            'deposit_DD' => 'image|mimes:jpeg,jpg,png,gif',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }

        if ($request->hasfile('deposit_DD')) {
            $image = $request->file('deposit_DD');
            $image_ext = $image->getClientOriginalExtension();
            $deposit_DD = rand(123456, 999999) . "." . $image_ext;
            $destination_path = public_path('/adminPayment');
            $image->move($destination_path, $deposit_DD);
        }
        $GmsPaymentOffice = GmsPaymentOffice::insert([
            'invoice_receipt' => $request->invoice_receipt,
            'office_type' => $request->office_type,
            'office_code' => $request->office_code,
            'paid_through' => $request->paid_through,
            'bank_name' => $request->bank_name,
            'check_no' => $request->check_no,
            'check_date' => $request->check_date,
            'amount' => $request->amount,
            'description' => $request->description,
            'deposit_DD' => isset($deposit_DD) ? $deposit_DD : '',
        ]);
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $GmsPaymentOffice);
    }

    public function adminViewPayment(Request $request)
    {
        $GmsPaymentOffice = GmsPaymentOffice::select('office_code as office_type', 'office_type as office',
            DB::raw('COUNT(office_code) As no_of_transaction'),
            'deposit_DD', 'amount', 'date as last_trans_date')->where('is_deleted', 0)->get();
        return $GmsPaymentOffice;
    }

    public function adminEditPayment(Request $request)
    {
        $validatedData = $request->validate([
            'office_type' => 'required',
            'deposit_DD' => 'image|mimes:jpeg,jpg,png,gif',
            'id' => 'required',
        ]);
        $getGmsPaymentOffice = GmsPaymentOffice::where('id', $request->id)->where('is_deleted', 0)->first();
        $sessionObject = session()->get('session_token');
        $editGmsPaymentOffice = GmsPaymentOffice::find($getGmsPaymentOffice->id);
        if ($request->hasfile('deposit_DD')) {
            $image = $request->file('deposit_DD');
            $image_ext = $image->getClientOriginalExtension();
            $deposit_DD = rand(123456, 999999) . "." . $image_ext;
            $data['deposit_DD'] = $deposit_DD;
            $destination_path = public_path('/adminPayment');
            $image->move($destination_path, $deposit_DD);
        }
        $editGmsPaymentOffice->invoice_receipt = $request->invoice_receipt;
        $editGmsPaymentOffice->office_type = $request->office_type;
        $editGmsPaymentOffice->office_code = $request->office_code;
        $editGmsPaymentOffice->paid_through = $request->paid_through;
        $editGmsPaymentOffice->bank_name = $request->bank_name;
        $editGmsPaymentOffice->check_no = $request->check_no;
        $editGmsPaymentOffice->check_date = $request->check_date;
        $editGmsPaymentOffice->amount = $request->amount;
        $editGmsPaymentOffice->description = $request->description;
        $editGmsPaymentOffice->posted_date = $request->posted_date;
        $editGmsPaymentOffice->deposit_DD = isset($deposit_DD) ? $deposit_DD : '';

        $data[] = $editGmsPaymentOffice->toArray();
        $editGmsPaymentOffice->update($data);
        if ($editGmsPaymentOffice) {
            $editGmsPaymentOffice->deposit_DD = URL::to('/') . '/public/adminPayment/' . $deposit_DD;
        }
        return $this->successResponse(self::CODE_OK, "Update Successfully!!", $editGmsPaymentOffice);
    }

    public function adminDeletePayment()
    {
        $validator = Validator::make($this->request->all(), [
            'payment_id' => 'required|exists:gms_payment_office,id',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $getGmsPaymentOffice = GmsPaymentOffice::where('id', $input['payment_id'])->first();;
        if ($getGmsPaymentOffice != null) {
            $getGmsPaymentOffice->is_deleted = 1;
            $getGmsPaymentOffice->save();
            return $this->successResponse(self::CODE_OK, "Delete Successfully!!");
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "Payment ID Not Found");
        }
    }

    public function adminCountryList()
    {
        $country = GmsCountries::select(
            'id as value',
            DB::raw('CONCAT(countries_name,"(",countries_iso_code_2,")") As label'),
        )->where('is_deleted', 0)->orderBy('countries_name', 'asc')->get();
        $data['label'] = 'country';
        $data['options'] = $country;
        $collection = new Collection([$data]);
        return $collection;
    }

    public function adminZoneList()
    {
        $zone = GmsZone::join('gms_countries', 'gms_countries.id', '=', 'gms_zone.country_id')->select(
            DB::raw('CONCAT(gms_countries.id,"_",gms_zone.id) As value'),
            DB::raw('CONCAT(gms_countries.countries_name,"(",gms_countries.countries_iso_code_2,")","-",gms_zone.zone_name,"(",gms_zone.zone_code,")") As label'),
        )->where('gms_zone.is_deleted', 0)->orderBy('gms_zone.zone_name', 'asc')->get();
        $data['label'] = 'zone';
        $data['options'] = $zone;
        $collection = new Collection([$data]);
        return $collection;
    }

    public function adminEditComplaints()
    {
        $sessionObject = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'complaint_id' => 'required|exists:gms_complaint,id',
            'log_cnno' => 'required',
            'consignor_mobile_no' => 'numeric|unique:gms_complaint,consignor_mobile_no,' . $this->request->complaint_id,
            'description' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $input['userid'] = $sessionObject->admin_id;
        $getGmsComplaint = GmsComplaint::where('id', $input['complaint_id'])->where('is_deleted', 0)->first();
        if ($getGmsComplaint) {
            $editGmsComplaint = GmsComplaint::find($getGmsComplaint->id);
            $editGmsComplaint->update($input);
            return $this->successResponse(self::CODE_OK, "Complaint Update Successfully!!", $editGmsComplaint);
        } else {
            return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, "ID Not Found");
        }
    }

    public function adminPurchaseList()
    {
        $purchase = GmsBookPurchase::select('purchase_invoice_no as value', 'purchase_invoice_no as label')->where('is_deleted', 0)->orderBy('purchase_invoice_no', 'asc')->get();
        $data['label'] = 'purchase';
        $data['options'] = $purchase;
        $collection = new Collection([$data]);
        return $collection;
    }

    public function addCutofTime()
    {
        $adminSession = session()->get('session_token');
        $validator = Validator::make($this->request->all(), [
            'office_type' => 'required',
            'office_code' => 'required',
            'from_time' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }

        $from_time = Carbon::parse($this->request->from_time);
        $to_date = $from_time->addDays(1);
        $data = $this->request->all();
        $data['to_time'] = $to_date->format('Y-m-d H:i:s');
        $data['created_by'] = $adminSession->admin_id;
        $data['status'] = 0;

        $addGmsOfficeTimeBlock = new GmsOfficeTimeBlock($data);
        $addGmsOfficeTimeBlock->save();
        return $this->successResponse(self::CODE_OK, "Added Successfully!!", $addGmsOfficeTimeBlock);
    }

    public function viewCutofTime(Request $request)
    {
        $adminSession = session()->get('session_token');
        $getGmsOfficeTimeBlock = GmsOfficeTimeBlock::select(
            'id',
            'office_type',
            'office_code',
            'block_type',
            'from_time',
            'to_time',
            'status',
        )->get();
        return $getGmsOfficeTimeBlock;
    }

    public function AdminAdvanceSearchIpmf(Request $request)
    {
        $from_date = $this->request->from_date;
        $to_date = $this->request->to_date;
        $pmf_mode = $this->request->pmf_mode;
        //$manifest_type = $this->request->manifest_type;
        $pmf_dest_ro = $this->request->pmf_dest_ro;
        $pmf_dest = $this->request->pmf_dest;
        $pmf_no = $this->request->pmf_no;
        $pmf_cnno = $this->request->pmf_cnno;

        $advanceSearchIpmf = GmsPmfDtls::join('gms_office', 'gms_office.office_code', '=', 'gms_pmf_dtls.pmf_origin')->select(
            DB::raw('CONCAT(gms_pmf_dtls.pmf_origin,"-",gms_office.office_name) AS origin_branch'),
            'gms_pmf_dtls.pmf_no as opmf',
            DB::raw('concat("[",gms_pmf_dtls.pmf_origin,"-",gms_pmf_dtls.pmf_dest,"]")As manifest_type'),
            DB::raw('SUM(gms_pmf_dtls.pmf_pcs)As no_cnno'),
            DB::raw('SUM(gms_pmf_dtls.pmf_received_pcs) As cnno_received'),
            DB::raw('concat(SUM(gms_pmf_dtls.pmf_pcs)- SUM(gms_pmf_dtls.pmf_received_pcs)) As not_received'),

        );
        $advanceSearchIpmf->groupBy('gms_pmf_dtls.pmf_no');

        if ($request->has('from_date') && $request->has('to_date')) {
            $advanceSearchIpmf->whereBetween('gms_pmf_dtls.pmf_date', [$from_date, $to_date]);
        }
        if ($request->has('pmf_dest_ro')) {
            $advanceSearchIpmf->Where('gms_pmf_dtls.pmf_dest_ro', $pmf_dest_ro);
        }
        if ($request->has('pmf_mode')) {
            $advanceSearchIpmf->where('gms_pmf_dtls.pmf_mode', $pmf_mode);
        }
        if ($request->has('pmf_dest')) {
            $advanceSearchIpmf->Where('gms_pmf_dtls.pmf_dest', $pmf_dest);
        }
        if ($request->has('pmf_no')) {
            $advanceSearchIpmf->where('gms_pmf_dtls.pmf_no', $pmf_no);
        }
        if ($request->has('pmf_cnno')) {
            $advanceSearchIpmf->where('gms_pmf_dtls.pmf_cnno', $pmf_cnno);
        }
        $data = $advanceSearchIpmf->get();
        return $data;

    }

    public function AdminViewIpmf()
    {
        $input = $this->request->all();
        $response = array();
        $response['ipmf'] = GmsPmfDtls::join('gms_office as original', 'original.office_code', '=', 'gms_pmf_dtls.pmf_origin')
            ->join('gms_office as dest', 'dest.office_code', '=', 'gms_pmf_dtls.pmf_dest')
            ->join('gms_city', 'gms_pmf_dtls.pmf_city', '=', 'gms_city.city_code')
            ->select(
                'gms_pmf_dtls.pmf_no as manifest_no',
                DB::raw('CONCAT(gms_pmf_dtls.pmf_origin," ",original.office_name) AS origin_branch'),
                DB::raw('CONCAT(gms_pmf_dtls.pmf_dest," ",dest.office_name) AS dest_branch'),
                'gms_pmf_dtls.pmf_mode as mode',
                DB::raw('concat(gms_pmf_dtls.pmf_date," ",gms_pmf_dtls.pmf_time )As date'),
                DB::raw("CONCAT('') As manifest_type")

            )->where('gms_pmf_dtls.pmf_no', $input['pmf_no'])->first();
        $response['pending'] = GmsPmfDtls::join('gms_city', 'gms_pmf_dtls.pmf_city', '=', 'gms_city.city_code')->whereColumn('pmf_pcs', '<>', 'pmf_received_pcs')->where('pmf_no', $input['pmf_no'])->select('pmf_cnno as cnno', 'pmf_wt as weight', 'pmf_pcs as pcs', 'pmf_pin as pincode', 'gms_city.city_name', 'pmf_doc as doc', DB::raw("CONCAT('') As topay"), DB::raw("CONCAT('') As cod"), 'pmf_status as status', 'pmf_remarks as remark')->get();
        $response['Complete'] = GmsPmfDtls::join('gms_city', 'gms_pmf_dtls.pmf_city', '=', 'gms_city.city_code')->whereColumn('pmf_pcs', '=', 'pmf_received_pcs')->where('pmf_no', $input['pmf_no'])
            ->select('pmf_cnno as cnno', 'pmf_wt as weight', 'pmf_pcs as pcs', 'pmf_pin as pincode', 'gms_city.city_name', 'pmf_doc as doc', DB::raw("CONCAT('') As topay"), DB::raw("CONCAT('') As cod"), 'pmf_status as status', 'pmf_remarks as remark')->get();
        $response['total'] = GmsPmfDtls::select(DB::raw('SUM(pmf_wt) as total_weight'), DB::raw('COUNT(pmf_pcs) as total_cnno'), DB::raw('SUM(pmf_pcs) as total_pcs'), DB::raw('SUM(pmf_vol_wt) as total_vol_amount'))->where('pmf_no', $input['pmf_no'])->where('is_deleted', 0)->first();
        $response['actua_packet_wight'] = GmsPmfDtls::select(DB::raw('sum(pmf_actual_wt) as totalActualwt'))->where('pmf_no', $input['pmf_no'])->first();
        return $response;

    }

    public function AdminViewTableIpmf()
    {
        $input = $this->request->all();
        $response = array();
        $response['pending'] = GmsPmfDtls::whereColumn('pmf_pcs', '<>', 'pmf_received_pcs')->where('pmf_no', $input['pmf_no'])->select('pmf_cnno as cnno', 'pmf_cnno_type AS cnno_type', 'pmf_wt as weight', 'pmf_pcs as pcs', 'pmf_pin as pincode', 'pmf_city', 'pmf_remarks as remark', 'pmf_received_by as incomed_by', 'pmf_status as status', 'created_at AS created_date', 'pmf_received_date as received_date')->get();
        $response['Complete'] = GmsPmfDtls::whereColumn('pmf_pcs', '=', 'pmf_received_pcs')->where('pmf_no', $input['pmf_no'])
            ->select('pmf_cnno as cnno', 'pmf_cnno_type AS cnno_type', 'pmf_wt as weight', 'pmf_pcs as pcs', 'pmf_pin as pincode', 'pmf_city', 'pmf_remarks as remark', 'pmf_received_by as incomed_by', 'pmf_status as status', 'created_at AS created_date', 'pmf_received_date as received_date')->get();
        return $response;

    }

    public function AdminConsignmentTracking()
    {
        $input = $this->request->all();
        $booking_details[] = GmsBookingDtls::join('gms_customer',
            'gms_booking_dtls.book_cust_code', '=', 'gms_customer.cust_code')->
        join('gms_office', 'gms_booking_dtls.book_br_code', '=', 'gms_office.office_code')->
        join('gms_emp', 'gms_booking_dtls.book_emp_code', '=', 'gms_emp.emp_code')->
        join('gms_city', 'gms_booking_dtls.book_dest', '=', 'gms_city.city_code')->select('gms_booking_dtls.book_cnno as conginment_no', 'gms_booking_dtls.book_mfdate as booking_date', 'gms_booking_dtls.book_refno as refNo', 'gms_booking_dtls.book_weight as weight', 'gms_booking_dtls.book_vol_weight as vol_weight', DB::raw('concat(gms_booking_dtls.book_cust_code,"/",gms_customer.cust_la_ent) As customer'), 'gms_booking_dtls.book_mfno', DB::raw('concat(gms_booking_dtls.book_br_code,"/",gms_office.office_name) As booking_branch'), DB::raw('concat(gms_booking_dtls.book_emp_code,"/",gms_emp.emp_name) As booking_branch'), 'gms_booking_dtls.book_invno as invoice_dtls', 'gms_city.city_name', 'gms_booking_dtls.book_pin as pincode', 'gms_booking_dtls.book_mode as mode', 'gms_booking_dtls.book_cod as cod_value', 'gms_booking_dtls.book_topay as topay_value', 'gms_booking_dtls.book_doc as doc_type', 'gms_booking_dtls.book_pcs as pcs', 'gms_booking_dtls.book_cons_dtl as consignor_dtls', 'gms_booking_dtls.book_cn_name as consignor_name', 'gms_booking_dtls.book_cn_name as consignor_name', 'gms_booking_dtls.book_cn_mobile as consignor_number', 'gms_booking_dtls.book_cons_mobile as consignee_name', 'gms_booking_dtls.book_remarks as remark')->where('gms_booking_dtls.book_cnno', $input['cnno_no'])->first();

        $gmsPmfDtls[] = GmsPmfDtls::join('gms_office', 'gms_pmf_dtls.pmf_origin', '=', 'gms_office.office_code')->join('gms_emp', 'gms_pmf_dtls.pmf_emp_code', '=', 'gms_emp.emp_code')->select('gms_pmf_dtls.pmf_no as opmf', DB::raw('concat(gms_pmf_dtls.pmf_origin,"/",gms_office.office_name) As origin_branch'), 'gms_pmf_dtls.pmf_date as opmf_date', 'gms_pmf_dtls.pmf_time as opmf_time', 'gms_pmf_dtls.pmf_pcs as pcs', DB::raw('concat(gms_pmf_dtls.pmf_wt,"/",gms_pmf_dtls.pmf_vol_wt) As wt_vol_wt'), DB::raw('concat(gms_pmf_dtls.pmf_dest,"/",gms_office.office_name) As dest_branch'), 'gms_pmf_dtls.pmf_received_date as in_date_time', DB::raw('concat(gms_pmf_dtls.pmf_received_wt,"/",gms_pmf_dtls.pmf_vol_received_wt) As receive_vol_wt'), DB::raw('concat(gms_pmf_dtls.pmf_origin,"/",gms_pmf_dtls.pmf_emp_code) As dest_branch'))->where('gms_pmf_dtls.pmf_cnno', $input['cnno_no'])->first();
        $gmsDmfDtls[] = GmsDmfDtls::join('gms_customer_franchisee', 'gms_dmf_dtls.dmf_fr_code', '=', 'gms_customer_franchisee.fran_cust_code')->select('gms_dmf_dtls.dmf_mfdate as dmf_date', 'gms_dmf_dtls.dmf_mfno as drs_no', 'gms_dmf_dtls.dmf_cnno_current_status as status', 'gms_dmf_dtls.modify_date as updated_by', 'gms_dmf_dtls.dmf_atmpt_date as attemp_date', 'gms_dmf_dtls.dmf_ndel_reason as reasone', 'gms_dmf_dtls.dmf_remarks as remark', 'gms_dmf_dtls.dmf_delv_remarks as info')->where('gms_dmf_dtls.dmf_cnno', $input['cnno_no'])->first();

        return $this->successResponse(self::CODE_OK, ["booking_details" => $booking_details,
            "gmsPmfDtls" => $gmsPmfDtls,
            "gmsDmfDtls" => $gmsDmfDtls
        ]);
    }
}
