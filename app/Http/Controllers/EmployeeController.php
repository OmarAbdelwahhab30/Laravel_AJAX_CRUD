<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateEmployeeRequest;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return array|\Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View|string
     */
    public function index()
    {
        return view("index");
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ValidateEmployeeRequest $request)
    {
        Employee::Insert([
            'name'   => $request->name ,
            'phone'     => $request->phone,
        ]);
        return response()->json("success");
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(ValidateEmployeeRequest $request): \Illuminate\Http\JsonResponse
    {
        $employee = Employee::find($request->id);
        $employee->name =  $request->name;
        $employee->phone = $request->phone;
        $employee->save();
        return response()->json("success");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request)
    {
        $employee = Employee::find($request->id);
        if ($employee->delete()) {
            return response()->json("success");
        }else{
            return response()->json("fail");
        }
    }


    public function GetAllEmployees()
    {
        $employees = Employee::all();
        return response()->json($employees);
    }

    public function SearchEmployee($word)
    {
        if (is_numeric($word)){
            $employee = Employee::where("phone",'like','%'.$word.'%')->get();
        }elseif (is_string($word)){
            $employee = Employee::where("name",'like','%'.$word.'%')->get();
        }
        return response()->json($employee);
    }
}
