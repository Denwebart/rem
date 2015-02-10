<?php

return array(

	'secret'  => getenv('NOCAPTCHA_SECRET') ?: '6LdhyQETAAAAAIYg3kSuajrmL5HZoQ7OVfraZtk6',
	'sitekey' => getenv('NOCAPTCHA_SITEKEY') ?: '6LdhyQETAAAAAD8bazOIeedB2t9po-JhWvSwBBfT',

	'lang'    => app()->getLocale(),

);
