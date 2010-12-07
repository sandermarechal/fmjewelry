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
 * The PartOption Model
 */
class PartOption extends AppModel
{
	/** @var array PartOptions belong to Parts */
	public $belongsTo = array('Part');

	/** @var array Part validation rules */
	public $validate = array(
		'name' => 'notEmpty',
		'price' => array(
			'type'   => array('rule' => array('decimal', 2)),
			'length' => array('rule' => array('between', 4, 9)),
		),
	);

	/**
	 * Set the part option as the default option
	 * 
	 * @param string $id The option ID
	 * @return boolean Success
	 */
	public function setDefault($id = null)
	{
		if ($id == null) {
			$id = $this->id;
		}

		$option = $this->find('first', array('conditions' => array(
			'PartOption.id' => $id
		)));

		if (!$option) {
			return false;
		}

		$this->updateAll(
			array('PartOption.default' => 0),
			array('PartOption.part_id' => $option['PartOption']['part_id'])
		);

		$option['PartOption']['default'] = 1;
		return (bool) $this->save($option);
	}

	/**
	 * If this is the first part option, make it default
	 * @return boolean true
	 */
	public function beforeSave()
	{
		$count = $this->find('count', array(
			'conditions' => array(
				'PartOption.part_id' => $this->data['PartOption']['part_id'],
				'PartOption.default' => true,
			),
		));

		if ($count == 0) {
			$this->data['PartOption']['default'] = true;
		}

		return true;
	}
}

?>
