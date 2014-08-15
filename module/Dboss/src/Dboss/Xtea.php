<?php
// http://wiki.secondlife.com/wiki/XTEA_Strong_Encryption_Implementation
//************************************************//
//* Sleight's PHP XTEA encryption/decryption v3  *//
//* Modified by SleightOf Hand for Stability and *//
//* intercommunication between PHP & LSL         *//
//************************************************//
// NOTE: This version only encodes 60 bits per 64-bit block!
// This code is public domain.
// Sleight was here 20070522
// masa was here 20070315
// so was strife 20070315
// so was adz 20080812
// gave this some class 20080201 JB Kraft
//
// This was converted from the LSL version by
// SleightOf Hand to allow Strong encryption
// between LSL and PHP. If you find this usefull
// any donations apreciated.
//************************************************//
//* XTEA IMPLEMENTATION                          *//
//************************************************//
 
/**
 * PHP 5 class to do XTEA crypting
 *
 * $xtea = new XTEA( "mypassword" );
 * $crypted = $xtea->encrypt( "Some bogus string" );
 * echo "Encrypted: " . bin2hex($crypted);
 * echo "Decrypted: " . $xtea->decrypt( $crypted );
 *
 * @package whatevah
 * @author JB Kraft
 **/
namespace Dboss;


class Xtea
{
 
  private $_XTEA_DELTA      = 0x9E3779B9; // (sqrt(5) - 1) * 2^31
  private $_xtea_num_rounds = 6;
  private $_xtea_key        = array(0, 0, 0, 0);
  private $_base64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
 
 
  /**
   * CTOR
   *
   * @param  $passwd the password to use for crypting
   * @author JB Kraft
   **/
  public function __construct( $passwd )
  {
    $this->xtea_key_from_string( $passwd );
  }
 
  /**
   * Encrypt a full string using XTEA.
   *
   * @param $str the string to encrypt
   * @return the encrypted string
   * @author JB Kraft
   **/
  public function encrypt( $str ) 
  {
   // encode Binany string to Base64
   $str = base64_encode($str);
 
   // remove trailing =s so we can do our own 0 padding
   $i = strpos($str, '=', 0);
   if ( $i !== FALSE  ){
    $str = substr( $str, 0, $i);
   }
   // we don't want to process padding, so get length before adding it
   $len = strlen($str);
   // zero pad
   $str .= "AAAAAAAAAA=";
   $result = "";
   $i = 0;
 
   do {
    // encipher 30 (5*6) bits at a time.
    $enc1 = $this->base64_integer(substr($str , $i , 5) . "A==");
    $i += 5;
    $enc2 = $this->base64_integer(substr($str , $i , 5) . "A==");
    $i += 5;
    $result .= $this->xtea_encipher($enc1, $enc2);
   } while ( $i < $len );
   return $result; //Return Encrypted string
  }
 
  /**
   * Decrypt a full string using XTEA
   *
   * @param $str the string to decrypt
   * @return the decrypted string
   * @author JB Kraft
   **/
  public function decrypt( $str ) 
  {
   $len = strlen($str);
   $i = 0;
   $result = '';
   do {
    $dec1 = $this->base64_integer(substr($str , $i , 6)."==");
    $i += 6;
    $dec2 = $this->base64_integer(substr($str , $i , 6)."==");
    $i += 6;
    $result .= $this->xtea_decipher( $dec1, $dec2);
   } while ( $i < $len );
 
   // Replace multiple trailing zeroes with a single one
   $i = strlen($result);
   while ( substr($result, --$i, 1) == "A" );
   $result = substr($result, 0, $i+1);
   $i = strlen($result);
   $mod = $i%4; //Depending on encoded length diffrent appends are needed
   if($mod == 1) $result .= "A==";
   else if($mod == 2 ) $result .= "==";
   else if($mod == 3) $result .= "=";
 
   return base64_decode( $result );
  }
 
  // -----------------------------
  // stuff below here is protected
  // -----------------------------
 
  // Returns Integer based on 8 byte Base64 Code XXXXXX== (llBase64ToInteger)
  protected function base64_integer($str)
  {
   if(strlen($str) != 8) return 0;
   return ((strpos($this->_base64,$str{0}) << 26)|
   (strpos($this->_base64,$str{1}) << 20)|
   (strpos($this->_base64,$str{2}) << 14)|
   (strpos($this->_base64,$str{3}) << 8) |
   (strpos($this->_base64,$str{4}) << 2) |
   (strpos($this->_base64,$str{5}) >> 4));
  }
 
  // Returns 8 Byte Base64 code based on 32 bit integer ((llIntegerToBase64)
  protected function integer_base64($int)
  {
   if($int != (integer) $int) return 0;
   return  $this->_base64{($int >> 26 & 0x3F)} .
   $this->_base64{($int >> 20 & 0x3F)} .
   $this->_base64{($int >> 14 & 0x3F)} .
   $this->_base64{($int >> 8 & 0x3F)}  .
   $this->_base64{($int >> 2 & 0x3F)}  .
   $this->_base64{($int << 4 & 0x3F)} . "==";
  }
 
  //strict 32 bit addition using logic
  protected function binadd($val1 , $val2)
  {
   $tc = $val1 & $val2;
   $ta = $val1 ^ $val2;
   do{
    $tac = ($tc << 1) & 0x0FFFFFFFF;
    $tc = $ta & $tac;
    $ta = $ta ^ $tac;
   }while($tc);
   return $ta; // $ta will now be the result so return it
  }
 
  // Convers any string to a 32 char MD5 string and then to a list of
  // 4 * 32 bit integers = 128 bit Key.
  protected function xtea_key_from_string( $str ) 
  {
   $str = md5($str . ":0"); // Use nonce = 0 in LSL for same output
   $this->_xtea_key[0] = hexdec(substr($str,0,8));
   $this->_xtea_key[1] = hexdec(substr($str,8,8));
   $this->_xtea_key[2] = hexdec(substr($str,16,8));
   $this->_xtea_key[3] = hexdec(substr($str,24,8));
  }
 
  // Encipher two integers and return the result as a 12-byte string
  // containing two base64-encoded integers.
  protected function xtea_encipher( $v0 , $v1 ) 
  {
   $num_rounds = $this->_xtea_num_rounds;
   $sum = 0;
   do {
    // LSL only has 32 bit integers. However PHP automatically changes
    // 32 bit integers to 64 bit floats as necessary. This causes
    // incompatibilities between the LSL Encryption and the PHP
    // counterpart. I got round this by changing all addition to
    // binary addition using logical & and ^ and loops to handle bit
    // carries. This forces the 32 bit integer to remain 32 bits as
    // I mask out any carry over 32 bits. this bring the output of the
    // encrypt routine to conform with the output of its LSL counterpart.
 
    // LSL does not have unsigned integers, so when shifting right we
    // have to mask out sign-extension bits.
 
    // calculate ((($v1 << 4) ^ (($v1 >> 5) & 0x07FFFFFF)) + $v1)
    $v0a = $this->binadd((($v1 << 4) ^ (($v1 >> 5) & 0x07FFFFFF)) , $v1);
    // calculate ($sum + $this->_xtea_key[$sum & 3])
    $v0b = $this->binadd($sum , $this->_xtea_key[$sum & 3]);
    // Calculate ($v0 + ((($v1 << 4) ^ (($v1 >> 5) & 0x07FFFFFF)) + $v1)
    //            ^ ($sum + $this->_xtea_key[$sum & 3]))
    $v0 = $this->binadd($v0 , ($v0a  ^ $v0b));
 
    //Calculate ($sum + $this->_XTEA_DELTA)
    $sum = $this->binadd($sum , $this->_XTEA_DELTA);
 
    //Calculate ((($v0 << 4) ^ (($v0 >> 5) & 0x07FFFFFF)) + $v0)
    $v1a = $this->binadd((($v0 << 4) ^ (($v0 >> 5) & 0x07FFFFFF))  , $v0);
    // Calculate ($sum + $this->_xtea_key[($sum >>11) & 3])
    $v1b = $this->binadd($sum , $this->_xtea_key[($sum >>11) & 3]);
    //Calculate ($v1 + ((($v0 << 4) ^ (($v0 >> 5) & 0x07FFFFFF)) + $v0
    //           ^ ($sum & $this->_xtea_key[($sum >>11) & 3]))
    $v1 = $this->binadd($v1 , ($v1a  ^ $v1b));
   } while( $num_rounds = ~-$num_rounds );
   //return only first 6 chars to remove "=="'s and compact encrypted text.
   return substr($this->integer_base64($v0),0,6) . substr($this->integer_base64($v1),0,6);
  }
 
  // Decipher two base64-encoded integers and return the FIRST 30 BITS of
  // each as one 10-byte base64-encoded string.
  protected function xtea_decipher( $v0, $v1 ) 
  {
   $num_rounds = $this->_xtea_num_rounds;
   $sum = 0; // $this->_XTEA_DELTA * $this->_xtea_num_rounds;
   $tda = $this->_XTEA_DELTA;
   do{ // Binary multiplication using binary manipulation
    if($num_rounds & 1){
     $sum = $this->binadd($sum , $tda);
    }
    $num_rounds = $num_rounds >> 1;
    $tda = ($tda << 1) & 0x0FFFFFFFF;
   }while($num_rounds);
   $num_rounds = $this->_xtea_num_rounds; // reset $num_rounds back to its propper setting;
 
   do {
    // LSL only has 32 bit integers. However PHP automatically changes
    // 32 bit integers to 64 bit floats as necessary. This causes
    // incompatibilities between the LSL Encryption and the PHP
    // counterpart. I got round this by changing all addition to
    // binary addition using logical & and ^ and loops to handle bit
    // carries. This forces the 32 bit integer to remain 32 bits as
    // I mask out any carry over 32 bits. this bring the output of the
    // decrypt routine to conform with the output of its LSL counterpart.
    // Subtractions are handled by using 2's compliment
 
    // LSL does not have unsigned integers, so when shifting right we
    // have to mask out sign-extension bits.
 
    // calculate ((($v0 << 4) ^ (($v0 >> 5) & 0x07FFFFFF)) + $v0)
    $v1a = $this->binadd((($v0 << 4) ^ (($v0 >> 5) & 0x07FFFFFF)) , $v0);
    // calculate ($sum + $this->_xtea_key[($sum>>11) & 3])
    $v1b = $this->binadd($sum , $this->_xtea_key[($sum>>11) & 3]);
    //Calculate 2's compliment of ($v1a ^ $v1b) for subtraction
    $v1c = $this->binadd((~($v1a ^ $v1b)) , 1);
    //Calculate ($v1 - ((($v0 << 4) ^ (($v0 >> 5) & 0x07FFFFFF)) + $v0)
    //            ^ ($sum + $this->_xtea_key[($sum>>11) & 3]))
    $v1 = $this->binadd($v1 , $v1c);
 
    // Calculate new $sum based on $num_rounds - 1
    $tnr = $num_rounds - 1;  // Temp $num_rounds
    $sum = 0; // $this->_XTEA_DELTA * ($num_rounds - 1);
    $tda = $this->_XTEA_DELTA;
    do{ // Binary multiplication using binary manipulation
     if($tnr & 1){
      $sum = $this->binadd($sum , $tda);
     }
     $tnr = $tnr >> 1;
     $tda = ($tda << 1) & 0x0FFFFFFFF;
    }while($tnr);
 
    //Calculate ((($v1 << 4) ^ (($v1 >> 5) & 0x07FFFFFF)) + $v1)
    $v0a = $this->binadd((($v1 << 4) ^ (($v1 >> 5) & 0x07FFFFFF)) , $v1);
    //Calculate ($sum + $this->_xtea_key[$sum & 3])
    $v0b = $this->binadd($sum , $this->_xtea_key[$sum & 3]);
    //Calculate 2's compliment of ($v0a ^ $v0b) for subtraction
    $v0c = $this->binadd((~($v0a ^ $v0b)) , 1);
    //Calculate ($v0 - ((($v1 << 4) ^ (($v1 >> 5) & 0x07FFFFFF)) + $v1
    //           ^ ($sum + $this->_xtea_key[$sum & 3]))
    $v0 = $this->binadd($v0 , $v0c);
   } while ( $num_rounds = ~-$num_rounds );
 
   return substr($this->integer_base64($v0), 0, 5) . substr($this->integer_base64($v1), 0, 5);
  }
 
}