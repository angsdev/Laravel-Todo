<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskPolicy {

  use HandlesAuthorization;

  /**
   * Determine whether the user can view any models.
   *
   * @param  \App\Models\User  $user
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function viewAny(User $user){

    return true;
  }

  /**
   * Determine whether the user can view the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\Task  $model
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function view(User $user, Task $model){

    if($user->id === $model->user->id) return true;
  }

  /**
   * Determine whether the user can create models.
   *
   * @param  \App\Models\User  $user
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function create(User $user){

    return true;
  }

  /**
   * Determine whether the user can update the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\Task  $model
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function update(User $user, Task $model){

    if($user->id === $model->user->id) return true;
  }

  /**
   * Determine whether the user can delete the model.
   *
   * @param  \App\Models\User  $user
   * @param  \App\Models\Task  $model
   * @return \Illuminate\Auth\Access\Response|bool
   */
  public function delete(User $user, Task $model){

    if($user->id === $model->user->id) return true;
  }
}
