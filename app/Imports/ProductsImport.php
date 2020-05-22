<?php

namespace App\Imports;

use App\Product;
use Debugbar;
use App\Clients\ZkabelClient;
use App\Project;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToCollection, WithHeadingRow
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function collection(Collection $rows)
    {
        $cart = array();
        // DebugBar::info($rows);
        $client = ZkabelClient::getInstance();

        $tmp_article = null;
        $tmp_row = null;

        foreach ($rows as $row) {
            $row['kolichestvo'] = floatval(preg_replace("/[^-0-9\.]/", "", $row['kolichestvo']));
            $row['tsena_s_nds'] = floatval(preg_replace("/[^-0-9\.]/", "", $row['tsena_s_nds']));

            if (!strcmp($row['artikul'], $tmp_article)) {
                $tmp_row['kolichestvo'] = floatval(preg_replace("/[^-0-9\.]/", "", $tmp_row['kolichestvo'])) + floatval(preg_replace("/[^-0-9\.]/", "", $row['kolichestvo']));
            } else {
                array_push($cart, $tmp_row);
                $tmp_article = $row['artikul'];
                $tmp_row = $row;
            }
        }
        array_push($cart, $tmp_row);
        array_shift($cart);
        // DebugBar::info($cart);

        $project = Project::find($this->id);
        $res = array();

        foreach ($cart as $row) {
            $article = $row['artikul'];
            $response = $client->get('catalog/findItemByArticle/?q=' . $article);
            $item = json_decode($response->getBody());

            if (empty($item->result)) {
                $product = Product::firstOrCreate(
                    ['article' => $row['artikul']],
                    ['name' => $row['tovary']]
                );
            } else {
                $item = array_shift($item->result);
                $product = Product::firstOrCreate(
                    ['article' => $item->article],
                    ['name' => $item->name, 'code' => $item->id]
                );
            }
            $res[$product->id] = [
                'count' => $row['kolichestvo'],
                'price' => $row['tsena_s_nds']
            ];
        }
        // DebugBar::info($res);
        $project->products()->sync($res);
    }
}
