<?php
error_reporting(E_ALL); ini_set('display_errors', 1);
$separator      = ',';
$pathToFile     = '';
$csvFileName    = 'available_stock_en_all_sizes.csv';
$csv = $fields = array(); $i = 0;
$handle = @fopen($pathToFile . $csvFileName, "r");
if ($handle) {
    $bom = fread($handle, 3);
    if ($bom != "\xEF\xBB\xBF")
    {
        rewind($handle);
    }
    while (($row = fgetcsv($handle, 0, $separator)) !== false) {
        if (empty($fields)) {
            $fields = $row;
            continue;
        }
        foreach ($row as $k=>$value) {
            $csv[$i][$fields[$k]] = $value;
        }
        $i++;
    }
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
}
echo "<pre>";
var_dump($csv);
die();
