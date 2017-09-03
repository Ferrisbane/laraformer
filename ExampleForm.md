<?php

namespace App\Form;

use Ferrisbane\Laraformer\Laraformer;

class ContactForm extends Laraformer {

    /**
     * Make the form.
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

}
