<?php

use JB\SPW\Helpers;

?>

<div id="spw-page-list" class="mole-sidebar-object-div">
  <ul class="mole-sidebar-object-ul">
    <?php foreach ($pages as $page): ?>
    <li class="mole-sidebar-object <?php 
      if((wp_get_post_parent_id( $post_ID ) == $page->ID) || ((get_the_ID() == $page->ID) && (wp_get_post_parent_id( $post_ID ) == 0 ) ) ) { echo ' mole-sidebar-parent ';}else if(get_the_ID() == $page->ID){ echo ' mole-sidebar-active ' ;} ?>" >
         <?php echo Helpers::build_sidebar_link($page) ?></li>
    <?php endforeach; ?>
  </ul>
</div>

