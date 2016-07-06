<?php

namespace HbgEventImporter\Api;

/**
 * Adding meta fields to location post type
 */

class ContactFields extends Fields
{

    private $postType = 'contact';

    public function __construct()
    {
        add_action('rest_api_init', array($this, 'registerRestFields'));
    }

    public static function registerRestFields()
    {

        //Name
        register_rest_field($this->postType,
            'name',
            array(
                'get_callback' => array($this, 'stringGetCallBack'),
                'update_callback' => array($this, 'stringUpdateCallBack'),
                'schema' => array(
                    'description' => 'Field contianing string value with contact first last name.',
                    'type' => 'string',
                    'context' => array('view', 'edit')
                )
            )
        );

        //Name
        register_rest_field($this->postType,
            'phone_number',
            array(
                'get_callback' => array($this, 'stringGetCallBack'),
                'update_callback' => array($this, 'stringUpdateCallBack'),
                'schema' => array(
                    'description' => 'Field contianing string value with contact phone number.',
                    'type' => 'string',
                    'context' => array('view', 'edit')
                )
            )
        );

        //Name
        register_rest_field($this->postType,
            'email',
            array(
                'get_callback' => array($this, 'stringGetCallBack'),
                'update_callback' => array($this, 'stringUpdateCallBack'),
                'schema' => array(
                    'description' => 'Field contianing string value with contact email.',
                    'type' => 'string',
                    'context' => array('view', 'edit')
                )
            )
        );
    }
}