/**
 * RangeFinder - pds, October 2015
 *
 * This class exists to assist in finding locations within a range from a specific location. It does
 * this with two methods, whereClause and distanceFrom. whereClause returns a mySql where clause
 * to find locations within a box. distanceFrom is used to find the radial distance from the location
 * using the Haversine formula.
 */
class RangeFinder {

    var $location;
    var $latitude;
    var $longitude;
    var $range;
    var $minLatitude;
    var $maxLatitude;
    var $minLongitude;
    var $maxLongitude;
    var $refLatitude;
    var $refLongitude;
    var $latitudeInRadians;
    var $longitudeInRadians;

    /*
     * The distance between longitudinal lines decreases as you
     * move from the equator (latitude 0) to the north pole
     * (latitude 90). We use absolute values so it's the same
     * to the south pole.
     */
    var $globe = [0 => ["lat" => 68.71, "lng" => 69.17],
        10 => ["lat" => 68.73, "lng" => 68.13],
        20 => ["lat" => 68.79, "lng" => 65.02],
        30 => ["lat" => 68.88, "lng" => 59.95],
        40 => ["lat" => 68.99, "lng" => 53.06],
        50 => ["lat" => 69.11, "lng" => 44.55],
        60 => ["lat" => 69.23, "lng" => 34.67],
        70 => ["lat" => 69.32, "lng" => 23.73],
        80 => ["lat" => 69.38, "lng" => 12.05],
        90 => ["lat" => 69.40, "lng" => 0.0]];

    /**
     * Initialize the object. Get latitude and longitude from $loc and range
     * from $range. Calculate the reference latitude and longitude and the
     * minimum and maximum latitude and longitude.
     *
     * @param string (lat,lng) $loc
     * @param numeric string $rng
     */
    function init($loc, $rng) {
        $this->location = $loc;
        $this->range = $rng;
        $this->setLatLng();
        $this->getRefLatLng();
        $this->calcMinMaxLatLng();
    }

    /**
     * Setter for location
     * @param string(lat,lng) $loc
     */
    function setLocation($loc) {
        $this->location = $loc;
    }

    /**
     * Setter for range
     * @param number $rng
     */
    function setRange($rng) {
        $this->range = $rng;
    }

    /**
     * Sets latitude and longitude from location, in both degrees and radians.
     */
    function setLatLng() {
        $loc = ltrim((rtrim($this->location, ")")), "(");
        $latLng = explode(",", $loc);
        $this->latitude = ($latLng[0]);
        $this->longitude = ($latLng[1]);
        $this->latitudeInRadians = deg2rad($this->latitude);
        $this->longitudeInRadians = deg2rad($this->longitude);
    }

    /**
     * Gets the reference latitude and longitude.
     */
    function getRefLatLng() {
        $lat = abs($this->latitude);
        $indx = (round(($lat + 5) / 10)) * 10;
        $this->refLatitude = $this->globe[$indx]["lat"];
        $this->refLongitude = $this->globe[$indx]["lng"];
    }

    /**
     * Calculates the minimum and maximum latitude and longitude for the range
     * based on the reference latitude and longitude.
     */
    function calcMinMaxLatLng() {
        $latRng = abs($this->range / $this->refLatitude);
        $lngRng = abs($this->range / $this->refLongitude);
        $this->minLatitude = $this->latitude - $latRng;
        $this->maxLatitude = $this->latitude + $latRng;
        $this->minLongitude = $this->longitude - $lngRng;
        $this->maxLongitude = $this->longitude + $lngRng;
    }

    /**
     * Getter for location
     * @return string(lat,lng)
     */
    function getLocation() {
        return $this->location;
    }

    /**
     * Getter for range
     * @return number
     */
    function getRange() {
        return $this->range;
    }

    /**
     * Getter for latitude
     * @return float
     */
    function getLatitude() {
        return $this->latitude;
    }

    /**
     * Getter for refLatitude
     * @return float
     */
    function getRefLatitude() {
        return $this->refLatitude;
    }

    /**
     * Getter for minLatitude
     * @return float
     */
    function getMinLatitude() {
        return $this->minLatitude;
    }

    /**
     * Getter for maxLatitude
     * @return float
     */
    function getMaxLatitude() {
        return $this->maxLatitude;
    }

    /**
     * Getter for longitude
     * @return float
     */
    function getLongitude() {
        return $this->longitude;
    }

    /**
     * Getter for refLongitude
     * @return float
     */
    function getRefLongitude() {
        return $this->refLongitude;
    }

    /**
     * Getter for minLongitude
     * @return float
     */
    function getMinLongitude() {
        return $this->minLongitude;
    }

    /**
     * Getter for maxLongitude
     * @return float
     */
    function getMaxLongitude() {
        return $this->maxLongitude;
    }

    /**
     * Creates SQL statement for WHERE which finds a square whose borders are
     * the range.
     * @return string
     */
    function whereClause() {
        $clause = "latitude > " . $this->minLatitude .
                " AND latitude < " . $this->maxLatitude .
                " AND longitude > " . $this->minLongitude .
                " AND longitude < " . $this->maxLongitude;
        return ($clause);
    }

    /**
     * Uses the Haversine formula to determine the distance from the current
     * location to the passed-in location.
     *
     * @param float $lat
     * @param float $lng
     * @return float
     */
    function distanceFrom($lat, $lng) {
        // Haversine formula
        $R = 6371000; // radius of earth in meters
        $fromLat = deg2rad($lat);
        $rDLat = deg2rad($lat - $this->latitude);
        $rDLng = deg2rad($lng - $this->longitude);

        $a = sin($rDLat / 2) * sin($rDLat / 2) +
                cos($this->latitudeInRadians) * cos($fromLat) *
                sin($rDLng / 2) * sin($rDLng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $d = $R * $c;
        return $d / 1609.34; // convert from meters to miles
    }

}

// class RangeFinder

