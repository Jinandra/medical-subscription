<?php


/**
* Author: Jinandra
* Date: 21-10-2016
* Get the string as per defined limit
*
* @param  string  $string
* @param  integer $limit
* @return string
*/
function limitString($string, $limit = 100) {
  if (strlen($string) <= $limit) { return $string; }

  $tail  = max(10, $limit-10);
  $trunk = substr($string, 0, $tail);
  return preg_replace('/\.+$/', '', $trunk) . "...";
  /* $trunk .= strrev(preg_replace('~^..+?[\s,:]\b|^...~', '...', strrev(substr($string, $tail, $limit-$tail)))); */
  /* return $trunk; */
}

/**
 * Returns the description string or default
 * @param string $string string to output
 * @param string $defaultDescription string to output if $string is empty
 * @return string description
 */
function description ($string, $defaultDescription = 'No description available.') {
  if (empty($string)) { return $defaultDescription; }
  return $string;
}

function formatMediaType ($type) {
  if ($type === 'text') {
    return 'Document';
  }
  return ucfirst($type);
}


/**
* ucode Generator
* Author: Jinandra
* Date: 22-10-2016
*
* @return String $ucode
*/
function ucodeGenerate() {
    $characters = 'ABCDEFGHIJKMNPQRSTUVWXYZ23456789';
    $ucode = '';
    for ($i = 0; $i < 10; $i++) {
        if ($i > 0 && $i % 5 == 0) {
            $ucode .= "-";
        }
        $ucode .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $ucode;
}

/**
* style error for form fields
* Author: Jinandra
* Date: 23-10-2016
*
* @param array $errors 
* @param string $field
* @return String
*/
function styleErrorFunction($errors, $field) {
    return $errors->has($field) ? 'style="color: red;"' : '';
}

/**
* style error for form fields
* Author: Jinandra
* Date: 23-10-2016
*
* @param string $field 
* @param string $media
* @return String
*/
function oldOrObjectFunction($field, $media) {
    return is_null(old($field)) ? $media[$field] : old($field);
}

/**
 * Returns time ago of datetime ( x ago )
 * @param string $datetime datetime in string
 * @return string time ago
 */
function time_ago ($datetime) {
  $d = new Date($datetime);
  return $d->ago();
}

/**
 * Sanitizes output string to consume by javascript
 * Strip off the new line
 * @param string $string string to output
 * @return string sanitized string
 */
function sanitizeToJS ($string) {
  return trim(preg_replace('/\s+/', ' ', $string));
}

/**
 * Return user fullname
 * @param object $user user object
 * @return string user fullname
 */
function fullname ($user) {
  return $user->first_name.(empty($user->last_name) ? "" : " ".$user->last_name);
}
