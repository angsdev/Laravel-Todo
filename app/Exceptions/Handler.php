<?php

namespace App\Exceptions;

use Exception;
use Throwable;
use ErrorException;
use App\Traits\ApiHelpers;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\MethodNotAllowedException;

class Handler extends ExceptionHandler {

  use ApiHelpers;

  /**
   * A list of the exception types that are not reported.
   *
   * @var array
   */
  protected $dontReport = [
    //
  ];

  /**
   * A list of the inputs that are never flashed for validation exceptions.
   *
   * @var array
   */
  protected $dontFlash = [
    'current_password',
    'password',
    'password_confirmation'
  ];

  /**
   * Register the exception handling callbacks for the application.
   *
   * @return void
   */
  public function register(){

    $this->reportable(function (Throwable $e) {
      //
    });

    $this->renderable(fn(AuthenticationException $e) => $this->failureResponse($e->getMessage(), 401));
    $this->renderable(fn(AuthorizationException $e) => $this->failureResponse($e->getMessage(), 403));
    $this->renderable(fn(MethodNotAllowedException $e) => $this->failureResponse($e->getMessage(), 405));
    $this->renderable(fn(ModelNotFoundException $e) => $this->failureResponse($e->getMessage(), 404));
    $this->renderable(fn(NotFoundHttpException $e) => $this->failureResponse($e->getMessage(), 404));
    $this->renderable(fn(QueryException $e) => $this->failureResponse($e->errorInfo[2], 409));
    $this->renderable(fn(TokenMismatchException $e) => redirect()->back()->withINput(request()->input()));
    $this->renderable(fn(ValidationException $e) => $this->failureResponse($e->validator->errors()->getMessages(), $e->status));
    $this->renderable(fn(RouteNotFoundException $e) => $this->failureResponse($e->getMessage()));
    $this->renderable(fn(HttpException $e) => $this->failureResponse($e->getMessage(), $e->getStatusCode()));
    $this->renderable(fn(ErrorException $e) => $this->failureResponse($e->getMessage(), 401));
    $this->renderable(fn(Exception $e) => $this->failureResponse($e->getMessage(), 401));
  }
}
