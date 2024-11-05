<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Include the Composer autoloader for PHPSpreadsheet
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Get the district name from the URL
$district = isset($_GET['district']) ? $_GET['district'] : '';

if ($district) {
    try {
        // Load the Excel file
        $spreadsheet = IOFactory::load('crops_data2.xlsx');
        $worksheet = $spreadsheet->getActiveSheet();

        $found = false;
        $cropData = [];  // To store crop details

        // Loop through each row of the spreadsheet to find the district
        foreach ($worksheet->getRowIterator() as $row) {
            $cellIterator = $row->getCellIterator();
            $cellIterator->setIterateOnlyExistingCells(false);

            $rowData = [];
            foreach ($cellIterator as $cell) {
                $rowData[] = $cell->getValue();
            }

            // Compare first column (district) with the query parameter
            if (strcasecmp(trim($rowData[0]), trim($district)) == 0) {
                $found = true;
                $cropData[] = [
                    'Crop' => $rowData[1],  
                    'Year' => $rowData[2],
                    'Season' => $rowData[3],
                    'Area' => $rowData[4],
                    'Production' => $rowData[5],
                    'Yeild' => $rowData[6]
                ];
            }
        }

        // Display crop data if district is found
        if ($found) {
            echo "<h2 style='text-align: center; font-family: Arial, sans-serif;'>Crops Data for District: " . htmlspecialchars($district) . "</h2>";
            echo "<table style='width: 100%; border-collapse: collapse; margin: 20px 0; font-family: Arial, sans-serif;'>
                    <thead style='background-color: #f2f2f2;'>
                      <tr>
                        <th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Crop</th>
                        <th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Year</th>
                        <th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Season</th>
                        <th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Area (Hectare)</th>
                        <th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Production (Tonnes)</th>
                        <th style='border: 1px solid #ddd; padding: 10px; text-align: left;'>Yield (Tonnes/Hectare)</th>
                      </tr>
                    </thead>
                    <tbody>";

            // Populate the table with crop data
            foreach ($cropData as $crop) {
                echo "<tr style='background-color: #f9f9f9;'>
                        <td style='border: 1px solid #ddd; padding: 10px;'>" . htmlspecialchars($crop['Crop']) . "</td>
                        <td style='border: 1px solid #ddd; padding: 10px;'>" . htmlspecialchars($crop['Year']) . "</td>
                        <td style='border: 1px solid #ddd; padding: 10px;'>" . htmlspecialchars($crop['Season']) . "</td>
                        <td style='border: 1px solid #ddd; padding: 10px;'>" . htmlspecialchars($crop['Area']) . "</td>
                        <td style='border: 1px solid #ddd; padding: 10px;'>" . htmlspecialchars($crop['Production']) . "</td>
                        <td style='border: 1px solid #ddd; padding: 10px;'>" . htmlspecialchars($crop['Yeild']) . "</td>
                      </tr>";
            }
            echo "</tbody></table>";
        } else {
            echo "<p style='font-family: Arial, sans-serif;'>No crops data found for the district: " . htmlspecialchars($district) . "</p>";
        }

    } catch (Exception $e) {
        echo "<p style='font-family: Arial, sans-serif; color: red;'>Error reading Excel file: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p style='font-family: Arial, sans-serif;'>District not provided.</p>";
}
?>
