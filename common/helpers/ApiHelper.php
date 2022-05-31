<?php
/**
 * Created by PhpStorm.
 * User: Như Lực
 * Phone : 0961196368
 * Date: 12/15/2017
 * Time: 10:01 AM
 */

namespace common\helpers;

use common\helpers\MyCurl;
use Yii;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use Firebase\JWT\JWT;


class ApiHelper
{
    /**
     * Get token
     * @return mixed
     */
    static function getAuthorization()
    {
        $authHeader = Yii::$app->request->getHeaders()->get('Authorization');
        if ($authHeader != null && preg_match("/^Bearer\\s+(.*?)$/", $authHeader, $matches))
            return isset($matches[1]) && trim($matches[1] != '') ? $matches[1] : null;
    }

    /**
     * @return mixed
     */
    static function parserToken()
    {
        $token = self::getAuthorization();
        $alg = Yii::$app->params['alt_jwt']; // get encode algorithm from config file
        $key = Yii::$app->params['key_jwt']; // get secret key from config file
        return JWT::decode($token, $key, [$alg]); // decode access token
    }

    /**
     * @return mixed
     */
    static function parserTokenFirebase($token)
    {
        $tks = explode('.', $token);
        if(count($tks) < 3){
            return null;
        }
        list($headb64, $bodyb64, $cryptob64) = $tks;
        if (null === ($header = JWT::jsonDecode(JWT::urlsafeB64Decode($headb64)))) {
            Yii::error('parserTokenFirebase Invalid header encoding');
            return null;
        }
        if (null === $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64))) {
            Yii::error('parserTokenFirebase Invalid claims encoding');
            return null;
        }
        if($payload->firebase->identities){
            return $payload->firebase->identities;
        };
        return null;
    }

    /**
     * Format phone number
     * Loai bo mot so loi do requet gui len khong nhan dc "+"
     * @param $phone
     * @return null|string
     */
    static function formatPhone($phone)
    {
        $phone = trim($phone);
        if ($phone == '')
            return null;
        $phone = str_replace('+', '', $phone);
        return "+$phone";
    }

    public static function convertSpectrumToRGB($spectrumRGB)
    {
        $r = ($spectrumRGB >> 16) & 255;
        $g = ($spectrumRGB >> 8) & 255; // 122
        $b = $spectrumRGB & 255; // 15
        return [$r, $g, $b];
    }

    /*
   **  Converts HSV to RGB values
   ** –––––––––––––––––––––––––––––––––––––––––––––––––––––
   **  Reference: http://en.wikipedia.org/wiki/HSL_and_HSV
   **  Purpose:   Useful for generating colours with
   **             same hue-value for web designs.
   **  Input:     Hue        (H) Integer 0-360
   **             Saturation (S) Integer 0-100
   **             Lightness  (V) Integer 0-100
   **  Output:    String "R,G,B"
   **             Suitable for CSS function RGB().
   */
    public static function convertHSBtoRGB($iH, $iS, $iV)
    {
        if ($iH < 0) $iH = 0;   // Hue:
        if ($iH > 360) $iH = 360; //   0-360
        if ($iS < 0) $iS = 0;   // Saturation:
        if ($iS > 100) $iS = 100; //   0-100
        if ($iV < 0) $iV = 0;   // Lightness:
        if ($iV > 100) $iV = 100; //   0-100
        $dS = $iS / 100.0; // Saturation: 0.0-1.0
        $dV = $iV / 100.0; // Lightness:  0.0-1.0
        $dC = $dV * $dS;   // Chroma:     0.0-1.0
        $dH = $iH / 60.0;  // H-Prime:    0.0-6.0
        $dT = $dH;       // Temp variable
        while ($dT >= 2.0) $dT -= 2.0; // php modulus does not work with float
        $dX = $dC * (1 - abs($dT - 1));     // as used in the Wikipedia link
        switch (floor($dH)) {
            case 0:
                $dR = $dC;
                $dG = $dX;
                $dB = 0.0;
                break;
            case 1:
                $dR = $dX;
                $dG = $dC;
                $dB = 0.0;
                break;
            case 2:
                $dR = 0.0;
                $dG = $dC;
                $dB = $dX;
                break;
            case 3:
                $dR = 0.0;
                $dG = $dX;
                $dB = $dC;
                break;
            case 4:
                $dR = $dX;
                $dG = 0.0;
                $dB = $dC;
                break;
            case 5:
                $dR = $dC;
                $dG = 0.0;
                $dB = $dX;
                break;
            default:
                $dR = 0.0;
                $dG = 0.0;
                $dB = 0.0;
                break;
        }
        $dM = $dV - $dC;
        $dR += $dM;
        $dG += $dM;
        $dB += $dM;
        $dR *= 255;
        $dG *= 255;
        $dB *= 255;
        return [round($dR), round($dG), round($dB)];
    }

    /**
     * @param $R
     * @param $G
     * @param $B
     * @return array
     */
    public static function convertRGBtoHSB($R, $G, $B)    // RGB values:    0-255, 0-255, 0-255
    {                                // HSV values:    0-360, 0-100, 0-100
        // Convert the RGB byte-values to percentages
        $R = ($R / 255);
        $G = ($G / 255);
        $B = ($B / 255);

        // Calculate a few basic values, the maximum value of R,G,B, the
        //   minimum value, and the difference of the two (chroma).
        $maxRGB = max($R, $G, $B);
        $minRGB = min($R, $G, $B);
        $chroma = $maxRGB - $minRGB;

        // Value (also called Brightness) is the easiest component to calculate,
        //   and is simply the highest value among the R,G,B components.
        // We multiply by 100 to turn the decimal into a readable percent value.
        $computedV = 100 * $maxRGB;

        // Special case if hueless (equal parts RGB make black, white, or grays)
        // Note that Hue is technically undefined when chroma is zero, as
        //   attempting to calculate it would cause division by zero (see
        //   below), so most applications simply substitute a Hue of zero.
        // Saturation will always be zero in this case, see below for details.
        if ($chroma == 0)
            return array(0, 0, $computedV);

        // Saturation is also simple to compute, and is simply the chroma
        //   over the Value (or Brightness)
        // Again, multiplied by 100 to get a percentage.
        $computedS = 100 * ($chroma / $maxRGB);

        // Calculate Hue component
        // Hue is calculated on the "chromacity plane", which is represented
        //   as a 2D hexagon, divided into six 60-degree sectors. We calculate
        //   the bisecting angle as a value 0 <= x < 6, that represents which
        //   portion of which sector the line falls on.
        if ($R == $minRGB)
            $h = 3 - (($G - $B) / $chroma);
        elseif ($B == $minRGB)
            $h = 1 - (($R - $G) / $chroma);
        else // $G == $minRGB
            $h = 5 - (($B - $R) / $chroma);

        // After we have the sector position, we multiply it by the size of
        //   each sector's arc (60 degrees) to obtain the angle in degrees.
        $computedH = 60 * $h;

        return array($computedH, $computedS, $computedV);
    }

    public static function convertRgbToSpectrum($rgb)
    {
        $hex_color = str_replace(':', '', $rgb);
        return hexdec($hex_color);
    }
}