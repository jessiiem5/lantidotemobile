<?php
	
function fca_cc_add_marketing_metaboxes( $post ) {

	add_meta_box( 
		'fca_cc_marketing_metabox',
		__( 'Level Up Your Contest', 'contest-cat' ),
		'fca_cc_render_marketing_metabox',
		null,
		'side',
		'default'
	);
	
	add_meta_box( 
		'fca_cc_quick_links_metabox',
		__( 'Quick Links', 'contest-cat' ),
		'fca_cc_render_quick_links_metabox',
		null,
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes_contest', 'fca_cc_add_marketing_metaboxes', 11, 1 );

function fca_cc_render_marketing_metabox( $post ) {
	
	ob_start(); ?>

	<h3><?php _e( "Build Awesome Contests With Contest Cat Premium", 'contest-cat' ); ?></h3>

	<ul>
		<li><div class="dashicons dashicons-yes"></div> <?php _e( 'Boost Social Shares', 'contest-cat' ); ?></li>
		<li><div class="dashicons dashicons-yes"></div> <?php _e( 'Get Leads & Email Subscribers', 'contest-cat' ); ?></li>
		<li><div class="dashicons dashicons-yes"></div> <?php _e( 'Priority Email Support', 'contest-cat' ); ?></li>
	</ul>
    
	<p style="text-align: center;">
		<a href="https://fatcatapps.com/contestcat/upgrade?utm_medium=plugin&utm_source=contest%20Cat%20Free&utm_campaign=free-plugin" target="_blank" class="button button-primary button-large"><?php _e('Upgrade Now', 'contest-cat'); ?></a>
	</p> 

	<?php 
		
	echo ob_get_clean();
}

function fca_cc_render_quick_links_metabox( $post ) {
	
	ob_start(); ?>

	<ul class='fca_cc_marketing_checklist'>
		<li><div class="dashicons dashicons-arrow-right"></div><a href="https://youtu.be/CQe3VsX_Xag" target="_blank"><?php _e( 'Need help getting started? Watch a video tutorial.', 'contest-cat' ); ?></a> </li>
		<li><div class="dashicons dashicons-arrow-right"></div><a href="http://wordpress.org/support/plugin/contest-cat" target="_blank"><?php _e( 'Problems or Suggestions? Get help here.', 'contest-cat' ); ?></a> </li>
		<li><div class="dashicons dashicons-arrow-right"></div><strong><a href="https://wordpress.org/support/plugin/contest-cat/reviews/" target="_blank"><?php _e( 'Like this plugin?  Please leave a review.', 'contest-cat' ); ?></strong></a> </li>
	</ul>

	<?php 
		
	echo ob_get_clean();
}
