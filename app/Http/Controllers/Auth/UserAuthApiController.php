<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserAuthApiController extends Controller
{
  public function register(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'name'     => 'required|max:255',
      'email'    => 'required|email|max:255|unique:users',
      'password' => 'required|min:6|confirmed'
    ]);

    if ($validator->fails())
    {
      $response = [
        'error' => TRUE,
        'message' => $validator->errors()->all()
      ];
      return response()->json($response, 400);
    }

    $user           = new User();
    $user->name     = $request->name;
    $user->email    = $request->email;
    $user->password = bcrypt($request->password);
    $user->save();

    if ($user)
    {
      $success['token'] = $user->createToken('MyApp', ['user'])->accessToken;
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

    if (auth()->guard('user')->attempt(['email' => request('email'), 'password' => request('password')]))
    {
      config(['auth.guards.api.provider' => 'user']);

      $user               = User::select('users.*')->find(auth()->guard('user')->user()->id);
      $success            = $user;
      $success['success'] = 'Login successful';
      $success['token']   = $user->createToken('MyApp', ['user'])->accessToken;

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
    return response()->json(['success' => TRUE, 'message' => 'logout successful'], 200);
  }

  public function profile(Request $request)
  {
    $user_id      = $request->user()->id;
    $user_details = DB::table('users')->where('id', $user_id)->get();

    $response     = [
      'message'      => 'success!',
      'user_details' => $user_details
    ];
    // $success = $user_details;
    return response()->json($response, 200);
  }
}
