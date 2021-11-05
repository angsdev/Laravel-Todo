<?php

namespace App\Models;

use App\Models\Task;
use App\Traits\ModelHelpers;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @OA\Schema(
 * required={"username", "email", "password"},
 * @OA\Xml(name="user"),
 * @OA\Property(property="id", type="integer", example="1"),
 * @OA\Property(property="firstName", type="string", maxLength=32, example="John"),
 * @OA\Property(property="lastName", type="string", maxLength=32, example="Doe"),
 * @OA\Property(property="username", type="string", maxLength=30, example="username1234"),
 * @OA\Property(property="email", type="string", format="email", description="User unique email address", example="user@gmail.com"),
 * @OA\Property(property="password", type="string", maxLength=60, description="User password", example="password123"),
 * @OA\Property(property="api_token", type="string", maxLength=100, description="User JWT", example="$asd!#(/Aasdasdaf76)("),
 * @OA\Property(property="email_verified_at", type="string", format="date-time", description="Datetime marker of verification status", example="2019-02-25 12:59:20"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp", example="2019-02-25 12:59:20"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", example="2019-02-25 12:59:20"),
 * )
 */

class User extends Authenticatable {

  use ModelHelpers, HasFactory, Notifiable;

  /**
   * The table associated with the model.
   *
   * @var string
   */
  protected $table = 'Users';

  /**
   * The model's default values for attributes.
   *
   * @var array
   */
  protected $attributes = [
    'api_token' => null,
    'remember_token' => null,
    'email_verified_at' => null
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'firstname',
    'lastname',
    'username',
    'phone',
    'email',
    'password'
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password',
    'remember_token',
    'email_verified_at',
    'pivot',
    'api_token',
    'created_at',
    'updated_at'
  ];

  /**
   * Retrieve the model for a bound value.
   *
   * @param  mixed  $value
   * @param  string|null  $field
   * @return \Illuminate\Database\Eloquent\Model|null
   */
  public function resolveRouteBinding($value, $field = null){

    return $this->findMatches([ 'id' => $value, 'fields' => 'id|email|phone|username' ]);
  }

  /**
   * Get tasks associated with the user.
   *
   * @return hasMany
   */
  public function tasks(){

    return $this->hasMany(Task::class);
  }
}
