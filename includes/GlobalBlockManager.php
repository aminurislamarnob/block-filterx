<?php

namespace WeLabs\BlockController;

class GlobalBlockManager {
    /**
     * The constructor.
     */
    public function __construct() {
        add_filter( 'allowed_block_types_all', [$this, 'example_allowed_block_types'], 10, 2 );
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