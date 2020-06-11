<?php

namespace App\Http\Controllers;

use App\Patient;
use Illuminate\Http\Request;

class ShareKeyController extends Controller
{
    /**
     * Check shareKey exist
     *
     * @return \Illuminate\Http\Response
     */
    public function checkShareKey(Request $request)
    {
        $deviceUserId = $request->get('deviceUserId');
        $patient = Patient::where('deviceUserId', '=' , $deviceUserId)->first();

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
        $patient = Patient::all();

        return view('sharekeys.index')->with(['sharekeys' => $patient]);
    }
}
