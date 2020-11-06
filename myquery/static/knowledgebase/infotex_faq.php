<?php /* Template Name: FAQ */
get_header();
$infocenter_sidebar_all = infocenter_get_global_infocenter_sidebar_all();
?>
<div class="faq-section">
          <div class="faqs-block">
            <div id="accordion" role="tablist" aria-multiselectable="true" class="panel-group">
              
			  <?php 
				$onoff_faq_qlinks = INFOCENTER_OPTIONS("onoff_faq_qlinks");
				$faq_qlinks1 = INFOCENTER_OPTIONS("faq_qlinks1");
				$faq_qlinks2 = INFOCENTER_OPTIONS("faq_qlinks2");
				$faq_qlinks3 = INFOCENTER_OPTIONS("faq_qlinks3");
				$faq_icon1 = INFOCENTER_OPTIONS("faq_icon1");
				$faq_icon2 = INFOCENTER_OPTIONS("faq_icon2");
				$faq_icon3 = INFOCENTER_OPTIONS("faq_icon3");
				$faq_text1 = INFOCENTER_OPTIONS("faq_text1");
				$faq_text2 = INFOCENTER_OPTIONS("faq_text2");
				$faq_text3 = INFOCENTER_OPTIONS("faq_text3");
				$args = array( 'post_type' => 'faqs', 'posts_per_page' => -1, 'order' => 'ASC' );
				$the_query = new WP_Query( $args ); 
				?>
				<?php if ( $the_query->have_posts() ) { ?>
				<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
				<div class="panel panel-default">
								<div role="tab" id="headingQ<?php echo esc_attr($post->ID); ?>" class="panel-heading">
								  <h3 class="panel-title"><a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseQ<?php echo esc_attr($post->ID); ?>" aria-expanded="false" aria-controls="collapseQ<?php echo esc_attr($post->ID); ?>"><?php the_title(); ?></a></h3>
								</div>
								<div id="collapseQ<?php echo esc_attr($post->ID); ?>" role="tabpanel" aria-labelledby="headingQ<?php echo esc_attr($post->ID); ?>" class="panel-collapse collapse">
								  <div class="panel-body">
									<?php the_content(); ?>
								  </div>
								</div>
							  </div>
				<?php endwhile; ?>
				<?php } else { ?>
				<div class="panel panel-default">
				<p><?php esc_html_e("Sorry, no FAQs found.","infocenter")?></p>
				</div>
				<?php } ?>
              
            </div>
			<?php if ($onoff_faq_qlinks == 1) { ?>
            <ul class="short-list-nav">
				<?php if ($faq_qlinks1 != '') { ?>
                <li><a href="<?php echo esc_url($faq_qlinks1); ?>"><span class="wrap-icon"><i class="<?php echo esc_attr($faq_icon1); ?>"></i></span><span class="text"><?php echo esc_attr($faq_text1); ?></span></a></li>
                <?php } if ($faq_qlinks2 != '') { ?>
				<li><a href="<?php echo esc_url($faq_qlinks2); ?>"><span class="wrap-icon"><i class="<?php echo esc_attr($faq_icon2); ?>"></i></span><span class="text"><?php echo esc_attr($faq_text2); ?></span></a></li>
                <?php } if ($faq_qlinks3 != '') { ?>
				<li><a href="<?php echo esc_url($faq_qlinks3); ?>"><span class="wrap-icon"><i class="<?php echo esc_attr($faq_icon3); ?>"></i></span><span class="text"><?php echo esc_attr($faq_text3); ?></span></a></li>
				<?php } ?>
			</ul>
			<?php } ?>
          </div>
        </div>
<?php get_footer();?>