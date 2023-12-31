<?php


namespace App\Http\Controllers;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

use App\Models\Company;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use Illuminate\Support\Facades\Storage;


/**
 * @OA\Get(
 *      path="/api/companies/",
 *      operationId="getListOfCompanies",
 *      tags={"Companies"},
 *      summary="Get list of Companies",
 *      description="Returns a list of Companies",
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

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $page = $request->query('page');
            if($page != ""){
                $companies = Company::paginate(10);
            } else {
                $companies = Company::orderBy('name')->get();
            }
            return response()->json($companies, 200);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }
    }

    public function count(){
        try {
            $count = Company::count();
            return response()->json(['count'=> $count],200);

        }catch (\Exception $e) {

            return response()->json($e->getMessage(), 500);
         }
    }

    public function companiesByEmployeesCount(int $companyCount)
    {
        $companies = Company::withCount('employees')->orderBy('employees_count', 'desc')->take($companyCount)->get();

        return response()->json($companies, 200);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompanyRequest $request)
    {
        try {
            // Process logo as file, move to store/app/public/images and set the logo url to images/
            $logoFile = $request->file('logoFile');
            if ($logoFile != null) {
                $logoName = time() . '.' . $logoFile->getClientOriginalExtension();
                $logoFile->storeAs('/public/images', $logoName);
                $request['logo'] = '/storage/images/' . $logoName;
            }

            $company = Company::create($request->all());
            return response()->json($company, 200);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        try {
            return response()->json($company,200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Company $company)
    {

    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCompanyRequest $request, Company $company)
    {
        try {
            $logoFile = $request->file('logoFile');
            if ($logoFile != null) {
                $logoName = time() . '.' . $logoFile->getClientOriginalExtension();
                $logoFile->storeAs('/public/images', $logoName);
                $request['logo'] = '/storage/images/' . $logoName;
                $oldLogo = $company->logo;
            }
            $company->update($request->all());

            if(isset($oldLogo) && $oldLogo != null) {
                Storage::delete(Str::replace('storage', 'public', $oldLogo));
            }

            return response()->json($company);

        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Company $company)
    {
        try {
            $companyLogo = $company->logo;
            $company->delete();
            if($companyLogo != null) {
                Storage::delete(Str::replace('storage', 'public', $companyLogo));
            }
            return response(200);
        } catch (\Exception $e) {
            return response()->json($e->getMessage(), 500);
        }

    }

}
