<?php /* Template Name: Home Page - Style 2*/
get_header();
?>
<?php $knowledgehome_quicklinks_section = INFOCENTER_OPTIONS("knowledgehome_quicklinks_section");
			$knowledgehome_quicklinks_section_whitebg = INFOCENTER_OPTIONS("knowledgehome_quicklinks_section_whitebg");
			$knowledgehome_quicklinks_link1_heading = INFOCENTER_OPTIONS("knowledgehome_quicklinks_link1_heading");
			$knowledgehome_quicklinks_link1_text = INFOCENTER_OPTIONS("knowledgehome_quicklinks_link1_text");
			$knowledgehome_quicklinks_link1_icon = INFOCENTER_OPTIONS("knowledgehome_quicklinks_link1_icon");
			$knowledgehome_quicklinks_link1_rmtext = INFOCENTER_OPTIONS("knowledgehome_quicklinks_link1_rmtext");
			$knowledgehome_quicklinks_link1_url = INFOCENTER_OPTIONS("knowledgehome_quicklinks_link1_url");
			$knowledgehome_quicklinks_link2_heading = INFOCENTER_OPTIONS("knowledgehome_quicklinks_link2_heading");
			$knowledgehome_quicklinks_link2_text = INFOCENTER_OPTIONS("knowledgehome_quicklinks_link2_text");
			$knowledgehome_quicklinks_link2_icon = INFOCENTER_OPTIONS("knowledgehome_quicklinks_link2_icon");
			$knowledgehome_quicklinks_link2_rmtext = INFOCENTER_OPTIONS("knowledgehome_quicklinks_link2_rmtext");
			$knowledgehome_quicklinks_link2_url = INFOCENTER_OPTIONS("knowledgehome_quicklinks_link2_url");
			$knowledgehome_quicklinks_link3_heading = INFOCENTER_OPTIONS("knowledgehome_quicklinks_link3_heading");
			$knowledgehome_quicklinks_link3_text = INFOCENTER_OPTIONS("knowledgehome_quicklinks_link3_text");
			$knowledgehome_quicklinks_link3_icon = INFOCENTER_OPTIONS("knowledgehome_quicklinks_link3_icon");
			$knowledgehome_quicklinks_link3_rmtext = INFOCENTER_OPTIONS("knowledgehome_quicklinks_link3_rmtext");
			$knowledgehome_quicklinks_link3_url = INFOCENTER_OPTIONS("knowledgehome_quicklinks_link3_url");
		if ($knowledgehome_quicklinks_section == 1) { ?>
	<section class="custom-padding<?php if ($knowledgehome_quicklinks_section_whitebg != 1) { ?> home-blog-parallex<?php } ?>">
      <div class="container">
        <!-- Row -->
        <div class="row">
          <!-- Left Side Content -->
          <div class="col-sm-12 col-xs-12 col-md-12">

            <div class="col-sm-4">
                    <div class="ticket-grid">
                        <span><i class="<?php echo esc_attr($knowledgehome_quicklinks_link1_icon); ?>" aria-hidden="true"></i></span>
                        <h3><?php echo esc_attr($knowledgehome_quicklinks_link1_heading); ?></h3>
                        <p><?php echo $knowledgehome_quicklinks_link1_text; ?></p>
                        <a href="<?php echo esc_url($knowledgehome_quicklinks_link1_url); ?>"><?php echo esc_attr($knowledgehome_quicklinks_link1_rmtext); ?> <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-sm-4">
                    <div class="ticket-grid">
                        <span><i class="<?php echo esc_attr($knowledgehome_quicklinks_link2_icon); ?>" aria-hidden="true"></i></span>
                        <h3><?php echo esc_attr($knowledgehome_quicklinks_link2_heading); ?></h3>
                        <p><?php echo $knowledgehome_quicklinks_link2_text; ?></p>
                        <a href="<?php echo esc_url($knowledgehome_quicklinks_link2_url); ?>"><?php echo esc_attr($knowledgehome_quicklinks_link2_rmtext); ?> <i class="fa fa-angle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-sm-4">
                    <div class="ticket-grid">
                        <span><i class="<?php echo esc_attr($knowledgehome_quicklinks_link3_icon); ?>" aria-hidden="true"></i></span>
                        <h3><?php echo esc_attr($knowledgehome_quicklinks_link3_heading); ?></h3>
                        <p><?php echo $knowledgehome_quicklinks_link3_text; ?></p>
                        <a href="<?php echo esc_url($knowledgehome_quicklinks_link3_url); ?>"><?php echo esc_attr($knowledgehome_quicklinks_link3_rmtext); ?> <i class="fa fa-angle-right"></i></a>
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
<?php $knowledgehome_category_section = INFOCENTER_OPTIONS("knowledgehome_category_section");
			$knowledgehome_category_section_whitebg = INFOCENTER_OPTIONS("knowledgehome_category_section_whitebg");
			$knowledgehome_category_heading = INFOCENTER_OPTIONS("knowledgehome_category_heading");
			$knowledgehome_category_sub_heading = INFOCENTER_OPTIONS("knowledgehome_category_sub_heading");
			$knowledgehome_category_button_label = INFOCENTER_OPTIONS("knowledgehome_category_button_label");
			$knowledgehome_category_button_url = INFOCENTER_OPTIONS("knowledgehome_category_button_url");
			$knowledgehome_category_number = INFOCENTER_OPTIONS("knowledgehome_category_number");
			$knowledgehome_category_asc_desc = INFOCENTER_OPTIONS("knowledgehome_category_asc_desc");
			$infocenter_hp_category_exclude = INFOCENTER_OPTIONS("infocenter_hp_category_exclude");
			// Base Category Query
			$infotex_hp_cat_args = array(
			  'orderby' => 'ID',
			  'order' => $knowledgehome_category_asc_desc,
			  'hierarchical' => true,
			  'hide_empty' => 0,
			  'exclude' => $infocenter_hp_category_exclude,
			  'number'  => $knowledgehome_category_number,
			);
		if ($knowledgehome_category_section == 1) { ?>
<section id="blog" class="custom-padding<?php if ($knowledgehome_category_section_whitebg != 1) { ?> home-blog-parallex<?php } ?>">
    <div class="container">
      <div class="row">
        <div class="col-xs-12">
          <!-- title-section -->
				<div class="main-heading text-center">
					<h2><?php echo esc_attr($knowledgehome_category_heading); ?></h2>
					<div class="slices"><span class="slice"></span><span class="slice"></span><span class="slice"></span>
					</div>
					<p><?php echo esc_attr($knowledgehome_category_sub_heading); ?></p>
				</div>
				<!-- End title-section -->
          <ul class="home-cats-list">
		  <?php $st_categories = get_categories($infotex_hp_cat_args); 
				$st_categories = wp_list_filter($st_categories,array('parent'=>0));
				// If there are catgegories
				if ($st_categories) {
				foreach($st_categories as $st_category) { ?>
				<?php
				//first get the current category ID
				$cat_id = $st_category->term_id;

				//then i get the data from the database
				$cat_data = get_option("category_$cat_id");
				?>
            <li><a href="<?php echo get_category_link( $st_category->term_id ); ?>" title="<?php echo esc_attr($st_category->name); ?>"><span class="wrap-icon"><?php if (isset($cat_data['extra1'])){ ?><i class="<?php echo esc_attr($cat_data['extra1']); ?>"></i><?php } else { ?><i class="fa fa-code"></i><?php } ?></span><span class="text"><?php echo esc_attr($st_category->name); ?> <span class="number">(<?php echo esc_attr($st_category->count); ?>)</span></span></a></li>
			<?php } //endforeach ?>
			<?php } //endif ?>
          </ul>
		  <?php if ($knowledgehome_category_button_url != '') { ?>
          <div class="text-center clearfix section-padding-40"> <a href="<?php echo esc_url($knowledgehome_category_button_url); ?>" class="btn btn-lg btn-primary"><?php echo esc_attr($knowledgehome_category_button_label); ?></a></div>
		  <?php } ?>
        </div>
      </div>
    </div>
  </section>
  <?php } ?>
  <div class="post-content">
			
		<?php $knowledgehome_downloads_section = INFOCENTER_OPTIONS("knowledgehome_downloads_section");
			$knowledgehome_downloads_bg = INFOCENTER_OPTIONS("knowledgehome_downloads_bg");
			$knowledgehome_downloads_m_heading = INFOCENTER_OPTIONS("knowledgehome_downloads_m_heading");
			$knowledgehome_downloads_app1_heading = INFOCENTER_OPTIONS("knowledgehome_downloads_app1_heading");
			$knowledgehome_downloads_app1_sub_heading = INFOCENTER_OPTIONS("knowledgehome_downloads_app1_sub_heading");
			$knowledgehome_downloads_app1_image = INFOCENTER_OPTIONS("knowledgehome_downloads_app1_image");
			$knowledgehome_downloads_app1_url = INFOCENTER_OPTIONS("knowledgehome_downloads_app1_url");
			$knowledgehome_downloads_app2_heading = INFOCENTER_OPTIONS("knowledgehome_downloads_app2_heading");
			$knowledgehome_downloads_app2_sub_heading = INFOCENTER_OPTIONS("knowledgehome_downloads_app2_sub_heading");
			$knowledgehome_downloads_app2_image = INFOCENTER_OPTIONS("knowledgehome_downloads_app2_image");
			$knowledgehome_downloads_app2_url = INFOCENTER_OPTIONS("knowledgehome_downloads_app2_url");
			$knowledgehome_downloads_app3_heading = INFOCENTER_OPTIONS("knowledgehome_downloads_app3_heading");
			$knowledgehome_downloads_app3_sub_heading = INFOCENTER_OPTIONS("knowledgehome_downloads_app3_sub_heading");
			$knowledgehome_downloads_app3_image = INFOCENTER_OPTIONS("knowledgehome_downloads_app3_image");
			$knowledgehome_downloads_app3_url = INFOCENTER_OPTIONS("knowledgehome_downloads_app3_url");
		if ($knowledgehome_downloads_section == 1) { ?>
		<!-- =-=-=-=-=-=-= Download Apps =-=-=-=-=-=-= -->
		<div class="parallex section-padding  our-apps text-center" <?php if ($knowledgehome_downloads_bg != '') { ?>style="background: url(<?php echo esc_url($knowledgehome_downloads_bg); ?>) repeat fixed center top;"<?php } ?>>
			<div class="container">
				<!-- title-section -->
				<div class="main-heading text-center">
					<h2><?php echo esc_attr($knowledgehome_downloads_m_heading); ?></h2>
					<hr class="main">
				</div>
				<!-- End title-section -->
				<div class="row">
					<div class="app-content">
						<!-- Single download start \-->
						<div class="col-md-4 col-sm-4">
							<a href="<?php echo esc_url($knowledgehome_downloads_app1_url); ?>" class="app-grid"> <span class="app-icon"> <img alt="" src="<?php echo esc_url($knowledgehome_downloads_app1_image); ?>"> </span>
								<div class="app-title">
									<h5><?php echo esc_attr($knowledgehome_downloads_app1_heading); ?></h5>
									<h3><?php echo esc_attr($knowledgehome_downloads_app1_sub_heading); ?></h3>
								</div>
							</a>
						</div>
						<!--/ Single download end-->
						<!-- Single download start \-->
						<div class="col-md-4 col-sm-4">
							<a href="<?php echo esc_url($knowledgehome_downloads_app2_url); ?>" class="app-grid"> <span class="app-icon"> <img alt="" src="<?php echo esc_url($knowledgehome_downloads_app2_image); ?>"> </span>
								<div class="app-title">
									<h5><?php echo esc_attr($knowledgehome_downloads_app2_heading); ?></h5>
									<h3><?php echo esc_attr($knowledgehome_downloads_app2_sub_heading); ?></h3>
								</div>
							</a>
						</div>
						<!--/ Single download end-->
						<!-- Single download start \-->
						<div class="col-md-4  col-sm-4">
							<a href="<?php echo esc_url($knowledgehome_downloads_app3_url); ?>" class="app-grid"> <span class="app-icon"> <img alt="" src="<?php echo esc_url($knowledgehome_downloads_app3_image); ?>"> </span>
								<div class="app-title">
									<h5><?php echo esc_attr($knowledgehome_downloads_app3_heading); ?></h5>
									<h3><?php echo esc_attr($knowledgehome_downloads_app3_sub_heading); ?></h3>
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
		<?php } $knowledgehome_social_section = INFOCENTER_OPTIONS("knowledgehome_social_section");
			$knowledgehome_social1_bgcolor = INFOCENTER_OPTIONS("knowledgehome_social1_bgcolor");
			$knowledgehome_social2_bgcolor = INFOCENTER_OPTIONS("knowledgehome_social2_bgcolor");
			$knowledgehome_social3_bgcolor = INFOCENTER_OPTIONS("knowledgehome_social3_bgcolor");
			$knowledgehome_social4_bgcolor = INFOCENTER_OPTIONS("knowledgehome_social4_bgcolor");
			$knowledgehome_social1_url = INFOCENTER_OPTIONS("knowledgehome_social1_url");
			$knowledgehome_social2_url = INFOCENTER_OPTIONS("knowledgehome_social2_url");
			$knowledgehome_social3_url = INFOCENTER_OPTIONS("knowledgehome_social3_url");
			$knowledgehome_social4_url = INFOCENTER_OPTIONS("knowledgehome_social4_url");
			$knowledgehome_social1_image = INFOCENTER_OPTIONS("knowledgehome_social1_image");
			$knowledgehome_social2_image = INFOCENTER_OPTIONS("knowledgehome_social2_image");
			$knowledgehome_social3_image = INFOCENTER_OPTIONS("knowledgehome_social3_image");
			$knowledgehome_social4_image = INFOCENTER_OPTIONS("knowledgehome_social4_image");
			$knowledgehome_social1_join = INFOCENTER_OPTIONS("knowledgehome_social1_join");
			$knowledgehome_social2_join = INFOCENTER_OPTIONS("knowledgehome_social2_join");
			$knowledgehome_social3_join = INFOCENTER_OPTIONS("knowledgehome_social3_join");
			$knowledgehome_social4_join = INFOCENTER_OPTIONS("knowledgehome_social4_join");
			$knowledgehome_social1_name = INFOCENTER_OPTIONS("knowledgehome_social1_name");
			$knowledgehome_social2_name = INFOCENTER_OPTIONS("knowledgehome_social2_name");
			$knowledgehome_social3_name = INFOCENTER_OPTIONS("knowledgehome_social3_name");
			$knowledgehome_social4_name = INFOCENTER_OPTIONS("knowledgehome_social4_name");
			
		if ($knowledgehome_social_section == 1) { ?>
		<div id="social-media">
		<div class="block no-padding">
			<div class="row">
				<div class="col-md-12">
					<div class="social-bar">
						<ul>
							<li class="social1" <?php if ($knowledgehome_social1_bgcolor != '') { ?>style="background: <?php echo esc_attr($knowledgehome_social1_bgcolor); ?> none repeat scroll 0 0;"<?php } ?>>
								<a title="" href="<?php echo esc_url($knowledgehome_social1_url); ?>">
									<img alt="" src="<?php echo esc_url($knowledgehome_social1_image); ?>"> <span><?php echo esc_attr($knowledgehome_social1_join); ?><strong><?php echo esc_attr($knowledgehome_social1_name); ?></strong></span> 
								</a>
							</li>
							<li class="social2" <?php if ($knowledgehome_social2_bgcolor != '') { ?>style="background: <?php echo esc_attr($knowledgehome_social2_bgcolor); ?> none repeat scroll 0 0;"<?php } ?>>
								<a title="" href="<?php echo esc_url($knowledgehome_social2_url); ?>">
									<img alt="" src="<?php echo esc_url($knowledgehome_social2_image); ?>"> <span><?php echo esc_attr($knowledgehome_social2_join); ?><strong><?php echo esc_attr($knowledgehome_social2_name); ?></strong></span> 
								</a>
							</li>
							<li class="social3" <?php if ($knowledgehome_social3_bgcolor != '') { ?>style="background: <?php echo esc_attr($knowledgehome_social3_bgcolor); ?> none repeat scroll 0 0;"<?php } ?>>
								<a title="" href="<?php echo esc_url($knowledgehome_social3_url); ?>">
									<img alt="" src="<?php echo esc_url($knowledgehome_social3_image); ?>"> <span><?php echo esc_attr($knowledgehome_social3_join); ?><strong><?php echo esc_attr($knowledgehome_social3_name); ?></strong></span> 
								</a>
							</li>
							<li class="social4" <?php if ($knowledgehome_social4_bgcolor != '') { ?>style="background: <?php echo esc_attr($knowledgehome_social4_bgcolor); ?> none repeat scroll 0 0;"<?php } ?>>
								<a title="" href="<?php echo esc_url($knowledgehome_social4_url); ?>">
									<img alt="" src="<?php echo esc_url($knowledgehome_social4_image); ?>"> <span><?php echo esc_attr($knowledgehome_social4_join); ?><strong><?php echo esc_attr($knowledgehome_social4_name); ?></strong></span> 
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