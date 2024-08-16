<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cluster;
use App\Http\Resources\ClusterResource;

class ClusterController extends Controller
{
    public function paginateClusters(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $query = Cluster::query();

        if(isset($request->cluster_code))
        {
            $query->where('cluster_code',$request->cluster_code);
        }
        if(isset($request->cluster_name))
        {
            $query->where('cluster_name',$request->cluster_name);
        }
        
        if($request->search!='')
        {
            $query->where('cluster_code', 'like', "%$request->search%")
                ->orWhere('cluster_name', 'like', "$request->search%");
        }
        $cluster = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 
        return ClusterResource::collection($cluster);
    }

    public function getClusters()
    {
        $cluster = Cluster::all();
        return ClusterResource::collection($cluster);
    }
    
    public function addCluster(Request $request)
    {
        $data = $request->validate([
            'cluster_code' => 'required|string|unique:clusters,cluster_code',
            'cluster_name' => 'required|string|unique:clusters,cluster_name'
        ]);

        $cluster = Cluster::create($data);
        return response()->json(["message" => "Cluster Created Successfully"]);     
    }

    public function getCluster(Request $request)
    {
        $request->validate([
            'cluster_id' => 'required|exists:clusters,cluster_id'
        ]);

        $cluster = Cluster::findOrFail($request->cluster_id);
        return new ClusterResource($cluster);
    } 

    public function updateCluster(Request $request)
    {
        $data = $request->validate([
            'cluster_id' => 'required|exists:clusters,cluster_id',
            'cluster_code' => 'required|string|unique:clusters,cluster_code,'.$request->cluster_id.',cluster_id',
            'cluster_name' => 'required|string|unique:clusters,cluster_name,'.$request->cluster_id.',cluster_id'
        ]);

        $cluster = Cluster::where('cluster_id', $request->cluster_id)->first();
        $cluster->update($data);
        return response()->json(["message" => "Cluster Updated Successfully"]);  
    }

    public function deleteCluster(Request $request)
    {
        $request->validate([
            'cluster_id' => 'required|exists:clusters,cluster_id'
        ]);
        $cluster = Cluster::withTrashed()->where('cluster_id', $request->cluster_id)->first();
        if($cluster->trashed()) 
        {
            $cluster->restore();
            return response()->json([
                "message" =>"Cluster Activated successfully"
            ],200);
        } 
        else 
        {
            $cluster->delete();
            return response()->json([
                "message" =>"Cluster Deactivated successfully"
            ], 200); 
        }
    }
}
