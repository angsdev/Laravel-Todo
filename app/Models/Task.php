<?php

namespace App\Models;

use App\Traits\ModelHelpers;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * @OA\Schema(
 * required={"title", "user_id"},
 * @OA\Xml(name="user"),
 * @OA\Property(property="id", type="integer", example="1"),
 * @OA\Property(property="user_id", type="integer", description="User id relationated with this task"),
 * @OA\Property(property="title", type="string", maxLength=32, example="Read some book"),
 * @OA\Property(property="description", type="string", example="Task to remember read some book when exist the chance."),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp"),
 * )
 */
//  // * OA\Property(property="user_id", type="integer", description="User id relationated with this task", example="user@mail.com", ref="#/components/schemas/User"),


class Task extends Model {

  use ModelHelpers, HasFactory;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'Tasks';

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'user_id',
    'title',
    'description',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'pivot'
  ];

  /**
   * Get user associated with the task.
   *
   * @return belongsTo
   */
  public function user(){

    return $this->belongsTo(Task::class);
  }
}
