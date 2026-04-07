<?php namespace Pensoft\ContactForm\Components;

use Cms\Classes\ComponentBase;
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
    public function componentDetails(): array
    {
        return [
            'name'        => 'Contact form Component',
            'description' => 'Pensoft Contact form'
        ];
    }

    public function defineProperties(): array
    {
        return [
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

    public function onRun(): void
    {
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

    public function onSubmit(): void
    {
        $validator = Validator::make(
            [
                'first_name' => request()->input('first_name'),
                'last_name' => request()->input('last_name'),
                'email' => request()->input('email'),
                'country' => request()->input('country'),
                'category' => request()->input('category'),
                'organisation' => request()->input('organisation'),
                'message' => request()->input('message'),
                'g-recaptcha-response' => request()->input('g-recaptcha-response'),
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

            $category = request()->input('category');



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
                'first_name' => request()->input('first_name'),
                'last_name' => request()->input('last_name'),
                'organisation' => request()->input('organisation'),
                'subject' => request()->input('subject'),
                'body' => request()->input('message'),
                'email' => request()->input('email')
            ];

            // send mail to user submitting the form
            Mail::send('pensoft.contactform::mail.autoreply', $vars, function($message) {

                $message->to(request()->input('email'), request()->input('first_name').' '.request()->input('last_name'));

            });

            // send mail to group of recipients country email relevant values

//			$country = request()->input('country');

            $replyToMail = request()->input('email');
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
            $data->organisation = $vars['organisation'];
            $data->subject = $vars['subject'];
            $data->message = $vars['body'];
            $data->form = $this->property('message_label');
            $data->save();

            Flash::success('Thank you');

        }


    }
}