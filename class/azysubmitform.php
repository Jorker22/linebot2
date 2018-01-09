<?php
class setfont
{
    public function imagestring($im, $size, $x, $y, $color, $font, $text){
        imagettftext($im, $size, 0, $x, $y, $color, $font, $text);
    }
}

?>