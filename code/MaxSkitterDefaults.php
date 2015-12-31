<?php
/**
 * Defines MaxSkitter degault configuration and common methods 	
 * @package maxskitter - silverstripe module for slides management and presentation with jQuery skitter plugin
 * @link skitter http://www.thiagosf.net/projects/jquery/skitter/
 * @link maxskitter https://github.com/Silvermax/maxskitter/
 * @author Pali Ondras
 */

class MaxSkitterDefaults
{
    
    public static $debugSkitter = false;
    
    private static $skitterConfigFields = array(
                'interval' => 'ShortTextRaw',
                'animation' => array(
                    'cube',
                    'cubeRandom',
                    'block',
                    'cubeStop',
                    'cubeHide',
                    'cubeSize',
                    'horizontal',
                    'showBars',
                    'showBarsRandom',
                    'tube',
                    'fade',
                    'fadeFour',
                    'paralell',
                    'blind',
                    'blindHeight',
                    'blindWidth',
                    'directionTop',
                    'directionBottom',
                    'directionRight',
                    'directionLeft',
                    'cubeStopRandom',
                    'cubeSpread',
                    'glassCube',
                    'glassBlock',
                    'circles',
                    'circlesInside',
                    'circlesRotate',
                    'cubeShow',
                    'upBars',
                    'downBars',
                    'hideBars',
                    'swapBars',
                    'swapBarsBack'
                ),
                'numbers' => 'Boolean',
                'numbers_align' => array('center','left','right'),
                'navigation' => 'Boolean',
                'dots' => 'Boolean',
                'thumbs' => 'Boolean',
                'hideTools' => 'Boolean',
                'label' => 'Boolean',
                /* ----*/
                'animateNumberOut' => 'LongTextRaw',
                'animateNumberOver' => 'LongTextRaw',
                'animateNumberActive' => 'LongTextRaw',
                'controls' => 'Boolean',
                'controls_position' => array('center', 'leftTop', 'rightTop', 'leftBottom', 'rightBottom'),
                'easing_default' => 'ShortText',
                'enable_navigation_keys' => 'Boolean',
                'focus' => 'Boolean',
                'focus_position' => array('center', 'leftTop', 'rightTop', 'leftBottom', 'rightBottom'),
                'fullscreen' => 'Boolean',
                'mouseOutButton' => 'LongTextRaw',
                'mouseOverButton' => 'LongTextRaw',
                'onLoad' => 'LongTextRaw',
                'preview' => 'Boolean',
                'stop_over' => 'Boolean',
                'show_randomly' => 'Boolean',
                'stop_over' => 'Boolean',
                'velocity' => 'ShortTextRaw',
                'width_label' => 'ShortText',
                'with_animations' => 'LongTextRaw'
    );
    
    public static $dbFieldsPrefix = "maxskitter_";
    
    public static function get_skitterDbFields()
    {
        foreach (self::$skitterConfigFields as $key => $value) {
            switch ($value) {
                case "Boolean":
                    $type = "Varchar(8)";
                    break;
                case "LongText":
                case "LongTextRaw":
                    $type = "Varchar(127)";
                    break;
                default:
                    $type = "Varchar(32)";
            }
            
            $dbFields[self::$dbFieldsPrefix.$key] = $type;
        }
        
        return $dbFields;
    }
    
    public static function set_skitterConfigFields($array)
    {
        self::$skitterConfigFields = $array;
    }
    
    public static function get_skitterConfigFields()
    {
        return self::$skitterConfigFields;
    }
    
    public static function set_animations($array)
    {
        self::$skitterConfigFields['animation'] = $array;
    }
    
    public static function get_animations()
    {
        return self::$skitterConfigFields['animation'];
    }

    public static function get_skitterCMSFields()
    {
        foreach (self::$skitterConfigFields as $key => $value) {
            switch ($value) {
                case "Boolean":
                    $f = self::get_boolean_dropdown($key);
                    break;
                case "ShortText":
                case "ShortTextRaw":
                case "LongText":
                case "LongTextRaw":
                    $f = new TextField(self::$dbFieldsPrefix.$key, _t("Skitter.".$key, $key));
                    break;
                default:
                    if (is_array($value)) {
                        $f = self::get_array_dropdown($key);
                    } else {
                        $f = new TextField(self::$dbFieldsPrefix.$key, _t("Skitter.".$key, $key));
                    }
            }
            
            $CMSFields[] = $f;
        }
        
        return $CMSFields;
    }
    
    public static function get_array_dropdown($field)
    {
        $options = self::$skitterConfigFields[$field];
        foreach ($options as $key => $value) {
            $new_key = $value;
            $options_for_dd[$new_key] = $value;
        }
        $dd = new DropdownField(self::$dbFieldsPrefix.$field, _t("Skitter.".$field, $field), $options_for_dd);
        $dd->setEmptyString(_t("Skitter.optionDefault", "-- Default --"));
        return $dd;
    }
    
    public static function get_boolean_dropdown($field)
    {
        $options = array('yes','no');
        foreach ($options as $key => $value) {
            $new_key = $value;
            $options_for_dd[$new_key] = $value;
        }
        $dd = new DropdownField(self::$dbFieldsPrefix.$field, _t("Skitter.".$field, $field), $options_for_dd);
        $dd->setEmptyString(_t("Skitter.optionDefault", "-- Default --"));
        return $dd;
    }
    
    private static $staticConfig = array();
    
    public static function set_staticConfig($array)
    {
        self::$staticConfig = $array;
    }
    
    public static function get_staticConfig()
    {
        return self::$staticConfig;
    }
}
