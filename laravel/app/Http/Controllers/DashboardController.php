<?php

namespace App\Http\Controllers;
use App\Models\User;
use App\Models\Asset;
use App\Models\Equipment;
use App\Models\UserAssetCheck;
use App\Models\ServiceType;
use App\Models\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function getDashboardContent(Request $request)
    {
        $authPlantId = Auth::User()->plant_id;

        $user = User::where('plant_id', $authPlantId)->count();
        $asset = Asset::where('plant_id', $authPlantId)->count();
        $equipment = Equipment::where('plant_id', $authPlantId)->count();
        $service_type = ServiceType::count();
        $deviations = UserAssetCheck::whereHas('UserCheck', function($query) use ($authPlantId) {
                $query->where('plant_id', $authPlantId);
            })->whereColumn('default_value', '!=', 'value')->count();

        $pending_services = UserService::where('plant_id', $authPlantId)->where('next_service_date', '<=', Carbon::now())->where('is_latest', true)->count();

        $upcoming_jobs = UserService::where('plant_id', $authPlantId)->where('next_service_date', '>=', Carbon::now())
            ->where('is_latest', true)->count();

        return response()->json([
            'user' => $user,
            'asset' => $asset,
            'equipment' => $equipment,
            'service_type' => $service_type,
            'deviations' => $deviations,
            'pending_services' => $pending_services,
            'upcoming_jobs' => $upcoming_jobs
        ]);
    }
}
