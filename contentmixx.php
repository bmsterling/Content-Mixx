<?php
/*
	Plugin Name: Content Mixx
	Plugin URI: http://benjaminsterling.com/wordpress-plugins/contentmixx/
	Description: This plugin retrieves one or more random post/page or both of them from your WordPress installation
	Author: Benjamin Sterling
	Version: 0.1
	Author URI: http://www.benjaminsterling.com

	Copyright 2008 by Benjamin Sterling

	
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.
	
	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.
	
	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

	Arguments:
		from (string)
			- show only posts, or pages, or both
			- possible values: post | page 
		numberposts (integer)
			- number of posts/pages to return
		categories (integers, separated by commas)
			- limits returned posts to posts filed under categories found in arguments
		exclude (integers, separated by commas)
			- limits returned posts to posts NOT filed under categories found in arguments

	
	Usage:
		contentmixx( arguments );

	Sample Code:
		<?php
		$myposts = contentmixx( 'from=post&numberposts=2&categories=8' );
		foreach( $myposts as $post ) {
			setup_postdata( $post ); 
		?>
			<div class="sideBarPost">
				<h3><a href="<?php the_permalink() ?>"><?php the_title() ?></a></h3>
				<p><?php the_excerpt(); ?></p>
				<p><a href="<?php the_permalink() ?>">Read More...</a></p>
			</div>
		<?php
		}
		?>


		 <?php
			 $results = contentmixx('numberposts=3');
			 foreach($results as $post) :
				setup_postdata($post);
		?>
			<h2><a href="<?php the_permalink(); ?>" id="post-<?php the_ID(); ?>"><?php the_title(); ?></a></h2>
			<?php the_content(); ?>
		 <?php endforeach; ?>
	
*/
/**
 *	Load WP-Config File If This File Is Called Directly
 */
if (!function_exists('add_action')) {
	require_once('../../wp-config.php');
} //  end : if (!function_exists('add_action'))

if(!class_exists("cm")){
	class cm{
		var $options = array();
		var $from = array( 'post', 'page', 'both' );

		function getRandom( $args = null ){
			global $wpdb;
			parse_str($args, $this->options);
			
			$this->options['from'] = (
					isset( $this->options['from'] )
					&&
					in_array( strtolower( $this->options['from'] ), $this->from ) 
				) ? strtolower( $this->options['from'] ) : 'post';
			
			$this->options['quantity'] = (
										isset( $this->options['numberposts'] )
										&&
										ctype_digit( $this->options['numberposts'] ) 
									) ? $this->options['numberposts'] : 1;

			$this->options['cat'] = (
										isset( $this->options['categories'] ) 
									) ? explode( ',', $this->options['categories'] ) : array();

			$this->options['excat'] = (
										isset( $this->options['exclude'] ) 
									) ? explode( ',', $this->options['exclude'] ) : array();
									
			$this->options['where'] = " p.post_type != 'attachment' AND p.post_status = 'publish' ";
			$this->options['table'] = "$wpdb->posts AS p";
			
			if( $this->options['from'] != 'both'){
				$this->options['where'] .= " AND post_type = '".$this->options['from']."' ";
			}  //  end : if( $this->options['from'] != 'both')

			if( count( $this->options['cat'] ) || count( $this->options['cat'] ) ){
				$this->options['table'] .= " LEFT JOIN ".$wpdb->term_relationships." as tr ON ( p.ID = tr.object_id ) 
										LEFT JOIN ".$wpdb->term_taxonomy." AS t ON (tr.term_taxonomy_id = t.term_taxonomy_id) ";
				
				$tmpCat = array();
				foreach( $this->options['cat'] as $cat ) {
					if( $cat == '' || $cat == 0 || !ctype_digit( $cat ) ) continue; // not a category
					array_push($tmpCat,$cat);
				}
				$this->options['where'] .= " AND t.taxonomy = 'category' AND t.term_id IN (".join(',',$tmpCat).") ";
			}  //  end : if( count( $this->options['cat'] ) || count( $this->options['cat'] ) )
			
			if( count( $this->options['excat'] ) || count( $this->options['excat'] ) ){
				$tmpCat = array();
				foreach( $this->options['excat'] as $cat ) {
					if( $cat == '' || $cat == 0 || !ctype_digit( $cat ) ) continue; // not a category
					array_push($tmpCat,$cat);
				}
				$this->options['where'] .= " AND t.term_id NOT IN (".join(',',$tmpCat).") ";
			}  //  end : ( count( $this->options['cat'] ) || count( $this->options['cat'] ) )
			
			$sql = "SELECT DISTINCT * FROM " . $this->options['table'] . " 
					WHERE " . $this->options['where'] . " 
					ORDER BY RAND() LIMIT ". $this->options['quantity'];
			
			$posts = $wpdb->get_results( $sql );
			update_post_caches( $posts );

			return $posts;	
		}
	}  //  end : class cm
}  //  end : if(!class_exists("cm"))

// initiate cm
if(class_exists("cm")){
	function contentmixx( $args = null ){
		$cm = new cm(  );
		return $cm->getRandom($args);
	}
}
?>