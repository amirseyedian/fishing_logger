<?php

namespace App\View\Components;

use Illuminate\View\Component;

class LeafletMap extends Component
{
    public $latitude;
    public $longitude;
    public $label;

    public function __construct($latitude, $longitude, $label = 'Fishing Spot')
    {
        $this->latitude = $latitude;
        $this->longitude = $longitude;
        $this->label = $label;
    }

    public function render()
    {
        return view('components.leaflet-map'); // DO NOT manually pass variables
    }
}