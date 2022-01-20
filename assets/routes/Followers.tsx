import React, { useEffect, useRef, useState, useCallback } from 'react'
import { Link, useParams } from 'react-router-dom'
import {
	SpinnerButton,
	MotionLoader,
	MotionReturn,
	UserList,
} from '../styledComponents/globalStyles'
import { useApp } from '../provider/AppProvider'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faChevronLeft, faCircleNotch } from '@fortawesome/pro-light-svg-icons'
import { useTranslation } from 'react-i18next'
import { ActivityEntry, ActivityResponse, TwitterUser, UsersResponse } from '../types/global'
import { formatAction, formatDate, getActionIcon } from '../utilities/utilities'

import { motion } from 'framer-motion'
import { ActivitiesPage, MotionActivitiesCard } from '../styledComponents/activityStyles'
import React_2 from 'framer-motion/dist/framer-motion'
import UserItem from '../components/userItem'

export default function Followers() {

	const { data } = useApp()

	const { t } = useTranslation()

	const card = useRef()

	const { type } = useParams()

	const [loading, setLoading] = useState(true)
	const [users, setUsers] = useState<TwitterUser[]>(null)
	const [limit, setLimit] = useState(20)
	const [page, setPage] = useState(0)
	const [moreAvailable, setMoreAvailable] = useState(false)

	useEffect(() => {
        document.title = `Sociant Hub - ${ t('pageTitles.activities') }`;

		setLoading(true)
		loadData().then(() => {
			setLoading(false)
		})
	}, [])

	const loadData = async (_page = page) => {
		const response = await fetch(`/api/users/${type}?limit=${limit}&page=${_page}`, {
			headers: {
				'Authorization': `Bearer ${data.apiToken}`,
			},
		})

		const responseData: UsersResponse = await response.json()

		if(users == null)
            setUsers(responseData.items)
		else
            setUsers([...users, ...responseData.items])

		setLimit(responseData.limit)
		setPage(responseData.page)
		setMoreAvailable(responseData.more_available)
	}

	const getTitle = () => {
		if(type === 'verified')
			return 'profile.followersVerified';
		return 'profile.followersProtected';
	}

	const loadMore = async () => {
		if(loading) return;

		setLoading(true)
		await loadData(page + 1)
		setLoading(false)
	}

	const itemVariants = {
		hover: { scale: 1.05 },
		tap: { scale: 0.95 },
	}

	if (loading && users == null) return <MotionLoader
		initial={{ opacity: 0, y: -50 }}
		animate={{ opacity: 1, y: 0 }}>
		<FontAwesomeIcon icon={faCircleNotch} spin={true} />
	</MotionLoader>

	return (
		<ActivitiesPage>
			<MotionReturn
				className="return"
				variants={itemVariants}
				whileHover="hover"
				whileTap="tap">
				<Link to="/profile">
					<FontAwesomeIcon icon={faChevronLeft} />
					{t('return.profile')}
				</Link>
			</MotionReturn>
			<MotionActivitiesCard
				ref={card}
				initial={{ opacity: 0, y: 100 }}
				animate={{ opacity: 1, y: 0 }}>
				<UserList>
					<div className="title">
						<h2>{t(getTitle())}</h2>
					</div>
					{users.map((item: TwitterUser, index: number) =>
						<UserItem item={item} origin='activities' t={t} key={index} />
					)}
					{
						moreAvailable &&
							<SpinnerButton>
								<motion.div
									variants={itemVariants}
									whileHover="hover"
									whileTap="tap"
									onClick={loadMore}>
									{ loading && <FontAwesomeIcon icon={ faCircleNotch } spin={true} /> }
									{t('loadMore')}
								</motion.div>
							</SpinnerButton>
					}
				</UserList>
			</MotionActivitiesCard>
		</ActivitiesPage>
	)
}