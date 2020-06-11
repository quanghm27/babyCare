<?php

namespace App\Http\Controllers;

use App\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    //
    public function index() {
        $departments = Department::all();

        return view('departments.index')->with(['departments' => $departments]);
    }

    public function store(Request $request) {

        $department = new Department();
        $department->name = $request->get('name');
        $department->save();

        return redirect()->route('departments.index');
    }

    public function update($id, Request $request) {

        $department = Department::findOrFail($id);
        $department->name = $request->get('name');
        $department->save();

        return redirect()->route('departments.index');
    }

    public function apiGetDepartments() {

        $departments = Department::all();
        $result = [
            'status' => true,
            'data' => $departments,
            'message' => 'ok'
        ];
        return response()->json($result, 200);
    }
}
