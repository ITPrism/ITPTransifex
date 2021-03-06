<?php
/**
 * @package      ITPTransifex
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

class ItpTransifexViewExport extends JViewLegacy
{
    /**
     * @var JDocumentHtml
     */
    public $document;

    /**
     * @var Joomla\Registry\Registry
     */
    protected $state;

    protected $items;
    protected $pagination;

    protected $numberOfPackages;
    protected $languages;

    protected $option;

    protected $listOrder;
    protected $listDirn;
    protected $saveOrder;
    protected $saveOrderingUrl;
    protected $sortFields;

    protected $sidebar;

    public function display($tpl = null)
    {
        $this->option     = JFactory::getApplication()->input->get('option');
        $this->state      = $this->get('State');
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');

        // Get projects IDs
        $ids = array();
        foreach ($this->items as $item) {
            $ids[] = $item->id;
        }

        // Get number of packages.
        $projects = new Transifex\Project\Projects(JFactory::getDbo());
        $this->numberOfPackages = $projects->getNumberOfPackages($ids);

        $languages = new Transifex\Language\Languages(JFactory::getDbo());
        $languages->load();

        $this->languages = $languages->toOptions('locale', 'name');

        // Prepare sorting data
        $this->prepareSorting();

        // Prepare actions
        $this->addToolbar();
        $this->addSidebar();
        $this->setDocument();

        parent::display($tpl);
    }

    /**
     * Prepare sortable fields, sort values and filters.
     */
    protected function prepareSorting()
    {
        // Prepare filters
        $this->listOrder = $this->escape($this->state->get('list.ordering'));
        $this->listDirn  = $this->escape($this->state->get('list.direction'));
        $this->saveOrder = (strcmp($this->listOrder, 'a.ordering') === 0);

        $this->sortFields = array(
            'a.name' => JText::_('COM_ITPTRANSIFEX_NAME'),
            'a.id'   => JText::_('JGRID_HEADING_ID')
        );
    }

    /**
     * Add a menu on the sidebar of page
     */
    protected function addSidebar()
    {
        ItpTransifexHelper::addSubmenu($this->getName());
        $this->sidebar = JHtmlSidebar::render();
    }

    /**
     * Add the page title and toolbar.
     *
     * @since   1.6
     */
    protected function addToolbar()
    {
        // Set toolbar items for the page
        JToolbarHelper::title(JText::_('COM_ITPTRANSIFEX_IMPORT_EXPORT_MANAGER'));

        // Add custom buttons
        $bar = JToolbar::getInstance('toolbar');

        // Import
        $link = JRoute::_('index.php?option=com_itptransifex&view=import');
        $bar->appendButton('Link', 'unarchive', JText::_('COM_ITPTRANSIFEX_IMPORT'), $link);

        // Export
        JToolbarHelper::custom('export.download', 'archive', '', JText::_('COM_ITPTRANSIFEX_EXPORT'), false);

        JToolbarHelper::divider();
        JToolbarHelper::custom('export.backToDashboard', 'dashboard', '', JText::_('COM_ITPTRANSIFEX_BACK_DASHBOARD'), false);
    }

    /**
     * Method to set up the document properties
     *
     * @return void
     */
    protected function setDocument()
    {
        $this->document->setTitle(JText::_('COM_ITPTRANSIFEX_IMPORT_EXPORT_MANAGER'));

        // Load language string in JavaScript
        JText::script('COM_ITPTRANSIFEX_PLEASE_MAKE_A_SELECTION_FROM_THE_LIST');

        // Scripts
        JHtml::_('behavior.multiselect');
        JHtml::_('formbehavior.chosen', '#sortTable, #directionTable, #limit');
        JHtml::_('bootstrap.tooltip');

        JHtml::_('Prism.ui.joomlaList');

        $this->document->addScript('../media/' . $this->option . '/js/admin/' . strtolower($this->getName()) . '.js');
    }
}
