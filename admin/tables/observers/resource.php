<?php
/**
 * @package      ITPTransifex
 * @subpackage   Component
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */
defined('JPATH_PLATFORM') or die;

/**
 * Abstract class defining methods that can be
 * implemented by an Observer class of a JTable class (which is an Observable).
 * Attaches $this Observer to the $table in the constructor.
 * The classes extending this class should not be instanciated directly, as they
 * are automatically instanciated by the JObserverMapper
 *
 * @package      UserIdeas
 * @subpackage   Component
 * @link         http://docs.joomla.org/JTableObserver
 * @since        3.1.2
 */
class ItpTransifexObserverResource extends JTableObserver
{
    /**
     * The pattern for this table's TypeAlias
     *
     * @var    string
     * @since  3.1.2
     */
    protected $typeAliasPattern = null;

    /**
     * Creates the associated observer instance and attaches it to the $observableObject
     * $typeAlias can be of the form "{variableName}.type", automatically replacing {variableName} with table-instance variables variableName
     *
     * @param   JObservableInterface $observableObject The subject object to be observed
     * @param   array                $params           ( 'typeAlias' => $typeAlias )
     *
     * @return  ItpTransifexObserverResource
     *
     * @since   3.1.2
     */
    public static function createObserver(JObservableInterface $observableObject, $params = array())
    {

        $observer = new self($observableObject);

        $observer->typeAliasPattern = Joomla\Utilities\ArrayHelper::getValue($params, 'typeAlias');

        return $observer;
    }

    /**
     * Pre-processor for $table->delete($pk)
     *
     * @param   mixed $pk An optional primary key value to delete.  If not set the instance property value is used.
     *
     * @return  void
     *
     * @since   3.1.2
     * @throws  UnexpectedValueException
     */
    public function onBeforeDelete($pk)
    {
        if (is_array($pk)) {
            $pk = Joomla\Utilities\ArrayHelper::toInteger($pk);
        }

        if (is_array($pk) and !empty($pk)) {
            $db = JFactory::getDbo();

            $query = $db->getQuery(true);
            $query
                ->delete($db->quoteName("#__itptfx_packages_map"))
                ->where($db->quoteName("resource_id") . " IN ( " . implode(",", $pk) . ")");

            $db->setQuery($query);
            $db->execute();
        }
    }
}
