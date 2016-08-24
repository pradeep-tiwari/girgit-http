<?php

require_once 'girgit/vendor/autoload.php';
$response = new Girgit\Http\Response();

// in action
$response
    ->html('<h3>404 Not Found</h3>')
    ->headers(['X-Powered' => 'Pradeep', 'X-Accept' => 'application/json'])
    ->code(404)
    ->message('You Okay??')
    ->send();
    
$response->explain(900);

echo $response->code();
