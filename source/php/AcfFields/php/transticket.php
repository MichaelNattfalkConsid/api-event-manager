<?php 


if (function_exists('acf_add_local_field_group')) {

    acf_add_local_field_group(array(
    'key' => 'group_5ae188afeb965',
    'title' => __('Transticket', 'event-manager'),
    'fields' => array(
        0 => array(
            'key' => 'field_5ae188b002bf0',
            'label' => __('Daily import', 'event-manager'),
            'name' => 'transticket_daily_cron',
            'type' => 'true_false',
            'instructions' => '',
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'message' => __('Enable daily automatic import from Transticket', 'event-manager'),
            'default_value' => 1,
            'ui' => 0,
            'ui_on_text' => '',
            'ui_off_text' => '',
        ),
        1 => array(
            'key' => 'field_5ae188b002edd',
            'label' => __('Post status', 'event-manager'),
            'name' => 'transticket_post_status',
            'type' => 'radio',
            'instructions' => __('Select status of imported events.', 'event-manager'),
            'required' => 1,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'choices' => array(
                'draft' => __('Draft', 'event-manager'),
                'publish' => __('Published', 'event-manager'),
            ),
            'allow_null' => 0,
            'other_choice' => 0,
            'save_other_choice' => 0,
            'default_value' => 'publish',
            'layout' => 'vertical',
            'return_format' => 'value',
        ),
        2 => array(
            'key' => 'field_5ae188b003b06',
            'label' => __('API links', 'event-manager'),
            'name' => 'transticket_api_urls',
            'type' => 'repeater',
            'instructions' => __('Add one or many API links to Transticket', 'event-manager'),
            'required' => 0,
            'conditional_logic' => 0,
            'wrapper' => array(
                'width' => '',
                'class' => '',
                'id' => '',
            ),
            'collapsed' => '',
            'min' => 0,
            'max' => 0,
            'layout' => 'block',
            'button_label' => __('Add', 'event-manager'),
            'sub_fields' => array(
                0 => array(
                    'key' => 'field_5ae188b03e49d',
                    'label' => __('API link', 'event-manager'),
                    'name' => 'transticket_api_url',
                    'type' => 'url',
                    'instructions' => __('Add the Transticket API-URL without \'FromDate\' and \'ToDate\' parameters, they will be added automatically.', 'event-manager'),
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                ),
                1 => array(
                    'key' => 'field_5ae18bcb22416',
                    'label' => __('Username', 'event-manager'),
                    'name' => 'transticket_username',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                2 => array(
                    'key' => 'field_5ae18bd922417',
                    'label' => __('Password', 'event-manager'),
                    'name' => 'transticket_password',
                    'type' => 'text',
                    'instructions' => '',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '50',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                3 => array(
                    'key' => 'field_5ae1e653aa68d',
                    'label' => __('Ticket URL', 'event-manager'),
                    'name' => 'transticket_ticket_url',
                    'type' => 'text',
                    'instructions' => __('Base URL to ticket purchase website.', 'event-manager'),
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                4 => array(
                    'key' => 'field_5ae188b03e4f7',
                    'label' => __('Exclude tags', 'event-manager'),
                    'name' => 'transticket_filter_tags',
                    'type' => 'text',
                    'instructions' => __('Enter the name of the tags that you want to exclude from the import. Separate with commas.', 'event-manager'),
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                5 => array(
                    'key' => 'field_5af2f83a8ff16',
                    'label' => __('Default city', 'event-manager'),
                    'name' => 'transticket_default_city',
                    'type' => 'text',
                    'instructions' => __('If essential address components are missing during import, this city will be used as default.', 'event-manager'),
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                6 => array(
                    'key' => 'field_5ae188b03e51a',
                    'label' => __('Default user groups', 'event-manager'),
                    'name' => 'transticket_publishing_groups',
                    'type' => 'taxonomy',
                    'instructions' => __('Select the user groups that you want to set as default to imported posts.', 'event-manager'),
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'taxonomy' => 'user_groups',
                    'field_type' => 'checkbox',
                    'allow_null' => 0,
                    'add_term' => 0,
                    'save_terms' => 1,
                    'load_terms' => 0,
                    'return_format' => 'id',
                    'multiple' => 0,
                ),
                7 => array(
                    'key' => 'field_5ae9d19c0881e',
                    'label' => __('Weeks to import', 'event-manager'),
                    'name' => 'transticket_weeks',
                    'type' => 'range',
                    'instructions' => __('Select how many weeks ahead you want to import. Try keeping the period as short as possible to avoid unnecessary data transfer.', 'event-manager'),
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => 2,
                    'min' => '',
                    'max' => 104,
                    'step' => '',
                    'prepend' => '',
                    'append' => '',
                ),
                8 => array(
                    'key' => 'field_61977682f2553',
                    'label' => __('Group event occasions', 'event-manager'),
                    'name' => 'transticket_group_occasions',
                    'type' => 'true_false',
                    'instructions' => __('Group event occasions under one event', 'event-manager'),
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'message' => '',
                    'default_value' => 1,
                    'ui' => 1,
                    'ui_on_text' => '',
                    'ui_off_text' => '',
                ),
            ),
        ),
    ),
    'location' => array(
        0 => array(
            0 => array(
                'param' => 'options_page',
                'operator' => '==',
                'value' => 'acf-options-options',
            ),
        ),
    ),
    'menu_order' => 2,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => '',
    'active' => 1,
    'description' => '',
));

}