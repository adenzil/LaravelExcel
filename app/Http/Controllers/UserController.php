<?php

namespace App\Http\Controllers;

use Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Imports\UsersImport;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Validation\ValidationException;

use App\Models\User;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $ret = [
            'success' => true
        ];
        $responseCode = Response::HTTP_OK;
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|max:191',
                'email' => 'required|email|unique:users',
                'password' => 'required',
            ]);
            if ($validator->fails()) {
                throw new ValidationException($validator);
            }
            $input = $request->all();
            $user = new User();
            $registered_user = $user->register($input);
            $success_ret = array(
                'success' => true,
                'data' => array(
                    'user' => $registered_user->toArray()
                )
            );
            $ret = array_merge($ret, $success_ret);
        } catch(\Exception $e) {
            $responseCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            $exception_ret = array(
                'success' => false,
                'error' => array(
                    'message' => $e->getMessage(),
                    'trace' => $e->getTrace()
                )
            );
            $ret = array_merge($ret, $exception_ret);
        }

        return response()
            ->json($ret, $responseCode);
    }

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
