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
            $query->where('asset_id', 'like', "%$request->search%")
                 ->orWhere('datasource', 'like', "$request->search%");
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
        ]);

        if ($request->hasFile('file')) 
        {
            $fileName = time().'.'.$request->file('file')->getClientOriginalExtension();
            $filePath = public_path('storage/files'); 
            $request->file('file')->move($filePath, $fileName);
            $data['file'] = $fileName;
        } 
        $campaign = Campaign::create($data);

        //Campaign Result 
        $client = new Client();
        $headers = [
            // "pdf_file" => "C:\\xampp\\htdocs\\digitrack-rms.akxatech.com\\laravel\\public\\rms_script\\Ladle 9.C-31.H-68.6800.pdf"
            "file" => $filePath . '/' . $fileName
        ];
        
        $responseDatas = $client->post('http://127.0.0.1:5000/runCampain', [
            'headers' => $headers
        ]);

        foreach($responseDatas as $response)
        {
            $compaign_Result = CampaignResult::create([
                'campaign_id' => $campaign->campaign_id,
                'asset_id' => $request->asset_id,
                'location' => $response['location'],
                'file' => $response['file'],
                'date' => $response['date']
            ]);   
        }

        return response()->json([
            "message" => "Campaign Created Successfully"
        ]); 
    }

    public function imagesCampaignResult(Request $request)
    {
        $compaign_result = CampaignResult::all();
        return CampaignResultResource::collection($compaign_result);
    }
}