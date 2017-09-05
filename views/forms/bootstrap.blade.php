<form method="{{ $formMethod }}" action="{{ $formUrl }}" accept-charset="UTF-8">

    <input name="_token" type="hidden" value="{{ csrf_token() }}" id="_token">

    @foreach($fields as $key => $field)
        <div class="clearfix"></div>

        @if($field->type == 'submit' || $field->type == 'reset')
            <div class="formrow">
                <button name="{{ $field->name }}" id="{{ $field->name }}" type="{{ $field->type }}" class="{{ $field->class }}">{{ $field->label }}</button>
            </div>
        @elseif($field->type == 'checkbox')
            <div class="formrow">
                <div>
                    <label for="{{ $field->name }}" class="{{ $field->class }}">
                        <input type="{{ $field->type }}" name="{{ $field->name }}" id="{{ $field->name }}" value="{{ $field->value }}">
                        {{ $field->label }}
                    </label>
                </div>
            </div>
        @elseif($field->type == 'select')
            <div class="formrow">
                <label for="{{ $field->name }}">{{ $field->label }}</label>

                <select id="{{ $field->name }}" name="{{ $field->name }}">
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
            </div>
        @elseif($field->type == 'textarea')
            <div class="formrow @if($field->required) formrow-required @endif">
                <label for="{{ $field->name }}">{{ $field->label }}</label>
                <textarea name="{{ $field->name }}" cols="50" rows="10" id="{{ $field->name }}">{{ $field->value }}</textarea>
            <div>
        @else
            <div class="formrow @if($field->required) formrow-required @endif">
                <label for="{{ $field->name }}">{{ $field->label }}</label>
                <input name="{{ $field->name }}" type="{{ $field->type }}" value="{{ $field->value }}" id="{{ $field->name }}">
            </div>
        @endif
    @endforeach
</form>