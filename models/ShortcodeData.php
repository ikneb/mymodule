<?php


class ShortcodeData extends ObjectModel
{

    public $shortcode_name;
    public $shortcode_description;
    public $shortcode_content;
    public $shortcode_status;



    public static $definition = array(
        'table' => 'shortcode_data',
        'primary' => 'id_shortcode_data',
        'multilang' => true,
        'fields' => array(

            'shortcode_name' => 		array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isGenericName', 'required' => true, 'size' => 50),
            'shortcode_description' => 		array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => false, 'size' => 50),
            'shortcode_content' => 		array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => false, 'size' => 255),
            'shortcode_status' => 		array('type' => self::TYPE_INT, 'validate' => 'isGenericName', 'required' => false, 'size' => 1),
        ),
    );

}