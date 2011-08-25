<?php
// Default decorators and extensions, for more info check corresponding files stored in maxskitter/code folder
DataObject::add_extension("SiteTree", "MaxSkitterDecorator");
Object::add_extension("ContentController", "MaxSkitterExtension");
DataObject::add_extension("SiteTree", "MaxSkitterPageConfigDecorator");
DataObject::add_extension("SiteConfig", "MaxSkitterSiteConfigDecorator");

// Creating croppedResize slide image
Object::add_extension("Image", "MaxSkitterImageDecorator");

// Per page specific drag & drop for sorting slides
SortableDataObject::add_sortable_many_many_relation('SiteTree','MaxSkitterSlides');

/** Skitter Configuration Example (mysite/_config.php) **/
//MaxSkitterDefaults::$debugSkitter = true;
//MaxSkitterImageDecorator::$SkitterSlideWidth = 850;
//MaxSkitterImageDecorator::$SkitterSlideHeight = 250;
/*
MaxSkitterDefaults::set_staticConfig(array(
	"animation" => "'cube'",
	"easing_default" => "'easeOutBack'",
	"animateNumberActive" => "{backgroundColor:'#004581', color:'#fff'}",
	"navigation" => "false",
	"label" => "false"
));
*/

// EOF
