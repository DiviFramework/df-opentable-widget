<?php
namespace DF\Opentable;

use ET_Builder_Module;

/**
 * Open table widget module.
 */
class OpentableWidgetModule extends ET_Builder_Module
{
    public $name = 'DF - Opentable Widget';
    public $slug = 'df_opentable_widget';
    public $fields;
    protected $container;

    public function __construct($container)
    {
        $this->container = $container;
        $this->initFields();
        parent::__construct();
    }

    /**
     * Initialise the fields.
     */
    private function initFields()
    {
        $this->fields = array();

        $this->fields['rid'] = array(
            'label' => __('Restaurant ID', 'et_builder'),
            'type' => 'text',
            'description' => __('Opentable Restaurant ID. Search for your restaurant here - <a href="https://www.otrestaurant.com/marketing/reservationwidget" target="_blank">https://www.otrestaurant.com/marketing/reservationwidget</a> and get the ID', 'et_builder'),
        );

        $this->fields['lang'] = array(
            'label' => 'Language',
            'type' => 'select',
            'options' => array(
                "en" => 'English',
                "fr" => 'Français',
                "es" => 'Español',
                "de" => 'Deutsch',
                "nl" => 'Nederlands',
                "ja" => '日本語',
            ),
            'default' => 'en',
        );

        $this->fields['type'] = array(
            'label' => 'Widget Type',
            'type' => 'select',
            'options' => array(
                'standard' => 'Standard (224 x 289 pixels)',
                'tall' => 'Tall (280 x 477 pixels)',
                'wide' => 'Wide (832 x 154 pixels)',
                'button' => 'Button (210 x 106 pixels)',
            ),
            'default' => 'standard',
        );

        $this->fields['iframe'] = array(
            'label' => 'Load widget in an iFrame',
            'type' => 'yes_no_button',
            'options' => array(
                'off' => __("No", 'et_builder'),
                'on' => __('Yes', 'et_builder'),
            ),
            'description' => 'Widget will appear in an iframe and prevent the restaurant website code from changing its styling.',
            'default' => 'on',
        );

        $this->fields['admin_label'] = array(
            'label' => __('Admin Label', 'et_builder'),
            'type' => 'text',
            'description' => __('This will change the label of the module in the builder for easy identification.', 'et_builder'),
        );
    }

    /**
     * Init module.
     */
    public function init()
    {
        $this->whitelisted_fields = array_keys($this->fields);

        if (strpos($this->slug, 'et_pb_') !== 0) {
            $this->slug = 'et_pb_' . $this->slug;
        }

        $defaults = array();

        foreach ($this->fields as $field => $options) {
            if (isset($options['default'])) {
                $defaults[$field] = $options['default'];
            }
        }

        $this->field_defaults = $defaults;
    }

    /**
     * Get Fields
     *
     */
    public function get_fields()
    {
        return $this->fields;
    }

    /**
     * Shortcode render.
     */
    public function shortcode_callback($atts, $content = null, $function_name)
    {
        $defaults = array(
            'lang' => 'en',
            'type' => 'standard',
            'rid' => '',
            'iframe' => 'on',
        );

        $atts = wp_parse_args($atts, $defaults);

        $atts['iframe'] = $atts['iframe'] == 'on' ? 'true' : 'false';

        $script = sprintf(
            "<script type='text/javascript' src='//www.opentable.com/widget/reservation/loader?rid=%s&domain=com&type=%s&theme=%s&lang=%s&overlay=false&iframe=%s'></script>",
            $atts['rid'],
            $atts['type'],
            $atts['type'],
            $atts['lang'],
            $atts['iframe']
        );

        return $script;
    }
}
