Expedia-PHP-API
===============

PHP Wrapper for Expedia API

Official API documentation can be found here: http://developer.ean.com/docs/

For usage examples please take a look at source code of `example.php` file.

Methods
===

 * `__construct( $cid, $key )` - constructs expedia api instance based on client id and api key
 * `getHotelList` - get hotels list ( doc: http://developer.ean.com/docs/hotel-list/ )
 * `getHotelInfo` - retrieve single hotel details ( doc: http://developer.ean.com/docs/hotel-info/ )
 * `getAvailableRooms` - retrieve available rooms for given hotel ( doc: http://developer.ean.com/docs/room-avail/ )
 * `getPaymentOptions` - retrieve supported payment methods ( doc: http://developer.ean.com/docs/payment-types/ )
 * `set_method` - set method to call api (GET or POST)
 * `set_protocol` - set prefix for api url ('http://' or 'https://book.')
 * `set_minor_rev` - set minimal api version
 * `set_locale` - set preferred locale which will be used to return results
 * `set_currency_code` - set currency for results (can be USD, AUD, RUR etc..)
 * `set_customer_session_id` - set unique customer session, required by api
 * `set_customer_ip_address` - set customer ip address
 * `set_customer_user_agent` - set customer user agent
 * `set_sig` - if you're using signature authentication, then set this
 * `*` - if you want to use an api method which is not implemented in this wrapper - then simply call it by name and it will be translated to api method call. Example: lets assume that we want to call method `rules` (http://developer.ean.com/docs/rate-rules/). Then we need to check method name in documentation. We go to documentation page and look for method REST URL and get last part (after v3/) from it. In our case it is `rules`. Then we simply call it like: `$hotelRules = $this->rules(array('hotelId' => 123, ...));`.
