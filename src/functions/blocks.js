export const excludedBlocks = [
	'core/missing',
	'core/text-columns',
	'core/navigation-submenu',
	//"core/pattern",
	//"core/post-navigation-link",
];
export const variationBlocks = ['core/embed'];

/**
 * Sort the blocks alpha and then remove excluded blocks.
 *
 * @param {Array} blocks Array of WP Blocks.
 * @return {Array}       The sorted array of blocks.
 */
export function sortBlocks(blocks) {
	return blocks
		.sort(function (a, b) {
			const textA = a.title.toUpperCase();
			const textB = b.title.toUpperCase();
			return textA < textB ? -1 : textA > textB ? 1 : 0; //eslint-disable-line
		})
		.filter((block) => {
			return excludedBlocks.indexOf(block.name) === -1;
		});
}

/**
 * Get all WP blocks and variations with updated categories.
 *
 * @param {Array} blocks             Array of WP Blocks.
 * @param {Array} filteredCategories The filtered categories.
 * @return {Array}                   The list of blocks.
 */
export function getBlocksData(blocks, filteredCategories = []) {
	if (!blocks?.length) {
		return [];
	}

	// Get and sort blocks.
	const wpBlocks = sortBlocks(blocks);

	// Update block categories.
	if (filteredCategories?.length) {
		// Loop saved categories.
		filteredCategories.forEach((item) => {
			const { block: name, cat: category } = item;

			// Find block by name and update category.
			const match = wpBlocks.find((block) => block.name === name);
			if (match) {
				match.category = category;
			}
		});
	}

	return getAllBlocksAndVariations(wpBlocks);
}

/**
 * Get an array of blocks and any block variations.
 * Block variations are stored in a nested `variations` array of each block.
 *
 * @param {Array} blocks Array of blocks.
 * @return {Array}       Array of blocks with variations included.
 */
export function getAllBlocksAndVariations(blocks) {
	if (!blocks?.length) {
		return [];
	}
	const WPBlocks = [];

	// Loop all blocks.
	blocks.forEach((block) => {
		const { name, variations, category } = block;
		WPBlocks.push(block);
		if (variationBlocks.includes(name) && variations?.length) {
			// Loop block variations and push into array.
			variations.forEach((variation) => {
				WPBlocks.push({
					...variation,
					name: `variation;${name};${variation?.name}`,
					variation: name,
					category,
				});
			});
		}
	});
	return WPBlocks;
}