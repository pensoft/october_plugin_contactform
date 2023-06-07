<?php namespace Pensoft\ContactForm\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Validator;
use Redirect;
use October\Rain\Support\Facades\Flash;
use Pensoft\ContactForm\Models\Recipientsgroup;
use Pensoft\ContactForm\Models\Data as MailsData;
use Multiwebinc\Recaptcha\Validators\RecaptchaValidator;
use ValidationException;

class Form extends ComponentBase
{
    public function componentDetails()
    {
        return [
            'name'        => 'Contact form Component',
            'description' => 'Pensoft Contact form'
        ];
    }

    public function defineProperties()
    {
        return [
			'templates' => [
				'title' => 'Select templates',
				'type' => 'dropdown',
				'default' => 'template1'
			],
			'recaptcha_key' => [
				'title' => 'Recaptcha site key',
				'type' => 'string',
				'default' => ''
			],
			'message_label' => [
				'title' => 'Message field label',
				'type' => 'string',
				'default' => 'Message'
			],
		];
    }

	public function getTemplatesOptions()
	{
		return [
			'template1' => 'Template 1',
			'template2' => 'Template 2',
		];
	}

    public function onRun(){
		$this->page['categories'] = $this->categories();
		$this->page['countries'] = $this->countries();
	}



	public function countries()
	{
		return Recipientsgroup::where('type', 1)->orderByRaw("(title ilike '%country specific%') DESC")->orderBy('title')->get();
	}

	public function categories()
	{
		return Recipientsgroup::where('type', 2)->orderBy('title')->get();
	}

    public function onSubmit(){
		$validator = Validator::make(
			[
				'first_name' => \Input::get('first_name'),
				'last_name' => \Input::get('last_name'),
				'email' => \Input::get('email'),
				'country' => \Input::get('country'),
				'category' => \Input::get('category'),
				'message' => \Input::get('message'),
				'g-recaptcha-response' => \Input::get('g-recaptcha-response'),
			],
			[
				'first_name' => 'required|string|min:2',
				'last_name' => 'required|string|min:2',
				'email' => 'required|email',
//				'country' => 'required',
//				'category' => 'required',
				'message' => 'required|string|min:5',
				'g-recaptcha-response' => [
					'required',
					new RecaptchaValidator,
				],
			]
		);

		if($validator->fails()){
			Flash::error($validator->messages()->first());
		}else{

			$category = \Input::get('category');



			$categoryEmails = [];
			if((int)$category){
				$categoryData = Recipientsgroup::where('id', (int)$category)->first()->toArray();
				$categoryEmails = explode(',', $categoryData['emails']);
				$catName = Recipientsgroup::find($category);
			}else{
				$categoryData = Recipientsgroup::first()->toArray();
				$categoryEmails = explode(',', $categoryData['emails']);
				$catName = Recipientsgroup::first();
			}


			$recipients = array_unique($categoryEmails);

			// These variables are available inside the message as Twig
			$vars = [
				'first_name' => \Input::get('first_name'),
				'last_name' => \Input::get('last_name'),
				'body' => \Input::get('message'),
				'email' => \Input::get('email')
			];

			// send mail to user submitting the form
			Mail::send('pensoft.contactform::mail.autoreply', $vars, function($message) {

				$message->to(\Input::get('email'), \Input::get('first_name').' '.\Input::get('last_name'));

			});

			// send mail to group of recipients country email relevant values

//			$country = \Input::get('country');

			$replyToMail = \Input::get('email');
//
//			$countryEmails = [];
//			if((int)$country){
//				$countryData = Recipientsgroup::where('id', (int)$country)->first()->toArray();
//				$countryEmails = explode(',', $countryData['emails']);
//			}
//
//			$recipients = array_unique($countryEmails);

			foreach($recipients as $mail){
				Mail::send('pensoft.contactform::mail.notification', $vars, function($message)  use ($mail, $replyToMail) {

					$message->to(trim($mail));
					$message->replyTo($replyToMail);

				});

//				Mail::sendTo($mail, 'pensoft.forms::mail.notification', $vars);
			}

            $data = new MailsData();
            $data->email =  $vars['email'];
            $data->first_name = $vars['first_name'];
            $data->last_name = $vars['last_name'];
            $data->message = $vars['body'];
            $data->form = $this->property('message_label');
            $data->save();

			Flash::success('Thank you');

		}


	}
}
