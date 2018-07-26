<?php
namespace Opentable_Divi_Modules\OpentableWidgetModule;

use ET_Builder_Element;
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
    public $vb_support = 'on';

    protected $module_credits = array(
        'module_uri' => 'https://www.diviframework.com',
        'author'     => 'Divi Framework',
        'author_uri' => 'https://www.diviframework.com',
    );


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

        $this->fields['align'] = array(
            'label' => 'Alignment',
            'type' => 'select',
            'options' => array(
                'left' => 'Left',
                'center' => 'Center',
                'right' => 'Right',
            ),
            'default' => 'center',
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
            'align' => 'center',
        );

        $atts = wp_parse_args($atts, $defaults);

        $atts['iframe'] = $atts['iframe'] == 'on' ? 'true' : 'false';

        $module_class = $this->shortcode_atts['module_class'];
        $module_class = ET_Builder_Element::add_module_order_class($module_class, $function_name);

        ET_Builder_Element::set_style($module_class, array(
            'selector' => 'div[id^="ot-widget-container"]',
            'declaration' => "text-align:" . $atts['align'],
        ));
        
        ET_Builder_Element::set_style($module_class, array(
            'selector' => 'div[id^="ot-reservation-widget"]',
            'declaration' => 'display:inline-block !important',
        ));

        ET_Builder_Element::set_style($module_class, array(
            'selector' => '.picker__table thead th',
            'declaration' => 'padding: 0 0 8px 0;',
        ));

        ET_Builder_Element::set_style($module_class, array(
            'selector' => '.picker__table tbody td',
            'declaration' => 'padding: 1px 0;',
        ));

        ET_Builder_Element::set_style($module_class, array(
            'selector' => '.picker__table',
            'declaration' => 'border:none !important ;',
        ));

        // ET_Builder_Element::set_style($module_class, array(
        //     'selector' => 'div[id^="ot-widget-container"] iframe',
        //     'declaration' => "height:auto!important",
        // ));

        $this->wp_add_inline_style();

        $script = sprintf(
            "<script type='text/javascript' src='//www.opentable.com/widget/reservation/loader?rid=%s&domain=com&type=%s&theme=%s&lang=%s&overlay=false&iframe=%s'></script>",
            $atts['rid'],
            $atts['type'],
            $atts['type'],
            $atts['lang'],
            $atts['iframe']
        );

        $iframe_on_off = $atts['iframe'] == 'true' ? 'on' : 'off';
        return sprintf('<div class="df-opentable type-%s iframe-%s">', $atts['type'], $iframe_on_off).  $script . '</div>';
    }

    protected function wp_add_inline_style()
    {

        // iframe-off + wide
        $style = '.df-opentable.type-wide.iframe-off input[type="submit"] {
            width:25% !important ;
        }';

        $style .= '.df-opentable.type-wide.iframe-off .ot-time-picker.ot-dtp-picker-selector {
            width:25% !important ;
        }';

        $style .= '.df-opentable.type-wide.iframe-off .ot-date-picker.ot-dtp-picker-selector.wide {
            width:25% !important ;
        }';

        $style .= '.df-opentable.type-wide.iframe-off .ot-party-size-picker {
            width:25% !important ;
        }';

        $style .= '.df-opentable.type-wide.iframe-off table.picker__table {
            text-align:center;
        }';

        $style .= '.df-opentable.type-wide.iframe-off table.picker__table td[role="presentation"] {
            border-top:none !important;        
        }';


        // iframe-off tally
        $style .= '.df-opentable.type-tall.iframe-off .ot-dtp-picker.tall .picker__holder {
            width:100% !important;
        }';

        $style .= '.df-opentable.type-tall.iframe-off table.picker__table td[role="presentation"] {
            border-top:none !important;        
        }';

        $style .= '.df-opentable.type-tall.iframe-off table.picker__table {
            text-align:center;
        }';

        $style .= '.df-opentable.type-tall.iframe-off  .picker__nav--next{
            right:20px;
        }';


        //iframe off , standard

        $style .= '.df-opentable.type-standard.iframe-off table.picker__table td[role="presentation"] {
            border-top:none !important;        
        }';

        $style .= '.df-opentable.type-standard.iframe-off table.picker__table {
            text-align:center;
        }';

        $style .= '.df-opentable.type-standard.iframe-off  .picker__nav--next{
            right:20px;
        }';


        wp_register_style('df-opentable-dummy-handle', false);
        wp_enqueue_style('df-opentable-dummy-handle');

        wp_add_inline_style('df-opentable-dummy-handle', $style);
    }
}
