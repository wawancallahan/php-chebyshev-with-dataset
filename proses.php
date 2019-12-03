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
            $result = number_format(abs($dataset[$attributeRange] - $inputSampleData[$field[$attributeRange]]), 2, ".", "");

            continue;
        }

        $result = max($result, number_format(abs($dataset[$attributeRange] - $inputSampleData[$field[$attributeRange]]), 2, ".", ""));
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

$k = number_format(sqrt(count($datasetSamples)));

if (count($datasetSamples) % 2 == 0) {
    if ($k % 2 == 0) {
        $k += 1;
    }
} else {
    if ($k % 2 != 0) {
        $k += 1;
    }
}

$kId = 0;

$resultDistance = [];
$resultData = [];

foreach ($newDistance as $keyDistance => $distanceItem) {
    if ( ! in_array($distanceItem['result'], $resultDistance)) {
        $resultDistance[] = $distanceItem['result'];

        $resultData[$kId][] = $distanceItem;

        $kId += 1;

        if ($kId == $k) {
            break;
        }
    } else {
        $resultData[$kId - 1][] = $distanceItem;
    }
}

$resultDataViewRow = '';

$canDonateBlood = $cannotDonateBlood = 0;

$no = 1;

foreach ($resultData as $dataItems) {
    foreach ($dataItems as $dataItemKey => $dataItem) {
        if ($datasetSamples[$dataItem['key']][4] == 1) {
            $cannotDonateBlood++;
        } else {
            $canDonateBlood++;
        }

        if ($dataItemKey == 0) {
            $resultDataViewRow .= '<tr>' .
                                '<td rowspan="' . count($dataItems) . '">' . $no++ . '</td>' . 
                                '<td>' . $datasetSamples[$dataItem['key']][0] . '</td>' . 
                                '<td>' . $datasetSamples[$dataItem['key']][1] . '</td>' . 
                                '<td>' . $datasetSamples[$dataItem['key']][2] . '</td>' . 
                                '<td>' . $datasetSamples[$dataItem['key']][3] . '</td>' . 
                                '<td>' . ($datasetSamples[$dataItem['key']][4] == 1 ? 'Tidak Dapat Berdonasi' : 'Dapat Berdonasi') . '</td>' . 
                                '<td>' . $dataItem['result'] . '</td>' . 
                            '</tr>';
        } else {
            $resultDataViewRow .= '<tr>' . 
                                '<td>' . $datasetSamples[$dataItem['key']][0] . '</td>' . 
                                '<td>' . $datasetSamples[$dataItem['key']][1] . '</td>' . 
                                '<td>' . $datasetSamples[$dataItem['key']][2] . '</td>' . 
                                '<td>' . $datasetSamples[$dataItem['key']][3] . '</td>' . 
                                '<td>' . ($datasetSamples[$dataItem['key']][4] == 1 ? 'Tidak Dapat Berdonasi' : 'Dapat Berdonasi') . '</td>' . 
                                '<td>' . $dataItem['result'] . '</td>' . 
                            '</tr>';
        }
    }
}

$resultDataView = '<table class="table">' . 
                    '<thead>' .
                        '<tr>' .
                            '<th>No</th>' .
                            '<th>V1</th>' .
                            '<th>V2</th>' .
                            '<th>V3</th>' .
                            '<th>V4</th>' .
                            '<th>Kelas</th>' .
                            '<th>Hasil</th>' .
                        '</tr>' .
                    '</thead>' .
                    '<tbody>' .
                        $resultDataViewRow .
                    '</tbody>' .
                '</table';

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
    'result_data_view' => $resultDataView,
    'result' => $resultDonated,
    'result_length' => [
        'canDonateBlood' => $canDonateBlood,
        'cannotDonateBlood' => $cannotDonateBlood
    ],
    'result_text' => ($resultDonated == 1 ? "" : "Tidak ") . "Dapat Berdonasi/ Tranfusi Darah"
]);
