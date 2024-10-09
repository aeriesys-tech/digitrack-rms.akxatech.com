<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Plant;
use App\Http\Resources\PlantResource;

class PlantController extends Controller
{
    public function paginatePlants(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = Plant::query();

        if(isset($request->plant_code))
        {
            $query->where('plant_code',$request->plant_code);
        }
        if(isset($request->plant_name))
        {
            $query->where('plant_name',$request->plant_name);
        }
        
        if($request->search!='')
        {
            $query->where('plant_code', 'like', "%$request->search%")
                ->orWhere('plant_name', 'like', "$request->search%")
                ->orWherehas('Area', function($quer) use($request){
                    $quer->where('area_name', 'like', "%$request->search%");
                });
        }

        if ($request->keyword == 'area_name') {
            $query->join('areas', 'plants.area_id', '=', 'areas.area_id')->select('plants.*') 
                  ->orderBy('areas.area_name', $request->order_by);
        } else {
            $query->orderBy($request->keyword, $request->order_by);
        }
        
        $plant = $query->withTrashed()->paginate($request->per_page); 
        return PlantResource::collection($plant);
    }

    public function getPlants()
    {
        $plant = Plant::all();
        return PlantResource::collection($plant);
    }

    public function addPlant(Request $request)
    {
        $data = $request->validate([
            'plant_code' => 'required|string|unique:plants,plant_code',
            'plant_name' => 'required|string|unique:plants,plant_name',
            'area_id' => 'required|exists:areas,area_id',
            'latitude' => 'nullable|sometimes',
            'longitude' => 'nullable|sometimes',
            'radius' => 'nullable|sometimes'
        ],[
            'plant_code.required' => 'shop code is required',
            'plant_code.unique' => 'shop code is already taken',
            'plant_name.required' => 'shop name is required',
            'plant_name.unique' => 'shop name is already taken'
        ]);
        
        $plant = Plant::create($data);
        return response()->json(["message" => "Shop Created Successfully"]);       
    }

    public function getPlant(Request $request)
    {
        $request->validate([
            'plant_id' => 'required|exists:plants,plant_id'
        ]);

        $plant = Plant::where('plant_id',$request->plant_id)->first();
        return new PlantResource($plant);
    }

    public function updatePlant(Request $request)
    {
        $data = $request->validate([
            'plant_id' => 'required|exists:plants,plant_id',
            'plant_code' => 'required|string|unique:plants,plant_code,'.$request->plant_id.',plant_id',
            'plant_name' => 'required|string|unique:plants,plant_name,'.$request->plant_id.',plant_id',
            'area_id' => 'required|exists:areas,area_id',
            'latitude' => 'nullable|sometimes',
            'longitude' => 'nullable|sometimes',
            'radius' => 'nullable|sometimes'
        ],[
            'plant_code.required' => 'shop code is required',
            'plant_name.required' => 'shop name is required',
            'plant_code.unique' => 'shop code is already taken',
            'plant_name.unique' => 'shop name is already taken'
        ]);

        $plant = Plant::where('plant_id', $request->plant_id)->first();
        $plant->update($data);
        return response()->json(["message" => "Shop Updated Successfully"]);  
    }
    
    public function deletePlant(Request $request)
    {
        $request->validate([
            'plant_id' => 'required|exists:plants,plant_id'
        ]);
        $plant = Plant::withTrashed()->where('plant_id', $request->plant_id)->first();

        if($plant->trashed())
        {
            $plant->restore();
            return response()->json([
                "message" =>"Shop Activated successfully"
            ],200);
        }
        else
        {
            $plant->delete();
            return response()->json([
                "message" =>"Shop Deactivated successfully"
            ], 200);
        }
    }
}
