import { __ } from '@wordpress/i18n';
import { useState, useEffect, Fragment } from 'react';
import { Spinner, __experimentalGrid as Grid } from '@wordpress/components';
import apiFetch from '@wordpress/api-fetch';
import GutenBlock from './GutenBlock';
import CategoryHeader from './CategoryHeader';
import { useDispatch, useSelect } from '@wordpress/data';
import store from '../store';

const GlobalSettings = ({gutenBlocks, gutenCategories}) => {
	const [ isDisabledBlocksLoaded, setIsDisabledBlocksLoaded ] = useState(false);
    const [ isOrganizingedBlocks, setIsOrganizingedBlocks ] = useState(false);
    const [ error, setError ] = useState( '' );
	const [disabledBlocks, setDisabledBlocks] = useState([]);

	 // Dispatch actions to the store
	 const { setCategoryWiseBlocks } = useDispatch('block-filterx/store');

	 // Select state from the store
	 const getCategoryWiseBlocks = useSelect((select) => select('block-filterx/store').getCategoryWiseBlocks(), []);

	/**
	 * Organize Blocks By Category
	 */
	function organizeBlocks() {
		if (!gutenBlocks?.length || !gutenCategories?.length) {
			return false;
		}

		setIsOrganizingedBlocks(false);
		const data = gutenCategories.map(function (category) {
			const categoryBlocks = gutenBlocks.filter((block) => block.category === category.slug);
			return {
				info: category,
				blocks: categoryBlocks,
				disabledBlocks: categoryBlocks.filter((block) => disabledBlocks.includes(block.name)),
			};
		});
		setCategoryWiseBlocks(data); // Set blocks category wise to global store.
		setIsOrganizingedBlocks(true);
	}

	// Fetch globally disabled blocks.
	const fetchGloballyDisabledBlocks = async () => {
		setIsDisabledBlocksLoaded(false); 
		try {
			const response = await apiFetch({
				path: '/block-filterx/v1/global-disabled-block',
			});
			setDisabledBlocks(response);
			setError(null); // Clear any previous errors
			setIsDisabledBlocksLoaded(true);
		} catch (err) {
			setError( err.message );
			setIsDisabledBlocksLoaded(true);
		}
	};
	
    useEffect(() => {
		fetchGloballyDisabledBlocks();
    }, []);

    useEffect(() => {
		organizeBlocks();
    }, [gutenBlocks, gutenCategories, disabledBlocks]);

	const isBlockReadyToDisplay = isDisabledBlocksLoaded && isOrganizingedBlocks;
	
	return (
		<div>
			<div className='settings-header'>
				<div className='settings-header-icon'>
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" className="bi bi-gear" viewBox="0 0 16 16">
						<path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492M5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0"/>
						<path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52zm-2.633.283c.246-.835 1.428-.835 1.674 0l.094.319a1.873 1.873 0 0 0 2.693 1.115l.291-.16c.764-.415 1.6.42 1.184 1.185l-.159.292a1.873 1.873 0 0 0 1.116 2.692l.318.094c.835.246.835 1.428 0 1.674l-.319.094a1.873 1.873 0 0 0-1.115 2.693l.16.291c.415.764-.42 1.6-1.185 1.184l-.291-.159a1.873 1.873 0 0 0-2.693 1.116l-.094.318c-.246.835-1.428.835-1.674 0l-.094-.319a1.873 1.873 0 0 0-2.692-1.115l-.292.16c-.764.415-1.6-.42-1.184-1.185l.159-.291A1.873 1.873 0 0 0 1.945 8.93l-.319-.094c-.835-.246-.835-1.428 0-1.674l.319-.094A1.873 1.873 0 0 0 3.06 4.377l-.16-.292c-.415-.764.42-1.6 1.185-1.184l.292.159a1.873 1.873 0 0 0 2.692-1.115z"/>
					</svg>
				</div>
				<h2>{ __( 'Enable/Disable Block Globally', 'shop-front' ) }</h2>
			</div>
			<div className='relative'>
			{ !isBlockReadyToDisplay && 
                <div className='z-50 absolute left-0 right-0 top-0 bottom-0 bg-[#000000d1] rounded-md flex items-center justify-center'>
                    <Spinner className='block-in-progress'
                        style={{
                            height: '40px',
                            width: '40px'
                        }}
                    />
                </div>
            }
			{isBlockReadyToDisplay && !!getCategoryWiseBlocks?.length &&
				getCategoryWiseBlocks.map((category) => (
					<Fragment key={category.info.slug}>
						<CategoryHeader category={category}/>
						<Grid columns={ 6 } className={'mb-6'}>
						{!!category?.blocks?.length && category.blocks.map((block) => (
							<Fragment key={block.name}>
								<GutenBlock blockData={block} disabledBlocks={category.disabledBlocks} />
							</Fragment>
						))}
						</Grid>
					</Fragment>
				))}
			</div>
		</div>
	);
};

export default GlobalSettings;