<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Section;
use App\Http\Resources\SectionResource;

class SectionController extends Controller
{
    public function paginateSections(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = Section::query();

        if(isset($request->section_code))
        {
            $query->where('section_code',$request->section_code);
        }
        if(isset($request->section_name))
        {
            $query->where('section_name',$request->section_name);
        }
        
        if($request->search!='')
        {
            $query->where('section_code', 'like', "%$request->search%")
                ->orWhere('section_name', 'like', "$request->search%");
        }
        $section = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return SectionResource::collection($section);
    }

    public function getSections()
    {
        $section = Section::all();
        return SectionResource::collection($section);
    }

    public function addSection(Request $request)
    {
        $data = $request->validate([
            'section_code' => 'required|string|unique:sections,section_code',
            'section_name' => 'required|string|unique:sections,section_name'
        ]);
        
        $section = Section::create($data);
        return response()->json(["message" => "Section Created Successfully"]);  
    }  

    public function getSection(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,section_id'
        ]);

        $section = Section::where('section_id',$request->section_id)->first();
        return new SectionResource($section);
    }

    public function updateSection(Request $request)
    {
        $data = $request->validate([
            'section_id' => 'required|exists:sections,section_id',
            'section_code' => 'required|unique:sections,section_code,'.$request->section_id.',section_id',
            'section_name' => 'required|unique:sections,section_name,'.$request->section_id.',section_id'
        ]);

        $section = Section::where('section_id', $request->section_id)->first();
        $section->update($data);
        return response()->json(["message" => "Cluster Updated Successfully"]);  
    }

    public function deleteSection(Request $request)
    {
        $request->validate([
            'section_id' => 'required|exists:sections,section_id'
        ]);
        $section = Section::withTrashed()->where('section_id', $request->section_id)->first();

        if($section->trashed())
        {
            $section->restore();
            return response()->json([
                "message" =>"Section Activated Successfully"
            ],200);
        }
        else
        {
            $section->delete();
            return response()->json([
                "message" =>"Section Deactivated Successfully"
            ], 200);
        }
    }
}