	<div id="sidebar">

<?php if ( !dynamic_sidebar('primary-widget-area') ) : ?>
	<div class="widget">
		<h3><?php _e('Random Posts', 'paramitopia'); ?></h3>
		<ul>
			<?php
			$rand_posts = get_posts('numberposts=5&orderby=rand');
			foreach( $rand_posts as $post ) :
			?>
			<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<div class="widget">
		<h3><?php _e('Search by Tags!', 'paramitopia'); ?></h3>
		<div><?php wp_tag_cloud('smallest=9&largest=18'); ?></div>
	</div>	
	<div class="widget">
		<h3><?php _e('Archives', 'paramitopia'); ?></h3>
		<ul>
			<?php wp_get_archives( 'type=monthly' ); ?>
		</ul>
	</div>
	<div class="widget">
		<h3><?php _e('Links', 'paramitopia'); ?></h3>
		<ul>
			<?php wp_list_bookmarks('title_li=&categorize=0&orderby=id'); ?>
		</ul>
	</div>
	<div class="widget">
		<h3><?php _e('Meta', 'paramitopia'); ?></h3>
		<ul>
			<?php wp_register(); ?>
			<li><?php wp_loginout(); ?></li>
			<?php wp_meta(); ?>
		</ul>
	</div>

<?php endif; ?>

	</div><!-- end: #sidebar -->
