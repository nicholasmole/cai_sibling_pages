<?php

namespace JB\SPW;

use JB\SPW\Constants;
use JB\SPW\Helpers;

class SiblingPages extends \WP_Widget {

  public function __construct() {
    $args = array(
      Constants::$ID,
      Constants::$UI_NAME,
      array('description' => Constants::$DESCRIPTION)
    );

    parent::__construct(...$args);
  }

  public function widget($args, $instance) {
    echo $args['before_widget'];

    //checks show_parent
    $show_parent = $instance['show_parent'] ? true : false;
    $list_options = array(
      'apex' => $show_parent
    );

    $list = new PageList($list_options);
    $list->render();

    echo $args['after_widget'];
  }

  public function form($instance) {
    $template = Helpers::get_template_path('_widget_options.php');

    $title = $this->maybe($instance, 'title');
    $title_id = esc_attr($this->get_field_id('title'));
    $title_name = esc_attr($this->get_field_name('title'));

    $show_parent_id = esc_attr($this->get_field_id('show_parent'));
    $show_parent_name = esc_attr($this->get_field_name('show_parent'));
    $show_parent_checked = $instance['show_parent'] ? "checked" : "";

    ob_start();
    include $template;
    $output = ob_get_contents();
    ob_end_clean();

    echo $output;
  }

  public function update($new_instance, $old_instance) {
    $instance = $old_instance;

    $instance['title'] = $this->maybe($new_instance, 'title');
    $instance['show_parent'] = $new_instance['show_parent'];

    return $instance;
  }

  /**
   * Returns the value if the key is set, or the empty string if not.
   */
  private function maybe($instance, $key) {
    $value = "";

    if (!empty($instance[$key])) {
      $value = strip_tags($instance[$key]);
    }

    return $value;
  }

}

