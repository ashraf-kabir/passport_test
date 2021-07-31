<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AdminAuthApiController extends Controller
{
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name'     => 'required|max:255',
      'email'    => 'required|email|max:255|unique:admins',
      'password' => 'required|min:6|confirmed'
    ]);

    if ($validator->fails())
    {
      return response()->json(['error' => $validator->errors()->all()]);
    }

    $user           = new Admin();
    $user->name     = $request->name;
    $user->email    = $request->email;
    $user->password = bcrypt($request->password);
    $user->save();

    if ($user)
    {
      $success['token'] = $user->createToken('MyApp', ['admin'])->accessToken;
      $success['user']  = $user;
      return response()->json($success, 200);
    }
  }

  public function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email'    => 'required|email',
      'password' => 'required'
    ]);

    if ($validator->fails())
    {
      return response()->json(['error' => $validator->errors()->all()]);
    }

    if (auth()->guard('admin')->attempt(['email' => request('email'), 'password' => request('password')]))
    {
      config(['auth.guards.api.provider' => 'admin']);

      $admin              = Admin::select('admins.*')->find(auth()->guard('admin')->user()->id);
      $success            = $admin;
      $success['success'] = 'Login successful';
      $success['token']   = $admin->createToken('MyApp', ['admin'])->accessToken;

      return response()->json($success, 200);
    }
    else
    {
      return response()->json(['error' => ['Email and Password are Wrong.']], 200);
    }
  }

  public function logout(Request $request)
  {
    $token = $request->user()->token();
    $token->revoke();
    return response()->json(['success' => 'logout success'], 200);
  }

  public function dashboard()
  {
    $users = User::all();

    if (count($users) > 0)
    {
      $response = [
        'message' => 'success',
        'users'   => $users
      ];
      return response()->json($response, 200);
    }
    else
    {
      $response = [
        'message' => 'error! no users found'
      ];
      return response()->json($response, 404);
    }
  }
}
