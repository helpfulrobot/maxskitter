<?php 
/**
 * Defines ContentController extension, generating skitter JS config and including needed JS and CSS files. 
 * By default, module is checking parent pages if no Slides defined on current page. You can disable this per page via CMS. 
 * Calling $SkitterSlidesRecursive in your theme file will show up your defined Slides.
 * Css: skitter.styles.css is called automaticly and it is almost exact copy from original skitter sources
 * Css: skitter.custom.css is called automaticly, but doesnt exists. Create it in your theme if you need extend skitter.styles.css 
 * Js: all needed JS is called, if you have custom jQuery files, block module version in your mysite/_config.php file by
 * <code>Requirements::block("maxskitter/javascript/xyz.js");</code>
 * @package maxskitter - silverstripe module for slides management and presentation with jQuery skitter plugin
 * @link skitter http://www.thiagosf.net/projects/jquery/skitter/
 * @link maxskitter https://github.com/Silvermax/maxskitter/
 * @author Pali Ondras
 */

class MaxSkitterExtension extends Extension
{
    
    private static $cachedSlides = false;
    private static $hasCachedSlides = false;
    
    /*
     *  is slides available, init all JS/css dependencies
     */
    public function onAfterInit()
    {
        if ($this->owner->SkitterSlidesRecursive()) {
            Requirements::themedCSS("skitter.styles", "maxskitter");
            Requirements::themedCSS("skitter.custom", "maxskitter");
           
            $JS = array(
                "maxskitter/javascript/jquery-1.6.3.min.js",
                "maxskitter/javascript/jquery.skitter.min.js",
                "maxskitter/javascript/jquery.easing.1.3.js",
                "maxskitter/javascript/jquery.animate-colors-min.js"
            );
            
            foreach ($JS as $js) {
                Requirements::javascript($js);
            }
            Requirements::combine_files("combined.skitter.js", $JS);
          
            Requirements::customScript("
				jQuery(document).ready(function() {
				jQuery('#skitter').skitter({
					".$this->owner->get_skitter_config_for_js()."
				});
			});
		");
        }
    }
    
    /*
     * return Slides, if recursive enabled, try to get parent's slides if not available on current page
     * */
     public function SkitterSlidesRecursive()
     {
         if (self::$hasCachedSlides) {
             return self::$cachedSlides;
         }
        
         $page = $this->owner;
         $slides = $this->owner->MaxSkitterSlides();
         while (!$slides->exists() && $page->ParentID != 0 && !$page->notRecursiveTeaser) {
             $page = $page->Parent();
             $slides = $page->MaxSkitterSlides();
         }
        
         if ($slides->exists()) {
             $data = new ArrayData(
                array(
                    "SkitterSlides" => $slides
                )
            );
             self::$cachedSlides = $data->renderWith('Skitter');
         } else {
             self::$cachedSlides = false;
         }
         self::$hasCachedSlides = true;
         return self::$cachedSlides;
     }
   
    public function get_skitter_config()
    {
        // Load static config
        $staticConfig = MaxSkitterDefaults::get_staticConfig();
        // Load SiteConfig
        $siteConfig = SiteConfig::current_site_config();
        
        // init final config
        $skitterConfig = array();
        
        // loop for all config fields
        foreach (MaxSkitterDefaults::get_skitterConfigFields()  as $key => $value) {
            
            // Db fields are prefixed
            $prefixed_key = MaxSkitterDefaults::$dbFieldsPrefix.$key;
            
            // isset any config for each field
            if (!empty($this->owner->$prefixed_key) || !empty($siteConfig->$prefixed_key) || isset($staticConfig[$key])) {
                if (!empty($this->owner->$prefixed_key)) {
                    $config = $this->owner->$prefixed_key;
                    $place = "Page";
                } else {
                    if (!empty($siteConfig->$prefixed_key)) {
                        $config = $siteConfig->$prefixed_key;
                        $place = "SiteConfig";
                    } else {
                        $config = $staticConfig[$key];
                        $place = "_config";
                    }
                }
                if (is_array($value)) {
                    $value = "Array";
                }
                $skitterConfig[$key] = array(
                    'config' => $config,
                    'type' => $value,
                    'place' => $place
                );
            }
        }
        //debug
        if (MaxSkitterDefaults::$debugSkitter) {
            print_r($skitterConfig);
        }
        return $skitterConfig;
    }
    
    /*
     * return skitter config - JS
     */
    public function get_skitter_config_for_js()
    {
        $array = array();
        foreach ($this->owner->get_skitter_config() as $key => $value) {
            switch ($value['type']) {
                case "Boolean":
                        if ($value['config'] == "yes") {
                            $c = "true";
                        } elseif ($value['config'] == "no") {
                            $c = "false";
                        } else {
                            $c = $value['config'];
                        }
                    break;
                case "ShortTextRaw":
                case "LongTextRaw":
                    $c = $value['config'];
                    break;
                default:
                    $c = "'".$value['config']."'";
            }
            $array[] = $key.":".$c;
        }
        return implode(",\n", $array);
    }
}
