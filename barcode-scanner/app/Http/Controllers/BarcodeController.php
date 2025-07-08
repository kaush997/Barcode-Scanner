<?php

namespace App\Http\Controllers;

use App\Models\Barcode;
use Illuminate\Http\Request;

class BarcodeController extends Controller
{
    public function index() {
        return view('barcode');
    }

    public function store(Request $request) {
        $request->validate([
            'code' => 'required'
        ]);

        Barcode::create(['code' => $request->code]);

        return redirect('/')->with('success', 'Barcode saved!');
    }
}
