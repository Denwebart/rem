<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| The following language lines contain the default error messages used by
	| the validator class. Some of these rules have multiple versions such
	| as the size rules. Feel free to tweak each of these messages here.
	|
	*/

	"accepted"             => "The :attribute must be accepted.",
	"active_url"           => "The :attribute is not a valid URL.",
	"after"                => "The :attribute must be a date after :date.",
	"alpha"                => "The :attribute may only contain letters.",
	"alpha_dash"           => "The :attribute may only contain letters, numbers, and dashes.",
	"alpha_num"            => "The :attribute may only contain letters and numbers.",
	"array"                => "The :attribute must be an array.",
	"before"               => "The :attribute must be a date before :date.",
	"between"              => [
		"numeric" => "The :attribute must be between :min and :max.",
		"file"    => "The :attribute must be between :min and :max kilobytes.",
		"string"  => "The :attribute must be between :min and :max characters.",
		"array"   => "The :attribute must have between :min and :max items.",
	],
	"boolean"              => "The :attribute field must be true or false.",
	"confirmed"            => "The :attribute confirmation does not match.",
	"date"                 => "The :attribute is not a valid date.",
	"date_format"          => "The :attribute does not match the format :format.",
	"different"            => "The :attribute and :other must be different.",
	"digits"               => "The :attribute must be :digits digits.",
	"digits_between"       => "The :attribute must be between :min and :max digits.",
	"email"                => "Неверный адрес электронной почты.",
	"exists"               => "The selected :attribute is invalid.",
	"image"                => "The :attribute must be an image.",
	"in"                   => "The selected :attribute is invalid.",
	"integer"              => "Значение в поле должно быть целым числом.",
	"ip"                   => "Неверный ip-адрес.",
	"max"                  => [
		"numeric" => "Значение не может быть больше :max.",
		"file"    => "The :attribute may not be greater than :max kilobytes.",
		"string"  => "Поле должно содержать не более :max символов.",
		"array"   => "The :attribute may not have more than :max items.",
	],
	"mimes"                => "Файл должен быть одним из следующих типов: :values.",
	"min"                  => [
		"numeric" => "Значение не может быть меньше :min.",
		"file"    => "The :attribute must be at least :min kilobytes.",
		"string"  => "Поле должно содержать не менее :min символов.",
		"array"   => "The :attribute must have at least :min items.",
	],
	"not_in"               => "The selected :attribute is invalid.",
	"numeric"              => "Значение в поле должно быть числом.",
	"regex"                => "Поле содержит недопустимые символы.",
	"required"             => "Поле обязательно для заполнения.",
	"required_if"          => "The :attribute field is required when :other is :value.",
	"required_with"        => "The :attribute field is required when :values is present.",
	"required_with_all"    => "The :attribute field is required when :values is present.",
	"required_without"     => "The :attribute field is required when :values is not present.",
	"required_without_all" => "The :attribute field is required when none of :values are present.",
	"same"                 => "The :attribute and :other must match.",
	"size"                 => [
		"numeric" => "The :attribute must be :size.",
		"file"    => "The :attribute must be :size kilobytes.",
		"string"  => "The :attribute must be :size characters.",
		"array"   => "The :attribute must contain :size items.",
	],
	"unique"               => "The :attribute has already been taken.",
	"url"                  => "The :attribute format is invalid.",
	"timezone"             => "The :attribute must be a valid zone.",

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Language Lines
	|--------------------------------------------------------------------------
	|
	| Here you may specify custom validation messages for attributes using the
	| convention "attribute.rule" to name the lines. This makes it quick to
	| specify a specific custom language line for a given attribute rule.
	|
	*/

	'custom' => [
		'name' => [
			'regex' => 'Поле может содержать только буквы и пробелы.',
		],
		'alias' => [
			'regex' => 'Поле может содержать только латинские буквы, дефис и цыфры.',
			'unique' => 'Страница с таким алиасом уже существует'
		],
		'parent_id' => [
			'required' => 'Выбор категории обязателен.',
		],
		'login' => [
			'required_without_all' => 'Введите логин или email.',
			'unique' => 'Пользователь с таким логином уже зарегистрирован.',
			'regex' => 'Можно использовать только буквы, цифры и знак дефиса.',
		],
		'email' => [
			'unique' => 'Пользователь с таким email уже зарегистрирован.',
		],
		'password' => [
			'required' => 'Введите пароль.',
			"min" => "Слишком короткий пароль (минимум :min символов).",
			"max" => "Слишком длинный пароль (минимум :max символов).",
			"confirmed" => "Пароли не совпадают. Повторите попытку.",
		],
		'newpassword' => [
			'required' => 'Введите новый пароль.',
			"min" => "Слишком короткий пароль (минимум :min символов).",
			"max" => "Слишком длинный пароль (минимум :max символов).",
			"confirmed" => "Пароли не совпадают. Повторите попытку.",
		],
		'password_confirmation' => [
			"min" => "Слишком короткий пароль (минимум :min символов).",
			"confirmed" => "Пароли не совпадают. Повторите попытку.",
		],
		'user_name' => [
			'required_without_all' => 'Поле обязательно для заполнения.',
		],
		'user_email' => [
			'required_without_all' => 'Поле обязательно для заполнения.',
		],
		'title' => [
			'unique' => 'Награда с таким названием уже существует.',
		],
		'position' => [
			'unique' => 'Эта позиция уже занята.',
		],
		'g-recaptcha-response' => [
			'required_without_all' => 'Поле обязательно для неавторизованных пользователей.',
		],
		'image' => [
			"mimes" => "Изображение может быть в формате :values.",
		],
		'avatar' => [
			"mimes" => "Изображение может быть в формате :values.",
		],
	],

	/*
	|--------------------------------------------------------------------------
	| Custom Validation Attributes
	|--------------------------------------------------------------------------
	|
	| The following language lines are used to swap attribute place-holders
	| with something more reader friendly such as E-Mail Address instead
	| of "email". This simply helps us make messages a little cleaner.
	|
	*/

	'attributes' => [],

];
