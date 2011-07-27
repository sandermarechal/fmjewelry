<?php
/**
 * Feral Penguin Pty Ltd website
 * Copyright (C) 2009 Stichting Lone Wolves
 * Written by Sander Marechal <s.marechal@jejik.com>
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

/**
 * The Order Model
 */
class Order extends AppModel
{
    /** The behaviors for this model */
    public $actsAs = array('Containable');

    /** @var array One-to-many relationships */
    public $hasMany = array('OrderLine' => array('dependent' => true));

    /** Many-to-one relationships */
    public $belongsTo = array(
        'User',
        'ShippingAddress' => array(
            'className' => 'Address',
            'foreignKey' => 'shipping_address_id',
        ),
        'BillingAddress' => array(
            'className' => 'Address',
            'foreignKey' => 'billing_address_id',
        ),
    );

    /**
     * Determine whether an address is used on an order.
     * Used to make CoW addresses
     * @param string $address_id The address ID
     * @return boolean True if the address is used. False otherwise
     */
    public function hasAddress($address_id)
    {
        $count = $this->find('count', array(
            'contain' => array(),
            'conditions' => array('or' => array(
                'Order.shipping_address_id' => $address_id,
                'Order.billing_address_id' => $address_id,
            )),
        ));

        return $count > 0;
    }

    /**
     * Calculate the total order price based off the products
     * @param string $id The order ID
     * @param string $skip_product Do not count this product when calculating the price
     * @return string the price
     */
    public function setPrice($id = null, $skip_product = null)
    {
        if (!$id) {
            $id = $this->id;
        }

        $order = $this->read(null, $id);
        $price = '0.00';

        foreach($order['OrderLine'] as $product) {
            if ($product['id'] != $skip_product) {
                $price = bcadd($price, $product['price_total'], 2);
            }
        }

        $this->saveField('price', $price);
        return $price;
    }

    /**
     * Set a datetime field
     * @param string $field The field name
     * @param string $datetime The datetime or null for now
     * @return boolean Success
     */
    private function stamp($field, $datetime = null)
    {
        if ($datetime === null) {
            $datetime = date('Y-m-d H:i:s');
        }

        return $this->saveField($field, $datetime);
    }

    /**
     * Set the invoiced field
     * @param string $datetime The datetime or null for now
     * @return boolean Success
     */
    public function setInvoiced($datetime = null)
    {
        return $this->stamp('invoiced', $datetime);
    }

    /**
     * Set the paid field
     * @param string $datetime The datetime or null for now
     * @return boolean Success
     */
    public function setPaid($datetime = null)
    {
        return $this->stamp('paid', $datetime);
    }

    /**
     * Set the ordered field
     * @param string $datetime The datetime or null for now
     * @return boolean Success
     */
    public function setOrdered($datetime = null)
    {
        return $this->stamp('ordered', $datetime);
    }

    /**
     * Set the shipped field
     * @param string $datetime The datetime or null for now
     * @return boolean Success
     */
    public function setShipped($datetime = null)
    {
        return $this->stamp('shipped', $datetime);
    }
}

?>
