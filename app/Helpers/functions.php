<?php

use App\Models\TokenVerification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Number;

use function Laravel\Prompts\progress;

function see($data, $proceed = false)
{
    echo "<pre>";
    if ($data instanceof \Illuminate\Database\Eloquent\Model) {
        print_r($data->toArray());
    } elseif (in_array(gettype($data), ["object", "array"])) {
        print_r($data);
    } else {
        echo ($data) . '<br/>';
    }
    if (!$proceed) die();
}

function object(array $data): object
{
    return (object) $data;
}

function toArray(object $data): array
{
    return json_decode(json_encode($data), true);
}

function isRequired(array|object|null $data = []): bool
{
    $response = [];
    if ($data === null) {
        return false;
    }
    if (gettype($data) === "object") {
        $data = toArray($data);
    }
    if (empty($data)) {
        return false;
    }
    foreach ($data as $k => $v) {
        if (empty($v)) {
            array_push($response, $k);
        }
    }
    return !count($response);
}

function object_merge(object $data1, object $data2): object
{
    return object(array_merge((array) $data1, (array) $data2));
}

function flatten_array($data)
{
    $build = [];
    foreach (new RecursiveIteratorIterator(new RecursiveArrayIterator($data)) as $key => $value) {
        $build[$key] = $value;
    }
    return gettype($data)  === "object" ? object(array_filter($build)) : array_filter($build);
}

function get_percent($amount, $total)
{
    return empty($total) ? 0 : ($amount * 100) / $total;
}

function get_percent_of($percent, $amount)
{
    return ($amount * $percent) / 100;
}

// -----

function addHttp($url)
{
    if (isset($url) && !empty($url) && $url != "") {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http://" . $url;
        }
    }
    return $url;
}


// Filters an array with one or multiple values
function filter_array(array $array, array $filter_values)
{
    $return = [];
    foreach ($array as $key => $value) {
        $validrow = 0;
        foreach ($filter_values as $filter_key => $filter_value) {
            if (isset($value->{$filter_key}) && $value->{$filter_key} == $filter_value) {
                $validrow++;
            }
        }
        if ($validrow == count($filter_values)) {
            $return[$key] = $value;
        }
    }
    return $return;
}

// Groups an array by value
function array_group($array = array(), $value = "")
{
    $response = [];
    foreach ($array as $k => $v) {
        $cat = !isset($v->{$value}) ? "" : strtolower($v->{$value});
        $response[$cat][] = $v;
    }
    return $response;
}

// Groups an array by value
function sum_of_object_array($array = array(), $v = "")
{
    $response = 0;
    foreach ($array as $key => $value) {
        $response += $value[$v];
    }
    return $response;
}

// Gives me an updated version of the user

function updated_filtered_user($array = array())
{
    $response = null;
    foreach ($array as $key => $value) {
        $response = $value;
    }
    return $response;
}

// checks if a string is valid url
function is_valid_url(string $url): bool
{
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

// get boolean value of a string
function get_boolean(string $trueFalse): bool
{
    return filter_var($trueFalse, FILTER_VALIDATE_BOOLEAN) !== false;
}

//returns my attendance array as an object
function updated_attendance($array = array())
{
    $response = null;
    foreach ($array as $key => $value) {
        $response = ["video_at" => $value["video_at"], "video_completed" => $value["video_completed"]];
    }
    return $response;
}


//Get a range of array indexes
function array_range($array = array(), $range = 1, $offset = 0)
{
    $result = array();
    $count = 1;
    $array = (array) $array;
    $range = $range === 1 ? count($array) : $range;
    foreach ($array as $key => $value) {
        if ($count >= $offset && $count <= $range) {
            $result[$key] = $value;
        }
        $count++;
    }
    return $result;
}

//Converts an array back to http GET (array_to_GET)
function array_serialize($array = array())
{
    $build = [];
    if (!empty($array)) {
        foreach ($array as $key => $val) {
            $key = trim($key);
            $val = trim(urlencode($val));
            $build[] = "$key=$val";
        }
    }
    return implode("&", $build);
}

//Converts GET-like string to an Array exploded by a given delimiter
function explodeToKey($delimiter, $array)
{
    $response = [];
    if (gettype($array) == 'array' && !empty($delimiter)) {
        foreach ($array as $key => $value) {
            $hold = explode($delimiter, $value);
            $hold = array_map("trim", $hold);
            if (isset($hold[1])) {
                $response[$hold[0]] = $hold[1];
            }
        }
    } else {
        $response = "second argument not an array";
    }
    return ($response);
}

//Searches a multidimentional array for a value and returns the parent key
function getdataType($array, $value)
{
    foreach ($array as $k => $v) {
        $key = array_search($value, $v);
        if ($key !== false) {
            return ($k);
        }
    }
    return (false);
}

//Extract values of specific keys from an array
function array_extract(array|object $main_array, array $extr_keys, bool $strict_extraction = true, bool $associate_keys = true)
{
    $return = [];
    if (gettype($main_array) === "object") {
        $main_array = toArray($main_array);
    }
    foreach ($extr_keys as $key) {
        if (isset($main_array[$key])) {
            if ($associate_keys) {
                $return[$key] = $main_array[$key];
            } else {
                array_push($return, $main_array[$key]);
            }
        } elseif ($strict_extraction) {
            return [];
        }
    }
    return $return;
}

//Rename the keys in an array to the values in another array where both keys match
function array_remap($main_array, $match_keys)
{
    $return = [];
    $main_array = (array) $main_array;
    $match_cols = array_keys((array) $match_keys);
    $match_vals = array_values((array) $match_keys);
    foreach ($main_array as $key => $value) {
        $index  = array_search($key, $match_cols);
        if ($index !== false) {
            $newkey = $match_vals[$index];
            $return[$newkey] = $value;
        }
    }
    return $return;
}
//Checks if a sring is json
function isJson($string)
{
    $json_val = json_decode($string);
    $bool_val = (json_last_error() == JSON_ERROR_NONE);
    return $json_val;
}

//Turns a string to a filename format
function strToFilename($str)
{
    $str = strtolower(str_replace(' ', '_', trim($str)));
    $str = preg_replace('/[^A-Za-z0-9\-_\/]/', '', $str);
    $str = str_replace('/', '-', $str);
    $str = str_replace('_-', '_', $str);
    $str = str_replace('-_', '_', $str);
    $str = str_replace('__', '_', $str);
    $str = str_replace('-', '_', $str);
    $str = str_replace('-', '_', $str);
    return (trim($str));
}

//Turns a string to a url format
function strToUrl($str)
{
    $str = str_replace('--', '-', str_replace('_', '-', strToFilename($str)));
    return $str;
}

function toSentence(string $string = ""): string
{
    return ucfirst(str_replace("-", " ", strToUrl($string)));
}

function formatNumber(string|int $number, string $currency = ""): string
{
    $currencies = ["" => "", "NAIRA" => "₦", "DOLLAR" => ""];
    $fmn = new NumberFormatter("en", NumberFormatter::DECIMAL);
    $fmn->setAttribute(NumberFormatter::FRACTION_DIGITS, 2);
    return $currencies[$currency] . $fmn->format($number);
}

// Appends more get variables to url
function add_get_to_url($url_string, $array)
{
    if (!empty($array)) {
        $get = [];
        $url_part = explode("?", $url_string);
        if (is_object($array)) {
            $array = toArray($array);
        }
        if (!empty($url_part[1])) {
            $get = explodeToKey("=", explode("&", $url_part[1]));
        }
        $url_part[1] = array_merge($get, $array);
        $url_part[1] = array_serialize($url_part[1]);
        $url_string = implode("?", $url_part);
    }
    return $url_string;
}

function _writeFile($file, $data, $returnData = false)
{
    $dir = dirname($file);
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    if (gettype($data) == 'array' || gettype($data) == 'object') {
        if (empty($data)) {
            return (false);
        }
        $data = utf8ize($data);
        $data = json_encode($data);
    }
    if (!empty($data)) {
        if ($handle = fopen($file, "w+")) {
            if (fwrite($handle, $data)) {
                if (file_exists($file) && !filesize($file)) {
                    unlink($file);
                }
                if ($returnData === true) {
                    return ($data);
                } else {
                    return (true);
                }
            } else {
                return (false);
            }
            fclose($handle);
        }
    } else {
        return (false);
    }
}


//Reads a file
function _readFile($file)
{
    $data = null;
    if (file_exists($file)) {
        $handle = fopen($file, 'r');
        $filesize = filesize($file);
        $data = ($filesize) ? fread($handle, filesize($file)) : json_encode([]);
        fclose($handle);
        if (!$filesize) {
            unlink($file);
        }
    }
    return ($data);
}

//Reads a directory
function _readDir($dir, $nested = false)
{
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }
    $files = scandir($dir);
    $files = array_filter($files, function ($file) {
        return ($file == "." || $file == "..") ? false : true;
    });
    return ($files);
}

//Best function to handle utf8 encoding of multi-dimensional array data
function utf8ize($d)
{
    if (is_array($d)) {
        foreach ($d as $k => $v) {
            $d[$k] = utf8ize($v);
        }
    } elseif (is_string($d)) {
        // Remove all non-printable charachters
        $d = preg_replace('/[^[:print:]]/', '', $d);
        return  trim(iconv('ISO-8859-1', 'UTF-8', $d));
    } elseif (is_object($d)) {
        foreach ($d as $k => $v) {
            $d->{$k} = utf8ize($v);
        }
    }
    return $d;
}

//Removes all special characters from a sting excluding;
function cleanUp($string = "", $excludes = [])
{
    $excludes = empty($excludes) ? "" : implode(",", $excludes);
    if (!empty($string)) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-_' . $excludes . ']/', '', $string); // Removes special chars.
    }
    return $string;
}

//Deletes all files and folders in a given Directory
function delete_files($dir)
{
    foreach (glob($dir . '/*') as $file) {
        if (is_dir($file)) {
            delete_files($file);
        } else {
            $dir = dirname($file);
            $tbn = "{$dir}/tbn";
            $tbn = str_replace($dir, $tbn, $file);
            if (file_exists($tbn)) {
                unlink($tbn);
            }
            unlink($file);
        };
    }
    rmdir($dir);
}

//Deletes files that have stayed longer than a given period
function clear_files_longer_than($days = 30, $dir = "")
{
    if (is_dir($dir)) {
        $today = strtotime(date('Y-m-d h:i:s'));
        $handle = opendir($dir);
        while (false !== ($file = readdir($handle))) {
            if ($file == '.' || $file == '..') {
                continue;
            }
            $thisfile = $dir . $file;
            $filetime = filectime($thisfile);
            $diff = round(($today - $filetime) / (3600 * 24));
            if ($diff > $days && !is_dir($thisfile)) {
                unlink($thisfile);
                continue;
            }
        }
    }
    clearstatcache();
}

// Generates token string
function random($l = 8)
{
    return substr(md5(uniqid(mt_rand(), true)), 0, $l);
}

//PHP version of AJAX
function curl_post($url = null, $fields = null, $headers = null)
{
    if (gettype($fields) == "object") {
        $fields = toArray($fields);
    }
    $postvars = http_build_query($fields);
    $ch = curl_init();
    if (empty($url) || gettype($url) !== "string") {
        return "Parameter 1 should be a url string";
    }
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, count($fields));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postvars);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

function curl_get($url)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_NOBODY, true); // remove body
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $head = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    return $head;
}

function curl_get_content($url = null, $islocalhost = false)
{
    if ($islocalhost) {
        $options = array(
            'ssl' => array(
                'cafile'            => "{$_SERVER['DOCUMENT_ROOT']}/cacert.pem",
                'verify_peer'       => true,
                'verify_peer_name'  => true,
            ),
        );
        $context = stream_context_create($options);
        $res = file_get_contents($url, false, $context);
    } else {
        $res = file_get_contents($url);
    }
    return $res;
}


function simple_encode($value)
{
    if (is_numeric($value)) {
        $value = "$value";
    }
    $value = str_replace(" ", "+", $value);
    // return substr(base64_encode(str_rot13($value)), 0, -1);
    return base64_encode(str_rot13($value));
}

function simple_decode($value)
{
    $value = str_rot13(base64_decode($value));
    return str_replace("+", " ", $value);
}

function withinRange(int $range1 = 0, int $range2 = 0, int $percent = 0): int
{
    $percentage = get_percent_of($percent, $range1);
    return $range2 < ($range1 + $percentage) &&  $range2 > ($range1 - $percentage);
}


function slugify($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);
    // trim
    $text = trim($text, '-');
    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);
    // lowercase
    $text = strtolower($text);
    if (empty($text)) {
        return 'n-a';
    }
    return $text;
}


function courseSlugify($text)
{
    // $courseSlug = slugify($text) . '-' . rand(1000, 9999);
    $courseSlug = slugify($text) . '-' . random(4);
    $exists = DB::table('courses')->where('slug', $courseSlug)->exists();
    if ($exists) {
        return courseSlugify($text);
    }
    return $courseSlug;
}


function getExceptionCode(\Throwable $th): int
{
    $codes = array_values(config('response'));
    return !in_array($th->getCode(), $codes) ? 400 : $th->getCode();
}

function toKobo(int $amount = 1): int
{
    return $amount * 100;
}

function toNaira(int $amount = 100): int
{
    return $amount / 100;
}



function isCloudinaryVideo($url)
{
    $pattern = '/^https:\/\/res\.cloudinary\.com\/[^\/]+\/video\/upload\/.+$/';
    return preg_match($pattern, $url);
}


function checkSortOrderDuplicates($sortArray)
{
    $sortOrders = [];
    foreach ($sortArray as $item) {
        foreach ($item as $id => $sortOrder) {
            // checks for number
            if (!is_int($sortOrder)) {
                throw new Exception("Sort order value must be an integer.");
            }
            if (in_array($sortOrder, $sortOrders, true)) {
                throw new Exception("The sort order values contain duplicates.");
            }
            $sortOrders[] = $sortOrder;
        }
    }
    return true;
}


function checkUserAuthorization($user, $userType, $record): bool
{
    if ($user->type !== $userType->admin) {
        if ($record->created_by !== $user->id) {
            return false;
        }
    }
    return true;
}



function checkVideo($video)
{

    if ($video instanceof UploadedFile) {

        if ($video->getError() !== UPLOAD_ERR_OK) {

            switch ($video->getError()) {
                case UPLOAD_ERR_INI_SIZE:
                case UPLOAD_ERR_FORM_SIZE:
                    $message = 'The uploaded file exceeds the maximum file size.';
                    break;
                case UPLOAD_ERR_PARTIAL:
                    $message = 'The uploaded file was only partially uploaded.';
                    break;
                case UPLOAD_ERR_NO_FILE:
                    $message = 'No file was uploaded.';
                    break;
                case UPLOAD_ERR_NO_TMP_DIR:
                    $message = 'Missing a temporary folder.';
                    break;
                case UPLOAD_ERR_CANT_WRITE:
                    $message = 'Failed to write file to disk.';
                    break;
                case UPLOAD_ERR_EXTENSION:
                    $message = 'A PHP extension stopped the file upload.';
                    break;
                default:
                    $message = 'Unknown upload error.';
                    break;
            }
            return ['message' => $message, "status" => false];
        }
        return ["status" => true, "data" => $video];
    }
}

function calcCourseProgress(array $lectures)
{
    $totalLectures = count($lectures);
    $attendanceCount =  array_sum(array_column($lectures, "user_attendance"));
    return $attendanceCount ? (int) get_percent($attendanceCount, $totalLectures) : 0;
}


// function CalcPercenChange($thisMonth, $lastMonth)
// {
//     $difference = $thisMonth - $lastMonth;
//     $average = ($thisMonth + $lastMonth) / 2;
//     $percentageDifference = ($difference / $average) * 100;

//     $percentageDifference = max(min($percentageDifference, 100), -100);
//     return round($percentageDifference, 2);
// }


function CalcPercenChange($thisMonth, $lastMonth)
{
    $difference = $thisMonth - $lastMonth;
    $average = ($thisMonth + $lastMonth) / 2;
    if ($average == 0) {
        return 0;
    }

    $percentageDifference = ($difference / $average) * 100;
    $percentageDifference = max(min($percentageDifference, 100), -100);
    return round($percentageDifference, 2);
}

function isBase64Encoded(string $string)
{
    if (base64_encode(base64_decode($string, true)) === $string) {
        return true;
    } else {
        return false;
    }
}



function isGoogleAuthImage(string $url)
{
    $pattern = '/^https:\/\/lh3\.googleusercontent\.com\/(?:[-\w]+\/)*[-\w]+=[-\w]+$/';
    return preg_match($pattern, $url) === 1 ? true : false;
}


function isValidBase64Image($imageData)
{
    if (preg_match('/^data:image\/(\w+);base64,/', $imageData, $matches)) {
        $imageType = $matches[1];
        $imageData = substr($imageData, strpos($imageData, ',') + 1);
        $imageData = base64_decode($imageData);

        if ($imageData === false) {
            return [
                'status' => false,
                'message' => 'Invalid base64 encoded data.',
            ];
        }

        return [
            'status' => true,
            'image_type' => $imageType,
            'image_data' => $imageData,
        ];
    }

    return [
        'status' => false,
        'message' => 'Invalid data URI scheme.',
    ];
}





function getLatestApprovedTransaction(array $transactions)
{
    $approvedTransactions = array_filter($transactions, function ($transaction) {
        return $transaction['status'] === config('data.approval.approved');
    });
    usort($approvedTransactions, function ($a, $b) {
        return strtotime($b['updated_at']) - strtotime($a['updated_at']);
    });
    $latestApprovedCourse = $approvedTransactions[0] ?? null;
    return $latestApprovedCourse;
}



function getDataFormat(string $timeFormat): string
{
    switch ($timeFormat) {
        case 'month':
            return 'Y-m';
        case 'week':
            return 'o-W';
        case 'day':
            return 'Y-m-d';
        case 'hour':
            return 'Y-m-d H';
        case 'minute':
            return 'Y-m-d H:i';
        case 'second':
            return 'Y-m-d H:i:s';
        default:
            return 'o-W';
    }
}





function formatCurrency($course_price, $currency)
{
    if ($currency == config('data.currencyCodes.usd')) {
        return  Number::currency($course_price, $currency);
    } else {
        return '₦' . Number::format($course_price, 2);
    }
}
