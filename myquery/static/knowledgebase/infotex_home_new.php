<?php /* Template Name: Home Page - Style 4
* This should be used only if users want to show Questions instead of Posts in the articles section (http://fluentthemes.com/wp/knowledgebase/)
*/
get_header();
?>
<?php $knowledgehome2_quicklinks_section = INFOCENTER_OPTIONS("knowledgehome2_quicklinks_section");
			$knowledgehome2_quicklinks_section_whitebg = INFOCENTER_OPTIONS("knowledgehome2_quicklinks_section_whitebg");
			$knowledgehome2_quicklinks_link1_heading = INFOCENTER_OPTIONS("knowledgehome2_quicklinks_link1_heading");
			$knowledgehome2_quicklinks_link1_text = INFOCENTER_OPTIONS("knowledgehome2_quicklinks_link1_text");
			$knowledgehome2_quicklinks_link1_icon = INFOCENTER_OPTIONS("knowledgehome2_quicklinks_link1_icon");
			$knowledgehome2_quicklinks_link1_rmtext = INFOCENTER_OPTIONS("knowledgehome2_quicklinks_link1_rmtext");
			$knowledgehome2_quicklinks_link1_url = INFOCENTER_OPTIONS("knowledgehome2_quicklinks_link1_url");
			$knowledgehome2_quicklinks_link2_heading = INFOCENTER_OPTIONS("knowledgehome2_quicklinks_link2_heading");
			$knowledgehome2_quicklinks_link2_text = INFOCENTER_OPTIONS("knowledgehome2_quicklinks_link2_text");
			$knowledgehome2_quicklinks_link2_icon = INFOCENTER_OPTIONS("knowledgehome2_quicklinks_link2_icon");
			$knowledgehome2_quicklinks_link2_rmtext = INFOCENTER_OPTIONS("knowledgehome2_quicklinks_link2_rmtext");
			$knowledgehome2_quicklinks_link2_url = INFOCENTER_OPTIONS("knowledgehome2_quicklinks_link2_url");
			$knowledgehome2_quicklinks_link3_heading = INFOCENTER_OPTIONS("knowledgehome2_quicklinks_link3_heading");
			$knowledgehome2_quicklinks_link3_text = INFOCENTER_OPTIONS("knowledgehome2_quicklinks_link3_text");
			$knowledgehome2_quicklinks_link3_icon = INFOCENTER_OPTIONS("knowledgehome2_quicklinks_link3_icon");
			$knowledgehome2_quicklinks_link3_rmtext = INFOCENTER_OPTIONS("knowledgehome2_quicklinks_link3_rmtext");
			$knowledgehome2_quicklinks_link3_url = INFOCENTER_OPTIONS("knowledgehome2_quicklinks_link3_url");
		if ($knowledgehome2_quicklinks_section == 1) { ?>
	<section class="custom-padding<?php if ($knowledgehome2_quicklinks_section_whitebg != 1) { ?> home-blog-parallex<?php } ?>">
      <div class="container">
        <!-- Row -->
        <div class="row">
          <!-- Left Side Content -->
          <div class="col-sm-12 col-xs-12 col-md-12">

            <div class="col-sm-4">
                    <div class="ticket-grid">
                        <span><i class="<?php echo esc_attr($knowledgehome2_quicklinks_link1_icon); ?>" aria-hidden="true"></i></span>
                        <h3><?php echo esc_attr($knowledgehome2_quicklinks_link1_heading); ?></h3>
                        <p><?php echo $knowledgehome2_quicklinks_link1_text; ?></p>
                        <a href="<?php echo esc_url($knowledgehome2_quicklinks_link1_url); ?>"><?php echo esc_attr($knowledgehome2_quicklinks_link1_rmtext); ?> <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-sm-4">
                    <div class="ticket-grid">
                        <span><i class="<?php echo esc_attr($knowledgehome2_quicklinks_link2_icon); ?>" aria-hidden="true"></i></span>
                        <h3><?php echo esc_attr($knowledgehome2_quicklinks_link2_heading); ?></h3>
                        <p><?php echo $knowledgehome2_quicklinks_link2_text; ?></p>
                        <a href="<?php echo esc_url($knowledgehome2_quicklinks_link2_url); ?>"><?php echo esc_attr($knowledgehome2_quicklinks_link2_rmtext); ?> <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-sm-4">
                    <div class="ticket-grid">
                        <span><i class="<?php echo esc_attr($knowledgehome2_quicklinks_link3_icon); ?>" aria-hidden="true"></i></span>
                        <h3><?php echo esc_attr($knowledgehome2_quicklinks_link3_heading); ?></h3>
                        <p><?php echo $knowledgehome2_quicklinks_link3_text; ?></p>
                        <a href="<?php echo esc_url($knowledgehome2_quicklinks_link3_url); ?>"><?php echo esc_attr($knowledgehome2_quicklinks_link3_rmtext); ?> <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>           
                     
                  
            <div class="clearfix"></div>
            <!-- Pagination End -->
          </div>
          <!-- Left Content End -->
        </div>
        <!-- Row End -->
      </div>
      <!-- end container -->
    </section>
	<?php } ?>
<?php $knowledgehome2_category_section = INFOCENTER_OPTIONS("knowledgehome2_category_section");
			$knowledgehome2_category_heading = INFOCENTER_OPTIONS("knowledgehome2_category_heading");
			$knowledgehome2_category_sub_heading = INFOCENTER_OPTIONS("knowledgehome2_category_sub_heading");
			$knowledgehome2_category_button_label = INFOCENTER_OPTIONS("knowledgehome2_category_button_label");
			$knowledgehome2_category_number = INFOCENTER_OPTIONS("knowledgehome2_category_number");
			$knowledgehome2_category_asc_desc = INFOCENTER_OPTIONS("knowledgehome2_category_asc_desc");
			$infocenter_h2_category_exclude = INFOCENTER_OPTIONS("infocenter_h2_category_exclude");
			$knowledgehome2_posts_per_page = INFOCENTER_OPTIONS("knowledgehome2_posts_per_page");
			$knowledgehome2_articles_asc_desc = INFOCENTER_OPTIONS("knowledgehome2_articles_asc_desc");
			$knowledgehome2_sidebar_on_off = INFOCENTER_OPTIONS("knowledgehome2_sidebar_on_off");
		if ($knowledgehome2_category_section == 1) { ?>

    <!-- =-=-=-=-=-=-= Categories =-=-=-=-=-=-= -->
    <section class="custom-padding">
      <div class="container">
        <!-- Row -->
        <div class="row">

          <!-- Left Side Content -->
          <div class="col-sm-12 col-xs-12 <?php if ($knowledgehome2_sidebar_on_off == 1) { ?>col-md-9<?php } else { ?>col-md-12<?php } ?>">
			<?php if ($knowledgehome2_category_heading != '') { ?>
			<div class="col-xs-12">
				<!-- title-section -->
				<div class="main-heading text-center">
					<h2><?php echo esc_attr($knowledgehome2_category_heading); ?></h2>
					<div class="slices"><span class="slice"></span><span class="slice"></span><span class="slice"></span>
					</div>
					<p><?php echo $knowledgehome2_category_sub_heading; ?></p>
				</div>
				<!-- End title-section -->
			</div>
			<?php } ?>
          <div id="posts-masonry" class="posts-masonry">
                     <?php
					$terms = get_terms( array(
						'taxonomy' => 'question-category',
						'hide_empty' => true,
						'exclude' => $infocenter_h2_category_exclude,
						'order' => $knowledgehome2_category_asc_desc,
						'number'  => $knowledgehome2_category_number,
					) );

				if ($terms) {
				$i = 0;
				foreach ($terms as $term) {
				$i++; ?>
					 <?php if ($knowledgehome2_sidebar_on_off == 1) { ?>
					 <?php if(($i % 2) !== 0){ ?><div class="block_row"><!-- block_row start --><?php } ?>
					 <?php } ?>
					 <div class="<?php if ($knowledgehome2_sidebar_on_off == 1) { ?>col-md-6 col-sm-6 col-xs-12<?php } else { ?>col-md-4 col-sm-6 col-xs-12<?php } ?>">
                        <div class="site-map">
                           <div class="cat-title">
                               <h3><a href="<?php echo get_term_link($term->slug, 'question-category'); ?>"><?php echo esc_attr($term->name); ?></a></h3>
                               <span><i class="fa fa-folder-o" aria-hidden="true"></i></span>
                           </div>
                           <ul class="site-map-list">
						   
						   <?php $cat_query = new WP_Query(array('post_type' => 'question', 'order'=> $knowledgehome2_articles_asc_desc, 'tax_query' => array( array( 'taxonomy' => 'question-category', 'terms'    => $term->term_taxonomy_id, ), ), 'orderby' => 'post_date', 'posts_per_page'   => $knowledgehome2_posts_per_page ));
							while ( $cat_query->have_posts() ) : $cat_query->the_post(); global $post;?>
                              <li><a href="<?php echo esc_url( get_permalink($post->ID) ); ?>"><?php echo get_the_title($post->ID); ?></a></li>
							<?php endwhile; wp_reset_postdata(); ?>  
                           </ul>
                           <a class="read-more" href="<?php echo get_term_link($term->slug, 'question-category'); ?>"><?php if ($knowledgehome2_category_button_label != '') { ?><?php echo esc_attr($knowledgehome2_category_button_label); ?><?php } else { ?><?php esc_html_e("View all Questions","infocenter");?><?php } ?></a>
                        </div>
                        <!-- .site-map -->
                     </div>
					 <?php if ($knowledgehome2_sidebar_on_off == 1) { ?>
					 <?php if(($i % 2) == 0){ ?></div><!-- block_row end --><?php } ?>
					 <?php } ?>
				<?php } //endforeach ?>
			<?php } //endif ?>
                     
                     
                  </div>
            <div class="clearfix"></div>
            <!-- Pagination End -->
          </div>
          <!-- Left Content End -->

          <!-- Blog Right Sidebar -->
		  <?php if ($knowledgehome2_sidebar_on_off == 1) { ?>
          <div class="col-sm-12 col-xs-12 col-md-3" style="padding-left:0;padding-right:30px;">

            <!-- sidebar -->						
			<?php if ( is_active_sidebar( 'sidebar_default' ) ) { ?>
				<div class="homestyle3_sidebar">
				<?php dynamic_sidebar('sidebar_default');?>
				</div>
			<?php } ?>
			<!-- sidebar end -->

          </div>
		  <?php } ?>
          <!-- Blog Right Sidebar End -->

        </div>

        <!-- Row End -->
      </div>
      <!-- end container -->
    </section>
    <!-- =-=-=-=-=-=-= Categories end =-=-=-=-=-=-= -->

  <?php } ?>
  <div class="post-content">
			
		<?php $knowledgehome2_downloads_section = INFOCENTER_OPTIONS("knowledgehome2_downloads_section");
			$knowledgehome2_downloads_bg = INFOCENTER_OPTIONS("knowledgehome2_downloads_bg");
			$knowledgehome2_downloads_m_heading = INFOCENTER_OPTIONS("knowledgehome2_downloads_m_heading");
			$knowledgehome2_downloads_app1_heading = INFOCENTER_OPTIONS("knowledgehome2_downloads_app1_heading");
			$knowledgehome2_downloads_app1_sub_heading = INFOCENTER_OPTIONS("knowledgehome2_downloads_app1_sub_heading");
			$knowledgehome2_downloads_app1_image = INFOCENTER_OPTIONS("knowledgehome2_downloads_app1_image");
			$knowledgehome2_downloads_app1_url = INFOCENTER_OPTIONS("knowledgehome2_downloads_app1_url");
			$knowledgehome2_downloads_app2_heading = INFOCENTER_OPTIONS("knowledgehome2_downloads_app2_heading");
			$knowledgehome2_downloads_app2_sub_heading = INFOCENTER_OPTIONS("knowledgehome2_downloads_app2_sub_heading");
			$knowledgehome2_downloads_app2_image = INFOCENTER_OPTIONS("knowledgehome2_downloads_app2_image");
			$knowledgehome2_downloads_app2_url = INFOCENTER_OPTIONS("knowledgehome2_downloads_app2_url");
			$knowledgehome2_downloads_app3_heading = INFOCENTER_OPTIONS("knowledgehome2_downloads_app3_heading");
			$knowledgehome2_downloads_app3_sub_heading = INFOCENTER_OPTIONS("knowledgehome2_downloads_app3_sub_heading");
			$knowledgehome2_downloads_app3_image = INFOCENTER_OPTIONS("knowledgehome2_downloads_app3_image");
			$knowledgehome2_downloads_app3_url = INFOCENTER_OPTIONS("knowledgehome2_downloads_app3_url");
		if ($knowledgehome2_downloads_section == 1) { ?>
		<!-- =-=-=-=-=-=-= Download Apps =-=-=-=-=-=-= -->
		<div class="parallex section-padding  our-apps text-center" <?php if ($knowledgehome2_downloads_bg != '') { ?>style="background: url(<?php echo esc_url($knowledgehome2_downloads_bg); ?>) repeat fixed center top;"<?php } ?>>
			<div class="container">
				<!-- title-section -->
				<div class="main-heading text-center">
					<h2><?php echo esc_attr($knowledgehome2_downloads_m_heading); ?></h2>
					<hr class="main">
				</div>
				<!-- End title-section -->
				<div class="row">
					<div class="app-content">
						<!-- Single download start \-->
						<div class="col-md-4 col-sm-4">
							<a href="<?php echo esc_url($knowledgehome2_downloads_app1_url); ?>" class="app-grid"> <span class="app-icon"> <img alt="" src="<?php echo esc_url($knowledgehome2_downloads_app1_image); ?>"> </span>
								<div class="app-title">
									<h5><?php echo esc_attr($knowledgehome2_downloads_app1_heading); ?></h5>
									<h3><?php echo esc_attr($knowledgehome2_downloads_app1_sub_heading); ?></h3>
								</div>
							</a>
						</div>
						<!--/ Single download end-->
						<!-- Single download start \-->
						<div class="col-md-4 col-sm-4">
							<a href="<?php echo esc_url($knowledgehome2_downloads_app2_url); ?>" class="app-grid"> <span class="app-icon"> <img alt="" src="<?php echo esc_url($knowledgehome2_downloads_app2_image); ?>"> </span>
								<div class="app-title">
									<h5><?php echo esc_attr($knowledgehome2_downloads_app2_heading); ?></h5>
									<h3><?php echo esc_attr($knowledgehome2_downloads_app2_sub_heading); ?></h3>
								</div>
							</a>
						</div>
						<!--/ Single download end-->
						<!-- Single download start \-->
						<div class="col-md-4  col-sm-4">
							<a href="<?php echo esc_url($knowledgehome2_downloads_app3_url); ?>" class="app-grid"> <span class="app-icon"> <img alt="" src="<?php echo esc_url($knowledgehome2_downloads_app3_image); ?>"> </span>
								<div class="app-title">
									<h5><?php echo esc_attr($knowledgehome2_downloads_app3_heading); ?></h5>
									<h3><?php echo esc_attr($knowledgehome2_downloads_app3_sub_heading); ?></h3>
								</div>
							</a>
						</div>
						<!--/ Single download end-->
					</div>
				</div>
				<!-- End row -->
			</div>
			<!-- end container -->
		</div>
		<!-- =-=-=-=-=-=-= Download Apps END =-=-=-=-=-=-= -->
		<?php } $knowledgehome2_social_section = INFOCENTER_OPTIONS("knowledgehome2_social_section");
			$knowledgehome2_social1_bgcolor = INFOCENTER_OPTIONS("knowledgehome2_social1_bgcolor");
			$knowledgehome2_social2_bgcolor = INFOCENTER_OPTIONS("knowledgehome2_social2_bgcolor");
			$knowledgehome2_social3_bgcolor = INFOCENTER_OPTIONS("knowledgehome2_social3_bgcolor");
			$knowledgehome2_social4_bgcolor = INFOCENTER_OPTIONS("knowledgehome2_social4_bgcolor");
			$knowledgehome2_social1_url = INFOCENTER_OPTIONS("knowledgehome2_social1_url");
			$knowledgehome2_social2_url = INFOCENTER_OPTIONS("knowledgehome2_social2_url");
			$knowledgehome2_social3_url = INFOCENTER_OPTIONS("knowledgehome2_social3_url");
			$knowledgehome2_social4_url = INFOCENTER_OPTIONS("knowledgehome2_social4_url");
			$knowledgehome2_social1_image = INFOCENTER_OPTIONS("knowledgehome2_social1_image");
			$knowledgehome2_social2_image = INFOCENTER_OPTIONS("knowledgehome2_social2_image");
			$knowledgehome2_social3_image = INFOCENTER_OPTIONS("knowledgehome2_social3_image");
			$knowledgehome2_social4_image = INFOCENTER_OPTIONS("knowledgehome2_social4_image");
			$knowledgehome2_social1_join = INFOCENTER_OPTIONS("knowledgehome2_social1_join");
			$knowledgehome2_social2_join = INFOCENTER_OPTIONS("knowledgehome2_social2_join");
			$knowledgehome2_social3_join = INFOCENTER_OPTIONS("knowledgehome2_social3_join");
			$knowledgehome2_social4_join = INFOCENTER_OPTIONS("knowledgehome2_social4_join");
			$knowledgehome2_social1_name = INFOCENTER_OPTIONS("knowledgehome2_social1_name");
			$knowledgehome2_social2_name = INFOCENTER_OPTIONS("knowledgehome2_social2_name");
			$knowledgehome2_social3_name = INFOCENTER_OPTIONS("knowledgehome2_social3_name");
			$knowledgehome2_social4_name = INFOCENTER_OPTIONS("knowledgehome2_social4_name");
			
		if ($knowledgehome2_social_section == 1) { ?>
		<div id="social-media">
		<div class="block no-padding">
			<div class="row">
				<div class="col-md-12">
					<div class="social-bar">
						<ul>
							<li class="social1" <?php if ($knowledgehome2_social1_bgcolor != '') { ?>style="background: <?php echo esc_attr($knowledgehome2_social1_bgcolor); ?> none repeat scroll 0 0;"<?php } ?>>
								<a title="" href="<?php echo esc_url($knowledgehome2_social1_url); ?>">
									<img alt="" src="<?php echo esc_url($knowledgehome2_social1_image); ?>"> <span><?php echo esc_attr($knowledgehome2_social1_join); ?><strong><?php echo esc_attr($knowledgehome2_social1_name); ?></strong></span> 
								</a>
							</li>
							<li class="social2" <?php if ($knowledgehome2_social2_bgcolor != '') { ?>style="background: <?php echo esc_attr($knowledgehome2_social2_bgcolor); ?> none repeat scroll 0 0;"<?php } ?>>
								<a title="" href="<?php echo esc_url($knowledgehome2_social2_url); ?>">
									<img alt="" src="<?php echo esc_url($knowledgehome2_social2_image); ?>"> <span><?php echo esc_attr($knowledgehome2_social2_join); ?><strong><?php echo esc_attr($knowledgehome2_social2_name); ?></strong></span> 
								</a>
							</li>
							<li class="social3" <?php if ($knowledgehome2_social3_bgcolor != '') { ?>style="background: <?php echo esc_attr($knowledgehome2_social3_bgcolor); ?> none repeat scroll 0 0;"<?php } ?>>
								<a title="" href="<?php echo esc_url($knowledgehome2_social3_url); ?>">
									<img alt="" src="<?php echo esc_url($knowledgehome2_social3_image); ?>"> <span><?php echo esc_attr($knowledgehome2_social3_join); ?><strong><?php echo esc_attr($knowledgehome2_social3_name); ?></strong></span> 
								</a>
							</li>
							<li class="social4" <?php if ($knowledgehome2_social4_bgcolor != '') { ?>style="background: <?php echo esc_attr($knowledgehome2_social4_bgcolor); ?> none repeat scroll 0 0;"<?php } ?>>
								<a title="" href="<?php echo esc_url($knowledgehome2_social4_url); ?>">
									<img alt="" src="<?php echo esc_url($knowledgehome2_social4_image); ?>"> <span><?php echo esc_attr($knowledgehome2_social4_join); ?><strong><?php echo esc_attr($knowledgehome2_social4_name); ?></strong></span> 
								</a>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php } ?>
			<?php the_content();?>
			<div class="clearfix"></div>
	</div>

<?php get_footer(); ?>