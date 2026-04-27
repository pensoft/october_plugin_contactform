<?php namespace Pensoft\ContactForm\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Backend\Behaviors\FormController;
use Backend\Behaviors\ListController;

/**
 * Recipientsgroup Back-end Controller
 */
class Recipientsgroup extends Controller
{
    /**
     * @var array Behaviors that are implemented by this controller.
     */
    public $implement = [
        FormController::class,
        ListController::class,
    ];

    /**
     * @var string Configuration file for the `FormController` behavior.
     */
    public string $formConfig = 'config_form.yaml';

    /**
     * @var string Configuration file for the `ListController` behavior.
     */
    public string $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Pensoft.ContactForm', 'contactform', 'recipientsgroup');
    }
}