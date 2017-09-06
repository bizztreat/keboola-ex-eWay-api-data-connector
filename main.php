<?php
/**
 * Created by PhpStorm.
 * User: davidch
 * Date: 06/09/17
 * Time: 08:14
 */

require "vendor/autoload.php";
require_once "eway/eway.class.php";

// read the configuration file
$dataDir = getenv('KBC_DATADIR') . DIRECTORY_SEPARATOR;
$configFile = $dataDir . 'config.json';
$config = json_decode(file_get_contents($configFile), true);

$webServiceAddress = $config['parameters']['webServiceAddress'];
$username = $config['parameters']['username'];
$password = $config['parameters']['password'];
$passwordAlreadyEncrypted = $config['parameters']['passwordAlreadyEncrypted'];
$dieOnItemConflict = $config['parameters']['dieOnItemConflict'];

// Create eWay API connector
$connector = new eWayConnector($webServiceAddress, $username, $password, $passwordAlreadyEncrypted, $dieOnItemConflict);


// create output file and write header
$outFile = new \Keboola\Csv\CsvFile(
    $dataDir . 'out' . DIRECTORY_SEPARATOR . 'tables' . DIRECTORY_SEPARATOR . 'destination.csv'
);
$outFile->writeRow(['number', 'someText', 'double_number']);

// read input file and write rows of output file
$inFile = new Keboola\Csv\CsvFile($dataDir . 'in' . DIRECTORY_SEPARATOR . 'tables' . DIRECTORY_SEPARATOR . 'source.csv');


foreach ($inFile as $rowNum => $row) {
    if ($rowNum == 0) {
        // skip header
        continue;
    }
    $outFile->writeRow([
        $row[0],
        $row[1],
        $row[0] * 10 //$multiplier
    ]);
}


// eway api call sample
/*
$contact = array(
    'FirstName' => 'John',
    'LastName' => 'Doe',
    'Email1Address' => 'john.doe@example.com'
);
// Save new contact
$result = $connector->saveContact($contact);
if ($result->ReturnCode == 'rcSuccess')
{
    echo "New contact created with Guid {$result->Guid}";
}
else
{
    echo "Unable to create new contact: {$result->Description}";
}
*/

try {

} catch (InvalidArgumentException $e) {
    echo $e->getMessage();
    exit(1);
} catch (\Throwable $e) { // + $e
    echo $e->getMessage();
    exit(2);
}