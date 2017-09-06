<form method="{{ $form->method }}" action="{{ $form->url }}" class="{{ $form->class }}" accept-charset="UTF-8">

    <input name="_token" type="hidden" value="{{ csrf_token() }}" id="_token">

    @foreach($fields as $key => $field)
        <div class="{{ $field->groupClass ?: 'form-group' }} @if($field->required) required @endif">

            @if($field->type == 'submit' || $field->type == 'reset')
                @if ($field->inputGroupClass) <div class="{{ $field->inputGroupClass }}"> @endif
                    <button name="{{ $field->name }}" id="{{ $field->name }}" type="{{ $field->type }}" class="btn {{ $field->class }}">{{ $field->label }}</button>
                @if ($field->inputGroupClass) </div> @endif

            @elseif($field->type == 'checkbox')
                <label for="{{ $field->name }}" class="{{ $field->labelClass }}">
                    <input type="{{ $field->type }}" name="{{ $field->name }}" id="{{ $field->name }}" value="{{ $field->value }}" class="{{ $field->class }}">
                    {{ $field->label }}
                </label>
            @elseif($field->type == 'select')
                <label for="{{ $field->name }}" class="{{ $field->labelClass }}">{{ $field->label }}</label>

                @if ($field->inputGroupClass) <div class="{{ $field->inputGroupClass }}"> @endif
                    <select id="{{ $field->name }}" name="{{ $field->name }}" class="form-control {{ $field->class }}">
                        @if( ! empty($field->values))
                            @foreach($field->values as $key => $value)
                                @if(is_array($value))
                                    <optgroup label="{{ $key }}">
                                        @foreach($value as $subKey => $subValue)
                                            <option value="{{ $subKey }}" @if($field->value == $subKey) selected="selected" @endif >
                                                {{ $subValue }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @else
                                    <option value="{{ $key }}" @if($field->value == $key) selected="selected" @endif >
                                        {{ $value }}
                                    </option>
                                @endif
                            @endforeach
                        @endif
                    </select>
                @if ($field->inputGroupClass) </div> @endif
            @elseif($field->type == 'textarea')
                <label for="{{ $field->name }}" class="{{ $field->labelClass }}">{{ $field->label }}</label>

                @if ($field->inputGroupClass) <div class="{{ $field->inputGroupClass }}"> @endif
                    <textarea
                        name="{{ $field->name }}"
                        id="{{ $field->name }}"
                        class="form-control {{ $field->class }}"
                        rows="{{ $field->rows ?: 10 }}"
                        rows="{{ $field->cols ?: 50 }}"
                    >{{ $field->value }}</textarea>
                @if ($field->inputGroupClass) </div> @endif
            @else
                <label for="{{ $field->name }}" class="{{ $field->labelClass }}">{{ $field->label }}</label>

                @if ($field->inputGroupClass) <div class="{{ $field->inputGroupClass }}"> @endif
                    <input name="{{ $field->name }}" type="{{ $field->type }}" value="{{ $field->value }}" id="{{ $field->name }}" class="form-control {{ $field->class }}">
                @if ($field->inputGroupClass) </div> @endif
            @endif
        </div>
    @endforeach
</form>