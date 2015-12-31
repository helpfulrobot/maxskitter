<?php
/**
 * Defines MaxSkitter image resize options, if you dont like used cropped resize, use your own copy of this file
 * (with custom name of class and methods), enable it in your mysite/_config.php and copy 
 * maxskitter/templates/Includes/SkitterImage.ss in your theme and change called image resizing method name.
 * If you are fine with cropped resizing, you can configure dimension in you mysite/_config.php. Example:
 * <code>
 * MaxSkitterImageDecorator::$SkitterSlideWidth = 850;
 * MaxSkitterImageDecorator::$SkitterSlideHeight = 250;
 * </code>
 * @package maxskitter - silverstripe module for slides management and presentation with jQuery skitter plugin
 * @link skitter http://www.thiagosf.net/projects/jquery/skitter/
 * @link maxskitter https://github.com/Silvermax/maxskitter/
 * @author Pali Ondras
 */

class MaxSkitterImageDecorator extends DataExtension
{
    
    public static $SkitterSlideWidth = 800;
    
    public static $SkitterSlideHeight = 300;
    
    public function generateSkitterSlide($gd)
    {
        return $gd->croppedResize(self::$SkitterSlideWidth, self::$SkitterSlideHeight);
    }
    
    public function SkitterSlide()
    {
        return ($this->owner->Width == self::$SkitterSlideWidth && $this->owner->Height == self::$SkitterSlideHeight) ? $this->owner : $this->owner->getFormattedImage('SkitterSlide');
    }
}
