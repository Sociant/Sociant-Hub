import React, { useState, useEffect, useRef, useMemo, Suspense } from 'react'
import { Link, useParams, useLocation } from 'react-router-dom'
import {
	Container,
	Loader,
	MotionError404,
	MotionLink,
	MotionLoader,
	MotionReturn,
	SpinnerButton,
} from '../styledComponents/globalStyles'
import { useApp } from '../provider/AppProvider'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import {
	faBoxOpen,
	faChevronLeft,
	faCircleNotch,
	faComment,
	faUserFriends,
	faUserSlash,
	faUserTimes,
} from '@fortawesome/pro-light-svg-icons'
import { useTranslation } from 'react-i18next'
import {
	ActivityEntry,
	ErrorResponse,
	HomeResponse,
	Relationship,
	RelationshipResponse,
	StatisticsResponse,
	TwitterUserExtended,
} from '../types/global'
import { MotionUserCard, Timeline, UserCard, UserData, UserPage } from '../styledComponents/userStyles'
import { MotionStatisticCard, StatisticCard, Statistics } from '../styledComponents/profileStyles'
import {
	formatActionExpanded,
	formatDate,
	getAccountAge,
	getActionIcon,
	getDifference,
	thousandSeparator,
} from '../utilities/utilities'
import TwitterButtonStyles from '../styledComponents/twitterButtonStyles'
import { faTwitter } from '@fortawesome/free-brands-svg-icons'

import { motion } from 'framer-motion'
import { faBadgeCheck, faLock } from '@fortawesome/pro-solid-svg-icons'

export default function User() {
	const { data } = useApp()

	const [loading, setLoading] = useState(true)

	const { t } = useTranslation()

	const { uuid } = useParams()

	const { state } = useLocation()

	const [twitterUser, setTwitterUser] = useState<TwitterUserExtended>(null)
	const [activities, setActivities] = useState<ActivityEntry[]>(null)
	const [relationship, setRelationship] = useState<Relationship>(null)

	useEffect(() => {
        document.title = `Sociant Hub - ${ t('pageTitles.userLoading') }`;

		setLoading(true)
		loadData().then(() => {
			setLoading(false)
		})
	}, [])

	const loadData = async () => {
		const response = await fetch(`/api/users/get/${uuid}?slim=false`, {
			headers: {
				Authorization: `Bearer ${data.apiToken}`,
			},
		})

		const responseData: TwitterUserExtended | ErrorResponse = await response.json()

		const relationResponse = await fetch(`/api/users/get/${uuid}/relation`, {
			headers: {
				Authorization: `Bearer ${data.apiToken}`,
			},
		})

		const relationResponseData: RelationshipResponse = await relationResponse.json()

		if (responseData.hasOwnProperty('error')) {
			return
		}

		const activityResponse = await fetch(`/api/user-activities/${uuid}`, {
			headers: {
				Authorization: `Bearer ${data.apiToken}`,
			},
		})

		const activityResponseData: { items: ActivityEntry[] } = await activityResponse.json()

		setTwitterUser(responseData)
		setActivities(activityResponseData.items)
		setRelationship(relationResponseData.relationship)

        document.title = `Sociant Hub - ${ t('pageTitles.user', { name: (responseData as TwitterUserExtended).screen_name }) }`;
	}

	const accountAge = useMemo(() => {
		if (!twitterUser) return null
		return getAccountAge(twitterUser.created_at, t)
	}, [twitterUser])

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

	if (twitterUser == null)
		return (
			<MotionError404 initial={{ opacity: 0, y: 100 }} animate={{ opacity: 1, y: 0 }}>
				<h2>404</h2>
				<span>Unknown user</span>
				<SpinnerButton>
					<MotionLink
						variants={itemVariants}
						whileHover="hover"
						whileTap="tap"
						to={`/${state?.origin || 'profile'}`}>
						{t(`return.${state?.origin || 'profile'}`)}
					</MotionLink>
				</SpinnerButton>
			</MotionError404>
		)

	return (
		<UserPage>
			<MotionReturn className="return" variants={itemVariants} whileHover="hover" whileTap="tap">
				<Link to={`/${state?.origin || 'profile'}`}>
					<FontAwesomeIcon icon={faChevronLeft} />
					{t(`return.${state?.origin || 'profile'}`)}
				</Link>
			</MotionReturn>
			<MotionUserCard initial={{ opacity: 0, y: 100 }} animate={{ opacity: 1, y: 0 }}>
				<motion.div
					transition={{ delay: 0.2 }}
					initial={{ opacity: 0, y: 100 }}
					animate={{ opacity: 1, y: 0 }}
					className="row">
					<Timeline>
						<h2>{t('user.followHistory')}</h2>
						{activities.map((item: ActivityEntry, index) => (
							<div className="item" key={`${index}-${item.uuid}`}>
								<FontAwesomeIcon
									icon={getActionIcon(item.action)}
									color={
										item.action === 'follow_self' || item.action === 'unfollow_self'
											? '#FF8C00'
											: '#00B294'
									}
								/>
								<div>
									<span>{formatActionExpanded(item.action, twitterUser, t)}</span>
									{formatDate(item.timestamp * 1000, t)}
								</div>
							</div>
						))}
						{activities.length === 0 && (
							<div className="empty">
								<FontAwesomeIcon icon={faBoxOpen} />
								<span>{t('user.noActivities')}</span>
							</div>
						)}
					</Timeline>
					<UserData>
						<h2 className="first">{t('user.userDetails')}</h2>
						<div className="profile-item">
							<img
								src={twitterUser.profile_image_url.replace('_normal', '')}
								alt={twitterUser.screen_name}
							/>
							<div>
								<b>
									{twitterUser.name}
									{twitterUser.verified && <FontAwesomeIcon icon={faBadgeCheck} />}
								</b>
								<span>
									@{twitterUser.screen_name}
									{twitterUser.protected && <FontAwesomeIcon icon={faLock} />}
								</span>
								<div className="description">{twitterUser.description}</div>
								<TwitterButtonStyles
									target="_blank"
									href={`https://twitter.com/${twitterUser.screen_name}`}>
									<FontAwesomeIcon icon={faTwitter} />
									{t('user.twitterButton')}
								</TwitterButtonStyles>
							</div>
						</div>
						<h2>{t('user.userStatistics')}</h2>
						<div className="item-row">
							<div className="item">
								{thousandSeparator(twitterUser.followers_count)}
								<small>{t('profile.userAnalytics.followers')}</small>
							</div>
							<div className="item">
								{thousandSeparator(twitterUser.friends_count)}
								<small>{t('profile.userAnalytics.following')}</small>
							</div>
						</div>
						<div className="item-row">
							<div className="item">
								{thousandSeparator(twitterUser.statuses_count)}
								<small>{t('profile.userAnalytics.tweets')}</small>
							</div>
							<div className="item">
								{thousandSeparator(twitterUser.listed_count)}
								<small>{t('profile.userAnalytics.listedIn')}</small>
							</div>
							<div className="item">
								{thousandSeparator(twitterUser.favorites_count)}
								<small>{t('profile.userAnalytics.liked')}</small>
							</div>
						</div>
						<div className="item smaller">
							{accountAge}
							<small>{t('profile.userAnalytics.age')}</small>
						</div>
						<h2>Current Relationship</h2>
						{relationship.source.followed_by && (
							<div className="relation-item other">
								<FontAwesomeIcon icon={faUserFriends} />
								<span>@{twitterUser.screen_name} is following you</span>
							</div>
						)}
						{relationship.source.following && (
							<div className="relation-item self">
								<FontAwesomeIcon icon={faUserFriends} />
								<span>You are following @{twitterUser.screen_name}</span>
							</div>
						)}
						{relationship.source.blocked_by && (
							<div className="relation-item other">
								<FontAwesomeIcon icon={faUserSlash} />
								<span>@{twitterUser.screen_name} blocked you</span>
							</div>
						)}
						{relationship.source.blocking && (
							<div className="relation-item self">
								<FontAwesomeIcon icon={faUserSlash} />
								<span>You blocked @{twitterUser.screen_name}</span>
							</div>
						)}
						{relationship.source.muting && (
							<div className="relation-item self">
								<FontAwesomeIcon icon={faUserTimes} />
								<span>You muted @{twitterUser.screen_name}</span>
							</div>
						)}
						{relationship.source.can_dm && (
							<div className="relation-item self">
								<FontAwesomeIcon icon={faComment} />
								<span>You can DM @{twitterUser.screen_name}</span>
							</div>
						)}
					</UserData>
				</motion.div>
			</MotionUserCard>
		</UserPage>
	)
}
