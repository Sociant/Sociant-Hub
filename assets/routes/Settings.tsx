import React, { useEffect, useRef, useState } from 'react'
import {
	MotionLoader
} from '../styledComponents/globalStyles'
import { useApp } from '../provider/AppProvider'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faCircleNotch } from '@fortawesome/pro-light-svg-icons'
import { useTranslation } from 'react-i18next'

export default function Settings() {

	const { data } = useApp()


	const { t } = useTranslation()

	const card = useRef()

	const [loading, setLoading] = useState(true)

	useEffect(() => {
		setLoading(true)
		loadData().then(() => {
			setLoading(false)
		})
	}, [])

	const loadData = async () => {
	}

	const itemVariants = {
		hover: { scale: 1.05 },
		tap: { scale: 0.95 },
	}

	if (loading) return <MotionLoader
		initial={{ opacity: 0, y: -50 }}
		animate={{ opacity: 1, y: 0 }}>
		<FontAwesomeIcon icon={faCircleNotch} spin={true} />
	</MotionLoader>

	return (
        <div>
            <h1>Settings</h1>
            
        </div>
	)
}