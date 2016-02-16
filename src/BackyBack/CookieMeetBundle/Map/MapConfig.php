<?php
/**
 * Created by PhpStorm.
 * User: mathieu
 * Date: 14/02/2016
 * Time: 12:13
 */

namespace BackyBack\CookieMeetBundle\Map;

use Ivory\GoogleMap\Map;
use Ivory\GoogleMap\MapTypeId;
use Ivory\GoogleMap\Overlays\Animation;
use Ivory\GoogleMap\Overlays\Marker;
use Ivory\GoogleMap\Services\Geocoding\Geocoder;
use Ivory\GoogleMap\Services\Geocoding\Result\GeocoderLocationType;
use Ivory\GoogleMap\Services\Geocoding\Result\GeocoderResult as Result;
use BackyBack\CookieMeetBundle\Controller\MapController as Mappy;

class MapConfig extends Mappy
{
    protected function configMapAction($map)
    {
        $map = new Map();

        $marker = $this->markerConfigAction($map);
        $map->addMarker($marker);
        $map->setPrefixJavascriptVariable('map_');
        $map->setHtmlContainerId('map_canvas');

        $map->setCenter(48.856614, 2.352222, true);

        $map->setMapOption('zoom', 12);
        $map->setMapOption('mapTypeId', MapTypeId::ROADMAP);
        $map->setMapOption('mapTypeId', 'roadmap');
        $map->setMapOption('disableDoubleClickZoom', false);
        $map->setMapOptions(array(
            'disableDefaultUI'       => false,
            'disableDoubleClickZoom' => false,
        ));
        $map->setStylesheetOptions(array(
            'width'  => '600px',
            'height' => '600px',
        ));

        $map->setLanguage('fr');
        return $map;
    }

    public function markerConfigAction($marker)
    {
        $marker = new Marker();
        $curl     = new \Ivory\HttpAdapter\CurlHttpAdapter();
        $geocoder = new \Geocoder\Provider\GoogleMaps($curl);
        $response = new Result();

        $geocoder->geocode('73 Boulevard Berthier, Paris, France');

        foreach($response->getResults() as $result)
        {
            // Position the marker
            $marker->setPosition($result->getGeometry()->getLocation());
            $marker->setAnimation(Animation::DROP);

            $marker->setOptions(array(
                'clickable' => true,
                'flat'      => true,
            ));
            // Add the marker to the map
            $map->addMarker($marker);
        }
        $marker->setPrefixJavascriptVariable('marker_');
        /*$marker->setPosition(48.856614, 2.352222, true);*/

        return $marker;
    }
}