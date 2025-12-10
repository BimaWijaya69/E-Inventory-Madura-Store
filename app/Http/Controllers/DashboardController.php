<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $data = Auth::user();
        $breadcrumb = (object) [
            'list' => ['Dashboard', '']
        ];
        return view('pages.dashboard.index', ['data' => $data, 'breadcrumb' => $breadcrumb]);
    }
}
