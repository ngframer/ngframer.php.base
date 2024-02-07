<?php

namespace NGFramer\NGFramerPHPBase\utilities;

final class UtilUrl
{


	public static function isValidUrl($url): bool
    {
		return (bool) filter_var($url, FILTER_VALIDATE_URL);
	}


}
