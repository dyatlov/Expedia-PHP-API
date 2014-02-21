<?php

/**
 * @author Vitaly Dyatlov <md.xytop@gmail.com>
 */

namespace Dyatlov\Expedia;

class Expedia
{
    /**
     * Your EAN-issued account ID.
     * This number is used for tracking sales for statistics and commissions purposes on live sites.
     *
     * @var int
     */
    protected $account_id;
    /**
     * Your EAN-issued access key to the API
     * Determines your access to live bookings, your authentication method (IP or signature-based) and request quotas.
     *
     * @var string
     */
    protected $api_key;

    /**
     * Sets the minor revision used for processing requests and returning responses.
     * Defaults to 4 (original release) if omitted.
     *
     * @var int
     */
    protected $minor_rev = 4;
    /**
     * Returns data in other languages where available for Expedia Collect properties only.
     *
     * @var string
     */
    protected $locale = 'en_US';
    /**
     * Returns data in other currencies where available.
     * When booking, this value must match the value returned within the ChargeableRateInfo node
     * from the prior room availability response to prevent price mismatch errors.
     *
     * @var string
     */
    protected $currency_code = 'USD';

    /**
     * Insert your own unique value for each customer beginning with their first hotel list search,
     * or use the value returned in the initial list response for the remainder of the booking path.
     * Continue to pass this same value for each customer during each booking session,
     * using a new value for every new customer session.
     * Including this value greatly eases EAN's internal debugging process for issues with partner requests,
     * as it explicitly links together request paths for individual customers.
     *
     * @var string
     */
    protected $customer_session_id;
    /**
     * IP address of the customer, as captured by your integration.
     * Ensure your integration passes the customer's IP, not your own.
     * This value helps determine their location and assign the correct payment gateway.
     *
     * @var string
     */
    protected $customer_ip_address;
    /**
     * Customer's user agent string, as captured by your integration.
     * For mobile sites, include the string value MOBILE_SITE anywhere within this element.
     * Use MOBILE_APP for mobile apps. These values can be sent by themselves
     * or appended to a captured user agent string.
     *
     * @var string
     */
    protected $customer_user_agent;

    /**
     * The sig value used with signature authentication.
     * All sig values are required to be at least 32 lower-case characters in length.
     * If you receive errors when generating your own digital signature,
     * send a ping request to verify your Unix time against EAN's or check your value against EAN's sig generator.
     *
     * @var string
     */
    protected $sig;

    protected $hotel_api_url = 'api.eancdn.com/ean-services/rs/hotel/v3/';

    /**
     * cURL dump for last response
     *
     * @var string
     */
    protected $verbose_log;

    /**
    * Query method: POST or GET
    *
    * @var string
    */
    protected $method = 'GET';

    protected $protocol = 'http://';

    public function __construct($account_id, $api_key)
    {
        assert(is_numeric($account_id));
        assert(is_string($api_key));

        $this->account_id = $account_id;
        $this->api_key = $api_key;
    }

    public function set_method( $method )
    {
        $this->method = strtoupper($method);
    }

    public function set_protocol( $protocol )
    {
        $this->protocol = strtolower($protocol);
    }

    /**
     * Sets the minor revision used for processing requests and returning responses.
     * Defaults to 4 (original release) if omitted.
     *
     * @param int $num
     */
    public function set_minor_rev($num)
    {
        assert(is_numeric($num));

        $this->minor_rev = $num;
    }

    public function get_minor_rev()
    {
        return $this->minor_rev;
    }

    /**
     * Returns data in other languages where available for Expedia Collect properties only.
     *
     * @param string $locale
     */
    public function set_locale($locale)
    {
        assert(is_string($locale));

        $this->locale = $locale;
    }

    public function get_locale()
    {
        return $this->locale;
    }

    /**
     * Returns data in other currencies where available.
     * When booking, this value must match the value returned within the ChargeableRateInfo node
     * from the prior room availability response to prevent price mismatch errors.
     *
     * @param string $currency_code
     */
    public function set_currency_code($currency_code)
    {
        assert(is_string($currency_code));

        $this->currency_code = $currency_code;
    }

    public function get_currency_code()
    {
        return $this->currency_code;
    }

    /**
     * Insert your own unique value for each customer beginning with their first hotel list search,
     * or use the value returned in the initial list response for the remainder of the booking path.
     * Continue to pass this same value for each customer during each booking session,
     * using a new value for every new customer session.
     * Including this value greatly eases EAN's internal debugging process for issues with partner requests,
     * as it explicitly links together request paths for individual customers.
     *
     * @param string $customer_session_id
     */
    public function set_customer_session_id($customer_session_id)
    {
        assert(is_string($customer_session_id));

        $this->customer_session_id = $customer_session_id;

        return $this;
    }

    public function get_customer_session_id()
    {
        return $this->customer_session_id;
    }

    /**
     * IP address of the customer, as captured by your integration.
     * Ensure your integration passes the customer's IP, not your own.
     * This value helps determine their location and assign the correct payment gateway.
     *
     * @param string $customer_ip_address
     */
    public function set_customer_ip_address($customer_ip_address)
    {
        assert(is_string($customer_ip_address));

        $this->customer_ip_address = $customer_ip_address;

        return $this;
    }

    public function get_customer_ip_address()
    {
        return $this->customer_ip_address;
    }

    /**
     * Customer's user agent string, as captured by your integration.
     * For mobile sites, include the string value MOBILE_SITE anywhere within this element.
     * Use MOBILE_APP for mobile apps. These values can be sent by themselves
     * or appended to a captured user agent string.
     *
     * @param string $customer_user_agent
     */
    public function set_customer_user_agent($customer_user_agent)
    {
        assert(is_string($customer_user_agent));

        $this->customer_user_agent = $customer_user_agent;

        return $this;
    }

    public function get_customer_user_agent()
    {
        return $this->customer_user_agent;
    }

    /**
     * The sig value used with signature authentication.
     * All sig values are required to be at least 32 lower-case characters in length.
     * If you receive errors when generating your own digital signature,
     * send a ping request to verify your Unix time against EAN's or check your value against EAN's sig generator.
     *
     * @param string $sig
     */
    public function set_sig($sig)
    {
        assert(is_string($sig));

        $this->sig = $sig;

        return $this;
    }

    public function get_sig()
    {
        return $this->sig;
    }

    public function __call($name, $args)
    {
        $response = $this->safeCall($name, $args);

        if( isset($response['EanWsError']) )
        {
            throw new Exception( $response['EanWsError'] );
        }

        return $response;
    }

    /**
     * SOAP calls going here
     *
     * @param string $name
     * @param array $args
     */
    public function safeCall($name, $args)
    {
        $url = $this->protocol . $this->hotel_api_url . $name;

        $ch = curl_init();

        if (count($args)) {
            assert(count($args) == 1);

            $data = array(
                    'cid' => $this->account_id,
                    'apiKey' => $this->api_key,
                    'minorRev' => $this->get_minor_rev(),
                    'customerSessionId' => $this->get_customer_session_id(),
                    'customerIpAddress' => $this->get_customer_ip_address(),
                    'customerUserAgent' => $this->get_customer_user_agent(),
                    'currencyCode' => $this->get_currency_code()
                ) + $args[0];

            $url .= '?' . http_build_query($data);

            if( $this->method == 'GET' ) {                                
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            }
            else {
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            }
        }

        $header[] = "Accept: application/json";
        $header[] = "Accept-Encoding: gzip";
        $header[] = "Content-length: 0";
        

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_ENCODING, "gzip");        
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $verbose = fopen('php://temp', 'rw+');
        curl_setopt($ch, CURLOPT_STDERR, $verbose);

        $result = curl_exec($ch);

        !rewind($verbose);
        $this->verbose_log = stream_get_contents($verbose);

        $response = json_decode($result, true);

        $response = current( $response );

        return $response;
    }

    public function get_http_dump()
    {
        return $this->verbose_log;
    }
}
