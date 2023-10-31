<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use Illuminate\Http\Request;
use App\Models\Employee;

/**
 * @OA\Get(
 *      path="/api/employees/",
 *      operationId="getListOfEmployees",
 *      tags={"Employees"},
 *      summary="Get list of Employees",
 *      description="Returns a list of Employees",
 *      @OA\Response(
 *          response=200,
 *          description="List of Company objects",
 *          @OA\JsonContent()
 *      ),
 *      @OA\Response(
 *          response=400, 
 *          description="Bad request"
 *      ),
 *      @OA\Parameter(
 *          name="Content-Type",
 *          in="header",
 *          required=true,
 *          @OA\Schema(type="string"),
 *          example="application/json",
 *          description="Content Type"
 *      ),
 *      security={
 *          {"bearerAuth": {}}
 *      },
 * )
 **/

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $page = $request->query('page');
            if ($page != "") {
                $employees = Employee::with('company')->paginate(10);
            } else {
                $employees = Employee::orderBy('name')->get();
            }
            return response()->json($employees, 200);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function count()
    {
        try {
            $count = Employee::count();
            return response()->json(['count' => $count], 200);

        } catch (\Exception $e) {

            return response()->json($e->getMessage(), 500);
        }
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
     * @param  \App\Http\Requests\StoreEmployeeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEmployeeRequest $request)
    {
        try {
            $employee = Employee::create($request->all());
            return response()->json($employee, 200);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function show(Employee $employee)
    {
        try {
            return response()->json($employee, 200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function edit(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEmployeeRequest  $request
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        try {
            $employee->update($request->all());
            return response()->json($employee);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();
            return response(200);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }
}
