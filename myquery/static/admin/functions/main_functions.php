<?php
/* excerpt */
define("EXCERPT_TYPE",INFOCENTER_OPTIONS("EXCERPT_TYPE"));
function excerpt ($excerpt_length,$excerpt_type = EXCERPT_TYPE) {
	global $post;
	$excerpt_length = (isset($excerpt_length) && $excerpt_length != ""?$excerpt_length:5);
	$content = $post->post_content;
	$content = apply_filters('the_content', strip_shortcodes($content));
	if ($excerpt_type == "characters") {
		$content = mb_substr($content,0,$excerpt_length,"UTF-8");
	}else {
		$words = explode(' ',$content,$excerpt_length + 1);
		if (count($words) > $excerpt_length) :
			array_pop($words);
			array_push($words,'');
			$content = implode(' ',$words);
		endif;
	}
	$content = strip_tags($content);
	echo esc_attr($content).'...';
}
/* excerpt_title */
function excerpt_title ($excerpt_length,$excerpt_type = EXCERPT_TYPE) {
	global $post;
	$excerpt_length = (isset($excerpt_length) && $excerpt_length != ""?$excerpt_length:5);
	$title = $post->post_title;
	if ($excerpt_type == "characters") {
		$title = mb_substr($title,0,$excerpt_length,"UTF-8");
	}else {
		$words = explode(' ',$title,$excerpt_length + 1);
		if (count($words) > $excerpt_length) :
			array_pop($words);
			array_push($words,'');
			$title = implode(' ',$words);
		endif;
	}
	$title = strip_tags($title);
	echo esc_attr($title);
}
/* excerpt_any */
function excerpt_any($excerpt_length,$title,$excerpt_type = EXCERPT_TYPE) {
	$excerpt_length = (isset($excerpt_length) && $excerpt_length != ""?$excerpt_length:5);
	if ($excerpt_type == "characters") {
		$title = mb_substr($title,0,$excerpt_length,"UTF-8");
	}else {
		$words = explode(' ',$title,$excerpt_length + 1);
		if (count($words) > $excerpt_length) :
			array_pop($words);
			array_push($words,'');
			$title = implode(' ',$words);
		endif;
			$title = strip_tags($title);
	}
	return $title;
}
/* add post-thumbnails */
add_theme_support('post-thumbnails');
/* get_aq_resize_img */
function get_aq_resize_img($thumbnail_size,$img_width_f,$img_height_f,$img_lightbox="") {
	$thumb = get_post_thumbnail_id();
	if ($thumb != "") {
		$img_url = wp_get_attachment_url($thumb,$thumbnail_size);
		$img_width = $img_width_f;
		$img_height = $img_height_f;
		$image = aq_resize($img_url,$img_width,$img_height,true);
		if ($image) {
			$last_image = $image;
		}else {
			$last_image = "http://placehold.it/".$img_width_f."x".$img_height_f;
		}
		return ($img_lightbox == "lightbox"?"<a href='".esc_url($img_url)."'>":"")."<img alt='".get_the_title()."' width='".$img_width."' height='".$img_height."' src='".$last_image."'>".($img_lightbox == "lightbox"?"</a>":"");
	}else {
		return ($img_lightbox == "lightbox"?"<a href='".esc_url($img_url)."'>":"")."<img alt='".get_the_title()."' src='".vpanel_image()."'>".($img_lightbox == "lightbox"?"</a>":"");
	}
}
/* get_aq_resize_img_url */
function get_aq_resize_img_url($url,$thumbnail_size,$img_width_f,$img_height_f) {
	$thumb = $url;
	if ($thumb != "") {
		$img_url = $thumb;
		$img_width = $img_width_f;
		$img_height = $img_height_f;
		$image = aq_resize($img_url,$img_width,$img_height,true);
		if ($image) {
			$last_image = $image;
		}else {
			$last_image = "http://placehold.it/".$img_width_f."x".$img_height_f;
		}
		return "<img alt='".get_the_title()."' width='".$img_width."' height='".$img_height."' src='".$last_image."'>";
	}else {
		return "<img alt='".get_the_title()."' src='".vpanel_image()."'>";
	}
}
/* get_aq_resize_url */
function get_aq_resize_url($url,$thumbnail_size,$img_width_f,$img_height_f) {
	$img_url = $url;
	$img_width = $img_width_f;
	$img_height = $img_height_f;
	$image = aq_resize($img_url,$img_width,$img_height,true);
	if ($image) {
		$last_image = $image;
	}else {
		$last_image = "http://placehold.it/".$img_width_f."x".$img_height_f;
	}
	return $last_image;
}
/* get_aq_resize_img_full */
function get_aq_resize_img_full($thumbnail_size) {
	$thumb = get_post_thumbnail_id();
	if ($thumb != "") {
		$img_url = wp_get_attachment_url($thumb,$thumbnail_size);
		$image = $img_url;
		return "<img alt='".get_the_title()."' src='".$image."'>";
	}else {
		return "<img alt='".get_the_title()."' src='".vpanel_image()."'>";
	}
}
/* vpanel_image */
function vpanel_image() {
	global $post;
	ob_start();
	ob_end_clean();
	$output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i',$post->post_content,$matches);
	if (isset($matches[1][0])) {
		return $matches[1][0];
	}else {
		return false;
	}
}
/* formatMoney */
function formatMoney($number,$fractional=false) {
    if ($fractional) {
        $number = sprintf('%.2f',$number);
    }
    while (true) {
        $replaced = preg_replace('/(-?\d+)(\d\d\d)/','$1,$2',$number);
        if ($replaced != $number) {
            $number = $replaced;
        }else {
            break;
        }
    }
    return $number;
}
/* vpanel_counter_facebook */
function vpanel_counter_facebook ($page_id, $return = 'count') {
	$count = get_transient('vpanel_facebook_followers');
	$link = get_transient('vpanel_facebook_page_url');
	if ($return == 'link') {
		if ($link !== false) return $link;
	}else {
		if ($count !== false) return $count;
	}
	$count = 0;
	$link = '';
	$access_token = INFOCENTER_OPTIONS('facebook_access_token');
	$data = wp_remote_get('https://graph.facebook.com/' . $page_id.'?fields=about,likes,link,is_published,username,category&access_token=' . $access_token);
	if (!is_wp_error($data)) {
		$json = json_decode( $data['body'], true );
		$count = intval($json['likes']);
		$link = $json['link'];
		set_transient('vpanel_facebook_followers', $count, 3600);
		set_transient('vpanel_facebook_page_url', $link, 3600);
	}
	if ($return == 'link') {
		return $link;
	}else {
		return $count;
	}
}
/* vpanel_counter_googleplus */
function vpanel_counter_googleplus ($page_id, $return = 'count') {
	$count = get_transient('vpanel_googleplus_followers');
	$link = get_transient('vpanel_googleplus_page_url');
	if ($return == 'link') {
		if ($link !== false) return $link;
	}else {
		if ($count !== false) return $count;
	}
	$count = 0;
	$link = '';
	$api_key = INFOCENTER_OPTIONS('google_api');
	$data = wp_remote_get('https://www.googleapis.com/plus/v1/people/'.$page_id.'?key='.$api_key);
	if (!is_wp_error($data)) {
		$json = json_decode( $data['body'], true );
		$count = isset($json['circledByCount']) ? intval($json['circledByCount']) : intval($json['plusOneCount']);
		$link = $json['url'];
		set_transient('vpanel_googleplus_followers', $count, 3600);
		set_transient('vpanel_googleplus_page_url', $link, 3600);
	}
	if ($return == 'link') {
		return $link;
	}else {
		return $count;
	}
}
/* vpanel_counter_youtube */
function vpanel_counter_youtube ($youtube, $return = 'count') {
	$count = get_transient('vpanel_youtube_followers');
	$api_key = INFOCENTER_OPTIONS('google_api');
	if ($count !== false) return $count;
	$count = 0;
	$data = wp_remote_get('https://www.googleapis.com/youtube/v3/channels?part=statistics&id='.$youtube.'&key='.$api_key);
	if (!is_wp_error($data)) {
		$json = json_decode( $data['body'], true );
		$count = intval($json['items'][0]['statistics']['subscriberCount']);
		set_transient('vpanel_youtube_followers', $count, 3600);
	}
	return $count;
}
/* Vpanel_Questions */
function Vpanel_Questions($questions_per_page = 5,$orderby,$display_date,$questions_excerpt,$post_or_question,$excerpt_title = 5,$display_image = "on") {
	global $post;
	$date_format = (INFOCENTER_OPTIONS("date_format")?INFOCENTER_OPTIONS("date_format"):get_option("date_format"));
	$excerpt_title = ($excerpt_title != ""?$excerpt_title:5);
	if ($orderby == "popular") {
		$orderby = array('orderby' => 'comment_count');
	}elseif ($orderby == "random") {
		$orderby = array('orderby' => 'rand');
	}else {
		$orderby = array();
	}
	$query = new WP_Query( array_merge($orderby,array('post_type' => $post_or_question,'ignore_sticky_posts' => 1,'posts_per_page' => $questions_per_page,'cache_results' => false,'no_found_rows' => true)) );
	if ( $query->have_posts() ) : 
		echo "<ul class='related-posts'>";
			while ( $query->have_posts() ) : $query->the_post();?>
				<li class="related-item">
					<?php if (has_post_thumbnail() && $display_image == "on") {?>
						<div class="author-img">
							<a href="<?php the_permalink();?>" title="<?php printf('%s',the_title_attribute('echo=0')); ?>" rel="bookmark">
								<?php echo get_aq_resize_img('full',60,60);?>
							</a>
						</div>
					<?php }?>
					<div class="questions-div">
						<h3>
							<a href="<?php the_permalink();?>" title="<?php printf('%s',the_title_attribute('echo=0')); ?>" rel="bookmark">
								<?php if ($questions_excerpt == 0) {?>
									<i class="icon-angle-right"></i>
								<?php }
								excerpt_title($excerpt_title);?>
							</a>
						</h3>
						<?php if ($questions_excerpt != 0) {?>
							<p><?php excerpt($questions_excerpt);?></p>
						<?php }
						if ($display_date == "on") {?>
							<div class="clear"></div><span <?php echo ($questions_excerpt == 0?"class='margin_t_5'":"")?>><?php the_time($date_format);?></span>
						<?php }?>
					</div>
				</li>
			<?php endwhile;
		echo "</ul>";
	endif;
	wp_reset_postdata();
}
/* Vpanel_comments */
function Vpanel_comments($post_or_question = "question",$comments_number = 5,$comment_excerpt = 30) {
	$comments = get_comments(array("post_type" => $post_or_question,"status" => "approve","number" => $comments_number));
	echo "<div class='widget_highest_points widget_comments'><ul>";
		foreach ($comments as $comment) {
			$you_avatar = get_the_author_meta('you_avatar',$comment->user_id);
			$user_profile_page = vpanel_get_user_url($comment->user_id);
		    ?>
		    <li>
		    	<div class="author-img">
		    		<?php if ($comment->user_id != 0) {?>
			    		<a href="<?php echo esc_url($user_profile_page);?>">
	    			<?php }
		    			if ($you_avatar && $comment->user_id != 0) {
		    				$you_avatar_img = get_aq_resize_url(esc_attr($you_avatar),"full",65,65);
		    				echo "<img alt='".esc_attr($comment->comment_author)."' src='".esc_url($you_avatar_img)."'>";
		    			}else {
		    				echo get_avatar(get_the_author_meta('user_email',$comment->user_id),'65','');
		    			}
		    		if ($comment->user_id != 0) {?>
			    		</a>
		    		<?php }?>
		    	</div> 
		    	<h6><a href="<?php echo get_permalink($comment->comment_post_ID);?>#comment-<?php echo esc_attr($comment->comment_ID);?>"><?php echo strip_tags($comment->comment_author);?> : <?php echo wp_html_excerpt($comment->comment_content,$comment_excerpt);?></a></h6>
		    </li>
		    <?php
		}
	echo "</ul></div>";
}
/* infocenter_comment */
function infocenter_comment($comment,$args,$depth) {
	global $k;
	$k++;
    $GLOBALS['comment'] = $comment;
    $add_below = '';
    $comment_id = esc_attr($comment->comment_ID);
    $user_get_current_user_id = get_current_user_id();
    $can_edit_comment = INFOCENTER_OPTIONS("can_edit_comment");
    $can_edit_comment_after = INFOCENTER_OPTIONS("can_edit_comment_after");
    $can_edit_comment_after = (int)(isset($can_edit_comment_after) && $can_edit_comment_after > 0?$can_edit_comment_after:0);
    $time_now = strtotime(current_time( 'mysql' ),date_create_from_format('Y-m-d H:i',current_time( 'mysql' )));
    $time_edit_comment = strtotime('+'.$can_edit_comment_after.' hour',strtotime($comment->comment_date));
    $time_end = ($time_now-$time_edit_comment)/60/60;
    $edit_comment = get_comment_meta($comment_id,"edit_comment",true);
    if (isset($k) && $k == INFOCENTER_OPTIONS("between_comments_position")) {
    	$between_adv_type = INFOCENTER_OPTIONS("between_comments_adv_type");
    	$between_adv_code = INFOCENTER_OPTIONS("between_comments_adv_code");
    	$between_adv_href = INFOCENTER_OPTIONS("between_comments_adv_href");
    	$between_adv_img = INFOCENTER_OPTIONS("between_comments_adv_img");
    	if (($between_adv_type == "display_code" && $between_adv_code != "") || ($between_adv_type == "custom_image" && $between_adv_img != "")) {
    		echo '<li class="advertising">
    			<div class="clearfix"></div>';
    		if ($between_adv_type == "display_code") {
    			echo stripcslashes($between_adv_code);
    		}else {
    			if ($between_adv_href != "") {
    				echo '<a target="_blank" href="'.esc_url($between_adv_href).'">';
    			}
    			echo '<img alt="" src="'.esc_url($between_adv_img).'">';
    			if ($between_adv_href != "") {
    				echo '</a>';
    			}
    		}
    		echo '<div class="clearfix"></div>
    		</li><!-- End advertising -->';
    	}
    }
    ?>
    <li <?php comment_class('comment');?> id="li-comment-<?php comment_ID();?>">
    	<div id="comment-<?php comment_ID();?>" class="comment-body clearfix">
            <div class="avatar-img">
            	<?php if ($comment->user_id != 0){
            		$vpanel_get_user_url = vpanel_get_user_url($comment->user_id,get_the_author_meta('user_nicename', $comment->user_id));
            		if (get_the_author_meta('you_avatar', $comment->user_id)) {
            			$you_avatar_img = get_aq_resize_url(esc_attr(get_the_author_meta('you_avatar', $comment->user_id)),"full",65,65);
            			echo "<img alt='".esc_attr($comment->comment_author)."' src='".esc_url($you_avatar_img)."'>";
            		}else {
            			echo get_avatar($comment,65);
            		}
            	}else {
            		$vpanel_get_user_url = ($comment->comment_author_url != ""?$comment->comment_author_url:"vpanel_No_site");
            		echo get_avatar($comment->comment_author_email,65);
            	}?>
            </div>
            <div class="comment-text">
                <div class="author clearfix">
                	<div class="comment-meta">
        	        	<div class="comment-author">
        	        		<?php if ($vpanel_get_user_url != "" && $vpanel_get_user_url != "vpanel_No_site") {?>
	        	        		<a href="<?php echo esc_url($vpanel_get_user_url)?>">
	        	        	<?php }
	        	        		echo get_comment_author();
	        	        	if ($vpanel_get_user_url != "" && $vpanel_get_user_url != "vpanel_No_site") {?>
	        	        		</a>
	        	        	<?php }
	        	        	if ($comment->user_id != 0) {
	        	        		echo esc_attr(vpanel_get_badge($comment->user_id));
	        	        	}?>
        	        	</div>
                        <div class="date"><i class="icon-time"></i><?php printf(esc_html__('%1$s at %2$s','infocenter'),get_comment_date(), get_comment_time()) ?></div> 
                    </div>
                    <div class="comment-reply">
                    <?php if (current_user_can('edit_comment',$comment_id)) {
                    	edit_comment_link('<i class="icon-pencil"></i>'.esc_html__("Edit","infocenter"),'  ','');
                    }else {
                    	if ($can_edit_comment == 1 && $comment->user_id == $user_get_current_user_id && ($can_edit_comment_after == 0 || $time_end <= $can_edit_comment_after)) {
                    		echo "<a class='comment-edit-link edit-comment' href='".esc_url(add_query_arg("comment_id", $comment_id,get_page_link(INFOCENTER_OPTIONS('edit_comment'))))."'><i class='icon-pencil'></i>Edit</a>";
                    	}
                    }
                    comment_reply_link( array_merge( $args, array( 'reply_text' => '<i class="icon-reply"></i>'.esc_html__( 'Reply', 'infocenter' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );?>
                    </div>
                </div>
                <div class="text">
                	<?php if ($edit_comment == "edited") {?>
                		<em><?php esc_html_e('This comment is edited.','infocenter')?></em><br>
                	<?php }
                	if ($comment->comment_approved == '0') : ?>
                	    <em><?php esc_html_e('Your comment is awaiting moderation.','infocenter')?></em><br>
                	<?php endif;
                	comment_text();?>
                </div>
            </div>
        </div>
    <?php
}
/* infocenter_answer */
function infocenter_answer($comment,$args,$depth) {
	global $post,$k;
	$k++;
	$GLOBALS['comment'] = $comment;
	$add_below = '';
	$comment_id = esc_attr($comment->comment_ID);
	$comment_vote = get_comment_meta($comment_id,'comment_vote');
	$comment_vote = (!empty($comment_vote)?$comment_vote[0]["vote"]:"");
	$the_best_answer = get_post_meta($post->ID,"the_best_answer",true);
	$best_answer_comment = get_comment_meta($comment_id,"best_answer_comment",true);
	$comment_best_answer = ($best_answer_comment == "best_answer_comment" || $the_best_answer == $comment_id?"comment-best-answer":"");
	$active_reports = INFOCENTER_OPTIONS("active_reports");
	$active_vote = INFOCENTER_OPTIONS("active_vote");
	$user_get_current_user_id = get_current_user_id();
	$can_edit_comment = INFOCENTER_OPTIONS("can_edit_comment");
	$can_edit_comment_after = INFOCENTER_OPTIONS("can_edit_comment_after");
	$can_edit_comment_after = (int)(isset($can_edit_comment_after) && $can_edit_comment_after > 0?$can_edit_comment_after:0);
	$time_now = strtotime(current_time( 'mysql' ),date_create_from_format('Y-m-d H:i',current_time( 'mysql' )));
	$time_edit_comment = strtotime('+'.$can_edit_comment_after.' hour',strtotime($comment->comment_date));
	$time_end = ($time_now-$time_edit_comment)/60/60;
	$edit_comment = get_comment_meta($comment_id,"edit_comment",true);
	if (isset($k) && $k == INFOCENTER_OPTIONS("between_comments_position")) {
		$between_adv_type = INFOCENTER_OPTIONS("between_comments_adv_type");
		$between_adv_code = INFOCENTER_OPTIONS("between_comments_adv_code");
		$between_adv_href = INFOCENTER_OPTIONS("between_comments_adv_href");
		$between_adv_img = INFOCENTER_OPTIONS("between_comments_adv_img");
		if (($between_adv_type == "display_code" && $between_adv_code != "") || ($between_adv_type == "custom_image" && $between_adv_img != "")) {
			echo '<li class="advertising">
				<div class="clearfix"></div>';
			if ($between_adv_type == "display_code") {
				echo stripcslashes($between_adv_code);
			}else {
				if ($between_adv_href != "") {
					echo '<a target="_blank" href="'.esc_url($between_adv_href).'">';
				}
				echo '<img alt="" src="'.esc_url($between_adv_img).'">';
				if ($between_adv_href != "") {
					echo '</a>';
				}
			}
			echo '<div class="clearfix"></div>
			</li><!-- End advertising -->';
		}
	}
    ?>
    <li <?php comment_class('comment '.$comment_best_answer);?> id="li-comment-<?php comment_ID();?>">
    	<div id="comment-<?php comment_ID();?>" class="comment-body clearfix" rel="post-<?php echo esc_attr($post->ID);?>">
    	    <div class="avatar-img">
    	    	<?php if ($comment->user_id != 0) {
    	    		$vpanel_get_user_url = vpanel_get_user_url($comment->user_id,get_the_author_meta('user_nicename', $comment->user_id));
    	    		if (get_the_author_meta('you_avatar', $comment->user_id)) {
    	    			$you_avatar_img = get_aq_resize_url(esc_attr(get_the_author_meta('you_avatar', $comment->user_id)),"full",65,65);
    	    			echo "<img alt='".esc_attr($comment->comment_author)."' src='".esc_url($you_avatar_img)."'>";
    	    		}else {
    	    			echo get_avatar($comment,65);
    	    		}
    	    	}else {
    	    		$vpanel_get_user_url = ($comment->comment_author_url != ""?$comment->comment_author_url:"vpanel_No_site");
    	    		echo get_avatar($comment->comment_author_email,65);
    	    	}?>
    	    </div>
			
    	    <div class="comment-text">
    	        <div class="author clearfix">
    	        	<div class="comment-author">
    	        		<div class="fnone">
						<?php if ($vpanel_get_user_url != "" && $vpanel_get_user_url != "vpanel_No_site") {?>
    	        			<a href="<?php echo esc_url($vpanel_get_user_url)?>">
    	        		<?php }
    	        			echo get_comment_author();
    	        		if ($vpanel_get_user_url != "" && $vpanel_get_user_url != "vpanel_No_site") {?>
    	        			</a>
    	        		<?php }
    	        		if ($comment->user_id != 0) {
    	        			echo esc_attr(vpanel_get_badge($comment->user_id));
    	        		}?>
						</div>
						<?php if ($active_vote == 1) {?>
						<?php if ($comment_vote != 0) { ?>
						<div class="date fnone"><?php esc_html_e("Got  ","infocenter")?><span class="fnone question-vote-result question_vote_result <?php echo ($comment_vote < 0?"question_vote_red":"")?>"><?php echo ($comment_vote != ""?esc_attr($comment_vote):0)?></span><?php esc_html_e("  Votes","infocenter")?></div>
						<?php } } ?>
					</div>
					<div class="comment-meta">
    	                
						<div class="date"><i class="icon-time"></i><?php printf(esc_html__('%1$s at %2$s','infocenter'),get_comment_date(), get_comment_time()) ?></div> 
    	            </div>
    	        	
    	        	<?php if ($best_answer_comment != "best_answer_comment" && $the_best_answer != $comment_id) { ?>
    	            <div class="comment-reply">
	    	            <?php if (current_user_can('edit_comment',$comment_id)) {
	    	            	edit_comment_link('<i class="icon-pencil"></i>'.esc_html__("Edit","infocenter"),'  ','');
	    	            }else {
	    	            	if ($can_edit_comment == 1 && $comment->user_id == $user_get_current_user_id && ($can_edit_comment_after == 0 || $time_end <= $can_edit_comment_after)) {
	    	            		echo "<a class='comment-edit-link edit-comment' href='".esc_url(add_query_arg("comment_id", $comment_id,get_page_link(INFOCENTER_OPTIONS('edit_comment'))))."'><i class='icon-pencil'></i>Edit</a>";
	    	            	}
	    	            }
						
	    	            if ($active_reports == 1) {?>
    	                	<a class="question_r_l comment_l report_c" href="#"><i class="icon-flag"></i><?php esc_html_e("Report","infocenter")?></a>
    	                <?php }
    	                comment_reply_link( array_merge( $args, array( 'reply_text' => '<i class="icon-reply"></i>'.esc_html__( 'Reply', 'infocenter' ), 'after' => '', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) );
						?>
    	            </div>
					<?php } ?>
					
    	        </div>
    	        <div class="text">
				
    	        	<?php if ($active_reports == 1) {?>
	    	        	<div class="infocenter-question-reporting">
	    	        		<h3><?php esc_html_e("Please briefly explain why you feel this answer should be reported .","infocenter")?></h3>
	    	        		<textarea name="infocenter-question-reporting"></textarea>
	    	        		<div class="clearfix"></div>
	    	        		<div class="loader_3"></div>
	    	        		<a class="color button small report"><?php esc_html_e("Report","infocenter")?></a>
	    	        		<a class="color button small dark_button cancel"><?php esc_html_e("Cancel","infocenter")?></a>
	    	        	</div><!-- End reported -->
    	        	<?php }
    	        	if ($edit_comment == "edited") {?>
    	        		<em><?php esc_html_e('This comment is edited.','infocenter')?></em><br>
    	        	<?php }
    	        	if ($comment->comment_approved == '0') : ?>
    	        	    <em><?php esc_html_e('Your comment is awaiting moderation.','infocenter')?></em><br>
    	        	<?php endif;
    	        	comment_text();?>
    	        	<div class="clearfix"></div>
    	        	<?php $added_file = get_comment_meta($comment_id,'added_file', true);
    	        	if ($added_file != "") {
    	        		echo "<div class='clearfix'></div><br><a href='".wp_get_attachment_url($added_file)."'>".esc_html__("Attachment","infocenter")."</a>";
    	        	}
    	        	?>
    	        </div>
				
    	        <div class="clearfix"></div>
				
	        	<div class="loader_3"></div>
    	        <?php
    	        $admin_best_answer = INFOCENTER_OPTIONS("admin_best_answer");
    	        if ($best_answer_comment == "best_answer_comment" || $the_best_answer == $comment_id) {
    	        	echo '<div class="commentform ribbon base"><span>'.esc_html__("Best Answer","infocenter").'</span></div>
    	        	<div class="clearfix"></div>';
    	        	if (((is_user_logged_in() && $user_get_current_user_id == $post->post_author) || ($admin_best_answer == 1 && is_super_admin($user_get_current_user_id))) && $the_best_answer != 0){
	    	        	echo '<a class="commentform best_answer_re question-report" title="'.esc_html__("Cancel the best answer","infocenter").'" href="#">'.esc_html__("Cancel the best answer","infocenter").'</a>';
    	        	}
    	        }
    	        if (((is_user_logged_in() && $user_get_current_user_id == $post->post_author) || ($admin_best_answer == 1 && is_super_admin($user_get_current_user_id))) && ($the_best_answer == 0 or $the_best_answer == "")){?>
    	        	<a class="commentform best_answer_a question-report" title="<?php esc_html_e("Select as best answer","infocenter");?>" href="#"><?php esc_html_e("Select as best answer","infocenter");?></a>
    	        <?php
    	        }
    	        ?>
    	        
				<div class="text">
				<?php if ($active_vote == 1) {?>	    	        	
						<div class="comment-vote">
	    	            	<ul class="single-question-vote">
	    	            		<?php if (is_user_logged_in()){?>
	    	            			<li class="loader_3"></li>
	    	            			<li><a href="#" class="single-question-vote-up comment_vote_up<?php echo (isset($_COOKIE['comment_vote'.$comment_id])?" ".$_COOKIE['comment_vote'.$comment_id]."-".$comment_id:"")?>" title="<?php esc_html_e("Like","infocenter");?>" id="comment_vote_up-<?php echo esc_attr($comment_id);?>"><i class="fa fa-thumbs-up"></i></a></li>									
	    	            			<li><a href="#" class="single-question-vote-down comment_vote_down<?php echo (isset($_COOKIE['comment_vote'.$comment_id])?" ".$_COOKIE['comment_vote'.$comment_id]."-".$comment_id:"")?>" id="comment_vote_down-<?php echo esc_attr($comment_id);?>" title="<?php esc_html_e("Dislike","infocenter");?>"><i class="fa fa-thumbs-down"></i></a></li>
	    	            		<?php }else { ?>
	    	            			<li class="loader_3"></li>
	    	            			<li><a href="#" class="single-question-vote-up comment_vote_up vote_not_user" title="<?php esc_html_e("Like","infocenter");?>"><i class="icon-thumbs-up"></i></a></li>
	    	            			<li><a href="#" class="single-question-vote-down comment_vote_down vote_not_user" title="<?php esc_html_e("Dislike","infocenter");?>"><i class="icon-thumbs-down"></i></a></li>
	    	            		<?php }?>
	    	            	</ul>
	    	        	</div>	    	        	
    	        	<?php }?>
					</div>
				<div class="no_vote_more"></div>
    	    </div>
    	</div>
	<?php
}
/* vpanel_pagination */
if ( ! function_exists('vpanel_pagination')) {
	function vpanel_pagination( $args = array(),$query = '') {
		global $wp_rewrite,$wp_query;
		do_action('vpanel_pagination_start');
		if ( $query) {
			$wp_query = $query;
		} // End IF Statement
		/* If there's not more than one page,return nothing. */
		if ( 1 >= $wp_query->max_num_pages)
			return;
		/* Get the current page. */
		$current = (get_query_var("paged") != ""?(int)get_query_var("paged"):(get_query_var("page") != ""?(int)get_query_var("page"):1));
		$page_what = (get_query_var("paged") != ""?"paged":(get_query_var("page") != ""?"page":"paged"));
		/* Get the max number of pages. */
		$max_num_pages = intval( $wp_query->max_num_pages);
		/* Set up some default arguments for the paginate_links() function. */
		$defaults = array(
			'base' => esc_url(add_query_arg($page_what,'%#%')),
			'format' => '',
			'total' => $max_num_pages,
			'current' => $current,
			'prev_next' => true,
			'prev_text' => '<i class="icon-angle-left"></i>',
			'next_text' => '<i class="icon-angle-right"></i>',
			'show_all' => false,
			'end_size' => 1,
			'mid_size' => 1,
			'add_fragment' => '',
			'type' => 'plain',
			'before' => '<div class="textcenter"><div class="pagination">',// Begin vpanel_pagination() arguments.
			'after' => '</div></div>',
			'echo' => true,
		);
		/* Add the $base argument to the array if the user is using permalinks. */
		if ( $wp_rewrite->using_permalinks())
			$defaults['base'] = user_trailingslashit( trailingslashit( get_pagenum_link()) . 'page/%#%');
		/* If we're on a search results page,we need to change this up a bit. */
		if ( is_search()) {
		/* If we're in BuddyPress,use the default "unpretty" URL structure. */
			if ( class_exists('BP_Core_User')) {
				$search_query = get_query_var('s');
				$paged = get_query_var('paged');
				$base = user_trailingslashit( home_url()) . '?s=' . $search_query . '&paged=%#%';
				$defaults['base'] = $base;
			} else {
				$search_permastruct = $wp_rewrite->get_search_permastruct();
				if ( !empty( $search_permastruct))
					$defaults['base'] = user_trailingslashit( trailingslashit( get_search_link()) . 'page/%#%');
			}
		}
		/* Merge the arguments input with the defaults. */
		$args = wp_parse_args( $args,$defaults);
		/* Allow developers to overwrite the arguments with a filter. */
		$args = apply_filters('vpanel_pagination_args',$args);
		/* Don't allow the user to set this to an array. */
		if ('array' == $args['type'])
			$args['type'] = 'plain';
		/* Make sure raw querystrings are displayed at the end of the URL,if using pretty permalinks. */
		$pattern = '/\?(.*?)\//i';
		preg_match( $pattern,$args['base'],$raw_querystring);
		if ( $wp_rewrite->using_permalinks() && $raw_querystring)
			$raw_querystring[0] = str_replace('','',$raw_querystring[0]);
			if (!empty($raw_querystring)) {
				@$args['base'] = str_replace( $raw_querystring[0],'',$args['base']);
				@$args['base'] .= substr( $raw_querystring[0],0,-1);
			}
		/* Get the paginated links. */
		$page_links = paginate_links( $args);
		/* Remove 'page/1' from the entire output since it's not needed. */
		$page_links = str_replace( array('&#038;paged=1\'','/page/1\''),'\'',$page_links);
		/* Wrap the paginated links with the $before and $after elements. */
		$page_links = $args['before'] . $page_links . $args['after'];
		/* Allow devs to completely overwrite the output. */
		$page_links = apply_filters('vpanel_pagination',$page_links);
		do_action('vpanel_pagination_end');
		/* Return the paginated links for use in themes. */
		if ( $args['echo']) {
			$allowed_html = array(
				'div' => array(
					'class' => array()
				),
				'span' => array(
					'class' => array()
				),
				'a' => array(
					'href' => array(),
					'class' => array()
				),
				'i' => array(
					'class' => array()
				),
				);
			echo wp_kses($page_links, $allowed_html);
		} else {
			return $page_links;
		}
	}
}
/* vpanel_admin_bar */
function vpanel_admin_bar() {
	global $wp_admin_bar;
	if (is_super_admin()) {
		$count_posts_by_type = count_posts_by_type( "question", "draft" );
		if ($count_posts_by_type > 0) {
			$wp_admin_bar->add_menu( array(
				'parent' => 0,
				'id' => 'questions_draft',
				'title' => '<span class="ab-icon dashicons-before dashicons-admin-page"></span><span class=" count-'.$count_posts_by_type.'"><span class="">'.$count_posts_by_type.'</span></span>' ,
				'href' => admin_url( 'edit.php?post_status=draft&post_type=question')
			));
		}
	}
}
add_action( 'wp_before_admin_bar_render', 'vpanel_admin_bar' );
/* breadcrumbs */
function breadcrumbs($args = array()) {
	$infocenter_knowledgebase_style = INFOCENTER_OPTIONS("infocenter_knowledgebase_style");
    $allowed_html = array(
				'div' => array(
					'class' => array()
				),
				'span' => array(
					'class' => array()
				),
				'a' => array(
					'href' => array(),
					'itemprop' => array(),
					'class' => array()
				),
				'i' => array(
					'class' => array()
				),
				'h1' => array(
					'class' => array()
				),
				);
	$delimiter  = '<span class="crumbs-span">/</span>';
    $home       = esc_html__('Home','infocenter');
	if ($infocenter_knowledgebase_style != 1) {
    $before     = '<h1>';
    $after      = '</h1>';
	} elseif ($infocenter_knowledgebase_style == 1){
	if (!is_single() && !is_page() || is_page_template("template-ask_question.php") || is_page_template("knowledgebase/infotex_faq.php")){
		$before     = '<h1>';
		$after      = '</h1>';
		} else {
		$before     = '<h1 class="nodisplay">';
		$after      = '</h1>';
		}
	}
    if (!is_home() && !is_front_page() || is_paged()) {
        
        global $post,$wp_query;
        $item = array();
        $homeLink = home_url();
        if (is_search()) {
        	echo wp_kses($before, $allowed_html) . esc_html__("Search","infocenter") . $after;
        }else if (is_page()) {
        	echo wp_kses($before, $allowed_html) . get_the_title() . $after;
        }else if (is_attachment()) {
			$parent = get_post($post->post_parent);
			$cat = get_the_category($parent->ID);
			echo wp_kses($before, $allowed_html) . get_the_title() . $after;
        }elseif ( is_singular() ) {
    		$post = $wp_query->get_queried_object();
    		$post_id = (int) $wp_query->get_queried_object_id();
    		$post_type = $post->post_type;
    		$post_type_object = get_post_type_object( $post_type );
    		if ( 'post' === $wp_query->post->post_type || 'question' === $wp_query->post->post_type || 'product' === $wp_query->post->post_type ) {
    			echo wp_kses($before, $allowed_html) . get_the_title() . $after;
    		}
    		if ( 'page' !== $wp_query->post->post_type ) {
    			if ( isset( $args["singular_{$wp_query->post->post_type}_taxonomy"] ) && is_taxonomy_hierarchical( $args["singular_{$wp_query->post->post_type}_taxonomy"] ) ) {
    				$terms = wp_get_object_terms( $post_id, $args["singular_{$wp_query->post->post_type}_taxonomy"] );
    				echo array_merge( $item, breadcrumbs_plus_get_term_parents( $terms[0], $args["singular_{$wp_query->post->post_type}_taxonomy"] ) );
    			}
    			elseif ( isset( $args["singular_{$wp_query->post->post_type}_taxonomy"] ) )
    				echo get_the_term_list( $post_id, $args["singular_{$wp_query->post->post_type}_taxonomy"], '', ', ', '' );
    		}
    	}else if (is_category() || is_tag() || is_tax()) {
            global $wp_query;
            $term = $wp_query->get_queried_object();
			$taxonomy = get_taxonomy( $term->taxonomy );
			if ( ( is_taxonomy_hierarchical( $term->taxonomy ) && $term->parent ) && $parents = breadcrumbs_plus_get_term_parents( $term->parent, $term->taxonomy ) )
				$item = array_merge( $item, $parents );
            echo wp_kses($before, $allowed_html) . '' . single_cat_title('', false) . '' . $after;
        }elseif (is_day()) {
            echo wp_kses($before, $allowed_html) . esc_html__('Daily Archives : ','infocenter') . get_the_time('d') . $after;
        }elseif (is_month()) {
            echo wp_kses($before, $allowed_html) . esc_html__('Monthly Archives : ','infocenter') . get_the_time('F') . $after;
        }elseif (is_year()) {
            echo wp_kses($before, $allowed_html) . esc_html__('Yearly Archives : ','infocenter') . get_the_time('Y') . $after;
        }elseif (is_single() && !is_attachment()) {
            if (get_post_type() != 'post' && get_post_type() != 'question' && get_post_type() != 'product') {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                echo wp_kses($before, $allowed_html) . get_the_title() . $after;
            }else {
            	$cat = get_the_category(); $cat = $cat[0];
            	echo wp_kses($before, $allowed_html) . get_the_title() . $after;
            }
        }elseif (!is_single() && !is_page() && get_post_type() != 'post' && get_post_type() != 'question' && get_post_type() != 'product') {
        	if (is_author()) {
        	    global $author;
				$userdata = get_userdata($author);
				echo wp_kses($before, $allowed_html) . $userdata->display_name . $after;
        	}else {
				$post_type = get_post_type_object(get_post_type());
				echo wp_kses($before, $allowed_html) . (isset($post_type->labels->singular_name)?$post_type->labels->singular_name:esc_html__("Error 404","infocenter")) . $after;
        	}
        }elseif (is_attachment()) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
            echo wp_kses($before, $allowed_html) . get_the_title() . $after;
        }elseif (is_page() && !$post->post_parent) {
            echo wp_kses($before, $allowed_html) . get_the_title() . $after;
        }elseif (is_page() && $post->post_parent) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            foreach ($breadcrumbs as $crumb) echo wp_kses($crumb, $allowed_html) . ' ' . $delimiter . ' ';
            echo wp_kses($before, $allowed_html) . get_the_title() . $after;
        }elseif (is_search()) {
            echo wp_kses($before, $allowed_html) . get_search_query() . $after;
        }elseif (is_tag()) {
            echo wp_kses($before, $allowed_html) . single_tag_title('', false) . $after;
        }elseif ( is_author() ) {
            global $author;
            $userdata = get_userdata($author);
            echo wp_kses($before, $allowed_html) . $userdata->display_name . $after;
        }elseif (is_404()) {
            echo wp_kses($before, $allowed_html) . esc_html__('Error 404 ', 'infocenter') . $after;
        }else if (is_archive()) {
        	if ( is_category() || is_tag() || is_tax() ) {
    			$term = $wp_query->get_queried_object();
    			$taxonomy = get_taxonomy( $term->taxonomy );
    			if ( ( is_taxonomy_hierarchical( $term->taxonomy ) && $term->parent ) && $parents = breadcrumbs_plus_get_term_parents( $term->parent, $term->taxonomy ) )
    				$item = array_merge( $item, $parents );
    			echo wp_kses($before, $allowed_html) . $term->name. $after;
    		}else if ( function_exists( 'is_post_type_archive' ) && is_post_type_archive() ) {
    			$infocenter_questionpage_title = INFOCENTER_OPTIONS("infocenter_questionpage_title");
				$post_type_object = get_post_type_object( get_query_var( 'post_type' ) );
    			echo wp_kses($before, $allowed_html) . $infocenter_questionpage_title . $after;
    		}else if ( is_date() ) {
    			if ( is_day() )
    				echo wp_kses($before, $allowed_html) . esc_html__( 'Archives for ', 'infocenter' ) . get_the_time( 'F j, Y' ). $after;
    			elseif ( is_month() )
    				echo wp_kses($before, $allowed_html) . esc_html__( 'Archives for ', 'infocenter' ) . single_month_title( ' ', false ). $after;
    			elseif ( is_year() )
    				echo wp_kses($before, $allowed_html) . esc_html__( 'Archives for ', 'infocenter' ) . get_the_time( 'Y' ). $after;
    		}else if ( is_author() ) {
    			echo wp_kses($before, $allowed_html) . esc_html__( 'Archives by: ', 'infocenter' ) . get_the_author_meta( 'display_name', $wp_query->post->post_author ). $after;
    		}
        }
        $before     = '<span class="current">';
        $after      = '</span>';
        echo '<div class="clearfix"></div>';
		if ($infocenter_knowledgebase_style != 1) {
        echo '<div class="crumbs">';
		} elseif ($infocenter_knowledgebase_style == 1) {
		if (!is_single() && !is_page() || is_page_template("template-ask_question.php") || is_page_template("knowledgebase/infotex_faq.php")){
		echo '<div class="crumbs">';
		} else { echo '<div class="crumbs fleft">'; }
		} echo '
        <a href="' . esc_url($homeLink) . '">' . $home . '</a>' . $delimiter . ' ';
        if (is_search()) {
        	echo wp_kses($before, $allowed_html) . esc_html__("Search","infocenter") . $after;
        }else if (is_category() || is_tag() || is_tax()) {
            global $wp_query;
            $term = $wp_query->get_queried_object();
        	$taxonomy = get_taxonomy( $term->taxonomy );
        	if ( ( is_taxonomy_hierarchical( $term->taxonomy ) && $term->parent ) && $parents = breadcrumbs_plus_get_term_parents( $term->parent, $term->taxonomy ) )
        		$item = array_merge( $item, $parents );
            echo wp_kses($before, $allowed_html) . '' . single_cat_title('', false) . '' . $after;
        }elseif (is_day()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $delimiter . '';
            echo '<a href="' . get_month_link(get_the_time('Y'),get_the_time('m')) . '">' . get_the_time('F') . '</a>' . $delimiter . '';
            echo wp_kses($before, $allowed_html) . get_the_time('d') . $after;
        }elseif (is_month()) {
            echo '<a href="' . get_year_link(get_the_time('Y')) . '">' . get_the_time('Y') . '</a>' . $delimiter . '';
            echo wp_kses($before, $allowed_html) . get_the_time('F') . $after;
        }elseif (is_year()) {
            echo wp_kses($before, $allowed_html) . get_the_time('Y') . $after;
        }elseif (is_single() && !is_attachment()) {
            if (get_post_type() != 'post') {
                $post_type = get_post_type_object(get_post_type());
                $slug = $post_type->rewrite;
                if (get_post_type() == 'question') {
                    if( function_exists( 'question_post_types_init' ) ) {
					$question_category = wp_get_post_terms($post->ID,'question-category',array("fields" => "all"));
					} else {
					$question_category = wp_get_post_terms($post->ID,'post_tag',array("fields" => "all"));	
					}
                    if (isset($question_category[0])) {?>
                        <a href="<?php echo get_term_link($question_category[0]->slug, "question-category");?>"><?php echo esc_attr($question_category[0]->name);?></a>
                    <?php
                    }
                    echo wp_kses($delimiter, $allowed_html);
                }else if (get_post_type() == 'product') {
                    global $product;
                    echo esc_attr($product->get_categories( ', ', '' ));
                    echo wp_kses($delimiter, $allowed_html);
                }else {
	                echo '<a href="' . esc_url($homeLink) . '/' . $slug['slug'] . '/">' . $post_type->labels->singular_name . '</a>' . $delimiter . '';
                }
                echo "".$before . get_the_title() . $after;
            }else {
                $cat = get_the_category(); $cat = $cat[0];
                echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
                echo wp_kses($before, $allowed_html) . get_the_title() . $after;
            }
        }elseif (!is_single() && !is_page() && get_post_type() != 'post') {
            if (is_author()) {
                global $author;
				$userdata = get_userdata($author);
				echo wp_kses($before, $allowed_html) . $userdata->display_name . $after;
            }else {
	            $post_type = get_post_type_object(get_post_type());
            	echo wp_kses($before, $allowed_html) . (isset($post_type->labels->singular_name)?$post_type->labels->singular_name:esc_html__("Error 404","infocenter")) . $after;
            }
        }elseif (is_attachment()) {
            $parent = get_post($post->post_parent);
            $cat = get_the_category($parent->ID);
            echo '<a href="' . get_permalink($parent) . '">' . $parent->post_title . '</a>' . $delimiter . '';
            echo wp_kses($before, $allowed_html) . get_the_title() . $after;
        }elseif (is_page() && !$post->post_parent) {
            echo wp_kses($before, $allowed_html) . get_the_title() . $after;
        }elseif (is_page() && $post->post_parent) {
            $parent_id  = $post->post_parent;
            $breadcrumbs = array();
            while ($parent_id) {
                $page = get_page($parent_id);
                $breadcrumbs[] = '<a href="' . get_permalink($page->ID) . '">' . get_the_title($page->ID) . '</a>';
                $parent_id  = $page->post_parent;
            }
            $breadcrumbs = array_reverse($breadcrumbs);
            foreach ($breadcrumbs as $crumb) echo wp_kses($crumb, $allowed_html) . ' ' . $delimiter . ' ';
            echo wp_kses($before, $allowed_html) . get_the_title() . $after;
        }elseif (is_search()) {
            echo wp_kses($before, $allowed_html) . esc_html__('Search results for ', 'infocenter') . '"' . get_search_query() . '"' . $after;
        }elseif (is_tag()) {
            echo wp_kses($before, $allowed_html) . esc_html__('Posts tagged ', 'infocenter') . '"' . single_tag_title('', false) . '"' . $after;
        }elseif ( is_author() ) {
            global $author;
            $userdata = get_userdata($author);
            echo wp_kses($before, $allowed_html) . $userdata->display_name . $after;
        }elseif (is_404()) {
            echo wp_kses($before, $allowed_html) . esc_html__('Error 404 ', 'infocenter') . $after;
        }
        if (get_query_var('paged')) {
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo '';
            echo "<span class='crumbs-span'>/</span><span class='current'>".esc_html__('Page', 'infocenter') . ' ' . get_query_var('paged')."</span>";
            if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo '';
        }
        echo '</div>';
    } elseif (is_front_page()) {
		echo wp_kses($before, $allowed_html) . get_bloginfo( 'name' ) . $after;
	} elseif (is_home()) {
		echo wp_kses($before, $allowed_html) . get_bloginfo( 'name' ) . $after;
	}
}
/* breadcrumbs_plus_get_term_parents */
function breadcrumbs_plus_get_term_parents( $parent_id = '', $taxonomy = '', $separator = '/' ) {
	$html = array();
	$parents = array();
	if ( empty( $parent_id ) || empty( $taxonomy ) )
		return $parents;
	while ( $parent_id ) {
		$parent = get_term( $parent_id, $taxonomy );
		$parents[] = '<a href="' . get_term_link( $parent, $taxonomy ) . '" title="' . esc_attr( $parent->name ) . '">' . $parent->name . '</a>';
		$parent_id = $parent->parent;
	}
	if ( $parents )
		$parents = array_reverse( $parents );
	return $parents;
}
/* vpanel_show_extra_profile_fields */
add_action( 'show_user_profile', 'vpanel_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'vpanel_show_extra_profile_fields' );
function vpanel_show_extra_profile_fields( $user ) { ?>
	<table class="form-table">
		<tr>
			<th><label for="you_avatar"><?php esc_html_e("Your avatar","infocenter")?></label></th>
			<td>
				<input type="text" size="36" class="upload upload_meta regular-text" value="<?php echo esc_attr( get_the_author_meta('you_avatar', $user->ID ) ); ?>" id="you_avatar" name="you_avatar">
				<input id="you_avatar_button" class="upload_image_button button upload-button-2" type="button" value="Upload Image">
			</td>
		</tr>
		<?php if (get_the_author_meta('you_avatar', $user->ID )) {?>
			<tr>
				<th><label><?php esc_html_e("Your avatar","infocenter")?></label></th>
				<td>
					<div class="you_avatar"><img alt="" src="<?php echo esc_attr( get_the_author_meta('you_avatar', $user->ID ) ); ?>"></div>
				</td>
			</tr>
		<?php } ?>
		<tr>
			<th><label for="country"><?php esc_html_e("Country","infocenter")?></label></th>
			<td>
				<select name="country" id="country">
					<option value=""><?php esc_html_e( 'Select a country&hellip;', 'infocenter' )?></option>
						<?php foreach( vpanel_get_countries() as $key => $value )
							echo '<option value="' . esc_attr( $key ) . '"' . selected( esc_attr( get_the_author_meta( 'country', $user->ID ) ), esc_attr( $key ), false ) . '>' . esc_html( $value ) . '</option>';?>
				</select>
			</td>
		</tr>
		<tr>
			<th><label for="city"><?php esc_html_e("City","infocenter")?></label></th>
			<td>
				<input type="text" name="city" id="city" value="<?php echo esc_attr( get_the_author_meta( 'city', $user->ID ) ); ?>" class="regular-text"><br>
			</td>
		</tr>
		<tr>
			<th><label for="age"><?php esc_html_e("Age","infocenter")?></label></th>
			<td>
				<input type="text" name="age" id="age" value="<?php echo esc_attr( get_the_author_meta( 'age', $user->ID ) ); ?>" class="regular-text"><br>
			</td>
		</tr>
		<tr>
			<th><label for="phone"><?php esc_html_e("Phone","infocenter")?></label></th>
			<td>
				<input type="text" name="phone" id="phone" value="<?php echo esc_attr( get_the_author_meta( 'phone', $user->ID ) ); ?>" class="regular-text"><br>
			</td>
		</tr>
		<tr>
			<?php
			$sex = esc_attr(get_the_author_meta( 'sex', $user->ID ) );
			?>
			<th><label><?php esc_html_e("Sex","infocenter")?></label></th>
			<td>
				<input id="sex_male" name="sex" type="radio" value="1"'<?php echo (isset($sex) && ($sex == "male" || $sex == "1")?' checked="checked"':' checked="checked"')?>'>
				<label for="sex_male"><?php esc_html_e("Male","infocenter")?></label>
				
				<input id="sex_female" name="sex" type="radio" value="2"<?php echo (isset($sex) && ($sex == "female" || $sex == "2")?' checked="checked"':'')?>>
					<label for="sex_female"><?php esc_html_e("Female","infocenter")?></label>
			</td>
		</tr>
		<tr>
			<th><label for="follow_email"><?php esc_html_e("Follow-up email","infocenter")?></label></th>
			<td>
				<input type="text" name="follow_email" id="follow_email" value="<?php echo esc_attr( get_the_author_meta( 'follow_email', $user->ID ) ); ?>" class="regular-text"><br>
			</td>
		</tr>
	</table>
	<h3><?php esc_html_e("Show the points, favorite question, authors i follow, followers, question follow, answer follow, post follow and comment follow","infocenter")?></h3>
	<table class="form-table">
		<tr>
			<?php $show_point_favorite = esc_attr( get_the_author_meta( 'show_point_favorite', $user->ID ) )?>
			<th><label for="show_point_favorite"><?php esc_html_e("Show this pages only for me or any one?","infocenter")?></label></th>
			<td>
				<input type="checkbox" name="show_point_favorite" id="show_point_favorite" value="1" <?php checked($show_point_favorite,1,true)?>><br>
			</td>
		</tr>
	</table>
	<?php $send_email_question_groups = INFOCENTER_OPTIONS("send_email_question_groups");
	if (isset($send_email_question_groups) && is_array($send_email_question_groups)) {
		foreach ($send_email_question_groups as $key => $value) {
			if ($value == 1) {
				$send_email_question_groups[$key] = $key;
			}else {
				unset($send_email_question_groups[$key]);
			}
		}
	}
	if (is_array($send_email_question_groups) && in_array($user->roles[0],$send_email_question_groups)) {?>
		<h3><?php esc_html_e("Received email when any one add a new question","infocenter")?></h3>
		<table class="form-table">
			<tr>
				<?php $received_email = esc_attr( get_the_author_meta( 'received_email', $user->ID ) )?>
				<th><label for="received_email"><?php esc_html_e("Received email?","infocenter")?></label></th>
				<td>
					<input type="checkbox" name="received_email" id="received_email" value="1" <?php checked($received_email,1,true)?>><br>
				</td>
			</tr>
		</table>
	<?php }?>
	<h3><?php esc_html_e( 'Social Networking', 'infocenter' ) ?></h3>
	<table class="form-table">
		<tr>
			<th><label for="google"><?php esc_html_e("Google +","infocenter")?></label></th>
			<td>
				<input type="text" name="google" id="google" value="<?php echo esc_attr( get_the_author_meta( 'google', $user->ID ) ); ?>" class="regular-text"><br>
			</td>
		</tr>
		<tr>
			<th><label for="twitter"><?php esc_html_e("Twitter","infocenter")?></label></th>
			<td>
				<input type="text" name="twitter" id="twitter" value="<?php echo esc_attr( get_the_author_meta( 'twitter', $user->ID ) ); ?>" class="regular-text"><br>
			</td>
		</tr>
		<tr>
			<th><label for="facebook"><?php esc_html_e("Facebook","infocenter")?></label></th>
			<td>
				<input type="text" name="facebook" id="facebook" value="<?php echo esc_attr( get_the_author_meta( 'facebook', $user->ID ) ); ?>" class="regular-text"><br>
			</td>
		</tr>
		<tr>
			<th><label for="youtube"><?php esc_html_e("Youtube","infocenter")?></label></th>
			<td>
				<input type="text" name="youtube" id="youtube" value="<?php echo esc_attr( get_the_author_meta( 'youtube', $user->ID ) ); ?>" class="regular-text"><br>
			</td>
		</tr>
		<tr>
			<th><label for="linkedin"><?php esc_html_e("linkedin","infocenter")?></label></th>
			<td>
				<input type="text" name="linkedin" id="linkedin" value="<?php echo esc_attr( get_the_author_meta( 'linkedin', $user->ID ) ); ?>" class="regular-text"><br>
			</td>
		</tr>
		<tr>
			<th><label for="pinterest"><?php esc_html_e("Pinterest","infocenter")?></label></th>
			<td>
				<input type="text" name="pinterest" id="pinterest" value="<?php echo esc_attr( get_the_author_meta( 'pinterest', $user->ID ) ); ?>" class="regular-text"><br>
			</td>
		</tr>
		<tr>
			<th><label for="instagram"><?php esc_html_e("Instagram","infocenter")?></label></th>
			<td>
				<input type="text" name="instagram" id="instagram" value="<?php echo esc_attr( get_the_author_meta( 'instagram', $user->ID ) ); ?>" class="regular-text"><br>
			</td>
		</tr>
	</table>
	<?php $active_points = INFOCENTER_OPTIONS("active_points");
	if (is_super_admin(get_current_user_id()) && $active_points == 1) {?>
		<h3><?php esc_html_e( 'Add or remove points for the user', 'infocenter' ) ?></h3>
		<table class="form-table">
			<tr>
				<th><label><?php esc_html_e("Add or remove points","infocenter")?></label></th>
				<td>
					<div>
						<select name="add_remove_point">
							<option value="add"><?php esc_html_e("Add","infocenter")?></option>
							<option value="remove"><?php esc_html_e("Remove","infocenter")?></option>
						</select>
					</div><br>
					<div><?php esc_html_e("The points","infocenter")?></div><br>
					<input type="text" name="the_points" class="regular-text"><br><br>
					<div><?php esc_html_e("The reason","infocenter")?></div><br>
					<input type="text" name="the_reason" class="regular-text"><br>
				</td>
			</tr>
		</table>
	<?php }?>
<?php }
/* Save user's meta */
add_action( 'personal_options_update', 'vpanel_save_extra_profile_fields' );
add_action( 'edit_user_profile_update', 'vpanel_save_extra_profile_fields' );
function vpanel_save_extra_profile_fields( $user_id ) {
	if ( !current_user_can( 'edit_user', $user_id ) ) return false;
	$google = (isset($_POST['google'])?$_POST['google']:"");
	update_user_meta( $user_id, 'google', esc_attr($google));
	$twitter = (isset($_POST['twitter'])?$_POST['twitter']:"");
	update_user_meta( $user_id, 'twitter', esc_attr($twitter));
	$facebook = (isset($_POST['facebook'])?$_POST['facebook']:"");
	update_user_meta( $user_id, 'facebook', esc_attr($facebook));
	$youtube = (isset($_POST['youtube'])?$_POST['youtube']:"");
	update_user_meta( $user_id, 'youtube', esc_attr($youtube));
	$linkedin = (isset($_POST['linkedin'])?$_POST['linkedin']:"");
	update_user_meta( $user_id, 'linkedin', esc_attr($linkedin));
	$instagram = (isset($_POST['instagram'])?$_POST['instagram']:"");
	update_user_meta( $user_id, 'instagram', esc_attr($instagram));
	$pinterest = (isset($_POST['pinterest'])?$_POST['pinterest']:"");
	update_user_meta( $user_id, 'pinterest', esc_attr($pinterest));
	$follow_email = (isset($_POST['follow_email'])?$_POST['follow_email']:"");
	update_user_meta( $user_id, 'follow_email', esc_attr($follow_email));
	$you_avatar = (isset($_POST['you_avatar'])?$_POST['you_avatar']:"");
	update_user_meta( $user_id, 'you_avatar', esc_attr($you_avatar));
	$country = (isset($_POST['country'])?$_POST['country']:"");
	update_user_meta( $user_id, 'country', esc_attr($country));
	$city = (isset($_POST['city'])?$_POST['city']:"");
	update_user_meta( $user_id, 'city', esc_attr($city));
	$age = (isset($_POST['age'])?$_POST['age']:"");
	update_user_meta( $user_id, 'age', esc_attr($age));
	$sex = (isset($_POST['sex'])?$_POST['sex']:"");
	update_user_meta( $user_id, 'sex', esc_attr($sex));
	$phone = (isset($_POST['phone'])?$_POST['phone']:"");
	update_user_meta( $user_id, 'phone', esc_attr($phone));
	
	$show_point_favorite = (isset($_POST['show_point_favorite'])?$_POST['show_point_favorite']:"");
	update_user_meta( $user_id, 'show_point_favorite', esc_attr($show_point_favorite));
	
	$received_email = (isset($_POST['received_email'])?$_POST['received_email']:"");
	update_user_meta( $user_id, 'received_email', esc_attr($received_email));
	
	$active_points = INFOCENTER_OPTIONS("active_points");
	if (is_super_admin(get_current_user_id()) && $active_points == 1) {
		$add_remove_point = "";
		$the_points = "";
		$the_reason = "";
		if (isset($_POST['add_remove_point'])) {
			$add_remove_point = esc_attr($_POST['add_remove_point']);
		}
		if (isset($_POST['the_points'])) {
			$the_points = (int)esc_attr($_POST['the_points']);
		}
		if (isset($_POST['the_reason'])) {
			$the_reason = esc_attr($_POST['the_reason']);
		}
		if ($the_points > 0) {
			$current_user = get_user_by("id",$user_id);
			$_points = get_user_meta($user_id,$current_user->user_login."_points",true);
			$_points++;
			
			$points_user = get_user_meta($user_id,"points",true);
			if ($add_remove_point == "remove") {
				$add_remove_point_last = "-";
				$the_reason_last = "admin_remove_points";
				update_user_meta($user_id,"points",$points_user-$the_points);
			}else {
				$add_remove_point_last = "+";
				$the_reason_last = "admin_add_points";
				update_user_meta($user_id,"points",$points_user+$the_points);
			}
			$the_reason = (isset($the_reason) && $the_reason != ""?$the_reason:$the_reason_last);
			update_user_meta($user_id,$current_user->user_login."_points",$_points);
			add_user_meta($user_id,$current_user->user_login."_points_".$_points,array(date_i18n('Y/m/d',current_time('timestamp')),date_i18n('g:i a',current_time('timestamp')),$the_points,$add_remove_point_last,$the_reason));
		}
	}
}
/* count_user_posts_by_type */
function count_user_posts_by_type( $userid, $post_type = 'post' ) {
	global $wpdb;
	$where = get_posts_by_author_sql( $post_type, true, $userid );
	$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );
  	return apply_filters( 'get_usernumposts', $count, $userid );
}
/* count_posts_by_type */
function count_posts_by_type( $post_type = 'post', $post_status = "publish" ) {
	global $wpdb;
	$where = "WHERE $wpdb->posts.post_type = 'question' AND $wpdb->posts.post_status = '$post_status'";
	$count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts $where" );
  	return $count;
}
/* makeClickableLinks */
function makeClickableLinks($text) {
	return make_clickable($text);
	//return preg_replace('@(?<!href="|src="|">)(https?:\/\/([-\w\.]+[-\w])+(:\d+)?(/([\w/_\.#-]*(\?\S+)?[^\.\s])?)?)@', '<a href="$1" target="_blank">$1</a>', $text);
}
/* vpanel_get_countries */
function vpanel_get_countries() {
	$countries = array(
		'AF' => esc_html__( 'Afghanistan', 'infocenter' ),
		'AX' => __( '&#197;land Islands', 'infocenter' ),
		'AL' => esc_html__( 'Albania', 'infocenter' ),
		'DZ' => esc_html__( 'Algeria', 'infocenter' ),
		'AD' => esc_html__( 'Andorra', 'infocenter' ),
		'AO' => esc_html__( 'Angola', 'infocenter' ),
		'AI' => esc_html__( 'Anguilla', 'infocenter' ),
		'AQ' => esc_html__( 'Antarctica', 'infocenter' ),
		'AG' => esc_html__( 'Antigua and Barbuda', 'infocenter' ),
		'AR' => esc_html__( 'Argentina', 'infocenter' ),
		'AM' => esc_html__( 'Armenia', 'infocenter' ),
		'AW' => esc_html__( 'Aruba', 'infocenter' ),
		'AU' => esc_html__( 'Australia', 'infocenter' ),
		'AT' => esc_html__( 'Austria', 'infocenter' ),
		'AZ' => esc_html__( 'Azerbaijan', 'infocenter' ),
		'BS' => esc_html__( 'Bahamas', 'infocenter' ),
		'BH' => esc_html__( 'Bahrain', 'infocenter' ),
		'BD' => esc_html__( 'Bangladesh', 'infocenter' ),
		'BB' => esc_html__( 'Barbados', 'infocenter' ),
		'BY' => esc_html__( 'Belarus', 'infocenter' ),
		'BE' => esc_html__( 'Belgium', 'infocenter' ),
		'PW' => esc_html__( 'Belau', 'infocenter' ),
		'BZ' => esc_html__( 'Belize', 'infocenter' ),
		'BJ' => esc_html__( 'Benin', 'infocenter' ),
		'BM' => esc_html__( 'Bermuda', 'infocenter' ),
		'BT' => esc_html__( 'Bhutan', 'infocenter' ),
		'BO' => esc_html__( 'Bolivia', 'infocenter' ),
		'BQ' => esc_html__( 'Bonaire, Saint Eustatius and Saba', 'infocenter' ),
		'BA' => esc_html__( 'Bosnia and Herzegovina', 'infocenter' ),
		'BW' => esc_html__( 'Botswana', 'infocenter' ),
		'BV' => esc_html__( 'Bouvet Island', 'infocenter' ),
		'BR' => esc_html__( 'Brazil', 'infocenter' ),
		'IO' => esc_html__( 'British Indian Ocean Territory', 'infocenter' ),
		'VG' => esc_html__( 'British Virgin Islands', 'infocenter' ),
		'BN' => esc_html__( 'Brunei', 'infocenter' ),
		'BG' => esc_html__( 'Bulgaria', 'infocenter' ),
		'BF' => esc_html__( 'Burkina Faso', 'infocenter' ),
		'BI' => esc_html__( 'Burundi', 'infocenter' ),
		'KH' => esc_html__( 'Cambodia', 'infocenter' ),
		'CM' => esc_html__( 'Cameroon', 'infocenter' ),
		'CA' => esc_html__( 'Canada', 'infocenter' ),
		'CV' => esc_html__( 'Cape Verde', 'infocenter' ),
		'KY' => esc_html__( 'Cayman Islands', 'infocenter' ),
		'CF' => esc_html__( 'Central African Republic', 'infocenter' ),
		'TD' => esc_html__( 'Chad', 'infocenter' ),
		'CL' => esc_html__( 'Chile', 'infocenter' ),
		'CN' => esc_html__( 'China', 'infocenter' ),
		'CX' => esc_html__( 'Christmas Island', 'infocenter' ),
		'CC' => esc_html__( 'Cocos (Keeling) Islands', 'infocenter' ),
		'CO' => esc_html__( 'Colombia', 'infocenter' ),
		'KM' => esc_html__( 'Comoros', 'infocenter' ),
		'CG' => esc_html__( 'Congo (Brazzaville)', 'infocenter' ),
		'CD' => esc_html__( 'Congo (Kinshasa)', 'infocenter' ),
		'CK' => esc_html__( 'Cook Islands', 'infocenter' ),
		'CR' => esc_html__( 'Costa Rica', 'infocenter' ),
		'HR' => esc_html__( 'Croatia', 'infocenter' ),
		'CU' => esc_html__( 'Cuba', 'infocenter' ),
		'CW' => esc_html__( 'Cura&Ccedil;ao', 'infocenter' ),
		'CY' => esc_html__( 'Cyprus', 'infocenter' ),
		'CZ' => esc_html__( 'Czech Republic', 'infocenter' ),
		'DK' => esc_html__( 'Denmark', 'infocenter' ),
		'DJ' => esc_html__( 'Djibouti', 'infocenter' ),
		'DM' => esc_html__( 'Dominica', 'infocenter' ),
		'DO' => esc_html__( 'Dominican Republic', 'infocenter' ),
		'EC' => esc_html__( 'Ecuador', 'infocenter' ),
		'EG' => esc_html__( 'Egypt', 'infocenter' ),
		'SV' => esc_html__( 'El Salvador', 'infocenter' ),
		'GQ' => esc_html__( 'Equatorial Guinea', 'infocenter' ),
		'ER' => esc_html__( 'Eritrea', 'infocenter' ),
		'EE' => esc_html__( 'Estonia', 'infocenter' ),
		'ET' => esc_html__( 'Ethiopia', 'infocenter' ),
		'FK' => esc_html__( 'Falkland Islands', 'infocenter' ),
		'FO' => esc_html__( 'Faroe Islands', 'infocenter' ),
		'FJ' => esc_html__( 'Fiji', 'infocenter' ),
		'FI' => esc_html__( 'Finland', 'infocenter' ),
		'FR' => esc_html__( 'France', 'infocenter' ),
		'GF' => esc_html__( 'French Guiana', 'infocenter' ),
		'PF' => esc_html__( 'French Polynesia', 'infocenter' ),
		'TF' => esc_html__( 'French Southern Territories', 'infocenter' ),
		'GA' => esc_html__( 'Gabon', 'infocenter' ),
		'GM' => esc_html__( 'Gambia', 'infocenter' ),
		'GE' => esc_html__( 'Georgia', 'infocenter' ),
		'DE' => esc_html__( 'Germany', 'infocenter' ),
		'GH' => esc_html__( 'Ghana', 'infocenter' ),
		'GI' => esc_html__( 'Gibraltar', 'infocenter' ),
		'GR' => esc_html__( 'Greece', 'infocenter' ),
		'GL' => esc_html__( 'Greenland', 'infocenter' ),
		'GD' => esc_html__( 'Grenada', 'infocenter' ),
		'GP' => esc_html__( 'Guadeloupe', 'infocenter' ),
		'GT' => esc_html__( 'Guatemala', 'infocenter' ),
		'GG' => esc_html__( 'Guernsey', 'infocenter' ),
		'GN' => esc_html__( 'Guinea', 'infocenter' ),
		'GW' => esc_html__( 'Guinea-Bissau', 'infocenter' ),
		'GY' => esc_html__( 'Guyana', 'infocenter' ),
		'HT' => esc_html__( 'Haiti', 'infocenter' ),
		'HM' => esc_html__( 'Heard Island and McDonald Islands', 'infocenter' ),
		'HN' => esc_html__( 'Honduras', 'infocenter' ),
		'HK' => esc_html__( 'Hong Kong', 'infocenter' ),
		'HU' => esc_html__( 'Hungary', 'infocenter' ),
		'IS' => esc_html__( 'Iceland', 'infocenter' ),
		'IN' => esc_html__( 'India', 'infocenter' ),
		'ID' => esc_html__( 'Indonesia', 'infocenter' ),
		'IR' => esc_html__( 'Iran', 'infocenter' ),
		'IQ' => esc_html__( 'Iraq', 'infocenter' ),
		'IE' => esc_html__( 'Republic of Ireland', 'infocenter' ),
		'IM' => esc_html__( 'Isle of Man', 'infocenter' ),
		'IL' => esc_html__( 'Israel', 'infocenter' ),
		'IT' => esc_html__( 'Italy', 'infocenter' ),
		'CI' => esc_html__( 'Ivory Coast', 'infocenter' ),
		'JM' => esc_html__( 'Jamaica', 'infocenter' ),
		'JP' => esc_html__( 'Japan', 'infocenter' ),
		'JE' => esc_html__( 'Jersey', 'infocenter' ),
		'JO' => esc_html__( 'Jordan', 'infocenter' ),
		'KZ' => esc_html__( 'Kazakhstan', 'infocenter' ),
		'KE' => esc_html__( 'Kenya', 'infocenter' ),
		'KI' => esc_html__( 'Kiribati', 'infocenter' ),
		'KW' => esc_html__( 'Kuwait', 'infocenter' ),
		'KG' => esc_html__( 'Kyrgyzstan', 'infocenter' ),
		'LA' => esc_html__( 'Laos', 'infocenter' ),
		'LV' => esc_html__( 'Latvia', 'infocenter' ),
		'LB' => esc_html__( 'Lebanon', 'infocenter' ),
		'LS' => esc_html__( 'Lesotho', 'infocenter' ),
		'LR' => esc_html__( 'Liberia', 'infocenter' ),
		'LY' => esc_html__( 'Libya', 'infocenter' ),
		'LI' => esc_html__( 'Liechtenstein', 'infocenter' ),
		'LT' => esc_html__( 'Lithuania', 'infocenter' ),
		'LU' => esc_html__( 'Luxembourg', 'infocenter' ),
		'MO' => esc_html__( 'Macao S.A.R., China', 'infocenter' ),
		'MK' => esc_html__( 'Macedonia', 'infocenter' ),
		'MG' => esc_html__( 'Madagascar', 'infocenter' ),
		'MW' => esc_html__( 'Malawi', 'infocenter' ),
		'MY' => esc_html__( 'Malaysia', 'infocenter' ),
		'MV' => esc_html__( 'Maldives', 'infocenter' ),
		'ML' => esc_html__( 'Mali', 'infocenter' ),
		'MT' => esc_html__( 'Malta', 'infocenter' ),
		'MH' => esc_html__( 'Marshall Islands', 'infocenter' ),
		'MQ' => esc_html__( 'Martinique', 'infocenter' ),
		'MR' => esc_html__( 'Mauritania', 'infocenter' ),
		'MU' => esc_html__( 'Mauritius', 'infocenter' ),
		'YT' => esc_html__( 'Mayotte', 'infocenter' ),
		'MX' => esc_html__( 'Mexico', 'infocenter' ),
		'FM' => esc_html__( 'Micronesia', 'infocenter' ),
		'MD' => esc_html__( 'Moldova', 'infocenter' ),
		'MC' => esc_html__( 'Monaco', 'infocenter' ),
		'MN' => esc_html__( 'Mongolia', 'infocenter' ),
		'ME' => esc_html__( 'Montenegro', 'infocenter' ),
		'MS' => esc_html__( 'Montserrat', 'infocenter' ),
		'MA' => esc_html__( 'Morocco', 'infocenter' ),
		'MZ' => esc_html__( 'Mozambique', 'infocenter' ),
		'MM' => esc_html__( 'Myanmar', 'infocenter' ),
		'NA' => esc_html__( 'Namibia', 'infocenter' ),
		'NR' => esc_html__( 'Nauru', 'infocenter' ),
		'NP' => esc_html__( 'Nepal', 'infocenter' ),
		'NL' => esc_html__( 'Netherlands', 'infocenter' ),
		'AN' => esc_html__( 'Netherlands Antilles', 'infocenter' ),
		'NC' => esc_html__( 'New Caledonia', 'infocenter' ),
		'NZ' => esc_html__( 'New Zealand', 'infocenter' ),
		'NI' => esc_html__( 'Nicaragua', 'infocenter' ),
		'NE' => esc_html__( 'Niger', 'infocenter' ),
		'NG' => esc_html__( 'Nigeria', 'infocenter' ),
		'NU' => esc_html__( 'Niue', 'infocenter' ),
		'NF' => esc_html__( 'Norfolk Island', 'infocenter' ),
		'KP' => esc_html__( 'North Korea', 'infocenter' ),
		'NO' => esc_html__( 'Norway', 'infocenter' ),
		'OM' => esc_html__( 'Oman', 'infocenter' ),
		'PK' => esc_html__( 'Pakistan', 'infocenter' ),
		'PS' => esc_html__( 'Palestinian Territory', 'infocenter' ),
		'PA' => esc_html__( 'Panama', 'infocenter' ),
		'PG' => esc_html__( 'Papua New Guinea', 'infocenter' ),
		'PY' => esc_html__( 'Paraguay', 'infocenter' ),
		'PE' => esc_html__( 'Peru', 'infocenter' ),
		'PH' => esc_html__( 'Philippines', 'infocenter' ),
		'PN' => esc_html__( 'Pitcairn', 'infocenter' ),
		'PL' => esc_html__( 'Poland', 'infocenter' ),
		'PT' => esc_html__( 'Portugal', 'infocenter' ),
		'QA' => esc_html__( 'Qatar', 'infocenter' ),
		'RE' => esc_html__( 'Reunion', 'infocenter' ),
		'RO' => esc_html__( 'Romania', 'infocenter' ),
		'RU' => esc_html__( 'Russia', 'infocenter' ),
		'RW' => esc_html__( 'Rwanda', 'infocenter' ),
		'BL' => esc_html__( 'Saint Barth&eacute;lemy', 'infocenter' ),
		'SH' => esc_html__( 'Saint Helena', 'infocenter' ),
		'KN' => esc_html__( 'Saint Kitts and Nevis', 'infocenter' ),
		'LC' => esc_html__( 'Saint Lucia', 'infocenter' ),
		'MF' => esc_html__( 'Saint Martin (French part)', 'infocenter' ),
		'SX' => esc_html__( 'Saint Martin (Dutch part)', 'infocenter' ),
		'PM' => esc_html__( 'Saint Pierre and Miquelon', 'infocenter' ),
		'VC' => esc_html__( 'Saint Vincent and the Grenadines', 'infocenter' ),
		'SM' => esc_html__( 'San Marino', 'infocenter' ),
		'ST' => __( 'S&atilde;o Tom&eacute; and Pr&iacute;ncipe', 'infocenter' ),
		'SA' => esc_html__( 'Saudi Arabia', 'infocenter' ),
		'SN' => esc_html__( 'Senegal', 'infocenter' ),
		'RS' => esc_html__( 'Serbia', 'infocenter' ),
		'SC' => esc_html__( 'Seychelles', 'infocenter' ),
		'SL' => esc_html__( 'Sierra Leone', 'infocenter' ),
		'SG' => esc_html__( 'Singapore', 'infocenter' ),
		'SK' => esc_html__( 'Slovakia', 'infocenter' ),
		'SI' => esc_html__( 'Slovenia', 'infocenter' ),
		'SB' => esc_html__( 'Solomon Islands', 'infocenter' ),
		'SO' => esc_html__( 'Somalia', 'infocenter' ),
		'ZA' => esc_html__( 'South Africa', 'infocenter' ),
		'GS' => esc_html__( 'South Georgia/Sandwich Islands', 'infocenter' ),
		'KR' => esc_html__( 'South Korea', 'infocenter' ),
		'SS' => esc_html__( 'South Sudan', 'infocenter' ),
		'ES' => esc_html__( 'Spain', 'infocenter' ),
		'LK' => esc_html__( 'Sri Lanka', 'infocenter' ),
		'SD' => esc_html__( 'Sudan', 'infocenter' ),
		'SR' => esc_html__( 'Suriname', 'infocenter' ),
		'SJ' => esc_html__( 'Svalbard and Jan Mayen', 'infocenter' ),
		'SZ' => esc_html__( 'Swaziland', 'infocenter' ),
		'SE' => esc_html__( 'Sweden', 'infocenter' ),
		'CH' => esc_html__( 'Switzerland', 'infocenter' ),
		'SY' => esc_html__( 'Syria', 'infocenter' ),
		'TW' => esc_html__( 'Taiwan', 'infocenter' ),
		'TJ' => esc_html__( 'Tajikistan', 'infocenter' ),
		'TZ' => esc_html__( 'Tanzania', 'infocenter' ),
		'TH' => esc_html__( 'Thailand', 'infocenter' ),
		'TL' => esc_html__( 'Timor-Leste', 'infocenter' ),
		'TG' => esc_html__( 'Togo', 'infocenter' ),
		'TK' => esc_html__( 'Tokelau', 'infocenter' ),
		'TO' => esc_html__( 'Tonga', 'infocenter' ),
		'TT' => esc_html__( 'Trinidad and Tobago', 'infocenter' ),
		'TN' => esc_html__( 'Tunisia', 'infocenter' ),
		'TR' => esc_html__( 'Turkey', 'infocenter' ),
		'TM' => esc_html__( 'Turkmenistan', 'infocenter' ),
		'TC' => esc_html__( 'Turks and Caicos Islands', 'infocenter' ),
		'TV' => esc_html__( 'Tuvalu', 'infocenter' ),
		'UG' => esc_html__( 'Uganda', 'infocenter' ),
		'UA' => esc_html__( 'Ukraine', 'infocenter' ),
		'AE' => esc_html__( 'United Arab Emirates', 'infocenter' ),
		'GB' => esc_html__( 'United Kingdom (UK)', 'infocenter' ),
		'US' => esc_html__( 'United States (US)', 'infocenter' ),
		'UY' => esc_html__( 'Uruguay', 'infocenter' ),
		'UZ' => esc_html__( 'Uzbekistan', 'infocenter' ),
		'VU' => esc_html__( 'Vanuatu', 'infocenter' ),
		'VA' => esc_html__( 'Vatican', 'infocenter' ),
		'VE' => esc_html__( 'Venezuela', 'infocenter' ),
		'VN' => esc_html__( 'Vietnam', 'infocenter' ),
		'WF' => esc_html__( 'Wallis and Futuna', 'infocenter' ),
		'EH' => esc_html__( 'Western Sahara', 'infocenter' ),
		'WS' => esc_html__( 'Western Samoa', 'infocenter' ),
		'YE' => esc_html__( 'Yemen', 'infocenter' ),
		'ZM' => esc_html__( 'Zambia', 'infocenter' ),
		'ZW' => esc_html__( 'Zimbabwe', 'infocenter' )
	);
	asort( $countries );
	return $countries;
}
/* vpanel_update_options */
function vpanel_update_options(){
	global $themename;
	$post_re = $_POST;
	$all_save = $post_re[INFOCENTER_OPTIONS];
	if(isset($all_save['import_setting']) && $all_save['import_setting'] != "") {
		$data = $all_save['import_setting'];
		$array_options = array(INFOCENTER_OPTIONS,"badges","sidebars","roles");
		foreach($array_options as $option){
			if(isset($data[$option])){
				update_option($option,$data[$option]);
			}else{
				delete_option($option);
			}
		}
		echo 2;
		update_option("FlushRewriteRules",true);
		die();
	}else {
		foreach($post_re[INFOCENTER_OPTIONS] as $key => $value) {
			if (isset($post_re[INFOCENTER_OPTIONS][$key]) && $post_re[INFOCENTER_OPTIONS][$key] == "on") {
				if (isset($post_re[INFOCENTER_OPTIONS]["theme_pages"]) && $post_re[INFOCENTER_OPTIONS]["theme_pages"] == "on") {
					if ($key == "theme_pages") {
						$page_data = array(
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'post_author'    => get_current_user_id(),
							'post_name'      => esc_html_x('add_question','Page slug','infocenter'),
							'post_title'     => esc_html_x('Add question','Page title','infocenter'),
							'post_content'   => '',
							'post_parent'    => 0,
							'comment_status' => 'closed'
						);
						$add_question = wp_insert_post($page_data);
						update_post_meta($add_question,'_wp_page_template','template-ask_question.php');
						$post_re[INFOCENTER_OPTIONS]["add_question"] = $add_question;
						
						$page_data = array(
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'post_author'    => get_current_user_id(),
							'post_name'      => esc_html_x('edit_question','Page slug','infocenter'),
							'post_title'     => esc_html_x('Edit question','Page title','infocenter'),
							'post_content'   => '',
							'post_parent'    => 0,
							'comment_status' => 'closed'
						);
						$edit_question = wp_insert_post($page_data);
						update_post_meta($edit_question,'_wp_page_template','template-edit_question.php');
						$post_re[INFOCENTER_OPTIONS]["edit_question"] = $edit_question;
						
						$page_data = array(
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'post_author'    => get_current_user_id(),
							'post_name'      => esc_html_x('login','Page slug','infocenter'),
							'post_title'     => esc_html_x('Login','Page title','infocenter'),
							'post_content'   => '',
							'post_parent'    => 0,
							'comment_status' => 'closed'
						);
						$login_register_page = wp_insert_post($page_data);
						update_post_meta($login_register_page,'_wp_page_template','template-login.php');
						$post_re[INFOCENTER_OPTIONS]["login_register_page"] = $login_register_page;
						
						$page_data = array(
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'post_author'    => get_current_user_id(),
							'post_name'      => esc_html_x('edit_profile','Page slug','infocenter'),
							'post_title'     => esc_html_x('Edit profile','Page title','infocenter'),
							'post_content'   => '',
							'post_parent'    => 0,
							'comment_status' => 'closed'
						);
						$user_edit_profile_page = wp_insert_post($page_data);
						update_post_meta($user_edit_profile_page,'_wp_page_template','template-edit_profile.php');
						$post_re[INFOCENTER_OPTIONS]["user_edit_profile_page"] = $user_edit_profile_page;
						
						$page_data = array(
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'post_author'    => get_current_user_id(),
							'post_name'      => esc_html_x('user_post','Page slug','infocenter'),
							'post_title'     => esc_html_x('User post','Page title','infocenter'),
							'post_content'   => '',
							'post_parent'    => 0,
							'comment_status' => 'closed'
						);
						$post_user_page = wp_insert_post($page_data);
						update_post_meta($post_user_page,'_wp_page_template','template-user_posts.php');
						$post_re[INFOCENTER_OPTIONS]["post_user_page"] = $post_user_page;
						
						$page_data = array(
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'post_author'    => get_current_user_id(),
							'post_name'      => esc_html_x('user_question','Page slug','infocenter'),
							'post_title'     => esc_html_x('User question','Page title','infocenter'),
							'post_content'   => '',
							'post_parent'    => 0,
							'comment_status' => 'closed'
						);
						$question_user_page = wp_insert_post($page_data);
						update_post_meta($question_user_page,'_wp_page_template','template-user_question.php');
						$post_re[INFOCENTER_OPTIONS]["question_user_page"] = $question_user_page;
						
						$page_data = array(
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'post_author'    => get_current_user_id(),
							'post_name'      => esc_html_x('user_answer','Page slug','infocenter'),
							'post_title'     => esc_html_x('User answer','Page title','infocenter'),
							'post_content'   => '',
							'post_parent'    => 0,
							'comment_status' => 'closed'
						);
						$answer_user_page = wp_insert_post($page_data);
						update_post_meta($answer_user_page,'_wp_page_template','template-user_answer.php');
						$post_re[INFOCENTER_OPTIONS]["answer_user_page"] = $answer_user_page;
						
						$page_data = array(
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'post_author'    => get_current_user_id(),
							'post_name'      => esc_html_x('favorite_question','Page slug','infocenter'),
							'post_title'     => esc_html_x('Favorite question','Page title','infocenter'),
							'post_content'   => '',
							'post_parent'    => 0,
							'comment_status' => 'closed'
						);
						$favorite_user_page = wp_insert_post($page_data);
						update_post_meta($favorite_user_page,'_wp_page_template','template-user_favorite_questions.php');
						$post_re[INFOCENTER_OPTIONS]["favorite_user_page"] = $favorite_user_page;
						
						$page_data = array(
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'post_author'    => get_current_user_id(),
							'post_name'      => esc_html_x('user_point','Page slug','infocenter'),
							'post_title'     => esc_html_x('User point','Page title','infocenter'),
							'post_content'   => '',
							'post_parent'    => 0,
							'comment_status' => 'closed'
						);
						$point_user_page = wp_insert_post($page_data);
						update_post_meta($point_user_page,'_wp_page_template','template-user_points.php');
						$post_re[INFOCENTER_OPTIONS]["point_user_page"] = $point_user_page;
						
						$page_data = array(
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'post_author'    => get_current_user_id(),
							'post_name'      => esc_html_x('i_follow_user','Page slug','infocenter'),
							'post_title'     => esc_html_x('I follow user','Page title','infocenter'),
							'post_content'   => '',
							'post_parent'    => 0,
							'comment_status' => 'closed'
						);
						$i_follow_user_page = wp_insert_post($page_data);
						update_post_meta($i_follow_user_page,'_wp_page_template','template-i_follow.php');
						$post_re[INFOCENTER_OPTIONS]["i_follow_user_page"] = $i_follow_user_page;
						
						$page_data = array(
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'post_author'    => get_current_user_id(),
							'post_name'      => esc_html_x('followers_user','Page slug','infocenter'),
							'post_title'     => esc_html_x('Followers user','Page title','infocenter'),
							'post_content'   => '',
							'post_parent'    => 0,
							'comment_status' => 'closed'
						);
						$followers_user_page = wp_insert_post($page_data);
						update_post_meta($followers_user_page,'_wp_page_template','template-followers.php');
						$post_re[INFOCENTER_OPTIONS]["followers_user_page"] = $followers_user_page;
						
						$page_data = array(
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'post_author'    => get_current_user_id(),
							'post_name'      => esc_html_x('follow_question','Page slug','infocenter'),
							'post_title'     => esc_html_x('Follow question','Page title','infocenter'),
							'post_content'   => '',
							'post_parent'    => 0,
							'comment_status' => 'closed'
						);
						$follow_question_page = wp_insert_post($page_data);
						update_post_meta($follow_question_page,'_wp_page_template','template-question_follow.php');
						$post_re[INFOCENTER_OPTIONS]["follow_question_page"] = $follow_question_page;
						
						$page_data = array(
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'post_author'    => get_current_user_id(),
							'post_name'      => esc_html_x('follow_answer','Page slug','infocenter'),
							'post_title'     => esc_html_x('Follow answer','Page title','infocenter'),
							'post_content'   => '',
							'post_parent'    => 0,
							'comment_status' => 'closed'
						);
						$follow_answer_page = wp_insert_post($page_data);
						update_post_meta($follow_answer_page,'_wp_page_template','template-answer_follow.php');
						$post_re[INFOCENTER_OPTIONS]["follow_answer_page"] = $follow_answer_page;
						
						$page_data = array(
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'post_author'    => get_current_user_id(),
							'post_name'      => esc_html_x('follow_post','Page slug','infocenter'),
							'post_title'     => esc_html_x('Follow post','Page title','infocenter'),
							'post_content'   => '',
							'post_parent'    => 0,
							'comment_status' => 'closed'
						);
						$follow_post_page = wp_insert_post($page_data);
						update_post_meta($follow_post_page,'_wp_page_template','template-post_follow.php');
						$post_re[INFOCENTER_OPTIONS]["follow_post_page"] = $follow_post_page;
						
						$page_data = array(
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'post_author'    => get_current_user_id(),
							'post_name'      => esc_html_x('follow comment','Page slug','infocenter'),
							'post_title'     => esc_html_x('Follow comment','Page title','infocenter'),
							'post_content'   => '',
							'post_parent'    => 0,
							'comment_status' => 'closed'
						);
						$follow_comment_page = wp_insert_post($page_data);
						update_post_meta($follow_comment_page,'_wp_page_template','template-comment_follow.php');
						$post_re[INFOCENTER_OPTIONS]["follow_comment_page"] = $follow_comment_page;
						
						$page_data = array(
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'post_author'    => get_current_user_id(),
							'post_name'      => esc_html_x('edit_post','Page slug','infocenter'),
							'post_title'     => esc_html_x('Edit post','Page title','infocenter'),
							'post_content'   => '',
							'post_parent'    => 0,
							'comment_status' => 'closed'
						);
						$edit_post = wp_insert_post($page_data);
						update_post_meta($edit_post,'_wp_page_template','template-edit_post.php');
						$post_re[INFOCENTER_OPTIONS]["edit_post"] = $edit_post;
						
						$page_data = array(
							'post_status'    => 'publish',
							'post_type'      => 'page',
							'post_author'    => get_current_user_id(),
							'post_name'      => esc_html_x('edit_comment','Page slug','infocenter'),
							'post_title'     => esc_html_x('Edit comment','Page title','infocenter'),
							'post_content'   => '',
							'post_parent'    => 0,
							'comment_status' => 'closed'
						);
						$edit_comment = wp_insert_post($page_data);
						update_post_meta($edit_comment,'_wp_page_template','template-edit_comment.php');
						$post_re[INFOCENTER_OPTIONS]["edit_comment"] = $edit_comment;
						echo 3;
					}
				}else {
					$post_re[INFOCENTER_OPTIONS][$key] = 1;
				}
			}else {
				$post_re[INFOCENTER_OPTIONS][$key] = $value;
			}
		}
		unset($post_re[INFOCENTER_OPTIONS]["theme_pages"]);
		update_option(INFOCENTER_OPTIONS,$post_re[INFOCENTER_OPTIONS]);
		/* Badges */
		if (isset($post_re["badges"])) {
			update_option("badges",$post_re["badges"]);
		}else {
			delete_option("badges");
		}
		/* Sidebars */
		if (isset($post_re["sidebars"])) {
			update_option("sidebars",$post_re["sidebars"]);
		}else {
			delete_option("sidebars");
		}
		/* roles */
		global $wp_roles;
		if (isset($post_re["roles"])) {$k = 0;
			foreach ($post_re["roles"] as $value_roles) {$k++;
				unset($wp_roles->roles[$value_roles["id"]]);
				add_role($value_roles["id"],$value_roles["group"],array('read' => false));
				$is_group = get_role($value_roles["id"]);
				if (isset($value_roles["ask_question"]) && $value_roles["ask_question"] == "on") {
					$is_group->add_cap('ask_question');
				}else {
					$is_group->remove_cap('ask_question');
				}
				if (isset($value_roles["show_question"]) && $value_roles["show_question"] == "on") {
					$is_group->add_cap('show_question');
				}else {
					$is_group->remove_cap('show_question');
				}
				if (isset($value_roles["add_answer"]) && $value_roles["add_answer"] == "on") {
					$is_group->add_cap('add_answer');
				}else {
					$is_group->remove_cap('add_answer');
				}
				if (isset($value_roles["show_answer"]) && $value_roles["show_answer"] == "on") {
					$is_group->add_cap('show_answer');
				}else {
					$is_group->remove_cap('show_answer');
				}
				if (isset($value_roles["add_post"]) && $value_roles["add_post"] == "on") {
					$is_group->add_cap('add_post');
				}else {
					$is_group->remove_cap('add_post');
				}
			}
			update_option("roles",$post_re["roles"]);
		}else {
			delete_option("roles");
		}
		/* roles_default */
		if (isset($post_re["roles_default"])) {
			update_option("roles_default",$post_re["roles_default"]);
			$old_roles = $wp_roles->roles;
			foreach ($old_roles as $key_r => $value_r) {
				$is_group = get_role($key_r);
				if (isset($post_re["roles_default"][$key_r]) && is_array($post_re["roles_default"][$key_r])) {
					$value_d = $post_re["roles_default"][$key_r];
					if (isset($value_d["ask_question"]) && $value_d["ask_question"] == "on") {
						$is_group->add_cap('ask_question');
					}else {
						$is_group->remove_cap('ask_question');
					}
					if (isset($value_d["show_question"]) && $value_d["show_question"] == "on") {
						$is_group->add_cap('show_question');
					}else {
						$is_group->remove_cap('show_question');
					}
					if (isset($value_d["add_answer"]) && $value_d["add_answer"] == "on") {
						$is_group->add_cap('add_answer');
					}else {
						$is_group->remove_cap('add_answer');
					}
					if (isset($value_d["show_answer"]) && $value_d["show_answer"] == "on") {
						$is_group->add_cap('show_answer');
					}else {
						$is_group->remove_cap('show_answer');
					}
					if (isset($value_d["add_post"]) && $value_d["add_post"] == "on") {
						$is_group->add_cap('add_post');
					}else {
						$is_group->remove_cap('add_post');
					}
				}
			}
		}else {
			delete_option("roles_default");
		}
	}
	update_option("FlushRewriteRules",true);
	die(1);
}
add_action( 'wp_ajax_vpanel_update_options', 'vpanel_update_options' );
add_action('wp_ajax_nopriv_vpanel_update_options','vpanel_update_options');
/* reset_options */
function reset_options() {
	global $themename;
	$options = & Options_Framework::_optionsframework_options();
	foreach ($options as $option) {
		if (isset($option['id'])) {
			$option_std = $option['std'];
			$option_res[$option['id']] = $option['std'];
		}
	}
	update_option(INFOCENTER_OPTIONS,$option_res);
	update_option("FlushRewriteRules",true);
	die(1);
}
add_action( 'wp_ajax_reset_options', 'reset_options' );
add_action('wp_ajax_nopriv_reset_options','reset_options');
/* delete_group */
function delete_group() {
	$group_id = esc_attr($_POST["group_id"]);
	remove_role($group_id);
	die(1);
}
add_action( 'wp_ajax_delete_group', 'delete_group' );
add_action('wp_ajax_nopriv_delete_group','delete_group');
/* vpanel_get_user_url */
function vpanel_get_user_url($author_id, $author_nicename = '') {
	global $wp_rewrite;
	$auth_ID = (int) $author_id;
	$link = $wp_rewrite->get_author_permastruct();
	if ( empty($link) ) {
		$file = home_url( '/' );
		$link = $file . '?author=' . $auth_ID;
	}else {
		if ( '' == $author_nicename ) {
			$user = get_userdata($author_id);
			if ( !empty($user->user_nicename) )
				$author_nicename = $user->user_nicename;
		}
		$link = str_replace('%author%', $author_nicename, $link);
		$link = home_url( user_trailingslashit( $link ) );
	}
	$link = apply_filters( 'author_link', $link, $author_id, $author_nicename );
	return $link;
}
/* vpanel_get_badge */
function vpanel_get_badge($author_id,$return = "") {
	$author_id = (int)$author_id;
	if ($author_id > 0) {
		$last_key = 0;
		$points = get_user_meta($author_id,"points",true);
		$badges = get_option("badges");
		if (isset($badges) && is_array($badges)) {
			foreach ($badges as $badges_k => $badges_v) {
				$badges_points[] = $badges_v["badge_points"];
			}
			if (isset($badges_points) && is_array($badges_points)) {
				foreach ($badges_points as $key => $badge_point) {
					if ($points >= $badge_point) {
						$last_key = $key;
					}
				}
			}
			$key = $last_key;
			if ($return == "color") {
				$badge_color = $badges[$key+1]["badge_color"];
				return $badge_color;
			}else if ($return == "name") {
				$badge_name = $badges[$key+1]["badge_name"];
				return $badge_name;
			}else {
				return '<span class="badge-span" style="background-color: '.$badges[$key+1]["badge_color"].'">'.$badges[$key+1]["badge_name"].'</span>';
			}
		}
	}
}
/* vpanel_sidebars */
if (!is_admin()) {
	function vpanel_sidebars($return = 'sidebar_dir') {
		global $post;
		$sidebar_layout          = $sidebar_class = "";
		$sidebar_width = INFOCENTER_OPTIONS("sidebar_width");
		$sidebar_width = (isset($sidebar_width) && $sidebar_width != ""?$sidebar_width:"col-md-4");
		$sidebar_layout = "";
		if (isset($sidebar_width) && $sidebar_width == "col-md-4") {
			$container_span = "col-md-8";
		}else {
			$container_span = "col-md-9";
		}
		$full_span       = "col-md-12";
		$page_right      = "page-right-sidebar";
		$page_left       = "page-left-sidebar";
		$page_full_width = "page-full-width";
		
		if (is_author()) {
			$author_sidebar_layout = INFOCENTER_OPTIONS('author_sidebar_layout');
		}else if (is_category() || is_tax("product_cat") || is_tax("question-category") || is_tax("question_tags")) {
			$cat_sidebar_layout = (isset($categories["cat_sidebar_layout"])?$categories["cat_sidebar_layout"]:"default");
			if ($cat_sidebar_layout == "default") {
				if (is_tax("product_cat")) {
					$cat_sidebar_layout = INFOCENTER_OPTIONS("products_sidebar_layout");
				}else if (is_tax("question-category") || is_tax("question_tags")) {
					$cat_sidebar_layout = INFOCENTER_OPTIONS("questions_sidebar_layout");
				}
			}
		}else if (is_single() || is_page()) {
			$sidebar_post = rwmb_meta('infocenter_sidebar','radio',$post->ID);
			if ($sidebar_post == "" || $sidebar_post == "default") {
				$sidebar_post = INFOCENTER_OPTIONS("sidebar_layout");
			}
		}else {
			$sidebar_layout = INFOCENTER_OPTIONS('sidebar_layout');
		}
		
		if (is_author()) {
			if ($author_sidebar_layout == "" || $author_sidebar_layout == "default") {
				$author_sidebar_layout = INFOCENTER_OPTIONS("sidebar_layout");
			}
			if ($author_sidebar_layout == 'left') {
				$sidebar_dir = $page_left;
				$homepage_content_span = $container_span;
			}elseif ($author_sidebar_layout == 'full') {
				$sidebar_dir = $page_full_width;
				$homepage_content_span = $full_span;
			}else {
				$sidebar_dir = $page_right;
				$homepage_content_span = $container_span;
			}
		}else if (is_category() || is_tax("product_cat") || is_tax("question-category") || is_tax("question_tags")) {
			if ($cat_sidebar_layout == "" || $cat_sidebar_layout == "default") {
				$cat_sidebar_layout = INFOCENTER_OPTIONS("sidebar_layout");
			}
			if ($cat_sidebar_layout == 'left') {
				$sidebar_dir = $page_left;
				$homepage_content_span = $container_span;
			}elseif ($cat_sidebar_layout == 'full') {
				$sidebar_dir = $page_full_width;
				$homepage_content_span = $full_span;
			}else {
				$sidebar_dir = $page_right;
				$homepage_content_span = $container_span;
			}
		}else if (is_single() || is_page()) {
			$sidebar_post = rwmb_meta('infocenter_sidebar','radio',$post->ID);
			$sidebar_dir = '';
			if (isset($sidebar_post) && $sidebar_post != "default" && $sidebar_post != "") {
				if ($sidebar_post == 'left') {
					$sidebar_dir = 'page-left-sidebar';
					$homepage_content_span = $container_span;
				}elseif ($sidebar_post == 'full') {
					$sidebar_dir = 'page-full-width';
					$homepage_content_span = $full_span;
				}else {
					$sidebar_dir = 'page-right-sidebar';
					$homepage_content_span = $container_span;
				}
			}else {
				$sidebar_layout_q = INFOCENTER_OPTIONS('questions_sidebar_layout');
				$sidebar_layout_p = INFOCENTER_OPTIONS('products_sidebar_layout');
				if (is_singular("question") && $sidebar_layout_q != "default") {
					if ($sidebar_layout_q == 'left') {
						$sidebar_dir = 'page-left-sidebar';
						$homepage_content_span = $container_span;
					}elseif ($sidebar_layout_q == 'full') {
						$sidebar_dir = 'page-full-width';
						$homepage_content_span = $full_span;
					}else {
						$sidebar_dir = 'page-right-sidebar';
						$homepage_content_span = $container_span;
					}
				}else if (is_singular("product") && $sidebar_layout_p != "default") {
					if ($sidebar_layout_p == 'left') {
						$sidebar_dir = 'page-left-sidebar';
						$homepage_content_span = $container_span;
					}elseif ($sidebar_layout_p == 'full') {
						$sidebar_dir = 'page-full-width';
						$homepage_content_span = $full_span;
					}else {
						$sidebar_dir = 'page-right-sidebar';
						$homepage_content_span = $container_span;
					}
				}else {
					$sidebar_layout = INFOCENTER_OPTIONS('sidebar_layout');
					if ($sidebar_layout == 'left') {
						$sidebar_dir = 'page-left-sidebar';
						$homepage_content_span = $container_span;
					}elseif ($sidebar_layout == 'full') {
						$sidebar_dir = 'page-full-width';
						$homepage_content_span = $full_span;
					}else {
						$sidebar_dir = 'page-right-sidebar';
						$homepage_content_span = $container_span;
					}
				}
			}
		}else {
			if ((is_single() || is_page()) && $sidebar_post != "default" && $sidebar_post != "") {
				if ($sidebar_post == 'left') {
					$sidebar_dir = 'page-left-sidebar';
					$homepage_content_span = $container_span;
				}elseif ($sidebar_post == 'full') {
					$sidebar_dir = 'page-full-width';
					$homepage_content_span = $full_span;
				}else {
					$sidebar_dir = 'page-right-sidebar';
					$homepage_content_span = $container_span;
				}
			}else {
				if ((is_singular("product") && $sidebar_layout_p != "default" && $sidebar_layout_p != "")) {
					$sidebar_layout_p = INFOCENTER_OPTIONS('products_sidebar_layout');
					if ($sidebar_layout_p == 'left') {
						$sidebar_dir = 'page-left-sidebar';
						$homepage_content_span = $container_span;
					}elseif ($sidebar_layout_p == 'full') {
						$sidebar_dir = 'page-full-width';
						$homepage_content_span = $full_span;
					}else {
						$sidebar_dir = 'page-right-sidebar';
						$homepage_content_span = $container_span;
					}
				}else if ((is_singular("question") && $sidebar_layout_q != "default" && $sidebar_layout_q != "")) {
					$sidebar_layout_q = INFOCENTER_OPTIONS('questions_sidebar_layout');
					if ($sidebar_layout_q == 'left') {
						$sidebar_dir = 'page-left-sidebar';
						$homepage_content_span = $container_span;
					}elseif ($sidebar_layout_q == 'full') {
						$sidebar_dir = 'page-full-width';
						$homepage_content_span = $full_span;
					}else {
						$sidebar_dir = 'page-right-sidebar';
						$homepage_content_span = $container_span;
					}
				}else {
					$sidebar_layout = INFOCENTER_OPTIONS('sidebar_layout');
					if ($sidebar_layout == 'left') {
						$sidebar_dir = 'page-left-sidebar';
						$homepage_content_span = $container_span;
					}elseif ($sidebar_layout == 'full') {
						$sidebar_dir = 'page-full-width';
						$homepage_content_span = $full_span;
					}else {
						$sidebar_dir = 'page-right-sidebar';
						$homepage_content_span = $container_span;
					}
				}
			}
		}
		
		if ($return == "sidebar_dir") {
			return $sidebar_dir;
		}else if ($return == "sidebar_class") {
			return $sidebar_class;
		}else if ($return == "sidebar_where") {
			if ($sidebar_dir == $page_full_width) {
				$sidebar_where = 'full';
			}else {
				$sidebar_where = 'sidebar';
			}
			return $sidebar_where;
		}else {
			return $homepage_content_span;
		}
	}
}
// **********************************************************************// 
// ! Get Global Variables
// **********************************************************************//
function infocenter_get_global_post() {
    global $post;
    if ( 
        ! $post instanceof \WP_Post
    ) {
        return false;
    }
    return $post;
}

function infocenter_get_global_wpquery() {
    global $wp_query;
    return $wp_query;
}

function infocenter_get_global_vpanel_emails() {
    global $vpanel_emails;
    return $vpanel_emails;
}

function infocenter_get_global_vpanel_emails_2() {
    global $vpanel_emails_2;
    return $vpanel_emails_2;
}

function infocenter_get_global_vpanel_emails_3() {
    global $vpanel_emails_3;
    return $vpanel_emails_3;
}

function infocenter_get_global_infocenter_sidebar_all() {
    global $infocenter_sidebar_all;
    return $infocenter_sidebar_all;
}

function infocenter_get_global_blog_style() {
    global $blog_style;
    return $blog_style;
}

function infocenter_get_global_question_bump_template() {
    global $question_bump_template;
    return $question_bump_template;
}

function infocenter_get_global_authordata() {
    global $authordata;
    return $authordata;
}

function infocenter_get_global_get_question_category() {
    global $get_question_category;
    return $get_question_category;
}

function infocenter_get_global_question_category() {
    global $question_category;
    return $question_category;
}

function infocenter_get_global_closed_question() {
    global $closed_question;
    return $closed_question;
}

function infocenter_get_global_user_ID() {
    global $user_ID;
    return $user_ID;
}

function infocenter_get_global_posted() {
    global $posted;
    return $posted;
}

/*Adding Meta Box to Categories*/
//add extra fields to category edit form hook
add_action ( 'edit_category_form_fields', 'infocenter_extra_category_fields');
//add extra fields to category edit form callback function
function infocenter_extra_category_fields( $tag ) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $cat_meta = get_option( "category_$t_id");
?>
<tr class="form-field">
<th scope="row" valign="top"><label for="extra1"><?php esc_html_e('Icon of the Category','infocenter'); ?></label></th>
<td>
<input type="text" name="Cat_meta[extra1]" id="Cat_meta[extra1]" size="25" style="width:60%;" value="<?php echo esc_attr($cat_meta['extra1']) ? esc_attr($cat_meta['extra1']) : ''; ?>"><br />
        <span class="description"><?php esc_html_e('Example Icon: fa fa-camera','infocenter'); ?></span>
    </td>
</tr>
<?php
} // save extra category extra fields hook
add_action ( 'edited_category', 'infocenter_save_extra_category_fileds');

// save extra category extra fields callback function
function infocenter_save_extra_category_fileds( $term_id ) {
    if ( isset( $_POST['Cat_meta'] ) ) {
        $t_id = $term_id;
        $cat_meta = get_option( "category_$t_id");
        $cat_keys = array_keys($_POST['Cat_meta']);
            foreach ($cat_keys as $key){
            if (isset($_POST['Cat_meta'][$key])){
                $cat_meta[$key] = $_POST['Cat_meta'][$key];
            }
        }
        //save the option array
        update_option( "category_$t_id", $cat_meta );
    }
}

// **********************************************************************// 
// ! Getting CSS and JS files for Error Header file
// **********************************************************************//
function infocenter_get_error_header_files() {
    wp_enqueue_style('infocenter-base', get_template_directory_uri().'/css/base.css');
	wp_enqueue_style('infocenter-lists', get_template_directory_uri().'/css/lists.css');
	wp_enqueue_style('bootstrap', get_template_directory_uri().'/css/bootstrap.min.css');
	wp_enqueue_style('prettyPhoto', get_template_directory_uri().'/css/prettyPhoto.css');
	wp_enqueue_style('infocenter-font_awesome_old', get_template_directory_uri().'/css/font-awesome-old/css/font-awesome.min.css');
	wp_enqueue_style('font-awesome', get_template_directory_uri().'/css/font-awesome/css/font-awesome.min.css');
	wp_enqueue_style('fontello', get_template_directory_uri().'/css/fontello/css/fontello.css');
	wp_enqueue_style('infocenter-stylesheet', get_template_directory_uri().'/style.css','',null,'all');
	wp_enqueue_style('infocenter-responsive', get_template_directory_uri()."/css/responsive.css");	
	wp_enqueue_style('infocenter-custom', get_template_directory_uri()."/css/custom.css");
	wp_enqueue_style('infocenter_style', get_template_directory_uri().'/css/more-style.css');
	wp_enqueue_style('infocenter_dropdownhover', get_template_directory_uri().'/css/bootstrap-dropdownhover.min.css');
	wp_enqueue_style('infocenter_select2', get_template_directory_uri().'/css/select2.min.css');
	wp_enqueue_style('infocenter_et-line-fonts', get_template_directory_uri().'/css/et-line-fonts.css');
	
	wp_enqueue_script("html5", get_template_directory_uri()."/js/html5.js",array("jquery"),'1.0.0',true);
	wp_enqueue_script("modernizr", get_template_directory_uri()."/js/modernizr.js",array("jquery"),'1.0.0',true);
	wp_enqueue_script("jquery.tipsy", get_template_directory_uri()."/js/jquery.tipsy.js",array("jquery"),'1.0.0',true);
	wp_enqueue_script("tabs", get_template_directory_uri()."/js/tabs.js",array("jquery"),'1.0.0',true);
	wp_enqueue_script("jquery.prettyphoto", get_template_directory_uri()."/js/jquery.prettyPhoto.js",array("jquery"),'1.0.0',true);
	wp_enqueue_script("jquery.scrollTo", get_template_directory_uri()."/js/jquery.scrollTo.js",array("jquery"),'1.0.0',true);
	wp_enqueue_script("jquery.nav", get_template_directory_uri()."/js/jquery.nav.js",array("jquery"),'1.0.0',true);
	wp_enqueue_script("infocenter_tags", get_template_directory_uri()."/js/tags.js",array("jquery"),'1.0.0',true);
	wp_enqueue_script("bootstrap", get_template_directory_uri()."/js/bootstrap.min.js",array("jquery"),'1.0.0',true);
	wp_enqueue_script("jquery.countTo", get_template_directory_uri()."/js/jquery.countTo.js",array("jquery"),'1.0.0',true);
	wp_enqueue_script("jquery.waypoints", get_template_directory_uri()."/js/jquery.waypoints.js",array("jquery"),'1.0.0',true);
	wp_enqueue_script("jquery.stellar", get_template_directory_uri()."/js/jquery.stellar.min.js",array("jquery"),'1.0.0',true);
	wp_enqueue_script("bootstrap-dropdownhover", get_template_directory_uri()."/js/bootstrap-dropdownhover.min.js",array("jquery"),'1.0.0',true);
}