<?php

require_once __DIR__ . '/vendor/autoload.php';

use Phpml\Dataset\CsvDataset;
use Phpml\Math\Distance\Chebyshev;

function getDataSetSeason($data, $season, $index) {
    $newArrayFiltered = array_filter($data, function ($item) use ($season) {
        return $item[2] == $season;
    });

    return array_map(function ($item) use ($index) {
        return $item[$index];
    }, $newArrayFiltered, []);
}

function chebyshevDistance($datasample_1, $datasample_2) {
    if (count($datasample_1) != count($datasample_2)) {
        return -1;
    }

    $countSample = count($datasample_1);
    
    $max = -1;

    foreach (range(0, $countSample - 1) as $i) {

        if ( ! isset($datasample_1[$i])) {
            echo '<pre>';
            echo 'Data Sample 1 : ' . $i;
            echo var_dump($datasample_1[$i]);
            echo '</pre>';
        }

        if ( ! isset($datasample_2[$i])) {
            echo '<pre>';
            echo 'Data Sample 2 ' . $i;
            echo var_dump($datasample_2[$i]);
            echo '</pre>';
        }

        $subtractResult = abs($datasample_1[$i] - $datasample_2[$i]);
        
        $max = $subtractResult > $max ? $subtractResult : $max;
    }

    return $max;
}

$chebyshev = new Chebyshev();

$datasets = new CsvDataset('./datasets/day.csv', 15, true);

$datasetColumnNames = $datasets->getColumnNames();
$datasetSamples = $datasets->getSamples();

$datasetSeason1 = getDataSetSeason($datasetSamples, 1, 14);
$datasetSeason2 = getDataSetSeason($datasetSamples, 2, 14);
$datasetSeason3 = getDataSetSeason($datasetSamples, 3, 14);
$datasetSeason4 = getDataSetSeason($datasetSamples, 4, 14);

$countMinimunRegistered = min(
    count($datasetSeason1),
    count($datasetSeason2),
    count($datasetSeason3),
    count($datasetSeason4)
);

$datasetSeason1 = array_slice($datasetSeason1, 0, count($datasetSeason1) - (count($datasetSeason1) - $countMinimunRegistered));
$datasetSeason2 = array_slice($datasetSeason2, 0, count($datasetSeason2) - (count($datasetSeason2) - $countMinimunRegistered));
$datasetSeason3 = array_slice($datasetSeason3, 0, count($datasetSeason3) - (count($datasetSeason3) - $countMinimunRegistered));
$datasetSeason4 = array_slice($datasetSeason4, 0, count($datasetSeason4) - (count($datasetSeason4) - $countMinimunRegistered));

echo 'Chebyshev Distance <br>';
echo 'Data Pegguna Peminjaman Sepeda <br>';

echo '<br>';

echo 'Jumlah Dari Musim Semi dan Musim Panas   : ' . chebyshevDistance($datasetSeason1, $datasetSeason2)  . ' - ' . $chebyshev->distance($datasetSeason1, $datasetSeason2) . ' <br>';
echo 'Jumlah Dari Musim Semi dan Musim Gugur   : ' . chebyshevDistance($datasetSeason1, $datasetSeason3)  . ' - ' . $chebyshev->distance($datasetSeason1, $datasetSeason3) . ' <br>';
echo 'Jumlah Dari Musim Semi dan Musim Dingin  : ' . chebyshevDistance($datasetSeason1, $datasetSeason4)  . ' - ' . $chebyshev->distance($datasetSeason1, $datasetSeason4) . ' <br>';

echo '<br>';

echo 'Jumlah Dari Musim Panas dan Musim Semi   : ' . chebyshevDistance($datasetSeason2, $datasetSeason1)  . ' - ' . $chebyshev->distance($datasetSeason2, $datasetSeason1) . ' <br>';
echo 'Jumlah Dari Musim Panas dan Musim Gugur  : ' . chebyshevDistance($datasetSeason2, $datasetSeason3)  . ' - ' . $chebyshev->distance($datasetSeason2, $datasetSeason3) . ' <br>';
echo 'Jumlah Dari Musim Panas dan Musim Dingin : ' . chebyshevDistance($datasetSeason2, $datasetSeason4)  . ' - ' . $chebyshev->distance($datasetSeason2, $datasetSeason4) . ' <br>';

echo '<br>';

echo 'Jumlah Dari Musim Gugur dan Musim Semi   : ' . chebyshevDistance($datasetSeason3, $datasetSeason1)  . ' - ' . $chebyshev->distance($datasetSeason3, $datasetSeason1) . ' <br>';
echo 'Jumlah Dari Musim Gugur dan Musim Panas  : ' . chebyshevDistance($datasetSeason3, $datasetSeason2)  . ' - ' . $chebyshev->distance($datasetSeason3, $datasetSeason2) . ' <br>';
echo 'Jumlah Dari Musim Gugur dan Musim Dingin : ' . chebyshevDistance($datasetSeason3, $datasetSeason4)  . ' - ' . $chebyshev->distance($datasetSeason3, $datasetSeason4) . ' <br>';

echo '<br>';

echo 'Jumlah Dari Musim Dingin dan Musim Semi  : ' . chebyshevDistance($datasetSeason4, $datasetSeason1)  . ' - ' . $chebyshev->distance($datasetSeason4, $datasetSeason1) . ' <br>';
echo 'Jumlah Dari Musim Dingin dan Musim Panas : ' . chebyshevDistance($datasetSeason4, $datasetSeason2)  . ' - ' . $chebyshev->distance($datasetSeason4, $datasetSeason2) . ' <br>';
echo 'Jumlah Dari Musim Dingin dan Musim Gugur : ' . chebyshevDistance($datasetSeason4, $datasetSeason3)  . ' - ' . $chebyshev->distance($datasetSeason4, $datasetSeason3) . ' <br>';