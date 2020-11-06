<?php
$prefix = 'infocenter_';
add_action( 'admin_init', 'infocenter_register_meta_boxes' );
function infocenter_register_meta_boxes() {
	global $prefix;
	if ( ! class_exists( 'RW_Meta_Box' ) )
		return;
	
	$options_categories = array();
	$options_categories_obj = get_categories();
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	$sidebars = get_option('sidebars');
	$new_sidebars = array('default'=> 'Default');
	foreach ($GLOBALS['wp_registered_sidebars'] as $sidebar) {
		$new_sidebars[$sidebar['id']] = $sidebar['name'];
	}
	
	// Menus
    $menus = array();
    $all_menus = get_terms('nav_menu',array('hide_empty' => true));
	foreach ($all_menus as $menu) {
	    $menus[$menu->term_id] = $menu->name;
	}
	
	
	$meta_boxes = array();
	$post_types = get_post_types();

	$meta_boxes[] = array(
		'id' => 'blog',
		'title' => 'Blog Options',
		'pages' => array('page'),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array(
				'name' => "Post number",
				'desc' => "put the post number",
				'id' => $prefix.'post_number_b',
				'type' => 'text',
				'std' => "5"
			),
			array(
				'name' => "Order by",
				'desc' => "Select the post order by .",
				'id' => $prefix."orderby_post_b",
				'std' => array("recent"),
				'type' => "select",
				'options' => array(
					'recent' => 'Recent',
					'popular' => 'Popular',
					'random' => 'Random',
				)
			),
			array(
				'name'		=> "Display by",
				'id'		=> $prefix."post_display_b",
				'type'		=> 'select',
				'options'	=> array(
					'lasts'	=> 'Lasts',
					'single_category' => 'Single category',
					'multiple_categories' => 'Multiple categories',
					'posts'	=> 'Custom posts',
				),
				'std'		=> array('lasts'),
			),
			array(
				'name'		=> 'Single category',
				'id'		=> $prefix.'post_single_category_b',
				'type'		=> 'select',
				'options'	=> $options_categories,
			),
			array(
				'name' => "Post categories",
				'desc' => "Select the post categories .",
				'id' => $prefix."post_categories_b",
				'options' => $options_categories,
				'type' => 'checkbox_list'
			),
			array(
				'name'     => "Post ids",
				'desc'     => "Type the post ids .",
				'id'       => $prefix."post_posts_b",
				'std'      => '',
				'type'     => 'text',
			),
		),
	);
	
	$meta_boxes[] = array(
		'id' => 'contact_us',
		'title' => 'Contact us Options',
		'pages' => array('page'),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array(
				'name' => 'Map',
				'desc' => 'Put the code iframe map .',
				'id'   => $prefix.'contact_map',
				'std'  => '<iframe height="400" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps?q=egypt&amp;hl=en&amp;sll=26.820553,30.802498&amp;sspn=16.874794,19.753418&amp;hnear=Egypt&amp;t=m&amp;z=6&amp;output=embed"></iframe>',
				'type' => 'textarea'
			),
			array(
				'name' => 'Form shortcode',
				'desc' => 'Put the form shortcode .',
				'id'   => $prefix.'contact_form',
				'type' => 'text'
			),
			array(
				'name' => 'About widget enable or disable',
				'desc' => 'About widget enable or disable .',
				'id'   => $prefix.'about_widget',
				'std'  => 1,
				'type' => 'checkbox'
			),
			array(
				'name' => 'About content',
				'desc' => 'Put the about content .',
				'id'   => $prefix.'about_content',
				'type' => 'textarea'
			),
			array(
				'name' => 'Address',
				'desc' => 'Put the address .',
				'id'   => $prefix.'address',
				'type' => 'text'
			),
			array(
				'name' => 'Phone',
				'desc' => 'Put the phone .',
				'id'   => $prefix.'phone',
				'type' => 'text'
			),
			array(
				'name' => 'Email',
				'desc' => 'Put the email .',
				'id'   => $prefix.'email',
				'type' => 'text'
			),
			array(
				'name' => 'Social enable or disable',
				'desc' => 'Social widget enable or disable .',
				'id'   => $prefix.'social',
				'std'  => 1,
				'type' => 'checkbox'
			),
			array(
				'name' => 'Facebook',
				'desc' => 'Put the facebook .',
				'id'   => $prefix.'facebook',
				'type' => 'text'
			),
			array(
				'name' => 'Twitter',
				'desc' => 'Put the twitter .',
				'id'   => $prefix.'twitter',
				'type' => 'text'
			),
			array(
				'name' => 'Youtube',
				'desc' => 'Put the youtube .',
				'id'   => $prefix.'youtube',
				'type' => 'text'
			),
			array(
				'name' => 'Linkedin',
				'desc' => 'Put the linkedin .',
				'id'   => $prefix.'linkedin',
				'type' => 'text'
			),
			array(
				'name' => 'Google plus',
				'desc' => 'Put the google plus .',
				'id'   => $prefix.'google_plus',
				'type' => 'text'
			),
			array(
				'name' => 'Instagram',
				'desc' => 'Put the instagram .',
				'id'   => $prefix.'instagram',
				'type' => 'text'
			),
			array(
				'name' => 'Dribbble',
				'desc' => 'Put the dribbble .',
				'id'   => $prefix.'dribbble',
				'type' => 'text'
			),
			array(
				'name' => 'Pinterest',
				'desc' => 'Put the pinterest .',
				'id'   => $prefix.'pinterest',
				'type' => 'text'
			),
			array(
				'name' => 'Rss enable or disable',
				'desc' => 'Rss widget enable or disable .',
				'id'   => $prefix.'rss',
				'std'  => 1,
				'type' => 'checkbox'
			),
		),
	);
	
	$meta_boxes[] = array(
		'id' => 'ask_me',
		'title' => 'Home Options',
		'pages' => array('page'),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array(
				'name' => 'Home top box enable or disable',
				'desc' => 'Home top box enable or disable .',
				'id'   => $prefix.'index_top_box',
				'std'  => 1,
				'type' => 'checkbox'
			),
			array(
				'name' => 'Remove the content ?',
				'desc' => 'Remove the content ( Title, content, buttons and ask question ) ?',
				'id'   => $prefix.'remove_index_content',
				'type' => 'checkbox'
			),
			array(
				'name'    => 'Custom Background On/Off',
				'desc'    => 'If it is switched off, black background will be used.',
				'id'      => $prefix.'index_top_box_background',
				'std'     => 'background',
				'type'    => 'radio',
				'class'   => 'radio',
				'options' => array("background" => "On","off" => "Off")
			),
			array(
				'name'		=> "Background color",
				'id'		=> $prefix."background_color_home",
				'type'		=> 'color',
			),
			array(
				'name'		=> 'Background',
				'id'		=> $prefix."background_img_home",
				'type'		=> 'upload',
			),
			array(
				'name'		=> "Background repeat",
				'id'		=> $prefix."background_repeat_home",
				'type'		=> 'select',
				'options'	=> array(
					'repeat'	=> 'repeat',
					'no-repeat'	=> 'no-repeat',
					'repeat-x'	=> 'repeat-x',
					'repeat-y'	=> 'repeat-y',
				),
			),
			array(
				'name'		=> "Background fixed",
				'id'		=> $prefix."background_fixed_home",
				'type'		=> 'select',
				'options'	=> array(
					'fixed'  => 'fixed',
					'scroll' => 'scroll',
				),
			),
			array(
				'name'		=> "Background position x",
				'id'		=> $prefix."background_position_x_home",
				'type'		=> 'select',
				'options'	=> array(
					'left'	 => 'left',
					'center' => 'center',
					'right'	 => 'right',
				),
			),
			array(
				'name'		=> "Background position y",
				'id'		=> $prefix."background_position_y_home",
				'type'		=> 'select',
				'options'	=> array(
					'top'	 => 'top',
					'center' => 'center',
					'bottom' => 'bottom',
				),
			),
			array(
				'name' => "Full Screen Background",
				'id'   => $prefix."background_full_home",
				'type' => 'checkbox',
				'std'  => 0,
			),
			array(
				'name' => 'Home top box title',
				'desc' => 'Put the Home top box title .',
				'id'   => $prefix.'index_title',
				'std'  => 'HAVE A QUESTION?',
				'type' => 'text'
			),
			array(
				'name' => 'Home top box content',
				'desc' => 'Put the Home top box content .',
				'id'   => $prefix.'index_content',
				'std'  => 'If you have any question you can ask below or enter what you are looking for!',
				'type' => 'textarea'
			),
			array(
				'name' => 'About Us title',
				'desc' => 'Put the About Us title .',
				'id'   => $prefix.'index_about',
				'std'  => '',
				'type' => 'text'
			),
			array(
				'name' => 'About Us link',
				'desc' => 'Put the About Us link .',
				'id'   => $prefix.'index_about_h',
				'std'  => '',
				'type' => 'text'
			),
			array(
				'name' => 'Join Now title',
				'desc' => 'Put the Join Now title .',
				'id'   => $prefix.'index_join',
				'std'  => '',
				'type' => 'text'
			),
			array(
				'name' => 'Join Now link',
				'desc' => 'Put the Join Now link .',
				'id'   => $prefix.'index_join_h',
				'std'  => '',
				'type' => 'text'
			),
			array(
				'name' => 'About Us title if login',
				'desc' => 'Put the About Us title if login .',
				'id'   => $prefix.'index_about_login',
				'std'  => '',
				'type' => 'text'
			),
			array(
				'name' => 'About Us link if login',
				'desc' => 'Put the About Us link if login .',
				'id'   => $prefix.'index_about_h_login',
				'std'  => '',
				'type' => 'text'
			),
			array(
				'name' => 'Ask question title if login',
				'desc' => 'Put the Ask question title if login .',
				'id'   => $prefix.'index_join_login',
				'std'  => '',
				'type' => 'text'
			),
			array(
				'name' => 'Ask question link if login',
				'desc' => 'Put the Ask question link if login .',
				'id'   => $prefix.'index_join_h_login',
				'std'  => '',
				'type' => 'text'
			),
			array(
				'name'		=> "Page style",
				'desc' => "This option works for (Home page) Template only",
				'id'		=> $prefix."index_tabs",
				'type'		=> 'select',
				'options'	=> array(
					1	=> "Tabs",
					2	=> 'Recent questions',
					3	=> 'Page content',
				),
			),
			array(
				'name' => 'Tabs pagination enable or disable',
				'desc' => 'This option works for (Home page) Template only',
				'id'   => $prefix.'pagination_tabs',
				'std'  => 1,
				'type' => 'checkbox'
			),
			array(
				'name'	  => 'Choose your tabs',
				'desc' => "This option works for (Home page) Template only",
				'id'	  => $prefix.'what_tab',
				'options' => array("recent_questions" => "Recent Questions","most_responses" => "Most Responses / answers","recently_answered" => "Recently Answered","no_answers" => "No answers","most_visit" => "Most Visit","most_vote" => "Most Vote","recent_posts" => "Recent Posts"),
				'std'  => array("recent_questions","most_responses","recently_answered","no_answers"),
				'type'	  => 'checkbox_list'
			),
			array(
				'name' => 'Posts per page',
				'desc' => 'Put the Posts per page. This option works for (Home page) Template only',
				'id'   => $prefix.'posts_per_page',
				'std'  => '10',
				'type' => 'text'
			),
		),
	);
	
	$meta_boxes[] = array(
		'id' => 'post_page',
		'title' => 'Post and Page Options',
		'pages' => array('post','page','question','product'),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			
			
		
			array(
				'name'		=> 'Sidebar',
				'id'		=> $prefix."sidebar",
				'class'   => 'radio_no_margin',
				'type'		=> 'radio',
				'options'	=> array(
					'default'		=> '',
					'right'			=> '',
					'full'			=> '',
					'left'			=> '',
				),
				'std'		=> 'default',
			),
			array(
				'name'		=> 'Select your sidebar',
				'id'		=> $prefix.'what_sidebar',
				'type'		=> 'select',
				'options'	=> $new_sidebars,
			),
			
			array(
				'name'		=> 'Google map',
				'desc'		=> "Put your google map html",
				'id'		=> $prefix."google",
				'type'		=> 'textarea',
				'cols'		=> "40",
				'rows'		=> "8"
			),
			array(
				'name'		=> 'Slideshow ?',
				'id'		=> $prefix.'slideshow_type',
				'type'		=> 'select',
				'options'	=> array(
					'custom_slide' => "Custom Slideshow",
					'upload_images' => "Upload your images",
				),
				'std'		=> array('custom_slide'),
			),
			array(
				'id'		=> $prefix.'slideshow_post',
				'type'		=> 'note',
			),
			array(
				'name'	=> 'Upload your images',
				'id'	=> $prefix."upload_images",
				'type'	=> 'image_advanced',
			),
			array(
				'name'		=> 'Video type',
				'id'		=> $prefix.'video_post_type',
				'type'		=> 'select',
				'options'	=> array(
					'youtube' => "Youtube",
					'vimeo' => "Vimeo",
					'daily' => "Dialymotion",
					'html5' => "HTML 5",
				),
				'std'		=> array('youtube'),
				'desc'		=> 'Choose from here the video type'
			),
			array(
				'name'		=> 'Video ID',
				'id'		=> $prefix.'video_post_id',
				'desc'		=> 'Put here the video id : http://www.youtube.com/watch?v=sdUUx5FdySs EX : "sdUUx5FdySs"',
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'name' => 'Video Image',
				'desc' => 'Upload a image, or enter URL to an image if it is already uploaded. ',
				'id'   => $prefix.'video_image',
				'std'  => '',
				'type' => 'upload'
			),
			array(
				'name'		=> 'Mp4 video',
				'id'		=> $prefix.'video_mp4',
				'desc'		=> 'Put here the mp4 video',
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'name'		=> 'M4v video',
				'id'		=> $prefix.'video_m4v',
				'desc'		=> 'Put here the m4v video',
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'name'		=> 'Webm video',
				'id'		=> $prefix.'video_webm',
				'desc'		=> 'Put here the webm video',
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'name'		=> 'Ogv video',
				'id'		=> $prefix.'video_ogv',
				'desc'		=> 'Put here the ogv video',
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'name'		=> 'Wmv video',
				'id'		=> $prefix.'video_wmv',
				'desc'		=> 'Put here the wmv video',
				'type'		=> 'text',
				'std'		=> ''
			),
			array(
				'name'		=> 'Flv video',
				'id'		=> $prefix.'video_flv',
				'desc'		=> 'Put here the flv video',
				'type'		=> 'text',
				'std'		=> ''
			),
		),
	);
	
	$meta_boxes[] = array(
		'id' => 'single_page',
		'title' => 'Single Pages Options',
		'pages' => array('post','page','question','product'),
		'context' => 'side',
		'priority' => 'default',
		'fields' => array(
			array(
				'name' => 'Choose a custom page setting',
				'desc' => 'Choose a custom page setting .',
				'id'   => $prefix.'custom_page_setting',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Sticky sidebar enable or disable',
				'desc' => 'Sticky sidebar enable or disable .',
				'id'   => $prefix.'sticky_sidebar_s',
				'std'  => 'on',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Post meta enable or disable',
				'desc' => 'Post meta enable or disable .',
				'id'   => $prefix.'post_meta_s',
				'std'  => 'on',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Share enable or disable',
				'desc' => 'Share enable or disable .',
				'id'   => $prefix.'post_share_s',
				'std'  => 0,
				'type' => 'checkbox'
			),
			array(
				'name' => 'Author info box enable or disable',
				'desc' => 'Author info box enable or disable .',
				'id'   =>  $prefix.'post_author_box_s',
				'std'  => 0,
				'type' => 'checkbox'
			),
			array(
				'name' => 'Related post enable or disable',
				'desc' => 'Related post enable or disable .',
				'id'   =>  $prefix.'related_post_s',
				'std'  => 0,
				'type' => 'checkbox'
			),
			array(
				'name' => 'Comments enable or disable',
				'desc' => 'Comments enable or disable .',
				'id'   =>  $prefix.'post_comments_s',
				'std'  => 'on',
				'type' => 'checkbox'
			),
			array(
				'name' => 'Navigation post enable or disable',
				'desc' => 'Navigation post ( next and previous posts) enable or disable .',
				'id'   =>  $prefix.'post_navigation_s',
				'std'  => 'on',
				'type' => 'checkbox'
			),
		),
	);
	
	$meta_boxes[] = array(
		'id' => 'advertising',
		'title' => 'Advertising Options',
		'pages' => array('post','page','question','product'),
		'context' => 'normal',
		'priority' => 'high',
		'fields' => array(
			array(
				'name'  => "Advertising after header",
				'id'    => $prefix.'header_adv_n',
				'type'  => 'heading'
			),
			array(
				'name'    => 'Advertising type',
				'desc'    => 'Advertising type .',
				'id'      => $prefix.'header_adv_type',
				'std'     => 'custom_image',
				'type'    => 'radio',
				'class'   => 'radio',
				'options' => array("display_code" => "Display code","custom_image" => "Custom Image")
			),
			array(
				'name' => 'Image URL',
				'desc' => 'Upload a image, or enter URL to an image if it is already uploaded. ',
				'id'   => $prefix.'header_adv_img',
				'std'  => '',
				'type' => 'upload'
			),
			array(
				'name' => 'Advertising url',
				'desc' => 'Advertising url. ',
				'id'   => $prefix.'header_adv_href',
				'std'  => '#',
				'type' => 'text'
			),
			array(
				'name' => "Advertising Code html ( Ex: Google ads)",
				'desc' => "Advertising Code html ( Ex: Google ads)",
				'id'   => $prefix.'header_adv_code',
				'std'  => '',
				'type' => 'textarea'
			),
			array(
				'name'  => "Advertising 1 in post and question",
				'id'    => $prefix.'share_adv_n',
				'type'  => 'heading'
			),
			array(
				'name' => 'Advertising type',
				'desc' => 'Advertising type .',
				'id'   => $prefix.'share_adv_type',
				'std'  => 'custom_image',
				'type' => 'radio',
				'class'   => 'radio',
				'options' => array("display_code" => "Display code","custom_image" => "Custom Image")
			),
			array(
				'name' => 'Image URL',
				'desc' => 'Upload a image, or enter URL to an image if it is already uploaded. ',
				'id'   => $prefix.'share_adv_img',
				'std'  => '',
				'type' => 'upload'
			),
			array(
				'name' => 'Advertising url',
				'desc' => 'Advertising url. ',
				'id'   => $prefix.'share_adv_href',
				'std'  => '#',
				'type' => 'text'
			),
			array(
				'name' => "Advertising Code html ( Ex: Google ads)",
				'desc' => "Advertising Code html ( Ex: Google ads)",
				'id'   => $prefix.'share_adv_code',
				'std'  => '',
				'type' => 'textarea'
			),
			array(
				'name'  => "Advertising 2 in post and question",
				'id'    => $prefix.'related_adv_n',
				'type'  => 'heading'
			),
			array(
				'name' => 'Advertising type',
				'desc' => 'Advertising type .',
				'id'   => $prefix.'related_adv_type',
				'std'  => 'custom_image',
				'type' => 'radio',
				'class'   => 'radio',
				'options' => array("display_code" => "Display code","custom_image" => "Custom Image")
			),
			array(
				'name' => 'Image URL',
				'desc' => 'Upload a image, or enter URL to an image if it is already uploaded. ',
				'id'   => $prefix.'related_adv_img',
				'std'  => '',
				'type' => 'upload'
			),
			array(
				'name' => 'Advertising url',
				'desc' => 'Advertising url. ',
				'id'   => $prefix.'related_adv_href',
				'std'  => '#',
				'type' => 'text'
			),
			array(
				'name' => "Advertising Code html ( Ex: Google ads)",
				'desc' => "Advertising Code html ( Ex: Google ads)",
				'id'   => $prefix.'related_adv_code',
				'std'  => '',
				'type' => 'textarea'
			),
			array(
				'name'  => "Advertising after content",
				'id'    => $prefix.'content_adv_n',
				'type'  => 'heading'
			),
			array(
				'name'    => 'Advertising type',
				'desc'    => 'Advertising type .',
				'id'      => $prefix.'content_adv_type',
				'std'     => 'custom_image',
				'type'    => 'radio',
				'class'   => 'radio',
				'options' => array("display_code" => "Display code","custom_image" => "Custom Image")
			),
			array(
				'name' => 'Image URL',
				'desc' => 'Upload a image, or enter URL to an image if it is already uploaded. ',
				'id'   => $prefix.'content_adv_img',
				'std'  => '',
				'type' => 'upload'
			),
			array(
				'name' => 'Advertising url',
				'desc' => 'Advertising url. ',
				'id'   => $prefix.'content_adv_href',
				'std'  => '#',
				'type' => 'text'
			),
			array(
				'name' => "Advertising Code html ( Ex: Google ads)",
				'desc' => "Advertising Code html ( Ex: Google ads)",
				'id'   => $prefix.'content_adv_code',
				'std'  => '',
				'type' => 'textarea'
			)
		),
	);
	
	foreach ( $meta_boxes as $meta_box ) {
		new RW_Meta_Box( $meta_box );
	}
}
?>