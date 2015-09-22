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
$project     = $displayData["project"];
?>
<div class="row">
    <div class="col-md-3">
        <?php if (!$project->getImage()) { ?>
            <img src="<?php echo "media/com_itptransifex/images/no_image.png"; ?>"
                 alt="<?php echo $displayData["clean_title"]; ?>" width="200"
                 height="200" />
        <?php } else { ?>
            <img src="<?php echo $displayData["images_folder"]."/".$project->getImage(); ?>" alt="<?php echo $displayData["clean_title"]; ?>" width="<?php echo $displayData["image_width"]; ?>" height="<?php echo $displayData["image_height"]; ?>"/>
        <?php } ?>
    </div>
    <div class="col-md-9">
        <?php
            echo "<".$displayData["h_tag"].">".$displayData["clean_title"]."</".$displayData["h_tag"].">";
        ?>

        <p><?php echo $this->escape($project->getDescription()); ?></p>

        <?php if ($project->getLink()) {?>
        <a href="<?php echo $project->getLink(); ?>" class="btn btn-default" target="_blank">
            <span class="fa fa-link" aria-hidden="true"></span>
            <?php echo JText::sprintf("COM_ITPTRANSIFEX_TRANSLATE_S", $displayData["clean_title"]); ?>
        </a>
        <?php } ?>
    </div>
</div>