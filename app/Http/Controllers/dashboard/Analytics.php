<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class Analytics extends Controller
{
  public function index()
  {
    return response(view('content.dashboard.dashboards-analytics'))
      ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
  }
}
