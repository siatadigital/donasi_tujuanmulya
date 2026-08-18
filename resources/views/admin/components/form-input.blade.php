<div class="form-group">
  <label>{{ $label }}</label>
  @if ($type === 'textarea')
  <textarea name="{{ $name }}" class="form-control {{ isset($class) ? $class : '' }} {{ isset($error) && $error ? 'is-invalid' : '' }}">{{ isset($value) ? $value : '' }}</textarea>
  @elseif ($type === 'select')
  <select
    name="{{ $name }}"
    class="form-control select2"
    @if (isset($additional))
    @foreach($additional as $key => $attr)
    {!! "$key=\"$attr\"" !!}
    @endforeach
    @endif
    {{ isset($required) && $required ? 'required' : '' }}
    {{ isset($multiple) && $multiple ? 'multiple' : '' }}
  >
    @if (isset($multiple) && $multiple)
    @foreach ($options as $key => $itemValue)
    <option value="{{ $key }}" {{ collect($value)->contains($key) ? 'selected' : '' }}>
      {{ $itemValue }}
    </option>
    @endforeach
    @else
    @foreach ($options as $key => $itemValue)
    <option value="{{ $key }}" {{ isset($value) && $key == $value ? 'selected' : '' }}>
      {{ $itemValue }}
    </option>
    @endforeach
    @endif
  </select>
  @elseif ($type === 'image')
  <div class="image-upload">
    @if (!isset($value))
    <i class="fa fa-plus"></i>
    @endif
    <input type="file" name="{{ $name }}" accept=".jpg,.jpeg,.png" />
    @if (isset($value))
    <img src="{{ $value }}" />
    @endif
  </div>
  @elseif ($type === 'images')
  <div class="image-list">
    @if (isset($value))
    @foreach($value as $index => $item)
    <div class="image-list__item" data-index="{{ $index }}">
        <input type="file" name="{{ $name }}[]" accept=".jpg,.jpeg,.png" />
        <img src="{{ $item }}" />
        <div class="image-list__item__close"><i class="fa fa-times"></i></div>
    </div>
    @endforeach
    @endif
    <div class="image-list__item">
        <i class="fa fa-plus"></i>
        <input type="file" name="{{ $name }}[]" accept=".jpg,.jpeg,.png" />
    </div>
  </div>
  @elseif ($type === 'checkboxes')
  <br>
  @foreach($options as $key => $itemValue)
  <label>
    <input name="{{ $name }}[]" type="checkbox" value="{{ $key }}" {{ collect($value)->contains($key) ? 'checked' : '' }} />
    &nbsp;&nbsp;{{ $itemValue }}
  </label>
  <br>
  @endforeach
  @else
  <input
    type="{{ $type }}"
    name="{{ $name }}"
    class="form-control {{ isset($class) ? $class : '' }} {{ isset($error) && $error ? 'is-invalid' : '' }}"
    value="{{ isset($value) ? $value : '' }}"
    @if (isset($additional))
    @foreach($additional as $key => $attr)
    {!! "$key=\"$attr\"" !!}
    @endforeach
    @endif
    {{ isset($required) && $required ? 'required' : '' }}
   />
  @endif

  @if (isset($error))
  <div class="invalid-feedback" style="display: block;">{{ $error }}</div>
  @endif
</div>
