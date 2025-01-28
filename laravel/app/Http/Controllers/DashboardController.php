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
use Illuminate\Support\Facades\Mail;

class DashboardController extends Controller
{
    public function getDashboardContent(Request $request)
    {
        // $authPlantId = Auth::User()->plant_id;

        $user = User::count();
        $asset = Asset::count();
        $equipment = Equipment::count();
        $service_type = ServiceType::count();
        // $deviations = UserAssetCheck::where('field_type', 'Number')->whereRaw('value < lcl OR value > ucl')
        // ->orwhere('field_type', '!=', 'Number')->whereColumn('default_value', '!=', 'value')->where('remark_status', false)->count();
        $deviations = UserAssetCheck::where('remark_status', false)->where(function ($q) {
            $q->where(function ($q) {
                $q->where('field_type', 'Number')
                ->whereRaw("(CAST(value AS DOUBLE PRECISION) < lcl OR CAST(value AS DOUBLE PRECISION) > ucl)");
            })->orWhere(function ($q) {
                $q->where('field_type', '!=', 'Number')->where('field_type', '!=', 'Date')->where('field_type', '!=', 'Date & Time')
                  ->where('field_type', '!=', 'Text')->where('field_type', '!=', 'Text Area')
                  ->where('field_type', 'Select')
                  ->whereColumn('default_value', '!=', 'value');
            });
        })->count();

        $pending_services = UserService::where('next_service_date', '<', Carbon::today())->where('is_latest', true)->count();

        $upcoming_jobs = UserService::whereBetween('next_service_date', [Carbon::today(), Carbon::today()->addDays(6)])->where('is_latest', true)->count();

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

    public function getMails()
    {
        $test = "Hello Welcome to Digitrack Asset Management";
        
        Mail::raw($test, function ($message) {
            $message->to('bharatesh.s@akxatech.com')->cc('sammed@aeriesys.com')->subject('Test Email');
        });
    }
}
