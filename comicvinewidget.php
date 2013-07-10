<?php
/*
Comic Vine variant of the Whiskey Media widget.
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

class ComicVine_Widget extends AbstractWMWidget {

	protected $config = array(
		'site' => 'comicvine',
		'url' => 'http://www.comicvine.com/api/',
		'sitename' => 'Comic Vine',
		'resource' => 'character'
	);

	function ComicVine_Widget() {
		parent::WP_Widget( false, "Comic Vine Widget", array( 'description' => "Shows a list of comic book characters based on the post tags." )  );
	}
}
