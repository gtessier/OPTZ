<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="post-info-left mo-animate" data-animate="fadeInLeft">
    <div class="post-info-description">
      <div class="date-post-info"><?php echo mysql2date('j',get_post()->post_date); ?> <small><?php echo mysql2date('M',get_post()->post_date); ?></small></div>
      <div class="image-post-info"><i class="icon-volume-up"></i></div>
    </div>
  </div>
  <?php
		
			$mp3_source = get_post_meta($post->ID, '_ps_mp3_file', true);
			$ogg_source = get_post_meta($post->ID, '_ps_ogg_file', true);
			$wav_source = get_post_meta($post->ID, '_ps_wav_file', true);

	?>
  <div class="blog-content mo-animate" data-animate="bounceIn">
    <div class="mo-media audio">
      <audio controls>
         <?php if($mp3_source != '') { ?><source src="<?php echo esc_url($mp3_source); ?>"><?php } ?>
            <?php if($ogg_source != '') { ?><source src="<?php echo esc_url($ogg_source); ?>"><?php } ?>
            <?php if($wav_source != '') { ?><source src="<?php echo esc_url($wav_source); ?>"><?php } ?>    
      </audio>
    </div>
    
    <a class="mo-animate" data-animate="fadeInDown" href="<?php the_permalink(); ?>">
            <h3 class="maleo-color"><?php the_title(); ?></h3>
    </a>
    
    <ul class="post-info mo-animate" data-animate="fadeInDown">
          <li><i class="icon-user"></i><a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author(); ?></a></li>
          <li><i class="icon-tag"></i><?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'prodigystudio' ) ); ?></li>
          <li><i class="icon-comment"></i><a href="<?php echo get_comments_link(); ?>"><?php echo get_comments_number(); ?></a></li>
        </ul>
    
<?php 
	if(!is_single() ) {
		if(!empty($post->post_excerpt)) :
			 echo '<p class="mo-animate" data-animate="fadeInDown">'. get_the_excerpt(). '</p>';
			 echo '<a href="'.get_the_permalink().'" class="button link read-more mo-animate" data-animate="fadeInDown">'.__('Read more','prodigystudio').'<i class="icon-circleright"></i></a><div class="line-separator mo-animate" data-animate="bounceIn"><hr></div>';
		 else: 
			$page_content = apply_filters('the_content', $post->post_content); echo substr($page_content, 0, strpos($page_content, "<!--more-->"));
			if (strpos($post->post_content, '<!--more-->')) : ?>
                <a href="<?php the_permalink(); ?>" class="button link read-more mo-animate" data-animate="fadeInDown"> <?php _e('Read more', 'prodigystudio'); ?> <i class="icon-circleright"></i> </a>
                <div class="line-separator mo-animate" data-animate="bounceIn">
                  <hr>
                </div>
	<?php endif;
		endif;
	} else {
		the_content();
		
		prodigystudio_share_this();
				
		prodigystudio_author_info();
			
		comments_template();	  
	}
?>
</div>
</article>