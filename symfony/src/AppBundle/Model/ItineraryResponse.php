<?php
/**
 * Created by PhpStorm.
 * User: bizmate
 * Date: 28/06/15
 * Time: 01:26
 */

namespace AppBundle\Model;


class ItineraryResponse {

    public $itineraries;
    public $hotels;
    public $localOffers;
    public function __construct($itineraries, $hotels, $localOffers)
    {
        $this->itineraries = $itineraries;
        $this->hotels = $hotels;
        $this->localOffers = $localOffers;
    }
}