=== Content Mixx ===
Contributors: bmsterling 
Donate link:http://benjaminsterling.com/donations/
Tags: random post, random page, content
Requires at least: 2.3
Tested up to: 3.1.1
Stable tag: 0.2

This plugin retrieves one or more random post/page or both of them from your WordPress installation

== Description ==

This plugin retrieves one or more random post/page or both of them from your WordPress installation
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

Please submit any bugs or suggested improvements to <a href=\"https://github.com/bmsterling/Content-Mixx/issues\">https://github.com/bmsterling/Content-Mixx/issues</a>

Need this plugin customize or need a plugin create, contact me via my contact form at <a href=\"http://kenzomedia.com\">http://kenzomedia.com</a>

Also available through twitter, @bmsterling

== Installation ==

1. Unzip into your `/wp-content/plugins/` directory. If you're uploading it make sure to upload the top-level folder. Don't just upload all the php files and put them in `/wp-content/plugins/`.
2. Activate the plugin through the 'Plugins' menu in WordPress

== Credits ==

Copyright 2007  Benjamin Sterling  (http://benjaminsterling.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  

See the GNU General Public License for more details:
http://www.gnu.org/licenses/gpl.txt