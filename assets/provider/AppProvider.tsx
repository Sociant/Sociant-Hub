import React, { createContext, useState, useContext, useEffect } from 'react'
import { Theme } from '../styledComponents/themes'
import { darkTheme, lightTheme } from '../styledComponents/themes'
import { pride } from '../types/titleColorBars'
import { isOnMobile } from '../utilities/utilities'

export type AppType = {
	isReady: boolean
	theme: Theme
	titleBarColors: string
	data: AppData | null
	profileChartScrollEffect: boolean

	setTheme: (theme: Theme) => void
	setTitleBarColors: (colors: string) => void
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
	titleBarColors: 'pride',
	data: null,
	profileChartScrollEffect: true,
	setTheme: (_) => {},
	setTitleBarColors: (colors: string) => {}
})

export const AppProvider = props => {
	const [theme, setTheme] = useState<Theme>((localStorage.getItem('theme') ?? 'dark') === 'dark' ? darkTheme : lightTheme)
	const [data, setData] = useState<AppData | null>(null)
	const [titleBarColors, setTitleBarColors] = useState<string>(localStorage.getItem('titleBar') ?? 'pride')
	const [profileChartScrollEffect, setProfileChartScrollEffect] = useState<boolean>((localStorage.getItem('profileChartScrollEffect') ?? !isOnMobile()).toString() === 'true')

	const [isReady, setIsReady] = useState(false)

	useEffect(() => {
		const app = document.getElementById('app')
		const appData: AppData = JSON.parse(app.getAttribute('data-app'))
		app.removeAttribute('data-app')

		setData(appData)
		setIsReady(true)
	}, [])

	useEffect(() => {
		localStorage.setItem('theme', theme === darkTheme ? 'dark' : 'light')
	}, [theme])

	useEffect(() => {
		localStorage.setItem('titleBar', titleBarColors)
	}, [titleBarColors])

	useEffect(() => {
		localStorage.setItem('profileChartScrollEffect', profileChartScrollEffect)
	}, [profileChartScrollEffect])

	const defaultValue = {
		isReady,
		theme,
		titleBarColors,
		data,
		profileChartScrollEffect,
		setTheme: (theme: Theme) => setTheme(theme),
		setTitleBarColors: (colors: string) => setTitleBarColors(colors),
		setProfileChartScrollEffect: (scrollEffect: boolean) => setProfileChartScrollEffect(scrollEffect)
	}

	return <AppContext.Provider value={defaultValue}>{props.children}</AppContext.Provider>
}

export const useApp = () => useContext(AppContext)