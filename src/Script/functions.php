<?php

/**
 * Encode javascript functions without escaping them
 *
 * @param $json
 * @return string
 */
function json_encode_with_functions($json)
{
    //if it's just a string, needs to be treated differently
    if (is_string($json)) {
        if (strpos($json, 'function') === 0) {
            return $json;
        }
        return json_encode($json);
    }

    $strtr = array();

    _json_traverse($json, $strtr);
    $final_json = json_encode($json);

    return strtr($final_json, $strtr);
}

/**
 * List the functions
 *
 * @param $array
 * @param $strtr
 */
function _json_traverse(&$array, &$strtr)
{
    foreach ($array as &$value) {
        if (is_array($value) or is_object($value)) {
            _json_traverse($value, $strtr);
        } elseif (strpos($value, 'function') === 0) {
            $value_data = $value;
            $value = '%' . md5($value) . '%';
            $strtr['"' . $value . '"'] = $value_data;
        }
    }
}
