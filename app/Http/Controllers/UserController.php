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
        Excel::import(new UsersImport, request()->file('your_file'));

        return response()->json(['success'=>true]);
    }
}
