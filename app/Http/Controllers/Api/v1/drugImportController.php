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
            "drug_code1" => "required",
            "lot_no" => 'required',
            "qty" => 'required',
            "mode" => 'required',
            "exp_date" => 'required'

        ]);

        if(DB::table('drug_general')->where('code1',$request->drug_code1)->exists()){

            $drug_id = DB::table('drug_general')->where('code1',$request->drug_code1)->value('id');

             if($request->mode == "pack"){
            $packing = DB::table('drug_general')->where('id',$drug_id)->value('packing');
            $qty = $request->qty * $packing;

            }elseif($request->mode === "unit"){
            $qty = $request->qty;
            }

            $drug_import = DB::table('drug_import')->insert([
                "drug_general_id" => $drug_id,
                "drug_name" => DB::table('drug_general')->where('code1',$request->drug_code1)->value('drug_name'),
                "lot_no" => $request->lot_no,
                "qty" => $qty,
                "price" => 0,
                "exp_date" => $request->exp_date,
                "add_by" => "system",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ]);
            $qty_now_in_drug_inv = DB::table('drug_inv')->where('drug_id',$drug_id)->value('qty');
            $update_drug_inv = DB::table('drug_inv')->where('drug_id',$drug_id)->update(["qty" => $qty_now_in_drug_inv + $qty ]);

            return response()->json([
            "status" => 1,
            "message" => "drug import to invtory successfully"
            ],200);
        }else{
            return response()->json([
                "status" => 0,
                "message" => "not found drug and not created"
            ]);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if(DB::table('drug_import')->where('id',$id)->exists()){
            $drug_import_detail = DB::table('drug_import')->find($id);
            return response()->json([
                "status" => 1,
                "message" => "get detail drug ".$drug_import_detail->drug_name." import successfully",
                "data" => $drug_import_detail
            ]);

        }else{
            return response()->json([
                "status" => 0,
                "message" => "not found"
            ]);
        }
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
        if(DB::table('drug_import')->where('id',$id)->exists()){
            $drug_import = DB::table('drug_import')->find($id);
            $drug_general_id = DB::table('drug_general')->where('code1',$drug_import->drug_general_id)->value('id');
            $drug_inv = DB::table('drug_inv')->where('drug_id',$drug_general_id)->update([
                "qty" => $drug_inv->qty - $drug_import->qty
            ]);

            DB::table('drug_import')->where('id',$id)->update([
                "drug_general_id" => $drug_general_id,
                "drug_name" => DB::table('drug_general')->where('code1',$request->drug_code1)->value('drug_name'),
                "lot_no" => $request->lot_no,
                "qty" => $qty,
                "price" => 0,
                "exp_date" => $request->exp_date,
                "add_by" => "system",
                "updated_at" => date('Y-m-d H:i:s')
            ]);

            return response()->json([
                "status" => 1,
                "message" => "update successfully"
            ]);
        }else{

            return response()->json([
                "status" => 0,
                "message" => "not found"
            ],404);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(DB::table('drug_import')->where('id',$id)->exists()){
            $drug_import = DB::table('drug_import')->find($id);
            $drug_inv = DB::table('drug_inv')->where('drug_id',$drug_import->drug_general_id);
            $drug_inv->update([
                "qty" => $drug_inv->value('qty') - $drug_import->qty
            ]);
            DB::table('drug_import')->where('id',$id)->delete();
            return response()->json([
                "status" => 1,
                "message" => "delete ".$id." successfully"
            ]);
        }else{
            return response()->json([
                "status" => 0,
                "message" => "not found"
            ],404);
        }
    }
}
