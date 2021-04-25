<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\UtilityHelper;
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

        $int_output = [];
        $char_output = [];
        $multiplicand = $request->multiplicand;
        $multiplier = $request->multiplier;
        $multiplier_size = sizeof($multiplier[0]);

        //do dot multiplication
        foreach ($multiplicand as $key => $value) { 
            $inner_int_output = [];
            $inner_char_output = [];

            for ($i=0; $i < $multiplier_size; $i++) {
                $inner_product = 0;

                foreach ($value as $_key => $_value) {
                    //print_r("A: " . $_value . ", B: " . $multiplier[$_key][$i] . "\r\n");
                    $inner_product += $_value * $multiplier[$_key][$i];
                }

                //Do character convertion here
                array_push($inner_int_output, $inner_product);
                array_push($inner_char_output, UtilityHelper::convertToCharacters($inner_product));

            }

            array_push($int_output, $inner_int_output);
            array_push($char_output, $inner_char_output);
        }

        //save to DB
        $matrix = new Matrix();
        $matrix->multiplicand = json_encode($multiplicand);
        $matrix->multiplier = json_encode($multiplier);
        $matrix->product = json_encode($int_output);
        $matrix->transformed_product = json_encode($char_output);
        $matrix->created_by = $request->auth_user->id;
        $matrix->save();

        //return response
        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $matrix->id,
                'matrix_product' => $int_output,
                'transformed_product' => $char_output]
            ], 200);
    }

    public function getMatrixRecord(Request $request, int $id)
    {
        $matrix = Matrix::where('id', $id)->first();

        if (!$matrix) {
            return response()->json(['status' => 'error', 'message' => 'Invalid id'], 404);
        } elseif ($request->auth_user->id != $matrix->created_by) {
            return response()->json(['status' => 'error', 'message' => 'Requested resource does not belong to you'], 403);
        }

        return response()->json([
            'status' => 'success', 
            'data' => [
                    'id' => $matrix->id,
                    'matrix_product' => json_decode($matrix->product, true),
                    'transformed_product' => json_decode($matrix->transformed_product),
                    'creator' => [
                        'account_name' => $matrix->user->account_name
                    ],
                    'created_at' => date('Y-m-d H:i:s', strtotime($matrix->created_at)),
                    'updated_at' => date('Y-m-d H:i:s', strtotime($matrix->updated_at))
                ]
            ], 
            200);
    }
}
