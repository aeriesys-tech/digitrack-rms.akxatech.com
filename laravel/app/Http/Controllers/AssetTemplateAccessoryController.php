<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\AssetTemplateAccessory;
use App\Models\AccessoryType;
use App\Models\TemplateZone;
use App\Models\AssetTemplate;
use App\Http\Resources\AccessoryTypeResource;
use App\Http\Resources\AssetTemplateAccessoryResource;

class AssetTemplateAccessoryController extends Controller
{
    public function paginateAssetTemplateAccessories(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = AssetTemplateAccessory::query();

        if(isset($request->plant_id))
        {
            $query->where('plant_id',$request->plant_id);
        }
        if(isset($request->accessory_id))
        {
            $query->where('accessory_id',$request->accessory_id);
        }
        if(isset($request->asset_template_id))
        {
            $query->where('asset_template_id',$request->asset_template_id);
        }
        
        if($request->search!='')
        {
            $query->wherehas('Plant', function($query) use($request){
                $query->where('plant_name', 'like', "$request->search%");
            })->orWherehas('AssetTemplate', function($query) use($request){
                $query->where('template_name', 'like', "$request->search%");
            })->orWherehas('Accessory', function($query) use($request){
                $query->where('accessory_name', 'like', "$request->search%");
            });
        }
        $asset_accessory = $query->orderBy($request->keyword,$request->order_by)->paginate($request->per_page); 

        //Accessories
        $accessory_type = AccessoryType::all();

        return response()->json([
            'paginate_accessories' => AssetTemplateAccessoryResource::collection($asset_accessory),
            'meta' => [
                'current_page' => $asset_accessory->currentPage(),
                'last_page' => $asset_accessory->lastPage(),
                'per_page' => $asset_accessory->perPage(),
                'total' => $asset_accessory->total(),
            ],
            'accessory_types' => AccessoryTypeResource::collection($accessory_type)
        ]);
    }

    public function addAssetTemplateAccessory(Request $request)
    {
        if ($request->has('template_zone_id') && is_string($request->input('template_zone_id'))) {
            $request->merge([
                'template_zone_id' => json_decode($request->input('template_zone_id'), true)
            ]);
        }
        $hasAssetZones = TemplateZone::where('asset_template_id', $request->asset_template_id)->exists();

        $data = $request->validate([
            'asset_template_id' => 'required|exists:asset_templates,asset_template_id',
            'accessory_type_id' => 'required|exists:accessory_types,accessory_type_id',
            'accessory_template_zones' => $hasAssetZones ? 'required' : 'nullable',
            'template_zone_id.*' => 'nullable|exists:template_zones,template_zone_id',
            'accessory_name' => 'required',
            'attachment' => 'nullable|file'
        ]);
        $asset = AssetTemplate::where('asset_template_id', $request->asset_template_id)->first();
        
        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;

        if ($request->hasFile('attachment')) {
            $attachment = time() . '.' . $request->file('attachment')->getClientOriginalExtension();
            $request->file('attachment')->move(public_path('storage/assetAttachments'), $attachment);
            $data['attachment'] = $attachment;
        }

        $createdAccessories = [];

        $accessoryZones = (array) $data['accessory_template_zones'];

        if (!empty($accessoryZones)) 
        {
            foreach ($accessoryZones as $zoneId) 
            {              
                if (is_null($zoneId) || $zoneId == 0) {
                    continue;
                }
    
                $accessoryData = $data;
                $accessoryData['template_zone_id'] = $zoneId;
    
                $assetAccessory = AssetTemplateAccessory::create($accessoryData);
                $createdAccessories[] = new AssetTemplateAccessoryResource($assetAccessory);
            }
        } 
        else {
            $accessoryData = $data;
            $accessoryData['template_zone_id'] = null;
    
            $assetAccessory = AssetTemplateAccessory::create($accessoryData);
            $createdAccessories[] = new AssetTemplateAccessoryResource($assetAccessory);
        }
        return response()->json($createdAccessories, 201);
    }

    public function deleteAssetTemplateAccessory(Request $request)
    {
        $request->validate([
            'asset_template_accessory_id' => 'required|exists:asset_template_accessories,asset_template_accessory_id'
        ]);

        $attachment = AssetTemplateAccessory::where('asset_template_accessory_id', $request->asset_template_accessory_id)->delete();
        
        // if ($attachment && $attachment->attachment) {
        //     $filePath = public_path('storage/assetAttachments/' . $attachment->attachment);
        //     if (file_exists($filePath)) {
        //         unlink($filePath); 
        //     }
        // }

        // $attachment->delete();

        return response()->json([
            'message' => "Asset Template Accessory Deleted Successfully"
        ]);
    }
}
