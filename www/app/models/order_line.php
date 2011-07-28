<?php
/**
 * Full Metal Jewelry website
 * Copyright (C) 2011 Stichting Lone Wolves
 * Written by Sander Marechal <s.marechal@jejik.com>
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

/**
 * The OrderLine Model
 */
class OrderLine extends AppModel
{
    /** @var array The model behaviors */
    public $actsAs = array('Containable');

    /** @var array Many-to-one relations */
    public $belongsTo = array('Order');


    /**
     * Recalculate the correct prices from the price parts
     * @param $id The OrderLine ID
     */
    public function setPrice($id = null)
    {
        if (!$id) {
            $id = $this->id;
        }

        $product  = $this->read(null, $id);
        $price    = $product['OrderLine']['price'];
        $quantity = $product['OrderLine']['quantity'];

        $this->create(false);
        $this->save(array('OrderLine' => array(
            'id' => $id,
            'price_total' => bcmul($price, $quantity, 2),
        )));
    }

    /**
     * Update the price_piece and price_total fields
     * return @boolean Continue with the update
     */
    public function beforeSave()
    {
        if (empty($this->data['OrderLine']['price']) && empty($this->data['OrderLine']['quantity'])) {
            return true;
        }

        if ($this->id) {
            $product = $this->find('first', array(
                'contain' => array(),
                'conditions' => array('OrderLine.id' => $this->id),
            ));

            $price = isset($this->data['OrderLine']['price'])
                ? $this->data['OrderLine']['price']
                : $product['OrderLine']['price'];
            $quantity = isset($this->data['OrderLine']['quantity'])
                ? $this->data['OrderLine']['quantity']
                : $product['OrderLine']['quantity'];

            $this->data['OrderLine']['price_total'] = bcmul($price, $quantity, 2);

            return true;
        }

        if (empty($this->data['OrderLine']['price'])) {
            $this->data['OrderLine']['price'] = '0.00';
        }
    
        if (empty($this->data['OrderLine']['quantity'])) {
            $this->data['OrderLine']['quantity'] = 1;
        }

        $this->data['OrderLine']['price_piece'] = $this->data['OrderLine']['price'];
        $this->data['OrderLine']['price_total'] = bcmul($this->data['OrderLine']['price'], $this->data['OrderLine']['quantity'], 2);

        return true;
    }

    /**
     * Update Order.price after a save
     * @param boolean $created True when a new OrderLine was created
     */
    public function afterSave($created)
    {
        if ($order_id = $this->field('order_id')) {
            $this->Order->setPrice($order_id);
        }
    }

    /**
     * When deleting, update Order.price
     * @param boolean $cascade Whether the deletion will cascade to dependent models
     */
    public function beforeDelete($cascade)
    {
        if ($order_id = $this->field('order_id')) {
            $this->Order->setPrice($order_id, $this->id);
        }

        return true;
    }
}

?>
