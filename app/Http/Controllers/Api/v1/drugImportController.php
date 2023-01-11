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
    {   $account = "systemd";
        //validate
        $validate = $request->validate([
            "code1" => "required",
            "lot_no" => 'required',
            "qty" => 'required|numeric',
            "mode" => 'required',
            "exp_date" => 'required|date'

        ]);
        // create data
        if(DB::table('drug_general')->where('code1',$request->code1)->exists()){

            $drug_general = DB::table('drug_general')->where('code1',$request->code1);
            $drug_inv = DB::table('drug_inv')->where('drug_general_id',$drug_general->value('id'));
            $drug_inv_in_stock = $drug_inv->value('qty');

            if($request->mode == "pack"){
                $packing = $drug_general->value('packing');
                $qty = $request->qty * $packing;

            }elseif($request->mode === "unit"){
                $qty = $request->qty;
            }

            if($drug_inv->exists()){
                $update_drug_inv = $drug_inv->update(["qty" => $drug_inv_in_stock + $qty,"updated_at" => now() ]);
                $drug_import = DB::table('drug_import')->insert([
                "drug_general_id" => $drug_general->value('id'),
                "drug_name" => $drug_general->value('drug_name'),
                "lot_no" => $request->lot_no,
                "qty" => $qty,
                "price" => 0,
                "exp_date" => $request->exp_date,
                "add_by" => $account,
                "created_at" => now(),
                "updated_at" => now()
                ]);
                $history = DB::table('history')->insert([
                    "drug_general_id" => $drug_general->value('id'),
                    "drug_name" => $drug_general->value('drug_name'),
                    "lot_no" => $request->lot_no,
                    "qty" => $qty,
                    "action" => "add-import",
                    "action_by" => $account,
                    "created_at" => now(),
                    "updated_at" => now()
                ]);

                return response()->json([
                    "status" => 1,
                    "mode" => $request->mode,
                    "drug_name" => $drug_general->value('drug_name'),
                    "lot_no" => $request->lot_no,
                    "qty_new" => $qty,
                    "in_stock" => $drug_inv->value('qty'),
                    "message" => "drug import to invtory successfully"
                ],200);

            }else{
                return response()->json([
                    "status" => 0,
                    "message" => "not found drug in stock drug_inv"
                ],404);
            }




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
        $drug_import = DB::table('drug_import')->where('id',$id);
        if($drug_import->exists()){
            $drug_general = DB::table('drug_general')->where('id',$drug_import->value('drug_general_id'));
            $drug_inv = DB::table('drug_inv')->where('drug_general_id',$drug_general->value('id'));

            $drug_inv = DB::table('drug_inv')->where('drug_general_id',$drug_general_id)->update([
                "qty" => $drug_inv->qty - $drug_import->qty
            ]);

            DB::table('drug_import')->where('id',$id)->update([
                "drug_general_id" => $drug_general_id,
                "drug_name" => DB::table('drug_general')->where('code1',$request->code1)->value('drug_name'),
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
            $drug_inv = DB::table('drug_inv')->where('drug_general_id',$drug_import->drug_general_id);
            $drug_inv->update([
                "qty" => $drug_inv->value('qty') - $drug_import->qty
            ]);
            $history = DB::table('history')->insert([
                "drug_general_id" => $drug_import->drug_general_id,
                "drug_name" => $drug_import->drug_name,
                "lot_no" => $drug_import->lot_no,
                "qty" => $drug_import->qty,
                "action" => "del-import",
                "action_by" => "system",
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date("Y-m-d H:i:s")
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
