<?php
/**
 * Developer: ONUR KAYA
 * Contact: empatisoft@gmail.com
 */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$local = true;
$hostname = 'localhost';
$username = 'root';
$password = 'root';
$database = 'adresler';
$port = 3306;

if($local != true) {
    $hostname = '';
    $username = '';
    $password = '';
    $database = '';
    $port = 3306;
}

define('DB_SERVER', $hostname);
define('DB_USERNAME', $username);
define('DB_PASSWORD', $password);
define('DB_NAME', $database);
define('DB_PORT', $port);

require_once 'Database.php';

$db = new Database();

$type = filter_input(INPUT_GET, 'type', FILTER_SANITIZE_STRING);
$type = empty($type) ? 'country' : $type;

$country_id = filter_input(INPUT_GET, 'country_id', FILTER_SANITIZE_NUMBER_INT);
$country_id = isset($country_id) && !empty($country_id) ? $country_id : 1;

$city_id = filter_input(INPUT_GET, 'city_id', FILTER_SANITIZE_NUMBER_INT);
$city_id = isset($city_id) && !empty($city_id) ? $city_id : 0;

$county_id = filter_input(INPUT_GET, 'county_id', FILTER_SANITIZE_NUMBER_INT);
$county_id = isset($county_id) && !empty($county_id) ? $county_id : 0;

$data = NULL;

if($type == 'country') {
    $data = $db->result('SELECT country_id, name FROM address_country WHERE is_active = 1 ORDER BY name ASC');
} else if($type == 'city') {
    $data = $db->result(
        'SELECT kimlikNo, bilesenAdi FROM address_city WHERE is_active = 1 AND country_id = :country_id ORDER BY bilesenAdi ASC',
        array('country_id' => $country_id)
    );
} else if($type == 'county') {
    $data = $db->result(
        'SELECT ilceKayitNo as kimlikNo, bilesenAdi FROM address_county WHERE is_active = 1 AND ilKayitNo = :city_id ORDER BY bilesenAdi ASC',
        array('city_id' => $city_id)
    );
} else if($type == 'neighborhood') {
    $data = $db->result(
        'SELECT kimlikNo, bilesenAdi FROM address_neighborhood WHERE is_active = 1 AND ilceKayitNo = :county_id ORDER BY bilesenAdi ASC',
        array('county_id' => $county_id)
    );
}

header('Content-type: application/json');
echo json_encode($data, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
