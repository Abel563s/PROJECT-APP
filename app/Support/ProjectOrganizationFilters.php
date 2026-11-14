<?php

namespace App\Support;

use App\Models\BusinessUnit;
use App\Models\Sector;
use Illuminate\Http\Request;

class ProjectOrganizationFilters
{
    public static function effectiveBusinessUnitId(Request $request): ?int
    {
        if ($request->exists('business_unit_id')) {
            return $request->filled('business_unit_id') ? (int) $request->input('business_unit_id') : null;
        }

        $user = $request->user();
        if ($user && ($user->isAdmin() || $user->isManager() || $user->isUser())) {
            return $user->business_unit_id ? (int) $user->business_unit_id : null;
        }

        return null;
    }

    public static function effectiveSectorId(Request $request): ?int
    {
        if ($request->exists('sector_id')) {
            return $request->filled('sector_id') ? (int) $request->input('sector_id') : null;
        }

        $user = $request->user();
        if ($user && !$user->isSuperadmin()) {
            return $user->sector_id ? (int) $user->sector_id : null;
        }

        return null;
    }

    public static function options(Request $request): array
    {
        $businessUnitId = self::effectiveBusinessUnitId($request);

        return [
            'businessUnits' => BusinessUnit::active()->orderBy('name')->get(['id', 'name']),
            'allOrganizationSectors' => Sector::active()->orderBy('name')->get(['id', 'business_unit_id', 'name']),
            'organizationSectors' => Sector::active()
                ->when($businessUnitId, fn ($query) => $query->where('business_unit_id', $businessUnitId))
                ->orderBy('name')
                ->get(['id', 'business_unit_id', 'name']),
        ];
    }

    public static function apply($query, Request $request, bool $projects = false)
    {
        $businessUnitId = self::effectiveBusinessUnitId($request);
        if (!$businessUnitId && !$request->filled('sector_id')) {
            return $query;
        }

        $filter = function ($projectQuery) use ($request, $businessUnitId) {
            if ($businessUnitId) {
                $projectQuery->where('business_unit_id', $businessUnitId);
            }
            if ($request->filled('sector_id')) {
                $projectQuery->where('sector_id', $request->input('sector_id'));
            }
        };

        if ($projects) {
            $filter($query);
            return $query;
        }

        return $query->whereHas('project', $filter);
    }
}
