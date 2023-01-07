<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class drugGeneralController extends Controller
{
    public function index()
    {
        $drug_general = DB::table('drug_general');
        $drug_id = $drug_general->latest()->value('id');
        $drug_name = $drug_general->latest()->value('drug_name');
        $drug_unit = $drug_general->latest()->value('unit');
        $drug_status = $drug_general->where('id', $drug_id)->value('status');
        dd($drug_status);
        ////////////////////////////////
        $data = DB::table('drug_general')->get();
        if ($data) {
            return response()->json([
                "status" => 1,
                "message" => "get drug general successfully",
                "data" => $data
            ], 200);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "not found drug",
            ], 200);
        }
    }
    public function show($id)
    {
        if (DB::table('drug_general')->where('id', $id)->exists()) {
            $data = DB::table('drug_general')->find($id)->first();
            return response()->json([
                "status" => 1,
                "message" => "show drug successfully",
                "data" => $data
            ], 200);
        } else {
            return response()->json([
                "status" => 0,
                "message" => "not found"
            ], 404);
        }
    }
    public function store(Request $request)
    {
        //validate
        $validate = $request->validate([
            "drug_name" => "required",
            "unit" => "required",
            "type" => "required",
            "group" => "required",
            "icode" => "required",
            "code1" => "required",
            "packing" => "required",
            "abc_analysis_type" => "required",
            "ved_analysis_type" => "required",
            "ed_list" => "required",
            "status" => "required"
        ]);
        // create data
        DB::table('drug_general')->insert([
            "drug_name" => $request->drug_name,
            "unit" => $request->unit,
            "type" => $request->type,
            "group" => $request->group,
            "icode" => $request->icode,
            "code1" => $request->code1,
            "packing" => $request->packing,
            "ABC_analysis_type" => $request->abc_analysis_type,
            "VED_analysis_type" => $request->ved_analysis_type,
            "ed_list" => $request->ed_list,
            "status" => $request->status,
            "created_at" => date('Y-m-d H:i:s'),
            "updated_at" => date('Y-m-d H:i:s')
        ]);
        $drug_general = DB::table('drug_general');
        $drug_id = $drug_general->latest()->value('id');
        $drug_name = $drug_general->latest()->value('drug_name');
        $drug_unit = $drug_general->latest()->value('unit');
        $drug_status = $drug_general->where('id', $drug_id)->value('status');
        if ($drug_status == 'Y') {
            DB::table('drug_inv')->insert([
                "drug_id" => $drug_id,
                "drug_name" => $drug_name,
                "unit" =>  $drug_unit,
                "qty" => 0,
                "created_at" => date('Y-m-d H:i:s'),
                "updated_at" => date('Y-m-d H:i:s')
            ]);
            return response()->json([
                "status" => 1,
                "message" => "create ".$request->drug_name." and add enable drug to drug_inv successfully"
            ]);
        }



        //response
        return response()->json([
            "status" => 1,
            "message" => "create ".$request->drug_name." successfully and disable"
        ]);
    }
}
