<?php

namespace App\Services\Statistics;

use App\Models\Company;

class StatisticsService implements StatisticsServiceInterface
{
    public function getStatistics(): array
    {
        $companies                      = Company::all();
        $totalCompanies                 = $companies->count();
        $companiesWithPhoneNumbersCount = $companies->where('phone_numbers', '!=', null)
                                                    ->where('phone_numbers', '!=', [])
                                                    ->count();
        $companiesWithSocialLinksCount  = $companies->where('social_media_links', '!=', null)
                                                    ->where('social_media_links', '!=', [])
                                                    ->count();
        $companiesWithBothDataPoints    = $companies->where('phone_numbers', '!=', null)
                                                    ->where('phone_numbers', '!=', [])
                                                    ->where('social_media_links', '!=', null)
                                                    ->where('social_media_links', '!=', [])
                                                    ->count();

        $phonesDataCoverage = ($totalCompanies > 0) ? (($companiesWithPhoneNumbersCount / $totalCompanies) * 100) : 0;
        $linksDataCoverage  = ($totalCompanies > 0) ? (($companiesWithSocialLinksCount / $totalCompanies) * 100) : 0;

        return [
            'total_companies'                       => $totalCompanies,
            'total_companies_with_both_data_points' => $companiesWithBothDataPoints,
            'total_companies_with_phone_numbers'    => $companiesWithPhoneNumbersCount,
            'total_companies_with_social_links'     => $companiesWithSocialLinksCount,
            'phones_data_coverage'                  => round($phonesDataCoverage, 2) . '%',
            'links_data_coverage'                   => round($linksDataCoverage, 2) . '%',
        ];
    }
}
