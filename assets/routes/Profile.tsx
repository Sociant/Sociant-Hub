import React, { useState, useEffect, useRef, useMemo, Suspense } from 'react'
const ReactApexChart = React.lazy(() => import('react-apexcharts'))
import { Link } from 'react-router-dom'
import { Loader, MotionLoader, UserList } from '../styledComponents/globalStyles'
import {
	ProfilePage,
	MotionStatisticCard,
	Statistics,
	ChartContainer,
	GraphSettings,
} from '../styledComponents/profileStyles'
import { useApp } from '../provider/AppProvider'
import {
	ActivityEntry,
	HistoryResponse,
	HomeResponse,
	StatisticsResponse,
	TwitterUserExtended,
	Statistics as StatisticsType,
	Analytics,
	HistoryEntry,
} from '../types/global'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faChevronDown, faCircleNotch, faSync } from '@fortawesome/pro-light-svg-icons'
import {
	formatAction,
	formatDate,
	getAccountAge,
	getActionIcon,
	getDifference,
	thousandSeparator,
} from '../utilities/utilities'
import { useTranslation } from 'react-i18next'

import { motion } from 'framer-motion'
import UserItem from '../components/userItem'
import dateFormat from 'dateformat'

export default function Profile() {
	const { data } = useApp()

	const [loading, setLoading] = useState(true)
	const [periodLoading, setPeriodLoading] = useState(false)
	const [period, setPeriod] = useState('month')

	const [series, setSeries] = useState()
	const [options, setOptions] = useState()

	const [scrollY, setScrollY] = useState(window.scrollY)

	const [activities, setActivities] = useState<ActivityEntry[]>(null)

	const [twitterUser, setTwitterUser] = useState<TwitterUserExtended>(null)

	const profilePage = useRef(null)

	const [statistics, setStatistics] = useState<StatisticsType>(null)
	const [analytics, setAnalytics] = useState<Analytics>(null)

	const [periodToggle, setPeriodToggle] = useState(false)

	const [canUpdate, setCanUpdate] = useState(false)
	const [manualUpdating, setManualUpdating] = useState(false)

	const [automatedUpdate, setAutomatedUpdate] = useState(null)

	const { t } = useTranslation()

	const chartContainerStyles = useMemo(() => {
		let blurScale = 0
		let sizeScale = 1
		let opacityScale = 1

		if (scrollY > 60) {
			let value = 435 - (scrollY - 60)
			if (value < 0) value = 0

			blurScale = 4 - (4 / 435) * value
			opacityScale = (1 / 435) * value
			sizeScale = 0.9 + (0.1 / 435) * value
		}

		return {
			filter: `blur(${blurScale}px)`,
			transform: `scale(${sizeScale})`,
			opacity: opacityScale,
		}
	}, [scrollY])

	const accountAge = useMemo(() => {
		if (!statistics) return null
		return getAccountAge(statistics.created_at, t)
	}, [statistics])

	useEffect(() => {
		const onScroll = (e) => {
			if (profilePage.current) {
				setScrollY(window.scrollY)
			}
		}

		document.addEventListener('scroll', onScroll)

		setLoading(true)
		loadData().then(() => {
			setLoading(false)
		})

		return () => {
			document.removeEventListener('scroll', onScroll)
		}
	}, [])

	useEffect(() => {
		if (activities == null) return

		setPeriodLoading(true)

		loadHistory().then(() => {
			setPeriodLoading(false)
		})
	}, [period])

	const loadData = async () => {
		const response = await fetch(`/api/home?type=${period}`, {
			headers: {
				Authorization: `Bearer ${data.apiToken}`,
			},
		})

		const responseData: HomeResponse = await response.json()

		const statisticsResponse = await fetch(`/api/statistics`, {
			headers: {
				Authorization: `Bearer ${data.apiToken}`,
			},
		})

		const statisticsResponseData: StatisticsResponse = await statisticsResponse.json()

		updateGraph(responseData.history)

		setActivities(responseData.activities)
		setTwitterUser(responseData.twitter_user)

		setStatistics(statisticsResponseData.statistics)
		setAnalytics(statisticsResponseData.analytics)

		setCanUpdate(responseData.can_update)
		setAutomatedUpdate(responseData.automated_update)
	}

	const loadHistory = async () => {
		const response = await fetch(`/api/history/${period}`, {
			headers: {
				Authorization: `Bearer ${data.apiToken}`,
			},
		})

		const responseData: HistoryResponse = await response.json()

		updateGraph(responseData.items)
	}

	const updateGraph = (history: HistoryEntry[]) => {
		let series1 = []
		let series2 = []
		let xAxis = []

		let minFollowers: number = null
		let maxFollowers: number = null

		let minFollowing: number = null
		let maxFollowing: number = null

		for (const item of history) {
			series1.push(item.followerCount)
			series2.push(item.followingCount)
			xAxis.push(item.date * 1000)

			if (minFollowers == null || item.followerCount < minFollowers) minFollowers = item.followerCount
			if (maxFollowers == null || item.followerCount > maxFollowers) maxFollowers = item.followerCount

			if (minFollowing == null || item.followingCount < minFollowing) minFollowing = item.followingCount
			if (maxFollowing == null || item.followingCount > maxFollowing) maxFollowing = item.followingCount
		}

		minFollowers = minFollowers - (Math.abs(minFollowers - maxFollowers) / 100) * 5 - 1
		maxFollowers = maxFollowers + (Math.abs(minFollowers - maxFollowers) / 100) * 5 + 1

		minFollowing = minFollowing - (Math.abs(minFollowing - maxFollowing) / 100) * 5 - 1
		maxFollowing = maxFollowing + (Math.abs(minFollowing - maxFollowing) / 100) * 5 + 1

		let format = t('profile.graph.formats.hour')

		switch (period) {
			case 'day':
				format = t('profile.graph.formats.day')
				break
			case 'month':
				format = t('profile.graph.formats.month')
				break
		}

		setSeries([
			{
				name: t('profile.graph.follower'),
				data: series1,
			},
			{
				name: t('profile.graph.following'),
				data: series2,
			},
		])

		setOptions({
			chart: {
				height: 350,
				type: 'area',
				toolbar: {
					show: false,
				},
				zoom: {
					enabled: false,
				},
			},
			colors: ['#00B294', '#FF8C00'],
			dataLabels: {
				enabled: false,
			},
			grid: {
				yaxis: {
					lines: {
						show: false,
					},
				},
			},
			stroke: {
				curve: 'smooth',
			},
			xaxis: {
				type: 'datetime',
				categories: xAxis,
				axisBorder: {
					show: false,
				},
				axisTicks: {
					show: false,
				},
			},
			yaxis: [
				{
					title: {
						text: t('profile.graph.follower'),
					},
					max: maxFollowers,
					min: minFollowers,
					labels: {
						formatter: (val) => val.toFixed(0),
					},
				},
				{
					opposite: true,
					title: {
						text: t('profile.graph.following'),
					},
					max: maxFollowing,
					min: minFollowing,
					labels: {
						formatter: (val) => val.toFixed(0),
					},
				},
			],
			tooltip: {
				x: {
					format: format,
				},
			},
			legend: {
				show: false,
			},
		})
	}

	const manualUpdate = async () => {
		if (manualUpdating || !canUpdate) return
		setManualUpdating(true)

		const response = await fetch('/api/manual-update', {
			method: 'put',
			headers: {
				Authorization: `Bearer ${data.apiToken}`,
			},
		})

		const responseText = await response.text()

		setManualUpdating(false)

		if (responseText === 'OK') {
			setLoading(true)
			loadData().then(() => {
				setLoading(false)
			})
		}
	}

	const itemVariants = {
		hover: { scale: 1.05 },
		tap: { scale: 0.95 },
	}

	const imageVariants = {
		animate: { y: -80 },
		hover: { scale: 1.05, y: -80 },
		tap: { scale: 0.95, y: -80 },
	}

	if (loading)
		return (
			<MotionLoader initial={{ opacity: 0, y: -50 }} animate={{ opacity: 1, y: 0 }}>
				<FontAwesomeIcon icon={faCircleNotch} spin={true} />
			</MotionLoader>
		)

	return (
		<ProfilePage onClick={() => setPeriodToggle(false)} ref={profilePage}>
			<ChartContainer key={period} style={chartContainerStyles}>
				{periodLoading ? (
					<Loader>
						<FontAwesomeIcon icon={faCircleNotch} spin={true} />
					</Loader>
				) : (
					<Suspense fallback={<div>Loading...</div>}>
						<ReactApexChart options={options} series={series} type="area" height={400} />
					</Suspense>
				)}
			</ChartContainer>
			<MotionStatisticCard initial={{ opacity: 0, y: 100 }} animate={{ opacity: 1, y: 0 }}>
				<motion.div
					transition={{ delay: 0.3 }}
					initial={{ opacity: 0, y: 100 }}
					animate={{ opacity: 1, y: 0 }}
					className="card-image-holder">
					<motion.a
						variants={imageVariants}
						animate="animate"
						whileHover="hover"
						whileTap="tap"
						target="_blank"
						href={`https://twitter.com/${twitterUser.screen_name}`}>
						<img src={twitterUser.profile_image_url.replace('_normal', '')} alt={twitterUser.screen_name} />
					</motion.a>
				</motion.div>
				<motion.div
					transition={{ delay: 0.2 }}
					initial={{ opacity: 0, y: 100 }}
					animate={{ opacity: 1, y: 0 }}
					className="row">
					<UserList>
						<div className="title">
							<h2>{t('profile.recentActivities')}</h2>
							<Link to="/activities">{t('profile.showMore')}</Link>
						</div>
						{activities.map((item: ActivityEntry, index: number) => (
							<UserItem item={item} origin="profile" t={t} key={index} />
						))}
					</UserList>
					<Statistics>
						<h2 className="first">{t('profile.userAnalytics.title')}</h2>
						<div className="item-row">
							<div className="item">
								{thousandSeparator(statistics.followers_count)}
								<small>{t('profile.userAnalytics.followers')}</small>
							</div>
							<div className="item">
								{thousandSeparator(statistics.friends_count)}
								<small>{t('profile.userAnalytics.following')}</small>
							</div>
						</div>
						<div className="item-row">
							<div className="item">
								{thousandSeparator(statistics.statuses_count)}
								<small>{t('profile.userAnalytics.tweets')}</small>
							</div>
							<div className="item">
								{thousandSeparator(statistics.listed_count)}
								<small>{t('profile.userAnalytics.listedIn')}</small>
							</div>
							<div className="item">
								{thousandSeparator(statistics.favorites_count)}
								<small>{t('profile.userAnalytics.liked')}</small>
							</div>
						</div>
						<div className="item smaller">
							{accountAge}
							<small>{t('profile.userAnalytics.age')}</small>
						</div>
						<h2>{t('profile.followerAnalytics.title')}</h2>
						<div className="item-row">
							<div className="item">
								{thousandSeparator(analytics.verified_followers)}
								<small>{t('profile.followerAnalytics.verified')}</small>
							</div>
							<div className="item">
								{thousandSeparator(analytics.protected_followers)}
								<small>{t('profile.followerAnalytics.protected')}</small>
							</div>
						</div>
						<div className="item-row">
							<div className="item">
								{thousandSeparator(analytics.status_count)}
								<small>{t('profile.followerAnalytics.tweets')}</small>
							</div>
							<div className="item">
								{thousandSeparator(analytics.favorite_count)}
								<small>{t('profile.followerAnalytics.likes')}</small>
							</div>
						</div>
						{analytics.most_statuses && (
							<Link to={`/user/${analytics.most_statuses.id}`} className="item-container">
								<motion.div variants={itemVariants} whileHover="hover" whileTap="tap" className="item">
									<div className="profile">
										<div
											style={{
												backgroundImage: `url(${analytics.most_statuses.profile_image_url.replace(
													'_bigger',
													''
												)})`,
											}}
										/>
										@{analytics.most_statuses.screen_name}
									</div>
									<small>{t('profile.followerAnalytics.mostTweets')}</small>
								</motion.div>
							</Link>
						)}
						{analytics.most_followers && (
							<Link to={`/user/${analytics.most_followers.id}`} className="item-container">
								<motion.div variants={itemVariants} whileHover="hover" whileTap="tap" className="item">
									<div className="profile">
										<div
											style={{
												backgroundImage: `url(${analytics.most_followers.profile_image_url.replace(
													'_bigger',
													''
												)})`,
											}}
										/>
										@{analytics.most_followers.screen_name}
									</div>
									<small>{t('profile.followerAnalytics.mostFollowers')}</small>
								</motion.div>
							</Link>
						)}
						{analytics.oldest_account && (
							<Link to={`/user/${analytics.oldest_account.id}`} className="item-container">
								<motion.div variants={itemVariants} whileHover="hover" whileTap="tap" className="item">
									<div className="profile">
										<div
											style={{
												backgroundImage: `url(${analytics.oldest_account.profile_image_url.replace(
													'_bigger',
													''
												)})`,
											}}
										/>
										@{analytics.oldest_account.screen_name}
									</div>
									<small>{t('profile.followerAnalytics.oldestAccount')}</small>
								</motion.div>
							</Link>
						)}
					</Statistics>
				</motion.div>
				<GraphSettings onClick={(e) => e.stopPropagation()}>
					<div className="inner">
						<div className="background">
							<motion.div
								variants={itemVariants}
								whileHover="hover"
								whileTap="tap"
								className="base"
								onClick={() => setPeriodToggle((current) => !current)}>
								<span>{t(`period.${period}`)}</span>
								<FontAwesomeIcon icon={faChevronDown} />
							</motion.div>
							{canUpdate != null && (
								<motion.div
									variants={itemVariants}
									whileHover="hover"
									whileTap="tap"
									onClick={manualUpdate}
									className={`base${canUpdate ? '' : ' disabled'}`}>
									<span>Manual Update</span>
									<FontAwesomeIcon icon={faSync} spin={manualUpdating} />
								</motion.div>
							)}
						</div>
						{periodToggle && (
							<div className="selection">
								<div
									onClick={() => {
										setPeriod('month')
										setPeriodToggle(false)
									}}
									className={`item${period === 'month' ? ' active' : ''}`}>
									<b>{t('period.month')}</b>
									<span>{t('period.monthInfo')}</span>
								</div>
								<div
									onClick={() => {
										setPeriod('day')
										setPeriodToggle(false)
									}}
									className={`item${period === 'day' ? ' active' : ''}`}>
									<b>{t('period.day')}</b>
									<span>{t('period.dayInfo')}</span>
								</div>
								<div
									onClick={() => {
										setPeriod('hour')
										setPeriodToggle(false)
									}}
									className={`item${period === 'hour' ? ' active' : ''}`}>
									<b>{t('period.hour')}</b>
									<span>{t('period.hourInfo')}</span>
								</div>
							</div>
						)}
					</div>
				</GraphSettings>
                <span className="last-update">{ t('profile.lastUpdate') } {dateFormat(new Date(automatedUpdate.last_update * 1000), t('dateTimeFormat'))}</span>
			</MotionStatisticCard>
		</ProfilePage>
	)
}
