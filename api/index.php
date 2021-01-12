<?php
require __DIR__ . '/vendor/autoload.php';

use ProbeIPA\Classes\Controller;
use ProbeIPA\Classes\Rest;

/**
 * @autor Florian Leimer
 * @version 2020
 *
 *  Ausschliessliche dieses Modul wird über die API aufgerufen. Je nach übergebenem
 *  Parameter "management" wird die entsprechende Funktion ausgeführt.
 *
 */

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
header("Access-Control-Allow-Headers: *");


// Get requested method
$method = $_SERVER['REQUEST_METHOD'];

// Read post, put, delete data from client
$dataFromClient = json_decode(file_get_contents('php://input'));

// Read get data from client
foreach ( $_GET as $pname => $pvalue ) {
    if ( !$dataFromClient )  $dataFromClient = new stdClass();
    $dataFromClient->$pname = $pvalue;
}

// Run controller
$dataToClient = Controller::dispatch( $dataFromClient->management, $method, $dataFromClient );

// Send response to client
Rest::setHttpHeaders(200);
echo Rest::encodeJson( $dataToClient );

?>
