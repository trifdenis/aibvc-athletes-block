<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Fetch athletes data from the AIBVC APIs.
 */
function aibvc_athletes_fetch( $genere ) {

    $curl = curl_init();
        curl_setopt($curl, CURLOPT_HTTPGET, 1);
        curl_setopt($curl, CURLOPT_URL, "http://aibvcapi.azurewebsites.net/api/v1/gestionale/GetClassifica/" . strtoupper($genere));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            'Content-Type' => 'application/json',
        ]);

        $rawResponse = curl_exec($curl);
        curl_close($curl);

        $collection = json_decode($rawResponse, true);
        return $collection;

}
