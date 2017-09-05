```php
<?php

namespace App\Forms;

use Ferrisbane\Laraformer\Laraformer;

class ContactForm extends Laraformer {

    protected $failedRedirectRoute = 'home.index';
    protected $successRedirectRoute = 'home.success';

    public function formUrl()
    {
        return route('home.post');
    }

    public function rules()
    {
        return [
            "first_name" => "required",
            "last_name" => "required",
            "email" => "confirmed|required",
            "email_confirmation" => "required",
            "phone_number" => "required",
            "query" => "required",
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'first_name.required' => 'The first name field is required',
            'last_name.required' => 'The last name field is required',
        ];
    }

    /**
     * Create the form.
     *
     * @return void
     */
    public function make($form)
    {
        $form->text('first_name')
             ->label('First Name')
             ->requiredWith('last_name')
             ->required();
        $form->text('last_name')
             ->label('Last Name')
             ->required();

        $form->email('email')
             ->label('Email')
             ->confirmed()
             ->required();
        $form->email('email_confirmation')
             ->label('Confirm Email')
             ->required();

        $form->text('phone_number')
             ->label('Phone No.')
             ->required();

        $form->textarea('query')
             ->label('Queries')
             ->required();

        $form->checkbox('email_me')
             ->label('Please email me updates');
        $form->select('contact_me_by')
             ->label('Contact me using')
             ->values([
                '' => 'Please Select...',
                'email' => 'My email',
                'phone' => 'My phone',
             ])
             ->value('');

        $form->submit('submit')
             ->label('Click here to contact us');
    }

    public function passedValidation($validator)
    {
        dd('Validation passed, save to db, send to service');
    }

}

```