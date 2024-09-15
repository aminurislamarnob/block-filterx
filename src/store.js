import { createReduxStore, register } from '@wordpress/data';

const DEFAULT_STATE = {
    categoryWiseBlocks: [],
};

const actions = {
    setCategoryWiseBlocks(categoryWiseBlocks) {
        return {
            type: 'SET_CATEGORIES',
            categoryWiseBlocks,
        };
    },
    updateDisabledBlocks(categorySlug, disabledBlocks) {
        return {
            type: 'UPDATE_DISABLED_BLOCKS',
            categorySlug,
            disabledBlocks,
        };
    },
};

const reducer = (state = DEFAULT_STATE, action) => {
    switch (action.type) {
        case 'SET_CATEGORIES':
            return {
                ...state,
                categoryWiseBlocks: action.categoryWiseBlocks,
            };
        case 'UPDATE_DISABLED_BLOCKS':
            return {
                ...state,
                categoryWiseBlocks: state.categoryWiseBlocks.map((category) => {
                    // Check if the current category matches the categorySlug in the action
                    if (category.info.slug === action.categorySlug) {
                        return {
                            ...category,
                            disabledBlocks: category.blocks.filter((block) => action.disabledBlocks.includes(block.name)), // Update the disabledBlocks property
                        };
                    }
                    // Return category as is if not matching
                    return category;
                }),
                // categoryWiseBlocks: state.categoryWiseBlocks.map((category) => {
                //     return {
                //         ...category,
                //         disabledBlocks: category.blocks.filter((block) => action.disabledBlocks.includes(block.name)),
                //     };
                // }),
            };
        default:
            return state;
    }
};

const selectors = {
    getCategoryWiseBlocks(state) {
        return state.categoryWiseBlocks;
    },
    getCategory(state, categorySlug) {
        return state.categoryWiseBlocks.find((category) => category.info.slug === categorySlug);
    },
};


// Create the store using createReduxStore
const store = createReduxStore('block-filterx/store', {
    reducer,
    actions,
    selectors,
});

// Register the store so it's available globally
register(store);

export default store;