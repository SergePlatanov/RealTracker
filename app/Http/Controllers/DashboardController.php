<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Inertia\Inertia;

use Illuminate\Support\Facades\Log;

use App\Models\Product;
use App\Models\Techno;
use App\Models\Status;
use App\Models\Event;
use App\Models\Number;

class DashboardController extends Controller
{
    public function index()
    {
        $products = Product::where('title','<>','none')->get();
        return Inertia::render('Dashboard', [
            'products' => $products,
        ]);
    }
}
