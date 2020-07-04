<?php

namespace App\Http\Controllers;

use App\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShareKeyController extends Controller
{
    /**
     * Check shareKey exist
     *
     * @return \Illuminate\Http\Response
     */
    public function checkNewShareKey(Request $request)
    {
        $operator_id = Auth::user()->id;
        $deviceUserId = $request->get('deviceUserId');
        $sharekey = $request->get('shareKey');

        // Check sharekey exist
        $patient = Patient::where('shareKey', $sharekey)->first();
        if (!is_null($patient)) {
            $result = [
                'status' => false,
                'message' => 'Mã chia sẻ bị trùng',
                'data' => null
            ];
            return response()->json($result);
        }

        // Check profile exist
        $patient = Patient::where('deviceUserId', '=' , $deviceUserId)->where('operator_id', '=', $operator_id)->first();
        if (!is_null($patient)) {
            $result = [
                'status' => false,
                'message' => 'Đã tồn tại hồ sơ của mã chia sẻ này trong khoa',
                'data' => null
            ];
            return response()->json($result);
        }

        $result = [
            'status' => true,
            'message' => '',
            'data' => null
        ];

        return response()->json($result);
    }

    public function checkEditShareKey(Request $request)
    {
        $operator_id = Auth::user()->id;
        $deviceUserId = $request->get('deviceUserId');
        $sharekey = $request->get('shareKey');

        // Check sharekey exist
        $patient = Patient::where('shareKey', $sharekey)->first();
        if (!is_null($patient)) {
            $result = [
                'status' => false,
                'message' => 'Mã chia sẻ bị trùng',
                'data' => null
            ];
            return response()->json($result);
        }

        // Check profile exist
        $patient = Patient::where('deviceUserId', '=' , $deviceUserId)->where('operator_id', '=', $operator_id)->first();
        if (is_null($patient)) {
            $result = [
                'status' => false,
                'message' => 'Mã chia sẻ không khớp với hồ sơ này',
                'data' => null
            ];
            return response()->json($result);
        }

        $result = [
            'status' => true,
            'message' => '',
            'data' => null
        ];

        return response()->json($result);
    }

    public function index()
    {
        $operator_id = Auth::user()->id;
        $patient = Patient::where('operator_id', $operator_id)->get();

        return view('sharekeys.index')->with(['sharekeys' => $patient]);
    }

    public function edit($sharekey)
    {
        $operator_id = Auth::id();
        $patient = Patient::where('shareKey', $sharekey)->where('operator_id', $operator_id)->firstOrFail();

        return view('patients.edit')->with(['patient' => $patient, 'edit' => true]);
    }

    public function show($sharekey)
    {
        $operator_id = Auth::id();
        $patient = Patient::where('shareKey', $sharekey)->where('operator_id', $operator_id)->firstOrFail();

        return view('patients.edit')->with(['patient' => $patient, 'edit' => false]);
    }

    public function toggleReleasePatient(Request $request)
    {
        $id = $request->get('id');
        $releaseTime = $request->get('releaseTime');

        $patient = Patient::findOrFail($id);
        try {
            DB::beginTransaction();
            if (!is_null($patient->releaseDate)) {
                $patient->releaseDate = null;
            } else {
                $patient->releaseDate = $releaseTime;
            }
            $patient->save();
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            return $exception->getMessage();
        }

        return response()->json([
            'data' => null,
            'message' => 'OK',
            'status' => true
        ]);
    }
}
