<?php namespace Pensoft\ContactForm\Controllers;

use Backend\Classes\Controller;
use Backend\Behaviors\ListController;
use Backend\Behaviors\FormController;
use BackendMenu;

class Mails extends Controller
{
    public $implement = [
        ListController::class,
        FormController::class,
    ];

    public string $listConfig = 'config_list.yaml';
    public string $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Pensoft.ContactForm', 'contactform');
    }
}