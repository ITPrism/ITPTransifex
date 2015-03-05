<?php
/**
 * @package      ItpTransifex
 * @subpackage   Components
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2015 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;
?>
<div class="itptfx-project<?php echo $this->pageclass_sfx; ?>">
    <?php if ($this->params->get('show_page_heading', 1)) { ?>
        <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
    <?php } ?>
    <?php
    $layout      = new JLayoutFile('project', $this->layoutsBasePath);
    echo $layout->render($this->layoutData);
    ?>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>
                    <?php echo JText::_("COM_ITPTRANSIFEX_LANGUAGE"); ?>
                </th>
                <th>&nbsp;</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($this->items as $item) {
            $number = (!isset($this->packagesNumber[$item->code])) ? 0 : (int)$this->packagesNumber[$item->code]["number"];
            ?>
            <tr>
                <td class="js-prj-title<?php echo $item->id; ?> has-context">
                    <?php echo $this->escape($item->name); ?> (<?php echo $this->escape($item->code); ?>)
                    <div class="font-xsmall">
                        <a href="<?php echo JRoute::_(ItpTransifexHelperRoute::getPackagesRoute($this->project->getSlug(), $item->code));?>">
                            <?php echo JText::sprintf("COM_ITPTRANSIFEX_PACKAGES_D", $number)?>
                        </a>
                    </div>
                </td>
                <td>
                    <button class="btn btn-primary js-prj-btn-download" data-language="<?php echo $this->escape($item->code); ?>">
                        <span class="glyphicon glyphicon-download" aria-hidden="true"></span>
                        <?php echo JText::_("COM_ITPTRANSIFEX_DOWNLOAD"); ?>
                    </button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>

    <?php if (($this->params->def('show_pagination', 1) == 1 || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) { ?>
        <div class="pagination">
            <?php if ($this->params->def('show_pagination_results', 1)) { ?>
                <p class="counter pull-right"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
            <?php } ?>
            <?php echo $this->pagination->getPagesLinks(); ?> </div>
    <?php } ?>

</div>
<div class="clearfix">&nbsp;</div>
<?php
if (!$this->params->get("enable_captcha", 0)) {
    echo $this->loadTemplate("nocaptcha");
} else {
    echo $this->loadTemplate("captcha");
}