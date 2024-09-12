import { renderToString } from '@wordpress/element';

const BlockIcon = ({ icon }) => {
	let src = icon?.src || icon;

	// Check icon src is block default then return
	if ('block-default' === src) {
		return null;
	}

	 // Check if the icon is a function (typically a React component).
	if ('function' === typeof src) {
        // If the function contains 'createElement', it is likely a React component. 
        // Call the function to render its JSX.
		if (src.toString().indexOf('createElement') >= 0) {
			src = src();
		}
	}

	return (
		<div
			aria-hidden="true"
			className="block-filterx-icon"
			dangerouslySetInnerHTML={{ __html: renderToString(src) }}
		></div>
	);
};

export default BlockIcon;