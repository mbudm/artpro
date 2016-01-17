<ul>
<?php

switch($thumb_size){
	case MBUDM_IMAGESIZE_BANNER:
	case MBUDM_IMAGESIZE_WIDE_18:
	case MBUDM_IMAGESIZE_WIDE_6:
		$grid_class = '';
	break;
	case MBUDM_IMAGESIZE_20:
	case MBUDM_IMAGESIZE_18:
	case MBUDM_IMAGESIZE_16:
	case MBUDM_IMAGESIZE_12:
	case MBUDM_IMAGESIZE_8:
	case MBUDM_IMAGESIZE_6:
	case MBUDM_IMAGESIZE_4:
	case MBUDM_IMAGESIZE_3:
	case MBUDM_IMAGESIZE_2:
	case MBUDM_IMAGESIZE_1:
		$grid_num = str_replace(MBUDM_IMAGESIZE_PREFIX, "", $thumb_size);
		$grid_class = 'grid_' . $grid_num[0];
	break;
	}
foreach ( $query as $artwork ) :
	
?>
	<li class="<?php echo $grid_class ?>">
			<?php
			echo mbudm_get_post_image($artwork->ID,$thumb_size,true,'');
			?>
    </li>
<?php endforeach; 
?>
</ul>
