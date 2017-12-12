<?php

namespace JB\SPW;

class Helpers {

  private static $currentID = 0;

  public function set_currentID($value){

    self::$currentID = $value;
  }
  public function get_currentID(){

    return self::$currentID;
  }

  public static function get_template_path($template_filename) {
    $templates_path = plugin_dir_path(dirname(__FILE__)) . "templates";
    $template_path = "$templates_path/$template_filename";

    return $template_path;
  } 

  public static function build_sidebar_link($post) {
    $name = $post->post_title;
    //Check news
    if($name=="Ponderosa News" ||$name=="ProVisions News"||$name=="JMAS News" ||$name=="Seasoft News"  ):
       $name = "News";
    endif;
    $url = get_permalink($post->ID);

    return "<a href=\"$url\">$name</a>";
  }

  //Gets number of Ancestors for post
  //Used for Tracking Second Tier Parent at Any Tier
  //Offset used when value is used in array ie. get_id_of_second_tier_parent
  public function get_num_of_parents($current_post, $offset){
    return sizeof( get_post_ancestors( $current_post ) ) - $offset;
  }

  //Gets ID of Parent 
  //Figures out Parent at level based on tier level of current_post
  public function get_id_of_second_tier_parent($current_post,$offset){
    return get_post_ancestors( $current_post )[self::get_num_of_parents($current_post,$offset)];
  }

  
  public function check_if_parent_page($page) {
    //check if parent || else if active level 
    if(self::get_id_of_second_tier_parent($page ,1 ) == $page->ID ){
      return ' spw-sidebar-parent ' ;
    }else if(self::get_currentID() == $page->ID){
      return ' spw-sidebar-active ' ;
    }
  }

  public function check_if_parent_is_active($page) {

    //If passed post == ID of current page
    if( ($page->ID == self::get_currentID()) ){
      //if this is the 5th Tier Child or 4th tier parent
      if((self::get_num_of_parents($page, 1) > 1)){
        return 'spw-child i-am-this-page ';
      }else{
        return ' i-am-this-page ';
      }
    //If this is the 5th Child && it shouldn't be there because parent isn;t active 
    }else if ((self::get_num_of_parents($page, 1) > 1) && !(wp_get_post_parent_id($page->ID) == self::get_currentID()) ){
      return ' spw-display-remove '; 
    //if this is the 5th Tier Child Sibling Identified by the 4th Tier Parent
    }else if( wp_get_post_parent_id($page->ID) == self::get_currentID()  ){
      
      if((self::get_num_of_parents($page, 1) > 1)){
        return 'spw-child i-am-this-child ';
      }else{
        return ' i-am-this-child ';
      }
    //This passed Post is The parent of the current page
    }else if( ($page->ID == self::get_id_of_second_tier_parent(self::get_currentID() , 2 )) ){
      return ' my-parent-page ';
    //This parent of the passed post is at the level of 2nd Tier 
    }else if( wp_get_post_parent_id($page) == self::get_id_of_second_tier_parent(self::get_currentID(),2 )){
      return ' page-above-this-page ';
    //Else remove because of how the Children are all called in multiple unnessary branches are dragged down
    }else{
      return ' spw-display-remove '; 
    }
  }

}

