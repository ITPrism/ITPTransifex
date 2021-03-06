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

/**
 * This model provides functionality for managing user resource.
 *
 * @package      ITPTransifex
 * @subpackage   Components
 */
class ItpTransifexModelResource extends JModelAdmin
{
    /**
     * Returns a reference to the a Table object, always creating it.
     *
     * @param   string   $type The table type to instantiate
     * @param   string $prefix A prefix for the table class name. Optional.
     * @param   array  $config Configuration array for model. Optional.
     *
     * @return  JTable  A database object
     * @since   1.6
     */
    public function getTable($type = 'Resource', $prefix = 'ItpTransifexTable', $config = array())
    {
        return JTable::getInstance($type, $prefix, $config);
    }

    /**
     * Method to get the record form.
     *
     * @param   array   $data     An optional array of data for the form to interrogate.
     * @param   boolean $loadData True if the form is to load its own data (default case), false if not.
     *
     * @return  JForm|bool   A JForm object on success, false on failure
     * @since   1.6
     */
    public function getForm($data = array(), $loadData = true)
    {
        // Get the form.
        $form = $this->loadForm($this->option . '.resource', 'resource', array('control' => 'jform', 'load_data' => $loadData));
        if (!$form) {
            return false;
        }

        return $form;
    }

    /**
     * Method to get the data that should be injected in the form.
     *
     * @return  mixed   The data for the form.
     * @since   1.6
     */
    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.resource.data', array());

        if (!$data) {
            $data = $this->getItem();
        }

        return $data;
    }

    /**
     * Save data into the DB
     *
     * @param array $data   The data about item
     *
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @return   int  Item ID
     */
    public function save($data)
    {
        $id        = Joomla\Utilities\ArrayHelper::getValue($data, 'id');
        $name      = Joomla\Utilities\ArrayHelper::getValue($data, 'name');
        $alias     = Joomla\Utilities\ArrayHelper::getValue($data, 'alias');
        $filename  = Joomla\Utilities\ArrayHelper::getValue($data, 'filename');
        $category  = Joomla\Utilities\ArrayHelper::getValue($data, 'category');
        $source    = Joomla\Utilities\ArrayHelper::getValue($data, 'source');
        $path      = Joomla\Utilities\ArrayHelper::getValue($data, 'path');
        $published = Joomla\Utilities\ArrayHelper::getValue($data, 'published');

        // Load a record from the database
        $row = $this->getTable();
        $row->load($id);

        $row->set('name', $name);
        $row->set('alias', $alias);
        $row->set('filename', $filename);
        $row->set('category', $category);
        $row->set('path', $path);
        $row->set('source', $source);
        $row->set('published', $published);

        $this->prepareTable($row);

        $row->store(true);

        return $row->get('id');
    }

    /**
     * Save the filename of the resource.
     *
     * @param   int  $id
     * @param   string $filename
     */
    public function saveFilename($id, $filename)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query
            ->update($db->quoteName('#__itptfx_resources'))
            ->set($db->quoteName('filename') . '=' .$db->quote($filename))
            ->where($db->quoteName('id') .'='. (int)$id);

        $db->setQuery($query);
        $db->execute();
    }

    /**
     * Save the category of the resource.
     *
     * @param   int  $id
     * @param   string $type
     */
    public function saveCategory($id, $type)
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query
            ->update($db->quoteName('#__itptfx_resources'))
            ->set($db->quoteName('category') . '=' .$db->quote($type))
            ->where($db->quoteName('id') .'='. (int)$id);

        $db->setQuery($query);
        $db->execute();
    }

    protected function prepareTable($table)
    {
        // Fix magic quotes
        if (get_magic_quotes_gpc()) {
            $table->set('name', stripcslashes($table->get('name')));
        }
    }

    public function synchronize($pks, $options)
    {
        $db = $this->getDbo();

        // Prepare project folder
        $query = $db->getQuery(true);
        $query
            ->select(
                'a.id, a.name, a.alias, a.filename, a.type, a.i18n_type, ' .
                'a.source_language_code, a.project_id, ' .
                'b.alias AS project_slug'
            )
            ->from($db->quoteName('#__itptfx_resources', 'a'))
            ->leftJoin($db->quoteName('#__itptfx_projects', 'b') . ' ON a.project_id = b.id')
            ->where('a.id IN (' . implode(',', $pks) . ')');

        $db->setQuery($query);
        $resources = (array)$db->loadObjectList();

        if (count($resources) > 0) {
            $transifexUrl = Joomla\Utilities\ArrayHelper::getValue($options, 'url');

            $transifex = new Prism\Transifex\Request($transifexUrl);

            $transifex->setUsername($options['username']);
            $transifex->setPassword($options['password']);
            $transifex->enableAuthentication();

            $options = array(
                'headers' => array(
                    'Content-type: application/json',
                    'X-HTTP-Method-Override: GET'
                )
            );

            foreach ($resources as $resource) {
                $uri      = 'project/' . $resource->project_slug . '/resource/' . $resource->alias;
                $response = $transifex->get($uri, $options);

                // Store the data
                if (!empty($response->slug)) {
                    $query = $db->getQuery(true);
                    $query
                        ->update($db->quoteName('#__itptfx_resources'))
                        ->set($db->quoteName('name') . '=' . $db->quote($response->name))
                        ->set($db->quoteName('source_language_code') . '=' . $db->quote($response->source_language_code))
                        ->set($db->quoteName('i18n_type') . '=' . $db->quote($response->i18n_type))
                        ->where($db->quoteName('alias') . '=' . $db->quote($resource->alias));

                    $db->setQuery($query);
                    $db->execute();
                }
            }
        }
    }
}
