<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class drugInvController extends Controller
{
    public function index(){
        $drug_inv = DB::table('drug_inv')->get();
        if(!empty($drug_inv)){
        return response()->json([
            "status" => 1,
            "message" => "show all drug in inventory successfully",
            "data" => $drug_inv
        ]);
        }else{
        return response()->json([
            "status" => 1,
            "message" => "not found drug in inventory",
        ],404);
        }
    }

    public function show($id){
        $drug_inv = DB::table('drug_inv')->where('id',$id);
        if($drug_inv->exists()){
            return response()->json([
                "status" => 1,
                "message" => "show detail drug".$drug_inv->value('drug_name')." sucessfully",
                "detail" => $drug_inv->get()
            ]);
        }else{
            return response()->json([
                "status" => 0,
                "message" => "not found drug in inventory"
            ],404);
        }
    }
}
