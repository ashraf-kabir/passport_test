<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class MainController extends Controller
{

  public function userDashboard()
  {
    $users   = User::all();
    $success = $users;

    return response()->json($success, 200);
  }

  public function adminDashboard()
  {
    $users   = Admin::all();
    $success = $users;

    return response()->json($success, 200);
  }

  public function userRegister(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name'     => 'required|max:255',
      'email'    => 'required|email|max:255|unique:users',
      'password' => 'required|min:6|confirmed'
    ]);

    if ($validator->fails())
    {
      return response()->json(['error' => $validator->errors()->all()]);
    }

    $user           = new User();
    $user->name     = $request->name;
    $user->email    = $request->email;
    $user->password = bcrypt($request->password);
    $user->save();

    // $user = User::create($validatedData);

    // $accessToken = $user->createToken('authToken')->accessToken;

    if ($user)
    {
      $success['token'] = $user->createToken('MyApp', ['user'])->accessToken;
      $success['user']  = $user;
      return response()->json($success, 200);
    }
  }

  public function userLogin(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email'    => 'required|email',
      'password' => 'required'
    ]);

    if ($validator->fails())
    {
      return response()->json(['error' => $validator->errors()->all()]);
    }

    if (auth()->guard('user')->attempt(['email' => request('email'), 'password' => request('password')]))
    {
      config(['auth.guards.api.provider' => 'user']);

      $user             = User::select('users.*')->find(auth()->guard('user')->user()->id);
      $success          = $user;
      $success['token'] = $user->createToken('MyApp', ['user'])->accessToken;

      return response()->json($success, 200);
    }
    else
    {
      return response()->json(['error' => ['Email and Password are Wrong.']], 200);
    }
  }

  public function adminRegister(Request $request)
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

  public function adminLogin(Request $request)
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

      $admin            = Admin::select('admins.*')->find(auth()->guard('admin')->user()->id);
      $success          = $admin;
      $success['token'] = $admin->createToken('MyApp', ['admin'])->accessToken;

      return response()->json($success, 200);
    }
    else
    {
      return response()->json(['error' => ['Email and Password are Wrong.']], 200);
    }
  }

  public function userLogout(Request $request)
  {
    $token = $request->user()->token();
    $token->revoke();
    return response()->json(['success' => 'logout success'], 200);
  }

  public function adminLogout(Request $request)
  {
    $token = $request->user()->token();
    $token->revoke();
    return response()->json(['success' => 'logout success'], 200);
  }

}
