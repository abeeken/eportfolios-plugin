<?php
    /*
        Worker functions for ePortfolios
    */

    // === Remove from multidimensional array
    function unset_r($array, $key, $value){
        foreach($array as $subKey => $subArray){
            if($subArray[$key] == $value){
                unset($array[$subKey]);
            }
        }

        return($array);
    }

    // === Check if value is in multidimensional array
    function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
                return true;
            }
        }
    
        return false;
    }

    // === Explode last element - explodes a string and converts it into a 2 element array. Used if we have an identifier at the end of a string.
    function last_element($string, $separator){
        $parts = explode($separator, $string);
        $last = array_pop($parts);
        $parts = array(implode($separator, $parts), $last);
        return $parts;
    }

    // === Options from array - builds an array of options for a HTML form, adding "selected" where appropriate. Assumes array is stored as per thing types
    function options_from_array($values, $id_val, $label_val, $check_value){        
        $options = "";
        foreach($values as $value){
            // If we have either an empty id or label val var then assume this is a flat array, if not, catch those values
            if($id_val == "" || $label_val == ""){
                $optval = $value;
                $label = $value;
            } else {
                $optval = $value[$id_val];
                $label = $value[$label_val];
            }

            $options .= '<option value="'.$optval.'"';
            if($optval == $check_value){
                $options .= ' selected';
            }
            $options .= '>'.$label.'</option>';
        }
        return $options;
    }

    // === Current EP Usertype - returns the ep_usertype value for the current user
    function current_ep_usertype(){
        $current_user = wp_get_current_user(); // Currently logged in user
        $current_user_meta = get_user_meta($current_user->ID);
        $current_ep_usertype = $current_user_meta['ep_usertype'][0];
        return $current_ep_usertype;
    }
?>