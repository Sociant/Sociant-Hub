import { faBadgeCheck, faLock } from '@fortawesome/pro-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { motion } from 'framer-motion'
import { TFunction } from 'i18next'
import React from 'react'
import { Link } from 'react-router-dom'
import { TwitterUser } from '../types/global'

export type UserItemProps = {
	item: TwitterUser
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
			<Link to={{ pathname: `/user/${item.id}`, state: { origin: origin } }} className="item">
				<img
					src={item.profile_image_url.replace('_bigger', '')}
					alt={item.screen_name}
					onError={(e) => {
						e.target.onerror = null
						e.target.src = '/assets/images/empty.gif'
					}}
				/>
				<div className="name">
					<b>
						{item.name}
						{item.verified && <FontAwesomeIcon icon={faBadgeCheck} />}
					</b>
					<span>
						@{item.screen_name}
						{item.protected && <FontAwesomeIcon icon={faLock} />}
					</span>
				</div>
			</Link>
		</motion.div>
	)
}
