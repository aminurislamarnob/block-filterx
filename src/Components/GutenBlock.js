import { __ } from '@wordpress/i18n';
import { useState, Fragment, useEffect } from 'react';
import apiFetch from '@wordpress/api-fetch';
import { ToggleControl, Spinner } from '@wordpress/components';
import BlockIcon from './BlockIcon';
import Alert from './Alert';

const GutenBlock = ({ blockData, disabledBlocks }) => {
    const [ value, setValue ] = useState( true );
    const [ isLoading, setIsLoading ] = useState( false );
	const [ message, setMessage ] = useState( '' );
    const [ error, setError ] = useState( '' );

    // Check if block is disabled initially
    useEffect(() => {
        if (disabledBlocks.includes(blockData.name)) {
            setValue(false);
        }
    }, [disabledBlocks, blockData.name]);

    // Handle toggle change and submit
    const handleToggleChange = async () => {
        setValue((state) => !state);
        setIsLoading( true );
		try {
			const response = await apiFetch( {
				path: '/block-filterx/v1/global-disabled-block',
				method: 'POST',
				data: {
					block_name: blockData.name,
				},
			} );
			setMessage(
				__( 'Settings saved successfully!', 'shop-front' )
			);
			setError( '' );
			setIsLoading( false );
		} catch (error) {
			setError( error.message );
			setMessage( '' );
			setIsLoading( false );
		}
    };
    
	return (
        <Fragment>
            <Alert
                status="success"
                message={message}
                onDismiss={() => setMessage('')}
            />
            <Alert
                status="error"
                message={error}
                onDismiss={() => setError('')}
            />
            <div className={`border border-[#e0e4e9] rounded-md p-2.5 flex flex-col items-center bg-[#f0f0f0] relative ${isLoading ? 'block-loading' : ''}`}>
                <BlockIcon icon={blockData.icon} />
                <h5 className='mb-2'>{blockData.title}</h5>
                <ToggleControl
                    __nextHasNoMarginBottom
                    checked={ value }
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