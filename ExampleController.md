```php
<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Forms\ContactForm;

class HomeController extends Controller
{

    public function index(ContactForm $form)
    {
        $contactForm = $form->render();

        return view('welcome', [
            'contactForm' => $contactForm,
            'success' => false
        ]);
    }

    public function post(ContactForm $form)
    {
        $input = request()->all();

        return $form->save($input);
    }

    public function success(ContactForm $form)
    {
        $contactForm = $form->render();

        return view('welcome', [
            'contactForm' => $contactForm,
            'success' => true
        ]);
    }

}

```