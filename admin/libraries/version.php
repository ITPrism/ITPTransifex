<?php
/**
 * @package      ITPTransifex
 * @subpackage   Libraries
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2013 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

defined('JPATH_PLATFORM') or die;

/**
 * Version information
 *
 * @package 	 ITPTransifex
 * @subpackage   Libraries
 */
class ItpTransifexVersion {
	
    /**
     * Extension name
     * 
     * @var string
     */
    public $product    = 'ITP Transifex';
    
    /**
     * Main Release Level
     * 
     * @var integer
     */
    public $release    = '1';
    
    /**
     * Sub Release Level
     * 
     * @var integer
     */
    public $devLevel  = '0';
    
    /**
     * Release Type
     * 
     * @var integer
     */
    public $releaseType  = 'Lite';
    
    /**
     * Development Status
     * 
     * @var string
     */
    public $devStatus = 'Stable';
    
    /**
     * Date
     * 
     * @var string
     */
    public $releaseDate= '05-October-2013';
    
    /**
     * License
     * 
     * @var string
     */
    public $license  = '<a href="http://www.gnu.org/copyleft/gpl.html" target="_blank">GNU/GPL</a>';
    
    /**
     * Copyright Text
     * 
     * @var string
     */
    public $copyright  = '&copy; 2013 ITPrism. All rights reserved.';
    
    /**
     * URL
     * 
     * @var string
     */
    public $url        = '<a href="#" target="_blank">ITPTransifex</a>';

    /**
     * Backlink
     * 
     * @var string
     */
    public $backlink   = '<div style="width:100%; text-align: left; font-size: xx-small; margin-top: 10px;"><a href="#" target="_blank">Joomla! ITPTransifex</a></div>';
    
    /**
     * Developer
     * 
     * @var string
     */
    public $developer  = '<a href="http://itprism.com" target="_blank">ITPrism</a>';
    
    /**
     *  Build long format of the verion text
     *
     * @return string Long format vpversion
     */
    public function getLongVersion() {
        
    	return 
    	   $this->product .' '. $this->release .'.'. $this->devLevel .' ' . 
    	   $this->devStatus . ' '. $this->releaseDate;
    }

    /**
     *  Build long format of the verion text
     *
     * @return string Long format version
     */
    public function getMediumVersion() {
        
    	return 
    	   $this->release .'.'. $this->devLevel .' ' . 
    	   $this->releaseType . ' ( ' .$this->devStatus . ' )';
    } 
    
    /**
     *  Build short format of the vpversion text
     *
     * @return string Short vpversion format
     */
    public function getShortVersion() {
        return $this->release .'.'. $this->devLevel;
    }

}