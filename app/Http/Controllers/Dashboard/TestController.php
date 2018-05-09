<?php

namespace App\Http\Controllers\Dashboard;

use App\Test;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller {

  public function __construct() {
    $this->middleware('auth');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    //
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Test  $test
   * @return \Illuminate\Http\Response
   */
  public function show(Test $test) {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Test  $test
   * @return \Illuminate\Http\Response
   */
  public function edit(Test $test) {
    dd($test);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Test  $test
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Test $test) {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Test  $test
   * @return \Illuminate\Http\Response
   */
  public function destroy(Test $test) {
    //
  }

}
