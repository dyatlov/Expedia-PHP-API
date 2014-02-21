<?php

/**
 * @author Vitaly Dyatlov <md.xytop@gmail.com>
 */

namespace Dyatlov\Expedia;

class API extends Expedia
{
    protected $_totalHotels = 0;
    protected $_availableHotels = 0;

    public function getTotalHotels()
    {
        return $this->_totalHotels;
    }

    public function getAvailableHotels()
    {
        return $this->_availableHotels;
    }

    public function getPaymentOptions($currencyCode, $locale)
    {
        $result = $this->paymentInfo(array(
                'currencyCode' => $currencyCode,
                'locale' => $locale
            ));

        return $result;
    }

    public function getHotelInfo($hotelId)
    {
        $result = $this->info(array(
            'hotelId' => $hotelId
        ));

        return $result;
    }

    public function getAvailableRooms($params)
    {
        $result = $this->avail($params);

        return $result;
    }

    public function getHotelList($params)
    {
        $result = array();

        try {
            $hotels = $this->list($params);

            if ($hotels != null && isset($hotels['HotelList'])) {
                $this->_totalHotels = $hotels['HotelList']['@activePropertyCount'];
            }

            while ($hotels != null) {
                if (isset($hotels['HotelList'])) {
                    if ($hotels['HotelList']['@size'] > 1) {
                        $result = array_merge($result, $hotels['HotelList']['HotelSummary']);
                    } else {
                        $result = array_merge($result, array($hotels['HotelList']['HotelSummary']));
                    }
                }

                if (!$hotels['moreResultsAvailable']) {
                    break;
                }

                if (isset($params['numberOfResults']) && count($result) <= intval($params['numberOfResults'])) {
                    break;
                }

                $hotels = $this->list(array(
                    'cacheKey' => $hotels['cacheKey'],
                    'cacheLocation' => $hotels['cacheLocation']
                ));
            }
        } catch (Exception $ex) {
            $data = $ex->getData();
            if ($data['category'] != 'RESULT_NULL') {
                throw new Exception($data);
            }
        }

        $this->_availableHotels = count($result);

        return $result;
    }
}
