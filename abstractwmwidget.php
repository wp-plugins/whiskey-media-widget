<?php
/*
Abstracted Whiskey Media widget (to be subclass'd by the "real" widgets)
--
Copyright 2013 Michael Enger (email : mike@thelonelycoder.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

class AbstractWMWidget extends WP_Widget {

	/**
	 * Styles of how to show the items.
	 *
	 * @var array
	 */
	protected $styles = array(
		'Basic list',
		'List w/images',
		'List w/description',
		'Images only'
	);

	/**
	 * Configuration for the widget.
	 *
	 * @var array
	 */
	protected $config = array(
		'site' => '@todo',
		'url' => '@todo',
		'sitename' => '@todo',
		'resource' => '@todo'
	);

	/**
	 * Build the widget form.
	 *
	 * @param array $instance Instance of the widget
	 */
	function form( $instance ) {
		$title = isset( $instance['title'] ) ? esc_attr( $instance['title'] ) : '';
		$api_key = isset( $instance['api_key'] ) ? esc_attr( $instance['api_key'] ) : '';
		$style = isset( $instance['style'] ) ? esc_attr( $instance['style'] ) : 1;
		$single_post_only = isset( $instance['single_post_only'] ) ? esc_attr( $instance['single_post_only'] ) : 1;
		$max_items = isset( $instance['max_items'] ) ? esc_attr( $instance['max_items'] ) : 3;
		$show_powered_by = isset( $instance['show_powered_by'] ) ? esc_attr( $instance['show_powered_by'] ) : 1;
		?>

			<p><label for="<?php print $this->get_field_id('title'); ?>">
				<?php _e('Title'); ?>:
				<input 	id="<?php print $this->get_field_id('title'); ?>"
						class="widefat"
						type="text"
						name="<?php print $this->get_field_name('title'); ?>"
						value="<?php print $title; ?>" />
			</label></p>

			<p><label for="<?php print $this->get_field_id('api_key'); ?>">
				<?php _e('API Key'); ?>:
				<input 	id="<?php print $this->get_field_id('api_key'); ?>"
						class="widefat"
						type="text"
						name="<?php print $this->get_field_name('api_key'); ?>"
						value="<?php print $api_key; ?>" />
				<small>
					<a href="http://api.<?php print $this->config['site']; ?>.com">Get your API key from <?php print $this->config['sitename']?></a>
				</small>
			</label></p>

			<p><label for="<?php print $this->get_field_id('style'); ?>">
				<?php _e('Style'); ?>:
				<select id="<?php print $this->get_field_id('style'); ?>"
						class="widefat"
						name="<?php print $this->get_field_name('style'); ?>">
					<?php foreach ( $this->styles as $key => $value ) {
						?><option value="<?php print $key; ?>" <?php if ( $key == $style) print 'selected="selected"'; ?>><?php print $value; ?></option><?php
					}?>
				</select>
			</label></p>

			<p><label for="<?php print $this->get_field_id('max_items'); ?>">
				<?php _e('Max items to show'); ?>:
				<input 	id="<?php print $this->get_field_id('max_items'); ?>"
						type="text"
						name="<?php print $this->get_field_name('max_items'); ?>"
						size="3"
						value="<?php print $max_items != 0 ? $max_items : ''; ?>" />
			</label></p>

			<p><label for="<?php print $this->get_field_id('single_post_only'); ?>">
				<input 	id="<?php print $this->get_field_id('single_post_only'); ?>"
						type="checkbox"
						name="<?php print $this->get_field_name('single_post_only'); ?>"
						<?php if ( $single_post_only ) print 'checked="checked"'; ?> />
				<?php _e('Show on single post only'); ?><br />
			</label></p>

			<p><label for="<?php print $this->get_field_id('show_powered_by'); ?>">
				<input 	id="<?php print $this->get_field_id('show_powered_by'); ?>"
						type="checkbox"
						name="<?php print $this->get_field_name('show_powered_by'); ?>"
						<?php if ( $show_powered_by ) print 'checked="checked"'; ?> />
				<?php _e('Show "Powered By" link'); ?><br />
			</label></p>
		<?php
	}

	/**
	 * Update the widget instance based on the form entries.
	 *
	 * @param array $new_instance New instance data
	 * @param array $old_instance Old instance data
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['api_key'] = trim( $new_instance['api_key'] );
		$instance['style'] = trim( $new_instance['style'] );
		$instance['site'] = trim( $new_instance['site'] );
		$instance['max_items'] = intval( $new_instance['max_items'] );
		$instance['single_post_only'] = isset( $new_instance['single_post_only'] ) ? 1 : 0;
		$instance['show_powered_by'] = isset( $new_instance['show_powered_by'] ) ? 1 : 0;
		return $instance;
	}

	/**
	 * Show the widget.
	 *
	 * @param array $args     Arguments sent to the widget
	 * @param array $instance Widget instance
	 */
	function widget( $args, $instance ) {
		global $wp_query;
		extract( $args );

		$api_key = $instance['api_key'];
		$title = !empty( $instance['title'] ) ? apply_filters( 'widget_title', $instance['title'] ) : $this->config['sitename'];
		$items = array();

		// Fetch items
		if ( $wp_query->is_single ) {
			$items = $this->getItemsByTags( $api_key, wp_get_post_tags( $wp_query->post->ID ), $instance['max_items'] );
		} elseif ( !$instance['single_post_only'] ) {
			$items = $this->getItemsByTags( $api_key, get_tags( array( 'orderby' => 'count', 'order' => 'DESC' ) ), $instance['max_items'] );
		}

		// No point in showing no items
		if ( empty( $items ) ) {
			return;
		}

		print $before_widget;
		?>
			<?php if ( !empty( $title ) ) echo $before_title . $title . $after_title; ?>

			<ul class="wmwidget-items">
				<?php foreach ( $items as $i => $item ) { ?>
					<li id="wmwidget-item-<?php print $i; ?>" class="wmwidget-item">
						<?php
						switch ( $instance['style'] ) {
							case 0: // Basic list
								?> <h4 class="wmwidget-title"><a href="<?php print $item->site_detail_url; ?>"><?php print $item->name; ?></a></h4> <?php
							break;

							case 1: // List w/images
								?>
								<h4 class="wmwidget-title"><a href="<?php print $item->site_detail_url; ?>"><?php print $item->name; ?></a></h4>
								<a href="<?php print $item->site_detail_url; ?>"><img class="wmwidget-thumbnail" src="<?php print $item->image->thumb_url; ?>" alt="<?php print esc_attr( $item->name ); ?>" /></a>
								<?php
							break;

							case 2: // List w/description
								?>
								<h4 class="wmwidget-title"><a href="<?php print $item->site_detail_url; ?>"><?php print $item->name; ?></a></h4>
								<p class="wmwidget-description"><?php print $item->deck; ?></p>
								<?php
							break;

							case 3: // Images only
								?>
								<a href="<?php print $item->site_detail_url; ?>"><img class="wmwidget-thumbnail" src="<?php print $item->image->thumb_url; ?>" alt="<?php print esc_attr( $item->name ); ?>" /></a>
								<?php
							break;
						}
						?>
					</li>
				<?php }
				?>
			</ul>
			<?php if ( $instance['show_powered_by'] ) { ?><em class="wmwidget-footer">Powered by <a href="http://<?php print $this->config['site']; ?>.com"><?php print $this->config['sitename']; ?></a></em> <?php } ?>
		<?php

		print $after_widget;
	}

	/**
	 * Get a list of items by tags.
	 *
	 * @param $api_key Whiskey Media API key
	 * @param $tags    List of WP tags
	 * @param $limit   Max amount of records to get
	 * @return array
	 */
	protected function getItemsByTags( $api_key, $tags, $limit = null ) {
		$items = array();

		foreach ( $tags as $tag ) {
			// Fetch the items
			$result = self::apiCall( $api_key, $tag->name );

			// Bail
			if ( $result->status_code !== 1 ) {
				//print "<strong>Error:</strong> Call to WM site {$site} ({$tag->name}) returned with status code {$result->status_code}"; // @debug
				continue;
			}

			// Get the first item
			if ( !empty( $result->results ) ) {
				$item = $result->results[0];
				if ( !array_key_exists( $item->id, $items ) )
					$items[$item->id] = $item;
			}

			// To the limit and nothing more
			if ( $limit && count( $items ) == $limit ) break;
		}

		return $items;
	}

	/**
	 * Make a call to the Whiskey Media API
	 *
	 * @param $api_key  Whiskey Media API key
	 * @param $name     Name to query for
	 * @return array
	 */
	protected function apiCall( $api_key, $name ) {

		// Build URL
		$url = $this->config['url'] . 'search/?' . http_build_query(array(
			'api_key' => $api_key,
			'resources' => $this->config['resource'],
			'query' => $name,
			'format' => 'json'
		));

		// Contact the server
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_HEADER, 0 );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		$data = curl_exec( $ch );

		curl_close( $ch );

		return json_decode( $data );
	}
}
