import React, { useEffect, useRef, useState } from 'react'
import { Container, MotionLoader } from '../styledComponents/globalStyles'
import { useApp } from '../provider/AppProvider'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faCircleNotch } from '@fortawesome/pro-light-svg-icons'
import { useTranslation } from 'react-i18next'
import { MotionSettingsPage, OptionRow } from '../styledComponents/setttingsStyles'

import { motion } from 'framer-motion'
import { darkTheme, lightTheme } from '../styledComponents/themes'

export default function Settings() {
	const { data, theme, setTheme } = useApp()

	const { t } = useTranslation()

	const card = useRef()

	const [loading, setLoading] = useState(true)

	const [updatePeriod, setUpdatePeriod] = useState('m')

	useEffect(() => {
        document.title = `Sociant Hub - ${ t('pageTitles.settings') }`;

		setLoading(true)
		loadData().then(() => {
			setLoading(false)
		})
	}, [])

	const loadData = async () => {}

	const downloadTarget = (endpoint: string, format: string) => {
		return `/api/download/${endpoint}?format=${format}&token=${data.apiToken}`
	}

	const itemVariants = {
		hover: { scale: 1.05 },
		tap: { scale: 0.95 },
	}

	if (loading)
		return (
			<MotionLoader initial={{ opacity: 0, y: -50 }} animate={{ opacity: 1, y: 0 }}>
				<FontAwesomeIcon icon={faCircleNotch} spin={true} />
			</MotionLoader>
		)

	return (
		<MotionSettingsPage initial={{ opacity: 0, y: 100 }} animate={{ opacity: 1, y: 0 }}>
			<Container>
				<h1>Settings</h1>
				<h2>Update interval</h2>
				<OptionRow>
					<motion.div
						onClick={() => setUpdatePeriod('m')}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${updatePeriod === 'm' ? ' selected' : ''}`}>
						Manual update
					</motion.div>
					<motion.div
						onClick={() => setUpdatePeriod('h1')}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${updatePeriod === 'h1' ? ' selected' : ''}`}>
						Every hour
					</motion.div>
					<motion.div
						onClick={() => setUpdatePeriod('h12')}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${updatePeriod === 'h12' ? ' selected' : ''}`}>
						Every 12 hours
					</motion.div>
					<motion.div
						onClick={() => setUpdatePeriod('d1')}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${updatePeriod === 'd1' ? ' selected' : ''}`}>
						Every day
					</motion.div>
					<motion.div
						onClick={() => setUpdatePeriod('w1')}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${updatePeriod === 'w1' ? ' selected' : ''}`}>
						Every week
					</motion.div>
				</OptionRow>
				<h2>Theme</h2>
				<OptionRow>
					<motion.div
						onClick={() => setTheme(darkTheme)}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${theme === darkTheme ? ' selected' : ''}`}>
						Darkmode
					</motion.div>
					<motion.div
						onClick={() => setTheme(lightTheme)}
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						className={`item${theme === lightTheme ? ' selected' : ''}`}>
						Lightmode
					</motion.div>
				</OptionRow>
				<h2>Download your data</h2>
				<table>
					<tbody>
						<tr>
							<td>
								Follower History
								<br />
								<small>List with users you followed, unfollowed and vice versa</small>
							</td>
							<td>
								<a target="_blank" href={downloadTarget('activities', 'csv')}>
									CSV
								</a>
							</td>
							<td>
								<a target="_blank" href={downloadTarget('activities', 'json')}>
									JSON
								</a>
							</td>
						</tr>
						<tr>
							<td>Follower Count (Total)</td>
							<td>
								<a target="_blank" href={downloadTarget('history/all', 'csv')}>
									CSV
								</a>
							</td>
							<td>
								<a target="_blank" href={downloadTarget('history/all', 'json')}>
									JSON
								</a>
							</td>
						</tr>
						<tr>
							<td>Follower Count (by year)</td>
							<td>
								<a target="_blank" href={downloadTarget('history/year', 'csv')}>
									CSV
								</a>
							</td>
							<td>
								<a target="_blank" href={downloadTarget('history/year', 'json')}>
									JSON
								</a>
							</td>
						</tr>
						<tr>
							<td>Follower Count (by month)</td>
							<td>
								<a target="_blank" href={downloadTarget('history/month', 'csv')}>
									CSV
								</a>
							</td>
							<td>
								<a target="_blank" href={downloadTarget('history/month', 'json')}>
									JSON
								</a>
							</td>
						</tr>
						<tr>
							<td>Follower Count (by day)</td>
							<td>
								<a target="_blank" href={downloadTarget('history/day', 'csv')}>
									CSV
								</a>
							</td>
							<td>
								<a target="_blank" href={downloadTarget('history/day', 'json')}>
									JSON
								</a>
							</td>
						</tr>
						<tr>
							<td>
								Additional data
								<br />
								<small>Twitter-User, connected devices, automated updates, analytics and more</small>
							</td>
							<td></td>
							<td>
								<a target="_blank" href={downloadTarget('additional', 'json')}>
									JSON
								</a>
							</td>
						</tr>
					</tbody>
				</table>
			</Container>
		</MotionSettingsPage>
	)
}
