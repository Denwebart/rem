<?php

return [
	// Url сайта без "http://"
	'siteUrl' => 'avtorem.dev',

	'metaAuthor' => '',
	'metaRobots' => 'noindex, nofollow',
	'metaCopyright' => '',

	'adminEmail' => 'denwebart@yandex.ua',
	'adminName' => 'Admin',
	'contactSubjectToUser' => 'Копия сообщения с сайта avtorem.info',

	'journalAlias' => 'bortovoj-zhurnal',

	//images
	'bannedImage' => '/images/banned.png',

	'defaultImage' => '/images/default-image.jpg',
	'mini_defaultImage' => '/images/mini_default-image.jpg',

	'defaultAvatar' => '/images/default-avatar.png',
	'mini_defaultAvatar' => '/images/mini_default-avatar.png',

	'defaultHonorImage' => '/images/default-honor-image.png',

	'defaultTagImage' => '/images/default-tag-image.png',
	//end images

	'defaultPublishedTime' => '10:00:00',

	'numberOfUserImages' => 5,

	// nocaptcha
	'nocaptchaSecret'  => getenv('NOCAPTCHA_SECRET') ?: '6LdhyQETAAAAAIYg3kSuajrmL5HZoQ7OVfraZtk6',
	'nocaptchaSitekey' => getenv('NOCAPTCHA_SITEKEY') ?: '6LdhyQETAAAAAD8bazOIeedB2t9po-JhWvSwBBfT',
];