

export const pride = ['#e40303', '#ff8c00', '#ffed00', '#008026', '#004dff', '#750787']

export default {
    'none': null,
    'pride': pride
}

export function convertToGradient(colors: string[] | null, hardColors = true) {
    if(colors == null) return 'none';

	const percentage = 100 / colors.length
	let output = []

	colors.map((item, index) => {
		if (hardColors) {
			output.push(`${item} ${percentage * index}%`)

			if (index + 1 >= colors.length) output.push(`${item} 100%`)
			else output.push(`${item} ${percentage * (index + 1)}%`)
		} else output.push(item)
	})

	return `linear-gradient(to right, ${output.join(',')})`
}
