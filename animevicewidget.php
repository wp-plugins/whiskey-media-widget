<?php
/*
Anime Vice variant of the Whiskey Media widget.
--
Copyright 2011 Michael Enger (email : mike@thelonelycoder.com)

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

class AnimeVice_Widget extends AbstractWMWidget {
	
	protected $config = array(
		'site' => 'animevice',
		'sitename' => 'Anime Vice',
		'resource' => 'character'
	);
	
	function AnimeVice_Widget() {
		parent::WP_Widget( false, "Anime Vice Widget", array( 'description' => "Shows a list of anime characters based on the post tags." ) );
	}
}
