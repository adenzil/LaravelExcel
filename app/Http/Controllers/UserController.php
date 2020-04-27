<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function import()
    {
        if(request()->has('your_file')) {
            try {
                $uploadedFile = request()->file('your_file');
                Excel::import(new UsersImport, $uploadedFile);
                return response()->json(['success'=>true], 200);
            } catch (\Exception $e) {
                $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                $ret = array(
                    'success' => false,
                    'error' => array(
                        'message' => $e->getMessage(),
                        'trace' => $e->getTrace()
                    )
                );
                return response()->json($ret, $responseCode);
            }
        }

        return response()->json(['success'=>false], 500);
    }
}
