<?php
/* Question */
if ((bool)get_option("FlushRewriteRules")) {
	flush_rewrite_rules(true);
	delete_option("FlushRewriteRules");
}

/* vpanel_remove_meta_boxes */
function vpanel_remove_meta_boxes() {
	remove_meta_box( 'question-categorydiv', 'question', 'side' );
}
add_action( 'admin_menu' , 'vpanel_remove_meta_boxes' );
/* Admin columns for post types */
function question_question_columns($old_columns){
	$columns = array();
	$columns["cb"]     = "<input type=\"checkbox\">";
	$columns["title"]  = __("Title","infocenter");
	$columns["type"]   = __("Type","infocenter");
	$columns["author"] = __("Added by","infocenter");
	$columns["date"]   = __("Date","infocenter");
	return $columns;
}
add_filter('manage_edit-question_columns', 'question_question_columns');

function question_question_custom_columns($column) {
	global $post;
	$question_details = question_get_question_details( $post->ID );
	switch ( $column ) {
		case 'type' :
			$question_poll = get_post_meta($post->ID,'question_poll',true);
			if ($question_poll == 1) {_e("Poll","infocenter");}else {_e("Question","infocenter");};
		break;
	}
}
add_action('manage_question_posts_custom_column', 'question_question_custom_columns', 2);

if (!function_exists('question_get_question_details')) {
	function question_get_question_details( $post_id ) { 
		$status = current(wp_get_object_terms( $post_id, 'site_status' ));
		return $post_id;
	}
}
function question_updated_messages($messages) {
  global $post,$post_ID;
  $messages['question'] = array(
    0 => '',
    1 => sprintf( __('Updated. <a href="%s">View question</a>','infocenter'),esc_url(get_permalink($post_ID))),
  );
  return $messages;
}
add_filter('post_updated_messages','question_updated_messages');

if (!function_exists('get_question_details')) {
	function get_question_details( $post_id ) { 
		
		if( function_exists( 'question_post_types_init' ) ) {
			$category = current(wp_get_object_terms( $post_id, 'question-category' ));
			} else {
			$category = current(wp_get_object_terms( $post_id, 'post_tag' ));
			}
		$video_type = get_post_meta($post_id,'video_type',true);
		$video_id = get_post_meta($post_id,'video_id',true);
		
		if (!isset($category->name)) $category = '';
		
		$question_details = array(
			'category'   => $category,
			'video_type' => $video_type,
			'video_id'   => $video_id,
		);
		return $question_details;
	}
}

/* Add Meta Boxes */
add_action( 'add_meta_boxes', 'question_meta_boxes' );
function question_meta_boxes() {
	add_meta_box( 'question_info', __('Questions','infocenter'), 'vpanel_question_meta', 'question', 'normal', 'high' );
}
/* Question Meta Box */
function vpanel_question_meta() {
	global $post;
	wp_nonce_field( 'vpanel_save_question_meta', 'vpanel_save_question_meta_nonce' );
	$question_id = $post->ID;
	$question_details = get_question_details( $question_id );
	?>
	<style type="text/css">
	.rwmb-field {
		margin: 10px 0;
	}
	.rwmb-label,.rwmb-input {
		display: inline-block;
		vertical-align: top;
	}
	.rwmb-label {
		width: 24%;
	}
	p.description {
		margin: 2px 0 5px;
	}
	p.description, span.description {
		font-family: sans-serif;
		font-size: 12px;
		font-style: italic;
	}
	</style>
	
	<?php
	$added_file = get_post_meta($post->ID, 'added_file', true);
	if ($added_file != "") {
		echo "<ul><li><div class='clear'></div><br><a href='".wp_get_attachment_url($added_file)."'>".__("Attachment","infocenter")."</a> - <a class='delete-this-attachment single-attachment' href='".$added_file."'>".__("Delete","infocenter")."</a></li></ul>";
	}
	$attachment_m = get_post_meta($post->ID, 'attachment_m');
	if (isset($attachment_m) && is_array($attachment_m) && !empty($attachment_m)) {
		$attachment_m = $attachment_m[0];
		if (isset($attachment_m) && is_array($attachment_m)) {
			echo "<ul>";
				foreach ($attachment_m as $key => $value) {
					echo "<li><div class='clear'></div><br><a href='".wp_get_attachment_url($value["added_file"])."'>".__("Attachment","infocenter")."</a> - <a class='delete-this-attachment' href='".$value["added_file"]."'>".__("Delete","infocenter")."</a></li>";
				}
			echo "</ul>";
		}
	}
	
    $custom = get_post_custom($post->ID);
	if (!empty($custom["ask"])) {
		$asks = unserialize($custom["ask"][0]);
	}?>
	<div class="rwmb-field">

		<div class="rwmb-label">
			<label for="vpanel_question_poll"><?php esc_html_e("This question is a poll?","infocenter")?></label>
		</div>
		<div class="rwmb-input">
        	<?php $question_poll = get_post_meta($post->ID, 'question_poll', true)?>
			<input type="checkbox" id="vpanel_question_poll" name="question_poll" value="1" <?php if ($question_poll != "" && $question_poll == 1){echo 'checked="checked"';} ?>>
		</div><div class="clear"></div>

        <div class="vpanel_poll_options<?php if ($question_poll == "") {echo " nodisplay";}?>">
			<input id="upload_add_ask" type="button" class="question_poll" value="<?php esc_html_e("Add a new option to poll","infocenter")?>">
			<div class="clear"></div>
			<div class="rwmb-label">
				<label><?php esc_html_e("Poll Options","infocenter")?></label><br>
			</div>
			<ul id="question_poll_item">
				<?php $i=0;
				if(isset($asks)){
					foreach( $asks as $ask ):
						$i++;?>
						<li id="listItem_<?php echo esc_attr($i);?>"  class="ui-state-default">
							<div class="widget-content option-item">
								<div class="rwmb-input">
									<input id="ask[<?php echo esc_attr($i);?>][title]" name="ask[<?php echo esc_attr($i);?>][title]" value="<?php echo stripslashes( $ask['title'] ) ?>" type="text">
									<input id="ask[<?php echo esc_attr($i);?>][value]" name="ask[<?php echo esc_attr($i);?>][value]" value="<?php echo stripslashes( $ask['value'] ) ?>" type="hidden">
									<input id="ask[<?php echo esc_attr($i);?>][id]" name="ask[<?php echo esc_attr($i);?>][id]" value="<?php echo stripslashes( $ask['id'] ) ?>" type="hidden">
									<a class="del-cat">x</a>
								</div>
							</div>
						</li>
					<?php
					endforeach;
				}?>
			</ul>
			<script> var nextCell = <?php echo esc_attr($i+1);?> ;</script>
        </div><div class="clear"></div><br>
        
        <?php
        if ($question_poll != "" && $question_poll == 1) {
        	if (isset($asks)) {
        		echo '<div class="rwmb-label"><label>Stats of User</label></div><div class="clear"></div><br>';
        		foreach( $asks as $ask ):$i++;
        			echo stripslashes( $ask['title'] ).' --- '.(isset($ask['value']) && $ask['value'] != 0?stripslashes( $ask['value'] ):0)." Votes <br>";
	        		if (isset($ask['user_ids']) && is_array($ask['user_ids'])) {
	        			foreach ($ask['user_ids'] as $key => $value) {
	        				if ($value != 0) {
	        					echo "<div class='vpanel_checkbox_input'><p class='description'>".get_user_by("id",$value)->display_name." Has vote for ".stripslashes( $ask['title'] )."</p></div>";
	        				}else {
	        					echo "<div class='vpanel_checkbox_input'><p class='description'>Unregistered user Has vote for ".stripslashes( $ask['title'] )."</p></div>";
	        				}
	        			}
	        			echo "<br>";
	        		}
	        	endforeach;?>
	        	<div class="clear"></div><br>
        	<?php }
        }
        ?>
        
		<div class="rwmb-label">
			<label for="vpanel_question-category"><?php esc_html_e("Category","infocenter")?></label>
		</div>
		<div class="rwmb-input">
			<?php
			$term_array = array();
			$terms = get_terms( 'question-category', array( 'hide_empty' => '0', 'orderby' => 'description' ) );
			if ($terms && sizeof($terms) > 0) :
				foreach ($terms as $term) :
					$term_array[$term->term_id] = $term->name;
				endforeach;
			endif;
			?>
			<div class="styled-select">
				<select class="rwmb-select" id="vpanel_question-category" name="question-category">
					<?php foreach ($term_array as $id => $name) : ?>
						<option value="<?php echo esc_attr($id); ?>" <?php if (isset($question_details['category']->term_id) && $question_details['category']->term_id==$id) echo 'selected="selected"'; ?>><?php echo esc_attr($name); ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<p class="description"><?php esc_html_e("Choose from here the question category.","infocenter")?></p>
			<p class="error"><?php esc_html_e("You must choose a category. If you keep it empty, your question will have Error Notice at the front-end. Please create
			question categories first from Categories Sub-Menu of Questions Menu of this wp-admin page's left sidebar.","infocenter")?></p>
		</div><div class="clear"></div>
		
		<div class="rwmb-label">
			<label for="vpanel_video_description"><?php esc_html_e("Video description","infocenter")?></label>
		</div>
		<div class="rwmb-input">
			<?php
			$video_description = get_post_meta($post->ID, 'video_description', true);
			?>
			<input type="checkbox" id="vpanel_video_description" name="video_description" value="1" <?php if ($video_description != "" && $video_description == 1){echo 'checked="checked"';} ?>>
			<p class="description"><?php esc_html_e("Do you need a video to description the problem better ?","infocenter")?></p>
		</div>
		
		<div class="video_description">
			<div class="rwmb-label">
				<label for="vpanel_video_type"><?php esc_html_e("Video type","infocenter")?></label>
			</div>
			<div class="rwmb-input">
				<div class="styled-select">
					<select class="rwmb-select" id="vpanel_video_type" name="video_type">
						<option value="youtube" <?php echo (isset($question_details['video_type']) && $question_details['video_type'] == "youtube"?' selected="selected"':'')?>>Youtube</option>
						<option value="vimeo" <?php echo (isset($question_details['video_type']) && $question_details['video_type'] == "vimeo"?' selected="selected"':'')?>>Vimeo</option>
						<option value="daily" <?php echo (isset($question_details['video_type']) && $question_details['video_type'] == "daily"?' selected="selected"':'')?>>Dialymotion</option>
					</select>
				</div>
				<p class="description"><?php esc_html_e("Choose from here the video type.","infocenter")?></p>
			</div><div class="clear"></div>
		
			<div class="rwmb-label">
				<label for="vpanel_video_id"><?php esc_html_e("Video ID","infocenter")?></label>
			</div>
			<div class="rwmb-input">
				<input type="text" class="rwmb-select" id="vpanel_video_id" name="video_id" <?php echo (isset($question_details['video_id'])?' value="'.$question_details['video_id'].'"':'')?>>
				<p class="description"><?php esc_html_e("Put here the video id : http://www.youtube.com/watch?v=sdUUx5FdySs EX : 'sdUUx5FdySs'.","infocenter")?></p>
			</div><div class="clear"></div>
		</div>
		
		<div class="rwmb-label">
			<label for="vpanel_remember_answer"><?php esc_html_e("Notified by e-mail","infocenter")?></label>
		</div>
		<div class="rwmb-input">
			<?php
			$remember_answer = get_post_meta($post->ID, 'remember_answer', true);
			?>
			<input type="checkbox" id="vpanel_remember_answer" name="remember_answer" value="1" <?php if ($remember_answer != "" && $remember_answer == 1){echo 'checked="checked"';} ?>>
			<p class="description"><?php esc_html_e("Notified by e-mail at incoming answers","infocenter")?></p>
		</div>
        
	</div>
	<?php
}	
/* Process question Meta Box */
add_action( 'save_post', 'vpanel_question_meta_save', 1, 2 );
function vpanel_question_meta_save( $post_id, $post ) {
	global $wpdb,$post;
	if ( !$_POST ) return $post_id;
	if ( isset($post) && $post->post_type != 'question' ) return $post_id;
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post_id;
	if ( !isset($_POST['vpanel_save_question_meta_nonce']) || !wp_verify_nonce( $_POST['vpanel_save_question_meta_nonce'], 'vpanel_save_question_meta' )) return $post_id;
	if ( !current_user_can( 'edit_post', $post_id )) return $post_id;
	
	$data = array();
	
	// Get Post Data
	$data['question-category'] = (isset($_POST['question-category'])?stripslashes( $_POST['question-category'] ):"");
	
	// category
	if (isset($_POST['question-category'])) {
		$new_term_slug = get_term_by( 'id', $data['question-category'], 'question-category')->slug;
		wp_set_object_terms( $post_id, $new_term_slug, 'question-category' );
	}

  	if( isset($_POST['ask']) && $_POST['ask'] != "" ){
		update_post_meta($post_id, 'ask' , $_POST['ask']);
	}
	
  	if( isset($_POST['question_poll']) && $_POST['question_poll'] != "" ){
		update_post_meta($post_id, 'question_poll' , $_POST['question_poll']);
	}else {
		update_post_meta($post_id, 'question_poll' , 2);
	}
	
  	if( isset($_POST['best_answer']) && $_POST['best_answer'] != "" ){
		update_post_meta($post_id, 'best_answer' , $_POST['best_answer']);		
	}
	
	if ( isset($_POST['video_type']) && $_POST['video_type'] != "" ) {
		update_post_meta($post_id, 'video_type', $_POST['video_type']);
	}
		
	if ( isset($_POST['video_id']) && $_POST['video_id'] != "" ) {
		update_post_meta($post_id, 'video_id', $_POST['video_id']);
	}
	
  	if( isset($_POST['remember_answer']) && $_POST['remember_answer'] != "" ){
		update_post_meta($post_id, 'remember_answer' , $_POST['remember_answer']);
	}else {
		delete_post_meta($post_id, 'remember_answer');
	}
	
	if( isset($_POST['video_description']) && $_POST['video_description'] != "" ){
		update_post_meta($post_id, 'video_description' , $_POST['video_description']);
	}else {
		delete_post_meta($post_id, 'video_description');
	}
	
	$user_id = get_current_user_id();
	
	$add_questions = get_user_meta($user_id,"add_questions_all",true);
	$add_questions_m = get_user_meta($user_id,"add_questions_m_".date_i18n('m_Y',current_time('timestamp')),true);
	$add_questions_d = get_user_meta($user_id,"add_questions_d_".date_i18n('d_m_Y',current_time('timestamp')),true);
	if ($add_questions_d == "" or $add_questions_d == 0) {
		add_user_meta($user_id,"add_questions_d_".date_i18n('d_m_Y',current_time('timestamp')),1);
	}else {
		update_user_meta($user_id,"add_questions_d_".date_i18n('d_m_Y',current_time('timestamp')),$add_questions_d+1);
	}
	
	if ($add_questions_m == "" or $add_questions_m == 0) {
		add_user_meta($user_id,"add_questions_m_".date_i18n('m_Y',current_time('timestamp')),1);
	}else {
		update_user_meta($user_id,"add_questions_m_".date_i18n('m_Y',current_time('timestamp')),$add_questions_m+1);
	}
	
	if ($add_questions == "" or $add_questions == 0) {
		add_user_meta($user_id,"add_questions_all",1);
	}else {
		update_user_meta($user_id,"add_questions_all",$add_questions+1);
	}
}
/* set_post_stats */
function set_post_stats() {
    $post_id = get_the_ID();
    if (is_single($post_id) || is_page($post_id)) {
        $current_stats = get_post_meta($post_id, 'post_stats', true);
        if (!isset($current_stats)) {
            add_post_meta($post_id, 'post_stats', 1, true);
        } else {
            if (is_numeric($post_id) && is_numeric($current_stats)) {
			update_post_meta($post_id, 'post_stats', $current_stats + 1);
			}
        }
    }
}
add_action('wp_head', 'set_post_stats', 1000);
/* extra_category_fields */
function extra_category_fields_edit ($tag) {
	if (isset($tag->term_id)) {
		$t_id = $tag->term_id;
		$questions_category = get_option("questions_category_$t_id");
	}?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="private"><?php esc_html_e("Private category?","infocenter")?></label></th>
		<td>
			<input id="private" class="checkbox of-input vpanel_checkbox" type="checkbox" name="questions_category[private]" <?php echo isset($questions_category['private']) && $questions_category['private'] == "on"?'checked="checked"':'';?>>
			<p class="description"><?php esc_html_e("Select 'On' to enable private category. (In private categories questions can only be seen by the author of the question and the admin).","infocenter")?></p><br><br>
			<div class="clear"></div>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="special"><?php esc_html_e("Special category?","infocenter")?></label></th>
		<td>
			<input id="special" class="checkbox of-input vpanel_checkbox" type="checkbox" name="questions_category[special]" <?php echo isset($questions_category['special']) && $questions_category['special'] == "on"?'checked="checked"':'';?>>
			<p class="description"><?php esc_html_e("Select 'On' to enable special category. (In a special category, the admin must answer the question before anyone else).","infocenter")?></p><br><br>
			<input type="hidden" name="questions_category[special_private]" value="special_private">
			<div class="clear"></div>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="new"><?php esc_html_e("New category?","infocenter")?></label></th>
		<td>
			<input id="new" class="checkbox of-input vpanel_checkbox" type="checkbox" name="questions_category[new]" <?php echo isset($questions_category['new']) && $questions_category['new'] == "on"?'checked="checked"':'';?>>
			<p class="description"><?php esc_html_e("Select 'On' to enable new category. (In a new category, the admin must answer the question before anyone else and the user has add question and admin only can answer).","infocenter")?></p><br><br>
			<div class="clear"></div>
		</td>
	</tr>
<?php
}
function extra_category_fields_edit_style ($tag) {
	if (isset($tag->term_id)) {
		$t_id = $tag->term_id;
		$categories = get_option("categories_$t_id");
	}?>
	<tr class="form-field">
		<th scope="row" valign="top"><label><?php esc_html_e("Sidebar layout","infocenter")?></label></th>
		<td>
			<div class="rwmb-input">
				<label class="radio_no_margin"><input type="radio" class="rwmb-radio" name="categories[cat_sidebar_layout]" value="default" <?php echo isset($categories['cat_sidebar_layout']) && $categories['cat_sidebar_layout'] == "default"?'checked="checked"':''.empty($categories['cat_sidebar_layout'])?'checked="checked"':'';?>></label>
				<label class="radio_no_margin"><input type="radio" class="rwmb-radio" name="categories[cat_sidebar_layout]" value="right" <?php echo isset($categories['cat_sidebar_layout']) && $categories['cat_sidebar_layout'] == "right"?'checked="checked"':'';?>></label>
				<label class="radio_no_margin"><input type="radio" class="rwmb-radio" name="categories[cat_sidebar_layout]" value="full" <?php echo isset($categories['cat_sidebar_layout']) && $categories['cat_sidebar_layout'] == "full"?'checked="checked"':'';?>></label>
				<label class="radio_no_margin"><input type="radio" class="rwmb-radio" name="categories[cat_sidebar_layout]" value="left" <?php echo isset($categories['cat_sidebar_layout']) && $categories['cat_sidebar_layout'] == "left"?'checked="checked"':'';?>></label>
			</div>
			<div class="clear"></div>
		</td>
	</tr>
	
	<tr class="form-field">
		<th scope="row" valign="top"><label for="cat_sidebar"><?php esc_html_e("Sidebar","infocenter")?></label></th>
		<td>
			<div class="styled-select">
				<select name="categories[cat_sidebar]" id="cat_sidebar">
					<?php $sidebars = get_option('sidebars');
					echo "<option ".(isset($categories['cat_sidebar']) && $categories['cat_sidebar'] == "default"?'selected="selected"':'')." value='default'>Default</option>";
					foreach ($GLOBALS['wp_registered_sidebars'] as $sidebar) {
						echo "<option ".(isset($categories['cat_sidebar']) && $categories['cat_sidebar'] == $sidebar['id']?'selected="selected"':'')." value='".$sidebar['id']."'>".$sidebar['name']."</option>";
					}?>
				</select>
			</div>
			<div class="clear"></div>
		</td>
	</tr>
<?php
}
function extra_category_fields ($tag) {?>
	<div class="form-field">
		<label for="private"><?php esc_html_e("Private category?","infocenter")?></label>
		<input id="private" class="checkbox of-input vpanel_checkbox" type="checkbox" name="questions_category[private]">
		<p><?php esc_html_e("Select 'On' to enable private category. (In private categories questions can only be seen by the author of the question and the admin).","infocenter")?></p>
	</div>
	<div class="form-field">
		<label for="special"><?php esc_html_e("Special category?","infocenter")?></label>
		<input id="special" class="checkbox of-input vpanel_checkbox" type="checkbox" name="questions_category[special]">
		<p><?php esc_html_e("Select 'On' to enable special category. (In a special category, the admin must answer the question before anyone else).","infocenter")?></p>
	</div>
	<div class="form-field">
		<label for="new"><?php esc_html_e("New category?","infocenter")?></label>
		<input id="new" class="checkbox of-input vpanel_checkbox" type="checkbox" name="questions_category[new]">
		<p><?php esc_html_e("Select 'On' to enable new category. (In a new category, the admin must answer the question before anyone else and the user has add question and admin only can answer).","infocenter")?></p>
	</div>
<?php
}
function extra_category_fields_style ($tag) {?>
	<div class="form-field">
		<label>Sidebar layout</label>
		<div class="rwmb-input">
			<label class="radio_no_margin"><input type="radio" class="rwmb-radio" name="categories[cat_sidebar_layout]" value="default" checked="checked"></label>
			<label class="radio_no_margin"><input type="radio" class="rwmb-radio" name="categories[cat_sidebar_layout]" value="right"></label>
			<label class="radio_no_margin"><input type="radio" class="rwmb-radio" name="categories[cat_sidebar_layout]" value="full"></label>
			<label class="radio_no_margin"><input type="radio" class="rwmb-radio" name="categories[cat_sidebar_layout]" value="left"></label>
		</div>
	</div>
	
	<div class="form-field">
		<label for="cat_sidebar"><?php esc_html_e("Sidebar","infocenter")?></label>
		<div class="styled-select">
			<select name="categories[cat_sidebar]" id="cat_sidebar">
				<?php $sidebars = get_option('sidebars');
				echo "<option value='default'>Default</option>";
				foreach ($GLOBALS['wp_registered_sidebars'] as $sidebar) {
					echo "<option value='".esc_attr($sidebar['id'])."'>".esc_attr($sidebar['name'])."</option>";
				}?>
			</select>
		</div>
	</div>
<?php
}
add_action('question-category_edit_form_fields','extra_category_fields_edit',10,2);
add_action ('question-category_add_form_fields','extra_category_fields',10,2);
/* style */
add_action('question-category_edit_form_fields','extra_category_fields_edit_style',10,2);
add_action ('question-category_add_form_fields','extra_category_fields_style',10,2);
add_action('category_edit_form_fields','extra_category_fields_edit_style',10,2);
add_action ('category_add_form_fields','extra_category_fields_style',10,2);
/* save_extra_category_fileds */
add_action('edited_question-category','save_extra_category_fileds',10,2);
add_action('create_question-category','save_extra_category_fileds',10,2);
/* save style */
add_action('edited_category','save_extra_category_fileds_style',10,2);
add_action('create_category','save_extra_category_fileds_style',10,2);
add_action('edited_question-category','save_extra_category_fileds_style',10,2);
add_action('create_question-category','save_extra_category_fileds_style',10,2);

add_action('edited_product_cat','save_extra_category_fileds_style',10,2);
add_action('create_product_cat','save_extra_category_fileds_style',10,2);
add_action('product_cat_edit_form_fields','extra_category_fields_edit_style',10,2);
add_action ('product_cat_add_form_fields','extra_category_fields_style',10,2);
function save_extra_category_fileds ($term_id) {
	if (isset($_POST['questions_category'])) {
		$t_id = $term_id;
		$questions_category = get_option("questions_category_$t_id");
		$questions_category = array_keys($_POST['questions_category']);
		foreach ($questions_category as $key){
			if (isset($_POST['questions_category'][$key])){
				$questions_category[$key] = $_POST['questions_category'][$key];
			}
		}
		update_option("questions_category_$t_id",$questions_category);
	}
}
function save_extra_category_fileds_style ($term_id) {
	if (isset($_POST['categories'])) {
		$t_id = $term_id;
		$categories = get_option("categories_$t_id");
		$categories = array_keys($_POST['categories']);
		foreach ($categories as $key){
			if (isset($_POST['categories'][$key])){
				$categories[$key] = $_POST['categories'][$key];
			}
		}
		update_option("categories_$t_id",$categories);
	}
}
if (is_admin()) {
	/* Count new reports */
	$ask_option_array = get_option("ask_option_array");
	if (is_array($ask_option_array)) {
		foreach ($ask_option_array as $key => $value) {
			$ask_one_option = get_option("ask_option_".$value);
			$post_no_empty = get_post($ask_one_option["post_id"]);
			if (!isset($post_no_empty)) {
				unset($ask_one_option);
			}
			if (isset($ask_one_option) && $ask_one_option["report_new"] == 1) {
				$count_report_new[] = $ask_one_option["report_new"];
			}
		}
	}
	/* Count new reports answers */
	$ask_option_answer_array = get_option("ask_option_answer_array");
	if (is_array($ask_option_answer_array)) {
		foreach ($ask_option_answer_array as $key => $value) {
			$ask_one_option = get_option("ask_option_answer_".$value);
			$comment_no_empty = get_comment($ask_one_option["comment_id"]);
			if (!isset($comment_no_empty)) {
				unset($ask_one_option);
			}
			if (isset($ask_one_option) && $ask_one_option["report_new"] == 1) {
				$count_report_answer_new[] = $ask_one_option["report_new"];
			}
		}
	}
	/* reports_delete */
	function reports_delete() {
		$reports_delete_id = (int)esc_html($_POST["reports_delete_id"]);
		/* delete option */
		delete_option("ask_option_".$reports_delete_id);
		$ask_option_array = get_option("ask_option_array");
		$ask_option = get_option("ask_option");
		$ask_option--;
		update_option("ask_option",$ask_option);
		$arr = array_diff($ask_option_array, array($reports_delete_id));
		update_option("ask_option_array",$arr);
		die();
	}
	add_action("wp_ajax_nopriv_reports_delete","reports_delete");
	add_action("wp_ajax_reports_delete","reports_delete");
	/* reports_view */
	function reports_view() {
		$reports_view_id = (int)esc_html($_POST["reports_view_id"]);
		/* option */
		$ask_one_option = get_option("ask_option_".$reports_view_id);
		$item_id_option = $ask_one_option["item_id_option"];
		foreach ($ask_one_option as $key => $value) {
			if ($key == "report_new") {
				$ask_one_option["report_new"] = 0;
			}
		}
		update_option("ask_option_".$reports_view_id,$ask_one_option);
		die();
	}
	add_action("wp_ajax_nopriv_reports_view","reports_view");
	add_action("wp_ajax_reports_view","reports_view");
	/* reports_answers_delete */
	function reports_answers_delete() {
		$reports_delete_id = (int)esc_html($_POST["reports_delete_id"]);
		/* delete option */
		delete_option("ask_option_answer_".$reports_delete_id);
		$ask_option_answer_array = get_option("ask_option_answer_array");
		$ask_option_answer = get_option("ask_option_answer");
		$ask_option_answer--;
		update_option("ask_option_answer",$ask_option_answer);
		$arr = array_diff($ask_option_answer_array, array($reports_delete_id));
		update_option("ask_option_answer_array",$arr);
		die();
	}
	add_action("wp_ajax_nopriv_reports_answers_delete","reports_answers_delete");
	add_action("wp_ajax_reports_answers_delete","reports_answers_delete");
	/* reports_answers_view */
	function reports_answers_view() {
		$reports_view_id = (int)esc_html($_POST["reports_view_id"]);
		echo esc_attr($reports_view_id);
		/* option */
		$ask_one_option = get_option("ask_option_answer_".$reports_view_id);
		$item_id_option = $ask_one_option["item_id_option"];
		foreach ($ask_one_option as $key => $value) {
			if ($key == "report_new") {
				$ask_one_option["report_new"] = 0;
			}
		}
		update_option("ask_option_answer_".$reports_view_id,$ask_one_option);
		die();
	}
	add_action("wp_ajax_nopriv_reports_answers_view","reports_answers_view");
	add_action("wp_ajax_reports_answers_view","reports_answers_view");
	/* publishing_action_post */
	function publishing_action_post() {
		$post_ID = (int)$_POST["post_ID"];
	    $question_username = get_post_meta($post_ID, 'question_username', true);
	    $question_email = get_post_meta($post_ID, 'question_email', true);
	    $post_username = get_post_meta($post_ID, 'post_username', true);
	    $post_email = get_post_meta($post_ID, 'post_email', true);
	    if ((isset($question_username) && $question_username != "" && isset($question_email) && $question_email != "") || (isset($post_username) && $post_username != "" && isset($post_email) && $post_email != "")) {
	    	$get_post = get_post($post_ID);
	    	$publish_date = $get_post->post_date;
	        $data = array(
	        	'ID' => $post_ID,
	        	'post_author' => "No_user",
	        );
	    	wp_update_post($data);
	    }
	}
	add_action('wp_ajax_publishing_action_post','publishing_action_post');
	add_action('wp_ajax_nopriv_publishing_action_post','publishing_action_post');
	/* confirm_delete_attachment */
	function confirm_delete_attachment() {
		$attachment_id     = (int)$_POST["attachment_id"];
		$post_id           = (int)$_POST["post_id"];
		$single_attachment = esc_attr($_POST["single_attachment"]);
		if ($single_attachment == "Yes") {
			wp_delete_attachment($attachment_id);
			delete_post_meta($post_id, 'added_file');
		}else {
			$attachment_m = get_post_meta($post_id, 'attachment_m');
			if (isset($attachment_m) && is_array($attachment_m) && !empty($attachment_m)) {
				$attachment_m = $attachment_m[0];
				if (isset($attachment_m) && is_array($attachment_m)) {
					foreach ($attachment_m as $key => $value) {
						if ($value["added_file"] == $attachment_id) unset($attachment_m[$key]);
						wp_delete_attachment($value["added_file"]);
					}
				}
			}
			update_post_meta($post_id, 'attachment_m', $attachment_m);
		}
		die();
	}
	add_action('wp_ajax_confirm_delete_attachment','confirm_delete_attachment');
	add_action('wp_ajax_nopriv_confirm_delete_attachment','confirm_delete_attachment');
}
/* ask_add_admin_page_reports */
function ask_add_admin_page_reports() {
	$active_reports = INFOCENTER_OPTIONS("active_reports");
	if ($active_reports == 1) {
		global $count_report_new,$count_report_answer_new;
		$count_report_new = count($count_report_new);
		$count_report_answer_new = count($count_report_answer_new);
		$count_lasts = $count_report_new+$count_report_answer_new;
		$vpanel_page = add_theme_page('Reports', 'Reports <span class="count_report_new awaiting-mod count-'.$count_lasts.'"><span class="count_lasts">'.$count_lasts.'</span></span>' ,'manage_options', 'r_questions' , 'r_questions','dashicons-email-alt');
		add_theme_page( 'r_questions', 'Questions', 'Questions <span class="count_report_new awaiting-mod count-'.$count_report_new.'"><span class="count_report_question_new">'.$count_report_new.'</span></span>', 'manage_options', 'r_questions', 'r_questions' );
		add_theme_page( 'r_questions', 'Answers', 'Answers <span class="count_report_new awaiting-mod count-'.$count_report_answer_new.'"><span class="count_report_answer_new">'.$count_report_answer_new.'</span></span>', 'manage_options', 'r_answers', 'r_answers' );
	}
}
add_action('admin_menu', 'ask_add_admin_page_reports');
/* r_questions */
function r_questions () {
	global $user_identity,$public_display;
	?>
	<div class="reports-warp">
		<div class="reports-head"><i class="dashicons dashicons-flag"></i><?php esc_html_e("Questions Reports","infocenter")?></div>
		<div class="reports-padding">
			<div class="reports-table">
				<div class="reports-table-head">
					<div class="report-link"><?php esc_html_e("Link","infocenter")?></div>
					<div class="report-author"><?php esc_html_e("Author","infocenter")?></div>
					<div class="report-date"><?php esc_html_e("Date","infocenter")?></div>
					<div class="reports-options"><?php esc_html_e("Options","infocenter")?></div>
				</div><!-- End reports-table-head -->
				<?php
				$rows_per_page = get_option("posts_per_page");
				$ask_option = get_option("ask_option");
				$ask_option_array = get_option("ask_option_array");
				if (is_array($ask_option_array)) {
					foreach ($ask_option_array as $key => $value) {
						$ask_one_option[$value] = get_option("ask_option_".$value);
						$post_no_empty = get_post($ask_one_option[$value]["post_id"]);
						if (!isset($post_no_empty)) {
							unset($ask_one_option[$value]);
						}
					}
				}
				if (isset($ask_one_option) && is_array($ask_one_option) && !empty($ask_one_option)) {?>
					<div class="reports-table-items">
					<?php
					$ask_reports_option = array_reverse($ask_one_option);
					$paged = (isset($_GET["paged"])?(int)$_GET["paged"]:1);
					$current = max(1,$paged);
					$pagination_args = array(
						'base' => @esc_url(add_query_arg('paged','%#%')),
						'total' => ceil(sizeof($ask_reports_option)/$rows_per_page),
						'current' => $current,
						'show_all' => false,
						'prev_text' => '&laquo; Previous',
						'next_text' => 'Next &raquo;',
					);
					if( !empty($wp_query->query_vars['s']) )
						$pagination_args['add_args'] = array('s' => get_query_var('s'));
						
					$start = ($current - 1) * $rows_per_page;
					$end = $start + $rows_per_page;
					$end = (sizeof($ask_reports_option) < $end) ? sizeof($ask_reports_option) : $end;
					for ($i=$start;$i < $end ;++$i ) {
						$ask_reports_option_result = $ask_reports_option[$i];?>
						<div class="reports-table-item">
							<div class="report-link"><a href="<?php echo get_the_permalink($ask_reports_option_result["post_id"]);?>"><?php echo get_the_permalink($ask_reports_option_result["post_id"]);?></a></div>
							<div class="report-author">
								<?php
								if ($ask_reports_option_result["the_author"] != "") {
									if ($ask_reports_option_result["the_author"] == 1) {
										echo "Not user";
									}else {
										echo esc_attr($ask_reports_option_result["the_author"]);
									}
								}else {
									?><a href="<?php echo esc_url(vpanel_get_user_url((int)$ask_reports_option_result["user_id"]));?>"><?php echo get_the_author_meta("display_name",(int)$ask_reports_option_result["user_id"])?></a><?php
								}
								?>
							</div>
							<div class="report-date"><?php echo human_time_diff($ask_reports_option_result["the_date"],current_time('timestamp'))." ago"?></div>
							<div class="reports-options">
								<a href="#" class="reports-view dashicons dashicons-search" attr="<?php echo esc_attr($ask_reports_option_result["item_id_option"])?>"></a>
								<a href="#" attr="<?php echo esc_attr($ask_reports_option_result["item_id_option"]);?>" class="reports-delete dashicons dashicons-no"></a>
								<?php if ($ask_reports_option_result["report_new"] == 1) {?>
									<div title="New reports" class="reports-new dashicons dashicons-email-alt"></div>
								<?php }?>
							</div>
							<div id="reports-<?php echo esc_attr($ask_reports_option_result["item_id_option"]);?>" class="reports-pop">
								<div class="reports-pop-no-scroll">
									<div class="reports-pop-inner">
										<a href="#" class="reports-close dashicons dashicons-no"></a>
										<div class="clear"></div>
										<div class="reports-pop-warp">
											<div>
												<div><?php esc_html_e("Message","infocenter")?></div>
												<div><?php echo nl2br($ask_reports_option_result["value"])?></div>
											</div>
										</div>
										<div class="clear"></div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
					</div><!-- End reports-table-items -->
				<?php }else {
					echo "<p>There are no reports yet</p>";
				}
				?>
			</div><!-- End reports-table -->
			<?php if (isset($pagination_args["total"]) && $pagination_args["total"] > 1) {?>
				<div class='reports-paged'>
					<?php echo (paginate_links($pagination_args) != ""?paginate_links($pagination_args):"")?>
				</div><!-- End reports-paged -->
				<div class="clear"></div>
			<?php }?>
		</div><!-- End reports-padding -->
	</div><!-- End reports-warp -->
	<?php
}
/* r_answers */
function r_answers () {
	?>
	<div class="reports-warp">
		<div class="reports-head"><i class="dashicons dashicons-flag"></i><?php esc_html_e("Answers Reports","infocenter")?></div>
		<div class="reports-padding">
			<div class="reports-table">
				<div class="reports-table-head">
					<div class="report-link"><?php esc_html_e("Link","infocenter")?></div>
					<div class="report-author"><?php esc_html_e("Author","infocenter")?></div>
					<div class="report-date"><?php esc_html_e("Date","infocenter")?></div>
					<div class="reports-options"><?php esc_html_e("Options","infocenter")?></div>
				</div><!-- End reports-table-head -->
				<?php
				$rows_per_page = get_option("posts_per_page");
				$ask_option_answer = get_option("ask_option_answer");
				$ask_option_answer_array = get_option("ask_option_answer_array");
				if (is_array($ask_option_answer_array)) {
					foreach ($ask_option_answer_array as $key => $value) {
						$ask_one_option[$value] = get_option("ask_option_answer_".$value);
						$comment_no_empty = get_comment($ask_one_option[$value]["comment_id"]);
						if (!isset($comment_no_empty)) {
							unset($ask_one_option[$value]);
						}
					}
				}
				if (isset($ask_one_option) && is_array($ask_one_option) && !empty($ask_one_option)) {?>
					<div class="reports-table-items">
					<?php
					$ask_reports_option = array_reverse($ask_one_option);
					$paged = (isset($_GET["paged"])?(int)$_GET["paged"]:1);
					$current = max(1,$paged);
					$pagination_args = array(
						'base' => @esc_url(add_query_arg('paged','%#%')),
						'total' => ceil(sizeof($ask_reports_option)/$rows_per_page),
						'current' => $current,
						'show_all' => false,
						'prev_text' => '&laquo; Previous',
						'next_text' => 'Next &raquo;',
					);
					if( !empty($wp_query->query_vars['s']) )
						$pagination_args['add_args'] = array('s' => get_query_var('s'));
						
					$start = ($current - 1) * $rows_per_page;
					$end = $start + $rows_per_page;
					$end = (sizeof($ask_reports_option) < $end) ? sizeof($ask_reports_option) : $end;
					for ($i=$start;$i < $end ;++$i ) {
						$ask_reports_option_result = $ask_reports_option[$i];?>
						<div class="reports-table-item">
							<div class="report-link"><a href="<?php echo get_the_permalink($ask_reports_option_result["post_id"]);?>#comment-<?php echo esc_attr($ask_reports_option_result["comment_id"]);?>"><?php echo get_the_permalink($ask_reports_option_result["post_id"]);?>#comment-<?php echo esc_attr($ask_reports_option_result["comment_id"]);?></a></div>
							<div class="report-author">
								<?php
								if ($ask_reports_option_result["the_author"] != "") {
									if ($ask_reports_option_result["the_author"] == 1) {
										echo "Not user";
									}else {
										echo esc_attr($ask_reports_option_result["the_author"]);
									}
								}else {
									?><a href="<?php echo esc_url(vpanel_get_user_url((int)$ask_reports_option_result["user_id"]));?>"><?php echo get_the_author_meta("display_name",(int)$ask_reports_option_result["user_id"])?></a><?php
								}
								?>
							</div>
							<div class="report-date"><?php echo human_time_diff($ask_reports_option_result["the_date"],current_time('timestamp'))." ago"?></div>
							<div class="reports-options">
								<a href="#" class="reports-view reports-answers dashicons dashicons-search" attr="<?php echo esc_attr($ask_reports_option_result["item_id_option"]);?>"></a>
								<a href="#" attr="<?php echo esc_attr($ask_reports_option_result["item_id_option"]);?>" class="reports-delete reports-answers dashicons dashicons-no"></a>
								<?php if ($ask_reports_option_result["report_new"] == 1) {?>
									<div title="New reports" class="reports-new dashicons dashicons-email-alt"></div>
								<?php }?>
							</div>
							<div id="reports-<?php echo esc_attr($ask_reports_option_result["item_id_option"]);?>" class="reports-pop">
								<div class="reports-pop-no-scroll">
									<div class="reports-pop-inner">
										<a href="#" class="reports-close dashicons dashicons-no"></a>
										<div class="clear"></div>
										<div class="reports-pop-warp">
											<div>
												<div><?php esc_html_e("Message","infocenter")?></div>
												<div><?php echo nl2br($ask_reports_option_result["value"])?></div>
											</div>
										</div>
										<div class="clear"></div>
									</div>
								</div>
							</div>
						</div>
					<?php } ?>
					</div><!-- End reports-table-items -->
				<?php }else {
					echo "<p>There are no reports yet</p>";
				}
				?>
			</div><!-- End reports-table -->
			<?php if (isset($pagination_args["total"]) && $pagination_args["total"] > 1) {?>
				<div class='reports-paged'>
					<?php echo (paginate_links($pagination_args) != ""?paginate_links($pagination_args):"")?>
				</div><!-- End reports-paged -->
				<div class="clear"></div>
			<?php }?>
		</div><!-- End reports-padding -->
	</div><!-- End reports-warp -->
	<?php
}
/* vpanel_user_table */
function vpanel_user_table( $column ) {
	$user_meta_admin = INFOCENTER_OPTIONS("user_meta_admin");
	if (isset ($user_meta_admin) && is_array($user_meta_admin)) {
		if (isset($user_meta_admin["phone"]) && $user_meta_admin["phone"] == 1) {
			$column['phone']   = 'Phone';
		}
		if (isset($user_meta_admin["country"]) && $user_meta_admin["country"] == 1) {
			$column['country'] = 'Country';
		}
		if (isset($user_meta_admin["age"]) && $user_meta_admin["age"] == 1) {
			$column['age']     = 'Age';
		}
	}
	return $column;
}
add_filter( 'manage_users_columns', 'vpanel_user_table' );
function vpanel_user_table_row( $val, $column_name, $user_id ) {
	$user = get_userdata( $user_id );
	switch ($column_name) {
		case 'phone' :
			return get_the_author_meta( 'phone', $user_id );
			break;
		case 'country' :
			$get_countries = vpanel_get_countries();
			$country = get_the_author_meta( 'country', $user_id );
			if ($country && $user_country != 1 && isset($get_countries[$country])) {
				return $get_countries[$country];
			}else {
				return '';
			}
			break;
		case 'age' :
			return get_the_author_meta( 'age', $user_id );
			break;
		default:
	}
	return $return;
}
add_filter( 'manage_users_custom_column', 'vpanel_user_table_row', 10, 3 );