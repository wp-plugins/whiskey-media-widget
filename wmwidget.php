<?php
/*
Plugin Name: Whiskey Media Widget
Description: Fetches a list of items from a specific Whiskey Media site based on the post tags.
Author: The Lonely Coder
Version: 0.3
License: GPLv2
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

// Require all that junk
require_once('abstractwmwidget.php');
require_once('animevicewidget.php');
require_once('comicvinewidget.php');
require_once('giantbombwidget.php');

// Register the widgets
//add_action( 'widgets_init', create_function( '', 'return register_widget("AnimeVice_Widget");' ) ); // the API doesn't work yet :(
add_action( 'widgets_init', create_function( '', 'return register_widget("ComicVine_Widget");' ) );
add_action( 'widgets_init', create_function( '', 'return register_widget("GiantBomb_Widget");' ) );
