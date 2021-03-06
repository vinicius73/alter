<?php
/**
 * Created by PhpStorm.
 * User: sergiovilar
 * Date: 03/07/14
 * Time: 14:46
 */

/**
 * Class Loader
 *
 * This class load all the user files
 */
class Loader
{

	private $app;
	private $folders = array('model', 'controller', 'view', 'option');

	function __construct($app)
	{
		$this->app = $app;
		$this->load();
	}

	private function load()
	{

		// User Models, Views and Controllers
		$rw = new RegisterMetabox();

		$folders = apply_filters('alter_folders', $this->folders);

		foreach ($folders as $folder) {

			foreach (glob(THEME_ABSOLUTE_PATH . '/' . $folder . "/*.php") as $file) {

				$name = str_replace('.php', '', $file);
				$name_arr = explode('/', $name);
				$name = $name_arr[count($name_arr) - 1];;

				require $file;

				$instance = new $name;

				// Register the meta-boxes if is a model
				if (is_subclass_of($instance, 'AppModel')) {
					$this->app->registerModel($instance);
					$rw->add($instance->getPostType(), $instance->getFields());
				}

				if (is_subclass_of($instance, 'OptionPage')) {
					$this->app->registerOption($instance);
				}

			}
		}

		$rw->register();

	}

} 