<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\AssetCheckResource;
use App\Http\Resources\UserAssetCheckResource;
use App\Http\Resources\UserAssetCheckDeviationResource;
use App\Models\AssetCheck;
use App\Models\UserCheck;
use App\Models\Check;
use App\Models\AssetZone;
use App\Models\Asset;
use App\Models\UserAssetCheck;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CheckResource;
use App\Http\Resources\AssetZoneResource;

class AssetCheckController extends Controller
{
    public function paginateAssetChecks(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required',
            'asset_type_id' => 'required|exists:asset_type,asset_type_id',
            'department_id' => 'nullable|exists:departments,department_id'
        ]);

        $query = AssetCheck::query();

        if(isset($request->plant_id))
        {
            $query->where('plant_id',$request->plant_id);
        }
        if(isset($request->check_id))
        {
            $query->where('check_id',$request->check_id);
        }
        if(isset($request->asset_id))
        {
            $query->where('asset_id',$request->asset_id);
        }
        
        if($request->search!='')
        {
            $query->wherehas('Plant', function($query) use($request){
                $query->where('plant_name', 'like', "$request->search%");
            })->orwherehas('Check', function($query) use($request){
                $query->where('check_name', 'like', "$request->search%");
            })->orwherehas('Asset', function($query) use($request){
                $query->where('asset_name', 'like', "$request->search%");
            });
        }
        $asset_check = $query->orderBy($request->keyword,$request->order_by)->withTrashed()->paginate($request->per_page); 

        //DropDown AssetCheck
        // if(isset($request->department_id))
        // {
        //     $asset = Asset::where('department_id', $request->department_id)->where('asset_type_id', $request->asset_type_id)->first();
        //     if ($asset) 
        //     {
        //         $checks = Check::whereHas('CheckAssetTypes', function($que) use ($request) {
        //             $que->where('asset_type_id', $request->asset_type_id);
        //         })->where('department_id', $request->department_id)->get();
        //     } 
        // }
        // else {
            $checks = Check::whereHas('CheckAssetTypes', function($que) use ($request) {
                $que->where('asset_type_id', $request->asset_type_id);
            })->get();
        // }

        return response()->json([
            'paginate_checks' => AssetCheckResource::collection($asset_check),
            'meta' => [
                'current_page' => $asset_check->currentPage(),
                'last_page' => $asset_check->lastPage(),
                'per_page' => $asset_check->perPage(),
                'total' => $asset_check->total(),
            ],
            'checks' => CheckResource::collection($checks)
        ]);
    }

    public function addAssetCheck(Request $request)
    {
        $assetHasZones = AssetZone::where('asset_id', $request->asset_id)->exists();
        $checkdata = Check::where('check_id', $request->check_id)->first();
        $data = $request->validate([
            'check_id' => [
                'required',
                'exists:checks,check_id',
                function ($attribute, $value, $fail) use ($request, $assetHasZones) {
                    $exists = AssetCheck::where('check_id', $value)
                        ->where('asset_id', $request->asset_id)
                        ->where(function ($query) use ($request, $assetHasZones) {
                            if ($assetHasZones && $request->filled('check_asset_zones')) {
                                $query->whereIn('asset_zone_id', $request->check_asset_zones);
                            } else {
                                $query->whereNull('asset_zone_id');
                            }
                        })->exists();

                    if ($exists) {
                        if ($request->filled('check_asset_zones') && $assetHasZones) {
                            $fail('The combination of Check and Asset Zone already exists.');
                        } else {
                            $fail('The combination of Check and Asset already exists.');
                        }
                    }
                },
            ],
            'asset_id' => 'required|exists:assets,asset_id',
            'check_asset_zones' => [
                $assetHasZones ? 'required' : 'nullable', 
                'array',
            ],
            'asset_zones.*' => 'nullable|exists:asset_zones,asset_zone_id',
            'lcl' => 'nullable|required_if:field_type,Number',
            'ucl' => 'nullable|required_if:field_type,Number',
            'default_value' => [
                'nullable',
                'required_if:field_type,Select',
                function ($attribute, $value, $fail) use ($request, $checkdata) {
                    if ($request->field_type === 'Select' && !empty($checkdata->field_values)) {
                        $fieldValuesArray = array_map('strtolower', array_map('trim', explode(',', $checkdata->field_values)));
                        if (!in_array(strtolower($value), $fieldValuesArray)) {
                            $formattedOptions = implode(', ', array_map('ucwords', $fieldValuesArray));
                            $fail("The default value must be one of the option in field values: " . $formattedOptions);
                        }
                    }
                }
            ],
        ]);

        $asset = Asset::where('asset_id', $request->asset_id)->first();

        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;

        // $check = Check::where('check_id', $request->check_id)->first();

        // $data['lcl'] = $request->input('lcl', $check->lcl);
        // $data['ucl'] = $request->input('ucl', $check->ucl);
        // $data['default_value'] = $request->input('default_value', $check->default_value);

        $createdChecks = [];

        if (!empty($data['check_asset_zones'])) 
        {
            foreach ($data['check_asset_zones'] as $zoneId) 
            {              
                if (is_null($zoneId) || $zoneId == 0) 
                {
                    continue;
                }

                $checksData = $data;
                $checksData['asset_zone_id'] = $zoneId;

                $assetChecks = AssetCheck::create($checksData);
                $createdChecks[] = new AssetCheckResource($assetChecks);
            }
        } 
        else 
        {
            $checksData = $data;
            $checksData['asset_zone_id'] = null;

            $assetChecks = AssetCheck::create($checksData);
            $createdChecks[] = new AssetCheckResource($assetChecks);
        }
        return response()->json([$createdChecks, "message" => "AssetCheck Created Successfully"]);
    }

    public function getAssetCheck(Request $request)
    {
        $request->validate([
            'asset_check_id' => 'required|exists:asset_checks,asset_check_id'
        ]);

        $asset_check = AssetCheck::where('asset_check_id', $request->asset_check_id)->first();
        return new AssetCheckResource($asset_check);
    }

    public function getAssetChecks(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,asset_id'
        ]);
        $asset_check = AssetCheck::where('asset_id', $request->asset_id)->get();
        return AssetCheckResource::collection($asset_check);
    }

    public function getAssetRegisterChecks(Request $request)
    {
        $request->validate([
            'asset_id' => 'required|exists:assets,asset_id',
            'asset_zone_id' => 'nullable|exists:asset_zones,asset_zone_id',
            'department_id' => 'nullable|exists:departments,department_id'
        ]);
        $query = AssetCheck::query();

        if (isset($request->asset_zone_id)) 
        {
            $query->where('asset_zone_id', $request->asset_zone_id);
        }

        if (isset($request->department_id)) 
        {
            $query->whereHas('Check', function($que) use($request){
                $que->where('department_id', $request->department_id);
            });
        }
        $asset_check = $query->where('asset_id', $request->asset_id)->get();
        return AssetCheckResource::collection($asset_check);
    }

    public function updateAssetCheck(Request $request)
    {
        $asset_check = AssetCheck::where('asset_check_id', $request->asset_check_id)->first();
        $assetHasZones = AssetZone::where('asset_id', $request->asset_id)->exists();
        $checkdata = Check::where('check_id', $request->check_id)->first();
        $data = $request->validate([
            'asset_check_id' => 'required|exists:asset_checks,asset_check_id',
            'asset_id' => 'required|exists:assets,asset_id',
            'check_id' => [
                'required',
                'exists:checks,check_id',
                function ($attribute, $value, $fail) use ($request, $asset_check) 
                {
                    if ($value != $asset_check->check_id) {
                        $exists = AssetCheck::where('check_id', $value)
                            ->where('asset_id', $request->asset_id)
                            ->where(function ($query) use ($request) {
                                if ($request->filled('asset_zone_id')) {
                                    $query->where('asset_zone_id', $request->asset_zone_id);
                                } else {
                                    $query->whereNull('asset_zone_id');
                                }
                            })->where('asset_check_id', '!=', $request->asset_check_id)->exists();
    
                        if ($exists) {
                            $fail('The combination of Check, Asset, and Asset Zone already exists.');
                        }
                    }
                },
            ],
            'lcl' => 'nullable|required_if:field_type,Number',
            'ucl' => 'nullable|required_if:field_type,Number',
            'default_value' => [
                'nullable',
                'required_if:field_type,Select',
                function ($attribute, $value, $fail) use ($request, $checkdata) {
                    if ($request->field_type === 'Select' && !empty($checkdata->field_values)) {
                        $fieldValuesArray = array_map('strtolower', array_map('trim', explode(',', $checkdata->field_values)));
                        if (!in_array(strtolower($value), $fieldValuesArray)) {
                            $formattedOptions = implode(', ', array_map('ucwords', $fieldValuesArray));
                            $fail("The default value must be one of the option in field values: " . $formattedOptions);
                        }
                    }
                }
            ],
            'asset_zone_id' => [
                $assetHasZones ? 'required' : 'nullable',
            ],
        ]);

        $asset = Asset::where('asset_id', $request->asset_id)->first();

        $data['plant_id'] = $asset->plant_id;
        $data['area_id'] = $asset->area_id;

        $asset_check = AssetCheck::where('asset_check_id', $request->asset_check_id)->first();
        $asset_check->update($data);
        return response()->json([
            "message" => "AssetCheck Updated Successfully",
            new AssetCheckResource($asset_check)
        ]);    
    }

    public function deleteAssetCheck(Request $request)
    {
        $request->validate([
            'asset_check_id' => 'required|exists:asset_checks,asset_check_id'
        ]);

        $asset_check = AssetCheck::withTrashed()->where('asset_check_id', $request->asset_check_id)->first();

        if($asset_check->trashed())
        {
            $asset_check->restore();
            return response()->json([
                "message" =>"AssetCheck Activated successfully"
            ],200);
        }
        else
        {
            $asset_check->delete();
            return response()->json([
                "message" =>"AssetCheck Deactivated successfully"
            ], 200); 
        }
    }

    public function forceDeleteAssetCheck(Request $request)
    {
        $request->validate([
            'asset_check_id' => 'required|exists:asset_checks,asset_check_id'
        ]);

        try {
            $asset_check = AssetCheck::where('asset_check_id', $request->asset_check_id)->first();
            $isAssociated = UserCheck::whereHas('UserAssetCheck', function($que) use($asset_check){
                $que->where('asset_check_id', $asset_check->asset_check_id);
            })->where('asset_zone_id', $asset_check->asset_zone_id)->where('asset_id', $asset_check->asset_id)->exists();

            if ($isAssociated) {
                return response()->json([
                    "message" => "Cannot delete AssetCheck because it is associated with other records."
                ], 400);
            }

            $asset_check = AssetCheck::where('asset_check_id', $request->asset_check_id)->forceDelete();

            return response()->json([
                "message" => "AssetCheck deleted successfully"
            ], 200);

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                "error" => "Cannot delete AssetCheck due to a database error."
            ], 400);

        } catch (\Exception $e) {
            return response()->json([
                "error" => "An unexpected error occurred. Please try again."
            ], 500);
        }
    }

    // public function deviationAssetChecks(Request $request)
    // {
    //     $request->validate([
    //         'order_by' => 'required',
    //         'per_page' => 'required',
    //         'keyword' => 'required'
    //     ]);

    //     // $authPlantId = Auth::User()->plant_id;
    //     $query = UserAssetCheck::query()->where('remark_status', false);

        // if (isset($request->department_id)) 
        // {
        //     $query->whereHas('UserCheck', function ($qu) use ($request) {
        //         $qu->whereHas('Asset', function ($que) use ($request) {
        //                 $que->whereHas('AssetDepartment', function($quer) use($request){
        //                     $quer->where('department_id', $request->department_id);
        //                 });
        //         });
        //     });
        // }     

        // if (isset($request->asset_id)) 
        // {
        //     $query->whereHas('UserCheck', function ($qu) use ($request) {
        //         $qu->where('asset_id', $request->asset_id);
        //     });
        // }     

        // if($request->search!='')
        // {
        //     $query->where(function($query) use ($request) {
        //         $query->where('default_value', 'like', "%$request->search%")->orWhere('value', 'like', "%$request->search%")
        //             ->orwhere('field_type', 'like', "%$request->search%")
        //             ->orWhereHas('Check', function($que) use ($request) {
        //             $que->where('field_name', 'like', "%$request->search%");
        //         })->orWhereHas('UserCheck', function($qu) use ($request) {
        //             $qu->whereHas('Asset', function($que) use ($request) {
        //                 $que->where('asset_name', 'like', "%$request->search%");
        //             });
        //         })->orwhereHas('UserCheck', function($quer) use($request){
        //             $quer->whereHas('Asset', function($que) use($request){
        //                 $que->whereHas('AssetType', function($qu) use($request){
        //                     $qu->where('asset_type_name', 'like', "%$request->search%");
        //                 });
        //             });
        //         })->orwhereHas('UserCheck', function($que) use($request){
        //             $que->whereHas('Asset',function($q) use($request){
        //                 $q->whereHas('AssetDepartment', function($qu) use($request){
        //                     $qu->whereHas('Department', function($qr) use($request){
        //                         $qr->where('department_name', 'like', "%$request->search%");
        //                     });
        //                 });
        //             });
        //         })->orwhereHas('UserCheck', function($que) use($request){
        //             $que->where('reference_no', 'like', "%$request->search%");
        //         });
        //     });
        // }
        
    //     $query->where(function($q) 
    //     {
    //         $q->where('field_type', 'Number')
    //         ->where(function($subQuery) 
    //         {
    //             $subQuery->whereRaw('CAST(value AS DECIMAL) < CAST(lcl AS DECIMAL)')
    //                     ->orWhereRaw('CAST(value AS DECIMAL) > CAST(ucl AS DECIMAL)');
    //         });
    //     })->orWhere(function($q) use ($authPlantId) {
    //         $q->where('field_type', '!=', 'Number')
    //           ->whereHas('UserCheck', function ($subQuery) use ($authPlantId) {
    //               $subQuery->where('plant_id', $authPlantId);
    //           })
    //           ->whereColumn('default_value', '!=', 'value');
    //     });      
    
    //     // Sort by related table columns
    //     if ($request->keyword == 'field_name') {
    //         $query->whereHas('Check', function($q) use ($request) {
    //             $q->orderBy('field_name', $request->order_by);
    //         });
    //     }

    //     $asset_check = $query->orderBy($request->keyword,$request->order_by)->paginate($request->per_page); 
    //     return UserAssetCheckDeviationResource::collection($asset_check);
    // }

    public function deviationAssetChecks(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $authPlantId = Auth::User()->plant_id;
        $query = UserAssetCheck::query()->where('remark_status', false);
    
        $query->where(function ($query) {
            $query->where(function ($q) {
                $q->where('field_type', 'Number')
                ->where(function ($subQuery) {
                    $subQuery->whereRaw('CAST(value AS DOUBLE PRECISION) < lcl OR CAST(value AS DOUBLE PRECISION) > ucl');
                });
            })->orWhere(function ($q) {
                $q->where('field_type', 'Select')
                ->whereColumn('default_value', '!=', 'value');
            });
        });

        if (isset($request->department_id)) 
        {
            $query->whereHas('UserCheck', function ($qu) use ($request) {
                $qu->whereHas('Asset', function ($que) use ($request) {
                        $que->whereHas('AssetDepartment', function($quer) use($request){
                            $quer->where('department_id', $request->department_id);
                        });
                });
            });
        }   
        
        if (isset($request->asset_id)) 
        {
            $query->whereHas('UserCheck', function ($qu) use ($request) {
                $qu->where('asset_id', $request->asset_id);
            });
        }    
    
        if($request->search!='')
        {
            $query->where(function($query) use ($request) {
                $query->where('default_value', 'like', "%$request->search%")->orWhere('value', 'like', "%$request->search%")
                    ->orwhere('field_type', 'like', "%$request->search%")
                    ->orWhereHas('Check', function($que) use ($request) {
                    $que->where('field_name', 'like', "%$request->search%");
                })->orWhereHas('UserCheck', function($qu) use ($request) {
                    $qu->whereHas('Asset', function($que) use ($request) {
                        $que->where('asset_name', 'like', "%$request->search%");
                    });
                })->orwhereHas('UserCheck', function($quer) use($request){
                    $quer->whereHas('Asset', function($que) use($request){
                        $que->whereHas('AssetType', function($qu) use($request){
                            $qu->where('asset_type_name', 'like', "%$request->search%");
                        });
                    });
                })->orwhereHas('UserCheck', function($que) use($request){
                    $que->whereHas('Asset',function($q) use($request){
                        $q->whereHas('AssetDepartment', function($qu) use($request){
                            $qu->whereHas('Department', function($qr) use($request){
                                $qr->where('department_name', 'like', "%$request->search%");
                            });
                        });
                    });
                })->orwhereHas('UserCheck', function($que) use($request){
                    $que->where('reference_no', 'like', "%$request->search%");
                });
            });
        }
        $asset_check = $query->orderBy($request->keyword,$request->order_by)->paginate($request->per_page); 
        return UserAssetCheckDeviationResource::collection($asset_check);
    }

    public function completedDeviationChecks(Request $request)
    {
        $request->validate([
            'order_by' => 'required',
            'per_page' => 'required',
            'keyword' => 'required'
        ]);

        $authPlantId = Auth::User()->plant_id;
        $query = UserAssetCheck::query()->where('remark_status', true);
    
        $query->where(function ($query) {
            $query->where(function ($q) {
                $q->where('field_type', 'Number')
                ->where(function ($subQuery) {
                    $subQuery->whereRaw('CAST(value AS DOUBLE PRECISION) < lcl OR CAST(value AS DOUBLE PRECISION) > ucl');
                });
            })->orWhere(function ($q) {
                $q->where('field_type', 'Select')
                ->whereColumn('default_value', '!=', 'value');
            });
        });

        if (isset($request->department_id)) 
        {
            $query->whereHas('UserCheck', function ($qu) use ($request) {
                $qu->whereHas('Asset', function ($que) use ($request) {
                        $que->whereHas('AssetDepartment', function($quer) use($request){
                            $quer->where('department_id', $request->department_id);
                        });
                });
            });
        }   
        
        if (isset($request->asset_id)) 
        {
            $query->whereHas('UserCheck', function ($qu) use ($request) {
                $qu->where('asset_id', $request->asset_id);
            });
        }    
    
        if($request->search!='')
        {
            $query->where(function($query) use ($request) {
                $query->where('default_value', 'like', "%$request->search%")->orWhere('value', 'like', "%$request->search%")
                    ->orwhere('field_type', 'like', "%$request->search%")
                    ->orWhereHas('Check', function($que) use ($request) {
                    $que->where('field_name', 'like', "%$request->search%");
                })->orWhereHas('UserCheck', function($qu) use ($request) {
                    $qu->whereHas('Asset', function($que) use ($request) {
                        $que->where('asset_name', 'like', "%$request->search%");
                    });
                })->orwhereHas('UserCheck', function($quer) use($request){
                    $quer->whereHas('Asset', function($que) use($request){
                        $que->whereHas('AssetType', function($qu) use($request){
                            $qu->where('asset_type_name', 'like', "%$request->search%");
                        });
                    });
                })->orwhereHas('UserCheck', function($que) use($request){
                    $que->whereHas('Asset',function($q) use($request){
                        $q->whereHas('AssetDepartment', function($qu) use($request){
                            $qu->whereHas('Department', function($qr) use($request){
                                $qr->where('department_name', 'like', "%$request->search%");
                            });
                        });
                    });
                })->orwhereHas('UserCheck', function($que) use($request){
                    $que->where('reference_no', 'like', "%$request->search%");
                });
            });
        }
        $asset_check = $query->orderBy($request->keyword,$request->order_by)->paginate($request->per_page); 
        return UserAssetCheckDeviationResource::collection($asset_check);
    }
}
