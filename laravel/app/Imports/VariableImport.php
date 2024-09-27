<?php

namespace App\Imports;

use App\Models\Variable;
use App\Models\VariableAssetType;
Use App\Models\VariableAttributeValue;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Auth;

class VariableImport implements ToCollection, WithHeadingRow
{
    public function collection(Collection $rows)
    {
        $userPlantId = Auth::user()->plant_id;

        foreach ($rows as $row) 
        {
            $data = [
                'variable_code' => $row['variable_code'],
                'variable_name' => $row['variable_name'],
                'variable_type_id' => $row['variable_type_id'],
                'plant_id' => $userPlantId,
            ];

            $variable = Variable::create($data);

            if (isset($row['asset_types'])) 
            {
                $assetTypes = explode(',', $row['asset_types']);
                foreach ($assetTypes as $asset_type_id) 
                {
                    VariableAssetType::create([
                        'variable_id' => $variable->variable_id,
                        'asset_type_id' => trim($asset_type_id),
                    ]);
                }
            }

            if (isset($row['variable_attributes'])) 
            {
                $variableAttributes = json_decode($row['variable_attributes'], true); 

                foreach ($variableAttributes as $attribute) 
                {
                    VariableAttributeValue::create([
                        'variable_id' => $variable->variable_id,
                        'variable_attribute_id' => $attribute['variable_attribute_id'],
                        'field_value' => $attribute['variable_attribute_value']['field_value'] ?? '',
                    ]);
                }
            }
        }
    }
}
