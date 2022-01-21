import { motion } from 'framer-motion'
import styled from 'styled-components'
import { Container, UserList } from './globalStyles'

export const ActivitiesCard = styled(Container)`
	background: ${(props) => props.theme.card.background};
	border-radius: 6px;
	width: 100%;
	transition: background 0.2s ease;
`

export const ActivitiesPage = styled.div`
	margin-top: 58px;
	padding-top: 40px;
	padding-bottom: 30px;
	position: relative;
	display: flex;
	flex-direction: column;
	align-items: center;

	${UserList} {
		padding: 35px;
	}
`

export const MotionActivitiesCard = motion(ActivitiesCard)
