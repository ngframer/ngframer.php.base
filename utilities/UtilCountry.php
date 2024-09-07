<?php

namespace NGFramer\NGFramerPHPBase\utilities;

use Exception;
use NGFramer\NGFramerPHPBase\defaults\exceptions\CountryException;

final class UtilCountry
{
    /**
     * The array contains information about the country.
     * @var array $country
     */
    private static array $country;


    /**
     * Private constructor to restrict instantiation.
     */
    private function __construct()
    {
    }


    /**
     * Initialize the country array.
     * @return void
     */
    public static function init(): void
    {
        self::$country = array(
            "AF" => array("calling_code" => "+93", "name" => "Afghanistan"),
            "AX" => array("calling_code" => "+358", "name" => "Åland Islands"),
            "AL" => array("calling_code" => "+355", "name" => "Albania"),
            "DZ" => array("calling_code" => "+213", "name" => "Algeria"),
            "AS" => array("calling_code" => "+1-684", "name" => "American Samoa"),
            "AD" => array("calling_code" => "+376", "name" => "Andorra"),
            "AO" => array("calling_code" => "+244", "name" => "Angola"),
            "AI" => array("calling_code" => "+1-264", "name" => "Anguilla"),
            "AQ" => array("calling_code" => "+672", "name" => "Antarctica"),
            "AG" => array("calling_code" => "+1-268", "name" => "Antigua and Barbuda"),
            "AR" => array("calling_code" => "+54", "name" => "Argentina"),
            "AM" => array("calling_code" => "+374", "name" => "Armenia"),
            "AW" => array("calling_code" => "+297", "name" => "Aruba"),
            "AU" => array("calling_code" => "+61", "name" => "Australia"),
            "AT" => array("calling_code" => "+43", "name" => "Austria"),
            "AZ" => array("calling_code" => "+994", "name" => "Azerbaijan"),
            "BS" => array("calling_code" => "+1-242", "name" => "Bahamas"),
            "BH" => array("calling_code" => "+973", "name" => "Bahrain"),
            "BD" => array("calling_code" => "+880", "name" => "Bangladesh"),
            "BB" => array("calling_code" => "+1-246", "name" => "Barbados"),
            "BY" => array("calling_code" => "+375", "name" => "Belarus"),
            "BE" => array("calling_code" => "+32", "name" => "Belgium"),
            "BZ" => array("calling_code" => "+501", "name" => "Belize"),
            "BJ" => array("calling_code" => "+229", "name" => "Benin"),
            "BM" => array("calling_code" => "+1-441", "name" => "Bermuda"),
            "BT" => array("calling_code" => "+975", "name" => "Bhutan"),
            "BO" => array("calling_code" => "+591", "name" => "Bolivia"),
            "BQ" => array("calling_code" => "+599", "name" => "Bonaire, Sint Eustatius and Saba"),
            "BA" => array("calling_code" => "+387", "name" => "Bosnia and Herzegovina"),
            "BW" => array("calling_code" => "+267", "name" => "Botswana"),
            "BV" => array("calling_code" => "+47", "name" => "Bouvet Island"),
            "BR" => array("calling_code" => "+55", "name" => "Brazil"),
            "IO" => array("calling_code" => "+246", "name" => "British Indian Ocean Territory"),
            "BN" => array("calling_code" => "+673", "name" => "Brunei Darussalam"),
            "BG" => array("calling_code" => "+359", "name" => "Bulgaria"),
            "BF" => array("calling_code" => "+226", "name" => "Burkina Faso"),
            "BI" => array("calling_code" => "+257", "name" => "Burundi"),
            "KH" => array("calling_code" => "+855", "name" => "Cambodia"),
            "CM" => array("calling_code" => "+237", "name" => "Cameroon"),
            "CA" => array("calling_code" => "+1", "name" => "Canada"),
            "CV" => array("calling_code" => "+238", "name" => "Cape Verde"),
            "KY" => array("calling_code" => "+1-345", "name" => "Cayman Islands"),
            "CF" => array("calling_code" => "+236", "name" => "Central African Republic"),
            "TD" => array("calling_code" => "+235", "name" => "Chad"),
            "CL" => array("calling_code" => "+56", "name" => "Chile"),
            "CN" => array("calling_code" => "+86", "name" => "China"),
            "CX" => array("calling_code" => "+61", "name" => "Christmas Island"),
            "CC" => array("calling_code" => "+61", "name" => "Cocos (Keeling) Islands"),
            "CO" => array("calling_code" => "+57", "name" => "Colombia"),
            "KM" => array("calling_code" => "+269", "name" => "Comoros"),
            "CG" => array("calling_code" => "+242", "name" => "Congo"),
            "CD" => array("calling_code" => "+243", "name" => "Congo, the Democratic Republic of the"),
            "CK" => array("calling_code" => "+682", "name" => "Cook Islands"),
            "CR" => array("calling_code" => "+506", "name" => "Costa Rica"),
            "CI" => array("calling_code" => "+225", "name" => "Côte d'Ivoire"),
            "HR" => array("calling_code" => "+385", "name" => "Croatia"),
            "CU" => array("calling_code" => "+53", "name" => "Cuba"),
            "CW" => array("calling_code" => "+599", "name" => "Curaçao"),
            "CY" => array("calling_code" => "+357", "name" => "Cyprus"),
            "CZ" => array("calling_code" => "+420", "name" => "Czech Republic"),
            "DK" => array("calling_code" => "+45", "name" => "Denmark"),
            "DJ" => array("calling_code" => "+253", "name" => "Djibouti"),
            "DM" => array("calling_code" => "+1-767", "name" => "Dominica"),
            "DO" => array("calling_code" => "+1-809, +1-829, +1-849", "name" => "Dominican Republic"),
            "EC" => array("calling_code" => "+593", "name" => "Ecuador"),
            "EG" => array("calling_code" => "+20", "name" => "Egypt"),
            "SV" => array("calling_code" => "+503", "name" => "El Salvador"),
            "GQ" => array("calling_code" => "+240", "name" => "Equatorial Guinea"),
            "ER" => array("calling_code" => "+291", "name" => "Eritrea"),
            "EE" => array("calling_code" => "+372", "name" => "Estonia"),
            "ET" => array("calling_code" => "+251", "name" => "Ethiopia"),
            "FK" => array("calling_code" => "+500", "name" => "Falkland Islands (Malvinas)"),
            "FO" => array("calling_code" => "+298", "name" => "Faroe Islands"),
            "FJ" => array("calling_code" => "+679", "name" => "Fiji"),
            "FI" => array("calling_code" => "+358", "name" => "Finland"),
            "FR" => array("calling_code" => "+33", "name" => "France"),
            "GF" => array("calling_code" => "+594", "name" => "French Guiana"),
            "PF" => array("calling_code" => "+689", "name" => "French Polynesia"),
            "TF" => array("calling_code" => "+262", "name" => "French Southern Territories"),
            "GA" => array("calling_code" => "+241", "name" => "Gabon"),
            "GM" => array("calling_code" => "+220", "name" => "Gambia"),
            "GE" => array("calling_code" => "+995", "name" => "Georgia"),
            "DE" => array("calling_code" => "+49", "name" => "Germany"),
            "GH" => array("calling_code" => "+233", "name" => "Ghana"),
            "GI" => array("calling_code" => "+350", "name" => "Gibraltar"),
            "GR" => array("calling_code" => "+30", "name" => "Greece"),
            "GL" => array("calling_code" => "+299", "name" => "Greenland"),
            "GD" => array("calling_code" => "+1-473", "name" => "Grenada"),
            "GP" => array("calling_code" => "+590", "name" => "Guadeloupe"),
            "GU" => array("calling_code" => "+1-671", "name" => "Guam"),
            "GT" => array("calling_code" => "+502", "name" => "Guatemala"),
            "GG" => array("calling_code" => "+44-1481", "name" => "Guernsey"),
            "GN" => array("calling_code" => "+224", "name" => "Guinea"),
            "GW" => array("calling_code" => "+245", "name" => "Guinea-Bissau"),
            "GY" => array("calling_code" => "+592", "name" => "Guyana"),
            "HT" => array("calling_code" => "+509", "name" => "Haiti"),
            "HM" => array("calling_code" => "+672", "name" => "Heard Island and McDonald Islands"),
            "VA" => array("calling_code" => "+379", "name" => "Holy See (Vatican City State)"),
            "HN" => array("calling_code" => "+504", "name" => "Honduras"),
            "HK" => array("calling_code" => "+852", "name" => "Hong Kong"),
            "HU" => array("calling_code" => "+36", "name" => "Hungary"),
            "IS" => array("calling_code" => "+354", "name" => "Iceland"),
            "IN" => array("calling_code" => "+91", "name" => "India"),
            "ID" => array("calling_code" => "+62", "name" => "Indonesia"),
            "IR" => array("calling_code" => "+98", "name" => "Iran, Islamic Republic of"),
            "IQ" => array("calling_code" => "+964", "name" => "Iraq"),
            "IE" => array("calling_code" => "+353", "name" => "Ireland"),
            "IM" => array("calling_code" => "+44-1624", "name" => "Isle of Man"),
            "IL" => array("calling_code" => "+972", "name" => "Israel"),
            "IT" => array("calling_code" => "+39", "name" => "Italy"),
            "JM" => array("calling_code" => "+1-876", "name" => "Jamaica"),
            "JP" => array("calling_code" => "+81", "name" => "Japan"),
            "JE" => array("calling_code" => "+44-1534", "name" => "Jersey"),
            "JO" => array("calling_code" => "+962", "name" => "Jordan"),
            "KZ" => array("calling_code" => "+7", "name" => "Kazakhstan"),
            "KE" => array("calling_code" => "+254", "name" => "Kenya"),
            "KI" => array("calling_code" => "+686", "name" => "Kiribati"),
            "KP" => array("calling_code" => "+850", "name" => "Korea, Democratic People's Republic of"),
            "KR" => array("calling_code" => "+82", "name" => "Korea, Republic of"),
            "KW" => array("calling_code" => "+965", "name" => "Kuwait"),
            "KG" => array("calling_code" => "+996", "name" => "Kyrgyzstan"),
            "LA" => array("calling_code" => "+856", "name" => "Lao People's Democratic Republic"),
            "LV" => array("calling_code" => "+371", "name" => "Latvia"),
            "LB" => array("calling_code" => "+961", "name" => "Lebanon"),
            "LS" => array("calling_code" => "+266", "name" => "Lesotho"),
            "LR" => array("calling_code" => "+231", "name" => "Liberia"),
            "LY" => array("calling_code" => "+218", "name" => "Libya"),
            "LI" => array("calling_code" => "+423", "name" => "Liechtenstein"),
            "LT" => array("calling_code" => "+370", "name" => "Lithuania"),
            "LU" => array("calling_code" => "+352", "name" => "Luxembourg"),
            "MO" => array("calling_code" => "+853", "name" => "Macao"),
            "MK" => array("calling_code" => "+389", "name" => "Macedonia, the former Yugoslav Republic of"),
            "MG" => array("calling_code" => "+261", "name" => "Madagascar"),
            "MW" => array("calling_code" => "+265", "name" => "Malawi"),
            "MY" => array("calling_code" => "+60", "name" => "Malaysia"),
            "MV" => array("calling_code" => "+960", "name" => "Maldives"),
            "ML" => array("calling_code" => "+223", "name" => "Mali"),
            "MT" => array("calling_code" => "+356", "name" => "Malta"),
            "MH" => array("calling_code" => "+692", "name" => "Marshall Islands"),
            "MQ" => array("calling_code" => "+596", "name" => "Martinique"),
            "MR" => array("calling_code" => "+222", "name" => "Mauritania"),
            "MU" => array("calling_code" => "+230", "name" => "Mauritius"),
            "YT" => array("calling_code" => "+262", "name" => "Mayotte"),
            "MX" => array("calling_code" => "+52", "name" => "Mexico"),
            "FM" => array("calling_code" => "+691", "name" => "Micronesia, Federated States of"),
            "MD" => array("calling_code" => "+373", "name" => "Moldova, Republic of"),
            "MC" => array("calling_code" => "+377", "name" => "Monaco"),
            "MN" => array("calling_code" => "+976", "name" => "Mongolia"),
            "ME" => array("calling_code" => "+382", "name" => "Montenegro"),
            "MS" => array("calling_code" => "+1-664", "name" => "Montserrat"),
            "MA" => array("calling_code" => "+212", "name" => "Morocco"),
            "MZ" => array("calling_code" => "+258", "name" => "Mozambique"),
            "MM" => array("calling_code" => "+95", "name" => "Myanmar"),
            "NA" => array("calling_code" => "+264", "name" => "Namibia"),
            "NR" => array("calling_code" => "+674", "name" => "Nauru"),
            "NP" => array("calling_code" => "+977", "name" => "Nepal"),
            "NL" => array("calling_code" => "+31", "name" => "Netherlands"),
            "NC" => array("calling_code" => "+687", "name" => "New Caledonia"),
            "NZ" => array("calling_code" => "+64", "name" => "New Zealand"),
            "NI" => array("calling_code" => "+505", "name" => "Nicaragua"),
            "NE" => array("calling_code" => "+227", "name" => "Niger"),
            "NG" => array("calling_code" => "+234", "name" => "Nigeria"),
            "NU" => array("calling_code" => "+683", "name" => "Niue"),
            "NF" => array("calling_code" => "+672", "name" => "Norfolk Island"),
            "MP" => array("calling_code" => "+1-670", "name" => "Northern Mariana Islands"),
            "NO" => array("calling_code" => "+47", "name" => "Norway"),
            "OM" => array("calling_code" => "+968", "name" => "Oman"),
            "PK" => array("calling_code" => "+92", "name" => "Pakistan"),
            "PW" => array("calling_code" => "+680", "name" => "Palau"),
            "PS" => array("calling_code" => "+970", "name" => "Palestinian Territory, Occupied"),
            "PA" => array("calling_code" => "+507", "name" => "Panama"),
            "PG" => array("calling_code" => "+675", "name" => "Papua New Guinea"),
            "PY" => array("calling_code" => "+595", "name" => "Paraguay"),
            "PE" => array("calling_code" => "+51", "name" => "Peru"),
            "PH" => array("calling_code" => "+63", "name" => "Philippines"),
            "PN" => array("calling_code" => "+64", "name" => "Pitcairn"),
            "PL" => array("calling_code" => "+48", "name" => "Poland"),
            "PT" => array("calling_code" => "+351", "name" => "Portugal"),
            "PR" => array("calling_code" => "+1-787, +1-939", "name" => "Puerto Rico"),
            "QA" => array("calling_code" => "+974", "name" => "Qatar"),
            "RE" => array("calling_code" => "+262", "name" => "Réunion"),
            "RO" => array("calling_code" => "+40", "name" => "Romania"),
            "RU" => array("calling_code" => "+7", "name" => "Russian Federation"),
            "RW" => array("calling_code" => "+250", "name" => "Rwanda"),
            "BL" => array("calling_code" => "+590", "name" => "Saint Barthélemy"),
            "SH" => array("calling_code" => "+290", "name" => "Saint Helena, Ascension and Tristan da Cunha"),
            "KN" => array("calling_code" => "+1-869", "name" => "Saint Kitts and Nevis"),
            "LC" => array("calling_code" => "+1-758", "name" => "Saint Lucia"),
            "MF" => array("calling_code" => "+590", "name" => "Saint Martin (French part)"),
            "PM" => array("calling_code" => "+508", "name" => "Saint Pierre and Miquelon"),
            "VC" => array("calling_code" => "+1-784", "name" => "Saint Vincent and the Grenadines"),
            "WS" => array("calling_code" => "+685", "name" => "Samoa"),
            "SM" => array("calling_code" => "+378", "name" => "San Marino"),
            "ST" => array("calling_code" => "+239", "name" => "Sao Tome and Principe"),
            "SA" => array("calling_code" => "+966", "name" => "Saudi Arabia"),
            "SN" => array("calling_code" => "+221", "name" => "Senegal"),
            "RS" => array("calling_code" => "+381", "name" => "Serbia"),
            "SC" => array("calling_code" => "+248", "name" => "Seychelles"),
            "SL" => array("calling_code" => "+232", "name" => "Sierra Leone"),
            "SG" => array("calling_code" => "+65", "name" => "Singapore"),
            "SX" => array("calling_code" => "+1-721", "name" => "Sint Maarten (Dutch part)"),
            "SK" => array("calling_code" => "+421", "name" => "Slovakia"),
            "SI" => array("calling_code" => "+386", "name" => "Slovenia"),
            "SB" => array("calling_code" => "+677", "name" => "Solomon Islands"),
            "SO" => array("calling_code" => "+252", "name" => "Somalia"),
            "ZA" => array("calling_code" => "+27", "name" => "South Africa"),
            "GS" => array("calling_code" => "+500", "name" => "South Georgia and the South Sandwich Islands"),
            "SS" => array("calling_code" => "+211", "name" => "South Sudan"),
            "ES" => array("calling_code" => "+34", "name" => "Spain"),
            "LK" => array("calling_code" => "+94", "name" => "Sri Lanka"),
            "SD" => array("calling_code" => "+249", "name" => "Sudan"),
            "SR" => array("calling_code" => "+597", "name" => "Suriname"),
            "SJ" => array("calling_code" => "+47", "name" => "Svalbard and Jan Mayen"),
            "SZ" => array("calling_code" => "+268", "name" => "Swaziland"),
            "SE" => array("calling_code" => "+46", "name" => "Sweden"),
            "CH" => array("calling_code" => "+41", "name" => "Switzerland"),
            "SY" => array("calling_code" => "+963", "name" => "Syrian Arab Republic"),
            "TW" => array("calling_code" => "+886", "name" => "Taiwan, Province of China"),
            "TJ" => array("calling_code" => "+992", "name" => "Tajikistan"),
            "TZ" => array("calling_code" => "+255", "name" => "Tanzania, United Republic of"),
            "TH" => array("calling_code" => "+66", "name" => "Thailand"),
            "TL" => array("calling_code" => "+670", "name" => "Timor-Leste"),
            "TG" => array("calling_code" => "+228", "name" => "Togo"),
            "TK" => array("calling_code" => "+690", "name" => "Tokelau"),
            "TO" => array("calling_code" => "+676", "name" => "Tonga"),
            "TT" => array("calling_code" => "+1-868", "name" => "Trinidad and Tobago"),
            "TN" => array("calling_code" => "+216", "name" => "Tunisia"),
            "TR" => array("calling_code" => "+90", "name" => "Turkey"),
            "TM" => array("calling_code" => "+993", "name" => "Turkmenistan"),
            "TC" => array("calling_code" => "+1-649", "name" => "Turks and Caicos Islands"),
            "TV" => array("calling_code" => "+688", "name" => "Tuvalu"),
            "UG" => array("calling_code" => "+256", "name" => "Uganda"),
            "UA" => array("calling_code" => "+380", "name" => "Ukraine"),
            "AE" => array("calling_code" => "+971", "name" => "United Arab Emirates"),
            "GB" => array("calling_code" => "+44", "name" => "United Kingdom"),
            "US" => array("calling_code" => "+1", "name" => "United States"),
            "UM" => array("calling_code" => "+1", "name" => "United States Minor Outlying Islands"),
            "UY" => array("calling_code" => "+598", "name" => "Uruguay"),
            "UZ" => array("calling_code" => "+998", "name" => "Uzbekistan"),
            "VU" => array("calling_code" => "+678", "name" => "Vanuatu"),
            "VE" => array("calling_code" => "+58", "name" => "Venezuela, Bolivarian Republic of"),
            "VN" => array("calling_code" => "+84", "name" => "Viet Nam"),
            "VG" => array("calling_code" => "+1-284", "name" => "Virgin Islands, British"),
            "VI" => array("calling_code" => "+1-340", "name" => "Virgin Islands, U.S."),
            "WF" => array("calling_code" => "+681", "name" => "Wallis and Futuna"),
            "EH" => array("calling_code" => "+212", "name" => "Western Sahara"),
            "YE" => array("calling_code" => "+967", "name" => "Yemen"),
            "ZM" => array("calling_code" => "+260", "name" => "Zambia"),
            "ZW" => array("calling_code" => "+263", "name" => "Zimbabwe")
        );
    }


    /**
     * Check if the country is valid using country code.
     * @param $countryCode
     * @return bool
     */
    public static function isValidCountry($countryCode): bool
    {
        self::init();
        if (array_key_exists($countryCode, UtilCountry::$country)) {
            return true;
        }
        return false;
    }


    /**
     * Get the country calling code using country code.
     * @param $countryCode
     * @return array|bool
     * @throws CountryException
     */
    public function getCallingCode($countryCode): array|bool
    {
        self::init();
        if (UtilCountry::isValidCountry($countryCode)) {
            return UtilCountry::$country['calling_code'];
        } else {
            throw new CountryException("Invalid country code. The country does not exist.", 1003001);
        }
    }
}