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
     * Register api
     *
     * @return \Illuminate\Http\Response
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
     * Login api
     *
     * @return \Illuminate\Http\Response
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
            return ResponseJson::unAuth([
                'message' => 'Unauthorized',
            ], 401);
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
            return ResponseJson::notFound([
                'errors' => "User Not Found.",
            ], 404);
        }

        $role = Role::where('name', $request->role)->first();

        if (!$role) {
            return ResponseJson::notFound([
                'errors' => "Role Not Found.",
            ], 404);
        }

        $user->assignRole($request->role);

        return ResponseJson::success('Role assigned successfully.');

    }

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
            return ResponseJson::notFound([
                'errors' => "User Not Found.",
            ], 404);
        }

        $permission = Permission::where('name', $request->permission)->first();

        if (!$permission) {
            return ResponseJson::notFound([
                'errors' => "Permission Not Found.",
            ], 404);
        }

        $user->givePermissionTo($request->permission);

        return ResponseJson::success('Permission granted successfully.');
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $tokenId = $user->token()->id;

        Token::where('id', $tokenId)->update(['revoked' => true]);

        return ResponseJson::success('Successfully logged out.');

    }
}