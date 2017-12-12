<?php

use JB\SPW\Helpers;

?>

<div id="spw-page-list" class="spw-sidebar-object-div">
  <ul class="spw-sidebar-object-ul">
    <?php foreach ($pages as $page): ?>
      <?php if (Helpers::check_if_parent_is_active($page) != ' spw-display-remove '): ?>
        <li class="spw-sidebar-object <?php echo Helpers::check_if_parent_page($page); echo Helpers::check_if_parent_is_active($page); ?>" >
        
            <?php echo Helpers::build_sidebar_link($page) ?>
        </li>
      <?php endif; ?>
    <?php endforeach; ?>
  </ul>
</div>

