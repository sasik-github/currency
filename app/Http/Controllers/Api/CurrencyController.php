<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;

/**
 * User: sasik
 * Date: 12/14/15
 * Time: 11:36 AM
 */
class CurrencyController extends Controller
{

    public function index()
    {
        return Currency::all();
    }
}