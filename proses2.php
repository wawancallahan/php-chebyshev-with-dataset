<?php
require_once __DIR__ . '/vendor/autoload.php';

use Phpml\Dataset\CsvDataset;

$datasets = new CsvDataset('./datasets/blood_tranfusion.csv', 5, true);

$datasetColumnNames = $datasets->getColumnNames();
$datasetSamples = $datasets->getSamples();

$attributeCountStart = 0;
$attributeCountEnd = 3;

$v1 = $_POST['v1'];
$v2 = $_POST['v2'];
$v3 = $_POST['v3'];
$v4 = $_POST['v4'];

$field = [
    'v1',
    'v2',
    'v3',
    'v4'
];

$max = $inputSampleData = [
    'v1' => $v1,
    'v2' => $v2,
    'v3' => $v3,
    'v4' => $v4,
];

// Mencari Max Nilai

foreach ($datasetSamples as $dataset) {
    foreach (range($attributeCountStart, $attributeCountEnd) as $attributeRange) {
        $max[$field[$attributeRange]] = max($dataset[$attributeRange], $max[$field[$attributeRange]]);
    }
}

//

$newSampleData = $datasetSamples;

// Setiap nilai dibagi max
foreach ($newSampleData as $datasetKey => $dataset) {
    foreach (range($attributeCountStart, $attributeCountEnd) as $attributeRange) {
        $newSampleData[$datasetKey][$attributeRange] = number_format($dataset[$attributeRange] / $max[$field[$attributeRange]], 2, '.', '');
    }
}

foreach (range($attributeCountStart, $attributeCountEnd) as $attributeRange) {
    $inputSampleData[$field[$attributeRange]] = number_format($inputSampleData[$field[$attributeRange]] / $max[$field[$attributeRange]], 2, '.', '');
}

//


$distance = [];

foreach ($newSampleData as $datasetKey => $dataset) {

    $result = null;

    foreach (range($attributeCountStart, $attributeCountEnd) as $attributeRange) {
        if ($result == null) {
            $result = abs($dataset[$attributeRange] - $inputSampleData[$field[$attributeRange]]);

            continue;
        }

        $result = max($result, abs($dataset[$attributeRange] - $inputSampleData[$field[$attributeRange]]));
    }

    $distance[$datasetKey] = $result;
}

asort($distance);

$newDistance = [];

foreach ($distance as $index => $ds) {
    $newDistance[] = [
        'key' => $index,
        'result' => $ds
    ];
}

$k = number_format(sqrt(count($datasetSamples) / 2));

if (count($datasetSamples) % 2 == 0) {
    if ($k % 2 == 0) {
        $k += 1;
    }
} else {
    if ($k % 2 != 0) {
        $k += 1;
    }
}

$resultData = [];

$canDonateBlood = $cannotDonateBlood = 0;
foreach (range(0, $k - 1) as $range) {
    $result = $datasetSamples[$newDistance[$range]['key']][4];

    $resultData = $datasetSamples[$newDistance[$range]['key']];

    if ($result == 1) {
        $cannotDonateBlood++;
    } else {
        $canDonateBlood++;
    }
}

$resultDonated = $canDonateBlood >= $cannotDonateBlood ? 1 : 0;

echo json_encode([
    'input' => [
        'v1' => $v1,
        'v2' => $v2,
        'v3' => $v3,
        'v4' => $v4
    ],
    'params' => [
        'length_sample' => count($datasetSamples),
        'k' => $k
    ],
    'result_data' => $resultData,
    'result_length' => [
        'canDonateBlood' => $canDonateBlood,
        'cannotDonateBlood' => $cannotDonateBlood
    ],
    'result' => $resultDonated,
    'result_text' => ($resultDonated == 1 ? "" : "Tidak ") . "Dapat Berdonasi/ Tranfusi Darah"
]);
