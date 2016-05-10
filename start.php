<?php
/**
 * Start file for this plugin
 */
@include_once(dirname(__FILE__) . '\vendor\autoload.php');

// default elgg event handlers
elgg_register_event_handler('init', 'system', 'js_packer_init');

/**
 * called when the Elgg system get initialized
 *
 * @return void
 */
function js_packer_init() {
	elgg_register_plugin_hook_handler('simplecache:generate', 'js', 'js_packer_pack');
}

/**
 * Packs JS views by handling the "simplecache:generate" hook
 *
 * @param string $hook    The name of the hook
 * @param string $type    View type (css, js, or unknown)
 * @param string $content Content of the view
 * @param array  $params  Array of parameters
 *
 * @return string|null View content packed (if js type)
 * @access private
 */
function js_packer_pack($hook, $type, $content, $params) {
	$view = elgg_extract('view', $params);
	
	if (preg_match('~[\.-]pack\.~', $view)) {
		// bypass packing if already packed
		return;
	}
	
	if (elgg_get_config('simplecache_minify_js')) {
		$myPacker = new \JavaScriptPacker($content, 62, true, false);
		return $myPacker->pack();
	}
}
