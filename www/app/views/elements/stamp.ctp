<?php

$fields = array('invoiced', 'paid', 'shipped');
$settable = true;

foreach ($fields as $f) {
	if ($field == $f) {
		if ($order['Order'][$field] != '0000-00-00 00:00:00') {
			echo $this->action == 'admin_view' ? $order['Order'][$field] : substr($order['Order'][$field], 0, 10);
			break;
		}

		if ($this->action == 'view' || !$settable) {
			echo '-';
			break;
		}

		echo $form->create('Order', array('url' => array('action' => 'stamp'), 'class' => 'stamp'));
		echo $form->input('id', array('default' => $order['Order']['id']));
		echo $form->input($field, array(
			'default' => $order['Order'][$field],
			'div' => false,
			'label' => false,
			'dateFormat' => 'YMD',
			'timeFormat' => 24,
			'interval' => 15,
		));
		echo '&nbsp;';
		echo $form->submit('Set', array('div' => false));
		echo $form->end();

		break;
	} 
	
	if ($order['Order'][$f] == '0000-00-00 00:00:00') {
		$settable = false;
	}
}

?>
