import { __ } from '@wordpress/i18n';
import { useState, Fragment, useEffect } from 'react';
import apiFetch from '@wordpress/api-fetch';
import { ToggleControl, Spinner } from '@wordpress/components';
import BlockIcon from './BlockIcon';
import { useDispatch } from '@wordpress/data';
import store from '../store';
import { triggerNotification } from '../store';

const GutenBlock = ({ blockData, disabledBlocks }) => {
    const [ isEnabled, setIsEnabled ] = useState( true );
    const [ isLoading, setIsLoading ] = useState( false );
	const [ message, setMessage ] = useState( '' );
    const [ error, setError ] = useState( '' );

    // Dispatch actions to the store
	const { updateDisabledBlocks, setNotification } = useDispatch('block-filterx/store');

    // Check if block is disabled initially
    useEffect(() => {
        if (disabledBlocks.some((block) => block.name === blockData.name)) {
            setIsEnabled(false);
        }
    }, [disabledBlocks, blockData.name]);
    
    // Handle toggle change and submit
    const handleToggleChange = async () => {
        setIsEnabled((state) => !state);
        setIsLoading( true );

		try {
			const response = await apiFetch( {
				path: '/block-filterx/v1/global-disabled-block',
				method: 'POST',
				data: {
					block_name: blockData.name,
				},
			} );

            // Update store disabled block
            updateDisabledBlocks(blockData.category, response);

            if(response.includes(blockData.name)){
                triggerNotification(setNotification, 'success', __('Block successfully disabled!', 'shop-front'));
            }else{
                triggerNotification(setNotification, 'success', __('Block successfully enabled!', 'shop-front'));
            }

			setError( '' );
			setIsLoading( false );
		} catch (error) {
			setError( error.message );
			setIsLoading( false );
            triggerNotification(setNotification, 'error', __('Error disabling block!', 'shop-front'));
		}
        // console.log(isEnabled);
    };
    
	return (
        <Fragment>
            <div className={`border border-[#e0e4e9] rounded-md p-2.5 flex flex-col items-center bg-[#f0f0f0] relative ${isLoading ? 'block-loading' : ''}`}>
                <BlockIcon icon={blockData.icon} />
                <h5 className='mb-2'>{blockData.title}</h5>
                <ToggleControl
                    __nextHasNoMarginBottom
                    checked={ isEnabled }
                    label=""
                    className='toggle-gap-0 block-filterx-switch'
                    onChange={ handleToggleChange }
                />
                { isLoading && 
                <div className='z-50 absolute left-0 right-0 top-0 bottom-0 bg-[#000000d1] rounded-md flex items-center justify-center'>
                    <Spinner className='block-in-progress'
                        style={{
                            height: '40px',
                            width: '40px'
                        }}
                    />
                </div>
                }
            </div>
        </Fragment>
	);
};

export default GutenBlock;