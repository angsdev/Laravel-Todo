<?php

namespace App\Http\Controllers\User;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UserTaskController extends ApiController {

  /**
   * Validation rules.
   *
   * @var array
   */
  protected $rules = [
    'title' => 'sometimes|string|required',
    'description' => 'sometimes|string|nullable'
  ];

  /**
   * Setup the new controller instance.
   */
  public function __construct(){

    $this->authorizeResource(User::class);
    $this->authorizeResource(Task::class);
  }

  /**
   * Display a listing of the resource.
   *
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function index(User $user){

    return $this->successResponse($user->tasks);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\Task  $task
   * @return \Illuminate\Http\Response
   */
  public function show(User $user, Task $task){

    $user->isAssociatedWith($task);
    return $this->successResponse($task);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\User  $user
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request, User $user){

    $input = $request->validate($this->rules);
    $task = $user->tasks()->create($input);
    return $this->successResponse($task, 201);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Models\User  $user
   * @param  \App\Models\Task  $task
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, User $user, Task $task){

    $user->isAssociatedWith($task);
    $task->customUpdate($this->rules);
    return $this->successResponse($task);
  }

  public function destroy(User $user, Task $task){

    $user->isAssociatedWith($task);
    $task->delete();
    $task->resetAutoIncrement();
    return $this->successResponse($task);
  }
}
