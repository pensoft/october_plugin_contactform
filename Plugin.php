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
//		return [
//			'pensoft.contactform::mail.autoreply' => 'pensoft.contactform::lang.mail.templates.autoreply',
//			'pensoft.contactform::mail.notification' => 'pensoft.contactform::lang.mail.templates.notification',
//		];
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

    public function registerPermissions()
    {
        return [
            'pensoft.contactform.access' => [
                'tab' => 'Contact form',
                'label' => 'Manage contactform'
            ],
        ];
    }

    public function registerNavigation()
    {
        return [
            'contactform' => [
                'label'       => 'Contact form',
                'url'         => \Backend::url('pensoft/contactform/recipientsgroup'),
                'icon'        => 'icon-external-link-square',
                'permissions' => ['pensoft.contactform.*'],
                'sideMenu' => [
                    'side-menu-item2' => [
                        'label'       => 'Mails',
                        'url'         => \Backend::url('pensoft/contactform/mails'),
                        'icon'        => 'icon-envelope',
                        'permissions' => ['pensoft.contactform.*'],
                    ],
                    'side-menu-item' => [
                        'label'       => 'Recipients groups',
                        'url'         => \Backend::url('pensoft/contactform/recipientsgroup'),
                        'icon'        => 'icon-star-o',
                        'permissions' => ['pensoft.contactform.*'],
                    ],

                ]
            ],
        ];
    }
}

