<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class drugImportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = DB::table('drug_import')->get();
        if($data){
            return response()->json([
                "status" => 1,
                "message" => "list drug import successfully",
                "data" => $data
            ]);
        }else{
            return response()->json([
                "status" => 0,
                "message" => "not found data"
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validate
        $validate = $request->validate([
            "drug_code" => "required",
            "lot_no" => 'required',
            "qty" => 'required',
            "mode" => 'required',
            "exp_date" => 'required'

        ]);
        $drug_id = DB::table('drug_general')->where('code1',$request->drug_code)->value('id');

        if($request->mode == "pack"){
            $packing = DB::table('drug_general')->where('id',$drug_id)->value('packing');
            $qty = $request->qty * $packing;

        }elseif($request->mode === "unit"){
            $qty = $request->qty;
        }

        $drug_import = DB::table('drug_import')->insert([
            "drug_general_id" => $drug_id,
            "drug_name" => DB::table('drug_general')->where('code1',$request->drug_code)->value('drug_name'),
            "lot_no" => $request->lot_no,
            "qty" => $qty,
            "price" => 0,
            "exp_date" => $request->exp_date,
            "add_by" => "system",
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s')
        ]);

        return response()->json([
            "status" => 1,
            "message" => "drug import to invtory successfully"
        ],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
