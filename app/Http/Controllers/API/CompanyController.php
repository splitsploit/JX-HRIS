<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Requests\CreateCompanyRequest;
use App\Models\Company;

class CompanyController extends Controller
{
    public function all(Request $request)
    {
        $id = $request->input('id');
        $name = $request->input('name');
        $limit = $request->input('limit', 10);

        //jxhris.com/api/company?id=1
        if ($id)
        {
            $company = Company::with('users')->find($id);

            if ($company)
            {
                return ResponseFormatter::success($company, 'Company Found!');
            }

            return ResponseFormatter::error('Company Not Found!', 404);
        }

        // jxhris.com/api/company
        $companies = Company::with(['users']);

        // jxhris.com/api/company?name=NamaCompany
        if ($name)
        {
            $companies->where('name', 'like', '%' . $name . '%');
        }

        return ResponseFormatter::success(
            $companies->paginate($limit),
            'Company Found!'
        );
    }

    public function create(CreateCompanyRequest $request)
    {

        if($request->hasFile('logo'))
        {
            $path = $request->file('logo')->store('public/logos');
        }

        $company = Company::create([
            'name' => $request->name,
            'logo' => $path,
        ]);
    }
}
