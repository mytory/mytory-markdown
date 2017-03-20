<?php
// http://stackoverflow.com/a/1652937

function arrayStrLenMin($arr, $strictMode = false, $forLoop = false)
{
    $errArrZeroLength = -1; // Return value for error: Array is empty
    $errOtherType = -2;     // Return value for error: Found other type (than string in array)
    $errStrNone = -3;       // Return value for error: No strings found (in array)

    $arrLength = count($arr);
    if ($arrLength <= 0) {
        return $errArrZeroLength;
    }

    $strFirstFound = 0;
    foreach ($arr as $key => $val) {
        if (is_string($val)) {
            $min = strlen($val);
            $strFirstFound = $key;
            // echo("Key\tLength / Notification / Error\n");
            // echo("$key\tFound first string member at key with length: $min!\n");
            break;
        } else {
            if ($strictMode) {
                return $errOtherType;
            }
        } // At least 1 type other than string was found.
    }
    if (!isset($min)) {
        return $errStrNone;
    } // No string was found in array.

    // SpeedRatio of foreach/for is approximately 2/1 as dicussed at:
    // http://juliusbeckmann.de/blog/php-foreach-vs-while-vs-for-the-loop-battle.html

    // If $strFirstFound is found within the first 1/SpeedRatio (=0.5) of the array, "foreach" is faster!

    if (!$forLoop) {
        foreach ($arr as $key => $val) {
            if (is_string($val)) {
                $cur = strlen($val);
                // echo("$key\t$cur\n");
                if ($cur == 0) {
                    return $cur;
                } // 0 is the shortest possible string, so we can abort here.
                if ($cur < $min) {
                    $min = $cur;
                }
            }
            // else { echo("$key\tNo string!\n"); }
        }
    } // If $strFirstFound is found after the first 1/SpeedRatio (=0.5) of the array, "for" is faster!

    else {
        for ($i = $strFirstFound + 1; $i < $arrLength; $i++) {
            if (is_string($arr[$i])) {
                $cur = strlen($arr[$i]);
                // echo("$i\t$cur\n");
                if ($cur == 0) {
                    return $cur;
                } // 0 is the shortest possible string, so we can abort here.
                if ($cur < $min) {
                    $min = $cur;
                }
            }
            // else { echo("$i\tNo string!\n"); }
        }
    }

    return $min;
}

function strCommonPrefixByStr($arr, $strFindShortestFirst = false)
{
    $arrLength = count($arr);
    if ($arrLength < 2) {
        return false;
    }

    // Determine loop length
    /// Find shortest string in array: Can bring down iterations dramatically, but the function arrayStrLenMin() itself can cause ( more or less) iterations.
    if ($strFindShortestFirst) {
        $end = arrayStrLenMin($arr, true);
    } /// Simply start with length of first string in array: Seems quite clumsy, but may turn out effective, if arrayStrLenMin() needs many iterations.
    else {
        $end = strlen($arr[0]);
    }

    $commonStrMax = '';
    for ($i = 1; $i <= $end + 1; $i++) {
        // Grab the part from 0 up to $i
        $commonStrMax = substr($arr[0], 0, $i);
//        echo("Match: $i\t$commonStrMax\n");
        // Loop through all the values in array, and compare if they match
        foreach ($arr as $key => $str) {
//            echo("  Str: $key\t$str\n");
            // Didn't match, return the part that did match
            if ($commonStrMax != substr($str, 0, $i)) {
                return substr($commonStrMax, 0, $i - 1);
            }
        }
    }
    // Special case: No mismatch (hence no return) happened until loop end!
    return $commonStrMax; // Thus entire first common string is the common prefix!
}

function strCommonPrefixByChar($arr, $strFindShortestFirst = false)
{
    $arrLength = count($arr);
    if ($arrLength < 2) {
        return false;
    }

    // Determine loop length
    /// Find shortest string in array: Can bring down iterations dramatically, but the function arrayStrLenMin() itself can cause ( more or less) iterations.
    if ($strFindShortestFirst) {
        $end = arrayStrLenMin($arr, true);
    } /// Simply start with length of first string in array: Seems quite clumsy, but may turn out effective, if arrayStrLenMin() needs many iterations.
    else {
        $end = strlen($arr[0]);
    }

    for ($i = 0; $i <= $end + 1; $i++) {
        // Grab char $i
        $char = substr($arr[0], $i, 1);
//        echo("Match: $i\t");
//        echo(str_pad($char, $i + 1, " ", STR_PAD_LEFT));
//        echo("\n");
        // Loop through all the values in array, and compare if they match
        foreach ($arr as $key => $str) {
//            echo("  Str: $key\t$str\n");
            // Didn't match, return the part that did match
            if ($char != $str[$i]) { // Same functionality as ($char != substr($str, $i, 1)). Same efficiency?
                return substr($arr[0], 0, $i);
            }
        }
    }
    // Special case: No mismatch (hence no return) happened until loop end!
    return substr($arr[0], 0, $end); // Thus entire first common string is the common prefix!
}


function strCommonPrefixByNeighbour($arr)
{
    $arrLength = count($arr);
    if ($arrLength < 2) {
        return false;
    }

    /// Get the common string prefix of the first 2 strings
    $strCommonMax = strCommonPrefixByChar(array($arr[0], $arr[1]));
    if ($strCommonMax === false) {
        return false;
    }
    if ($strCommonMax == "") {
        return "";
    }
    $strCommonMaxLength = strlen($strCommonMax);

    /// Now start looping from the 3rd string
//    echo("-----\n");
    for ($i = 2; ($i < $arrLength) && ($strCommonMaxLength >= 1); $i++) {
//        echo("  STR: $i\t{$arr[$i]}\n");

        /// Compare the maximum common string with the next neighbour

        /*
        //// Compare by char: Method unsuitable!

        // Iterate from string end to string beginning
        for ($ii = $strCommonMaxLength - 1; $ii >= 0; $ii--) {
            echo("Match: $ii\t"); echo(str_pad($arr[$i][$ii], $ii+1, " ", STR_PAD_LEFT)); echo("\n");
            // If you find the first mismatch from the end, break.
            if ($arr[$i][$ii] != $strCommonMax[$ii]) {
                $strCommonMaxLength = $ii - 1; break;
                // BUT!!! We may falsely assume that the string from the first mismatch until the begining match! This new string neighbour string is completely "unexplored land", there might be differing chars closer to the beginning. This method is not suitable. Better use string comparison than char comparison.
            }
        }
        */

        //// Compare by string

        for ($ii = $strCommonMaxLength; $ii > 0; $ii--) {
//            echo("MATCH: $ii\t$strCommonMax\n");
            if (substr($arr[$i], 0, $ii) == $strCommonMax) {
                break;
            } else {
                $strCommonMax = substr($strCommonMax, 0, $ii - 1);
                $strCommonMaxLength--;
            }
        }
    }
    return substr($arr[0], 0, $strCommonMaxLength);
}


// Tests for finding the common prefix

/// Scenarios

//$filesLeastInCommon = array(
//    "/Vol/1/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/a/1",
//    "/Vol/2/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/a/2",
//    "/Vol/1/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/b/1",
//    "/Vol/1/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/b/2",
//    "/Vol/2/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/b/c/1",
//    "/Vol/2/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/a/1",
//);
//
//$filesLessInCommon = array(
//    "/Vol/1/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/a/1",
//    "/Vol/1/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/a/2",
//    "/Vol/1/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/b/1",
//    "/Vol/1/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/b/2",
//    "/Vol/2/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/b/c/1",
//    "/Vol/2/aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa/a/1",
//);
//
//$filesMoreInCommon = array(
//    "/Voluuuuuuuuuuuuuumes/1/a/a/1",
//    "/Voluuuuuuuuuuuuuumes/1/a/a/2",
//    "/Voluuuuuuuuuuuuuumes/1/a/b/1",
//    "/Voluuuuuuuuuuuuuumes/1/a/b/2",
//    "/Voluuuuuuuuuuuuuumes/2/a/b/c/1",
//    "/Voluuuuuuuuuuuuuumes/2/a/a/1",
//);
//
//$sameDir = array(
//    "/Volumes/1/a/a/",
//    "/Volumes/1/a/a/aaaaa/2",
//);
//
//$sameFile = array(
//    "/Volumes/1/a/a/1",
//    "/Volumes/1/a/a/1",
//);
//
//$noCommonPrefix = array(
//    "/Volumes/1/a/a/",
//    "/Volumes/1/a/a/aaaaa/2",
//    "Net/1/a/a/aaaaa/2",
//);
//
//$longestLast = array(
//    "/Volumes/1/a/a/1",
//    "/Volumes/1/a/a/aaaaa/2",
//);
//
//$longestFirst = array(
//    "/Volumes/1/a/a/aaaaa/1",
//    "/Volumes/1/a/a/2",
//);
//
//$one = array("/Volumes/1/a/a/aaaaa/1");
//
//$empty = array();


// Test Results for finding  the common prefix

/*

I tested my functions in many possible scenarios.
The results, the common prefixes, were always correct in all scenarios!
Just try a function call with your individual array!

Considering iteration efficiency, I also performed tests:

I put echo functions into the functions where iterations occur, and measured the number of CLI line output via:
php <script with strCommonPrefixByStr or strCommonPrefixByChar> | egrep "^  Str:" | wc -l   GIVES TOTAL ITERATION SUM.
php <Script with strCommonPrefixByNeighbour> | egrep "^  Str:" | wc -l   PLUS   | egrep "^MATCH:" | wc -l   GIVES TOTAL ITERATION SUM.

My hypothesis was proven:
strCommonPrefixByChar wins in situations where the strings have less in common in their beginning (=prefix).
strCommonPrefixByNeighbour wins where there is more in common in the prefixes.

*/

// Test Results Table
// Used Functions | Iteration amount | Remarks

//$result = (strCommonPrefixByStr($filesLessInCommon)); // 35
//$result = (strCommonPrefixByChar($filesLessInCommon)); // 35 // Same amount of iterations, but much fewer characters compared because ByChar instead of ByString!
//$result = (strCommonPrefixByNeighbour($filesLessInCommon)); // 88 + 42 = 130 // Loses in this category!

//$result = (strCommonPrefixByStr($filesMoreInCommon)); // 137
//$result = (strCommonPrefixByChar($filesMoreInCommon)); // 137 // Same amount of iterations, but much fewer characters compared because ByChar instead of ByString!
//$result = (strCommonPrefixByNeighbour($filesLeastInCommon)); // 12 + 4 = 16 // Far the winner in this category!

//echo("Common prefix of all members:\n");
//var_dump($result);


// Tests for finding the shortest string in array

// Arrays

//$empty = array();
//$noStrings = array(0, 1, 2, 3.0001, 4, false, true, 77);
//$stringsOnly = array("one", "two", "three", "four");
//$mixed = array(0, 1, 2, 3.0001, "four", false, true, "seven", 8888);

// Scenarios

// I list them from fewest to most iterations, which is not necessarily equivalent to slowest to fastest!
// For speed consider the remarks in the code considering the Speed ratio of foreach/for!

// Fewest iterations (immediate abort on "Found other type", use "for" loop)

//foreach (array($empty, $noStrings, $stringsOnly, $mixed) as $arr) {
//    echo("NEW ANALYSIS:\n");
//    echo("Result: " . arrayStrLenMin($arr, true, true) . "\n\n");
//}

/* Results:

    NEW ANALYSIS:
    Result: Array is empty!

    NEW ANALYSIS:
    Result: Found other type!

    NEW ANALYSIS:
    Key Length / Notification / Error
    0   Found first string member at key with length: 3!
    1   3
    2   5
    3   4
    Result: 3

    NEW ANALYSIS:
    Result: Found other type!

*/

//// Fewer iterations (immediate abort on "Found other type", use "foreach" loop)

// foreach( array($empty, $noStrings, $stringsOnly, $mixed) as $arr) {
//  echo("NEW ANALYSIS:\n");
//  echo("Result: " . arrayStrLenMin($arr, true, false) . "\n\n");
// }

/* Results:

    NEW ANALYSIS:
    Result: Array is empty!

    NEW ANALYSIS:
    Result: Found other type!

    NEW ANALYSIS:
    Key Length / Notification / Error
    0   Found first string member at key with length: 3!
    0   3
    1   3
    2   5
    3   4
    Result: 3

    NEW ANALYSIS:
    Result: Found other type!

*/

//// More iterations (No immediate abort on "Found other type", use "for" loop)

// foreach( array($empty, $noStrings, $stringsOnly, $mixed) as $arr) {
//  echo("NEW ANALYSIS:\n");
//  echo("Result: " . arrayStrLenMin($arr, false, true) . "\n\n");
// }

/* Results:

    NEW ANALYSIS:
    Result: Array is empty!

    NEW ANALYSIS:
    Result: No strings found!

    NEW ANALYSIS:
    Key Length / Notification / Error
    0   Found first string member at key with length: 3!
    1   3
    2   5
    3   4
    Result: 3

    NEW ANALYSIS:
    Key Length / Notification / Error
    4   Found first string member at key with length: 4!
    5   No string!
    6   No string!
    7   5
    8   No string!
    Result: 4

*/


//// Most iterations (No immediate abort on "Found other type", use "foreach" loop)

// foreach( array($empty, $noStrings, $stringsOnly, $mixed) as $arr) {
//  echo("NEW ANALYSIS:\n");
//  echo("Result: " . arrayStrLenMin($arr, false, false) . "\n\n");
// }

/* Results:

    NEW ANALYSIS:
    Result: Array is empty!

    NEW ANALYSIS:
    Result: No strings found!

    NEW ANALYSIS:
    Key Length / Notification / Error
    0   Found first string member at key with length: 3!
    0   3
    1   3
    2   5
    3   4
    Result: 3

    NEW ANALYSIS:
    Key Length / Notification / Error
    4   Found first string member at key with length: 4!
    0   No string!
    1   No string!
    2   No string!
    3   No string!
    4   4
    5   No string!
    6   No string!
    7   5
    8   No string!
    Result: 4

*/