<?php

namespace JB\SPW;

class Helpers {

  public static function get_template_path($template_filename) {
    $templates_path = plugin_dir_path(dirname(__FILE__)) . "templates";
    $template_path = "$templates_path/$template_filename";

    return $template_path;
  }

  public static function build_sidebar_link($post) {
    $name = $post->post_title;
    $url = get_permalink($post->ID);

    return "<a href=\"$url\">$name</a>";
  }

  public function check_if_parent_page($page) {
    if((wp_get_post_parent_id( $post_ID ) == $page->ID) || ((get_the_ID() == $page->ID) && (wp_get_post_parent_id( $post_ID ) == 0 ) ) ) {
      return ' mole-sidebar-parent ';
    }else if(get_the_ID() == $page->ID){
      return ' mole-sidebar-active ' ;
    }
  }

}

