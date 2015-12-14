<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use Illuminate\Http\Request;

use App\Http\Requests;

class CurrencyController extends Controller
{
    public function index()
    {
        return view('currency.index');
    }
}
