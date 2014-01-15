<?php

use Dyatlov\Expedia\API;

session_start();

// create an instance
$expedia = new Expedia( '<put your client id (cid) here>', '<put your key here>' );

$expedia->set_customer_session_id( session_id() );
$expedia->set_customer_ip_address($_SERVER['REMOTE_ADDR']);
$expedia->set_customer_user_agent($_SERVER['HTTP_USER_AGENT']);
$expedia->set_currency_code('USD');
$expedia->set_minor_rev(26);

// get hotels list for specified destination
$hotels = $expedia->getHotelList(array(
  'destinationId' => '08D9CD84-36F7-4FE4-9D80-D04E10A778C4',
  'arrivalDate' => '10/10/2014',
  'departureDate' => '10/11/2014',
  'numberOfResults' => 10,
  'room1' => 1
));

var_export($hotels);

// get hotel info
$info = $expedia->getHotelInfo( $hotels['hotels'][0]['id'] );

var_export( $info );

// book hotel
$expedia->set_method('POST');
$expedia->set_protocol('https://book.');
$result = $expedia->res( array(
    'hotelId' => 123,
    'arrivalDate' => '10/10/2014',
    'departureDate' => '10/11/2014',
    'supplierType' => 'E',
    'rateKey' => '234be234234afd',
    'roomTypeCode' => 12345,
    'rateCode' => '123123',
    'chargeableRate' => '150.0',

    'room1' => 1,
    'room1FirstName' => 'John',
    'room1LastName' => 'Guest',
    'room1BedTypeId' => 1234,
    'room1SmokingPreference' => 'no',

    'email' => 'test@test.com',
    'firstName' => 'Tester',
    'lastName' => 'Tester',
    'homePhone' => '1234567',
    'workPhone' => '1234567',
    'creditCardType' => 'VI',
    'creditCardNumber' => '41111111111111111',
    'creditCardIdentifier' => '123',
    'creditCardExpirationMonth' => '05',
    'creditCardExpirationYear' => '2017',

    'address1' => 'travelnow',
    'city' => 'Moscow',
    'stateProvinceCode' => null,
    'countryCode' => 'RU',
    'postalCode' => '3357'
) );

var_export($result);
