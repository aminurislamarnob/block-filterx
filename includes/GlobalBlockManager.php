<?php

namespace WeLabs\BlockFilterx;

class GlobalBlockManager {
    /**
     * The constructor.
     */
    public function __construct() {
        add_action('admin_init', [$this, 'get_registered_blocks']);
        // add_filter( 'allowed_block_types_all', [$this, 'example_allowed_block_types'], 10, 2 );
    }

    public function get_registered_blocks(){
        // Retrieve all registered block types
        $registered_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();

        // Prepare an array to store block names and icons
        $blocks_info = [];
    
        foreach ($registered_blocks as $block_type) {
            $blocks_info[] = [
                'name' => $block_type->name,
                'icon' => $this->get_block_icon($block_type)
            ];
        }
    
        // Log the block names and icons
        
        error_log(wp_json_encode($registered_blocks));
    }

    private function get_block_icon($block_type) {
        // Check if the block has an icon property and return it
        if (isset($block_type->icon)) {
            // If the icon is a function, call it
            if (is_callable($block_type->icon)) {
                $icon = call_user_func($block_type->icon);
                return is_array($icon) ? wp_json_encode($icon) : $icon;
            }
            // If the icon is an array, convert it to a JSON string
            if (is_array($block_type->icon)) {
                return wp_json_encode($block_type->icon);
            }
            return $block_type->icon;
        }
        // Return a default value if no icon is set
        return 'default-icon';
    }

    /**
     * Filters the list of allowed block types in the block editor.
     *
     * This function restricts the available block types to Heading, List, Image, and Paragraph only.
     *
     * @param array|bool $allowed_block_types Array of block type slugs, or boolean to enable/disable all.
     * @param object     $block_editor_context The current block editor context.
     *
     * @return array The array of allowed block types.
     */
    public function example_allowed_block_types( $allowed_block_types, $block_editor_context ){
        $allowed_block_types = array(
            'core/heading',
            'core/image',
            'core/list',
            'core/list-item',
            'core/paragraph',
        );
    
        return $allowed_block_types;
    }
}