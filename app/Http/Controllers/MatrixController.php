<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Matrix;

class MatrixController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function computeProduct(Request $request)
    {
        /* Assumption : multiplier & multiplicand are array of arrays
        where the outmost array represents each horizontal content of the matrix
        hence, a 2x2 array would be [[1,2],[5,6]], written as:
        [
            [1, 2],
            [5, 6]
        ]
        1 is in position 0,0
        2 is in position 0,1
        5 is in position 1,0
        6 is in position 1,1
        */

        $this->validate($request, [
            'multiplicand' => 'required|array',
            'multiplier' => 'required|array',
            'multiplicand.*' => 'required|array|size:' . sizeof($request->multiplier),
            'multiplier.*' => 'required|array|size:' . sizeof($request->multiplier[0]),
            'multiplicand.*.*' => 'required|integer',
            'multiplier.*.*' => 'required|integer',
        ]);

        $output = [];
        $multiplicand = $request->multiplicand;
        $multiplier = $request->multiplier;
        $multiplier_size = sizeof($multiplier[0]);

        //do dot multiplication
        foreach ($multiplicand as $key => $value) { 
            $inner_output = [];            

            for ($i=0; $i < $multiplier_size; $i++) {
                $inner_product = 0;

                foreach ($value as $_key => $_value) {
                    //print_r("A: " . $_value . ", B: " . $multiplier[$_key][$i] . "\r\n");
                    $inner_product += $_value * $multiplier[$_key][$i];
                }

                //Do character convertion here

                array_push($inner_output, $inner_product);

            }

            array_push($output, $inner_output);
        }

        //convert result

        //save to DB

        //return response

        return response()->json(['status' => 'success', 'data' => $output], 301);
    }

    public function getMatrixRecord(Request $request, $id)
    {
        $matrix = Matrix::where('id', $id)->first();

        if (!$matrix) {
            return response()->json(['status' => 'error', 'message' => 'Invalid id'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $matrix], 200);
    }
}
