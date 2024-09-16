import { createReduxStore, register } from '@wordpress/data';

const DEFAULT_STATE = {
    categoryWiseBlocks: [],
    notification: {
        status: false,
        type: 'success',
        message: ''
    }
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
    setNotification(status, type, message) {
        return {
            type: 'SET_NOTIFICATION',
            status,
            notificationType: type,
            message
        };
    },
    clearNotification() {
        return {
            type: 'CLEAR_NOTIFICATION'
        };
    }
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
            };
        case 'SET_NOTIFICATION':
            return {
                ...state,
                notification: {
                    status: action.status,
                    type: action.notificationType,
                    message: action.message
                },
            };
        case 'CLEAR_NOTIFICATION':
            return {
                ...state,
                notification: {
                    status: false,
                    type: '',
                    message: ''
                }
            };
        default:
            return state;
    }
};

const selectors = {
    getNotification(state) {
        return state.notification;
    },
    getCategoryWiseBlocks(state) {
        return state.categoryWiseBlocks;
    },
    getCategory(state, categorySlug) {
        return state.categoryWiseBlocks.find((category) => category.info.slug === categorySlug);
    },
};

// Define the triggerNotification function
let notificationTimeoutId;
export const triggerNotification = (setNotification, type, message) => {
    // Clear the previous timeout if it exists
    if (notificationTimeoutId) {
        clearTimeout(notificationTimeoutId);
    }

    setNotification(true, type, message);

    notificationTimeoutId = setTimeout(() => {
        setNotification(false, '', ''); // Clear the notification
        notificationTimeoutId = null; // Reset the timeout ID
    }, 5000);
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