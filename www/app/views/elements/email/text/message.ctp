Hello,

The following message was sent from the website of
Full Metal Jewelry via IP address <?php echo $ipAddress;?>:

<?php

foreach ($data as $field => $value) {
	echo "$field\n";
	echo str_repeat('-', strlen($field)) . "\n";
	echo wordwrap($value) . "\n\n";
}

?>

Kind regards,

-- 
Full Metal Jewelry
