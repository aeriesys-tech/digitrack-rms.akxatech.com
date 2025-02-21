<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\CampaignResult;
use App\Models\Campaign;
use GuzzleHttp\Client;
use App\Http\Resources\CampaignResource;
use App\Http\Resources\CampaignResultResource;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\AssetDataSource;
use GuzzleHttp\Exception\ServerException;

class CampaignController extends Controller
{
    public function getLocations(Request $request)
    {
        $locations = CampaignResult::whereHas('Campaign', function($que) use($request){
            $que->where('script', $request->script);
        })->select('location')->distinct()->get();

        return $locations;
    }
    
    public function getScripts(Request $request)
    {
        $scripts = AssetDataSource::where('asset_id', $request->asset_id)->distinct()->pluck('script'); 
        return response()->json($scripts);
    }

    public function paginateCampaigns(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = Campaign::query();

        if(isset($request->asset_id))
        {
            $query->where('asset_id',$request->asset_id);
        }

        if(isset($request->datasource))
        {
            $query->where('datasource',$request->datasource);
        }
    
        if($request->search!='')
        {
            $query->where('datasource', 'like', "%$request->search%")->orwhere('job_no', 'like', "%$request->search%")->orwhere('job_date_time', 'like', "%$request->search%")
            ->orwhere('datasource', 'like', "%$request->search%")->orwhere('script', 'like', "%$request->search%")
                ->orwhereHas('Asset', function($que) use($request){
                    $que->where('asset_name', 'like', "%$request->search%");
                });
        }

        if ($request->keyword == 'asset_name') {
            $query->join('assets', 'campaigns.asset_id', '=', 'assets.asset_id')->select('campaigns.*') 
                  ->orderBy('assets.asset_name', $request->order_by);
        }
        else {
            $query->orderBy($request->keyword, $request->order_by);
        }

        $campaign = $query->withTrashed()->paginate($request->per_page); 
        return CampaignResource::collection($campaign);
    }

    // public function addCampaign(Request $request)
    // {
    //     $data = $request->validate([
    //         'asset_id' => 'required|exists:assets,asset_id',
    //         'datasource' => 'required',
    //         'file' => 'required|file|mimes:pdf,jpeg,jpg,png',
    //         'job_date_time' => 'required',
    //         'script' => 'required'
    //     ]);

    //     $data['job_no'] = $this->generateCampaignNo();

    //     if ($request->hasFile('file')) 
    //     {
    //         $fileName = $request->file('file')->getClientOriginalName();
    //         $filePath = public_path('storage/files'); 
    //         $request->file('file')->move($filePath, $fileName);
    //         $data['file'] = $fileName;
    //     } 
    //     $campaign = Campaign::create($data);

    //     //CampaignResult 
    //     $client = new Client();
    //     $fullFilePath = $filePath . '/' . $fileName;

    //     //Ladle Scanner
    //     if ($request->script == "Ladle Scanner") 
    //     {
    //         $response = $client->post('http://127.0.0.1:5000/runCampain', [
    //             'json' => [
    //                 'pdf_file' => $fullFilePath 
    //             ]
    //         ]);

    //         $responseContent = $response->getBody()->getContents();
    //         $responseDatas = json_decode($responseContent, true);
    
    //         foreach($responseDatas['result'] as $responseData)
    //         {
    //             $compaign_Result = CampaignResult::create([
    //                 'campaign_id' => $campaign->campaign_id,
    //                 'asset_id' => $campaign->asset_id,
    //                 'location' => $responseData['location'],
    //                 'file' => $responseData['file'],
    //                 'date' => $responseData['date']
    //             ]);   
    //         }
    //     }
         
    //     //Torpedo Scanner
    //     if ($request->script == "Torpedo Scanner") 
    //     {
    //         $response = $client->post('http://127.0.0.1:5000/runTorpedo', [
    //             'json' => [
    //                 'pdf_file' => $fullFilePath
    //             ]
    //         ]);
    //         $responseContent = $response->getBody()->getContents();
    //         $responseDatas = json_decode($responseContent, true);
    
    //         // return $responseDatas['result']['torpedo_values'];
    //         CampaignResult::create([
    //             'campaign_id' => $campaign->campaign_id,
    //             'asset_id' => $campaign->asset_id,
    //             'file' => $campaign->file,
    //             'date' => Carbon::now(),
    //             'torpedo_values' => implode(',', $responseDatas['result']['torpedo_values'])
    //         ]);
    //     }

    //     //Images
    //     $compaign_images = CampaignResult::where('campaign_id', $campaign->campaign_id)->get();

    //     return response()->json([
    //         "message" => "HealthCheck Created Successfully",
    //         CampaignResultResource::collection($compaign_images)
    //     ]); 
    // }

    public function addCampaign(Request $request)
    {
        $data = $request->validate([
            'asset_id' => 'required|exists:assets,asset_id',
            'datasource' => 'required',
            'file' => 'required|file|mimes:pdf,jpeg,jpg,png',
            'job_date_time' => 'required',
            'script' => 'required'
        ]);

        $data['job_no'] = $this->generateCampaignNo();

    
        if ($request->hasFile('file')) {
        
            $originalFileName = $request->file('file')->getClientOriginalName();
            $timestamp = now()->format('Ymd_His');
            $fileName = pathinfo($originalFileName, PATHINFO_FILENAME) . '_' . $timestamp . '.' . $request->file('file')->getClientOriginalExtension();
            $filePath = public_path('storage/files');
            $request->file('file')->move($filePath, $fileName);
            $data['file'] = $fileName;
        }
    
        $campaign = Campaign::create($data);
        
        $client = new Client();
        $fullFilePath = isset($filePath) ? $filePath . '/' . $fileName : null;

        if ($request->script == "Ladle Scanner") 
        {
            try {
                $response = $client->post('http://127.0.0.1:5000/runCampain', [
                    'json' => [
                        'pdf_file' => $fullFilePath
                    ]
                ]);
    
                $responseContent = $response->getBody()->getContents();
                $responseDatas = json_decode($responseContent, true);
    
                if (isset($responseDatas['result']) && is_array($responseDatas['result'])) {
                    foreach ($responseDatas['result'] as $responseData) {
                        CampaignResult::create([
                            'campaign_id' => $campaign->campaign_id,
                            'asset_id' => $campaign->asset_id,
                            'location' => $responseData['location'],
                            'file' => $responseData['file'],
                            'date' => $responseData['date']
                        ]);
                    }
                } else {
                    return response()->json(['message' => 'Ladle Scanner could not be processed'], 400);
                }
            } catch (ServerException $e) {
                return response()->json(['message' => 'Ladle Scanner could not be processed'], 400);
            }
        }
    
        // Torpedo Scanner
        if ($request->script == "Torpedo Scanner") 
        {
            try {
                $response = $client->post('http://127.0.0.1:5000/runTorpedo', [
                    'json' => [
                        'pdf_file' => $fullFilePath
                    ]
                ]);
    
                $responseContent = $response->getBody()->getContents();
                $responseDatas = json_decode($responseContent, true);
    
                if (isset($responseDatas['result']['torpedo_values']) && is_array($responseDatas['result']['torpedo_values'])) {
                    CampaignResult::create([
                        'campaign_id' => $campaign->campaign_id,
                        'asset_id' => $campaign->asset_id,
                        'file' => $campaign->file,
                        'date' => Carbon::now(),
                        'torpedo_values' => implode(',', $responseDatas['result']['torpedo_values'])
                    ]);
                } else {
                    return response()->json(['message' => 'Torpedo Scanner could not be processed'], 400);
                }
            } catch (ServerException $e) {
                return response()->json(['message' => 'Torpedo Scanner could not be processed'], 400);
            }
        }
        
        $campaign_images = CampaignResult::where('campaign_id', $campaign->campaign_id)->get();
        return response()->json([
            "message" => "HealthCheck Created Successfully",
            CampaignResultResource::collection($campaign_images)
        ]);
    }
    
    public function campaignResultImages(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,asset_id',
            'location' => 'nullable|sometimes',
            'from_date' => 'nullable|date',
            'to_date' => 'required|date',
            'datasource' => 'required',
            'script' => 'required'
        ]);

        $fromDate = $request->from_date ? $request->from_date . ' 00:00:00' : '1970-01-01 00:00:00';
        $toDate = $request->to_date . ' 23:59:59';

        $compaign_result_query = CampaignResult::join('campaigns', 'campaigns.campaign_id', '=', 'campaign_results.campaign_id')
            ->whereBetween('campaigns.job_date_time', [$fromDate, $toDate])->where('campaigns.datasource', $request->datasource)
            ->where('campaign_results.asset_id', $request->asset_id)->where('campaign_results.location', $request->location)->orderBy('campaigns.job_date_time')
            ->select('campaign_results.*');

        $compaign_result = $compaign_result_query->get();
        return CampaignResultResource::collection($compaign_result);
    }

    public function deleteHealthCheck(Request $request)
    {
        $request->validate([
            'campaign_id' => 'required|exists:campaigns,campaign_id'
        ]);

        CampaignResult::where('campaign_id', $request->campaign_id)->forceDelete();
        Campaign::where('campaign_id', $request->campaign_id)->forceDelete();

        return response()->json([
            'message' => 'Health Check Register Deleted Successfully'
        ]);
    }

    public function generateCampaignNo()
    {
        $campaign = Campaign::latest()->first();
        $nextActivityNumber = 1; 
        
        if ($campaign) {
            $lastActivityNumber = (int) substr($campaign->job_no, 9); 
            $nextActivityNumber = $lastActivityNumber + 1;
        }
        
        $formattedNextActivityNumber = str_pad($nextActivityNumber, 4, '0', STR_PAD_LEFT);
        $job_no = 'Campaign_' . $formattedNextActivityNumber;
        
        while (Campaign::where('job_no', $job_no)->exists()) {
            $nextActivityNumber++;
            $formattedNextActivityNumber = str_pad($nextActivityNumber, 4, '0', STR_PAD_LEFT);
            $job_no = 'Campaign_' . $formattedNextActivityNumber;
        }
        return $job_no;
    }
}