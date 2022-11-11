<?php

namespace GTP\GutenbergPro\Main;

class gtp__GutenbergPro
{

    const meta_fields = [
        "gtp_columnspro_styling",
        "gtp_paragraph_styling",
        "gtp_heading_styling",
        "gtp_spacer_styling",
        "gtp_video_styling",
        "gtp_group_styling",
        'gtp_cover_styling'
    ];

    const extensions = [
        'gtp_columnspro_styling' => [
            'label'     => 'Columns Pro',
            'block'     => 'core/columns'
        ],
        'gtp_paragraph_styling'  => [
            'label'     => 'Paragraph Pro',
            'block'     => 'core/paragraph'
        ],
        'gtp_heading_styling'  => [
            'label'     => 'Heading Pro',
            'block'     => 'core/heading'
        ],
        'gtp_video_styling'  => [
            'label'     => 'Video Pro',
            'block'     => 'core/video'
        ],
        'gtp_spacer_styling'  => [
            'label'     => 'Spacer Pro',
            'block'     => 'core/spacer'
        ],
        'gtp_cover_styling' => [
            'label'     => 'Cover Pro',
            'block'     => 'core/cover',
        ],
        'gtp_group_styling' => [
            'label'     => 'Group Pro',
            'block'     => 'core/group',
        ]
    ];

    const option_group_slug = "gtp_options";

    /**
     * Will return the localize data that is needed for the 
     * plugin to function properly in the gutenberg editor
     * @return array data
     */

    public static function get_localize_data(): array
    {
        $data = [
            'extensions' => []
        ];

        foreach (self::extensions as $slug => $extension) :

            $data['extensions'][$slug] = [
                'status'    => get_option($slug, 'true'),
                'label'     => $extension['label'],
                'block'     => $extension['block']
            ];

        endforeach;

        return $data;
    }

    /***
     * Registering all plugin meta fields and other things
     */

    public static function register()
    {
        foreach (self::meta_fields as $slug) :


            $current_extension = self::extensions[$slug];

            # weather the extension requires meta field
            $meta_extension = !array_key_exists('has_meta', $current_extension);

            if ($meta_extension) :

                # Registering all meta fields for saving extended styles
                register_meta(
                    'post',
                    $slug,
                    array(
                        'show_in_rest' => true,
                        'single'       => true,
                        'type'         => 'string',
                        'default'       => '{}'
                    )
                );

            endif;

        endforeach;

        foreach (self::extensions as $slug => $extension) :

            # Registering all settings to toggle all extensions
            register_setting(self::option_group_slug, $slug, array(
                'type'          => 'string',
                'show_in_rest'  => true,
                'default'       => 'true'
            ));

        endforeach;
    }

    public static function render()
    {
        global $post;
        $styles = [];

        foreach (self::meta_fields as $slug) :

            $current_post_styles = get_post_meta($post->ID, $slug, true);

            foreach (json_decode($current_post_styles, TRUE) as $block_style) :

                $styles[] = $block_style;

            endforeach;

        endforeach;

        $block_styles = join(" ", $styles);
        $minified_css = \GTP\GutenbergPro\Utils\gtp_minify_css($block_styles);

        echo "<style id='gutenbergpro-styling'>" . $minified_css . "</style>";
    }
}
