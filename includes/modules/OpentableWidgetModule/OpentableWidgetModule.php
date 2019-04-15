<?php
namespace Opentable_Divi_Modules\OpentableWidgetModule;

use ET_Builder_Module;
use ET_Builder_Element;

/**
 * Open table widget module.
 */
class OpentableWidgetModule extends ET_Builder_Module
{
    public $fields;

    public $name = 'DF - Opentable Widget';

    public $slug = 'df_opentable_widget';

    public $vb_support = 'on';

    protected $container;

    protected $module_credits = [
        'module_uri' => 'https://www.diviframework.com',
        'author'     => 'Divi Framework',
        'author_uri' => 'https://www.diviframework.com',
    ];

    public function __construct($container)
    {
        $this->container = $container;
        $this->initFields();
        parent::__construct();
    }

    /**
     * Get Fields
     */
    public function get_fields()
    {
        return $this->fields;
    }

    /**
     * Init module.
     */
    public function init()
    {

        if (strpos($this->slug, 'et_pb_') !== 0) {
            $this->slug = 'et_pb_' . $this->slug;
        }

        $defaults = [];

        foreach ($this->fields as $field => $options) {
            if (isset($options['default'])) {
                $defaults[$field] = $options['default'];
            }
        }

        $this->field_defaults = $defaults;
    }

    /**
     * Shortcode render.
     */
    public function render($atts, $content = null, $function_name)
    {
        $defaults = [
            'lang'   => 'en-US',
            'type'   => 'standard',
            'rid'    => '',
            'iframe' => 'on',
            'align'  => 'center',
        ];

        $atts = wp_parse_args($atts, $defaults);

        switch ($atts['lang']) {
        case "en":
            $atts['lang'] = 'en-US';
            break;

        case "fr":
            $atts['lang'] = 'fr-CA';
            break;

        case "es":
            $atts['lang'] = 'Español-MX';
            break;

        case "de":
            $atts['lang'] = 'de-DE';
            break;

        case "nl":
            $atts['lang'] = 'Nederlands-NL';
            break;

        case "ja":
            $atts['lang'] = '日本語-JP';
            break;

        default:
            // code...
            break;
        }

        $atts['iframe'] = $atts['iframe'] == 'on' ? 'true' : 'false';

        $module_class = $this->props['module_class'];
        $module_class = trim(ET_Builder_Element::add_module_order_class($module_class, $function_name));

        ET_Builder_Element::set_style(
            $module_class,
            [
                'selector'    => 'div[id^="ot-widget-container"]',
                'declaration' => "text-align:" . $atts['align'],
            ]
        );

        ET_Builder_Element::set_style(
            $module_class,
            [
                'selector'    => 'div[id^="ot-reservation-widget"]',
                'declaration' => 'display:inline-block !important',
            ]
        );

        ET_Builder_Element::set_style(
            $module_class,
            [
                'selector'    => '.picker__table thead th',
                'declaration' => 'padding: 0 0 8px 0;',
            ]
        );

        ET_Builder_Element::set_style(
            $module_class,
            [
                'selector'    => '.picker__table tbody td',
                'declaration' => 'padding: 1px 0;',
            ]
        );

        ET_Builder_Element::set_style(
            $module_class,
            [
                'selector'    => '.picker__table',
                'declaration' => 'border:none !important ;',
            ]
        );

        // ET_Builder_Element::set_style($module_class, array(
        //     'selector' => 'div[id^="ot-widget-container"] iframe',
        //     'declaration' => "height:auto!important",
        // ));

        $this->wp_add_inline_style($module_class);

        $opentableScriptUrl = sprintf(
            '//www.opentable.com/widget/reservation/loader?rid=%s&domain=com&type=%s&theme=%s&lang=%s&overlay=false&iframe=%s',
            $atts['rid'],
            $atts['type'],
            $atts['type'],
            $atts['lang'],
            $atts['iframe']
        );

        // $script = sprintf(
        //     "<script type='text/javascript' src='//www.opentable.com/widget/reservation/loader?rid=%s&domain=com&type=%s&theme=%s&lang=%s&overlay=false&iframe=%s'></script>",
        //     $atts['rid'],
        //     $atts['type'],
        //     $atts['type'],
        //     $atts['lang'],
        //     $atts['iframe']
        // );

        $this->container['divi_modules']->enqueue_divi_module_scripts();

        $iframe_on_off = $atts['iframe'] == 'true' ? 'on' : 'off';
        return sprintf(
            '<div id="%s" class="df-opentable type-%s iframe-%s" data-src="%s">',
            $module_class,
            $atts['type'],
            $iframe_on_off,
            $opentableScriptUrl
        ) . '</div>';
    }

    /**
     * Add inline styles
     *
     * @param  [type] $module_class [description]
     * @return [type] [description]
     */
    protected function wp_add_inline_style($render_slug)
    {

        \ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '.df-opentable.type-wide.iframe-off input[type="submit"]',
                'declaration' => 'width:25% !important',
            ]
        );

        \ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '.df-opentable.type-wide.iframe-off .ot-time-picker.ot-dtp-picker-selector',
                'declaration' => 'width:25% !important',
            ]
        );

        \ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '.df-opentable.type-wide.iframe-off .ot-date-picker.ot-dtp-picker-selector.wide',
                'declaration' => 'width:25% !important',
            ]
        );

        \ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '.df-opentable.type-wide.iframe-off .ot-party-size-picker',
                'declaration' => 'width:25% !important',
            ]
        );

        \ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '.df-opentable.type-wide.iframe-off table.picker__table',
                'declaration' => 'text-align:center',
            ]
        );

        \ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '.df-opentable.type-wide.iframe-off table.picker__table td[role="presentation"]',
                'declaration' => 'border-top:none !important;',
            ]
        );

        \ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '.df-opentable.type-tall.iframe-off .ot-dtp-picker.tall .picker__holder',
                'declaration' => 'width:100% !important;',
            ]
        );

        \ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '.df-opentable.type-tall.iframe-off table.picker__table td[role="presentation"]',
                'declaration' => 'border-top:none !important;',
            ]
        );

        \ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '.df-opentable.type-tall.iframe-off table.picker__table',
                'declaration' => 'text-align:center;',
            ]
        );

        \ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '.df-opentable.type-tall.iframe-off  .picker__nav--next',
                'declaration' => 'right:20px;',
            ]
        );
        //iframe off , standard
        \ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '.df-opentable.type-standard.iframe-off table.picker__table td[role="presentation"]',
                'declaration' => 'border-top:none !important;',
            ]
        );

        \ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '.df-opentable.type-standard.iframe-off table.picker__table',
                'declaration' => 'text-align:center;',
            ]
        );

        \ET_Builder_Element::set_style(
            $render_slug,
            [
                'selector'    => '.df-opentable.type-standard.iframe-off  .picker__nav--next',
                'declaration' => 'right:20px;',
            ]
        );
    }

    /**
     * Initialise the fields.
     */
    private function initFields()
    {
        $this->fields = [];

        $this->fields['rid'] = [
            'label'       => __('Restaurant ID', 'et_builder'),
            'type'        => 'text',
            'description' => __('Opentable Restaurant ID. Search for your restaurant here - <a href="https://www.otrestaurant.com/marketing/reservationwidget" target="_blank">https://www.otrestaurant.com/marketing/reservationwidget</a> and get the ID', 'et_builder'),
        ];

        $this->fields['lang'] = [
            'label'   => 'Language',
            'type'    => 'select',
            'options' => [
                'en-US' => 'English-US',
                'fr-CA' => 'Français-CA',
                'de-DE' => 'Deutsch-DE',
                'es-MX' => 'Español-MX',
                'ja-JP' => '日本語-JP',
                'nl-NL' => 'Nederlands-NL',
                'it-IT' => 'Italiano-IT',

            ],
            'default' => 'en',
        ];

        $this->fields['type'] = [
            'label'   => 'Widget Type',
            'type'    => 'select',
            'options' => [
                'standard' => 'Standard (224 x 289 pixels)',
                'tall'     => 'Tall (280 x 477 pixels)',
                'wide'     => 'Wide (832 x 154 pixels)',
                'button'   => 'Button (210 x 106 pixels)',
            ],
            'default' => 'standard',
        ];

        $this->fields['align'] = [
            'label'   => 'Alignment',
            'type'    => 'select',
            'options' => [
                'left'   => 'Left',
                'center' => 'Center',
                'right'  => 'Right',
            ],
            'default' => 'center',
        ];

        $this->fields['iframe'] = [
            'label'       => 'Load widget in an iFrame',
            'type'        => 'yes_no_button',
            'options'     => [
                'off' => __("No", 'et_builder'),
                'on'  => __('Yes', 'et_builder'),
            ],
            'description' => 'Widget will appear in an iframe and prevent the restaurant website code from changing its styling.<br/><strong>Note: The `No` option doesn`t work in the frontend builder due to an CORs issue with opentable server.</strong>',
            'default'     => 'on',
        ];

        $this->fields['admin_label'] = [
            'label'       => __('Admin Label', 'et_builder'),
            'type'        => 'text',
            'description' => __('This will change the label of the module in the builder for easy identification.', 'et_builder'),
        ];
    }
}
