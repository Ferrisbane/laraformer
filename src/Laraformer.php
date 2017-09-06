<?php

namespace Ferrisbane\Laraformer;

use Ferrisbane\Laraformer\Contracts\Laraformer as LaraformerC;
use Illuminate\Support\Fluent;
// use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Foundation\Http\FormRequest;

class Laraformer extends FormRequest implements LaraformerC
{

    /**
     * The view.
     *
     * @var string
     */
    protected $view = 'laraformer::forms.bootstrap';

    protected $formUrl = false;
    protected $formMethod = 'POST';
    protected $formClass = '';

    protected $validate = false;
    protected $validationFailed = false;

    /**
     * The commands that should be run for the table.
     *
     * @var array
     */
    protected $fields = [];

    /**
     * The commands that should be run for the table.
     *
     * @var array
     */
    protected $validationRules = [
        'accepted',
        'activeUrl',
        'after',
        'afterOrEqual',
        'alpha',
        'alphaDash',
        'alphaNum',
        'array',
        'before',
        'beforeOrEqual',
        'between',
        'boolean',
        'confirmed',
        'date',
        'dateFormat',
        'different',
        'digits',
        'digitsBetween',
        'dimensions',
        'distinct',
        'email',
        'exists',
        'file',
        'filled',
        'image',
        'in',
        'inArray',
        'integer',
        'ip',
        'ipv4',
        'ipv6',
        'json',
        'max',
        'mimetypes',
        'mimes',
        'min',
        'nullable',
        'notIn',
        'numeric',
        'present',
        'regex',
        'required',
        'requiredIf',
        'requiredUnless',
        'requiredWith',
        'requiredWithAll',
        'requiredWithout',
        'requiredWithoutAll',
        'same',
        'size',
        'sometimes',
        'string',
        'timezone',
        'unique',
        'url',
    ];

    /**
     * The commands that should be run for the table.
     *
     * @var array
     */
    protected $rules = [];

    public function __construct()
    {
    }

    public function render($view = false)
    {
        $this->make($this);

        if ($view) {
            $this->view = $view;
        }

        $form = new Fluent([
            'url' => $this->formUrl(),
            'method' => $this->formMethod(),
            'class' => $this->formClass()
        ]);

        return view($this->view, [
            'form' => $form,
            'fields' => $this->fields
        ]);
    }

    public function formUrl()
    {
        return $this->formUrl;
    }

    public function formMethod()
    {
        return $this->formMethod;
    }

    public function formClass()
    {
        return $this->formClass;
    }

    public function make($form)
    {
        return null;
    }

    public function save($input)
    {
        $this->validate = true;
        return $this->validate();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->make($this);

        return $this->getValidationRules();
    }


    /**
     * Validate the class instance.
     *
     * @return void
     */
    public function validate()
    {
        if ($this->validate) {
            $this->prepareForValidation();

            $instance = $this->getValidatorInstance();

            if ( ! $this->passesAuthorization()) {
                $this->validationFailed = true;
                return $this->failedAuthorization();
            } elseif ( ! $instance->passes()) {
                $this->validationFailed = true;
                return $this->failedValidation($instance);
            } elseif ($instance->passes()) {
                $this->validationFailed = false;
                return $this->passedValidation($instance);
            }
        }
    }

    /**
     * Handle a successful validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function passedValidation($validator)
    {
        return redirect($this->getRedirectUrl())->with('success', true);
    }

    /**
     * Get the URL to redirect to on a validation error.
     *
     * @return string
     */
    protected function getRedirectUrl()
    {
        $url = $this->redirector->getUrlGenerator();

        $prefix = $this->validationFailed ? 'failed' : 'success';

        if ($this->{$prefix.'Redirect'}) {
            return $url->to($this->{$prefix.'Redirect'});
        } elseif ($this->{$prefix.'RedirectRoute'}) {
            return $url->route($this->{$prefix.'RedirectRoute'});
        } elseif ($this->{$prefix.'RedirectAction'}) {
            return $url->action($this->{$prefix.'RedirectAction'});
        }

        return $url->previous();
    }


    protected function getValidationRules()
    {
        $validate = array_filter($this->fields, function ($column) {
            $fieldValidationRules = array_where($column->toArray(), function ($value, $key) {
                return in_array($key, $this->validationRules);
            });

            if ( ! empty($fieldValidationRules)) {
                $rules = [];

                foreach ($fieldValidationRules as $rule => $value) {
                    $ruleString = $rule;

                    if ($value !== true) {
                        $ruleString .= ':'.$value;
                    }

                    $rules[] = $ruleString;
                }

                $this->rules[$column->name] = implode('|', $rules);
                return true;
            }

            return false;
        });

        return $this->rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Add a new field to the form.
     *
     * @param  string  $type
     * @param  string  $name
     * @param  array   $parameters
     * @return \Illuminate\Support\Fluent
     */
    public function addField($type, $name, array $parameters = [])
    {
        $this->fields[] = $field = new Fluent(
            array_merge(compact('type', 'name'), $parameters)
        );

        return $field;
    }


    /**
     * Create a new text column on the form.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function text($column)
    {
        return $this->addField('text', $column);
    }

    /**
     * Create a new password column on the form.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function password($column)
    {
        return $this->addField('password', $column);
    }

    /**
     * Create a new submit column on the form.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function submit($column)
    {
        return $this->addField('submit', $column);
    }

    /**
     * Create a new reset column on the form.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function reset($column)
    {
        return $this->addField('reset', $column);
    }

    /**
     * Create a new radio column on the form.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function radio($column)
    {
        return $this->addField('radio', $column);
    }

    /**
     * Create a new checkbox column on the form.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function checkbox($column)
    {
        return $this->addField('checkbox', $column);
    }

    /**
     * Create a new button column on the form.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function button($column)
    {
        return $this->addField('button', $column);
    }

    /**
     * Create a new color column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function color($column)
    {
        return $this->addField('color', $column);
    }

    /**
     * Create a new date column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function date($column)
    {
        return $this->addField('date', $column);
    }

    /**
     * Create a new datetimeLocal column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function datetimeLocal($column)
    {
        return $this->addField('datetime-local', $column);
    }

    /**
     * Create a new email column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function email($column)
    {
        return $this->addField('email', $column);
    }

    /**
     * Create a new month column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function month($column)
    {
        return $this->addField('month', $column);
    }

    /**
     * Create a new number column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function number($column)
    {
        return $this->addField('number', $column);
    }

    /**
     * Create a new range column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function range($column)
    {
        return $this->addField('range', $column);
    }

    /**
     * Create a new search column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function search($column)
    {
        return $this->addField('search', $column);
    }


    /**
     * Create a new tel column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function tel($column)
    {
        return $this->addField('tel', $column);
    }

    /**
     * Create a new time column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function time($column)
    {
        return $this->addField('time', $column);
    }

    /**
     * Create a new url column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    // public function url($column)
    // {
    //     return $this->addField('url', $column);
    // }

    /**
     * Create a new week column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function week($column)
    {
        return $this->addField('week', $column);
    }

    /**
     * Create a new select column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function select($column)
    {
        return $this->addField('select', $column);
    }

    /**
     * Create a new textarea column on the table.
     *
     * @param  string  $column
     * @return \Illuminate\Support\Fluent
     */
    public function textarea($column)
    {
        return $this->addField('textarea', $column);
    }


}
