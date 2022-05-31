<?php
namespace common\helpers;
/**
 * Description of CVietnameseTools
 *
 * @version 1.0
 * @since 1 Nov 2011
 * @author Nguyen Chi Thuc, gthuc.nguyen@gmail.com
 * @copyright 2011
 * 
 */
class CVietnameseTools {
    //put your code here
    
    private static $_vi_lower         = array('á', 'à', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'đ', 'é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ', 'í', 'ì', 'ỉ', 'ĩ', 'ị', 'ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự', 'ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ');
    private static $_vi_lower_nosigns = array('a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'd', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'y', 'y', 'y', 'y', 'y');
    private static $_vi_upper         = array('Á', 'À', 'Ả', 'Ã', 'Ạ', 'Ă', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ', 'Ặ', 'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ', 'Đ', 'É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ', 'Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị', 'Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ', 'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ', 'Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự', 'Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ');
    private static $_vi_upper_nosigns = array('A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'D', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'Y', 'Y', 'Y', 'Y', 'Y');
    private static $_en_lower         = array('a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z');
    private static $_en_upper         = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    
    private static $_vi_all_lower      = array('a', 'á', 'à', 'ả', 'ã', 'ạ', 'ă', 'ắ', 'ằ', 'ẳ', 'ẵ', 'ặ', 'â', 'ấ', 'ầ', 'ẩ', 'ẫ', 'ậ', 'b', 'c', 'd', 'đ', 'e', 'é', 'è', 'ẻ', 'ẽ', 'ẹ', 'ê', 'ế', 'ề', 'ể', 'ễ', 'ệ', 'f', 'g', 'h', 'i', 'í', 'ì', 'ỉ', 'ĩ', 'ị', 'j', 'k', 'l', 'm', 'n', 'o', 'ó', 'ò', 'ỏ', 'õ', 'ọ', 'ô', 'ố', 'ồ', 'ổ', 'ỗ', 'ộ', 'ơ', 'ớ', 'ờ', 'ở', 'ỡ', 'ợ', 'p', 'q', 'r', 's', 't', 'u', 'ú', 'ù', 'ủ', 'ũ', 'ụ', 'ư', 'ứ', 'ừ', 'ử', 'ữ', 'ự', 'v', 'w', 'x', 'y', 'ý', 'ỳ', 'ỷ', 'ỹ', 'ỵ', 'z');
    private static $_vi_all_upper      = array('A', 'Á', 'À', 'Ả', 'Ã', 'Ạ', 'Ă', 'Ắ', 'Ằ', 'Ẳ', 'Ẵ', 'Ặ', 'Â', 'Ấ', 'Ầ', 'Ẩ', 'Ẫ', 'Ậ', 'B', 'C', 'D', 'Đ', 'E', 'É', 'È', 'Ẻ', 'Ẽ', 'Ẹ', 'Ê', 'Ế', 'Ề', 'Ể', 'Ễ', 'Ệ', 'F', 'G', 'H', 'I', 'Í', 'Ì', 'Ỉ', 'Ĩ', 'Ị', 'J', 'K', 'L', 'M', 'N', 'O', 'Ó', 'Ò', 'Ỏ', 'Õ', 'Ọ', 'Ô', 'Ố', 'Ồ', 'Ổ', 'Ỗ', 'Ộ', 'Ơ', 'Ớ', 'Ờ', 'Ở', 'Ỡ', 'Ợ', 'P', 'Q', 'R', 'S', 'T', 'U', 'Ú', 'Ù', 'Ủ', 'Ũ', 'Ụ', 'Ư', 'Ứ', 'Ừ', 'Ử', 'Ữ', 'Ự', 'V', 'W', 'X', 'Y', 'Ý', 'Ỳ', 'Ỷ', 'Ỹ', 'Ỵ', 'Z');
    
    private static $_viet_char = array(
        'á' => 'a', 'à' => 'a', 'ả' => 'a', 'ã' => 'a', 'ạ' => 'a',
        'ă' => 'a', 'ắ' => 'a', 'ằ' => 'a', 'ẳ' => 'a', 'ẵ' => 'a', 'ặ' => 'a',
        'â' => 'a', 'ấ' => 'a', 'ầ' => 'a', 'ẩ' => 'a', 'ẫ' => 'a', 'ậ' => 'a',
        'đ'=>'d',  
        'é' => 'e', 'è' => 'e', 'ẻ' => 'e', 'ẽ' => 'e', 'ẹ' => 'e',
        'ê' => 'e', 'ế' => 'e', 'ề' => 'e', 'ể' => 'e', 'ễ' => 'e', 'ệ' => 'e',
        'í' => 'i', 'ì' => 'i', 'ỉ' => 'i', 'ĩ' => 'i', 'ị' => 'i',
        'ó' => 'o', 'ò' => 'o', 'ỏ' => 'o', 'õ' => 'o', 'ọ' => 'o',
        'ô' => 'o', 'ố' => 'o', 'ồ' => 'o', 'ổ' => 'o', 'ỗ' => 'o', 'ộ' => 'o',
        'ơ' => 'o', 'ớ' => 'o', 'ờ' => 'o', 'ở' => 'o', 'ỡ' => 'o', 'ợ' => 'o',
        'ú' => 'u', 'ù' => 'u', 'ủ' => 'u', 'ũ' => 'u', 'ụ' => 'u',
        'ư' => 'u', 'ứ' => 'u', 'ừ' => 'u', 'ử' => 'u', 'ữ' => 'u', 'ự' => 'u',
        'ý' => 'y', 'ỳ' => 'y', 'ỷ' => 'y', 'ỹ' => 'y', 'ỵ' => 'y',
        
        'Á' => 'A', 'À' => 'A', 'Ả' => 'A', 'Ã' => 'A', 'Ạ' => 'A',
        'Ă' => 'A', 'Ắ' => 'A', 'Ằ' => 'A', 'Ẳ' => 'A', 'Ẵ' => 'A', 'Ặ' => 'A',
        'Â' => 'A', 'Ấ' => 'A', 'Ầ' => 'A', 'Ẩ' => 'A', 'Ẫ' => 'A', 'Ậ' => 'A',
        'Đ'=>'D',          
        'É' => 'E', 'È' => 'E', 'Ẻ' => 'E', 'Ẽ' => 'E', 'Ẹ' => 'E',
        'Ê' => 'E', 'Ế' => 'E', 'Ề' => 'E', 'Ể' => 'E', 'Ễ' => 'E', 'Ệ' => 'E',
        'Í' => 'I', 'Ì' => 'I', 'Ỉ' => 'I', 'Ĩ' => 'I', 'Ị' => 'I',
        'Ó' => 'O', 'Ò' => 'O', 'Ỏ' => 'O', 'Õ' => 'O', 'Ọ' => 'O',
        'Ô' => 'O', 'Ố' => 'O', 'Ồ' => 'O', 'Ổ' => 'O', 'Ỗ' => 'O', 'Ộ' => 'O',
        'Ơ' => 'O', 'Ớ' => 'O', 'Ờ' => 'O', 'Ở' => 'O', 'Ỡ' => 'O', 'Ợ' => 'O',
        'Ú' => 'U', 'Ù' => 'U', 'Ủ' => 'U', 'Ũ' => 'U', 'Ụ' => 'U',
        'Ư' => 'U', 'Ứ' => 'U', 'Ừ' => 'U', 'Ử' => 'U', 'Ữ' => 'U', 'Ự' => 'U',
        'Ý' => 'Y', 'Ỳ' => 'Y', 'Ỷ' => 'Y', 'Ỹ' => 'Y', 'Ỵ' => 'Y',
    );
    
    private static $_viet_char2 = array(
        'a'=>'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',  
        'A'=>'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',  
        'd'=>'đ',  
        'D'=>'Đ',  
        'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',  
        'E'=>'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',  
        'i'=>'í|ì|ỉ|ĩ|ị',  
        'I'=>'Í|Ì|Ỉ|Ĩ|Ị',  
        'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',  
        'O'=>'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',  
        'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',  
        'U'=>'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',  
        'y'=>'ý|ỳ|ỷ|ỹ|ỵ',  
        'Y'=>'Ý|Ỳ|Ỷ|Ỹ|Ỵ',  
    );
   
    /**
     *
     * @param string $str
     * @return string 
     */
    public static function removeSigns($str) {
        return str_replace(array_merge(CVietnameseTools::$_vi_lower, CVietnameseTools::$_vi_upper), array_merge(CVietnameseTools::$_vi_lower_nosigns, CVietnameseTools::$_vi_upper_nosigns), $str);
    }
    
    /**
     *
     * @param string $str
     * @return string 
     */
    public static function removeSigns2($str) {
        $patterns = array();
        $replacements = array();
        foreach (CVietnameseTools::$_viet_char2 as $res=>$source) {
            $patterns[] = "/$source/";
            $replacements[] = $res;
        }
        
        $str = preg_replace($patterns, $replacements, $str);
        
        return $str;
    }
    
    /**
     * @param string $str
     * @return string
     */
    public static function toLower($str) {
        return str_replace(CVietnameseTools::$_vi_all_upper, CVietnameseTools::$_vi_all_lower, $str);        
    }
    
    /**
     * @param string $str
     * @return string
     */
    public static function toUpper($str) {
        return str_replace(CVietnameseTools::$_vi_all_lower, CVietnameseTools::$_vi_all_upper, $str);          
    }
    
    /**
     * xoa cac dau trang thua
     * @param string $str
     * @return string
     */
    public static function proper($str) {
        $str = preg_replace("/[ \t\n\r]+/", ' ', $str); // --> de \s thi bi mat mot so chu (à, Ạ...)
        return trim($str);
    }
    
    /**
     * tra lai xau viet hoa cac ky tu dau tien của mỗi từ (danh từ riêng), xoa cac dau trang thua
     * @param string $str
     * @return string
     */
    public static function properName($str) {
        $str = ' '.CVietnameseTools::proper(CVietnameseTools::toLower($str));
        
        return ltrim($str);
    }
    
    /**
     * kiểm tra xem có các dấu tiếng việt trong xâu ko
     * @param string $str
     * @return boolean
     */
    public static function hasSigns($str) {
        $pattern = implode(array_merge(CVietnameseTools::$_vi_lower, CVietnameseTools::$_vi_upper));
        //return $pattern;
        return preg_match("/[$pattern]/", $str);
    }
    
    /**
     * bỏ hết các ký tự đặc biệt, chỉ để lại chữ cái
     * * @param string $str 
     * @return string
     */
    public static function lettersOnly($str) {
        $pattern = implode(array_merge(CVietnameseTools::$_en_lower, CVietnameseTools::$_en_upper, CVietnameseTools::$_vi_lower, CVietnameseTools::$_vi_upper));
        $pattern = "/[^\s$pattern]/";
        //return $pattern;
        return preg_replace($pattern, '', $str);
    }
    
    /**
     * bỏ hết các ký tự đặc biệt, chỉ để lại chữ cái và số
     * * @param string $str 
     * @return string
     */
    public static function alphanumericOnly($str) {
        $pattern = implode(array_merge(CVietnameseTools::$_en_lower, CVietnameseTools::$_en_upper, CVietnameseTools::$_vi_lower, CVietnameseTools::$_vi_upper));
        $pattern = "/[^\s${pattern}1234567890]/";
        //return $pattern;
        return preg_replace($pattern, '', $str);
        return $str;
    }
    
    /**
     * bỏ hết dấu, bỏ khoảng trắng thừa, khoảng trắng ở đầu, cuối, chuyển về lower case 
     * và bỏ các dấu đặc biệt (.,!@#$)
     * @param string $str 
     * @return string
     */
    public static function makeSearchableStr($str) {
        $str = CVietnameseTools::removeSigns($str);
        $str = strtolower($str);

        $pattern = implode(array_merge(CVietnameseTools::$_en_lower));
        $pattern = "/[^\s${pattern}1234567890]/";
        //return $pattern;
        $str = preg_replace($pattern, '', $str);
        return CVietnameseTools::proper($str);

    }
   
    /**
     * bỏ dấu, bỏ các khoảng trắng, lower case, bỏ ký tự đặc biệt, nối các từ = '_'
     * @param string $str
     * @return string 
     */
    public static function makeCodeName($str, $addRandomToken = false) {
        $str = CVietnameseTools::removeSigns($str);
        $str = strtolower($str);
        $pattern = implode(array_merge(CVietnameseTools::$_en_lower));
        $pattern = "/[^\s${pattern}1234567890]/";
        //return $pattern;
        $str = preg_replace($pattern, '', $str);
        //return implode('_',explode(' ',  CVietnameseTools::proper($str)));
        $result =  str_replace(" ", "_", CVietnameseTools::proper($str));
        if($addRandomToken){
            $result .= '_'.substr(time(),strlen(time())-4,4);
        }
        return $result;
    }

    /**
     * bỏ dấu, bỏ các khoảng trắng, lower case, bỏ ký tự đặc biệt, nối các từ = '_'
     * @param string $str
     * @return string
     */
    public static function makeSlugName($str) {
        $str = CVietnameseTools::removeSigns($str);
        $str = strtolower($str);
        $pattern = implode(array_merge(CVietnameseTools::$_en_lower));
        $pattern = "/[^\s${pattern}1234567890]/";
        //return $pattern;
        $str = preg_replace($pattern, '', $str);
        //return implode('_',explode(' ',  CVietnameseTools::proper($str)));
        $result =  str_replace(" ", "-", CVietnameseTools::proper($str));
        return $result;
    }

    public static function makeValidFileName($fileName){
        $str = CVietnameseTools::removeSigns($fileName);
        $str = strtolower($str);
        $result =  str_replace(" ", "_", CVietnameseTools::proper($str));
        return $result;
    }

    public static function truncateString($value, $length)
    {
        if ($value != '') {
            if (is_array($value)) {
                list($string, $match_to) = $value;
            } else {
                $string = $value;
                $match_to = $value{0};
            }

            $match_start = stristr($string, $match_to);
            $match_compute = strlen($string) - strlen($match_start);
            if (strlen($string) > $length) {
                if ($match_compute < ($length - strlen($match_to))) {
                    $pre_string = substr($string, 0, $length);
                    $pos_end = strrpos($pre_string, " ");
                    if ($pos_end === false) {
                        $string = $pre_string . "...";
                    } else {
                        $string = substr($pre_string, 0, $pos_end) . "...";
                    }
                } else {
                    if ($match_compute > (strlen($string) - ($length - strlen($match_to)))) {
                        $pre_string = substr($string, (strlen($string) - ($length - strlen($match_to))));
                        $pos_start = strpos($pre_string, " ");
                        $string = "..." . substr($pre_string, $pos_start);
                        if ($pos_start === false) {
                            $string = "..." . $pre_string;
                        } else {
                            $string = "..." . substr($pre_string, $pos_start);
                        }
                    } else {
                        $pre_string = substr($string, ($match_compute - round(($length / 3))), $length);
                        $pos_start = strpos($pre_string, " ");
                        $pos_end = strrpos($pre_string, " ");
                        $string = "..." . substr($pre_string, $pos_start, $pos_end) . "...";
                        if ($pos_start === false && $pos_end === false) {
                            $string = "..." . $pre_string . "...";
                        } else {
                            $string = "..." . substr($pre_string, $pos_start, $pos_end) . "...";
                        }
                    }
                }

                $match_start = stristr($string, $match_to);
                $match_compute = strlen($string) - strlen($match_start);
            }
            return $string;
        } else {
            return '';
        }
    }

    public static function stripSpecialChars($str, $removeHyphens=false){
        if(!$str) return false;
        $str = strtolower($str);
        $unicode = array(
            'a'=>'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ|Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'd'=>'đ|Đ',
            'e'=>'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ|É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'i'=>'í|ì|ỉ|ĩ|ị|Í|Ì|Ỉ|Ĩ|Ị',
            'o'=>'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ|Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'u'=>'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự|Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'y'=>'ý|ỳ|ỷ|ỹ|ỵ|Ý|Ỳ|Ỷ|Ỹ|Ỵ'
        );
        foreach($unicode as $nonUnicode=>$uni) $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        $newStr = preg_replace("/[^A-Za-z0-9.]/", "-", $str);
        $newStr = str_replace('---', '-', $newStr);
        $newStr = str_replace('--', '-', $newStr);
        if($removeHyphens){
            $newStr = str_replace('-', ' ', $newStr);
        }
        return $newStr;
    }

}

?>
