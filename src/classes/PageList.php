<?php

namespace JB\SPW;

use JB\SPW\Helpers;

class PageList {

  private $origin;
  private $include_apex = false;
  //private $currentID;

  public function __construct($list_options) {
    $this->origin = $this->find_origin();
    $this->include_apex = $list_options['apex'];
  }

  public function get_origin() {
    return $this->origin;
  }

  public function render() {
    $template = Helpers::get_template_path('_page_list.php');
    $pages = $this->find_sibling_pages();

    ob_start();
    
    include $template;
    
    $output = ob_get_contents();
    ob_end_clean();

    echo $output;
  }

  private function find_origin() {
    $post = $origin = get_post(get_the_ID());

    if ($post->post_parent) {
      $ancestors = get_post_ancestors($post->ID);
      $origin_index = count($ancestors) - 1;
      $origin = $ancestors[$origin_index];
    }

    return $origin;
  }

  private function find_sibling_pages() {
    $pages = $this->find_child_pages();
    
    return $pages;
  }

  //Checks to see if this page has children
  //Used when trying to see if this is the bottom most tier
  private function find_children_array($current_post){
    return get_pages( array( 'child_of' => $current_post->ID ) );
  }

  //Checks to see if Parents Should Be Kept
  //Since Call for Parents, and children vary due to need for 3rd Tier to be dominate
  //  menu for anything below. This is a fix to 2nd Tier Being added to Page 
  //  when is shouldn't be there.
  private function find_keep_parent($current_post){
    $keepParent = TRUE;
    $keepParent = get_post_ancestors( $current_post->post_parent ) ? FALSE : TRUE;
    if ( is_page() && $current_post->post_parent && count($this->find_children_array($current_post) ) > 0 ) {
      $keepParent = FALSE;
    }
    return $keepParent;
  }

  //Gets number of Ancestors for post
  //Used for Tracking Second Tier Parent at Any Tier
  //Offset used when value is used in array ie. get_id_of_second_tier_parent
  private function get_num_of_parents($current_post, $offset){
    return sizeof( get_post_ancestors( $current_post ) ) - $offset;
  }

  //Gets ID of Parent 
  //Figures out Parent at level based on tier level of current_post
  private function get_id_of_second_tier_parent($current_post,$offset){
    return get_post_ancestors( $current_post )[$this->get_num_of_parents($current_post,$offset)];
  }
  
  // Find out where to collect the Arguments
  private function find_args($current_post){
    //if - 2nd Tier Parent
    //Result: Print out 2nd Tiers Children
    //Else if - InBetween 2nd tier and Lowest Tier
    //Result: Get Children of 2nd tier Parent - by figuring out association of current post
    //Else - 1nd tier Parent
    //Result: Gets 1st Tiers Child
   
    
    //SINGLE PAGE IS TRUE 
    if(is_single()){
      //Get the slug for the category
      $slugGetter = get_the_category( $current_post );
      $slugGetter = $slugGetter[0]->slug;
      
      //Get URL
      
      $url = $homeurl."/solutions/".$slugGetter."/news";
      $url = str_replace("/news-events","",$url);
      //GETS POST FROM URL
      $post_id_url =  url_to_postid($url);
      $get_post = get_post(  $post_id_url );
      //Overwrites current post if single Page
      $current_post = $get_post;
      
      Helpers::set_currentID($current_post->ID);

    }

    if (Helpers::get_num_of_parents($current_post, 0) == 1) {
      $args = array(
        'child_of' => $current_post->ID,
        'sort_column' => 'menu_order',
      );
    }else if (get_post_ancestors( $current_post )) {
      $args = array(
        'child_of' => Helpers::get_id_of_second_tier_parent($current_post, 1),
        'sort_column' => 'menu_order',
      );
    } else {
      $args = array(
        'parent' => $current_post->ID,
        'hierarchical' => 1,
        'sort_column' => 'menu_order',
      );
    }
   
    return $args;
    
  }

  //Add This Page into the array of $pages
  //Gets removed due to need to keep 3rd Tier as Dominate Menu starting point
  //  even when going down further levels
  private function get_add_self_page($current_post){
    //if has parents && children is 0 (so not lowest or highest Tier)
    if (get_post_ancestors( $current_post ) && (count($this->find_children_array($current_post) ) != 0)) {
      if( (Helpers::get_num_of_parents(Helpers::get_currentID(), 2) < 0) ){ return TRUE; }
    }
    return FALSE;
  }


  private function find_child_pages() {
    
    global $post;
    //Sets the $currentID
    Helpers::set_currentID($post->ID); // $currentID = $post->ID;
    //1 : Get Pages
    $pages = get_pages($this->find_args($post));
    //2 : Append Self Due to Parent being different than Collected children in Tier 2 Pages
    if($this->get_add_self_page($post)){
      array_unshift($pages, get_post($post->ID));
    }
    //3 : Add Parent if Box is Checked && find_keep_parent is true 
    if ($this->include_apex && $this->find_keep_parent($post)) {
      array_unshift($pages, get_post($this->origin));
    }
    
    return $pages;
  }


}

