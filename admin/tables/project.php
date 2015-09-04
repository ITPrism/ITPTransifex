<?php
/**
 * @package      ITPTransifex
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

class ItpTransifexTableProject extends JTable
{
    public function __construct($db)
    {
        parent::__construct('#__itptfx_projects', 'id', $db);
    }
}
