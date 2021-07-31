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
        'success' => TRUE,
        'data'    => $blogs
      ];
      return response()->json($response, 200);
    }
    else
    {
      $response = [
        'error'   => TRUE,
        'message' => 'no data found',
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
      $response = [
        'error' => TRUE,
        'message' => $validator->errors()->all()
      ];
      return response()->json($response, 400);
    }

    $user_id         = $request->user()->id;
    $data['user_id'] = $user_id;

    $blog = Blog::create($data);

    if ($blog)
    {
      $response = [
        'success' => TRUE,
        'message' => 'blog added successfully',
        'data'    => $blog
      ];
      return response()->json($response, 200);
    }
    else
    {
      $response = [
        'error'   => TRUE,
        'message' => 'try again'
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
          'success' => TRUE,
          'message' => 'blog deleted successfully'
        ];
        return response()->json($response, 200);
      }
      else
      {
        $response = [
          'error'   => TRUE,
          'message' => 'this blog doesn\'t belong to you'
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

  public function search(Request $request)
  {
    $params = $request->all();

    $validator = Validator::make($params, [
      'search_term' => 'required|min:3'
    ]);

    if ($validator->fails())
    {
      return response()->json(['error' => $validator->errors()], 400);
    }

    // dd($params);
    // $user_id = $request->user()->id;

    $blogs = DB::table('blogs')
      ->join('categories', 'categories.id', '=', 'blogs.category_id')
      ->join('tags', 'tags.id', '=', 'blogs.tag_id')
      ->join('users', 'users.id', '=', 'blogs.user_id')
      ->where('blogs.title', 'LIKE', '%' . $params['search_term'] . '%')
      ->orWhere('categories.name', 'LIKE', '%' . $params['search_term'] . '%')
      ->orWhere('tags.name', 'LIKE', '%' . $params['search_term'] . '%')
      ->where('blogs.status', '1')
      ->select('blogs.*', 'categories.name as category_name', 'tags.name as tag_name', 'users.name as user_name')
      ->get();

    if (count($blogs) > 0)
    {
      $response = [
        'success' => TRUE,
        'message' => 'success!',
        'data'    => $blogs
      ];
      return response()->json($response, 200);
    }
    else
    {
      $response = [
        'error'   => TRUE,
        'message' => 'error! no data found'
      ];
      return response()->json($response, 404);
    }
  }
}
