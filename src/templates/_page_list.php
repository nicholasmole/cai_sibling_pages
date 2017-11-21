<?php

use JB\SPW\Helpers;

?>

<div id="spw-page-list" class="spw-sidebar-object-div">
  <ul class="spw-sidebar-object-ul">
    <?php foreach ($pages as $page): ?>
    <li class="spw-sidebar-object <?php echo Helpers::check_if_parent_page($page) ?>" >
         <?php echo Helpers::build_sidebar_link($page) ?>
    </li>
    <?php endforeach; ?>
  </ul>
</div>

