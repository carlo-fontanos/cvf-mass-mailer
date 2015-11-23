<?php
/**
 * Plugin Name: CVF Mass Mailer
 * Plugin URI: http://carlofontanos.com/product/cvf-mass-mailer-plugin/
 * Description: Simple yet powerful mass mailer plugin for WordPress
 * Version: 1.0
 * Author: Carl Victor C. Fontanos
 * Author URI: http://carlofontanos.com
 * Text Domain: cvf-mass-mailer
 * License: 
 */
 
/*  Copyright 2014  Carl Victor C. Fontanos  (email : PLUGIN carl.esilverconnect@gmail.com)

	This program is a free software; you can redistribute it and/or modify
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

define( 'CVF_MASS_MAILER_FILE', __FILE__ );
define( 'CVF_MASS_MAILER_PLUGIN_DIR', plugin_dir_path( CVF_MASS_MAILER_FILE ) );
define( 'CVF_MASS_MAILER_PLUGIN_URL', plugin_dir_url( CVF_MASS_MAILER_FILE ) );
define( 'CVF_MASS_MAILER_AUTHOR', 'Carl Victor Fontanos');
define( 'CVF_MASS_MAILER_AUTHOR_URL', 'www.carlofontanos.com');

require_once( CVF_MASS_MAILER_PLUGIN_DIR . '/inc/CVF_Mass_Mailer.class.php' );
