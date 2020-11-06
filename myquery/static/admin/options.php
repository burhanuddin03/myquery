<?php
/**
 * Defines an array of options that will be used to generate the settings page and be saved in the database.
 * When creating the 'id' fields, make sure to use all lowercase and no spaces.
 *
 * If you are making your theme translatable, you should replace 'options_framework_theme'
 * with the actual text domain for your theme.  Read more:
 * http://codex.WordPress.org/Function_Reference/load_theme_textdomain
 */

/* Save default options */
$options_framework_admin = new Options_Framework_Admin;
$default_options = $options_framework_admin->get_default_values();
if (!get_option(INFOCENTER_OPTIONS)) {
	add_option(INFOCENTER_OPTIONS,$default_options);
}
function optionsframework_options() {

	// Test data
	$test_array = array(
		'one' => 'One',
		'two' => 'Two',
		'three' => 'Three',
		'four' => 'Four',
		'five' => 'Five'
	);

	// Multicheck Array
	$multicheck_array = array(
		'one' => 'French Toast',
		'two' => 'Pancake',
		'three' => 'Omelette',
		'four' => 'Crepe',
		'five' => 'Waffle'
	);

	// Multicheck Defaults
	$multicheck_defaults = array(
		'one' => '1',
		'five' => '1'
	);

	// Background Defaults
	$background_defaults = array(
		'color' => '',
		'image' => '',
		'repeat' => 'repeat',
		'position' => 'top center',
		'attachment'=>'scroll' );

	// Typography Defaults
	$typography_defaults = array(
		'size' => '15px',
		'face' => 'georgia',
		'style' => 'bold',
		'color' => '#bada55' );

	// Typography Options
	$typography_options = array(
		'sizes' => array( '6','12','14','16','20' ),
		'faces' => array( 'Helvetica Neue' => 'Helvetica Neue','Arial' => 'Arial' ),
		'styles' => array( 'normal' => 'Normal','bold' => 'Bold' ),
		'color' => false
	);

	// Pull all the categories into an array
	$options_categories = array();
	$args = array(
		'type'                     => 'post',
		'child_of'                 => 0,
		'parent'                   => '',
		'orderby'                  => 'name',
		'order'                    => 'ASC',
		'hide_empty'               => 0,
		'hierarchical'             => 1,
		'exclude'                  => '',
		'include'                  => '',
		'number'                   => '',
		'taxonomy'                 => 'category',
		'pad_counts'               => false );
	$options_categories_obj = get_categories($args);
	foreach ($options_categories_obj as $category) {
		$options_categories[$category->cat_ID] = $category->cat_name;
	}
	
	// Pull all the question category into an array
	$options_categories_q = array();
	$args = array(
		'type'                     => 'question',
		'child_of'                 => 0,
		'parent'                   => '',
		'orderby'                  => 'name',
		'order'                    => 'ASC',
		'hide_empty'               => 0,
		'hierarchical'             => 1,
		'exclude'                  => '',
		'include'                  => '',
		'number'                   => '',
		'taxonomy'                 => 'question-category',
		'pad_counts'               => false );
	
	$options_categories_obj_q = get_categories($args);
	$options_categories_q = array();
	foreach ($options_categories_obj_q as $category_q) {
		$options_categories_q[$category_q->term_id] = $category_q->name;
	}
	
	// Pull all the groups into an array
	$options_groups = array();
	global $wp_roles;
	$options_groups_obj = $wp_roles->roles;
	foreach ($options_groups_obj as $key_r => $value_r) {
		$options_groups[$key_r] = $value_r['name'];
	}
	
	// Pull all tags into an array
	$options_tags = array();
	$options_tags_obj = get_tags();
	foreach ( $options_tags_obj as $tag ) {
		$options_tags[$tag->term_id] = $tag->name;
	}

	// Pull all the pages into an array
	$options_pages = array();
	$options_pages_obj = get_pages('sort_column=post_parent,menu_order');
	$options_pages[''] = 'Select a page:';
	foreach ($options_pages_obj as $page) {
		$options_pages[$page->ID] = $page->post_title;
	}
	
	// Pull all the sidebars into an array
	$sidebars = get_option('sidebars');
	$new_sidebars = array('default'=> 'Default');
	foreach ($GLOBALS['wp_registered_sidebars'] as $sidebar) {
		$new_sidebars[$sidebar['id']] = $sidebar['name'];
	}
	
	// Pull all the roles into an array
	global $wp_roles;
	$new_roles = array();
	foreach ($wp_roles->roles as $key => $value) {
		$new_roles[$key] = $value['name'];
	}
	
	$export = array(INFOCENTER_OPTIONS,"sidebars","badges","roles");
	$current_options = array();
	foreach ($export as $options) {
		if (get_option($options)) {
			$current_options[$options] = get_option($options);
		}else {
			$current_options[$options] = array();
		}
	}
	$current_options_e = $current_options;
	
	// If using image radio buttons, define a directory path
	$imagepath =  get_template_directory_uri() . '/admin/images/';
	
	$options = array();
	
	$options[] = array(
		'name' => 'General Settings',
		'type' => 'heading');
	
	
	
	$options[] = array(
		'name' => 'Enable loader',
		'desc' => 'Select ON to enable loader.',
		'id' => 'loader',
		'std' => 1,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Enable Live Search',
		'desc' => 'Select ON to enable live search in your homepage.',
		'id' => 'livesearch',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Enable nicescroll',
		'desc' => 'Select ON to enable nicescroll.',
		'id' => 'nicescroll',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => "Custom logo for email template",
		'desc' => "Upload your custom logo for email template",
		'id' => 'logo_email_template',
		'type' => 'upload');
	
	$options[] = array(
		'name' => "Add your email for email template",
		'desc' => "Add your email for email template",
		'id' => 'email_template',
		'std' => get_bloginfo("admin_email"),
		'type' => 'text');
		
	$options[] = array(
		'name' => "Header code",
		'desc' => "Past your Google analytics code in the box",
		'id' => 'head_code',
		'std' => '',
		'type' => 'textarea');

	$options[] = array(
		'name' => "Footer code",
		'desc' => "Paste footer code in the box",
		'id' => 'footer_code',
		'std' => '',
		'type' => 'textarea');

	$options[] = array(
		'name' => "Custom CSS code",
		'desc' => "Advanced CSS options , Paste your CSS code in the box",
		'id' => 'custom_css',
		'std' => '',
		'type' => 'textarea');
	
	$options[] = array(
		'name' => 'Enable SEO options',
		'desc' => 'Select ON to enable SEO options.',
		'id' => 'seo_active',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => "SEO keywords",
		'desc' => "Paste your keywords in the box",
		'id' => 'the_keywords',
		'std' => '',
		'type' => 'textarea');
	
	$options[] = array(
		'name' => "FaceBook share image",
		'desc' => "This is the FaceBook share image",
		'id' => 'fb_share_image',
		'type' => 'upload');
	
	
	
	
	

	
	$options[] = array(
		'name' => "Custom favicon",
		'desc' => "Upload the site’s favicon here , You can create new favicon here favicon.cc",
		'id' => 'favicon',
		'type' => 'upload');
	
	$options[] = array(
		'name' => "Custom favicon for iPhone",
		'desc' => "Upload your custom iPhone favicon",
		'id' => 'iphone_icon',
		'type' => 'upload');
	
	$options[] = array(
		'name' => "Custom iPhone retina favicon",
		'desc' => "Upload your custom iPhone retina favicon",
		'id' => 'iphone_icon_retina',
		'type' => 'upload');
	
	$options[] = array(
		'name' => "Custom favicon for iPad",
		'desc' => "Upload your custom iPad favicon",
		'id' => 'ipad_icon',
		'type' => 'upload');
	
	$options[] = array(
		'name' => "Custom iPad retina favicon",
		'desc' => "Upload your custom iPad retina favicon",
		'id' => 'ipad_icon_retina',
		'type' => 'upload');
	
	$options[] = array(
		'name' => 'Header Settings',
		'type' => 'heading');

	
	if ( class_exists( 'woocommerce' ) ) {
		$options[] = array(
			'name' => 'Header cart Settings',
			'desc' => 'Select ON to enable the cart in the header.',
			'id' => 'header_cart',
			'std' => 'off',
			'type' => 'checkbox');
	}
	
	$options[] = array(
		'name' => 'Logo display',
		'desc' => 'choose Logo display.',
		'id' => 'logo_display',
		'std' => 'display_title',
		'type' => 'radio',
		'options' => array("display_title" => "Display site title","custom_image" => "Custom Image"));
	
	$options[] = array(
		'name' => 'Logo upload',
		'desc' => 'Upload your custom logo. ',
		'id' => 'logo_img',
		'std' => '',
		'type' => 'upload');
	
	$options[] = array(
		'name' => 'Logo retina upload',
		'desc' => 'Upload your custom logo retina. ',
		'id' => 'retina_logo',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => 'On/Off Top Bar',
		'desc' => 'The top bar will be invisible if you switch it off. The below options will not work if Top bar is switched off.',
		'id' => 'top_bar_enabledisable',
		'std' => 1,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'On/Off Menu Style 2',
		'desc' => 'Main Menu will have / between the menu items.',
		'id' => 'menu_slash_enable',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'On/Off Custom User Logged In Menu',
		'desc' => 'Select ON to Custom Logged in user Menu at the top right corner of top bar. If it is switched off, a default user menu will be used.',
		'id' => 'top_bar_login_custom',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Label of Main Menu Post Question Button',
		'desc' => '',
		'id' => 'pq_label',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'URL of Main Menu Post Question Button',
		'desc' => 'You can put #ask-question in the field.',
		'id' => 'pq_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Display Post Question Button for Logged In users only',
		'desc' => '',
		'id' => 'pq_for_loggedin_users',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Label of Topbar Left Side First Menu',
		'desc' => '',
		'id' => 'headertopbar_left1',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Label of Topbar Left Side First Menu URL',
		'desc' => '',
		'id' => 'headertopbar_left1_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Label of Topbar Left Side 2nd Menu',
		'desc' => '',
		'id' => 'headertopbar_left2',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Label of Topbar Left Side 2nd Menu URL',
		'desc' => '',
		'id' => 'headertopbar_left2_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Label of Topbar Left Side 3rd Menu',
		'desc' => '',
		'id' => 'headertopbar_left3',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Label of Topbar Left Side 3rd Menu URL',
		'desc' => '',
		'id' => 'headertopbar_left3_url',
		'std' => '',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Breadcrumbs Settings',
		'desc' => 'Select ON to enable breadcrumbs.',
		'id' => 'breadcrumbs',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Home Template Page',
		'type' => 'heading');
	
	$options[] = array(
		'name' => 'Note: The following options work in the Home Page  - Style 1 template only. You choose (Home Page  - Style 1 template) as Front page from Appearance -> Reading settings.',
		'class' => 'home_page_display',
		'type' => 'info');
		
	$options[] = array(
		'name' => 'Home Stats Settings',
		'desc' => 'Select ON if you want to enable the statistics section in home page',
		'id' => 'hometemplate_stats_section',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Stats Background Parallax Image',
		'desc' => 'Upload Stats Background Parallax Image',
		'id' => 'hometemplate_stat_bg',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '1st Stats Title',
		'desc' => 'Put the title',
		'id' => 'hometemplate_stat1_title',
		'std' => 'Happy Clients',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Stats Number',
		'desc' => 'Put the number (example: 126)',
		'id' => 'hometemplate_stat1_number',
		'std' => '126',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Stats Icon',
		'desc' => 'Put the et-line-fonts icon name. Here is the full list of icon names: http://rhythmwp.wpengine.com/et-line-icons/',
		'id' => 'hometemplate_stat1_icon',
		'std' => 'icon-trophy',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Stats Title',
		'desc' => 'Put the title',
		'id' => 'hometemplate_stat2_title',
		'std' => 'Happy Clients',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Stats Number',
		'desc' => 'Put the number (example: 226)',
		'id' => 'hometemplate_stat2_number',
		'std' => '226',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Stats Icon',
		'desc' => 'Put the et-line-fonts icon name. Here is the full list of icon names: http://rhythmwp.wpengine.com/et-line-icons/',
		'id' => 'hometemplate_stat2_icon',
		'std' => 'icon-trophy',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Stats Title',
		'desc' => 'Put the title',
		'id' => 'hometemplate_stat3_title',
		'std' => 'Happy Clients',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Stats Number',
		'desc' => 'Put the number (example: 336)',
		'id' => 'hometemplate_stat3_number',
		'std' => '336',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Stats Icon',
		'desc' => 'Put the et-line-fonts icon name. Here is the full list of icon names: http://rhythmwp.wpengine.com/et-line-icons/',
		'id' => 'hometemplate_stat3_icon',
		'std' => 'icon-trophy',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th Stats Title',
		'desc' => 'Put the title',
		'id' => 'hometemplate_stat4_title',
		'std' => 'Happy Clients',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th Stats Number',
		'desc' => 'Put the number (example: 446)',
		'id' => 'hometemplate_stat4_number',
		'std' => '446',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th Stats Icon',
		'desc' => 'Put the et-line-fonts icon name. Here is the full list of icon names: http://rhythmwp.wpengine.com/et-line-icons/',
		'id' => 'hometemplate_stat4_icon',
		'std' => 'icon-trophy',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Home Blog Section Settings',
		'desc' => 'Select ON if you want to enable the Blog Section in home page',
		'id' => 'hometemplate_blog_section',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Blog Section Heading',
		'id' => 'hometemplate_blog_heading',
		'std' => 'Latest Articles',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Blog Section Sub-Heading',
		'id' => 'hometemplate_blog_subheading',
		'std' => 'This is sample sub heading text of the blog section of Home Page  - Style 1 template',
		'type' => 'textarea');
		
	$options[] = array(
		'name' => 'Number of Posts to Show in Blog Section',
		'id' => 'hometemplate_blog_number',
		'std' => 3,
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Blog Posts Category',
		'id' => 'hometemplate_posts_cat',
		'std' => 'uncategorized',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Blog Posts Order',
		'desc' => 'Put DESC or ASC for Descending or Ascending order.',
		'id' => 'hometemplate_posts_order',
		'std' => 'DESC',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Blog Meta On/Off',
		'desc' => 'Select ON if you want to enable blog meta',
		'id' => 'hometemplate_posts_meta',
		'std' => 1,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'View All Posts Button Label',
		'desc' => 'Put view all posts button text here if you want to show a button at the bottom of the blog posts',
		'id' => 'hometemplate_blog_button_label',
		'std' => 'View All Blog Posts',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'View All Posts Button URL',
		'desc' => 'Put the URL of View All Posts Button',
		'id' => 'hometemplate_blog_button_url',
		'std' => '#',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Home Testimonials Settings',
		'desc' => 'Select ON if you want to enable the Testimonials section in home page',
		'id' => 'hometemplate_testimonial_section',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Testimonials Background Parallax Image',
		'desc' => 'Upload Testimonials Background Parallax Image',
		'id' => 'hometemplate_testimonial_bg',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '1st User/Client Name',
		'desc' => 'Put the Name',
		'id' => 'hometemplate_testi1_name',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st User/Client Image',
		'desc' => 'Recommended image size: 68x68',
		'id' => 'hometemplate_testi1_img',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '1st User/Client Says',
		'desc' => 'Put the text what 1st client says',
		'id' => 'hometemplate_testi1_text',
		'std' => '',
		'type' => 'textarea');
		
	$options[] = array(
		'name' => '2nd User/Client Name',
		'desc' => 'Put the Name',
		'id' => 'hometemplate_testi2_name',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd User/Client Image',
		'desc' => 'Recommended image size: 68x68',
		'id' => 'hometemplate_testi2_img',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '2nd User/Client Says',
		'desc' => 'Put the text what 2nd client says',
		'id' => 'hometemplate_testi2_text',
		'std' => '',
		'type' => 'textarea');
		
	$options[] = array(
		'name' => '3rd User/Client Name',
		'desc' => 'Put the Name',
		'id' => 'hometemplate_testi3_name',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd User/Client Image',
		'desc' => 'Recommended image size: 68x68',
		'id' => 'hometemplate_testi3_img',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '3rd User/Client Says',
		'desc' => 'Put the text what 3rd client says',
		'id' => 'hometemplate_testi3_text',
		'std' => '',
		'type' => 'textarea');
		
	$options[] = array(
		'name' => '4th User/Client Name',
		'desc' => 'Put the Name',
		'id' => 'hometemplate_testi4_name',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th User/Client Image',
		'desc' => 'Recommended image size: 68x68',
		'id' => 'hometemplate_testi4_img',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '4th User/Client Says',
		'desc' => 'Put the text what 4th client says',
		'id' => 'hometemplate_testi4_text',
		'std' => '',
		'type' => 'textarea');
		
	$options[] = array(
		'name' => '5th User/Client Name',
		'desc' => 'Put the Name',
		'id' => 'hometemplate_testi5_name',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '5th User/Client Image',
		'desc' => 'Recommended image size: 68x68',
		'id' => 'hometemplate_testi5_img',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '5th User/Client Says',
		'desc' => 'Put the text what 5th client says',
		'id' => 'hometemplate_testi5_text',
		'std' => '',
		'type' => 'textarea');
		
	$options[] = array(
		'name' => 'Right Side Success Story Title',
		'desc' => 'Put Success Story Title',
		'id' => 'hometemplate_test_storytitle',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Right Side Success Story Text',
		'desc' => 'Put Success Story Text',
		'id' => 'hometemplate_test_storytext',
		'std' => '',
		'type' => 'textarea');
		
	$options[] = array(
		'name' => 'Use Image instead of Success Story',
		'desc' => 'Select ON if you want to Use Image instead of Success Story',
		'id' => 'hometemplate_test_story_image_switch',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Right Side Image',
		'desc' => 'The image will be visible if your switch on the above option',
		'id' => 'hometemplate_test_story_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => 'Home Clients Section',
		'desc' => 'Select ON if you want to enable the Clients section in home page',
		'id' => 'hometemplate_clients_section',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => '1st Client Image',
		'desc' => 'Upload 1st Client Image',
		'id' => 'hometemplate_clients1_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '1st Client URL',
		'desc' => 'Put the Hyperlink URL of 1st Client Image',
		'id' => 'hometemplate_clients1_url',
		'std' => '',
		'type' => 'text');
	
	$options[] = array(
		'name' => '2nd Client Image',
		'desc' => 'Upload 2nd Client Image',
		'id' => 'hometemplate_clients2_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '2nd Client URL',
		'desc' => 'Put the Hyperlink URL of 2nd Client Image',
		'id' => 'hometemplate_clients2_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Client Image',
		'desc' => 'Upload 3rd Client Image',
		'id' => 'hometemplate_clients3_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '3rd Client URL',
		'desc' => 'Put the Hyperlink URL of 3rd Client Image',
		'id' => 'hometemplate_clients3_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th Client Image',
		'desc' => 'Upload 4th Client Image',
		'id' => 'hometemplate_clients4_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '4th Client URL',
		'desc' => 'Put the Hyperlink URL of 4th Client Image',
		'id' => 'hometemplate_clients4_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '5th Client Image',
		'desc' => 'Upload 5th Client Image',
		'id' => 'hometemplate_clients5_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '5th Client URL',
		'desc' => 'Put the Hyperlink URL of 5th Client Image',
		'id' => 'hometemplate_clients5_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '6th Client Image',
		'desc' => 'Upload 6th Client Image',
		'id' => 'hometemplate_clients6_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '6th Client URL',
		'desc' => 'Put the Hyperlink URL of 6th Client Image',
		'id' => 'hometemplate_clients6_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Note: The following options work in the Home Page  - Style 1 template only. You can choose (Home Page  - Style 1 template) as Front page from Appearance -> Reading settings.',
		'class' => 'home_page_display',
		'type' => 'info');
		
	$options[] = array(
		'name' => 'Home Download App Section',
		'desc' => 'Select ON if you want to enable the Download App Section in home page',
		'id' => 'hometemplate_downloads_section',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Download App Section Background Parallax Image',
		'desc' => 'Upload Download App Section Background Parallax Image',
		'id' => 'hometemplate_downloads_bg',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => 'Download App Section Heading',
		'desc' => 'Put Section Heading',
		'id' => 'hometemplate_downloads_m_heading',
		'std' => 'Download Our Apps',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st App Heading - Line 1',
		'desc' => '',
		'id' => 'hometemplate_downloads_app1_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st App Heading - Line 2',
		'desc' => '',
		'id' => 'hometemplate_downloads_app1_sub_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st App Image',
		'desc' => 'Upload 1st App Image',
		'id' => 'hometemplate_downloads_app1_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '1st App URL',
		'desc' => 'Put the Hyperlink URL of 1st App',
		'id' => 'hometemplate_downloads_app1_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd App Heading - Line 1',
		'desc' => '',
		'id' => 'hometemplate_downloads_app2_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd App Heading - Line 2',
		'desc' => '',
		'id' => 'hometemplate_downloads_app2_sub_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd App Image',
		'desc' => 'Upload 2nd App Image',
		'id' => 'hometemplate_downloads_app2_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '2nd App URL',
		'desc' => 'Put the Hyperlink URL of 2nd App',
		'id' => 'hometemplate_downloads_app2_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd App Heading - Line 1',
		'desc' => '',
		'id' => 'hometemplate_downloads_app3_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd App Heading - Line 2',
		'desc' => '',
		'id' => 'hometemplate_downloads_app3_sub_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd App Image',
		'desc' => 'Upload 3rd App Image',
		'id' => 'hometemplate_downloads_app3_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '3rd App URL',
		'desc' => 'Put the Hyperlink URL of 3rd App',
		'id' => 'hometemplate_downloads_app3_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Home Social Section',
		'desc' => 'Select ON if you want to enable the Social section in home page',
		'id' => 'hometemplate_social_section',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => '1st Social Box Color',
		'desc' => '',
		'id' => 'hometemplate_social1_bgcolor',
		'std' => '',
		'type' => 'color');
		
	$options[] = array(
		'name' => '1st Social Box URL',
		'desc' => 'Put the Hyperlink URL of 1st Social Box',
		'id' => 'hometemplate_social1_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Social Image',
		'desc' => 'Upload 1st Social Image',
		'id' => 'hometemplate_social1_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '1st Social Heading Text',
		'desc' => 'Put 1st Social Box Heading Text',
		'id' => 'hometemplate_social1_join',
		'std' => 'Join Us On',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Social Network Name',
		'desc' => 'Put 1st Social Network Name',
		'id' => 'hometemplate_social1_name',
		'std' => 'Facebook',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Social Box Color',
		'desc' => '',
		'id' => 'hometemplate_social2_bgcolor',
		'std' => '',
		'type' => 'color');
		
	$options[] = array(
		'name' => '2nd Social Box URL',
		'desc' => 'Put the Hyperlink URL of 2nd Social Box',
		'id' => 'hometemplate_social2_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Social Image',
		'desc' => 'Upload 2nd Social Image',
		'id' => 'hometemplate_social2_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '2nd Social Heading Text',
		'desc' => 'Put 2nd Social Box Heading Text',
		'id' => 'hometemplate_social2_join',
		'std' => 'Join Us On',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Social Network Name',
		'desc' => 'Put 2nd Social Network Name',
		'id' => 'hometemplate_social2_name',
		'std' => 'TWIITER',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Social Box Color',
		'desc' => '',
		'id' => 'hometemplate_social3_bgcolor',
		'std' => '',
		'type' => 'color');
		
	$options[] = array(
		'name' => '3rd Social Box URL',
		'desc' => 'Put the Hyperlink URL of 3rd Social Box',
		'id' => 'hometemplate_social3_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Social Image',
		'desc' => 'Upload 3rd Social Image',
		'id' => 'hometemplate_social3_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '3rd Social Heading Text',
		'desc' => 'Put 3rd Social Box Heading Text',
		'id' => 'hometemplate_social3_join',
		'std' => 'Join Us On',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Social Network Name',
		'desc' => 'Put 3rd Social Network Name',
		'id' => 'hometemplate_social3_name',
		'std' => 'GOOGLE PLUS',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th Social Box Color',
		'desc' => '',
		'id' => 'hometemplate_social4_bgcolor',
		'std' => '',
		'type' => 'color');
		
	$options[] = array(
		'name' => '4th Social Box URL',
		'desc' => 'Put the Hyperlink URL of 4th Social Box',
		'id' => 'hometemplate_social4_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th Social Image',
		'desc' => 'Upload 4th Social Image',
		'id' => 'hometemplate_social4_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '4th Social Heading Text',
		'desc' => 'Put 4th Social Box Heading Text',
		'id' => 'hometemplate_social4_join',
		'std' => 'Join Us On',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th Social Network Name',
		'desc' => 'Put 4th Social Network Name',
		'id' => 'hometemplate_social4_name',
		'std' => 'LINKEDIN',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Go to the page and add new page template <a href="post-new.php?post_type=page">from here</a> , choose the template page ( Home ) set it a static page <a href="options-reading.php">from here</a>.',
		'class' => 'home_page_display',
		'type' => 'info');
		
	$options[] = array(
		'name' => 'Home Page - Style 2 template',
		'type' => 'heading');
		
	$options[] = array(
		'name' => 'Note: The following options work in the Home Page - Style 2 template only. You can choose (Home Page - Style 2 template) as Front page from Appearance -> Reading settings.',
		'class' => 'home_page_display',
		'type' => 'info');
		
	$options[] = array(
		'name' => 'Home Page - Style 2; 3 Columns Quick Links Section',
		'desc' => 'Select ON if you want to enable the Quick Links Section in Home Page - Style 2 template',
		'id' => 'knowledgehome_quicklinks_section',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => '1st Column Heading',
		'desc' => '',
		'id' => 'knowledgehome_quicklinks_link1_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Column Text',
		'desc' => '',
		'id' => 'knowledgehome_quicklinks_link1_text',
		'std' => '',
		'type' => 'textarea');
		
	$options[] = array(
		'name' => '1st Column Icon',
		'desc' => 'Put the Font-Awesome icon name (example: fa fa-comments-o). Here is the full list of icon names: http://fontawesome.io/icons/',
		'id' => 'knowledgehome_quicklinks_link1_icon',
		'std' => 'fa fa-comments-o',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Column ReadMore Text',
		'desc' => 'Put the Read More Text of 1st Column',
		'id' => 'knowledgehome_quicklinks_link1_rmtext',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Column ReadMore URL',
		'desc' => 'Put the Read More URL of 1st Column',
		'id' => 'knowledgehome_quicklinks_link1_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Column Heading',
		'desc' => '',
		'id' => 'knowledgehome_quicklinks_link2_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Column Text',
		'desc' => '',
		'id' => 'knowledgehome_quicklinks_link2_text',
		'std' => '',
		'type' => 'textarea');
		
	$options[] = array(
		'name' => '2nd Column Icon',
		'desc' => 'Put the Font-Awesome icon name (example: fa fa-comments-o). Here is the full list of icon names: http://fontawesome.io/icons/',
		'id' => 'knowledgehome_quicklinks_link2_icon',
		'std' => 'fa fa-comments-o',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Column ReadMore Text',
		'desc' => 'Put the Read More Text of 2nd Column',
		'id' => 'knowledgehome_quicklinks_link2_rmtext',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Column ReadMore URL',
		'desc' => 'Put the Read More URL of 2nd Column',
		'id' => 'knowledgehome_quicklinks_link2_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Column Heading',
		'desc' => '',
		'id' => 'knowledgehome_quicklinks_link3_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Column Text',
		'desc' => '',
		'id' => 'knowledgehome_quicklinks_link3_text',
		'std' => '',
		'type' => 'textarea');
		
	$options[] = array(
		'name' => '3rd Column Icon',
		'desc' => 'Put the Font-Awesome icon name (example: fa fa-comments-o). Here is the full list of icon names: http://fontawesome.io/icons/',
		'id' => 'knowledgehome_quicklinks_link3_icon',
		'std' => 'fa fa-comments-o',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Column ReadMore Text',
		'desc' => 'Put the Read More Text of 3rd Column',
		'id' => 'knowledgehome_quicklinks_link3_rmtext',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Column ReadMore URL',
		'desc' => 'Put the Read More URL of 3rd Column',
		'id' => 'knowledgehome_quicklinks_link3_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'White Background of 3 Columns Quick Links Section',
		'desc' => 'Select ON if you want white background for this section.',
		'id' => 'knowledgehome_quicklinks_section_whitebg',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Home Page - Style 2 Article Categories',
		'desc' => 'Select ON if you want to enable the Article Categories Section in Home Page - Style 2 template',
		'id' => 'knowledgehome_category_section',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'White Background of 3 Article Categories Section',
		'desc' => 'Select ON if you want white background for this section.',
		'id' => 'knowledgehome_category_section_whitebg',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Section Heading',
		'desc' => 'Put Section Heading',
		'id' => 'knowledgehome_category_heading',
		'std' => 'Article Categories',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Section Sub Heading',
		'desc' => '',
		'id' => 'knowledgehome_category_sub_heading',
		'std' => 'Articles are nicely organized by category, just choose a category and find your desired post.',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'View All Articles Button Label',
		'desc' => '',
		'id' => 'knowledgehome_category_button_label',
		'std' => 'View All Articles',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'View All Articles Button URL',
		'desc' => 'Put your blog page url here. Keep this field empty if you do not want to show the button.',
		'id' => 'knowledgehome_category_button_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Number of Categories to Show',
		'desc' => 'Put (0) if you want to show all the categories.',
		'id' => 'knowledgehome_category_number',
		'std' => 0,
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Ascending or Descending Order of Categories',
		'desc' => 'Put ASC for Ascending order and put DESC for Descending order',
		'id' => 'knowledgehome_category_asc_desc',
		'std' => 'ASC',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Exclude Categories',
		'desc' => 'Input Category IDs (separated by comma) you want to exclude.',
		'id' => 'infocenter_hp_category_exclude',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Home Download App Section',
		'desc' => 'Select ON if you want to enable the Download App Section in home page',
		'id' => 'knowledgehome_downloads_section',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Download App Section Background Parallax Image',
		'desc' => 'Upload Download App Section Background Parallax Image',
		'id' => 'knowledgehome_downloads_bg',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => 'Download App Section Heading',
		'desc' => 'Put Section Heading',
		'id' => 'knowledgehome_downloads_m_heading',
		'std' => 'Download Our Apps',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st App Heading - Line 1',
		'desc' => '',
		'id' => 'knowledgehome_downloads_app1_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st App Heading - Line 2',
		'desc' => '',
		'id' => 'knowledgehome_downloads_app1_sub_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st App Image',
		'desc' => 'Upload 1st App Image',
		'id' => 'knowledgehome_downloads_app1_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '1st App URL',
		'desc' => 'Put the Hyperlink URL of 1st App',
		'id' => 'knowledgehome_downloads_app1_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd App Heading - Line 1',
		'desc' => '',
		'id' => 'knowledgehome_downloads_app2_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd App Heading - Line 2',
		'desc' => '',
		'id' => 'knowledgehome_downloads_app2_sub_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd App Image',
		'desc' => 'Upload 2nd App Image',
		'id' => 'knowledgehome_downloads_app2_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '2nd App URL',
		'desc' => 'Put the Hyperlink URL of 2nd App',
		'id' => 'knowledgehome_downloads_app2_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd App Heading - Line 1',
		'desc' => '',
		'id' => 'knowledgehome_downloads_app3_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd App Heading - Line 2',
		'desc' => '',
		'id' => 'knowledgehome_downloads_app3_sub_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd App Image',
		'desc' => 'Upload 3rd App Image',
		'id' => 'knowledgehome_downloads_app3_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '3rd App URL',
		'desc' => 'Put the Hyperlink URL of 3rd App',
		'id' => 'knowledgehome_downloads_app3_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Home Social Section',
		'desc' => 'Select ON if you want to enable the Social section in home page',
		'id' => 'knowledgehome_social_section',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => '1st Social Box Color',
		'desc' => '',
		'id' => 'knowledgehome_social1_bgcolor',
		'std' => '',
		'type' => 'color');
		
	$options[] = array(
		'name' => '1st Social Box URL',
		'desc' => 'Put the Hyperlink URL of 1st Social Box',
		'id' => 'knowledgehome_social1_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Social Image',
		'desc' => 'Upload 1st Social Image',
		'id' => 'knowledgehome_social1_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '1st Social Heading Text',
		'desc' => 'Put 1st Social Box Heading Text',
		'id' => 'knowledgehome_social1_join',
		'std' => 'Join Us On',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Social Network Name',
		'desc' => 'Put 1st Social Network Name',
		'id' => 'knowledgehome_social1_name',
		'std' => 'Facebook',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Social Box Color',
		'desc' => '',
		'id' => 'knowledgehome_social2_bgcolor',
		'std' => '',
		'type' => 'color');
		
	$options[] = array(
		'name' => '2nd Social Box URL',
		'desc' => 'Put the Hyperlink URL of 2nd Social Box',
		'id' => 'knowledgehome_social2_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Social Image',
		'desc' => 'Upload 2nd Social Image',
		'id' => 'knowledgehome_social2_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '2nd Social Heading Text',
		'desc' => 'Put 2nd Social Box Heading Text',
		'id' => 'knowledgehome_social2_join',
		'std' => 'Join Us On',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Social Network Name',
		'desc' => 'Put 2nd Social Network Name',
		'id' => 'knowledgehome_social2_name',
		'std' => 'TWIITER',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Social Box Color',
		'desc' => '',
		'id' => 'knowledgehome_social3_bgcolor',
		'std' => '',
		'type' => 'color');
		
	$options[] = array(
		'name' => '3rd Social Box URL',
		'desc' => 'Put the Hyperlink URL of 3rd Social Box',
		'id' => 'knowledgehome_social3_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Social Image',
		'desc' => 'Upload 3rd Social Image',
		'id' => 'knowledgehome_social3_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '3rd Social Heading Text',
		'desc' => 'Put 3rd Social Box Heading Text',
		'id' => 'knowledgehome_social3_join',
		'std' => 'Join Us On',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Social Network Name',
		'desc' => 'Put 3rd Social Network Name',
		'id' => 'knowledgehome_social3_name',
		'std' => 'GOOGLE PLUS',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th Social Box Color',
		'desc' => '',
		'id' => 'knowledgehome_social4_bgcolor',
		'std' => '',
		'type' => 'color');
		
	$options[] = array(
		'name' => '4th Social Box URL',
		'desc' => 'Put the Hyperlink URL of 4th Social Box',
		'id' => 'knowledgehome_social4_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th Social Image',
		'desc' => 'Upload 4th Social Image',
		'id' => 'knowledgehome_social4_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '4th Social Heading Text',
		'desc' => 'Put 4th Social Box Heading Text',
		'id' => 'knowledgehome_social4_join',
		'std' => 'Join Us On',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th Social Network Name',
		'desc' => 'Put 4th Social Network Name',
		'id' => 'knowledgehome_social4_name',
		'std' => 'LINKEDIN',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Home Page - Style 3 template',
		'type' => 'heading');
		
	$options[] = array(
		'name' => 'Note: The following options work in the Home Page - Style 3 template only. You can choose (Home Page - Style 3 template) as Front page from Appearance -> Reading settings.',
		'class' => 'home_page_display',
		'type' => 'info');
		
	$options[] = array(
		'name' => 'Home Page - Style 3; 3 Columns Quick Links Section',
		'desc' => 'Select ON if you want to enable the Quick Links Section in Home Page - Style 3 template',
		'id' => 'knowledgehome2_quicklinks_section',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => '1st Column Heading',
		'desc' => '',
		'id' => 'knowledgehome2_quicklinks_link1_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Column Text',
		'desc' => '',
		'id' => 'knowledgehome2_quicklinks_link1_text',
		'std' => '',
		'type' => 'textarea');
		
	$options[] = array(
		'name' => '1st Column Icon',
		'desc' => 'Put the Font-Awesome icon name (example: fa fa-comments-o). Here is the full list of icon names: http://fontawesome.io/icons/',
		'id' => 'knowledgehome2_quicklinks_link1_icon',
		'std' => 'fa fa-comments-o',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Column ReadMore Text',
		'desc' => 'Put the Read More Text of 1st Column',
		'id' => 'knowledgehome2_quicklinks_link1_rmtext',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Column ReadMore URL',
		'desc' => 'Put the Read More URL of 1st Column',
		'id' => 'knowledgehome2_quicklinks_link1_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Column Heading',
		'desc' => '',
		'id' => 'knowledgehome2_quicklinks_link2_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Column Text',
		'desc' => '',
		'id' => 'knowledgehome2_quicklinks_link2_text',
		'std' => '',
		'type' => 'textarea');
		
	$options[] = array(
		'name' => '2nd Column Icon',
		'desc' => 'Put the Font-Awesome icon name (example: fa fa-comments-o). Here is the full list of icon names: http://fontawesome.io/icons/',
		'id' => 'knowledgehome2_quicklinks_link2_icon',
		'std' => 'fa fa-comments-o',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Column ReadMore Text',
		'desc' => 'Put the Read More Text of 2nd Column',
		'id' => 'knowledgehome2_quicklinks_link2_rmtext',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Column ReadMore URL',
		'desc' => 'Put the Read More URL of 2nd Column',
		'id' => 'knowledgehome2_quicklinks_link2_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Column Heading',
		'desc' => '',
		'id' => 'knowledgehome2_quicklinks_link3_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Column Text',
		'desc' => '',
		'id' => 'knowledgehome2_quicklinks_link3_text',
		'std' => '',
		'type' => 'textarea');
		
	$options[] = array(
		'name' => '3rd Column Icon',
		'desc' => 'Put the Font-Awesome icon name (example: fa fa-comments-o). Here is the full list of icon names: http://fontawesome.io/icons/',
		'id' => 'knowledgehome2_quicklinks_link3_icon',
		'std' => 'fa fa-comments-o',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Column ReadMore Text',
		'desc' => 'Put the Read More Text of 3rd Column',
		'id' => 'knowledgehome2_quicklinks_link3_rmtext',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Column ReadMore URL',
		'desc' => 'Put the Read More URL of 3rd Column',
		'id' => 'knowledgehome2_quicklinks_link3_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'White Background of 3 Columns Quick Links Section',
		'desc' => 'Select ON if you want white background for this section.',
		'id' => 'knowledgehome2_quicklinks_section_whitebg',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Home Page - Style 3 Article Categories',
		'desc' => 'Select ON if you want to enable the Article Categories Section in Home Page - Style 3 template',
		'id' => 'knowledgehome2_category_section',
		'std' => 1,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Section Heading',
		'desc' => 'Put Section Heading',
		'id' => 'knowledgehome2_category_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Section Sub Heading',
		'desc' => '',
		'id' => 'knowledgehome2_category_sub_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'View All Articles Text',
		'desc' => 'By Default it will show View all Articles',
		'id' => 'knowledgehome2_category_button_label',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Number of Categories to Show',
		'desc' => 'Put (0) if you want to show all the categories.',
		'id' => 'knowledgehome2_category_number',
		'std' => 0,
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Ascending or Descending Order of Categories',
		'desc' => 'Put ASC for Ascending order and put DESC for Descending order',
		'id' => 'knowledgehome2_category_asc_desc',
		'std' => 'ASC',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Exclude Categories',
		'desc' => 'Input Category IDs (separated by comma) you want to exclude.',
		'id' => 'infocenter_h2_category_exclude',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'How Many Articles under Each Category?',
		'desc' => '',
		'id' => 'knowledgehome2_posts_per_page',
		'std' => 5,
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Ascending or Descending Ordered Articles under Each Category?',
		'desc' => 'Put ASC for Ascending order and put DESC for Descending order',
		'id' => 'knowledgehome2_articles_asc_desc',
		'std' => 'DESC',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'On/Off Sidebar in the Articles Category Section',
		'desc' => 'Select ON if you want to enable the Sidebar for Article Categories Section in Home Page - Style 3 template',
		'id' => 'knowledgehome2_sidebar_on_off',
		'std' => 1,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Home Download App Section',
		'desc' => 'Select ON if you want to enable the Download App Section in home page',
		'id' => 'knowledgehome2_downloads_section',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Download App Section Background Parallax Image',
		'desc' => 'Upload Download App Section Background Parallax Image',
		'id' => 'knowledgehome2_downloads_bg',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => 'Download App Section Heading',
		'desc' => 'Put Section Heading',
		'id' => 'knowledgehome2_downloads_m_heading',
		'std' => 'Download Our Apps',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st App Heading - Line 1',
		'desc' => '',
		'id' => 'knowledgehome2_downloads_app1_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st App Heading - Line 2',
		'desc' => '',
		'id' => 'knowledgehome2_downloads_app1_sub_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st App Image',
		'desc' => 'Upload 1st App Image',
		'id' => 'knowledgehome2_downloads_app1_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '1st App URL',
		'desc' => 'Put the Hyperlink URL of 1st App',
		'id' => 'knowledgehome2_downloads_app1_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd App Heading - Line 1',
		'desc' => '',
		'id' => 'knowledgehome2_downloads_app2_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd App Heading - Line 2',
		'desc' => '',
		'id' => 'knowledgehome2_downloads_app2_sub_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd App Image',
		'desc' => 'Upload 2nd App Image',
		'id' => 'knowledgehome2_downloads_app2_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '2nd App URL',
		'desc' => 'Put the Hyperlink URL of 2nd App',
		'id' => 'knowledgehome2_downloads_app2_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd App Heading - Line 1',
		'desc' => '',
		'id' => 'knowledgehome2_downloads_app3_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd App Heading - Line 2',
		'desc' => '',
		'id' => 'knowledgehome2_downloads_app3_sub_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd App Image',
		'desc' => 'Upload 3rd App Image',
		'id' => 'knowledgehome2_downloads_app3_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '3rd App URL',
		'desc' => 'Put the Hyperlink URL of 3rd App',
		'id' => 'knowledgehome2_downloads_app3_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Home Social Section',
		'desc' => 'Select ON if you want to enable the Social section in home page',
		'id' => 'knowledgehome2_social_section',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => '1st Social Box Color',
		'desc' => '',
		'id' => 'knowledgehome2_social1_bgcolor',
		'std' => '',
		'type' => 'color');
		
	$options[] = array(
		'name' => '1st Social Box URL',
		'desc' => 'Put the Hyperlink URL of 1st Social Box',
		'id' => 'knowledgehome2_social1_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Social Image',
		'desc' => 'Upload 1st Social Image',
		'id' => 'knowledgehome2_social1_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '1st Social Heading Text',
		'desc' => 'Put 1st Social Box Heading Text',
		'id' => 'knowledgehome2_social1_join',
		'std' => 'Join Us On',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Social Network Name',
		'desc' => 'Put 1st Social Network Name',
		'id' => 'knowledgehome2_social1_name',
		'std' => 'Facebook',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Social Box Color',
		'desc' => '',
		'id' => 'knowledgehome2_social2_bgcolor',
		'std' => '',
		'type' => 'color');
		
	$options[] = array(
		'name' => '2nd Social Box URL',
		'desc' => 'Put the Hyperlink URL of 2nd Social Box',
		'id' => 'knowledgehome2_social2_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Social Image',
		'desc' => 'Upload 2nd Social Image',
		'id' => 'knowledgehome2_social2_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '2nd Social Heading Text',
		'desc' => 'Put 2nd Social Box Heading Text',
		'id' => 'knowledgehome2_social2_join',
		'std' => 'Join Us On',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Social Network Name',
		'desc' => 'Put 2nd Social Network Name',
		'id' => 'knowledgehome2_social2_name',
		'std' => 'TWIITER',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Social Box Color',
		'desc' => '',
		'id' => 'knowledgehome2_social3_bgcolor',
		'std' => '',
		'type' => 'color');
		
	$options[] = array(
		'name' => '3rd Social Box URL',
		'desc' => 'Put the Hyperlink URL of 3rd Social Box',
		'id' => 'knowledgehome2_social3_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Social Image',
		'desc' => 'Upload 3rd Social Image',
		'id' => 'knowledgehome2_social3_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '3rd Social Heading Text',
		'desc' => 'Put 3rd Social Box Heading Text',
		'id' => 'knowledgehome2_social3_join',
		'std' => 'Join Us On',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Social Network Name',
		'desc' => 'Put 3rd Social Network Name',
		'id' => 'knowledgehome2_social3_name',
		'std' => 'GOOGLE PLUS',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th Social Box Color',
		'desc' => '',
		'id' => 'knowledgehome2_social4_bgcolor',
		'std' => '',
		'type' => 'color');
		
	$options[] = array(
		'name' => '4th Social Box URL',
		'desc' => 'Put the Hyperlink URL of 4th Social Box',
		'id' => 'knowledgehome2_social4_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th Social Image',
		'desc' => 'Upload 4th Social Image',
		'id' => 'knowledgehome2_social4_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '4th Social Heading Text',
		'desc' => 'Put 4th Social Box Heading Text',
		'id' => 'knowledgehome2_social4_join',
		'std' => 'Join Us On',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th Social Network Name',
		'desc' => 'Put 4th Social Network Name',
		'id' => 'knowledgehome2_social4_name',
		'std' => 'LINKEDIN',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Home Default Page',
		'type' => 'heading');
		
	$options[] = array(
		'name' => 'Note: The following options work in the default home page only (Not for the home template) and if you don\'t choose any page as Static Front page from Reading Settings.',
		'class' => 'home_page_display',
		'type' => 'info');
	
	$options[] = array(
		'name' => 'Home top box Settings',
		'desc' => 'Select ON if you want to enable the home top box.',
		'id' => 'index_top_box',
		'std' => 1,
		'type' => 'checkbox');
	

	
	$options[] = array(
		'name' => 'Remove the content ?',
		'desc' => 'Remove the content ( Title, content, buttons and ask question ) ?',
		'id'   => 'remove_index_content',
		'type' => 'checkbox'
	);
	
	$options[] = array(
		'name'    => 'Home top box background',
		'desc'    => 'Home top box background.',
		'id'      => 'index_top_box_background',
		'std'     => 'background',
		'type'    => 'hidden',
	);
	
	$options[] = array(
		'name' =>  "Background",
		'desc' => "Upload a image, or enter URL to an image if it is already uploaded.",
		'id' => 'background_home',
		'std' => $background_defaults,
		'type' => 'background' );
	
	$options[] = array(
		'name' => "Full Screen Background",
		'id'   => "background_full_home",
		'type' => 'checkbox',
		'std'  => 0,
	);
	
	$options[] = array(
		'name' => 'Home top box title',
		'desc' => 'Put the Home top box title.',
		'id' => 'index_title',
		'std' => 'HAVE A QUESTION?',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Home top box content',
		'desc' => 'Put the Home top box content.',
		'id' => 'index_content',
		'std' => 'If you have any question you can ask below or enter what you are looking for!',
		'type' => 'textarea');
	
	$options[] = array(
		'name' => 'How Works Template Page',
		'type' => 'heading');
		
	$options[] = array(
		'name' => 'How Works Illustration Image Section',
		'desc' => 'Select ON if you want to enable the Illustration Image Section Works page',
		'id' => 'howtemplate_howork_section',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => '1st Column Image',
		'desc' => '1st Column Image',
		'id' => 'howtemplate_howork1_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '1st Column Title',
		'desc' => 'Put 1st Column Title',
		'id' => 'howtemplate_howork1_heading',
		'std' => 'Create An Account',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Column URL',
		'desc' => '1st Column Hyperlink URL',
		'id' => 'howtemplate_howork1_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Column Image',
		'desc' => '2nd Column Image',
		'id' => 'howtemplate_howork2_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '2nd Column Title',
		'desc' => 'Put 2nd Column Title',
		'id' => 'howtemplate_howork2_heading',
		'std' => 'Post Your Question',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Column URL',
		'desc' => '2nd Column Hyperlink URL',
		'id' => 'howtemplate_howork2_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Column Image',
		'desc' => '3rd Column Image',
		'id' => 'howtemplate_howork3_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '3rd Column Title',
		'desc' => 'Put 3rd Column Title',
		'id' => 'howtemplate_howork3_heading',
		'std' => 'Find Your Solution',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Column URL',
		'desc' => '3rd Column Hyperlink URL',
		'id' => 'howtemplate_howork3_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'How Works Download App Section',
		'desc' => 'Select ON if you want to enable the Download App Section in How Works page',
		'id' => 'howtemplate_downloads_section',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Download App Section Background Parallax Image',
		'desc' => 'Upload Download App Section Background Parallax Image',
		'id' => 'howtemplate_downloads_bg',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => 'Download App Section Heading',
		'desc' => 'Put Section Heading',
		'id' => 'howtemplate_downloads_m_heading',
		'std' => 'Download Our Apps',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st App Heading - Line 1',
		'desc' => '',
		'id' => 'howtemplate_downloads_app1_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st App Heading - Line 2',
		'desc' => '',
		'id' => 'howtemplate_downloads_app1_sub_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st App Image',
		'desc' => 'Upload 1st App Image',
		'id' => 'howtemplate_downloads_app1_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '1st App URL',
		'desc' => 'Put the Hyperlink URL of 1st App',
		'id' => 'howtemplate_downloads_app1_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd App Heading - Line 1',
		'desc' => '',
		'id' => 'howtemplate_downloads_app2_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd App Heading - Line 2',
		'desc' => '',
		'id' => 'howtemplate_downloads_app2_sub_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd App Image',
		'desc' => 'Upload 2nd App Image',
		'id' => 'howtemplate_downloads_app2_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '2nd App URL',
		'desc' => 'Put the Hyperlink URL of 2nd App',
		'id' => 'howtemplate_downloads_app2_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd App Heading - Line 1',
		'desc' => '',
		'id' => 'howtemplate_downloads_app3_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd App Heading - Line 2',
		'desc' => '',
		'id' => 'howtemplate_downloads_app3_sub_heading',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd App Image',
		'desc' => 'Upload 3rd App Image',
		'id' => 'howtemplate_downloads_app3_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '3rd App URL',
		'desc' => 'Put the Hyperlink URL of 3rd App',
		'id' => 'howtemplate_downloads_app3_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'How Works Clients Section',
		'desc' => 'Select ON if you want to enable the Clients section in How Works page',
		'id' => 'howtemplate_clients_section',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => '1st Client Image',
		'desc' => 'Upload 1st Client Image',
		'id' => 'howtemplate_clients1_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '1st Client URL',
		'desc' => 'Put the Hyperlink URL of 1st Client Image',
		'id' => 'howtemplate_clients1_url',
		'std' => '',
		'type' => 'text');
	
	$options[] = array(
		'name' => '2nd Client Image',
		'desc' => 'Upload 2nd Client Image',
		'id' => 'howtemplate_clients2_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '2nd Client URL',
		'desc' => 'Put the Hyperlink URL of 2nd Client Image',
		'id' => 'howtemplate_clients2_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Client Image',
		'desc' => 'Upload 3rd Client Image',
		'id' => 'howtemplate_clients3_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '3rd Client URL',
		'desc' => 'Put the Hyperlink URL of 3rd Client Image',
		'id' => 'howtemplate_clients3_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th Client Image',
		'desc' => 'Upload 4th Client Image',
		'id' => 'howtemplate_clients4_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '4th Client URL',
		'desc' => 'Put the Hyperlink URL of 4th Client Image',
		'id' => 'howtemplate_clients4_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '5th Client Image',
		'desc' => 'Upload 5th Client Image',
		'id' => 'howtemplate_clients5_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '5th Client URL',
		'desc' => 'Put the Hyperlink URL of 5th Client Image',
		'id' => 'howtemplate_clients5_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '6th Client Image',
		'desc' => 'Upload 6th Client Image',
		'id' => 'howtemplate_clients6_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '6th Client URL',
		'desc' => 'Put the Hyperlink URL of 6th Client Image',
		'id' => 'howtemplate_clients6_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'How Works Social Section',
		'desc' => 'Select ON if you want to enable the Social section in How Works page',
		'id' => 'howtemplate_social_section',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => '1st Social Box Color',
		'desc' => '',
		'id' => 'howtemplate_social1_bgcolor',
		'std' => '',
		'type' => 'color');
		
	$options[] = array(
		'name' => '1st Social Box URL',
		'desc' => 'Put the Hyperlink URL of 1st Social Box',
		'id' => 'howtemplate_social1_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Social Image',
		'desc' => 'Upload 1st Social Image',
		'id' => 'howtemplate_social1_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '1st Social Heading Text',
		'desc' => 'Put 1st Social Box Heading Text',
		'id' => 'howtemplate_social1_join',
		'std' => 'Join Us On',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Social Network Name',
		'desc' => 'Put 1st Social Network Name',
		'id' => 'howtemplate_social1_name',
		'std' => 'Facebook',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Social Box Color',
		'desc' => '',
		'id' => 'howtemplate_social2_bgcolor',
		'std' => '',
		'type' => 'color');
		
	$options[] = array(
		'name' => '2nd Social Box URL',
		'desc' => 'Put the Hyperlink URL of 2nd Social Box',
		'id' => 'howtemplate_social2_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Social Image',
		'desc' => 'Upload 2nd Social Image',
		'id' => 'howtemplate_social2_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '2nd Social Heading Text',
		'desc' => 'Put 2nd Social Box Heading Text',
		'id' => 'howtemplate_social2_join',
		'std' => 'Join Us On',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Social Network Name',
		'desc' => 'Put 2nd Social Network Name',
		'id' => 'howtemplate_social2_name',
		'std' => 'TWIITER',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Social Box Color',
		'desc' => '',
		'id' => 'howtemplate_social3_bgcolor',
		'std' => '',
		'type' => 'color');
		
	$options[] = array(
		'name' => '3rd Social Box URL',
		'desc' => 'Put the Hyperlink URL of 3rd Social Box',
		'id' => 'howtemplate_social3_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Social Image',
		'desc' => 'Upload 3rd Social Image',
		'id' => 'howtemplate_social3_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '3rd Social Heading Text',
		'desc' => 'Put 3rd Social Box Heading Text',
		'id' => 'howtemplate_social3_join',
		'std' => 'Join Us On',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Social Network Name',
		'desc' => 'Put 3rd Social Network Name',
		'id' => 'howtemplate_social3_name',
		'std' => 'GOOGLE PLUS',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th Social Box Color',
		'desc' => '',
		'id' => 'howtemplate_social4_bgcolor',
		'std' => '',
		'type' => 'color');
		
	$options[] = array(
		'name' => '4th Social Box URL',
		'desc' => 'Put the Hyperlink URL of 4th Social Box',
		'id' => 'howtemplate_social4_url',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th Social Image',
		'desc' => 'Upload 4th Social Image',
		'id' => 'howtemplate_social4_image',
		'std' => '',
		'type' => 'upload');
		
	$options[] = array(
		'name' => '4th Social Heading Text',
		'desc' => 'Put 4th Social Box Heading Text',
		'id' => 'howtemplate_social4_join',
		'std' => 'Join Us On',
		'type' => 'text');
		
	$options[] = array(
		'name' => '4th Social Network Name',
		'desc' => 'Put 4th Social Network Name',
		'id' => 'howtemplate_social4_name',
		'std' => 'LINKEDIN',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'FAQ Template Page',
		'type' => 'heading');
		
	$options[] = array(
		'name' => 'Switch On/Off Bottom Quick Links',
		'desc' => 'If it is switched off, the following options are useless.',
		'id' => 'onoff_faq_qlinks',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => '1st Link URL',
		'id' => 'faq_qlinks1',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Link Text',
		'id' => 'faq_text1',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '1st Link Icon',
		'id' => 'faq_icon1',
		'std' => 'fa fa-home',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Link URL',
		'id' => 'faq_qlinks2',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Link Text',
		'id' => 'faq_text2',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '2nd Link Icon',
		'id' => 'faq_icon2',
		'std' => 'fa fa-folder-open',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Link URL',
		'id' => 'faq_qlinks3',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Link Text',
		'id' => 'faq_text3',
		'std' => '',
		'type' => 'text');
		
	$options[] = array(
		'name' => '3rd Link Icon',
		'id' => 'faq_icon3',
		'std' => 'fa fa-comments-o',
		'type' => 'text');
		
	$options[] = array(
		'name' => "One Click Page Import",
		'id' => "advanced",
		'type' => 'heading');
	
	$options[] = array(
		'name' => 'Google API ( Get it from here : https://developers.google.com/+/api/oauth )',
		'desc' => 'Type here the Google API. ',
		'id' => 'google_api',
		'class' => 'displaynone',
		'std' => '',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Facebook access token  ( Creat https://developers.facebook.com/apps & Get it from here : https://developers.facebook.com/tools/access_token )',
		'desc' => 'Facebook access token. ',
		'id' => 'facebook_access_token',
		'class' => 'displaynone',
		'std' => '',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Twitter consumer key',
		'desc' => 'Twitter consumer key. ',
		'id' => 'twitter_consumer_key',
		'class' => 'displaynone',
		'std' => '',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Twitter consumer secret',
		'desc' => 'Twitter consumer secret. ',
		'id' => 'twitter_consumer_secret',
		'class' => 'displaynone',
		'std' => '',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Twitter access token',
		'desc' => 'Twitter access token. ',
		'id' => 'twitter_access_token',
		'class' => 'displaynone',
		'std' => '',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Twitter access token secret',
		'desc' => 'Twitter access token secret. ',
		'id' => 'twitter_access_token_secret',
		'class' => 'displaynone',
		'std' => '',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Click ON to create all template pages',
		'desc' => 'Click ON and then clik on "Save Options" button to create all template pages automatically',
		'id' => 'theme_pages',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Questions',
		'type' => 'heading');
	
	$options[] = array(
		'name' => 'Question Page Title',
		'desc' => 'Add Question Page Title.',
		'id' => 'infocenter_questionpage_title',
		'std' => 'Questions',
		'type' => 'text');
		
	$options[] = array(
		'name' => 'Questions slug',
		'desc' => 'Add your questions slug.',
		'id' => 'questions_slug',
		'std' => 'questions',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Questions category slug',
		'desc' => 'Add your questions category slug.',
		'id' => 'category_questions_slug',
		'std' => 'question-category',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Questions tag slug',
		'desc' => 'Add your questions tag slug.',
		'id' => 'tag_questions_slug',
		'std' => 'question-tag',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Display Like/disLike in main page',
		'desc' => 'Display Like/disLike in main page enable or disable.',
		'id' => 'question_vote_show',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Active the author image in the questions loop',
		'desc' => 'If you put it OFF the author name will add in the meta.',
		'id' => 'question_author',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Click on to hide the excerpt in questions',
		'desc' => 'Click on to hide the excerpt in questions.',
		'id' => 'excerpt_questions',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Active the reports in site ?',
		'desc' => 'Active the reports enable or disable.',
		'id' => 'active_reports',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Active the vote in site ?',
		'desc' => 'Active the vote enable or disable.',
		'id' => 'active_vote',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Active the points system in site ?',
		'desc' => 'Active the points system enable or disable.',
		'id' => 'active_points',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Any one can ask question without register',
		'desc' => 'Any one can ask question without register enable or disable.',
		'id' => 'ask_question_no_register',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => "Add question page",
		'desc' => "Create a page using the Add question template and select it here",
		'id' => 'add_question',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => "Edit question page",
		'desc' => "Create a page using the Edit question template and select it here",
		'id' => 'edit_question',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => 'Details in ask question form is required',
		'desc' => 'Details in ask question form is required.',
		'id' => 'comment_question',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Editor enable or disable for details in add question form',
		'desc' => 'Editor enable or disable for details in add question form.',
		'id' => 'editor_question_details',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Tags enable or disable in add question form',
		'desc' => 'Select ON to enable the tags in add question form.',
		'id' => 'tags_question',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Poll enable or disable in add question form',
		'desc' => 'Select ON to enable the poll in add question form.',
		'id' => 'poll_question',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Attachment in add question form',
		'desc' => 'Select ON to enable the attachment in add question form.',
		'id' => 'attachment_question',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Attachment in a new answer form',
		'desc' => 'Select ON to enable the attachment in a new answer form.',
		'id' => 'attachment_answer',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Charge points for questions',
		'desc' => 'How many points should be taken from the user’s account for asking questions.',
		'id' => 'question_points',
		'std' => '5',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Charge points for questions Settings',
		'desc' => 'Select ON if you want to charge points from users for asking questions.',
		'id' => 'question_points_active',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Choose question status',
		'desc' => 'Choose question status after user publish the question.',
		'id' => 'question_publish',
		'options' => array("publish" => "Publish","draft" => "Draft"),
		'std' => 'publish',
		'type' => 'select');
	
	$options[] = array(
		'name' => 'Video description Settings',
		'desc' => 'Select ON if you want to let users to add video with their question.',
		'id' => 'video_desc_active',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'video description position',
		'desc' => 'Choose the video description position.',
		'id' => 'video_desc',
		'options' => array("before" => "Before content","after" => "After content"),
		'std' => 'after',
		'type' => 'select');
	
	$options[] = array(
		'name' => 'Send email for the user to notified a new question',
		'desc' => 'Send email enable or disable.',
		'id' => 'send_email_new_question',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Send email for custom groups to notified a new question',
		'desc' => 'Send email for custom groups to notified a new question.',
		'id' => 'send_email_question_groups',
		'type' => 'multicheck',
		'std' => array("editor" => 1,"administrator" => 1,"author" => 1,"contributor" => 1,"subscriber" => 1),
		'options' => $options_groups);
	
	$options[] = array(
		'name' => 'Active poll for user only ?',
		'desc' => 'Select ON if you want the poll allow to users only.',
		'id' => 'poll_user_only',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Select the question control style',
		'desc' => 'Select the question control style.',
		'id' => 'question_control_style',
		'std' => "style_2",
		'type' => 'select',
		'options' => array("style_1" => "Style 1","style_2" => "Style 2"));
	
	$options[] = array(
		'name' => 'Active user can edit the questions',
		'desc' => 'Select ON if you want the user can edit the questions.',
		'id' => 'question_edit',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Active user can delete the questions',
		'desc' => 'Select ON if you want the user can delete the questions.',
		'id' => 'question_delete',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Active user can follow the questions',
		'desc' => 'Select ON if you want the user can follow the questions.',
		'id' => 'question_follow',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Active close and open questions',
		'desc' => 'Select ON if you want active close and open questions.',
		'id' => 'question_close',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Active the question bump',
		'desc' => 'Select ON if you want the question bump.',
		'id' => 'question_bump',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Active the admin select or remove the best answer',
		'desc' => 'Select ON if you want the admin select or remove the best answer.',
		'id' => 'admin_best_answer',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Active the page terms ?',
		'desc' => 'Select ON if you want active the page terms.',
		'id' => 'terms_avtive',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Open the page in same page or a new page ?',
		'desc' => 'Open the page in same page or a new page.',
		'id' => 'terms_avtive_target',
		'std' => "new_page",
		'type' => 'select',
		'options' => array("same_page" => "Same page","new_page" => "New page"));
	
	$options[] = array(
		'name' => "Terms page",
		'desc' => "Select the terms page",
		'id' => 'terms_page',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => "Type the terms links if you don't like a page",
		'desc' => "Type the terms links if you don't like a page",
		'id' => 'terms_link',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Captcha Settings',
		'type' => 'heading');
	
	$options[] = array(
		'name' => 'Captcha enable or disable ( in ask question form )',
		'desc' => 'Captcha enable or disable ( in ask question form ).',
		'id' => 'the_captcha',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Captcha enable or disable ( in add post form )',
		'desc' => 'Captcha enable or disable ( in add post form ).',
		'id' => 'the_captcha_post',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Captcha enable or disable ( in register form )',
		'desc' => 'Captcha enable or disable ( in register form ).',
		'id' => 'the_captcha_register',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Captcha enable or disable ( in answer form )',
		'desc' => 'Captcha enable or disable ( in answer form ).',
		'id' => 'the_captcha_answer',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Captcha enable or disable ( in comment form )',
		'desc' => 'Captcha enable or disable ( in comment form ).',
		'id' => 'the_captcha_comment',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => "Captcha style",
		'desc' => "Choose the captcha style",
		'id' => 'captcha_style',
		'std' => 'question_answer',
		'type' => 'radio',
		'options' => 
			array(
				"question_answer" => "Question and answer",
				"normal_captcha" => "Normal captcha"
		)
	);
	
	$options[] = array(
		'name' => 'Captcha answer enable or disable in form ( in ask question form and register form )',
		'desc' => 'Captcha answer enable or disable.',
		'id' => 'show_captcha_answer',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => "Captcha question",
		'desc' => "put the Captcha question",
		'id' => 'captcha_question',
		'type' => 'text',
		'std' => "What is the capital of Egypt ?");
	
	$options[] = array(
		'name' => "Captcha answer",
		'desc' => "put the Captcha answer",
		'id' => 'captcha_answer',
		'type' => 'text',
		'std' => "Cairo");
	
	$options[] = array(
		'name' => 'User Settings',
		'type' => 'heading');
	
	$options[] = array(
		'name' => 'All the site for the register users only ?',
		'desc' => 'Click ON to active the site for the register users only.',
		'id' => 'site_users_only',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Send default message after register',
		'desc' => 'Send default message after register enable or disable.',
		'id' => 'send_default_message',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Select the user links',
		'desc' => 'Select the user links.',
		'id' => 'user_links',
		'type' => 'multicheck',
		'std' => array(
			"profile" => 1,
			"questions" => 1,
			"answers" => 1,
			"favorite" => 1,
			"points" => 1,
			"i_follow" => 1,
			"followers" => 1,
			"posts" => 1,
			"follow_questions" => 1,
			"follow_answers" => 1,
			"follow_posts" => 1,
			"follow_comments" => 1,
			"edit_profile" => 1,
			"logout" => 1,
		),
		'options' => array(
			"profile" => "Profile page",
			"questions" => "Questions",
			"answers" => "Answers",
			"favorite" => "Favorite Questions",
			"points" => "Points",
			"i_follow" => "Authors I Follow",
			"followers" => "Followers",
			"posts" => "Posts",
			"follow_questions" => "Follow Questions",
			"follow_answers" => "Follow Answers",
			"follow_posts" => "Follow Posts",
			"follow_comments" => "Follow Comments",
			"edit_profile" => "Edit Profile",
			"logout" => "Logout",
		));
	
	$options[] = array(
		'name' => 'Select the columns in the user admin',
		'desc' => 'Select the columns in the user admin.',
		'id' => 'user_meta_admin',
		'type' => 'multicheck',
		'std' => array(
			"phone" => 0,
			"country" => 0,
			"age" => 0,
		),
		'options' => array(
			"phone" => "Phone",
			"country" => "Country",
			"age" => "Age",
		));
	
	$options[] = array(
		'name' => "Login and register page",
		'desc' => "Select the Login and register page",
		'id' => 'login_register_page',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => "User edit profile page",
		'desc' => "Select the User edit profile page",
		'id' => 'user_edit_profile_page',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => "User post page",
		'desc' => "Select User post page",
		'id' => 'post_user_page',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => "User question page",
		'desc' => "Select User question page",
		'id' => 'question_user_page',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => "User answer page",
		'desc' => "Select User answer page",
		'id' => 'answer_user_page',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => "User favorite question page",
		'desc' => "Select User favorite question page",
		'id' => 'favorite_user_page',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => "User point page",
		'desc' => "Select User point page",
		'id' => 'point_user_page',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => "Authors I Follow page",
		'desc' => "Select Authors I Follow page",
		'id' => 'i_follow_user_page',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => "User Followers page",
		'desc' => "Select User Followers page",
		'id' => 'followers_user_page',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => "User follow question page",
		'desc' => "Select User follow question page",
		'id' => 'follow_question_page',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => "User follow answer page",
		'desc' => "Select User follow answer page",
		'id' => 'follow_answer_page',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => "User follow posts page",
		'desc' => "Select User follow posts page",
		'id' => 'follow_post_page',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => "User follow comment page",
		'desc' => "Select User follow comment page",
		'id' => 'follow_comment_page',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => 'Points Settings',
		'type' => 'heading');
	
	$options[] = array(
		'name' => "Badges setting",
		'class' => 'home_page_display',
		'class' => 'displaynone',
		'type' => 'info');
	
	$options[] = array(
		'desc' => "Add your badges.",
		'id' => "badges",
		'std' => '',
		'class' => 'displaynone',
		'type' => 'badges');
	
	$options[] = array(
		'name' => "Points needed to add a new question ( put 0 if not points needed )",
		'desc' => "Put the number of points needed to add a new question",
		'id' => 'point_add_question',
		'type' => 'text',
		'std' => 0);
	
	$options[] = array(
		'name' => "Points needed to choose best answer",
		'desc' => "Put the number of points needed to choose best answer",
		'id' => 'point_best_answer',
		'type' => 'text',
		'std' => 5);
	
	$options[] = array(
		'name' => "Points needed to Rate question",
		'desc' => "Put the number of points needed to Rate question",
		'id' => 'point_rating_question',
		'type' => 'text',
		'std' => 0);
	
	$options[] = array(
		'name' => "Points needed to add comment",
		'desc' => "Put the number of points needed to add comment",
		'id' => 'point_add_comment',
		'type' => 'text',
		'std' => 2);
	
	$options[] = array(
		'name' => "Points needed to Rate answer",
		'desc' => "Put the number of points needed to Rate answer",
		'id' => 'point_rating_answer',
		'type' => 'text',
		'std' => 1);
	
	$options[] = array(
		'name' => "Points needed to follow user",
		'desc' => "Put the number of points needed to follow user",
		'id' => 'point_following_me',
		'type' => 'text',
		'std' => 1);
	
	$options[] = array(
		'name' => "Points given to a new user",
		'desc' => "Put the number of points to be given to a new user",
		'id' => 'point_new_user',
		'type' => 'text',
		'std' => 20);
	
	$options[] = array(
		'name' => 'User group Settings',
		'type' => 'heading');
	
	$options[] = array(
		'name' => 'Select ON to can add a custom permission.',
		'desc' => 'Select ON to can add a custom permission.',
		'id' => 'custom_permission',
		'std' => 0,
		'class' => 'displaynone',
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => "Not logged in users can",
		'class' => 'home_page_display',
		'type' => 'info');
	
	$options[] = array(
		'name' => 'Select ON so that Not logged in users can add a question.',
		'desc' => 'Select ON so that Not logged in users can add a question.',
		'id' => 'ask_question',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Select ON so that Not logged in users can see other questions.',
		'desc' => 'Select ON so that Not logged in users can see other questions.',
		'id' => 'show_question',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Select ON so that Not logged in users can add answer.',
		'desc' => 'Select ON so that Not logged in users can add answer.',
		'id' => 'add_answer',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Select ON so that Not logged in users can see other answers.',
		'desc' => 'Select ON so that Not logged in users can see other answers.',
		'id' => 'show_answer',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Select ON so that Not logged in users can add a post.',
		'desc' => 'Select ON so that Not logged in users can add a post.',
		'id' => 'add_post',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'desc' => "Add your groups.",
		'id' => "roles",
		'std' => '',
		'type' => 'roles');
	
	$options[] = array(
		'name' => 'Register Settings',
		'type' => 'heading');
	
	$options[] = array(
		'name' => "Register in default group",
		'desc' => "Select the default group",
		'id' => 'default_group',
		'std' => 'subscriber',
		'type' => 'select',
		'options' => $new_roles);
	
	$options[] = array(
		'name' => 'Confirm with email enable or disable ( in register form )',
		'desc' => 'Confirm with email enable or disable.',
		'id' => 'confirm_email',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Add profile picture in register form',
		'desc' => 'Add profile picture in register form.',
		'id' => 'profile_picture',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Profile picture in register form is required',
		'desc' => 'Profile picture in register form is required.',
		'id' => 'profile_picture_required',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Add country in register form',
		'desc' => 'Add country in register form.',
		'id' => 'country_register',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Country in register form is required',
		'desc' => 'Country in register form is required.',
		'id' => 'country_required',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Add city in register form',
		'desc' => 'Add city in register form.',
		'id' => 'city_register',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'City in register form is required',
		'desc' => 'City in register form is required.',
		'id' => 'city_required',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Add age in register form',
		'desc' => 'Add age in register form.',
		'id' => 'age_register',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Age in register form is required',
		'desc' => 'Age in register form is required.',
		'id' => 'age_required',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Add phone in register form',
		'desc' => 'Add phone in register form.',
		'id' => 'phone_register',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Phone in register form is required',
		'desc' => 'Phone in register form is required.',
		'id' => 'phone_required',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Add sex in register form',
		'desc' => 'Add sex in register form.',
		'id' => 'sex_register',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Sex in register form is required',
		'desc' => 'Sex in register form is required.',
		'id' => 'sex_required',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Add names in register form',
		'desc' => 'Add names in register form.',
		'id' => 'names_register',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Names in register form is required',
		'desc' => 'Names in register form is required.',
		'id' => 'names_required',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Active the page terms ?',
		'desc' => 'Select ON if you want active the page terms.',
		'id' => 'terms_avtive_register',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Open the page in same page or a new page ?',
		'desc' => 'Open the page in same page or a new page.',
		'id' => 'terms_avtive_target_register',
		'std' => "new_page",
		'type' => 'select',
		'options' => array("same_page" => "Same page","new_page" => "New page"));
	
	$options[] = array(
		'name' => "Terms page",
		'desc' => "Select the terms page",
		'id' => 'terms_page_register',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => "Type the terms links if you don't like a page",
		'desc' => "Type the terms links if you don't like a page",
		'id' => 'terms_link_register',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Register content',
		'desc' => 'Put the register content in top panel and register page.',
		'id' => 'register_content',
		'std' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Morbi adipiscing gravdio, sit amet suscipit risus ultrices eu. Fusce viverra neque at purus laoreet consequa. Vivamus vulputate posuere nisl quis consequat.',
		'type' => 'textarea');
	
	$options[] = array(
		'name' => 'New post Settings',
		'type' => 'heading');
	
	$options[] = array(
		'name' => 'Any one can add post without register',
		'desc' => 'Any one can add post without register enable or disable.',
		'id' => 'add_post_no_register',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Choose post status',
		'desc' => 'Choose post status after user publish the post.',
		'id' => 'post_publish',
		'options' => array("publish" => "Publish","draft" => "Draft"),
		'std' => 'draft',
		'type' => 'select');
	
	$options[] = array(
		'name' => 'Tags enable or disable in add post form',
		'desc' => 'Select ON to enable the tags in add post form.',
		'id' => 'tags_post',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Attachment in add post form',
		'desc' => 'Select ON to enable the attachment in add post form.',
		'id' => 'attachment_post',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Details in add post form is required',
		'desc' => 'Details in add post form is required.',
		'id' => 'content_post',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Editor enable or disable for details in add post form',
		'desc' => 'Editor enable or disable for details in add post form.',
		'id' => 'editor_post_details',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'The users can edit the posts ?',
		'desc' => 'The users can edit the posts ?',
		'id' => 'can_edit_post',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => "Edit post page",
		'desc' => "Create a page using the Edit post template and select it here",
		'id' => 'edit_post',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => 'Active user can delete the posts',
		'desc' => 'Select ON if you want the user can delete the posts.',
		'id' => 'post_delete',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Author Page',
		'type' => 'heading');
	
	$options[] = array(
		'name' => 'Hide the user registered in profile page',
		'desc' => 'Select ON if you want to hide the user registered in profile page.',
		'id' => 'user_registered',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Hide the user country in profile page',
		'desc' => 'Select ON if you want to hide the user country in profile page.',
		'id' => 'user_country',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Hide the user city in profile page',
		'desc' => 'Select ON if you want to hide the user city in profile page.',
		'id' => 'user_city',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Hide the user phone in profile page',
		'desc' => 'Select ON if you want to hide the user phone in profile page.',
		'id' => 'user_phone',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Hide the user age in profile page',
		'desc' => 'Select ON if you want to hide the user age in profile page.',
		'id' => 'user_age',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Hide the user sex in profile page',
		'desc' => 'Select ON if you want to hide the user sex in profile page.',
		'id' => 'user_sex',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Hide the user url in profile page',
		'desc' => 'Select ON if you want to hide the user url in profile page.',
		'id' => 'user_url',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => "Author sidebar layout",
		'desc' => "Author sidebar layout.",
		'id' => "author_sidebar_layout",
		'std' => "full",
		'type' => "images",
		'options' => array(
			'default' => $imagepath . 'sidebar_default.jpg',
			'right' => $imagepath . 'sidebar_right.jpg',
			'full' => $imagepath . 'sidebar_no.jpg',
			'left' => $imagepath . 'sidebar_left.jpg',
		)
	);
	
	$options[] = array(
		'name' => "Author Page Sidebar",
		'desc' => "Author Page Sidebar.",
		'id' => "author_sidebar",
		'std' => '',
		'options' => $new_sidebars,
		'type' => 'select');
	
	$options[] = array(
		'name' => "Author page layout",
		'desc' => "Author page layout.",
		'id' => "author_layout",
		'std' => "full",
		'type' => "images",
		'options' => array(
			'default' => $imagepath . 'sidebar_default.jpg',
			'full' => $imagepath . 'full.jpg',
			'fixed' => $imagepath . 'fixed.jpg',
			'fixed_2' => $imagepath . 'fixed_2.jpg'
		)
	);
	
	$options[] = array(
		'name' => "Choose template",
		'desc' => "Choose template layout.",
		'id' => "author_template",
		'std' => "grid_1200",
		'type' => "images",
		'options' => array(
			'default' => $imagepath . 'sidebar_default.jpg',
			'grid_1200' => $imagepath . 'template_1200.jpg',
			'grid_970' => $imagepath . 'template_970.jpg'
		)
	);
	
	
	
	

	$options[] = array(
		'name' => "Full Screen Background",
		'desc' => "Click on to Full Screen Background",
		'id' => 'author_full_screen_background',
		'std' => '0',
		'class' => 'displaynone',
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Blog & Article Settings',
		'type' => 'heading');
	
	$options[] = array(
		'name' => 'Enable Knowledge Base Post Style',
		'desc' => 'Switch On/Off Knowledge Base Post Style.',
		'id' => 'infocenter_knowledgebase_style',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => "Blog display",
		'desc' => "Choose the Blog display",
		'id' => 'home_display',
		'std' => 'blog_1',
		'type' => 'radio',
		'options' => 
			array(
				"blog_1" => "Blog 1",
				"blog_2" => "Blog 2"
		)
	);
	
	$options[] = array(
		'name' => 'Type the date format see this link also : http://codex.wordpress.org/Formatting_Date_and_Time',
		'desc' => 'Type here your date format.',
		'id' => 'date_format',
		'std' => 'F j , Y',
		'type' => 'text');
	
	$options[] = array(
		'desc' => "Sort your sections.",
		'name' => "Sort your sections.",
		'id' => "order_sections_li",
		'std' => '',
		'type' => 'sections');
	
	$options[] = array(
		'name' => 'Hide the featured image in the single post',
		'desc' => 'Click on to hide the featured image in the single post.',
		'id' => 'featured_image',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Excerpt type ',
		'desc' => 'Choose form here the excerpt type.',
		'id' => 'excerpt_type',
		'std' => 5,
		'type' => "select",
		'options' => array(
			'words' => 'Words',
			'characters' => 'Characters')
		);
	
	$options[] = array(
		'name' => 'Excerpt post',
		'desc' => 'Put here the excerpt post.',
		'id' => 'post_excerpt',
		'std' => 40,
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Post meta enable or disable',
		'desc' => 'Post meta enable or disable.',
		'id' => 'post_meta',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Share enable or disable',
		'desc' => 'Share enable or disable.',
		'id' => 'post_share',
		'std' => 0,
		'type' => 'checkbox');
		
	$options[] = array(
		'name' => 'Author info box enable or disable',
		'desc' => 'Author info box enable or disable.',
		'id' => 'post_author_box',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Related post enable or disable',
		'desc' => 'Related post enable or disable.',
		'id' => 'related_post',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Related post number',
		'desc' => 'Type related post number from here.',
		'id' => 'related_number',
		'std' => '5',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Comments enable or disable',
		'desc' => 'Comments enable or disable.',
		'id' => 'post_comments',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Enable the editor in the comment or disable',
		'desc' => 'Enable the editor in the comment or disable.',
		'id' => 'comment_editor',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Comments enable or disable for user only',
		'desc' => 'Comments enable or disable for user only.',
		'id' => 'post_comments_user',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'User can edit the comment or answer?',
		'desc' => 'User can edit the comment or answer?',
		'id' => 'can_edit_comment',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		"name" => "User can edit the comment or answer after -- hours",
		"desc" => "If you want the user edit it all the time leave it 0",
		"id" => "can_edit_comment_after",
		"type" => "sliderui",
		'std' => 1,
		"step" => "1",
		"min" => "0",
		"max" => "24");
	
	$options[] = array(
		'name' => "Edit comment page",
		'desc' => "Create a page using the Edit post template and select it here",
		'id' => 'edit_comment',
		'type' => 'select',
		'options' => $options_pages);
	
	$options[] = array(
		'name' => 'After edit comment or answer approved auto or need to approved again?',
		'desc' => 'Press ON to approved auto',
		'id' => 'comment_approved',
		'std' => 0,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Navigation post enable or disable',
		'desc' => 'Navigation post ( next and previous posts) enable or disable.',
		'id' => 'post_navigation',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Sidebar',
		'type' => 'heading');
	
	$options[] = array(
		'desc' => "Add your sidebars.",
		'id' => "sidebars",
		'std' => '',
		'type' => 'sidebar');
	
	$options[] = array(
		'name' => "Sidebar width",
		'desc' => "Sidebar width",
		'id' => 'sidebar_width',
		'std' => 'col-md-4',
		'type' => 'radio',
		'options' => 
			array(
				"col-md-3" => "1/4",
				"col-md-4" => "1/3"
			)
		);
	
	$options[] = array(
		'name' => "Sticky sidebar",
		'desc' => "Click on to active the sticky sidebar",
		'id' => 'sticky_sidebar',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => "Sidebar layout",
		'desc' => "Sidebar layout.",
		'id' => "sidebar_layout",
		'std' => "default",
		'type' => "images",
		'options' => array(
			'default' => $imagepath . 'sidebar_default.jpg',
			'right' => $imagepath . 'sidebar_right.jpg',
			'full' => $imagepath . 'sidebar_no.jpg',
			'left' => $imagepath . 'sidebar_left.jpg',
		)
	);
	
	$options[] = array(
		'name' => "Home Page Sidebar",
		'desc' => "Home Page Sidebar.",
		'id' => "sidebar_home",
		'std' => '',
		'options' => $new_sidebars,
		'type' => 'select');
	
	$options[] = array(
		'name' => "Else home page , single and page",
		'desc' => "Else home page , single and page.",
		'id' => "else_sidebar",
		'std' => '',
		'options' => $new_sidebars,
		'type' => 'select');
	

	

	

	
	

	
	

	
	
	$options[] = array(
		'name' => 'Questions Styling',
		'type' => 'heading');
	

	
	
	
	

	$options[] = array(
		'name' => 'Logo upload for questions',
		'desc' => 'Upload your custom logo for questions. ',
		'id' => 'questions_logo_img',
		'std' => '',
		'type' => 'upload');
	
	$options[] = array(
		'name' => 'Logo retina upload for questions',
		'desc' => 'Upload your custom logo retina for questions. ',
		'id' => 'questions_retina_logo',
		'std' => '',
		'type' => 'upload');
	
	$options[] = array(
		'name' => "Questions sidebar layout",
		'desc' => "Questions sidebar layout.",
		'id' => "questions_sidebar_layout",
		'std' => "default",
		'type' => "images",
		'options' => array(
			'default' => $imagepath . 'sidebar_default.jpg',
			'right' => $imagepath . 'sidebar_right.jpg',
			'full' => $imagepath . 'sidebar_no.jpg',
			'left' => $imagepath . 'sidebar_left.jpg',
		)
	);
	
	$options[] = array(
		'name' => "Questions Page Sidebar",
		'desc' => "Questions Page Sidebar.",
		'id' => "questions_sidebar",
		'std' => '',
		'options' => $new_sidebars,
		'type' => 'select');
	
	
	
	
	
	
	

	

		
	


		
	
	
	if ( class_exists( 'woocommerce' ) ) {
		$options[] = array(
			'name' => 'Products Settings',
			'type' => 'heading');
		
		$options[] = array(
			'name' => "Custom Logo position - Header skin - Logo display ?",
			'desc' => "Click on to make a Custom Logo position - Header skin - Logo display",
			'id' => 'products_custom_header',
			'std' => '0',
			'type' => 'checkbox');
		
		if (is_rtl()) {
			$options[] = array(
				'name' => "Logo position for products",
				'desc' => "Select where you would like your logo to appear for products.",
				'id' => "products_logo_position",
				'std' => "left_logo",
				'type' => "images",
				'options' => array(
					'left_logo' => $imagepath . 'right_logo.jpg',
					'right_logo' => $imagepath . 'left_logo.jpg',
					'center_logo' => $imagepath . 'center_logo.jpg'
				)
			);
		}else {
			$options[] = array(
				'name' => "Logo position for products",
				'desc' => "Select where you would like your logo to appear for products.",
				'id' => "products_logo_position",
				'std' => "left_logo",
				'type' => "images",
				'options' => array(
					'left_logo' => $imagepath . 'left_logo.jpg',
					'right_logo' => $imagepath . 'right_logo.jpg',
					'center_logo' => $imagepath . 'center_logo.jpg'
				)
			);
		}
		
		$options[] = array(
			'name' => "Header skin for products",
			'desc' => "Select your preferred header skin for products.",
			'id' => "products_header_skin",
			'std' => "header_dark",
			'type' => "images",
			'options' => array(
				'header_dark' => $imagepath . 'left_logo.jpg',
				'header_light' => $imagepath . 'header_light.jpg'
			)
		);
		
		$options[] = array(
			'name' => 'Logo display for products',
			'desc' => 'choose Logo display for products.',
			'id' => 'products_logo_display',
			'std' => 'display_title',
			'type' => 'radio',
			'options' => array("display_title" => "Display site title","custom_image" => "Custom Image"));
		
		$options[] = array(
			'name' => 'Logo upload for products',
			'desc' => 'Upload your custom logo for products. ',
			'id' => 'products_logo_img',
			'std' => '',
			'type' => 'upload');
		
		$options[] = array(
			'name' => 'Logo retina upload for products',
			'desc' => 'Upload your custom logo retina for products. ',
			'id' => 'products_retina_logo',
			'std' => '',
			'type' => 'upload');
		
		$options[] = array(
			'name' => 'Related products number',
			'desc' => 'Type related products number from here.',
			'id' => 'related_products_number',
			'std' => '3',
			'type' => 'text');
		
		$options[] = array(
			'name' => 'Related products number full width',
			'desc' => 'Type related products number full width from here.',
			'id' => 'related_products_number_full',
			'std' => '4',
			'type' => 'text');
		
		$options[] = array(
			'name' => 'Excerpt title in products pages',
			'desc' => 'Type excerpt title in products pages from here.',
			'id' => 'products_excerpt_title',
			'std' => '40',
			'type' => 'text');
		
		$options[] = array(
			'name' => "Products sidebar layout",
			'desc' => "Products sidebar layout.",
			'id' => "products_sidebar_layout",
			'std' => "default",
			'type' => "images",
			'options' => array(
				'default' => $imagepath.'sidebar_default.jpg',
				'right' => $imagepath.'sidebar_right.jpg',
				'full' => $imagepath.'sidebar_no.jpg',
				'left' => $imagepath.'sidebar_left.jpg',
			)
		);
		
		$options[] = array(
			'name' => "Products Page Sidebar",
			'desc' => "Products Page Sidebar.",
			'id' => "products_sidebar",
			'std' => '',
			'options' => $new_sidebars,
			'type' => 'select');
		
		$options[] = array(
			'name' => "Products page layout",
			'desc' => "Products page layout.",
			'id' => "products_layout",
			'std' => "default",
			'type' => "images",
			'options' => array(
				'default' => $imagepath.'sidebar_default.jpg',
				'full' => $imagepath.'full.jpg',
				'fixed' => $imagepath.'fixed.jpg',
				'fixed_2' => $imagepath.'fixed_2.jpg'
			)
		);
		
		$options[] = array(
			'name' => "Choose template",
			'desc' => "Choose template layout.",
			'id' => "products_template",
			'std' => "default",
			'type' => "images",
			'options' => array(
				'default' => $imagepath.'sidebar_default.jpg',
				'grid_1200' => $imagepath.'template_1200.jpg',
				'grid_970' => $imagepath.'template_970.jpg'
			)
		);
		
		
		
	
	
		
	
			
		$options[] = array(
			'name' => "Full Screen Background",
			'desc' => "Click on to Full Screen Background",
			'id' => 'products_full_screen_background',
			'std' => '0',
			'type' => 'checkbox');
	}
	
	$options[] = array(
		'name' => 'Advertising',
		'type' => 'heading');
	
	$options[] = array(
		'name' => "Advertising after header",
		'class' => 'home_page_display',
		'type' => 'info');
	
	$options[] = array(
		'name' => 'Advertising type',
		'desc' => 'Advertising type.',
		'id' => 'header_adv_type',
		'std' => 'custom_image',
		'type' => 'radio',
		'options' => array("display_code" => "Display code","custom_image" => "Custom Image"));
	
	$options[] = array(
		'name' => 'Image URL',
		'desc' => 'Upload a image, or enter URL to an image if it is already uploaded. ',
		'id' => 'header_adv_img',
		'std' => '',
		'type' => 'upload');
	
	$options[] = array(
		'name' => 'Advertising url',
		'desc' => 'Advertising url. ',
		'id' => 'header_adv_href',
		'std' => '#',
		'type' => 'text');
	
	$options[] = array(
		'name' => "Advertising Code html ( Ex: Google ads)",
		'desc' => "Advertising Code html ( Ex: Google ads)",
		'id' => 'header_adv_code',
		'std' => '',
		'type' => 'textarea');
	
	$options[] = array(
		'name' => "Advertising 1 in post and question",
		'class' => 'home_page_display',
		'type' => 'info');
	
	$options[] = array(
		'name' => 'Advertising type',
		'desc' => 'Advertising type.',
		'id' => 'share_adv_type',
		'std' => 'custom_image',
		'type' => 'radio',
		'options' => array("display_code" => "Display code","custom_image" => "Custom Image"));
	
	$options[] = array(
		'name' => 'Image URL',
		'desc' => 'Upload a image, or enter URL to an image if it is already uploaded. ',
		'id' => 'share_adv_img',
		'std' => '',
		'type' => 'upload');
	
	$options[] = array(
		'name' => 'Advertising url',
		'desc' => 'Advertising url. ',
		'id' => 'share_adv_href',
		'std' => '#',
		'type' => 'text');
	
	$options[] = array(
		'name' => "Advertising Code html ( Ex: Google ads)",
		'desc' => "Advertising Code html ( Ex: Google ads)",
		'id' => 'share_adv_code',
		'std' => '',
		'type' => 'textarea');
	
	$options[] = array(
		'name' => "Advertising 2 in post and question",
		'class' => 'home_page_display',
		'type' => 'info');
	
	$options[] = array(
		'name' => 'Advertising type',
		'desc' => 'Advertising type.',
		'id' => 'related_adv_type',
		'std' => 'custom_image',
		'type' => 'radio',
		'options' => array("display_code" => "Display code","custom_image" => "Custom Image"));
	
	$options[] = array(
		'name' => 'Image URL',
		'desc' => 'Upload a image, or enter URL to an image if it is already uploaded. ',
		'id' => 'related_adv_img',
		'std' => '',
		'type' => 'upload');
	
	$options[] = array(
		'name' => 'Advertising url',
		'desc' => 'Advertising url. ',
		'id' => 'related_adv_href',
		'std' => '#',
		'type' => 'text');
	
	$options[] = array(
		'name' => "Advertising Code html ( Ex: Google ads)",
		'desc' => "Advertising Code html ( Ex: Google ads)",
		'id' => 'related_adv_code',
		'std' => '',
		'type' => 'textarea');
		
	$options[] = array(
		'name' => "Advertising after content",
		'class' => 'home_page_display',
		'type' => 'info');
	
	$options[] = array(
		'name' => 'Advertising type',
		'desc' => 'Advertising type.',
		'id' => 'content_adv_type',
		'std' => 'custom_image',
		'type' => 'radio',
		'options' => array("display_code" => "Display code","custom_image" => "Custom Image"));
	
	$options[] = array(
		'name' => 'Image URL',
		'desc' => 'Upload a image, or enter URL to an image if it is already uploaded. ',
		'id' => 'content_adv_img',
		'std' => '',
		'type' => 'upload');
	
	$options[] = array(
		'name' => 'Advertising url',
		'desc' => 'Advertising url. ',
		'id' => 'content_adv_href',
		'std' => '#',
		'type' => 'text');
	
	$options[] = array(
		'name' => "Advertising Code html ( Ex: Google ads)",
		'desc' => "Advertising Code html ( Ex: Google ads)",
		'id' => 'content_adv_code',
		'std' => '',
		'type' => 'textarea');
	
	$options[] = array(
		'name' => "Between questions and posts",
		'class' => 'home_page_display',
		'type' => 'info');
	
	$options[] = array(
		'name' => 'Between questions or posts position',
		'desc' => 'Between questions or posts position. ',
		'id' => 'between_questions_position',
		'std' => '2',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Advertising type',
		'desc' => 'Advertising type.',
		'id' => 'between_adv_type',
		'std' => 'custom_image',
		'type' => 'radio',
		'options' => array("display_code" => "Display code","custom_image" => "Custom Image"));
	
	$options[] = array(
		'name' => 'Image URL',
		'desc' => 'Upload a image, or enter URL to an image if it is already uploaded. ',
		'id' => 'between_adv_img',
		'std' => '',
		'type' => 'upload');
	
	$options[] = array(
		'name' => 'Advertising url',
		'desc' => 'Advertising url. ',
		'id' => 'between_adv_href',
		'std' => '#',
		'type' => 'text');
	
	$options[] = array(
		'name' => "Advertising Code html ( Ex: Google ads)",
		'desc' => "Advertising Code html ( Ex: Google ads)",
		'id' => 'between_adv_code',
		'std' => '',
		'type' => 'textarea');
	
	$options[] = array(
		'name' => "Between comments and answers",
		'class' => 'home_page_display',
		'type' => 'info');
	
	$options[] = array(
		'name' => 'Between comments and answers position',
		'desc' => 'Between comments and answers position. ',
		'id' => 'between_comments_position',
		'std' => '2',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Advertising type',
		'desc' => 'Advertising type.',
		'id' => 'between_comments_adv_type',
		'std' => 'custom_image',
		'type' => 'radio',
		'options' => array("display_code" => "Display code","custom_image" => "Custom Image"));
	
	$options[] = array(
		'name' => 'Image URL',
		'desc' => 'Upload a image, or enter URL to an image if it is already uploaded. ',
		'id' => 'between_comments_adv_img',
		'std' => '',
		'type' => 'upload');
	
	$options[] = array(
		'name' => 'Advertising url',
		'desc' => 'Advertising url. ',
		'id' => 'between_comments_adv_href',
		'std' => '#',
		'type' => 'text');
	
	$options[] = array(
		'name' => "Advertising Code html ( Ex: Google ads)",
		'desc' => "Advertising Code html ( Ex: Google ads)",
		'id' => 'between_comments_adv_code',
		'std' => '',
		'type' => 'textarea');
	
	$options[] = array(
		'name' => 'Social Settings',
		'type' => 'heading');
	
	$options[] = array(
		'name' => 'Social header enable or disable',
		'desc' => 'Social enable or disable.',
		'id' => 'social_icon_h',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Social footer enable or disable',
		'desc' => 'Social enable or disable.',
		'id' => 'social_icon_f',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'Twitter URL',
		'desc' => 'Type the twitter URL from here.',
		'id' => 'twitter_icon_f',
		'std' => '#',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Facebook URL',
		'desc' => 'Type the facebook URL from here.',
		'id' => 'facebook_icon_f',
		'std' => '#',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Google plus URL',
		'desc' => 'Type the google plus URL from here.',
		'id' => 'gplus_icon_f',
		'std' => '#',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Youtube URL',
		'desc' => 'Type the youtube URL from here.',
		'id' => 'youtube_icon_f',
		'std' => '#',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Skype',
		'desc' => 'Type the skype from here.',
		'id' => 'skype_icon_f',
		'std' => '#',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Linkedin URL',
		'desc' => 'Type the linkedin URL from here.',
		'id' => 'linkedin_icon_f',
		'std' => '#',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Flickr URL',
		'desc' => 'Type the flickr URL from here.',
		'id' => 'flickr_icon_f',
		'std' => '#',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'RSS enable or disable',
		'desc' => 'RSS enable or disable.',
		'id' => 'rss_icon_f',
		'std' => 1,
		'type' => 'checkbox');
	
	$options[] = array(
		'name' => 'RSS URL if you want change the default URL',
		'desc' => 'Type the RSS URL if you want change the default URL or leave it empty for enable the default URL.',
		'id' => 'rss_icon_f_other',
		'std' => '',
		'type' => 'text');
	
	$options[] = array(
		'name' => 'Footer Settings',
		'type' => 'heading');
		
	
	
	$options[] = array(
		'name' => "Footer Layout",
		'desc' => "Footer columns Layout.",
		'id' => "footer_layout",
		'std' => "footer_4c",
		'type' => "images",
		'options' => array(
			'footer_1c' => $imagepath . 'footer_1c.jpg',
			'footer_2c' => $imagepath . 'footer_2c.jpg',
			'footer_3c' => $imagepath . 'footer_3c.jpg',
			'footer_4c' => $imagepath . 'footer_4c.jpg',
			'footer_5c' => $imagepath . 'footer_5c.jpg',
			'footer_no' => $imagepath . 'footer_no.jpg'
		)
	);
	
	$options[] = array(
		'name' => 'Copyrights',
		'desc' => 'Put the copyrights of footer.',
		'id' => 'footer_copyrights',
		'std' => 'Copyright 2017 knowledge theme | <a href=#> InfoCenter</a>',
		'type' => 'textarea');
	
	return $options;
}