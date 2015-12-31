<?php
/**
 * Defines an SiteTree decorator which enables slides management 	
 * @package maxskitter - silverstripe module for slides management and presentation with jQuery skitter plugin
 * @link skitter http://www.thiagosf.net/projects/jquery/skitter/
 * @link maxskitter https://github.com/Silvermax/maxskitter/
 * @author Pali Ondras
 */

class MaxSkitterDecorator extends DataExtension
{
    
    public static $db = array('notRecursive' => 'Boolean');
    
    public static $many_many = array('MaxSkitterSlides'=>'MaxSkitterSlide');
    
    public static $many_many_extraFields=array(
        'MaxSkitterSlides'=>array(
            'SortOrder'=>'Int'
        )
    );
    
    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab('Root.SkitterSlides', $grid=new GridField('MaxSkitterSlides', 'Skitter slides', $this->owner->MaxSkitterSlides(), GridFieldConfig_RelationEditor::create(10)));

        if (class_exists("GridFieldSortableRows")) {
            $grid->getConfig()->addComponent(new GridFieldSortableRows('SortOrder'));
        }
    }
    
    public function updateSettingsFields(FieldList $fields)
    {
        $fields->addFieldToTab("Root.SkitterConfig", new CheckboxField("notRecursive", _t("Skitter.notRecursive", "Do not grab slides from parent page!")));
    }
    
    public function OrderedSkitterSlides()
    {
        return $this->owner->getManyManyComponents('MaxSkitterSlides')->sort('SortOrder');
    }
}
