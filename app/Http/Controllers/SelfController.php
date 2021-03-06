<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use JWTAuth;

use App\Http\Requests\Pages\StoreTodoRequest;

class SelfController extends Controller
{
	/**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show()
  {
    return JWTAuth::parseToken()->authenticate();
  }

	public function indexTodos()
	{

	}

	public function storeTodo(StoreTodoRequest $request)
	{

	}

	public function destroyTodo()
	{

	}
}
