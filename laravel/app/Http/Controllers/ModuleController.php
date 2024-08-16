<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Module;
use App\Http\Resources\ModuleResource;

class ModuleController extends Controller
{
    public function paginateModules(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required|numeric',
            'keyword' => 'required'
        ]);

        $query = Module::query();
        if(isset($request->module_name))
        {
            $query->where('module_name',$request->module_name);
        }
        if($request->search!='')
        {
            $query->where('module_name', 'like', "%$request->search%");
        }
        $modules = $query->orderBy($request->keyword,$request->order_by)->paginate($request->per_page); 
        return ModuleResource::collection($modules);
    }

    public function getModules()
    {
        $modules = Module::all();
        return ModuleResource::collection($modules);
    }

    public function addModule(Request $request)
    {
        $data = $request->validate([
            'module_name' => 'required',
            'description' => 'sometimes|nullable'
        ]);

        $module = Module::create($data);
        return response()->json(["message" => "Module Created Successfully"]);
    }

    public function getModule(Request $request)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,module_id'
        ]);

        $module = Module::where('module_id', $request->module_id)->first();
        return new ModuleResource($module);
    }

    public function updateModule(Request $request)
    {
        $data = $request->validate([
            'module_name' => 'required',
            'description' => 'sometimes|nullable'
        ]);

        $module = Module::where('module_id', $request->module_id)->first();
        $module->update($data);
        return response()->json(["message" => "Module Updated Successfully"]);
    }

    public function deleteModule(Request $request)
    {
        $request->validate([
            'module_id' => 'required|exists:modules,module_id'
        ]);

        Module::where('module_id', $request->module_id)->delete();
        return response()->json([
            "message" => 'Module Deleted Successfully'
        ]);
    }
}
