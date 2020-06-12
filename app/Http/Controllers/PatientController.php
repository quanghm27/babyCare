<?php

namespace App\Http\Controllers;

use App\Patient;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPUnit\Util\Exception;

class PatientController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $operator_id = Auth::user()->id;
        $patients = Patient::where('operator_id', $operator_id)->get();

        $data = [
            'patients' => $patients
        ];

        return view('patients.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('patients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try {
            DB::beginTransaction();
            $patient = new Patient();
            $patient->roomNo = $request->get('roomNo');
            $patient->bedNo = $request->get('bedNo');
            $patient->shareKey = $request->get('shareKey');
            $patient->name = $request->get('name');
            $patient->medical_number = $request->get('medicalNo');
            $patient->phone_number = $request->get('phoneNo');
            $patient->deviceUserId = $request->get('deviceUserId');
            $patient->joinDate = $request->get('joinDate');
            $patient->minTemp = $request->get('minTemp');
            $patient->maxTemp = $request->get('maxTemp');
            $patient->note = $request->get('note');
            //$patient->avatar = $request->get('avatar');
            $patient->expiredTime = $request->get('expiredTime');
            $patient->operator_id = Auth::user()->id;
            $patient->save();
            DB::commit();
        }catch (Exception $e) {
            DB::rollBack();
            $result = [
                'status' => false,
                'message' => $e->getMessage(),
                'data' => $e->getCode()
            ];
        }

        $result = [
            'status' => true,
            'message' => 'Đã tạo hồ sơ thành công',
            'data' => null
        ];

        return response()->json($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function show(Patient $patient)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
//        $sharekey = $request->get('sharekey');
//        $operator_id = Auth::user()->id;
//        $patient = Patient::where('shareKey', $sharekey)->where('operator_id', $operator_id)->firstOrFail();

        $patient = Patient::findOrFail($id);
        try {
            DB::beginTransaction();
            $patient->shareKey = $request->get('sharekey');
            $patient->name = $request->get('name');
            $patient->bedNo = $request->get('bedNo');
            $patient->roomNo = $request->get('roomNo');
            $patient->maxTemp = $request->get('maxTemp');
            $patient->minTemp = $request->get('minTemp');
            $patient->joinDate = $request->get('joinDate');
            $patient->medical_number = $request->get('medical_number');
            $patient->phone_number = $request->get('phone_number');
            $patient->expiredTime = $request->get('expiredTime');
            $patient->note = $request->get('note');

            $patient->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e->getMessage();
        }

        return response()->json([
            'data' => null,
            'message' => 'OK',
            'status' => true
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        //
    }

    public function showHistory($shareKey)
    {
        $patient = Patient::where('shareKey', '=', $shareKey)->first();
        return view('patients.history');
    }
}
