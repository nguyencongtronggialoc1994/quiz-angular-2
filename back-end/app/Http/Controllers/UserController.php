<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserController extends Controller
{
    public function show($id)
    {

        $user = DB::table('users')
            ->join('role_user','users.id','=','role_user.user_id')
            ->join('roles','role_user.role_id','=','roles.id')
            ->where('users.id','=',$id)
            ->select('users.*','roles.name as roleName')
            ->first();
        return response()->json($user, 200);
    }

    public function index(): \Illuminate\Http\JsonResponse
    {
        $users = DB::table('users')
            ->join('role_user','users.id','=','role_user.user_id')
            ->join('roles','role_user.role_id','=','roles.id')
            ->select('users.*','roles.name as roleName')
            ->get();
        return response()->json($users, 200);
    }

    public function getRole($email)
    {
        $role = DB::table('users')
            ->join('role_user', 'users.id', '=', 'role_user.user_id')
            ->join('roles', 'role_user.role_id', '=', 'roles.id')
            ->select('users.name','roles.name as roleName')->where('users.email', '=', $email)
            ->first();
        return response()->json($role, 200);
    }

    public function getIdByEmail($email)
    {
        $id = DB::table('users')->where('email', '=', $email)->value('id');
        return response()->json($id, 200);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $statusCode = 404;
          if (Hash::check($request->oldPassword,$user->password)){
              if ($request->newPassword==$request->confirmPassword){
                  $user->password = Hash::make($request->newPassword);
                  $user->save();
                  $statusCode=200;
              }

          }
        return response()->json($user, $statusCode);
    }

    public
    function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }

        return response()->json(compact('token'));
    }

    public
    function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        $statusCode = 201;
        if ($validator->fails()) {
            $statusCode = 400;
            return response()->json($validator->errors()->toJson(), $statusCode);
        }

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);
        $user->roles()->attach($request->role);
        $token = JWTAuth::fromUser($user);

        return response()->json(compact('user', 'token', 'statusCode'), $statusCode);
    }

    /*
     * get current user
     */
    public
    function getAuthenticatedUser()
    {
        try {

            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());
        }

        return response()->json(compact('user'));
    }
}
