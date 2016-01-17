<div id="sidebar-artwork-tabs" class="container_24 ">
<?php if(comments_open($post->ID)){ ?>
				<div id="commentTab">
					<div class="faux-hr grid_24"></div>
					<?php comments_template( '/comments.php' ); ?>
					<div class="faux-hr grid_24"></div>
				</div>
<?php } ?>
<?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar(__('Artwork Tabs', TEMPLATE_DOMAIN)) ) : ?><?php endif; ?></div>
