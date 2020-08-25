<?php

namespace App\Http\Controllers;

use App\VMarcacionDia;
use Illuminate\Http\Request;

class VMarcacionDiaController extends Controller
{
    public function index()
    {
        $data = VMarcacionDia::all();
        return view('pages.tareo.index')->with(compact('data'));
    }
}
