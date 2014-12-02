<?php
/**
 * 	File  > helper
 * 	Used to put general php functions
 *
 * 	Date: 4 Aug, 2014
 * 	@author Ankur Raiyani
 */

function p_r($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}

function e_l($data)
{
    error_log(print_r($data, true), 3, EP);
}

function getCurrentdate()
{
    return date("Y-m-d H:i:s");
}
function encrypt($plain)
{
    $length=strlen($plain);
    $temp = array();

    for($i=0;$i<$length;$i++)
    {
        $temp[$i]=chr(ord($plain[$i])+$i*5);
    }

    $cipher=implode($temp);
    return $cipher;
}

function decrypt($cipher)
{
    $length=strlen($cipher);
    $temp = array();

    for($i=0;$i<$length;$i++)
    {
        $temp[$i]=chr(ord($cipher[$i])-$i*5);
    }

    $plain=implode($temp);
    return $plain;
}

function encryptData($value)
{
    $key = "MyProject:04082014:Admin";
    $text = $value;
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $crypttext = mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $key, $text, MCRYPT_MODE_ECB, $iv);
    return $crypttext;
}

function decryptData($value)
{
    $key = "MyProject:04082014:Admin";
    $crypttext = $value;
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
    $decrypttext = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $key, $crypttext, MCRYPT_MODE_ECB, $iv);
    return trim($decrypttext);
}

function convertDateToTimezone($Date, $TimeZone, $Format = "Y-m-d H:i:s")
{
    $ConvertDate = new DateTime($Date, new DateTimeZone('GMT'));
    $ConvertDate->setTimezone(new DateTimeZone($TimeZone));
    return $ConvertDate->format($Format);
}

function getAge($dob)
{
    //calculate years of age (input string: YYYY-MM-DD)
    list($year, $month, $day) = explode("-", $dob);

    $year_diff  = date("Y") - $year;
    $month_diff = date("m") - $month;
    $day_diff   = date("d") - $day;

    if ($day_diff < 0 || $month_diff < 0)
        $year_diff--;

    return $year_diff;
}

function getMonthList()
{
    return array(
            1 => "January",      2 => "February",    3 => "March",
            4 => "April",       5 => "May",         6 => "June",
            7 => "July",        8 => "August",      9 => "September",
            10 => "October",    11 => "November",   12 => "December"
    );
}

function getWeekDaysList()
{
    return array(
            1 => "Sunday",      2 => "Monday",      3 => "Tuesday",
            4 => "Wednesday",   5 => "Thursday",    6 => "Friday",
            7 => "Saturday"
    );
}

function getDateList($month_number = 0, $year = -1)
{
    if($month_number != 0 && $year != -1)
    {
        return range(1, getDaysInMonth($month_number, $year));
    }
    return array(
        "1", "2", "3", "4", "5", "6", "7", "8", "9", "10",
        "11", "12", "13", "14", "15", "16", "17", "18", "19", "20",
        "21", "22", "23", "24", "25", "26", "27", "28", "29", "30", "31"
    );
}

function getYearList()
{
    return array(
        "2012", "2011", "2010", "2009", "2008", "2007", "2006", "2005", "2004", "2003", "2002",
        "2001", "2000", "1999", "1998", "1997", "1996", "1995", "1994", "1993", "1992", "1991",
        "1990", "1989", "1988", "1987", "1986", "1985", "1984", "1983", "1982", "1981", "1980",
        "1979", "1978", "1977", "1976", "1975", "1974", "1973", "1972", "1971", "1970", "1969",
        "1968", "1967", "1966", "1965", "1964", "1963", "1962", "1961", "1960", "1959", "1958",
        "1957", "1956", "1955", "1954", "1953", "1952", "1951", "1950", "1949", "1948", "1947",
        "1946", "1945", "1944", "1943", "1942", "1941", "1940", "1939", "1938", "1937" ,"1936",
        "1935", "1934", "1933", "1932", "1931", "1930", "1929", "1928", "1927", "1926", "1925",
        "1924", "1923", "1922", "1921", "1920", "1919", "1918", "1917" ,"1916", "1915", "1914",
        "1913", "1912", "1911", "1910", "1909", "1908", "1907", "1906" ,"1905", "1904", "1903",
        "1902", "1901", "1900"
    );
}

function getDaysInMonth($month_number, $year)
{
    return cal_days_in_month(CAL_GREGORIAN, $month_number, $year);
}
// Date End

// Short string with limit, break point and padding string
function getStringTruncate($string, $limit, $break=".", $pad="...")
{
    if(strlen($string) <= $limit)
        return $string;

    if(false !== ($breakpoint = strpos($string, $break, $limit))) {

        if($breakpoint < strlen($string) - 1) {

            $string = substr($string, 0, $breakpoint) . $pad;
        }
    } else {
        $string = substr($string, 0, $limit) . $pad;
    }
    return $string;
}

function AsciiToInt($char)
{
    $success = "";
    if(strlen($char) == 1)
        return "char(".ord($char).")";
    else{
        for($i = 0; $i < strlen($char); $i++){
            if($i == strlen($char) - 1)
                $success = $success.ord($char[$i]);
            else
                $success = $success.ord($char[$i]).",";
        }
        return $success;
    }
}

function seo_friendly_url($string)
{
    $string	= strip_tags($string);
    $string = str_replace(array('[\', \']'), '', $string);
    $string = preg_replace('/\[.*\]/U', '', $string);
    $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
    $string = htmlentities($string, ENT_COMPAT, 'utf-8');
    $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
    $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
    return strtolower(trim($string, '-'));
}

function date_sort($a, $b)
{
    $aval = strtotime($a['date']);
    $bval = strtotime($b['date']);
    if ($aval == $bval)
    {
        return 0;
    }
    return $aval < $bval ? 1 : -1;
}

function nbspToSpace($content)
{
    return str_replace('&nbsp;', ' ', $content);
}

function recursive_array_search($needle, $haystack)
{
    foreach($haystack as $key=>$value)
    {
        $current_key=$key;
        if($needle===$value OR (is_array($value) && recursive_array_search($needle,$value) !== false))
        {
            return $current_key;
        }
    }
    return false;
}

function getPageLink($Controller = '', $Action = '')
{
    $Link = SITE_URL;

    if($Controller != '')
    {
        $Controller =trim($Controller, '/');
        $Link .= $Controller.'/';
    }
    if($Action != '')
    {
        $Action = trim($Action, '/');
        $Link .= $Action.'/';
    }

    return $Link;
}

function getTimeZones()
{
    return array(

        // Africa
        "Africa/Abidjan",		"Africa/Accra",			"Africa/Addis_Ababa",
        "Africa/Algiers",		"Africa/Asmara",		"Africa/Asmera",
        "Africa/Bamako",		"Africa/Bangui",		"Africa/Banjul",
        "Africa/Bissau",		"Africa/Blantyre",		"Africa/Brazzaville",
        "Africa/Bujumbura",		"Africa/Cairo",			"Africa/Casablanca",
        "Africa/Ceuta",			"Africa/Conakry",		"Africa/Dakar",
        "Africa/Dar_es_Salaam",	"Africa/Djibouti",		"Africa/Douala",
        "Africa/El_Aaiun",		"Africa/Freetown",		"Africa/Gaborone",
        "Africa/Harare",		"Africa/Johannesburg",	"Africa/Juba",
        "Africa/Kampala",		"Africa/Khartoum",		"Africa/Kigali",
        "Africa/Kinshasa",		"Africa/Lagos",			"Africa/Libreville",
        "Africa/Lome",			"Africa/Luanda",		"Africa/Lubumbashi",
        "Africa/Lusaka",		"Africa/Malabo",		"Africa/Maputo",
        "Africa/Maseru",		"Africa/Mbabane",		"Africa/Mogadishu",
        "Africa/Monrovia",		"Africa/Nairobi",		"Africa/Ndjamena",
        "Africa/Niamey",		"Africa/Nouakchott",	"Africa/Ouagadougou",
        "Africa/Porto-Novo",	"Africa/Sao_Tome",		"Africa/Timbuktu",
        "Africa/Tripoli",		"Africa/Tunis",			"Africa/Windhoek",

        //America
        "America/Adak",			"America/Anchorage",	"America/Anguilla",
        "America/Antigua",		"America/Araguaina", 	"America/Argentina/Buenos_Aires",
        "America/Argentina/Catamarca",					"America/Argentina/ComodRivadavia",
        "America/Argentina/Cordoba",					"America/Argentina/Jujuy",
        "America/Argentina/La_Rioja",					"America/Argentina/Mendoza",
        "America/Argentina/Rio_Gallegos",				"America/Argentina/Salta",
        "America/Argentina/San_Juan",					"America/Argentina/San_Luis",
        "America/Argentina/Tucuman",					"America/Argentina/Ushuaia",
        "America/Aruba",		"America/Asuncion",  	"America/Atikokan",
        "America/Atka",			"America/Bahia",		"America/Bahia_Banderas",
        "America/Barbados",		"America/Belem",		"America/Belize",
        "America/Blanc-Sablon",	"America/Boa_Vista",	"America/Bogota",
        "America/Boise",		"America/Buenos_Aires",	"America/Cambridge_Bay",
        "America/Campo_Grande",	"America/Cancun",		"America/Caracas",
        "America/Catamarca",	"America/Cayenne",		"America/Cayman",
        "America/Chicago",		"America/Chihuahua",	"America/Coral_Harbour",
        "America/Cordoba",		"America/Costa_Rica",	"America/Creston",
        "America/Cuiaba",		"America/Curacao",		"America/Danmarkshavn",
        "America/Dawson",		"America/Dawson_Creek",	"America/Denver",
        "America/Detroit",		"America/Dominica",		"America/Edmonton",
        "America/Eirunepe",		"America/El_Salvador",	"America/Ensenada",
        "America/Fort_Wayne",	"America/Fortaleza",	"America/Glace_Bay",
        "America/Godthab",		"America/Goose_Bay",	"America/Grand_Turk",
        "America/Grenada",		"America/Guadeloupe",	"America/Guatemala",
        "America/Guayaquil",	"America/Guyana",		"America/Halifax",
        "America/Havana",		"America/Hermosillo",	"America/Indiana/Indianapolis",
        "America/Indiana/Knox",							"America/Indiana/Marengo",
        "America/Indiana/Petersburg",					"America/Indiana/Tell_City",
        "America/Indiana/Vevay",						"America/Indiana/Vincennes",
        "America/Indiana/Winamac",						"America/Yellowknife",
        "America/Indianapolis",	"America/Inuvik",		"America/Iqaluit",
        "America/Jamaica",		"America/Jujuy",		"America/Juneau",
        "America/Kentucky/Louisville",					"America/Kentucky/Monticello",
        "America/Knox_IN",		"America/Kralendijk",	"America/La_Paz",
        "America/Lima",			"America/Los_Angeles",	"America/Louisville",
        "America/Lower_Princes","America/Maceio",		"America/Managua",
        "America/Manaus",		"America/Marigot",		"America/Martinique",
        "America/Matamoros",	"America/Mazatlan",		"America/Mendoza",
        "America/Menominee",	"America/Merida",		"America/Metlakatla",
        "America/Mexico_City",	"America/Miquelon",		"America/Moncton",
        "America/Monterrey",	"America/Montevideo",	"America/Montreal",
        "America/Montserrat",	"America/Nassau",		"America/New_York",
        "America/Nipigon",		"America/Nome",			"America/Noronha",
        "America/North_Dakota/Beulah",					"America/North_Dakota/Center",
        "America/North_Dakota/New_Salem",				"America/Ojinaga",
        "America/Panama",		"America/Pangnirtung",	"America/Paramaribo",
        "America/Phoenix",		"America/Port-au-Prince","America/Port_of_Spain",
        "America/Porto_Acre",	"America/Porto_Velho",	"America/Puerto_Rico",
        "America/Rainy_River",	"America/Rankin_Inlet",	"America/Recife",
        "America/Regina",		"America/Resolute",		"America/Rio_Branco",
        "America/Rosario",		"America/Santa_Isabel",	"America/Santarem",
        "America/Santiago",		"America/Santo_Domingo","America/Sao_Paulo",
        "America/Scoresbysund",	"America/Shiprock",		"America/Sitka",
        "America/St_Barthelemy","America/St_Johns",		"America/St_Kitts",
        "America/St_Lucia",		"America/St_Thomas",	"America/St_Vincent",
        "America/Swift_Current","America/Tegucigalpa",	"America/Thule",
        "America/Thunder_Bay",	"America/Tijuana",		"America/Toronto",
        "America/Tortola",		"America/Vancouver",	"America/Virgin",
        "America/Whitehorse",	"America/Winnipeg",		"America/Yakutat",

        //Antarctica & Arctic
        "Antarctica/Casey",		"Antarctica/Davis",		"Antarctica/DumontDUrville",
        "Antarctica/Macquarie",	"Antarctica/Mawson",	"Antarctica/McMurdo",
        "Antarctica/Palmer",	"Antarctica/Rothera",	"Antarctica/South_Pole",
        "Antarctica/Syowa",		"Antarctica/Vostok",	"Arctic/Longyearbyen",

        // Asia
        "Asia/Aden", 			"Asia/Almaty", 			"Asia/Amman",
        "Asia/Anadyr", 			"Asia/Aqtau",			"Asia/Aqtobe",
        "Asia/Ashgabat", 		"Asia/Ashkhabad", 		"Asia/Baghdad",
        "Asia/Bahrain",			"Asia/Baku", 			"Asia/Bangkok",
        "Asia/Beirut", 			"Asia/Bishkek", 		"Asia/Brunei",
        "Asia/Calcutta", 		"Asia/Choibalsan", 		"Asia/Chongqing",
        "Asia/Chungking", 		"Asia/Colombo",			"Asia/Dacca",
        "Asia/Damascus", 		"Asia/Dhaka", 			"Asia/Dili",
        "Asia/Dubai",			"Asia/Dushanbe", 		"Asia/Gaza",
        "Asia/Harbin",			"Asia/Hebron", 			"Asia/Ho_Chi_Minh",
        "Asia/Hong_Kong", 		"Asia/Hovd", 			"Asia/Irkutsk",
        "Asia/Istanbul", 		"Asia/Jakarta",			"Asia/Jayapura",
        "Asia/Jerusalem", 		"Asia/Kabul", 			"Asia/Kamchatka",
        "Asia/Karachi",			"Asia/Kashgar", 		"Asia/Kathmandu",
        "Asia/Katmandu",		"Asia/Khandyga", 		"Asia/Kolkata",
        "Asia/Krasnoyarsk", 	"Asia/Kuala_Lumpur", 	"Asia/Kuching",
        "Asia/Kuwait", 			"Asia/Macao",			"Asia/Macau",
        "Asia/Magadan", 		"Asia/Makassar", 		"Asia/Manila",
        "Asia/Muscat",			"Asia/Nicosia", 		"Asia/Novokuznetsk",
        "Asia/Novosibirsk", 	"Asia/Omsk", 			"Asia/Oral",
        "Asia/Phnom_Penh", 		"Asia/Pontianak", 		"Asia/Pyongyang",
        "Asia/Qatar", 			"Asia/Qyzylorda",		"Asia/Rangoon",
        "Asia/Riyadh", 			"Asia/Saigon",			"Asia/Sakhalin",
        "Asia/Samarkand",		"Asia/Seoul",			"Asia/Shanghai",
        "Asia/Singapore", 		"Asia/Taipei", 			"Asia/Tashkent",
        "Asia/Tbilisi", 		"Asia/Tehran", 			"Asia/Tel_Aviv",
        "Asia/Thimbu", 			"Asia/Thimphu",			"Asia/Tokyo",
        "Asia/Ujung_Pandang",	"Asia/Ulaanbaatar", 	"Asia/Ulan_Bator", "Asia/Urumqi",
        "Asia/Ust-Nera", 		"Asia/Vientiane", 		"Asia/Vladivostok",
        "Asia/Yakutsk", 		"Asia/Yekaterinburg",	"Asia/Yerevan",

        //Atlantic
        "Atlantic/Azores", 		"Atlantic/Bermuda", 	"Atlantic/Canary",
        "Atlantic/Cape_Verde", 	"Atlantic/Faeroe",		"Atlantic/Faroe",
        "Atlantic/Jan_Mayen", 	"Atlantic/Madeira", 	"Atlantic/Reykjavik",
        "Atlantic/South_Georgia","Atlantic/St_Helena", 	"Atlantic/Stanley",

        //Australia
        "Australia/ACT", 		"Australia/Adelaide", 	"Australia/Brisbane",
        "Australia/Broken_Hill","Australia/Canberra", 	"Australia/Currie",
        "Australia/Darwin", 	"Australia/Eucla", 		"Australia/Hobart",
        "Australia/LHI",		"Australia/Lindeman", 	"Australia/Lord_Howe",
        "Australia/Melbourne", 	"Australia/North", 		"Australia/NSW",
        "Australia/Perth", 		"Australia/Queensland", "Australia/South",
        "Australia/Sydney", 	"Australia/Tasmania", 	"Australia/Victoria",
        "Australia/West", 		"Australia/Yancowinna",

        //Europe
        "Europe/Amsterdam", 	"Europe/Andorra", 		"Europe/Athens",
        "Europe/Belfast", 		"Europe/Belgrade",		"Europe/Berlin",
        "Europe/Bratislava",	"Europe/Brussels", 		"Europe/Bucharest",
        "Europe/Budapest",		"Europe/Busingen", 		"Europe/Chisinau",
        "Europe/Copenhagen", 	"Europe/Dublin",		"Europe/Gibraltar",
        "Europe/Guernsey", 		"Europe/Helsinki", 		"Europe/Isle_of_Man",
        "Europe/Istanbul", 		"Europe/Jersey",		"Europe/Kaliningrad",
        "Europe/Kiev", 			"Europe/Lisbon", 		"Europe/Ljubljana",
        "Europe/London",		"Europe/Luxembourg",	"Europe/Madrid",
        "Europe/Malta", 		"Europe/Mariehamn", 	"Europe/Minsk",
        "Europe/Monaco", 		"Europe/Moscow", 		"Europe/Nicosia",
        "Europe/Oslo", 			"Europe/Paris",			"Europe/Podgorica",
        "Europe/Prague", 		"Europe/Riga", 			"Europe/Rome",
        "Europe/Samara",		"Europe/San_Marino", 	"Europe/Sarajevo",
        "Europe/Simferopol", 	"Europe/Skopje", 		"Europe/Sofia",
        "Europe/Stockholm", 	"Europe/Tallinn", 		"Europe/Tirane",
        "Europe/Tiraspol", 		"Europe/Uzhgorod",		"Europe/Vaduz",
        "Europe/Vatican", 		"Europe/Vienna", 		"Europe/Vilnius",
        "Europe/Volgograd",		"Europe/Warsaw", 		"Europe/Zagreb",
        "Europe/Zaporozhye", 	"Europe/Zurich",

        //Indian
        "Indian/Antananarivo",	"Indian/Chagos",		"Indian/Christmas",
        "Indian/Cocos",			"Indian/Comoro",		"Indian/Kerguelen",
        "Indian/Mahe",			"Indian/Maldives",		"Indian/Mauritius",
        "Indian/Mayotte",		"Indian/Reunion",

        //Pacific

        "Pacific/Apia",			"Pacific/Auckland", 	"Pacific/Chatham",
        "Pacific/Chuuk", 		"Pacific/Easter",		"Pacific/Efate",
        "Pacific/Enderbury", 	"Pacific/Fakaofo", 		"Pacific/Fiji",
        "Pacific/Funafuti",		"Pacific/Galapagos", 	"Pacific/Gambier",
        "Pacific/Guadalcanal", 	"Pacific/Guam", 		"Pacific/Honolulu",
        "Pacific/Johnston", 	"Pacific/Kiritimati", 	"Pacific/Kosrae",
        "Pacific/Kwajalein", 	"Pacific/Majuro",		"Pacific/Marquesas",
        "Pacific/Midway", 		"Pacific/Nauru",		"Pacific/Niue",
        "Pacific/Norfolk",		"Pacific/Noumea", 		"Pacific/Pago_Pago",
        "Pacific/Palau", 		"Pacific/Pitcairn", 	"Pacific/Pohnpei",
        "Pacific/Ponape", 		"Pacific/Port_Moresby", "Pacific/Rarotonga",
        "Pacific/Saipan", 		"Pacific/Samoa",		"Pacific/Tahiti",
        "Pacific/Tarawa", 		"Pacific/Tongatapu", 	"Pacific/Truk",
        "Pacific/Wake",			"Pacific/Wallis", 		"Pacific/Yap",

        //Others
        "Brazil/Acre", 			"Brazil/DeNoronha", 	"Brazil/East",
        "Brazil/West", 			"Canada/Atlantic",		"Canada/Central",
        "Canada/East-Saskatchewan", 					"Canada/Eastern",
        "Canada/Mountain", 		"Canada/Newfoundland",	"Canada/Pacific",
        "Canada/Saskatchewan", 	"Canada/Yukon", 		"CET",
        "Chile/Continental",	"Chile/EasterIsland", 	"CST6CDT",
        "Cuba", 				"EET", 					"Egypt",
        "Eire", 				"EST", 					"EST5EDT",
        "Etc/GMT", 				"Etc/GMT+0",			"Etc/GMT+1",
        "Etc/GMT+10", 			"Etc/GMT+11", 			"Etc/GMT+12",
        "Etc/GMT+2",			"Etc/GMT+3", 			"Etc/GMT+4",
        "Etc/GMT+5",			"Etc/GMT+6", 			"Etc/GMT+7",
        "Etc/GMT+8", 			"Etc/GMT+9", 			"Etc/GMT-0",
        "Etc/GMT-1", 			"Etc/GMT-10",			"Etc/GMT-11",
        "Etc/GMT-12", 			"Etc/GMT-13", 			"Etc/GMT-14",
        "Etc/GMT-2",			"Etc/GMT-3", 			"Etc/GMT-4",
        "Etc/GMT-5", 			"Etc/GMT-6",			"Etc/GMT-7",
        "Etc/GMT-8", 			"Etc/GMT-9",			"Etc/GMT0",
        "Etc/Greenwich", 		"Etc/UCT",				"Etc/Universal",
        "Etc/UTC", 				"Etc/Zulu", 			"Factory", "GB",
        "GB-Eire", 				"GMT", 					"GMT+0",
        "GMT-0", 				"GMT0",					"Greenwich",
        "Hongkong", 			"HST", 					"Iceland",
        "Iran",					"Israel", 				"Jamaica",
        "Japan", 				"Kwajalein", 			"Libya",
        "MET", 					"Mexico/BajaNorte", 	"Mexico/BajaSur",
        "Mexico/General", 		"MST",					"MST7MDT",
        "Navajo", 				"NZ", 					"NZ-CHAT",
        "Poland",				"Portugal", 			"PRC",
        "PST8PDT", 				"ROC", 					"ROK",
        "Singapore", 			"Turkey", 				"UCT",
        "Universal", 			"US/Alaska",			"US/Aleutian",
        "US/Arizona", 			"US/Central", 			"US/East-Indiana",
        "US/Eastern",			"US/Hawaii", 			"US/Indiana-Starke",
        "US/Michigan", 			"US/Mountain", 			"US/Pacific",
        "US/Pacific-New", 		"US/Samoa", 			"UTC",
        "W-SU", 				"WET"
    );
}
