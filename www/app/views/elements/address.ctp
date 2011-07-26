<?php
	if (!isset($address['Address'])) {
		$address = array('Address' => $address);
	}

	echo $address['Address']['name'] . '<br />';
	echo $address['Address']['address_1'] . '<br />';
	ife($address['Address']['address_2'], $address['Address']['address_2'] . '<br />', '');
	echo $address['Address']['postal_code'] . ' ';
	echo $address['Address']['city'] . '<br />';
	echo strtoupper($address['Address']['country']);
?>
