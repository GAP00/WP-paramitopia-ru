<?php get_header(); ?>
<div id="content">
	<div class="post">
		<h2 class="title title_page"><?php _e('Error 404 - Not Found', 'paramitopia'); ?></h2>
		<div class="entry">
			<p><?php _e('Sorry, but you are looking for something that isn&#8217;t here.', 'paramitopia'); ?></p>
			<h3><?php _e('Random Posts', 'paramitopia'); ?></h3>
			<ul>
				<?php
					$rand_posts = get_posts('numberposts=5&orderby=rand');
					foreach( $rand_posts as $post ) :
				?>
				<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
				<?php endforeach; ?>
			</ul>
		</div><!--entry-->
	</div><!--post-->
</div><!--content-->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
