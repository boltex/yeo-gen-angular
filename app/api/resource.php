<?php
namespace Api;

//error_reporting(E_ERROR | E_PARSE);

use \Exception;

require('_includes/data.php');
require('_includes/functions.php');
session_cache_expire(60);
session_start();
$lastsessionid = session_id();
//session_regenerate_id();

$conn = 0 ;// Global Connection to DB2
$connPDO = 0;

try {
    // Prevent displaying errors
    ob_start();

    $response = array();
    $allowedMethods = array('GET', 'POST');
    $allowedHeaders = array();

    $resources = array(
        'html5-boilerplate' => array(
            'name' => 'HTML5 Boilerplate',
            'description' => 'test Boilerplate is a professional front-end template'
                . ' for building fast, robust, and adaptable web apps or sites.',
        ),
        'angular' => array(
            'name' => 'Angular',
            'description' => 'AngularJS is a toolset for building the framework most'
                . ' suited to your application development. test accents: é à ',
        ),
        'bootstrap' => array(
            'name' => 'Bootstrap',
            'description' => 'The most popular front-end framework for developing'
                . ' responsive, mobile first projects on the web.',
        ),
    );

    // Get headers and normalize them
    if (function_exists('getallheaders')) {
        $headers = getallheaders();
    }
    else {
        $headers = array();
        foreach ($_SERVER as $name => $value) {
            if (preg_match('/^HTTP_(.*)$/', $name, $matches)) {
                $headers[str_replace('_', '-', $matches[1])] = $value;
            }
        }
    }
    foreach ($headers as $name => $value) {
        unset($headers[$name]);
        $headers[ucwords(strtolower($name))] = $value;
    }

    // Handle OPTIONS method
    if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('Allow: ' . implode(', ', $allowedMethods));
        header('Access-Control-Allow-Methods: ' . implode(', ', $allowedMethods));
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Request-Headers: ' . implode(', ', $allowedHeaders));
        exit();
    }

    // Reject if method is not allowed
    if (!in_array($_SERVER['REQUEST_METHOD'], $allowedMethods)) {
        throw new Exception(
            'Only ' . implode(', ', $allowedMethods)
            . (count($allowedMethods) === 1 ? ' method is' : ' methods are')
            . ' allowed',
            405
        );
    }

    // Reject if not acceptable
    if (!array_key_exists('Accept', $headers) || !in_array('application/json', explode(', ', $headers['Accept']))) {
        throw new Exception('Only application/json is acceptable', 406);
    }

    /**
     * -------------------------------------------------------------------------
     * /resource/{id}
     * -------------------------------------------------------------------------
     */
    if (isset($_POST['id'])) {
        if (!preg_match('/^[a-z0-9-]+$/', $id = $_POST['id'])) {
            throw new Exception('
             id must contain only lowercase alphanumeric and - characters', 400);
        }
        if (!array_key_exists($id, $resources)) {
            throw new Exception("Resource with id $id does not exist", 404);
        }
        $response = array_merge(
            $response,
            array('id' => $id),
            $resources[$id]
        );
    }
    /**
     * -------------------------------------------------------------------------
     * /resource/{action}
     * -------------------------------------------------------------------------
     */
    if (isset($_POST['action'])) {
        if (!preg_match('/^[a-z0-9-]+$/', $action = $_POST['action'])) {
            throw new Exception('
             action must contain only lowercase alphanumeric and - characters', 400);
        }
        switch ( $action ){

            case 'testquery':
                $response = array_merge(
                    $response,
                    array( 'message' => testfunction() ) 
                ); 
                break;

            case 'testsp0120':
                testsp0120($response);
                break;


            default:
                throw new Exception("Action with id $action does not exist", 404);
                break;

        }
    }
    /**
     * -------------------------------------------------------------------------
     * /resource
     * -------------------------------------------------------------------------
     */
    else {
        $url = 'http' . (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] === 'on' ? 's' : '') . '://'
            . $headers['Host'] . preg_replace('/^(\/api)?/', '/api', $_SERVER['REQUEST_URI']);

        $response['resources'] = array();
        foreach ($resources as $id => $resource) {
            array_push($response['resources'], array(
                'id' => $id,
                'name' => $resource['name'],
                'href' => preg_match('/\.php$/', $url) ? "$url?id=$id" : "$url/$id",
            ));
        }
    }

    // Catch non-exception errors
    $output = ob_get_clean();
    if ($output !== '') {
        throw new Exception(trim($output), 500);
    }

    header('HTTP/1.1 200 OK');
    header('Content-Type: application/json');
    echo json_encode($response);
    // *********************************************************** [END IF OK]
}
catch(Exception $e) {

    $response = array(
        'status' => $e->getCode(),
        'statusText' => null,
        'description' => $e->getMessage(),
        'stack' => $e->getTrace(),
    );

    switch ($response['status']) {
        case 400:
            $response['statusText'] = 'Bad Request';
            break;
        case 404:
            $response['statusText'] = 'Not Found';
            break;
        case 405:
            $response['statusText'] = 'Method Not Allowed';
            break;
        case 406:
            $response['statusText'] = 'Not Acceptable';
            break;
        default:
            $response['statusText'] = 'Internal Server Error';
            $response['status'] = 500;
            break;
    }

    header('HTTP/1.1 ' . $response['status'] . ' ' . $response['statusText']);
    header('Content-Type: application/json');
    echo json_encode($response);
}
