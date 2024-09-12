import { __ } from '@wordpress/i18n';
import { useState, useEffect, Fragment } from 'react';
import apiFetch from '@wordpress/api-fetch';
import { ToggleControl } from '@wordpress/components';
import BlockIcon from './BlockIcon'

const GutenBlock = ({ blockData }) => {
    const [ value, setValue ] = useState( true );
    
	return (
		<div className='border border-[#e0e4e9] rounded-md p-2.5 flex flex-col items-center bg-[#f0f0f0]'>
            <BlockIcon icon={blockData.icon} />
            <h5 className='mb-2'>{blockData.title}</h5>
            <ToggleControl
                __nextHasNoMarginBottom
                checked={ value }
                label=""
                className="toggle-gap-0"
                onChange={ () => setValue( ( state ) => ! state ) }
            />
		</div>
	);
};

export default GutenBlock;