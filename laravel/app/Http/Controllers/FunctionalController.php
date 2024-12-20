<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Functional;
use App\Http\Resources\FunctionalResource;

class FunctionalController extends Controller
{
    public function paginateFunctionals(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = Functional::query();

        if(isset($request->functional_code))
        {
            $query->where('functional_code',$request->functional_code);
        }
        if(isset($request->functional_name))
        {
            $query->where('functional_name',$request->functional_name);
        }
        
        if($request->search!='')
        {
            $query->where('functional_code', 'like', "%$request->search%")
                ->orWhere('functional_name', 'like', "$request->search%");
        }
        $functional = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return FunctionalResource::collection($functional);
    }

    public function getFunctionals()
    {
        $functional = Functional::all();
        return FunctionalResource::collection($functional);
    }

    public function addFunctional(Request $request)
    {
        $data = $request->validate([
            'functional_code' => 'required|string|unique:functionals,functional_code',
            'functional_name' => 'required|string|unique:functionals,functional_name'
        ]);
        
        $functional = Functional::create($data);
        return response()->json(["message" => "Functional Created Successfully"]);
    } 
    
    public function getFunctional(Request $request)
    {
        $request->validate([
            'functional_id' => 'required|exists:functionals,functional_id'
        ]);

        $functional = Functional::where('functional_id',$request->functional_id)->first();
        return new FunctionalResource($functional);
    }

    public function updateFunctional(Request $request)
    {
        $data = $request->validate([
            'functional_id' => 'required|exists:functionals,functional_id',
            'functional_code' => 'required|string|unique:functionals,functional_code,'.$request->functional_id.',functional_id',
            'functional_name' => 'required|string|unique:functionals,functional_name,'.$request->functional_id.',functional_id'
        ]);

        $functional = Functional::where('functional_id', $request->functional_id)->first();
        $functional->update($data);
        return response()->json(["message" => "Functional Updated Successfully"]);  
    }

    public function deleteFunctional(Request $request)
    {
        $request->validate([
            'functional_id' => 'required|exists:functionals,functional_id'
        ]);
        $functional = Functional::withTrashed()->where('functional_id', $request->functional_id)->first();

        if($functional->trashed())
        {
            $functional->restore();
            return response()->json([
                "message" =>"Functional Activated Successfully"
            ],200);
        }
        else
        {
            $functional->delete();
            return response()->json([
                "message" =>"Functional Deactivated Successfully"
            ], 200);
        }
    }
}
