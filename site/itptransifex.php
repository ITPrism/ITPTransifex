<?php
/**
 * @package      ITPTransifex
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2014 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

jimport("itprism.init");
jimport("itptransifex.init");

$controller = JControllerLegacy::getInstance('ItpTransifex');
$controller->execute(JFactory::getApplication()->input->getCmd('task'));
$controller->redirect();
