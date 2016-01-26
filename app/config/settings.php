<?php

return [
	// Url сайта без "http://"
	'siteUrl' => 'avtorem.dev',

	'metaAuthor' => '',
	'metaRobots' => 'index, follow',
	'metaCopyright' => '',

    'metaTitle' => ' - Школа авторемонта',
    'metaDesc' => 'Школа авторемонта - www.avtorem.info',
    'metaKey' => 'Школа авторемонта - www.avtorem.info',

	'adminEmail' => 'contact@avtorem.info',
	'adminName' => 'Школа авторемонта',
	'contactSubjectToUser' => 'Копия сообщения с сайта avtorem.info',

	'journalAlias' => 'bortovoj-zhurnal',

	//images
	'maxImageSize' => 2048,
	'maxImageHeight' => 1200,
	'maxImageWidth' => 1200,
	'maxTagImageWidth' => 150,
	'maxHonorImageWidth' => 300,

	'maxImageSizeInfo' => 'Максимальный вес изображения 2 МБ.',

	'bannedImage' => '/images/banned.png',

	'defaultImage' => '/images/default-image.jpg',
	'mini_defaultImage' => '/images/mini_default-image.jpg',

	'defaultAvatar' => '/images/default-avatar.png',
	'mini_defaultAvatar' => '/images/mini_default-avatar.png',

	'defaultHonorImage' => '/images/default-honor-image.png',

	'defaultTagImage' => '/images/default-tag-image.png',
	//end images

	'defaultPublishedTime' => '10:00:00',
	'userActivityTime' => 5, // minutes

	'numberOfUserImages' => 5,

	// count on page
	'commentsCountOnPage' => 10,

	'error404Redirect' => true,

	// nocaptcha
	'nocaptchaSecret'  => getenv('NOCAPTCHA_SECRET') ?: '6LdhyQETAAAAAIYg3kSuajrmL5HZoQ7OVfraZtk6',
	'nocaptchaSitekey' => getenv('NOCAPTCHA_SITEKEY') ?: '6LdhyQETAAAAAD8bazOIeedB2t9po-JhWvSwBBfT',
];