<?php namespace Pensoft\ContactForm\Components;

use Cms\Classes\ComponentBase;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Validator;
use Redirect;
use October\Rain\Support\Facades\Flash;
use Pensoft\ContactForm\Models\Recipientsgroup;
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
        return [];
    }

    public function onRun(){
		$this->page['categories'] = $this->categories();
		$this->page['countries'] = $this->countries();
	}



	public function countries()
	{
		return Recipientsgroup::where('type', 1)->orderBy('title')->get();
	}

	public function categories()
	{
		return Recipientsgroup::where('type', 2)->orderBy('title')->get();
	}

    public function onSubmit(){
		$validator = Validator::make(
			[
				'first_name' => Input::get('first_name'),
				'last_name' => Input::get('last_name'),
				'email' => Input::get('email'),
				'country' => Input::get('country'),
				'category' => Input::get('category'),
				'message' => Input::get('message'),
				'g-recaptcha-response' => Input::get('g-recaptcha-response'),
			],
			[
				'first_name' => 'required|string|min:2',
				'last_name' => 'required|string|min:2',
				'email' => 'required|email',
				'country' => 'required',
				'category' => 'required',
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
			// These variables are available inside the message as Twig
			$vars = [
				'first_name' => Input::get('first_name'),
				'last_name' => Input::get('last_name'),
				'email' => Input::get('email'),
				'body' => Input::get('message'),
				'subject' => Input::get('category'),
				'email' => Input::get('email')
			];

//			// send mail to user submitting the form
			Mail::send('pensoft.contactform::mail.autoreply', $vars, function($message) {

				$message->to(Input::get('email'), Input::get('first_name').' '.Input::get('last_name'));

			});

			// send mail to group of recipients country email relevant values

			$country = Input::get('country');
			$replyToMail = Input::get('email');

			$countryEmails = [];
			if((int)$country){
				$countryData = Recipientsgroup::where('id', (int)$country)->first()->toArray();
				$countryEmails = explode(',', $countryData['emails']);
			}

			$recipients = array_unique($countryEmails);

			foreach($recipients as $mail){
				Mail::send('pensoft.contactform::mail.notification', $vars, function($message)  use ($mail) {

					$message->to($mail);
					$message->replyTo($replyToMail);

				});

//				Mail::sendTo($mail, 'pensoft.forms::mail.notification', $vars);
			}

			Flash::success('Thank you');

		}


	}
}
