import { Notice } from '@wordpress/components';
import { useEffect } from 'react';

const Alert = ({ status, message, onDismiss }) => {
    // Auto-dismiss the notice after 5 seconds
    useEffect(() => {
        if (message) {
            const timer = setTimeout(() => {
                onDismiss();
            }, 5000); // 5 seconds

            return () => clearTimeout(timer); // Cleanup the timer
        }
    }, [message, onDismiss]);

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
