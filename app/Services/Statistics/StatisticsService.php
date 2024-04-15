<?php

namespace App\Services\Statistics;

use App\Models\Company;

class StatisticsService implements StatisticsServiceInterface
{
    public function getStatistics(): array
    {
        $companies                        = Company::all();
        $totalCompanies                   = $companies->count();
        $companiesWithAtLeastOneDataPoint = $companies->filter(fn(Company $company) => !empty($company->phone_numbers) || !empty($company->social_media_links))->count();
        $companiesWithPhoneNumbersCount   = $companies->filter(fn(Company $company) => !empty($company->phone_numbers))->count();
        $companiesWithSocialLinksCount    = $companies->filter(fn(Company $company) => !empty($company->social_media_links))->count();
        $companiesWithBothDataPoints      = $companies->filter(fn(Company $company) => !empty($company->phone_numbers) && !empty($company->social_media_links))->count();
        $companiesWithAddresses           = $companies->filter(fn(Company $company) => !empty($company->address))->count();

        $phonesDataCoverage = ($totalCompanies > 0) ? (($companiesWithPhoneNumbersCount / $totalCompanies) * 100) : 0;
        $linksDataCoverage  = ($totalCompanies > 0) ? (($companiesWithSocialLinksCount / $totalCompanies) * 100) : 0;

        return [
            'total_companies'                              => $totalCompanies,
            'total_companies_with_at_least_one_data_point' => $companiesWithAtLeastOneDataPoint,
            'total_companies_with_both_data_points'        => $companiesWithBothDataPoints,
            'total_companies_with_phone_numbers'           => $companiesWithPhoneNumbersCount,
            'total_companies_with_social_links'            => $companiesWithSocialLinksCount,
            'total_companies_with_addresses'               => $companiesWithAddresses,
            'phones_data_coverage'                         => round($phonesDataCoverage, 2) . '%',
            'links_data_coverage'                          => round($linksDataCoverage, 2) . '%',
        ];
    }
}
