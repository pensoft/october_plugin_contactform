<?php namespace Pensoft\ContactForm;

use Backend;
use System\Classes\PluginBase;
use Pensoft\ContactForm\Components\Form;

/**
 * ContactForm Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     */
    public function pluginDetails(): array
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
     */
    public function register(): void
    {

    }

    /**
     * Register Plugin Mail Templates
     */
    public function registerMailTemplates(): array
    {
        return [];
    }

    /**
     * Registers any front-end components implemented in this plugin.
     */
    public function registerComponents(): array
    {
        return [
            Form::class => 'SimpleContactFormComponent',
        ];
    }

    public function registerPermissions(): array
    {
        return [
            'pensoft.contactform.access' => [
                'tab' => 'Contact form',
                'label' => 'Manage contactform'
            ],
        ];
    }

    public function registerNavigation(): array
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