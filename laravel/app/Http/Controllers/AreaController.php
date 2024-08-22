<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Area;
use App\Http\Resources\AreaResource;

class AreaController extends Controller
{
    public function paginateAreas(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = Area::query();

        if(isset($request->area_code))
        {
            $query->where('area_code',$request->area_code);
        }
        if(isset($request->area_name))
        {
            $query->where('area_name',$request->area_name);
        }
        
        if($request->search!='')
        {
            $query->where('area_code', 'like', "%$request->search%")
                ->orWhere('area_name', 'like', "$request->search%");
        }
        $area = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return AreaResource::collection($area);
    }

    public function getAreas()
    {
        $area = Area::all();
        return AreaResource::collection($area);
    }
    
    public function addArea(Request $request)
    {
        $data = $request->validate([
            'area_code' => 'required|string|unique:areas,area_code',
            'area_name' => 'required|string|unique:areas,area_name'
        ]);

        $area = Area::create($data);
        return response()->json(["message" => "Area Created Successfully"]);     
    }

    public function getArea(Request $request)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,area_id'
        ]);

        $area = Area::findOrFail($request->area_id);
        return new AreaResource($area);
    } 

    public function updateArea(Request $request)
    {
        $data = $request->validate([
            'area_id' => 'required|exists:areas,area_id',
            'area_code' => 'required|string|unique:areas,area_code,'.$request->area_id.',area_id',
            'area_name' => 'required|string|unique:areas,area_name,'.$request->area_id.',area_id'
        ]);

        $area = Area::where('area_id', $request->area_id)->first();
        $area->update($data);
        return response()->json(["message" => "Area Updated Successfully"]);  
    }

    public function deleteArea(Request $request)
    {
        $request->validate([
            'area_id' => 'required|exists:areas,area_id'
        ]);
        $area = Area::withTrashed()->where('area_id', $request->area_id)->first();
        if($area->trashed()) 
        {
            $area->restore();
            return response()->json([
                "message" =>"Area Activated successfully"
            ],200);
        } 
        else 
        {
            $area->delete();
            return response()->json([
                "message" =>"Area Deactivated successfully"
            ], 200); 
        }
    }
}
