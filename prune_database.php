<?php
/*
Plugin Name: prune_database
Plugin URI: http://blog.wereldkeuken.be/
Description: delete posts & comments older than x days
Author: Herman Horsten
Author URI: http://blog.wereldkeuken.be/
Version: 1.0
*/

function prune_database($post_ID)
{
  global $wpdb, $tableposts, $tablecomments;
  $remove_older_than_days = 180;
  
	//
	// remove orphans and comments on posts older than ...
	//
	$query_delete_comments = "DELETE $tablecomments FROM $tablecomments 
	      LEFT JOIN $tableposts ON $tablecomments.comment_post_ID = $tableposts.ID 
		  WHERE ($tableposts.ID IS NULL) OR ( ( TO_DAYS( NOW(  )  )  - TO_DAYS( post_date )  ) > $remove_older_than_days)";
    $wpdb->query($query_delete_comments);
	
	//
	// remove posts older than ....
	//
    $query_delete_posts = "DELETE FROM $tableposts WHERE ( TO_DAYS( NOW(  )  )  - TO_DAYS( post_date )  ) > $remove_older_than_days";
    $wpdb->query($query_delete_posts);
	return $post_ID;
}

add_action('publish_post', 'prune_database'); 
?>