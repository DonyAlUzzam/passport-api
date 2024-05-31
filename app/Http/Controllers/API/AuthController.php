<?php
   
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ResponseJson;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Laravel\Passport\Token;

   
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/register",
     *      operationId="register",
     *      tags={"Auth"},
     *      summary="Register a new user",
     *      description="Returns user data and token",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email", "password"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", example="john@example.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password")
     *          ),
     *      ),
     *       @OA\Response(
    *          response=200,
    *          description="User register successfully",
    *          @OA\JsonContent(ref="#/components/schemas/ApiResponse")
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad request",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
    *      )
     * )
    */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
        ]);
   
        if ($validator->fails()) {
            return ResponseJson::error([
                'errors' => $validator->errors(),
            ], 400);
        }
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('Token')->accessToken;
        $success['user'] =  $user;
        return ResponseJson::success($success, 'User register successfully.');
    }
   
    /**
     * @OA\Post(
     *      path="/login",
     *      operationId="login",
     *      tags={"Auth"},
     *      summary="Login a user",
     *      description="Returns user data and token",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", example="testing@gmail.com"),
     *              @OA\Property(property="password", type="string", format="password", example="123456")
     *          ),
     *      ),
    *       @OA\Response(
    *          response=200,
    *          description="User Logged in successfully",
    *          @OA\JsonContent(ref="#/components/schemas/ApiResponse")
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad request",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
    *      ),
    *       @OA\Response(
    *          response=401,
    *          description="Unauthorized",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorUnAuth")
    *      )
     * )
    */

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ResponseJson::error([
                'errors' => $validator->errors(),
            ], 400);
        }

        Auth::shouldUse('web');
        if (!Auth::attempt($request->only('email', 'password'))) {
            return ResponseJson::unAuth("Unauthorized");
        }

        $user = Auth::user();
        $token = $user->createToken('authToken')->accessToken;
       
        return ResponseJson::success([
            'token' => $token,
            'token_type' => 'Bearer',
            // 'expires_at' => $token->expires_at,
            'user' => $user,
        ], 'User logged in successfully.');
    }

    /**
     * @OA\Post(
     *      path="/assign-role",
     *      operationId="asign-role",
     *      tags={"API Endpoints for Admin"},
     *      summary="Asign user Role",
     *      description="Returns user data",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", example="api@gmail.com"),
     *              @OA\Property(property="role", type="string", example="admin")
     *          ),
     *      ),
    *       @OA\Response(
    *          response=200,
    *          description="Role assigned successfully.",
    *          @OA\JsonContent(ref="#/components/schemas/ApiResponse")
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad request",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
    *      ),
    *       @OA\Response(
    *          response=401,
    *          description="Unauthorized",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorUnAuth")
    *      )
     * )
    */

    public function assignRole(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'role' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ResponseJson::error([
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return ResponseJson::notFound("User Not Found.");
        }

        $role = Role::where('name', $request->role)->first();

        if (!$role) {
            return ResponseJson::notFound("Role Not Found.");
        }

        $user->assignRole($request->role);

        return ResponseJson::success($user, 'Role assigned successfully.');

    }

    /**
     * @OA\Post(
     *      path="/give-permission",
     *      operationId="give-permission",
     *      tags={"API Endpoints for Admin"},
     *      summary="Permission granted user.",
     *      description="Returns user data",
     *      security={{"bearerAuth":{}}},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", example="api@gmail.com"),
     *              @OA\Property(property="permission", type="string", example="manage book")
     *          ),
     *      ),
    *       @OA\Response(
    *          response=200,
    *          description="Permission granted successfully.",
    *          @OA\JsonContent(ref="#/components/schemas/ApiResponse")
    *      ),
    *      @OA\Response(
    *          response=400,
    *          description="Bad request",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorResponse")
    *      ),
    *       @OA\Response(
    *          response=401,
    *          description="Unauthorized",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorUnAuth")
    *      )
     * )
    */

    public function givePermission(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'permission' => 'required|string',
        ]);

        if ($validator->fails()) {
            return ResponseJson::error([
                'errors' => $validator->errors(),
            ], 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return ResponseJson::notFound("User Not Found.");
        }

        $permission = Permission::where('name', $request->permission)->first();

        if (!$permission) {
            return ResponseJson::notFound("Permission Not Found.");
        }

        $user->givePermissionTo($request->permission);

        return ResponseJson::success($user, 'Permission granted successfully.');
    }

    /**
     * @OA\Post(
     *      path="/logout",
     *      operationId="logout",
     *      tags={"Auth"},
     *      summary="Logout a user",
     *      description="Revoke user token",
     *      security={{"bearerAuth":{}}},
     *       @OA\Response(
    *          response=200,
    *          description="Successfully logged out.",
    *          @OA\JsonContent(ref="#/components/schemas/ApiResponse")
    *      ),
      *       @OA\Response(
    *          response=401,
    *          description="Unauthorized",
    *          @OA\JsonContent(ref="#/components/schemas/ApiErrorUnAuth")
    *      )
     * )
     */
    public function logout(Request $request)
    {
        $user = Auth::user();
        $tokenId = $user->token()->id;

        Token::where('id', $tokenId)->update(['revoked' => true]);

        return ResponseJson::success('Successfully logged out.');

    }
}