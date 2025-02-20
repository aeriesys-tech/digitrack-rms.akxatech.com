<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Log;
// use App\Models\PPMSData;

class PpmsData extends Command
{
    protected $signature = 'fetch:PpmsData';

    protected $description = 'Fetch data from Oracle view ispat.VW_LF_PARA';

    public function handle()
    {
        //max inserted_date
        $latestInsertDate = DB::table('ppms_datas')->max('insert_date');

        // $oracleData = DB::connection('oracle')->select("SELECT * FROM ispat.VW_LF_PARA");
        
        $oracleData = DB::connection('sec_pgsql')->select("SELECT * FROM ppms_datas");

        if (empty($oracleData)) 
        {
            Log::create([
                'log_type' => 'PPMS',
                'status' => false,
                'message' => 'No data found in ispat.VW_LF_PARA.',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $this->warn("No data found in ispat.VW_LF_PARA.");
            return;
        }
        else 
        {
            //max-InsertDate 
            if ($latestInsertDate) {
                $oracleData = array_filter($oracleData, function ($row) use ($latestInsertDate) {
                    return isset($row->INSERT_DATE) && $row->INSERT_DATE > $latestInsertDate;
                });
            }

            if (empty($oracleData)) 
            {
                Log::create([
                    'log_type' => 'PPMS',
                    'status' => false,
                    'message' => 'No new data found in ispat.VW_LF_PARA.',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->info("No new data to insert.");
                return;
            }
            else
            {
                $log = Log::create([
                    'log_type' => 'PPMS',
                    'status' => true,
                    'message' => 'Fetched data from ispat.VW_LF_PARA',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
    
                $this->info("Inserted log: $log");
        
                foreach ($oracleData as $row) 
                {
                    DB::table('ppms_datas')->insert([
                        'log_id' => $log->log_id,
                        'insert_date' => $row->INSERT_DATE ?? null,
                        'heat_no' => $row->HEAT_NO,
                        'grade' => $row->GRADE ?? null,
                        're_treat' => $row->RE_TREAT,
                        'holding_time' => $row->HOLDING_TIME ?? null,
                        'processing_time' => $row->PROCESSING_TIME ?? null,
                        'ladle_number' => $row->LADLE_NUMBER ?? null,
                        'o2_ppm' => $row->O2_PPM ?? null,
                        'oxygen_after_celoxa' => $row->OXYGEN_AFTER_CELOXA ?? null,
                        'heat_size' => $row->HEAT_SIZE ?? null,
                        'al2_addition_bar' => $row->AL2_ADDITION_BAR ?? null,
                        'al2_addition_coil' => $row->AL2_ADDITION_COIL ?? null,
                        'tapping_temperature' => $row->TAPPING_TEMPERATURE ?? null,
                        'lf_in_sulphur' => $row->LF_IN_SULPHUR ?? null,
                        'lf_in_temperature' => $row->LF_IN_TEMPERATURE ?? null,
                        'lime_consumption' => $row->LIME_CONSUMPTION ?? null,
                        'tundish_temperature' => $row->TUNDISH_TEMPERATURE ?? null,
                        'super_heat_avg' => $row->SUPER_HEAT_AVG ?? null,
                        'super_heat_max' => $row->SUPER_HEAT_MAX ?? null,
                        'lifting_temperature' => $row->LIFTING_TEMPERATURE ?? null,
                        'lf_slag_report' => $row->LF_SLAG_REPORT ?? null,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                $this->info("Inserted " . count($oracleData) . " rows into ppms_datas.");
            }
        }
    }
}
