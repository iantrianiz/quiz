<?php
// Function to load CSV and return data for plotting. We used GD Library for Scatter Plot

function loadCSVData($filename) {
    $data = array();
    
    if (($handle = fopen($filename, "r")) !== FALSE) {
        while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
            // Assume the first column is the x-axis and the second column is the y-axis
            $data[] = array(floatval($row[0]), floatval($row[1]));
        }
        fclose($handle);
    }
    
    return $data;
}

// Filepath to our CSV
$csvFile = "PHP_Quiz_Question#2-out.csv"; 

// Load data from CSV
$data = loadCSVData($csvFile);

// Get the min and max values for the axes to normalize the data for better scaling
$minX = min(array_column($data, 0));
$maxX = max(array_column($data, 0));
$minY = min(array_column($data, 1));
$maxY = max(array_column($data, 1));

// Create an image
$imageWidth = 700;
$imageHeight = 500;
$image = imagecreatetruecolor($imageWidth, $imageHeight);

// Set background color (white)
$backgroundColor = imagecolorallocate($image, 255, 255, 255);
imagefill($image, 0, 0, $backgroundColor);

// Set colors for axes and points
$axisColor = imagecolorallocate($image, 0, 0, 0);
$pointColor = imagecolorallocate($image, 255, 0, 0);

// Draw X and Y axes
imageline($image, 50, $imageHeight - 50, $imageWidth - 50, $imageHeight - 50, $axisColor); // X-axis
imageline($image, 50, $imageHeight - 50, 50, 50, $axisColor); // Y-axis

// Scale the data to fit in the image
$scaleX = ($imageWidth - 100) / ($maxX - $minX);
$scaleY = ($imageHeight - 100) / ($maxY - $minY);

// Plot the points
foreach ($data as $point) {
    $x = 50 + ($point[0] - $minX) * $scaleX; // X position based on scale
    $y = $imageHeight - 50 - ($point[1] - $minY) * $scaleY; // Y position based on scale
    imagefilledellipse($image, $x, $y, 5, 5, $pointColor); // Draw each point as a red dot
}

// Output the image to the browser
header('Content-Type: image/png');
imagepng($image);

// Free memory
imagedestroy($image);

?>