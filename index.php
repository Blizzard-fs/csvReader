<?php

class CsvParser {
    private $filePath;
    private $separator;

    public function __construct($filePath, $separator = ',') {
        $this->filePath = $filePath;
        $this->separator = $separator;
    }

    public function parse() {
        $this->validateFile();
        $handle = $this->openFile();

        $this->handleBom($handle);
        $headers = $this->getHeaders($handle);
        $data = $this->getData($handle, $headers);

        fclose($handle);
        return $data;
    }

    private function validateFile() {
        if (!file_exists($this->filePath) || !is_readable($this->filePath)) {
            throw new Exception("Error: Unable to read the file '{$this->filePath}'.");
        }
    }

    private function openFile() {
        $handle = fopen($this->filePath, "r");
        if (!$handle) {
            throw new Exception("Error: Unable to open the file.");
        }
        return $handle;
    }

    private function handleBom($handle) {
        if (fread($handle, 3) !== "\xEF\xBB\xBF") {
            rewind($handle);
        }
    }

    private function getHeaders($handle) {
        $headers = fgetcsv($handle, 0, $this->separator);
        if (!$headers) {
            throw new Exception("Error: Failed to read headers from the file.");
        }
        return $headers;
    }

    private function getData($handle, $headers) {
        $data = [];
        while (($row = fgetcsv($handle, 0, $this->separator)) !== false) {
            $data[] = array_combine($headers, $row);
        }
        return $data;
    }
}

// Example Usage
try {
    $csvParser = new CsvParser('available_stock_en_all_sizes.csv');
    $data = $csvParser->parse();
    echo "<pre>";
    print_r($data);
} catch (Exception $e) {
    echo $e->getMessage();
}
