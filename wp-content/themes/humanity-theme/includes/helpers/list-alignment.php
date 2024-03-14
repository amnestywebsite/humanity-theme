<?php

if (! function_exists('Add_Alignment_Controls_To_List_block') ) {
    /**
     * Add alignment support to the core/list block
     *
     * @param array $metadata - The block type metadata
     *
     * @return array $metadata - The modified block type metadata
     */
    function Add_Alignment_Controls_To_List_block( $metadata )
    {
        if ('core/list' === $metadata['name'] ) {
            $metadata['supports']['align'] = true;
        }

        return $metadata;
    }
}

add_filter('block_type_metadata', 'Add_Alignment_Controls_To_List_block');
