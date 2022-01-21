import { motion } from 'framer-motion'
import styled from 'styled-components'
import { SettingsPage } from './setttingsStyles'

export const SetupPage = styled(SettingsPage)`
	.welcome-text {
		margin-bottom: 60px;
	}
`

export const SubmitButton = styled.div`
	background: ${(props) => props.theme.settings.itemSelected};
	padding: 5px 35px;
	min-height: 60px;
	display: inline-flex;
	align-items: center;
	justify-content: center;
	text-align: center;
	border-radius: 6px;
	cursor: pointer;
	color: white;
	font-size: 18px;
	font-weight: 500;
	margin-top: 40px;
`

export const SubmitOverlay = styled.div`
	position: fixed;
	top: 0;
	left: 0;
	width: 100%;
	height: 100%;
	background: rgba(0, 0, 0, 0.5);
	z-index: 1000;

	display: flex;
	align-items: center;

	.inner {
		display: grid;
		place-items: center;
		min-height: 100vh;
		width: 100%;
		padding: 15px;

		.box {
			background: ${(props) => props.theme.settings.item};
			width: 100%;
			max-width: 400px;
			border-radius: 10px;
			padding: 50px 30px 30px;
			color: ${(props) => props.theme.textPrimary};
			display: flex;
			flex-direction: column;
			align-items: center;

			svg {
				font-size: 40px;
			}

			.message {
				margin-top: 40px;
				text-align: center;
			}
		}
	}
`

export const MotionSubmitButton = motion(SubmitButton)

export const MotionSetupPage = motion(SetupPage)
