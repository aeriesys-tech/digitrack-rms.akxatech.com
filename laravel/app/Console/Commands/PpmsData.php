<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PpmsData extends Command
{
    protected $signature = 'fetch:PpmsData';

    protected $description = 'Fetch data from Oracle view ispat.VW_LF_PARA';

    public function handle()
    {
        $data = DB::connection('oracle')->select("SELECT * FROM ispat.VW_LF_PARA");

        $this->info("Fetched data from ispat.VW_LF_PARA:");

        foreach ($data as $row) {
            $this->info(json_encode($row));
        }
    }
}
