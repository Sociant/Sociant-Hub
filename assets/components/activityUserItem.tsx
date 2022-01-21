import { faBadgeCheck, faLock } from '@fortawesome/pro-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { motion } from 'framer-motion'
import { TFunction } from 'i18next'
import React from 'react'
import { Link } from 'react-router-dom'
import { ActivityEntry } from '../types/global'
import { formatAction, formatDate, getActionIcon } from '../utilities/utilities'

export type UserItemProps = {
	item: ActivityEntry
	origin: string
	t: TFunction
	key: any
}

const itemVariants = {
	hover: { scale: 1.05 },
	tap: { scale: 0.95 },
}

export default function UserItem({ item, origin, t }: UserItemProps) {
	return (
		<motion.div variants={itemVariants} whileHover="hover" whileTap="tap" className="item-holder">
			<Link to={{ pathname: `/user/${item.uuid}`, state: { origin: origin } }} className="item">
				<img
					src={item.twitter_user.profile_image_url.replace('_bigger', '')}
					alt={item.twitter_user.screen_name}
					onError={(e) => {
						e.target.onerror = null
						e.target.src = '/assets/images/empty.gif'
					}}
				/>
				<div className="name">
					<b>
						{item.twitter_user.name}
						{item.twitter_user.verified && <FontAwesomeIcon icon={faBadgeCheck} />}
					</b>
					<span>
						@{item.twitter_user.screen_name}
						{item.twitter_user.protected && <FontAwesomeIcon icon={faLock} />}
					</span>
				</div>
				<div className="date-action">
					{formatDate(item.timestamp * 1000, t)}
					<small>
						{formatAction(item.action, t)}
						<FontAwesomeIcon
							icon={getActionIcon(item.action)}
							color={
								item.action === 'follow_self' || item.action === 'unfollow_self' ? '#FF8C00' : '#00B294'
							}
						/>
					</small>
				</div>
			</Link>
		</motion.div>
	)
}
