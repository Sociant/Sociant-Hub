import React, { useEffect, useRef, useState } from 'react'
import { Container, MotionLoader } from '../styledComponents/globalStyles'
import { useApp } from '../provider/AppProvider'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faCircleNotch, faClipboard } from '@fortawesome/pro-light-svg-icons'
import { useTranslation } from 'react-i18next'
import { APIKeyContainer, MotionSettingsPage, OptionRow } from '../styledComponents/setttingsStyles'

import { motion } from 'framer-motion'
import { darkTheme, lightTheme } from '../styledComponents/themes'
import { AutomatedUpdate, InfoResponse } from '../types/global'

export default function Settings() {
	const { data, theme, setTheme, profileChartScrollEffect, setProfileChartScrollEffect } = useApp()

	const { t, i18n } = useTranslation()

	const [loading, setLoading] = useState(true)
	const [updatePeriod, setUpdatePeriod] = useState(null)
	const [updatingPeriod, setUpdatingPeriod] = useState(false)
	const [isAPIKeyWorking, setIsAPIKeyWorking] = useState(false)
	const [apiKeyCopied, setApiKeyCopied] = useState(null)

	useEffect(() => {
		document.title = `Sociant Hub - ${t('pageTitles.settings')}`

		setLoading(true)
		loadData().then(() => {
			setLoading(false)
		})
	}, [])

	useEffect(() => {
		if (!loading && !updatingPeriod) submitUpdateInterval()
	}, [updatePeriod])

	const submitUpdateInterval = async () => {
		setUpdatingPeriod(true)

		const formData = new FormData()
		formData.append('interval', updatePeriod)

		const response = await fetch('/api/update-interval', {
			method: 'POST',
			body: formData,
			headers: {
				Authorization: `Bearer ${data.apiToken}`,
			},
		})

		setUpdatingPeriod(false)
	}

	const loadData = async () => {
		const response = await fetch('/api/info', {
			headers: {
				Authorization: `Bearer ${data.apiToken}`,
			},
		})

		const responseData: InfoResponse = await response.json()

		if (responseData.automated_update) {
			setUpdatePeriod(responseData.automated_update.update_interval)
			setIsAPIKeyWorking(true)
		}
	}

	const copyAPITokenToClipboard = () => {
		navigator.clipboard
			.writeText(data.apiToken)
			.then(() => {
				setApiKeyCopied(true)
			})
			.catch(() => {
				setApiKeyCopied(false)
			})
	}

	const downloadTarget = (endpoint: string, format: string) => {
		return `/api/download/${endpoint}?format=${format}&token=${data.apiToken}`
	}

	const itemVariants = {
		hover: { scale: 1.05 },
		tap: { scale: 0.95 },
	}

	return (
		<MotionSettingsPage initial={{ opacity: 0, y: 100 }} animate={{ opacity: 1, y: 0 }}>
			<Container>
				<h1>{t('settings.title')}</h1>
				<h2>{t('settings.updateInterval.title')}</h2>
				<OptionRow disabled={updatePeriod == null || updatingPeriod}>
					<motion.div
						onClick={() => setUpdatePeriod('m')}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${updatePeriod === 'm' ? ' selected' : ''}`}>
						{t('settings.updateInterval.items.m')}
					</motion.div>
					<motion.div
						onClick={() => setUpdatePeriod('h1')}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${updatePeriod === 'h1' ? ' selected' : ''}`}>
						{t('settings.updateInterval.items.h1')}
					</motion.div>
					<motion.div
						onClick={() => setUpdatePeriod('h12')}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${updatePeriod === 'h12' ? ' selected' : ''}`}>
						{t('settings.updateInterval.items.h12')}
					</motion.div>
					<motion.div
						onClick={() => setUpdatePeriod('d1')}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${updatePeriod === 'd1' ? ' selected' : ''}`}>
						{t('settings.updateInterval.items.d1')}
					</motion.div>
					<motion.div
						onClick={() => setUpdatePeriod('w1')}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${updatePeriod === 'w1' ? ' selected' : ''}`}>
						{t('settings.updateInterval.items.w1')}
					</motion.div>
				</OptionRow>
				<h2>{t('settings.theme.title')}</h2>
				<OptionRow>
					<motion.div
						onClick={() => setTheme(darkTheme)}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${theme === darkTheme ? ' selected' : ''}`}>
						{t('settings.theme.items.darkmode')}
					</motion.div>
					<motion.div
						onClick={() => setTheme(lightTheme)}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${theme === lightTheme ? ' selected' : ''}`}>
						{t('settings.theme.items.lightmode')}
					</motion.div>
				</OptionRow>
				<h2>{t('settings.language.title')}</h2>
				<OptionRow>
					<motion.div
						onClick={() => i18n.changeLanguage('en')}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${i18n.language === 'en' ? ' selected' : ''}`}>
						{t('settings.language.items.english')}
					</motion.div>
					<motion.div
						onClick={() => i18n.changeLanguage('de')}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${i18n.language === 'de' ? ' selected' : ''}`}>
						{t('settings.language.items.german')}
					</motion.div>
				</OptionRow>
				<h2>{t('settings.profileChartScrollEffect.title')}</h2>
				<OptionRow>
					<motion.div
						onClick={() => setProfileChartScrollEffect(true)}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${profileChartScrollEffect ? ' selected' : ''}`}>
						{t('settings.profileChartScrollEffect.items.on')}
					</motion.div>
					<motion.div
						onClick={() => setProfileChartScrollEffect(false)}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${!profileChartScrollEffect ? ' selected' : ''}`}>
						{t('settings.profileChartScrollEffect.items.off')}
					</motion.div>
				</OptionRow>
				<h2>{t('settings.apiToken.title')}</h2>
				<APIKeyContainer>
					<div className="container">
						<span>{data.apiToken}</span>
						<small>{t('settings.apiToken.hint')}</small>
					</div>
					<motion.div
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`clipboard${apiKeyCopied != null ? (apiKeyCopied ? ' success' : ' error') : ''}`}
						onClick={copyAPITokenToClipboard}>
						<FontAwesomeIcon icon={faClipboard} />
						<span>Kopieren</span>	
					</motion.div>
				</APIKeyContainer>

				<h2>{t('settings.download.title')}</h2>
				<table>
					<tbody>
						<tr>
							<td>
								{t('settings.download.followerHistory.title')}
								<br />
								<small>{t('settings.download.followerHistory.subtitle')}</small>
							</td>
							<td>
								<a target="_blank" href={downloadTarget('activities', 'csv')}>
									{t('settings.download.csv')}
								</a>
							</td>
							<td>
								<a target="_blank" href={downloadTarget('activities', 'json')}>
									{t('settings.download.json')}
								</a>
							</td>
						</tr>
						<tr>
							<td>
								{t('settings.download.followerCount.title')} (
								{t('settings.download.followerCount.types.total')})
							</td>
							<td>
								<a target="_blank" href={downloadTarget('history/all', 'csv')}>
									{t('settings.download.csv')}
								</a>
							</td>
							<td>
								<a target="_blank" href={downloadTarget('history/all', 'json')}>
									{t('settings.download.json')}
								</a>
							</td>
						</tr>
						<tr>
							<td>
								{t('settings.download.followerCount.title')} (
								{t('settings.download.followerCount.types.year')})
							</td>
							<td>
								<a target="_blank" href={downloadTarget('history/year', 'csv')}>
									{t('settings.download.csv')}
								</a>
							</td>
							<td>
								<a target="_blank" href={downloadTarget('history/year', 'json')}>
									{t('settings.download.json')}
								</a>
							</td>
						</tr>
						<tr>
							<td>
								{t('settings.download.followerCount.title')} (
								{t('settings.download.followerCount.types.month')})
							</td>
							<td>
								<a target="_blank" href={downloadTarget('history/month', 'csv')}>
									{t('settings.download.csv')}
								</a>
							</td>
							<td>
								<a target="_blank" href={downloadTarget('history/month', 'json')}>
									{t('settings.download.json')}
								</a>
							</td>
						</tr>
						<tr>
							<td>
								{t('settings.download.followerCount.title')} (
								{t('settings.download.followerCount.types.day')})
							</td>
							<td>
								<a target="_blank" href={downloadTarget('history/day', 'csv')}>
									{t('settings.download.csv')}
								</a>
							</td>
							<td>
								<a target="_blank" href={downloadTarget('history/day', 'json')}>
									{t('settings.download.json')}
								</a>
							</td>
						</tr>
						<tr>
							<td>
								{t('settings.download.followerCount.title')} ({t('settings.download.followerIds')})
							</td>
							<td>
								<a target="_blank" href={downloadTarget('list/followers', 'csv')}>
									{t('settings.download.csv')}
								</a>
							</td>
							<td>
								<a target="_blank" href={downloadTarget('list/followers', 'json')}>
									{t('settings.download.json')}
								</a>
							</td>
						</tr>
						<tr>
							<td>
								{t('settings.download.followerCount.title')} ({t('settings.download.followingIds')})
							</td>
							<td>
								<a target="_blank" href={downloadTarget('list/following', 'csv')}>
									{t('settings.download.csv')}
								</a>
							</td>
							<td>
								<a target="_blank" href={downloadTarget('list/following', 'json')}>
									{t('settings.download.json')}
								</a>
							</td>
						</tr>
						<tr>
							<td>
								{t('settings.download.additionalData.title')}
								<br />
								<small>{t('settings.download.additionalData.subtitle')}</small>
							</td>
							<td></td>
							<td>
								<a target="_blank" href={downloadTarget('additional', 'json')}>
									{t('settings.download.json')}
								</a>
							</td>
						</tr>
					</tbody>
				</table>
			</Container>
		</MotionSettingsPage>
	)
}
