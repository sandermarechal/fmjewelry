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
 * The Permission model
 */
class Permission extends AppModel
{
	/** @var array Permissions belong to Groups */
	public $belongsTo = array('Group');
}

?>
