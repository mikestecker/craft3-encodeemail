<?php
/**
 * Encode Email plugin for Craft CMS 3.x
 *
 * Protect email addresses in your templates from robots.
 *
 * @link      http://github.com/mikestecker
 * @copyright Copyright (c) 2017 Mike Stecker
 */

namespace mikestecker\encodeemail\twigextensions;

use mikestecker\encodeemail\EncodeEmail;

use Craft;

/**
 * Twig can be extended in many ways; you can add extra tags, filters, tests, operators,
 * global variables, and functions. You can even extend the parser itself with
 * node visitors.
 *
 * http://twig.sensiolabs.org/doc/advanced.html
 *
 * @author    Mike Stecker
 * @package   EncodeEmail
 * @since     1.0.0
 */
class EncodeEmailTwigExtension extends \Twig_Extension
{

    private $count = 1;


    public function getName()
    {
        return 'EncodeEmail';
    }

    /**
     * Returns an array of Twig filters, used in Twig templates via:
     *
     *      {{ 'something' | someFilter }}
     *
     * @return array
     */
    public function getFilters()
    {
        return array(
          new \Twig_SimpleFilter('encode', [$this, 'encode'], array('is_safe' => array('html'))),
          new \Twig_SimpleFilter('rot13', [$this, 'rot13'], array('is_safe' => array('html'))),
          new \Twig_SimpleFilter('entities', [$this, 'entities'], array('is_safe' => array('html'))),
        );
    }

    /**
     * Encode string using Rot13.  This function does the same as the Rot13 function
     * and only exists as it may be easier for some folks to remember 'encode'
     *
     * @param  string $string Value to be encoded
     * @return mixed          Returns Rot13 encoded string
     */
    public function encode($string)
    {

      return $this->_encodeStringRot13($string);
    }

    /**
     * Encode string using Rot13.
     *
     * @param  string $string Value to be encoded
     * @return mixed          Returns Rot13 encoded string
     */
    public function rot13($string)
    {
      return $this->_encodeStringRot13($string);
    }

    /**
     * Encode string using HTML Entities.
     *
     * @param  string $string Value to be encoded
     * @return mixed          Returns string encoded as HTML Entities
     */
    public function entities($string)
    {
      return $this->_encodeHtmlEntities($string);
    }

    /**
     * Returns a rot13 encrypted string as well as a JavaScript decoder function.
     * http://snipplr.com/view/6037/
     *
     * @param  string $string Value to be encoded
     * @return mixed          An encoded string and javascript decoder function
     */
    private function _encodeStringRot13($string)
    {;
      $rot13encryptedString = str_replace('"', '\"', str_rot13($string));

      $uniqueId = uniqid();
      $countId  = $this->count++;
      $ajaxId   = (Craft::$app->getRequest()->getIsAjax()) ? '-ajax' : '';

      $encodeId = 'encodeemail-' . $uniqueId . '-' . $countId . $ajaxId;

      $encodedString = '
    <span id="' . $encodeId . '"></span>
    <script type="text/javascript">
      var encodeemailRot13String = "' . $rot13encryptedString . '";
      var encodeemailRot13 = encodeemailRot13String.replace(/[a-zA-Z]/g, function(c){return String.fromCharCode((c<="Z"?90:122)>=(c=c.charCodeAt(0)+13)?c:c-26);});
      document.getElementById("' . $encodeId . '").innerHTML =
      encodeemailRot13;
        </script>';

      return $encodedString;
    }

    /**
     * Returns a string converted to html entities
     * http://goo.gl/LPhtJ
     *
     * @param  string $string Value to be encoded
     * @return mixed          Returns a string converted to html entities
     */
    private function _encodeHtmlEntities($string)
    {
      $string = mb_convert_encoding($string, 'UTF-32', 'UTF-8');
      $t      = unpack("N*", $string);
      $t      = array_map(function ($n)
      {
        return "&#$n;";
      }, $t);

      return implode("", $t);
    }
}
