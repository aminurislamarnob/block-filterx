import { __ } from '@wordpress/i18n';

const CategoryHeader = ({ category }) => {
    return (
        <div className='category-block-header flex justify-between items-center'>
            <h4 className='mb-4 text-lg'><strong>{category.info.title}</strong></h4>
            <div className='block-cat-filter flex gap-3'>
                <button>{category.info.title} { __( 'Blocks', 'block-filterx' ) } ({category?.blocks?.length})</button>
                <button>{ __( 'Active', 'block-filterx' ) } ({category?.blocks?.length - category?.disabledBlocks?.length})</button>
                <button>{ __( 'Inactive', 'block-filterx' ) } ({category?.disabledBlocks?.length})</button>
            </div>
        </div>
    );
};

export default CategoryHeader;
