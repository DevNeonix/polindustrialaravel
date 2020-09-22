<?php

namespace App\Http\Controllers;

use App\VMarcacionDia;
use Carbon\Carbon;
use Illuminate\Http\Request;

class VMarcacionDiaController extends Controller
{
    public function index(Request $request)
    {

        $f1 = $request->input('fechaini');
        $f2 = $request->input('fechafin');

        if (!empty($f1) && !empty($f2)) {
            $f1 = Carbon::make($f1);
            $f2 = Carbon::make($f2);
            $a1 = $f1->year;
            $m1 = intval($f1->month);
            $d1 = intval($f1->day);
            $a2 = $f2->year;
            $m2 = intval($f2->month);
            $d2 = intval($f2->day);
            $data = VMarcacionDia::where("ano", ">=", $a1)
                ->where("mes", ">=", $m1)
                ->where("dia", ">=", $d1)
                ->where("ano", "<=", $a2)
                ->where("mes", "<=", $m2)
                ->where("dia", "<=", $d2)->get();
            //SELECT * FROM `view_marcacion_dia` where (ano >= 2020 and mes >= 08 and dia >= 01) and (ano <= 2020 and mes <= 08 and dia <= 10)
            return view('pages.tareo.index')->with(compact('data'));
        }else{
            $data = VMarcacionDia::all();
        }

        return view('pages.tareo.index')->with(compact('data'));
    }

}
