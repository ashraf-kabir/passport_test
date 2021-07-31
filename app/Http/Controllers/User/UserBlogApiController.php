<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserBlogApiController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index(Request $request)
  {
    $user_id = $request->user()->id;

    $blogs = DB::table('blogs')->where('user_id', $user_id)->get();

    // var_dump($blogs);
    // die();

    if (count($blogs) > 0)
    {
      $response = [
        'message' => 'success!',
        'data'    => $blogs
      ];
      return response()->json($response, 200);
    }
    else
    {
      $response = [
        'message' => 'error! no data found',
        'user_id' => $user_id
      ];
      return response()->json($response, 404);
    }
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $data = $request->all();

    $validator = Validator::make($data, [
      'title'       => 'required|max:255',
      'description' => 'required',
      'status'      => 'required|numeric',
      'category_id' => 'required|numeric',
      'tag_id'      => 'required|numeric'
    ]);

    if ($validator->fails())
    {
      return response()->json(['error' => $validator->errors(), 'Validation Error']);
    }

    $user_id         = $request->user()->id;
    $data['user_id'] = $user_id;

    $blog = Blog::create($data);

    if ($blog)
    {
      $response = [
        'message' => 'success! blog added successfully',
        'data'    => $blog
      ];
      return response()->json($response, 200);
    }
    else
    {
      $response = [
        'message' => 'error! try again',
        'data'    => $blog
      ];
      return response()->json($response, 400);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request, $id)
  {
    $blog = Blog::find($id);

    $user_id = $request->user()->id;

    if ($blog)
    {
      $check = DB::table('blogs')->where('id', $id)->where('user_id', $user_id)->get();

      if (count($check) > 0)
      {
        $blog->delete();
        $response = [
          'message' => 'success! blog deleted successfully'
        ];
        return response()->json($response, 200);
      }
      else
      {
        $response = [
          'message' => 'error! this blog doesn\'t belong to you'
        ];
        return response()->json($response, 400);
      }
    }
    else
    {
      $response = [
        'message' => 'error! blog doesn\'t exist'
      ];
      return response()->json($response, 404);
    }
  }
}