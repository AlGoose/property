<?php

namespace App\Imports;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class ProductsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        \DebugBar::info($rows);
        \DebugBar::info($rows[31]);

        // foreach ($rows as $row) 
        // {
            
        // }
    }
}
