<?php namespace Pensoft\ContactForm\Models;

use Model;

/**
 * Model
 */
class Data extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    use \October\Rain\Database\Traits\SoftDelete;

    protected $dates = ['deleted_at'];


    /**
     * @var string The database table used by the model.
     */
    public $table = 'pensoft_contactform_data';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
