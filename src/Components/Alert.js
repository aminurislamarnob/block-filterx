import { Notice } from '@wordpress/components';

const Alert = ({ status, message, onDismiss }) => {
    // Render the notice
    if (!message) return null;
    
    return (
        <Notice
            className="!fixed bottom-4 right-4 z-50"
            status={status}
            isDismissible
            onDismiss={onDismiss}
        >
            {message}
        </Notice>
    );
};

export default Alert;
