<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Validation\Rule;

class FamilyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(){
        try {
            $data = DB::table('tbl_families')->get();

            return response()->json(array(
                'OUT_STAT' => 'T',
                'OUT_MESS' => 'Success',
                'OUT_DATA' => $data
            ), 200);

        } catch (\Throwable $th) {
            return response()->json(array(
                'OUT_STAT' => 'F',
                'OUT_MESS' => 'Failed',
                'OUT_DATA' => []
            ), 500);
        }
        
    }

    public function create(Request $req){
        try {
            $req->validate([
                'name' => 'required',
                'gender' => Rule::in(["Laki-laki", "Perempuan"]),
            ]);

            $id = DB::table('tbl_families')->insertGetId(
                [
                    'name' => $req->name, 
                    'gender' => $req->gender,
                    'parent' => $req->parent
                ]);
            $data = [
                ['id' => $id]
            ];

            return response()->json(array(
                'OUT_STAT' => 'T',
                'OUT_MESS' => 'Success',
                'OUT_DATA' => $data
            ), 200);

        } catch (\Throwable $th) {
            return response()->json(array(
                'OUT_STAT' => 'F',
                'OUT_MESS' => 'Failed',
                'OUT_DATA' => []
            ), 500);
        }
    }

    public function update(Request $req){
        try {
            $req->validate([
                'id' => 'required',
                'name' => 'required',
                'gender' => Rule::in(["Laki-laki", "Perempuan"]),
            ]);

            $affected = DB::table('tbl_families')
                ->where('id', $req->id)
                ->update([
                    'name' => $req->name, 
                    'gender' => $req->gender,
                    'parent' => $req->parent,
                    'updated_at' => Carbon::now('Asia/Jakarta')
                ]);

            if($affected>0){
                return response()->json(array(
                    'OUT_STAT' => 'T',
                    'OUT_MESS' => 'Success'
                ), 200);
            }
            else{
                return response()->json(array(
                    'OUT_STAT' => 'F',
                    'OUT_MESS' => 'Id not found',
                    'OUT_DATA' => []
                ), 500);
            }

        } catch (\Throwable $th) {
            return response()->json(array(
                'OUT_STAT' => 'F',
                'OUT_MESS' => 'Failed',
                'OUT_DATA' => []
            ), 500);
        }
    }

    public function delete(Request $req){
        try {
            $req->validate([
                'id' => 'required'
            ]);

            $affected = DB::table('tbl_families')
                ->where('id', $req->id)
                ->delete();

            if($affected>0){
                return response()->json(array(
                    'OUT_STAT' => 'T',
                    'OUT_MESS' => 'Success'
                ), 200);
            }
            else{
                return response()->json(array(
                    'OUT_STAT' => 'F',
                    'OUT_MESS' => 'Id not found',
                    'OUT_DATA' => []
                ), 500);
            }
            
        } catch (\Throwable $th) {
            return response()->json(array(
                'OUT_STAT' => 'F',
                'OUT_MESS' => 'Failed',
                'OUT_DATA' => []
            ), 500);
        }
    }

}
