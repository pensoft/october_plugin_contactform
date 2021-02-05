<?php namespace Pensoft\ContactForm;

use Backend;
use System\Classes\PluginBase;

/**
 * ContactForm Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'ContactForm',
            'description' => 'No description provided yet...',
            'author'      => 'Pensoft',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

	/**
	 * Register Plugin Mail Templates
	 *
	 * @return array
	 */
	public function registerMailTemplates()
	{
		return [
			'pensoft.forms::mail.autoreply' => 'pensoft.forms::lang.mail.templates.autoreply',
			'pensoft.forms::mail.notification' => 'pensoft.forms::lang.mail.templates.notification',
		];
	}

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {

        return [
            'Pensoft\ContactForm\Components\Form' => 'SimpleContactFormComponent',
        ];
    }
}
