<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class drugExportController extends Controller
{
    public function index(){
        $data = DB::table('drug_export')->get();
        if($data){
            return response()->json([
                "status" => 1,
                "message" => "drud export successfully",
                "data" => $data
            ]);
        }else{
            return response()->json([
                "status" => 0,
                "message" => "not found"
            ],404);

        }
    }
    public function store(Request $request){
        //validate
        $request->validate([
            "icode" => "required",
            "lot_no" => "required",
            "qty" => "required|numeric",
            "mode" => "required"

        ]);

        $drug_general = DB::table('drug_general')->where('icode',$request->icode);
        $drug_inv = DB::table('drug_inv')->where('drug_general_id',$drug_general->value('id'));
        if($drug_general->exists()){
            if($request->mode === "unit"){
                $qty = $request->qty;
            }elseif($request->mode === "pack"){
                $packing = $drug_general->value('packing');
                $qty = $request->qty * $packing;

            }
            if($drug_inv->exists() && $qty <= $drug_inv->value('qty') ){

                $drug_inv->update([
                    "qty" => $drug_inv->value('qty') - $qty,
                    "updated_at" => now()
                ]);
                DB::table('drug_export')->insert([
                "drug_general_id" => $drug_general->value('id'),
                "drug_name" => $drug_general->value('drug_name'),
                "lot_no" => $request->lot_no,
                "qty" => $qty,
                "price" => 0,
                "discount_by" => "systemd",
                "created_at" => now(),
                "updated_at" => now()
                ]);
                DB::table('history')->insert([
                    "drug_general_id" => $drug_general->value('id'),
                    "drug_name" => $drug_general->value('drug_name'),
                    "lot_no" => "000000",
                    "qty" => $qty,
                    "action" => "add-export",
                    "action_by" => 'systemd',
                    "created_at" => now(),
                    "updated_at" => now()
                ]);

                return response()->json([
                "status" => 1,
                "mode" => $request->mode,
                "drug_name" => $drug_general->value('drug_name'),
                "qty_out" => $qty,
                "in_stock" => $drug_inv->value('qty'),
                "message" => "create drug export successfully"
                ]);

            }elseif($drug_inv->exists()){
                return response()->json([
                    "status" => 0,
                    "in_stock" => $drug_inv->value('qty'),
                    "message" => "not enough drug"
                ],404);

            }else{
                return response()->json([
                    "status" => 0,
                    "message" => "not found drug in stock"
                ],404);
            }



        }else{
            return response()->json([
                "status" => 0,
                "message" => "not found drug and not import"
            ],404);
        }
    }
}
