<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request): CompanyResource
    {
        $query = Company::query();

        if ($request->filled('name')) {
            $query->where(function ($q) use ($request) {
                $q->where('commercial_name', 'like', "%{$request->name}%")
                  ->orWhere('legal_name', 'like', "%{$request->name}%")
                  ->orWhere('all_available_names', 'like', "%{$request->name}%");
            });
        }

        if ($request->filled('website')) {
            $query->where('domain', '=', $request->website);
        }

        if ($request->filled('phone')) {
            $query->whereJsonContains('phone_numbers', $request->phone);
        }

        if ($request->filled('facebook')) {
            $query->whereJsonContains('social_media_links', ['url' => $request->facebook, 'platform' => 'Facebook']);
        }

        $company = $query->first();

        return new CompanyResource($company);

    }
}
