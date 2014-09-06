<?php
/**
* Plugin Name: Easy Digital Downloads - Software Licenses - Custom Expiration
* Plugin URI: http://www.wpcube.co.uk/plugins/edd-software-licenses-custom-expiration
* Version: 1.0
* Author: WP Cube
* Author URI: http://www.wpcube.co.uk
* Description: Define a different license expiration for downloads assigned to a specific Bundle. Requires the <a href="https://easydigitaldownloads.com/extensions/software-licensing/">EDD Software Licensing Addon</a>
* License: GPL2
*/

/*  Copyright 2013 WP Cube (email : support@wpcube.co.uk)

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

/**
* EDD SL CE Class
* 
* @package WP Cube
* @subpackage Framework
* @author Tim Carr
* @version 1.0
* @copyright WP Cube
*/
class EDDSLCE {
    /**
    * Constructor.
    */
    function EDDSLCE() {
        // Plugin Details
        $this->plugin = new stdClass;
        $this->plugin->name = 'easy-digital-downloads-software-licenses-custom-expiration'; // Plugin Folder
        $this->plugin->displayName = 'Custom Expiration'; // Plugin Name
        $this->plugin->version = '1.0';
        $this->plugin->folder = WP_PLUGIN_DIR.'/'.$this->plugin->name; // Full Path to Plugin Folder
        $this->plugin->url = WP_PLUGIN_URL.'/'.str_replace(basename( __FILE__),"",plugin_basename(__FILE__));
        
        // Dashboard Submodule
        if (!class_exists('WPCubeDashboardWidget')) {
			require_once($this->plugin->folder.'/_modules/dashboard/dashboard.php');
		}
		$dashboard = new WPCubeDashboardWidget($this->plugin); 
		
		// Hooks
        add_action('admin_enqueue_scripts', array(&$this, 'adminScriptsAndCSS'));
        add_action('admin_menu', array(&$this, 'adminPanelsAndMetaBoxes'));
        add_action('save_post', array(&$this, 'save'));
        add_action('plugins_loaded', array(&$this, 'loadLanguageFiles'));
        add_action('edd_sl_store_license', array(&$this, 'setCustomLicenseExpiration'), 99, 4);
    }
    
    /**
    * Register and enqueue any JS and CSS for the WordPress Administration
    */
    function adminScriptsAndCSS() {
    	// JS
    	wp_enqueue_script($this->plugin->name.'-admin', $this->plugin->url.'js/admin.js', array('jquery'), $this->plugin->version, true);
    }
    
    /**
    * Register the plugin settings panel
    */
    function adminPanelsAndMetaBoxes() {
        add_meta_box($this->plugin->name, $this->plugin->displayName, array(&$this, 'displayMetaBox'), 'download', 'side', 'high');
    }
    
    /**
    * Displays a meta box where the user can choose how many days, weeks, months or years licenses for
    * downloads within the Bundle should last for
    */
    function displayMetaBox($postID) {
    	global $post;
    	
    	// Get meta
    	$meta = get_post_meta($post->ID, $this->plugin->name, true);
    	
		// Output
		echo ('	<p>
			  		<strong>'.__('How long are license keys valid for?', $this->plugin->name).'</strong>
			  	</p>
			  	<p>
			  		<input type="number" name="'.$this->plugin->name.'[number]" value="'.(isset($meta['number']) ? $meta['number'] : '').'" min="1" max="999" step="1" />
			  		<select name="'.$this->plugin->name.'[unit]" size="1">
			  			<option value="days"'.((isset($meta['unit']) AND $meta['unit'] == 'days') ? ' selected' : '').'>Days</option>
			  			<option value="weeks"'.((isset($meta['unit']) AND $meta['unit'] == 'weeks') ? ' selected' : '').'>Weeks</option>
			  			<option value="months"'.((isset($meta['unit']) AND $meta['unit'] == 'months') ? ' selected' : '').'>Months</option>
			  			<option value="years"'.((isset($meta['unit']) AND $meta['unit'] == 'years') ? ' selected' : '').'>Years</option>
			  		</select>
			  		'.wp_nonce_field($this->plugin->name, $this->plugin->name.'_nonce').'
			  	</p>');  
    }
    
    /**
    * Save Post Meta
    */
    function save($postID) {
    	// Check Post Type = EDD Download
    	if ($_POST['post_type'] != 'download') {
	    	return;
    	}
    	
    	// Verify nonce
    	if (!isset($_POST[$this->plugin->name.'_nonce'])) {
    		return;
    	}
    	if (!wp_verify_nonce($_POST[$this->plugin->name.'_nonce'], $this->plugin->name)) {
	    	return;
    	}
    	
    	// Update Post Meta if a Bundle
    	if ($_POST['_edd_product_type'] != 'bundle') {
	    	return;
    	}
    	
    	// Update
    	update_post_meta($postID, $this->plugin->name, $_POST[$this->plugin->name]);
    }
    
    /**
	* Loads plugin textdomain
	*/
	function loadLanguageFiles() {
		load_plugin_textdomain($this->plugin->name, false, $this->plugin->name.'/languages/');
	}
	
	/**
	* EDD Software Licensing has already generated a license. We now checn
	* if the license belongs to a bundle, and if so whether that download
	* has a custom expiration defined.
	*
	* If so, we amend the license expiration setting
	*
	* @param int $licenseID License ID
	* @param int $downloadID Download ID
	* @param int $paymentID Payment ID
	* @param string $type Download Type (0|bundle)
	*/
	function setCustomLicenseExpiration($licenseID, $downloadID, $paymentID, $type) {
		// Check this is a bundle
		if ($type != 'bundle') {
			return;
		}
		
		// Get downloads in payment
		$downloads = edd_get_payment_meta_downloads($paymentID);
		foreach ($downloads as $download) {
			$meta = get_post_meta($download['id'], $this->plugin->name, true);
			
			if (!empty($meta['number']) AND is_numeric($meta['number'])) {
				// Amend license expiration
				$edd_sl = edd_software_licensing();
				$result = $edd_sl->set_license_expiration($licenseID, strtotime('+'.$meta['number'].' '.$meta['unit']));
				break;
			}	
		}
	}
}
$eddSLCE = new EDDSLCE();
?>
