<?php

namespace App\Traits;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

/**
 * Trait to handle api responses
 */
trait ApiHelpers {

  /**
   * Response handler method.
   *
   * @param  Mixed ...$props
   * - 'success' key should contain boolean specifiying if request is success or don't.
   * - 'content' key should contain String|Array|Collection with data returned to client.
   * - 'code' key should contain the code returned as a Integer.
   * @return \Illuminate\Http\Response
   */
  private function response(...$props){

    if(isset($props['content']) && ($props['content'] instanceof Collection)){

      $props['content'] = $this->filterData($props['content']);
      $props['content'] = $this->sortData($props['content']);
      $props['content'] = $this->paginate($props['content']);
      $props['content'] = $this->cacheResponse($props['content']);
    }
    $code = (isset($props['code'])) ? (int) Arr::pull($props, 'code') : 200;
    return response()->json($props, $code);
  }

  /**
   * Success response method.
   *
   * @param  \Illuminate\Support\Collection[]|Array|String[] $content
   * @param  Int $code
   * @return \Illuminate\Http\Response
   */
  protected function successResponse(Model|Collection|Array|String $content = [], Int $code = 200){

    return $this->response(success: true, content: $content, code: $code);
  }

  /**
   * Failure response method.
   *
   * @param  String $message
   * @param  Int $code
   * @return \Illuminate\Http\Response
   */
  protected function failureResponse(Array|String $message, Int $code = 200){

    return $this->response(success: false, message: $message, code: $code);
  }

  /**
   * Filtering of data by request query params.
   *
   * @param  \Illuminate\Support\Collection $collection
   * @return \Illuminate\Support\Collection
   */
  private function filterData(Collection $collection){

    foreach ((request()->query()) as $attr => $val) {

      if($attr === 'page' || $attr === 'per_page' ||  $attr === 'sort_by') continue;
      $collection = $collection->filter(fn($collectionVal) => (stripos($collectionVal->$attr, $val) !== false));
    }
    return $collection;
  }

  /**
   * Sorting of data by request 'sort_by' query param.
   *
   * @param  \Illuminate\Support\Collection $collection
   * @return \Illuminate\Support\Collection
   */
  private function sortData(Collection $collection){

    return (request()->has('sort_by')) ? $collection->sortBy(request()->sort_by) : $collection;
  }

  /**
   * Pagination of data by request 'page' and 'per_page' query params.
   *
   * @param  \Illuminate\Support\Collection $collection
   * @return \Illuminate\Pagination\Paginator
   */
  private function paginate(Collection $collection){

    request()->validate([ 'per_page' => 'integer|min:2|max:50', 'page' => 'integer|min:1|max:50' ]);
    $currentPage = (int) request()->input('page', Paginator::resolveCurrentPage());
    $perPage = (int) request()->input('per_page', 15);
    $results = $collection->forPage($currentPage, $perPage)->values();
    $pagination = new Paginator($results, $perPage, $currentPage, [ 'path' => Paginator::resolveCurrentPath() ]);
    return $pagination;
  }

  /**
   * Data cache for faster response after first time.
   *
   * @param  \Paginator|Collection|Array $data
   * @return \Illuminate\Pagination\Paginator|Illuminate\Support\Collection|Array
   */
  private function cacheResponse(Paginator|Collection|Array $data){

    $url = request()->url();
    $queryParams = request()->query();
    ksort($queryParams);
    $queryString = http_build_query($queryParams);
    $fullUrl = "{$url}?{$queryString}";
    $cache = Cache::remember($fullUrl, (30/60), fn() => $data);
    return $cache;
  }
}
