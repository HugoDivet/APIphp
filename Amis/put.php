<?php
$url = "http://http://localhost:63342/api/ami/1"; // modifier le produit 1
$data = array('utilisateur' => '1', 'ami' => '1');
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS,http_build_query($data));
$response = curl_exec($ch);
var_dump($response);
if (!$response)
{
    return false;
}
?>