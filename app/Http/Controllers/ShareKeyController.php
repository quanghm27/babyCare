<?php

namespace App\Http\Controllers;

use App\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShareKeyController extends Controller
{
    /**
     * Check shareKey exist
     *
     * @return \Illuminate\Http\Response
     */
    public function checkShareKey(Request $request)
    {
        $operator_id = Auth::user()->id;
        $deviceUserId = $request->get('deviceUserId');
        $patient = Patient::where('deviceUserId', '=' , $deviceUserId)->where('operator_id', '=', $operator_id)->first();

        if (!is_null($patient)) {
            $result = [
                'status' => false,
                'message' => 'Đã tồn tại hồ sơ của mã chia sẻ này',
                'data' => null
            ];
        } else {
            $result = [
                'status' => true,
                'message' => '',
                'data' => null
            ];
        }

        return response()->json($result);
    }

    public function index()
    {
        $operator_id = Auth::user()->id;
        $patient = Patient::where('operator_id', $operator_id)->get();

        return view('sharekeys.index')->with(['sharekeys' => $patient]);
    }
}
