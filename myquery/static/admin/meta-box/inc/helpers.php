<?php
/**
 * This file contains all helpers/public functions
 * that can be used both on the back-end or front-end
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'RWMB_Helper' ) )
{
	/**
	 * Wrapper class for helper functions
	 */
	class RWMB_Helper
	{
		/**
		 * Do actions when class is loaded
		 *
		 * @return void
		 */
		static function on_load()
		{
			//add_shortcode( 'rwmb_meta', array( __CLASS__, 'shortcode' ) );
		}

		/**
		 * Shortcode to display meta value
		 *
		 * @param $atts Array of shortcode attributes, same as meta() function, but has more "meta_key" parameter
		 * @see meta() function below
		 *
		 * @return string
		 */
		static function shortcode( $atts )
		{
			$atts = wp_parse_args( $atts, array(
				'type'    => 'text',
				'post_id' => get_the_ID(),
			) );
			if ( empty( $atts['meta_key'] ) )
				return '';

			$meta = self::meta( $atts['meta_key'], $atts, $atts['post_id'] );

			// Get uploaded files info
			if ( in_array( $atts['type'], array( 'file', 'file_advanced' ) ) )
			{
				$content = '<ul>';
				foreach ( $meta as $file )
				{
					$content .= sprintf(
						'<li><a href="%s" title="%s">%s</a></li>',
						$file['url'],
						$file['title'],
						$file['name']
					);
				}
				$content .= '</ul>';
			}

			// Get uploaded images info
			elseif ( in_array( $atts['type'], array( 'image', 'plupload_image', 'thickbox_image', 'image_advanced' ) ) )
			{
				$content = '<ul>';
				foreach ( $meta as $image )
				{
					// Link thumbnail to full size image?
					if ( isset( $atts['link'] ) && $atts['link'] )
					{
						$content .= sprintf(
							'<li><a href="%s" title="%s"><img src="%s" alt="%s" title="%s" /></a></li>',
							$image['full_url'],
							$image['title'],
							$image['url'],
							$image['alt'],
							$image['title']
						);
					}
					else
					{
						$content .= sprintf(
							'<li><img src="%s" alt="%s" title="%s" /></li>',
							$image['url'],
							$image['alt'],
							$image['title']
						);
					}
				}
				$content .= '</ul>';
			}

			// Get post terms
			elseif ( 'taxonomy' == $atts['type'] )
			{
				$content = '<ul>';
				foreach ( $meta as $term )
				{
					$content .= sprintf(
						'<li><a href="%s" title="%s">%s</a></li>',
						get_term_link( $term, $atts['taxonomy'] ),
						$term->name,
						$term->name
					);
				}
				$content .= '</ul>';
			}

			// Normal multiple fields: checkbox_list, select with multiple values
			elseif ( is_array( $meta ) )
			{
				$content = '<ul><li>' . implode( '</li><li>', $meta ) . '</li></ul>';
			}

			else
			{
				$content = $meta;
			}

			return apply_filters( __FUNCTION__, $content );
		}

		/**
		 * Get post meta
		 *
		 * @param string   $key     Meta key. Required.
		 * @param int|null $post_id Post ID. null for current post. Optional
		 * @param array    $args    Array of arguments. Optional.
		 *
		 * @return mixed
		 */
		static function meta( $key, $args = array(), $post_id = null )
		{
			$post_id = empty( $post_id ) ? get_the_ID() : $post_id;

			$args = wp_parse_args( $args, array(
				'type' => 'text',
			) );

			// Set 'multiple' for fields based on 'type'
			if ( !isset( $args['multiple'] ) )
				$args['multiple'] = in_array( $args['type'], array( 'checkbox_list', 'file', 'file_advanced', 'image', 'image_advanced', 'plupload_image', 'thickbox_image' ) );

			$meta = get_post_meta( $post_id, $key, !$args['multiple'] );

			// Get uploaded files info
			if ( in_array( $args['type'], array( 'file', 'file_advanced' ) ) )
			{
				if ( is_array( $meta ) && !empty( $meta ) )
				{
					$files = array();
					foreach ( $meta as $id )
					{
						$files[$id] = self::file_info( $id );
					}
					$meta = $files;
				}
			}

			// Get uploaded images info
			elseif ( in_array( $args['type'], array( 'image', 'plupload_image', 'thickbox_image', 'image_advanced' ) ) )
			{
				global $wpdb;

				$meta = $wpdb->get_col( $wpdb->prepare( "
					SELECT meta_value FROM $wpdb->postmeta
					WHERE post_id = %d AND meta_key = '%s'
					ORDER BY meta_id ASC
				", $post_id, $key ) );

				if ( is_array( $meta ) && !empty( $meta ) )
				{
					$images = array();
					foreach ( $meta as $id )
					{
						$images[$id] = self::image_info( $id, $args );
					}
					$meta = $images;
				}
			}

			// Get terms
			elseif ( 'taxonomy_advanced' == $args['type'] )
			{
				if ( !empty( $args['taxonomy'] ) )
				{
					$term_ids = array_map( 'intval', array_filter( explode( ',', $meta . ',' ) ) );

					// Allow to pass more arguments to "get_terms"
					$func_args = wp_parse_args( array(
						'include'    => $term_ids,
						'hide_empty' => false,
					), $args );
					unset( $func_args['type'], $func_args['taxonomy'], $func_args['multiple'] );
					$meta = get_terms( $args['taxonomy'], $func_args );
				}
				else
				{
					$meta = array();
				}
			}

			// Get post terms
			elseif ( 'taxonomy' == $args['type'] )
			{
				$meta = empty( $args['taxonomy'] ) ? array() : wp_get_post_terms( $post_id, $args['taxonomy'] );
			}

			// Get map
			elseif ( 'map' == $args['type'] )
			{
				$meta = self::map( $key, $args, $post_id );
			}

			return apply_filters( __FUNCTION__, $meta, $key, $args, $post_id );
		}

		/**
		 * Get uploaded file information
		 *
		 * @param int $id Attachment file ID (post ID). Required.
		 *
		 * @return array|bool False if file not found. Array of (id, name, path, url) on success
		 */
		static function file_info( $id )
		{
			$path = get_attached_file( $id );
			return array(
				'ID'    => $id,
				'name'  => basename( $path ),
				'path'  => $path,
				'url'   => wp_get_attachment_url( $id ),
				'title' => get_the_title( $id ),
			);
		}

		/**
		 * Get uploaded image information
		 *
		 * @param int   $id   Attachment image ID (post ID). Required.
		 * @param array $args Array of arguments (for size). Required.
		 *
		 * @return array|bool False if file not found. Array of (id, name, path, url) on success
		 */
		static function image_info( $id, $args = array() )
		{
			$args = wp_parse_args( $args, array(
				'size' => 'thumbnail',
			) );

			$img_src = wp_get_attachment_image_src( $id, $args['size'] );
			if ( empty( $img_src ) )
				return false;

			$attachment = get_post( $id );
			$path = get_attached_file( $id );
			return array(
				'ID'          => $id,
				'name'        => basename( $path ),
				'path'        => $path,
				'url'         => $img_src[0],
				'width'       => $img_src[1],
				'height'      => $img_src[2],
				'full_url'    => wp_get_attachment_url( $id ),
				'title'       => $attachment->post_title,
				'caption'     => $attachment->post_excerpt,
				'description' => $attachment->post_content,
				'alt'         => get_post_meta( $id, '_wp_attachment_image_alt', true ),
			);
		}

		
	}

	RWMB_Helper::on_load();
}

/**
 * Get post meta
 *
 * @param string   $key     Meta key. Required.
 * @param int|null $post_id Post ID. null for current post. Optional
 * @param array    $args    Array of arguments. Optional.
 *
 * @return mixed
 */
function rwmb_meta( $key, $args = array(), $post_id = null )
{
	return RWMB_Helper::meta( $key, $args, $post_id );
}
