export type Theme = {
	background: string
	textPrimary: string
	textSecondary: string
	navigation: {
		background: string
		title: string
		item: string
		itemHover: string
	}
	twitter: {
		border: string
		background: string
		text: string
		borderHover: string
		backgroundHover: string
		textHover: string
	}
	card: {
		background: string
		itemBackground: string
		profileHover: string
		button: string
		buttonHover: string
	}
    settingsBar: {
        background: string
        backgroundHover: string
    }
    footer: {
        background: string
		selectBackground: string
    }
	settings: {
		item: string
		itemHover: string
		itemSelected: string
		tableOddRow: string
		tableDivider: string
	}
}

const lightTheme: Theme = {
	background: '#efefef',
	textPrimary: '#3c3c3c',
	textSecondary: '#7c7c7c',
	navigation: {
		background: '#ffffff',
		title: '#3c3c3c',
		item: '#3c3c3c',
		itemHover: '#000000'
	},
	twitter: {
		border: '#1da1f2',
		background: '#1da1f2',
		text: '#ffffff',
		borderHover: '#1da1f2',
		backgroundHover: 'none',
		textHover: '#1da1f2',
	},
	card: {
		background: '#f8f8f8cc',
		itemBackground: '#ffffff',
		profileHover: '#ffffff',
		button: '#ffffff',
		buttonHover: '#f8f8f8'
	},
    settingsBar: {
		background: '#EEEEEEcc',
		backgroundHover: '#f8f8f8'
    },
    footer: {
        background: '#f8f8f8',
		selectBackground: '#ffffff'
    },
	settings: {
		item: '#ffffff',
		itemHover: '#f8f8f8',
		itemSelected: '#42A5F5',
		tableOddRow: '#ffffff',
		tableDivider: '#ffffff'
	}
}

const darkTheme: Theme = {
	background: '#121212',
	textPrimary: '#a7a7a7',
	textSecondary: '#888888',
	navigation: {
		background: '#1F1F1F',
		title: '#ffffff',
		item: '#a7a7a7',
		itemHover: '#ffffff'
	},
	twitter: {
		border: '#a7a7a7',
		background: 'none',
		text: '#a7a7a7',
		borderHover: '#fff',
		backgroundHover: 'none',
		textHover: '#fff',
	},
	card: {
		background: '#1a1a1acc',
		itemBackground: '#212121',
		profileHover: '#212121',
		button: '#212121',
		buttonHover: '#323232'
	},
    settingsBar: {
		background: '#424242cc',
		backgroundHover: '#323232'
    },
    footer: {
        background: '#1a1a1a',
		selectBackground: '#3A3A3A'
    },
	settings: {
		item: '#212121',
		itemHover: '#1a1a1a',
		itemSelected: '#0277BD',
		tableOddRow: '#212121',
		tableDivider: '#212121'
	}
}

export { lightTheme, darkTheme }