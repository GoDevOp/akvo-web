<?php
namespace JsonData\Admin\Controller;

/**
 * Description of Home
 *
 * @author Jayawi Perera
 */
class LimitedHome extends Base {

	public function initialise () {

		$sHookName = add_menu_page(
				'Json Data',
				'Feed',
				'manage_options',
				'AWPR_limited_home',
				array($this, 'page'),
				null,
				null
			);

//		add_action('admin_print_styles', array($this, 'enqueueAdminIconCss'));

		add_action('admin_print_scripts-' . $sHookName, array($this, 'enqueueBootstrapJs'));
		add_action('admin_print_styles-' . $sHookName, array($this, 'enqueueBootstrapCss'));
		add_action('admin_print_styles-' . $sHookName, array($this, 'enqueueFontAwesomeCss'));
		add_action('admin_print_styles-' . $sHookName, array($this, 'enqueueAdminCss'));

	}

	public function page () {

		include_once JsonData_Plugin_Dir . '/src/Admin/View/scripts/home/limited_home.phtml';

	}

}