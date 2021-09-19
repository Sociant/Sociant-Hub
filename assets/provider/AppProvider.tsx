import React, { createContext, useState, useContext, useEffect } from 'react'
import { Theme } from '../styledComponents/themes'
import { darkTheme, lightTheme } from '../styledComponents/themes'

export type AppType = {
	isReady: boolean
	theme: Theme
	data: AppData | null
	setTheme: (theme: Theme) => void
}

export type AppData = {
	authenticated: boolean
	twitterUser: {
		name: string
		screenName: string
		profileImageURL: string
	} | null
	screenName: string | null
	name: string | null
	apiToken: string | null
}

export const AppContext = createContext<AppType>({
	isReady: false,
    theme: darkTheme,
	data: null,
	setTheme: (_) => {}
})

export const AppProvider = props => {
	const [theme, setTheme] = useState<Theme>(darkTheme)
	const [data, setData] = useState<AppData | null>(null)

	const [isReady, setIsReady] = useState(false)

	useEffect(() => {
		const app = document.getElementById('app')
		const appData: AppData = JSON.parse(app.getAttribute('data-app'))
		app.removeAttribute('data-app')

		setData(appData)
		setIsReady(true)
	}, [])

	const defaultValue = {
		isReady,
		theme,
		data,
		setTheme: (theme: Theme) => setTheme(theme)
	}

	return <AppContext.Provider value={defaultValue}>{props.children}</AppContext.Provider>
}

export const useApp = () => useContext(AppContext)