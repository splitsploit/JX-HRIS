<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use PhpParser\Node\Stmt\TryCatch;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\CreateCompanyRequest;

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

        try {
                    
            // upload logo
            if($request->hasFile('logo'))
            {
                $path = $request->file('logo')->store('public/logos');
            }

            // create company
            $company = Company::create([
                'name' => $request->name,
                'logo' => $path,
            ]);

            if(!$company)
            {
                throw new Exception('Company Not Created!');
            }

            // attach company to user
            $user = User::find(Auth::id());
            $user->companies()->attach($company->id);

            // load users at company
            $company->load('users');

            return ResponseFormatter::success($company, 'Company Created');

        } catch (Exception $e) {
            return ResponseFormatter::error($e->getMessage(), 500);
        }

        
    }
}
