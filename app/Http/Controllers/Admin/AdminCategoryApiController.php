<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AdminCategoryApiController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $categories = DB::table('categories')->get();

    // var_dump($categories);
    // die();

    if (count($categories) > 0)
    {
      $response = [
        'message' => 'success!',
        'data'    => $categories
      ];
      return response()->json($response, 200);
    }
    else
    {
      $response = [
        'message' => 'No data found!'
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
      'name'   => 'required|max:255',
      'status' => 'required|numeric'
    ]);

    if ($validator->fails())
    {
      return response()->json(['error' => $validator->errors(), 'Validation Error']);
    }

    $blog = Category::create($data);

    if ($blog)
    {
      $response = [
        'message' => 'success! category added successfully',
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
  public function destroy($id)
  {
    $category = Category::find($id);

    if ($category)
    {
      $blogs = DB::table('blogs')->where('category_id', $id)->get();

      if (count($blogs) > 0)
      {
        $response = [
          'message' => 'error! this category is being used on blogs'
        ];
        return response()->json($response, 400);
      }
      else
      {
        $category->delete();
        $response = [
          'message' => 'success! category deleted successfully'
        ];
        return response()->json($response, 200);
      }
    }
    else
    {
      $response = [
        'message' => 'error! category doesn\'t exist'
      ];
      return response()->json($response, 404);
    }
  }
}
