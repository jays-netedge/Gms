<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Manager\CommonMgr;
use App\Models\UserSession;
use App\Models\User;


/**
 * @OA\Info(
 *     description="This is Api documentation for Gms - Gms Management.",
 *     version="1.0.0",
 *     title="Gms - Gms Management",
 *     termsOfService="http://swagger.io/terms/",
 * )
 * @OA\Server(
 *      url="http://gms.netedgetechnology.net/",
 *  )
 *
 *
 */
class UserController extends Controller
{
    protected $commonMgr;
    protected $request;

    public function __construct(Request $request, CommonMgr $commonMgr)
    {
        $this->request = $request;
        $this->commonMgr = $commonMgr;
    }

    public function generateDefaultUser()
    {
        if (Admin::where('email', "user@user.com")->count() == 0) {
            try {
                $user = new Admin([
                    'name' => 'User',
                    'username' => 'user',
                    'email' => 'user@user.com',
                    'password' => Hash::make('user123'),
                ]);
                $user->save();
                return $this->successResponse(self::CODE_OK, self::OPERATION_SUCCESS, $user);
            } catch (\Exception $e) {
                return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, self::INTERNAL_SERVER_ERROR, $e);
            }
        } else {
            return $this->successResponse(self::CODE_OK, "Admin Already Created!!");
        }
    }

    /**
     * @OA\Post(
     * path="/createUser",
     * summary="Create User",
     * operationId="createUser",
     *  tags={"User"},
     * @OA\Parameter(
     *   name="username",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="password",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="email",
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
    public function createUser()
    {
        $validator = Validator::make($this->request->all(), [
            'username' => 'required',
            'password' => 'required|min:8',
            'email' => 'required|email:rfc,dns|unique:users|max:255'
        ], [
            'email.unique' => 'Email ID is already registered with us!',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();

        $header = $this->commonMgr->headerDetails();
        $input['ip_address'] = $header['IP_ADDRESS'];

        DB::beginTransaction();
        try {
            $input['password'] = Hash::make($input['password']);
            // User Data

            $user = new User($input);
            $user->save();

            $input['user_id'] = $user->id;
            $input['session_token'] = $user->createToken('Gsm')->accessToken;

            $userSession = new UserSession($input);
            $userSession->save();

            $input['user_id'] = $user->id;
            DB::commit();
            return $this->successResponse(self::CODE_OK, "User Created Successfully!!", ['session_token' => true]);
        } catch (\Exception $e) {
            return $this->successResponse(self::CODE_INTERNAL_SERVER_ERROR, "Something Error!!", $e);
        }
    }

    /**
     * @OA\Post(
     * path="/login",
     * summary="Admin Login",
     * operationId="login",
     *  tags={"User"},
     * @OA\Parameter(
     *   name="username",
     *   in="query",
     *   required=true,
     *  @OA\Schema(
     *   type="string"
     *  )
     * ),
     * @OA\Parameter(
     *   name="password",
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
    public function login()
    {
        $validator = Validator::make($this->request->all(), [
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, self::INVALID_REQUEST, $validator->errors());
        }
        $input = $this->request->all();
        $header = $this->commonMgr->headerDetails();
        $user = User::where('username', $input['username'])->first();
        if ($user != null) {
            if (Hash::check($input['password'], $user->password)) {
                DB::beginTransaction();
                try {
                    $token = $user->createToken('Gsm')->accessToken;
                    $userSession = new UserSession([
                        'user_id' => $user->id,
                        'user_agent' => $header['USER-AGENT'],
                        'ip_address' => $header['IP_ADDRESS'],
                        'session_token' => $token,
                    ]);
                    $userSession->save();
                    DB::commit();
                    return $this->successResponse(self::CODE_OK, "Login Successfully!!", ['session_token' => $userSession->session_token,
                        'user_name' => $user->username,
                        'id' => $user->id,
                        'email' => $user->email]);
                } catch (\Exception $e) {
                    DB::rollback();
                    return $this->errorResponse(self::CODE_INTERNAL_SERVER_ERROR, "Something Error!!", $e);
                }
            } else {
                return $this->errorResponse(self::CODE_UNAUTHORIZED, "Password Is Incorrect");
            }
        } else {
            return $this->errorResponse(self::CODE_UNAUTHORIZED, "User Is Not Register");
        }
    }


    public function validateSession()
    {
        $header = $this->commonMgr->headerDetails();
        if (isset($header['SESSION-TOKEN']) && !empty($header['SESSION-TOKEN'])) {
            $accessToken = $header['SESSION-TOKEN'];
            $token = UserSession::where('session_token', $accessToken)->first();
            if ($token != null) {
                if ($token->is_active == 1) {
                    return $this->successResponse(self::CODE_OK, "Session is Active.");
                } else {
                    return $this->errorResponse(self::CODE_UNAUTHORIZED, 'Session is Expired.');
                }
            } else {
                return $this->errorResponse(self::CODE_INVALID_REQUEST, 'Invalid Access Token.');
            }
        } else {
            return $this->errorResponse(self::CODE_INVALID_REQUEST, 'Access Token is not found.');
        }
    }
}
