import { faChevronLeft, faCircleNotch } from '@fortawesome/pro-light-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { motion } from 'framer-motion'
import React, { useEffect, useRef, useState } from 'react'
import { useTranslation } from 'react-i18next'
import { Link } from 'react-router-dom'
import UserItem from '../components/activityUserItem'
import { useApp } from '../provider/AppProvider'
import { ActivitiesPage, MotionActivitiesCard } from '../styledComponents/activityStyles'
import { MotionLoader, MotionReturn, SpinnerButton, UserList } from '../styledComponents/globalStyles'
import { ActivityEntry, ActivityResponse } from '../types/global'

export default function Activities() {
	const { data } = useApp()

	const { t } = useTranslation()

	const card = useRef()

	const [loading, setLoading] = useState(true)
	const [activities, setActivities] = useState<ActivityEntry[]>(null)
	const [limit, setLimit] = useState(20)
	const [page, setPage] = useState(0)
	const [moreAvailable, setMoreAvailable] = useState(false)

	useEffect(() => {
		document.title = `Sociant Hub - ${t('pageTitles.activities')}`

		setLoading(true)
		loadData().then(() => {
			setLoading(false)
		})
	}, [])

	const loadData = async (_page = page) => {
		const response = await fetch(`/api/activities?limit=${limit}&page=${_page}`, {
			headers: {
				Authorization: `Bearer ${data.apiToken}`,
			},
		})

		const responseData: ActivityResponse = await response.json()

		if (activities == null) setActivities(responseData.items)
		else setActivities([...activities, ...responseData.items])

		setLimit(responseData.limit)
		setPage(responseData.page)
		setMoreAvailable(responseData.more_available)
	}

	const loadMore = async () => {
		if (loading) return

		setLoading(true)
		await loadData(page + 1)
		setLoading(false)
	}

	const itemVariants = {
		hover: { scale: 1.05 },
		tap: { scale: 0.95 },
	}

	if (loading && activities == null)
		return (
			<MotionLoader initial={{ opacity: 0, y: -50 }} animate={{ opacity: 1, y: 0 }}>
				<FontAwesomeIcon icon={faCircleNotch} spin={true} />
			</MotionLoader>
		)

	return (
		<ActivitiesPage>
			<MotionReturn className="return" variants={itemVariants} whileHover="hover" whileTap="tap">
				<Link to="/profile">
					<FontAwesomeIcon icon={faChevronLeft} />
					{t('return.profile')}
				</Link>
			</MotionReturn>
			<MotionActivitiesCard ref={card} initial={{ opacity: 0, y: 100 }} animate={{ opacity: 1, y: 0 }}>
				<UserList>
					<div className="title">
						<h2>{t('profile.recentActivities')}</h2>
					</div>
					{activities.map((item: ActivityEntry, index: number) => (
						<UserItem item={item} origin="activities" t={t} key={index} />
					))}
					{moreAvailable && (
						<SpinnerButton>
							<motion.div variants={itemVariants} whileHover="hover" whileTap="tap" onClick={loadMore}>
								{loading && <FontAwesomeIcon icon={faCircleNotch} spin={true} />}
								{t('loadMore')}
							</motion.div>
						</SpinnerButton>
					)}
				</UserList>
			</MotionActivitiesCard>
		</ActivitiesPage>
	)
}
