<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\CampaignResult;
use App\Models\Campaign;
use GuzzleHttp\Client;
use App\Http\Resources\CampaignResource;
use App\Http\Resources\CampaignResultResource;

class CampaignController extends Controller
{
    public function getLocations()
    {
        $locations = CampaignResult::select('location')->distinct()->get();
        return $locations;
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
            $query->where('datasource', 'like', "$request->search%")
                ->orwhereHas('Asset', function($que) use($request){
                    $que->where('asset_name', 'like', "$request->search%");
                });
        }
        $campaign = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return CampaignResource::collection($campaign);
    }

    public function addCampaign(Request $request)
    {
        $data = $request->validate([
            'asset_id' => 'required|exists:assets,asset_id',
            'datasource' => 'required',
            'file' => 'required|file|mimes:pdf',
            'job_date_time' => 'required',
            'script' => 'required'
        ]);

        $data['job_no'] = $this->generateCampaignNo();

        if ($request->hasFile('file')) 
        {
            $fileName = $request->file('file')->getClientOriginalName();
            $filePath = public_path('storage/files'); 
            $request->file('file')->move($filePath, $fileName);
            $data['file'] = $fileName;
        } 
        $campaign = Campaign::create($data);

        //CampaignResult 
        $client = new Client();
        $fullFilePath = $filePath . '/' . $fileName;
        
        $response = $client->post('http://127.0.0.1:5000/runCampain', [
            'json' => [
                    'pdf_file' => $fullFilePath 
                ]
            ]
        );

        $responseContent = $response->getBody()->getContents();
        $responseDatas = json_decode($responseContent, true);

        foreach($responseDatas['result'] as $responseData)
        {
            $compaign_Result = CampaignResult::create([
                'campaign_id' => $campaign->campaign_id,
                'asset_id' => $campaign->asset_id,
                'location' => $responseData['location'],
                'file' => $responseData['file'],
                'date' => $responseData['date']
            ]);   
        }

        //Images
        $compaign_images = CampaignResult::where('campaign_id', $campaign->campaign_id)->get();

        return response()->json([
            "message" => "HealthCheck Created Successfully",
            CampaignResultResource::collection($compaign_images)
        ]); 
    }

    public function campaignResultImages(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,asset_id',
            'location' => 'required',
            'from_date' => 'nullable|date',
            'to_date' => 'required|date',
            'datasource' => 'required'
        ]);

        $compaign_result_query = CampaignResult::whereHas('Campaign', function($que) use($request) 
        {
            $fromDate = $request->from_date . ' 00:00:00';
            $toDate = $request->to_date . ' 23:59:59';

            $que->whereBetween('job_date_time', [$fromDate, $toDate])->where('datasource', $request->datasource);
        });
        $compaign_result = $compaign_result_query->where('asset_id', $request->asset_id)->where('location', $request->location)->get();

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
            'message' => 'HealthCheck Deleted Successfully'
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