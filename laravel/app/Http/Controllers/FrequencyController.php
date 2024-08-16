<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Frequency;
use App\Http\Resources\FrequencyResource;

class FrequencyController extends Controller
{
    public function paginateFrequencies(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = Frequency::query();

        if(isset($request->frequency_code))
        {
            $query->where('frequency_code',$request->frequency_code);
        }
        if(isset($request->frequency_name))
        {
            $query->where('frequency_name',$request->frequency_name);
        }
        
        if($request->search!='')
        {
            $query->where('frequency_code', 'like', "%$request->search%")
                 ->orWhere('frequency_name', 'like', "$request->search%");
        }
        $frequency = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return FrequencyResource::collection($frequency);
    }

    public function addFrequency(Request $request)
    {
        $data = $request->validate([
            'frequency_code' => 'required|unique:frequencies,frequency_code',
            'frequency_name' => 'required|unique:frequencies,frequency_name'
        ]);

        $frequency = Frequency::create($data);
        return response()->json([
            "message" => "Frequency Created Successfully"
        ]);
    }

    public function getFrequency(Request $request)
    {
        $request->validate([
            'frequency_id' => 'required|exists:frequencies,frequency_id'
        ]);

        $frequency = Frequency::where('frequency_id', $request->frequency_id)->first();
        return new FrequencyResource($frequency);
    }

    public function getFrequencies()
    {
        $frequencies = Frequency::all();
        return FrequencyResource::collection($frequencies);
    }

    public function updateFrequency(Request $request)
    {
        $data = $request->validate([
            'frequency_id' => 'required|exists:frequencies,frequency_id',
            'frequency_code' => 'required|string|unique:frequencies,frequency_code,'.$request->frequency_id.',frequency_id',
            'frequency_name' => 'required|string|unique:frequencies,frequency_name,'.$request->frequency_id.',frequency_id',
        ]);

        $frequency = Frequency::where('frequency_id', $request->frequency_id)->first();
        $frequency->update($data);
        return response()->json([
            "message" => "Frequency Updated Successfully"
        ]);
    }

    public function deleteFrequency(Request $request)
    {
        $request->validate([
            'frequency_id' => 'required|exists:frequencies,frequency_id'
        ]);
        $frequency = Frequency::withTrashed()->where('frequency_id', $request->frequency_id)->first();

        if($frequency->trashed())
        {
            $frequency->restore();
            return response()->json([
                "message" => "Frequency Activated successfully"
            ],200);
        }
        else
        {
            $frequency->delete();
            return response()->json([
                "message" => "Frequency Deactivated successfully"
            ], 200);
        }
    }
}
