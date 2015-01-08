<?php
/**
 * Strip punctuation from text.
 */
function strip_punctuation( $text )
{
    $urlbrackets    = '\[\]\(\)';
    $urlspacebefore = ':;\'_\*%@&?!' . $urlbrackets;
    $urlspaceafter  = '\.,:;\'\-_\*@&\/\\\\\?!#' . $urlbrackets;
    $urlall         = '\.,:;\'\-_\*%@&\/\\\\\?!#' . $urlbrackets;
 
    $specialquotes  = '\'"\*<>';
 
    $fullstop       = '\x{002E}\x{FE52}\x{FF0E}';
    $comma          = '\x{002C}\x{FE50}\x{FF0C}';
    $arabsep        = '\x{066B}\x{066C}';
    $numseparators  = $fullstop . $comma . $arabsep;
 
    $numbersign     = '\x{0023}\x{FE5F}\x{FF03}';
    $percent        = '\x{066A}\x{0025}\x{066A}\x{FE6A}\x{FF05}\x{2030}\x{2031}';
    $prime          = '\x{2032}\x{2033}\x{2034}\x{2057}';
    $nummodifiers   = $numbersign . $percent . $prime;
 
    return preg_replace(
        array(
        // Remove separator, control, formatting, surrogate,
        // open/close quotes.
            '/[\p{Z}\p{Cc}\p{Cf}\p{Cs}\p{Pi}\p{Pf}]/u',
        // Remove other punctuation except special cases
            '/\p{Po}(?<![' . $specialquotes .
                $numseparators . $urlall . $nummodifiers . '])/u',
        // Remove non-URL open/close brackets, except URL brackets.
            '/[\p{Ps}\p{Pe}](?<![' . $urlbrackets . '])/u',
        // Remove special quotes, dashes, connectors, number
        // separators, and URL characters followed by a space
            '/[' . $specialquotes . $numseparators . $urlspaceafter .
                '\p{Pd}\p{Pc}]+((?= )|$)/u',
        // Remove special quotes, connectors, and URL characters
        // preceded by a space
            '/((?<= )|^)[' . $specialquotes . $urlspacebefore . '\p{Pc}]+/u',
        // Remove dashes preceded by a space, but not followed by a number
            '/((?<= )|^)\p{Pd}+(?![\p{N}\p{Sc}])/u',
        // Remove consecutive spaces
            '/ +/',
        ),
        ' ',
        $text );
}

/**
 * Strip symbols from text.
 */
function strip_symbols( $text )
{
    $plus   = '\+\x{FE62}\x{FF0B}\x{208A}\x{207A}';
    $minus  = '\x{2012}\x{208B}\x{207B}';
 
    $units  = '\\x{00B0}\x{2103}\x{2109}\\x{23CD}';
    $units .= '\\x{32CC}-\\x{32CE}';
    $units .= '\\x{3300}-\\x{3357}';
    $units .= '\\x{3371}-\\x{33DF}';
    $units .= '\\x{33FF}';
 
    $ideo   = '\\x{2E80}-\\x{2EF3}';
    $ideo  .= '\\x{2F00}-\\x{2FD5}';
    $ideo  .= '\\x{2FF0}-\\x{2FFB}';
    $ideo  .= '\\x{3037}-\\x{303F}';
    $ideo  .= '\\x{3190}-\\x{319F}';
    $ideo  .= '\\x{31C0}-\\x{31CF}';
    $ideo  .= '\\x{32C0}-\\x{32CB}';
    $ideo  .= '\\x{3358}-\\x{3370}';
    $ideo  .= '\\x{33E0}-\\x{33FE}';
    $ideo  .= '\\x{A490}-\\x{A4C6}';
 
    return preg_replace(
        array(
        // Remove modifier and private use symbols.
            '/[\p{Sk}\p{Co}]/u',
        // Remove mathematics symbols except + - = ~ and fraction slash
            '/\p{Sm}(?<![' . $plus . $minus . '=~\x{2044}])/u',
        // Remove + - if space before, no number or currency after
            '/((?<= )|^)[' . $plus . $minus . ']+((?![\p{N}\p{Sc}])|$)/u',
        // Remove = if space before
            '/((?<= )|^)=+/u',
        // Remove + - = ~ if space after
            '/[' . $plus . $minus . '=~]+((?= )|$)/u',
        // Remove other symbols except units and ideograph parts
            '/\p{So}(?<![' . $units . $ideo . '])/u',
        // Remove consecutive white space
            '/ +/',
        ),
        ' ',
        $text );
}

/**
 * Strip numbers from text.
 */
function strip_numbers( $text )
{
    $urlchars      = '\.,:;\'=+\-_\*%@&\/\\\\?!#~\[\]\(\)';
    $notdelim      = '\p{L}\p{M}\p{N}\p{Pc}\p{Pd}' . $urlchars;
    $predelim      = '((?<=[^' . $notdelim . '])|^)';
    $postdelim     = '((?=[^'  . $notdelim . '])|$)';
 
    $fullstop      = '\x{002E}\x{FE52}\x{FF0E}';
    $comma         = '\x{002C}\x{FE50}\x{FF0C}';
    $arabsep       = '\x{066B}\x{066C}';
    $numseparators = $fullstop . $comma . $arabsep;
    $plus          = '\+\x{FE62}\x{FF0B}\x{208A}\x{207A}';
    $minus         = '\x{2212}\x{208B}\x{207B}\p{Pd}';
    $slash         = '[\/\x{2044}]';
    $colon         = ':\x{FE55}\x{FF1A}\x{2236}';
    $units         = '%\x{FF05}\x{FE64}\x{2030}\x{2031}';
    $units        .= '\x{00B0}\x{2103}\x{2109}\x{23CD}';
    $units        .= '\x{32CC}-\x{32CE}';
    $units        .= '\x{3300}-\x{3357}';
    $units        .= '\x{3371}-\x{33DF}';
    $units        .= '\x{33FF}';
    $percents      = '%\x{FE64}\x{FF05}\x{2030}\x{2031}';
    $ampm          = '([aApP][mM])';
 
    $digits        = '[\p{N}' . $numseparators . ']+';
    $sign          = '[' . $plus . $minus . ']?';
    $exponent      = '([eE]' . $sign . $digits . ')?';
    $prenum        = $sign . '[\p{Sc}#]?' . $sign;
    $postnum       = '([\p{Sc}' . $units . $percents . ']|' . $ampm . ')?';
    $number        = $prenum . $digits . $exponent . $postnum;
    $fraction      = $number . '(' . $slash . $number . ')?';
    $numpair       = $fraction . '([' . $minus . $colon . $fullstop . ']' .
        $fraction . ')*';
 
    return preg_replace(
        array(
        // Match delimited numbers
            '/' . $predelim . $numpair . $postdelim . '/u',
        // Match consecutive white space
            '/ +/u',
        ),
        ' ',
        $text );
}
    /**
    * Copyright (c) 2005 Richard Heyes (http://www.phpguru.org/)
    *
    * All rights reserved.
    *
    * This script is free software.
    */

    /**
    * PHP5 Implementation of the Porter Stemmer algorithm. Certain elements
    * were borrowed from the (broken) implementation by Jon Abernathy.
    *
    * Usage:
    *
    *  $stem = PorterStemmer::Stem($word);
    *
    * How easy is that?
    */

    class PorterStemmer
    {
        /**
        * Regex for matching a consonant
        * @var string
        */
        private static $regex_consonant = '(?:[bcdfghjklmnpqrstvwxz]|(?<=[aeiou])y|^y)';


        /**
        * Regex for matching a vowel
        * @var string
        */
        private static $regex_vowel = '(?:[aeiou]|(?<![aeiou])y)';


        /**
        * Stems a word. Simple huh?
        *
        * @param  string $word Word to stem
        * @return string       Stemmed word
        */
        public static function Stem($word)
        {
            if (strlen($word) <= 2) {
                return $word;
            }

            $word = self::step1ab($word);
            $word = self::step1c($word);
            $word = self::step2($word);
            $word = self::step3($word);
            $word = self::step4($word);
            $word = self::step5($word);

            return $word;
        }


        /**
        * Step 1
        */
        private static function step1ab($word)
        {
            // Part a
            if (substr($word, -1) == 's') {

                   self::replace($word, 'sses', 'ss')
                OR self::replace($word, 'ies', 'i')
                OR self::replace($word, 'ss', 'ss')
                OR self::replace($word, 's', '');
            }

            // Part b
            if (substr($word, -2, 1) != 'e' OR !self::replace($word, 'eed', 'ee', 0)) { // First rule
                $v = self::$regex_vowel;

                // ing and ed
                if (   preg_match("#$v+#", substr($word, 0, -3)) && self::replace($word, 'ing', '')
                    OR preg_match("#$v+#", substr($word, 0, -2)) && self::replace($word, 'ed', '')) { // Note use of && and OR, for precedence reasons

                    // If one of above two test successful
                    if (    !self::replace($word, 'at', 'ate')
                        AND !self::replace($word, 'bl', 'ble')
                        AND !self::replace($word, 'iz', 'ize')) {

                        // Double consonant ending
                        if (    self::doubleConsonant($word)
                            AND substr($word, -2) != 'll'
                            AND substr($word, -2) != 'ss'
                            AND substr($word, -2) != 'zz') {

                            $word = substr($word, 0, -1);

                        } else if (self::m($word) == 1 AND self::cvc($word)) {
                            $word .= 'e';
                        }
                    }
                }
            }

            return $word;
        }


        /**
        * Step 1c
        *
        * @param string $word Word to stem
        */
        private static function step1c($word)
        {
            $v = self::$regex_vowel;

            if (substr($word, -1) == 'y' && preg_match("#$v+#", substr($word, 0, -1))) {
                self::replace($word, 'y', 'i');
            }

            return $word;
        }


        /**
        * Step 2
        *
        * @param string $word Word to stem
        */
        private static function step2($word)
        {
            switch (substr($word, -2, 1)) {
                case 'a':
                       self::replace($word, 'ational', 'ate', 0)
                    OR self::replace($word, 'tional', 'tion', 0);
                    break;

                case 'c':
                       self::replace($word, 'enci', 'ence', 0)
                    OR self::replace($word, 'anci', 'ance', 0);
                    break;

                case 'e':
                    self::replace($word, 'izer', 'ize', 0);
                    break;

                case 'g':
                    self::replace($word, 'logi', 'log', 0);
                    break;

                case 'l':
                       self::replace($word, 'entli', 'ent', 0)
                    OR self::replace($word, 'ousli', 'ous', 0)
                    OR self::replace($word, 'alli', 'al', 0)
                    OR self::replace($word, 'bli', 'ble', 0)
                    OR self::replace($word, 'eli', 'e', 0);
                    break;

                case 'o':
                       self::replace($word, 'ization', 'ize', 0)
                    OR self::replace($word, 'ation', 'ate', 0)
                    OR self::replace($word, 'ator', 'ate', 0);
                    break;

                case 's':
                       self::replace($word, 'iveness', 'ive', 0)
                    OR self::replace($word, 'fulness', 'ful', 0)
                    OR self::replace($word, 'ousness', 'ous', 0)
                    OR self::replace($word, 'alism', 'al', 0);
                    break;

                case 't':
                       self::replace($word, 'biliti', 'ble', 0)
                    OR self::replace($word, 'aliti', 'al', 0)
                    OR self::replace($word, 'iviti', 'ive', 0);
                    break;
            }

            return $word;
        }


        /**
        * Step 3
        *
        * @param string $word String to stem
        */
        private static function step3($word)
        {
            switch (substr($word, -2, 1)) {
                case 'a':
                    self::replace($word, 'ical', 'ic', 0);
                    break;

                case 's':
                    self::replace($word, 'ness', '', 0);
                    break;

                case 't':
                       self::replace($word, 'icate', 'ic', 0)
                    OR self::replace($word, 'iciti', 'ic', 0);
                    break;

                case 'u':
                    self::replace($word, 'ful', '', 0);
                    break;

                case 'v':
                    self::replace($word, 'ative', '', 0);
                    break;

                case 'z':
                    self::replace($word, 'alize', 'al', 0);
                    break;
            }

            return $word;
        }


        /**
        * Step 4
        *
        * @param string $word Word to stem
        */
        private static function step4($word)
        {
            switch (substr($word, -2, 1)) {
                case 'a':
                    self::replace($word, 'al', '', 1);
                    break;

                case 'c':
                       self::replace($word, 'ance', '', 1)
                    OR self::replace($word, 'ence', '', 1);
                    break;

                case 'e':
                    self::replace($word, 'er', '', 1);
                    break;

                case 'i':
                    self::replace($word, 'ic', '', 1);
                    break;

                case 'l':
                       self::replace($word, 'able', '', 1)
                    OR self::replace($word, 'ible', '', 1);
                    break;

                case 'n':
                       self::replace($word, 'ant', '', 1)
                    OR self::replace($word, 'ement', '', 1)
                    OR self::replace($word, 'ment', '', 1)
                    OR self::replace($word, 'ent', '', 1);
                    break;

                case 'o':
                    if (substr($word, -4) == 'tion' OR substr($word, -4) == 'sion') {
                       self::replace($word, 'ion', '', 1);
                    } else {
                        self::replace($word, 'ou', '', 1);
                    }
                    break;

                case 's':
                    self::replace($word, 'ism', '', 1);
                    break;

                case 't':
                       self::replace($word, 'ate', '', 1)
                    OR self::replace($word, 'iti', '', 1);
                    break;

                case 'u':
                    self::replace($word, 'ous', '', 1);
                    break;

                case 'v':
                    self::replace($word, 'ive', '', 1);
                    break;

                case 'z':
                    self::replace($word, 'ize', '', 1);
                    break;
            }

            return $word;
        }


        /**
        * Step 5
        *
        * @param string $word Word to stem
        */
        private static function step5($word)
        {
            // Part a
            if (substr($word, -1) == 'e') {
                if (self::m(substr($word, 0, -1)) > 1) {
                    self::replace($word, 'e', '');

                } else if (self::m(substr($word, 0, -1)) == 1) {

                    if (!self::cvc(substr($word, 0, -1))) {
                        self::replace($word, 'e', '');
                    }
                }
            }

            // Part b
            if (self::m($word) > 1 AND self::doubleConsonant($word) AND substr($word, -1) == 'l') {
                $word = substr($word, 0, -1);
            }

            return $word;
        }


        /**
        * Replaces the first string with the second, at the end of the string. If third
        * arg is given, then the preceding string must match that m count at least.
        *
        * @param  string $str   String to check
        * @param  string $check Ending to check for
        * @param  string $repl  Replacement string
        * @param  int    $m     Optional minimum number of m() to meet
        * @return bool          Whether the $check string was at the end
        *                       of the $str string. True does not necessarily mean
        *                       that it was replaced.
        */
        private static function replace(&$str, $check, $repl, $m = null)
        {
            $len = 0 - strlen($check);

            if (substr($str, $len) == $check) {
                $substr = substr($str, 0, $len);
                if (is_null($m) OR self::m($substr) > $m) {
                    $str = $substr . $repl;
                }

                return true;
            }

            return false;
        }


        /**
        * What, you mean it's not obvious from the name?
        *
        * m() measures the number of consonant sequences in $str. if c is
        * a consonant sequence and v a vowel sequence, and <..> indicates arbitrary
        * presence,
        *
        * <c><v>       gives 0
        * <c>vc<v>     gives 1
        * <c>vcvc<v>   gives 2
        * <c>vcvcvc<v> gives 3
        *
        * @param  string $str The string to return the m count for
        * @return int         The m count
        */
        private static function m($str)
        {
            $c = self::$regex_consonant;
            $v = self::$regex_vowel;

            $str = preg_replace("#^$c+#", '', $str);
            $str = preg_replace("#$v+$#", '', $str);

            preg_match_all("#($v+$c+)#", $str, $matches);

            return count($matches[1]);
        }


        /**
        * Returns true/false as to whether the given string contains two
        * of the same consonant next to each other at the end of the string.
        *
        * @param  string $str String to check
        * @return bool        Result
        */
        private static function doubleConsonant($str)
        {
            $c = self::$regex_consonant;

            return preg_match("#$c{2}$#", $str, $matches) AND $matches[0]{0} == $matches[0]{1};
        }


        /**
        * Checks for ending CVC sequence where second C is not W, X or Y
        *
        * @param  string $str String to check
        * @return bool        Result
        */
        private static function cvc($str)
        {
            $c = self::$regex_consonant;
            $v = self::$regex_vowel;

            return     preg_match("#($c$v$c)$#", $str, $matches)
                   AND strlen($matches[1]) == 3
                   AND $matches[1]{2} != 'w'
                   AND $matches[1]{2} != 'x'
                   AND $matches[1]{2} != 'y';
        }
    }

?>
