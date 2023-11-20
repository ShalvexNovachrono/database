<?php 
    
    $just_date = date("d/m/Y");
    
    $just_time = date("h:i");
    $date_time = "_".date("Ymd").date("hi");
    $just_date_format2 = date("Y/m/d");

    function checkToday($time) {
        $convertToUNIXtime = strtotime($time);
        $todayUNIXtime = strtotime('today');
    
        return $convertToUNIXtime === $todayUNIXtime;
    }
    
    function getRandomWord($len) {
        $word = array_merge(range('a', 'z'), range('A', 'Z'));
        shuffle($word);
        return substr(implode($word), 0, $len);
    }
    
    function validate($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function text_to_number($n) {
        $n_string = $n;
        $n_array_ord = Array();
        $i = 0;
        for ($i;$i<=strlen($n_string);) {
          if ($i === strlen($n_string)) {
            break;
          }
          array_push($n_array_ord, ord($n_string[$i]));
          $i++;
    
        }
        $n_array_ord_string = "";
        foreach($n_array_ord as $t) {
          if ($n_array_ord_string === "") {
            $n_array_ord_string = $n_array_ord_string . $t;
          } else {
            $n_array_ord_string = $n_array_ord_string . "," . $t;
          }
        }
        $n = $n_array_ord_string;
        return $n;
    }

    function number_to_text($n) {
        $n_numbers = explode(",", $n);
        $n_string = "";
        foreach($n_numbers as $t) {
          $n_string = $n_string.chr($t);
        }
        $n = $n_string;
        return $n;
    }

    function host_name_extractor($n) {
        $n = explode('//', $n);
        $n = $n[1];
        $n = explode('/', $n);
        return $n[0];
    }

    function zipText($text) {
        return gzcompress($text);
    }

    function upZipText($text) {
        return gzuncompress($text);
    } 

