<?php
class RangeFinder {

	private $location;
	private $latitude;
	private $longitude;
	private $range;
	private $minLatitude;
	private $maxLatitude;
	private $minLongitude;
	private $maxLongitude;
	private $refLatitude;
	private $refLongitude;

	private $globe = array(0 => array("lat"=>68.71,"lng"=>69.17),
						   10 => array("lat"=>68.73,"lng"=>68.13),
						   20 => array("lat"=>68.79,"lng"=>65.02),
						   30 => array("lat"=>68.88,"lng"=>59.95),
						   40 => array("lat"=>68.99,"lng"=>53.06),
						   50 => array("lat"=>69.11,"lng"=>44.55),
						   60 => array("lat"=>69.23,"lng"=>34.67),
						   70 => array("lat"=>69.32,"lng"=>23.73),
						   80 => array("lat"=>69.38,"lng"=>12.05),
						   90 => array("lat"=>69.40,"lng"=>0.0));

	public function __construct($loc, $rng) {
		$this->location = $loc;
		$this->range = $rng;
		$this->setLatLng();
		$this->getRefLatLng();
		$this->calcMinMaxLatLng();
	}

	public function setLocation($loc) {
		$this->$location = $loc;
	}
	public function setRange($rng) {
		$this->$range = $rng;
	}

	private function setLatLng() {
		$loc = ltrim((rtrim($this->location,")")),"(");
		$latLng = explode(",", $loc);
		$this->latitude = ($latLng[0]);
		$this->longitude = ($latLng[1]);
	}

	private function getRefLatLng() {
		$lat = abs($this->latitude);
		$indx = (round(($lat + 5)/10))*10;
		$this->refLatitude = $this->globe[$indx]["lat"];
		$this->refLongitude = $this->globe[$indx]["lng"];
	}

	private function calcMinMaxLatLng() {
		$latRng = abs($this->range / $this->refLatitude);
		$lngRng = abs($this->range / $this->refLongitude);
		$this->minLatitude = $this->latitude - $latRng;
		$this->maxLatitude = $this->latitude + $latRng;
		$this->minLongitude = $this->longitude - $lngRng;
		$this->maxLongitude = $this->longitude + $lngRng;
	}

	public function getLocation() {
		return $this->location;
	}
	public function getRange() {
		return $this->range;
	}
	public function getLatitude() {
		return $this->latitude;
	}
	public function getRefLatitude() {
		return $this->refLatitude;
	}
	public function getMinLatitude() {
		return $this->minLatitude;
	}
	public function getMaxLatitude() {
		return $this->maxLatitude;
	}
	public function getLongitude() {
		return $this->longitude;
	}
	public function getRefLongitude() {
		return $this->refLongitude;
	}
	public function getMinLongitude() {
		return $this->minLongitude;
	}
	public function getMaxLongitude() {
		return $this->maxLongitude;
	}

	public function whereClause() {
		$clause = "latitude > ".$this->minLatitude.
					" AND latitude < ".$this->maxLatitude.
					" AND longitude > ".$this->minLongitude.
					" AND longitude < ".$this->maxLongitude;
		return ($clause);
	}
}
?>
