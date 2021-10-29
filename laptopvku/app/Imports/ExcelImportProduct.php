<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\ToModel;

class ExcelImportProduct implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Product([
            'category_id' => $row[0],
            'brand_id' => $row[1],
            'product_name' => $row[2],
            'product_quantity' => $row[3],
            'product_sold' => $row[4],
            'product_desc' => $row[5],
            'product_content' => $row[6],
            'product_price' => $row[7],
            'product_image' => $row[8],
            'product_status' => $row[9],
        ]);
    }
}
