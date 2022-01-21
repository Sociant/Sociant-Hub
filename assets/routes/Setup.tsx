import { faCircleNotch } from '@fortawesome/pro-light-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { motion } from 'framer-motion'
import React, { useEffect, useState } from 'react'
import { useTranslation } from 'react-i18next'
import { useHistory } from 'react-router-dom'
import { useApp } from '../provider/AppProvider'
import { Container } from '../styledComponents/globalStyles'
import { OptionRow } from '../styledComponents/setttingsStyles'
import { MotionSetupPage, MotionSubmitButton, SubmitOverlay } from '../styledComponents/setupStyles'
import { darkTheme, lightTheme } from '../styledComponents/themes'
import { InfoResponse } from '../types/global'

export default function Setup() {
	const { data, theme, setTheme, profileChartScrollEffect, setProfileChartScrollEffect, setSetupCompleted } = useApp()

	const { t, i18n } = useTranslation()

	const [loading, setLoading] = useState(true)
	const [updatePeriod, setUpdatePeriod] = useState(null)
	const [isAPIKeyWorking, setIsAPIKeyWorking] = useState(false)

	const [submittingSetup, setSubmittingSetup] = useState(false)

	const history = useHistory()

	useEffect(() => {
		document.title = `Sociant Hub - ${t('pageTitles.setup')}`

		setLoading(true)
		loadData().then(() => {
			setLoading(false)
		})
	}, [])

	const loadData = async () => {
		const response = await fetch('/api/info', {
			headers: {
				Authorization: `Bearer ${data.apiToken}`,
			},
		})

		const responseData: InfoResponse = await response.json()

		if (typeof responseData.automated_update !== 'undefined') {
			setUpdatePeriod(responseData.automated_update ? responseData.automated_update.update_interval : 'h12')
			setIsAPIKeyWorking(true)
		}
	}

	const submitSetup = async () => {
		if (submittingSetup) return
		setSubmittingSetup(true)

		const formData = new FormData()
		formData.append('interval', updatePeriod)

		await fetch('/api/setup', {
			method: 'POST',
			headers: {
				Authorization: `Bearer ${data.apiToken}`,
			},
			body: formData,
		})

		setSetupCompleted(true)
		history.push('/profile')
	}

	const itemVariants = {
		hover: { scale: 1.05 },
		tap: { scale: 0.95 },
	}

	return (
		<MotionSetupPage initial={{ opacity: 0, y: 100 }} animate={{ opacity: 1, y: 0 }}>
			{submittingSetup && (
				<SubmitOverlay>
					<div className="inner">
						<div className="box">
							<FontAwesomeIcon spin={true} icon={faCircleNotch} />
							<div className="message">{t('setup.loadingMessage')}</div>
						</div>
					</div>
				</SubmitOverlay>
			)}
			<Container>
				<h1>{t('setup.title')}</h1>
				<p className="welcome-text">
					{t('setup.welcomeMessage', {
						name: data.twitter_user ? data.twitterUser.name : data.twitterUser.screenName,
					})}
				</p>
				<h2>{t('settings.updateInterval.title')}</h2>
				<OptionRow disabled={updatePeriod == null}>
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
				<MotionSubmitButton
					onClick={() => submitSetup()}
					variants={itemVariants}
					whileHover="hover"
					whileTap="tap">
					{t('setup.submitButton')}
				</MotionSubmitButton>
			</Container>
		</MotionSetupPage>
	)
}
