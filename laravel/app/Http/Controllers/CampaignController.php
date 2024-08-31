<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use App\Models\CampaignResult;
use App\Models\Campaign;
use App\Http\Resources\CampaignResource;

class CampaignController extends Controller
{
    public function getLocations()
    {
        $locations = CampaignResult::select('location')->distinct()->get();
        return response()->json($locations);
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

    public function AddCampaign(Request $request)
    {
        $data = $request->validate([
            'asset_id' => 'required|exists:assets,asset_id',
            'datasource' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf',
        ]);

        $filePath = $request->file('file')->store('uploads/campaigns', 'public');

        $campaign = Campaign::create($data);

        return response()->json(["message" => "Campaign Created Successfully"]); 
    }

    public function image(Request $request)
    {
        if ($action === 'upload') 
        {
            $request->validate([
                'file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            ]);

            $fileName = null;
            if ($request->hasFile('file')) {
                $fileName = time() . '.' . $request->file('file')->getClientOriginalExtension();
                $request->file('file')->move(public_path('storage/files'), $fileName);
            }

            $data = $request->except('file');
            $data['file'] = $fileName;

            $campaign = Campaign::create($data);

            return response()->json(["message" => "Image Uploaded Successfully"]); 
        } 
        elseif ($action === 'retrieve') 
        {
            $filename = $request->query('filename'); 
            $filePath = public_path('storage/files/' . $filename);

            if (!file_exists($filePath)) 
            {
                return response()->json(['message' => 'File not found'], 404);
            }
            return Response::file($filePath);
        } 
        else 
        {
            return response()->json(['message' => 'Invalid action'], 400);
        }
    }
}