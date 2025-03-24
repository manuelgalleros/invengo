<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use Picqer\Barcode\BarcodeGeneratorPNG;

if (!function_exists('generate_barcode')) {
    function generate_barcode($code) {
        $generator = new BarcodeGeneratorPNG();
        return 'data:image/png;base64,' . base64_encode($generator->getBarcode($code, $generator::TYPE_CODE_128));
    }
}