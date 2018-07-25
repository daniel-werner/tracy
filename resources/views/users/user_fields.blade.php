@csrf

<div class="form-group row">
    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

    <div class="col-md-6">
        <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ old('name', $user->name ?? '') }}" required autofocus>

        @if ($errors->has('name'))
            <span class="invalid-feedback">
            <strong>{{ $errors->first('name') }}</strong>
        </span>
        @endif
    </div>
</div>

@can('admin')
<div class="form-group row">
    <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('Role') }}</label>

    <div class="col-md-6">
        <select id="role_id" class="form-control{{ $errors->has('role_id') ? ' is-invalid' : '' }}" name="role_id" required autofocus>
            <option value="">{{__('Please select')}}</option>
            @foreach( \App\User::getRoles() as $id => $label )
                <option value="{{$id}}" @if(old('role_id', $user->role_id ?? '') == $id) selected @endif>{{$label}}</option>
            @endforeach
        </select>

        @if ($errors->has('role_id'))
            <span class="invalid-feedback">
            <strong>{{ $errors->first('role_id') }}</strong>
        </span>
        @endif
    </div>
</div>
@endcan

<div class="form-group row">
    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

    <div class="col-md-6">
        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email', $user->email ?? '') }}" required>

        @if ($errors->has('email'))
            <span class="invalid-feedback">
            <strong>{{ $errors->first('email') }}</strong>
        </span>
        @endif
    </div>
</div>
<div class="form-group row">
    <label for="role" class="col-md-4 col-form-label text-md-right">{{ __('Timezone') }}</label>

    <div class="col-md-6">
        <select id="role_id" class="form-control{{ $errors->has('timezone') ? ' is-invalid' : '' }}" name="timezone" required autofocus>
            <option value="">{{__('Please select')}}</option>
            @foreach( $timezones as $id => $label )
                <option value="{{$label}}" @if(old('role_id', $user->timezone ?? '') == $label) selected @endif>{{$label}}</option>
            @endforeach
        </select>

        @if ($errors->has('timezone'))
            <span class="invalid-feedback">
            <strong>{{ $errors->first('timezone') }}</strong>
        </span>
        @endif
    </div>
</div>
<div class="form-group row">
    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

    <div class="col-md-6">
        <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password">

        @if ($errors->has('password'))
            <span class="invalid-feedback">
            <strong>{{ $errors->first('password') }}</strong>
        </span>
        @endif
    </div>
</div>

<div class="form-group row">
    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

    <div class="col-md-6">
        <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
    </div>
</div>

<div class="form-group row mb-0">
    <div class="col-md-6 offset-md-4">
        <button type="submit" class="btn btn-primary">
            {{ __('Save') }}
        </button>
    </div>
</div>