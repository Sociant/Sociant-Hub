import { Container } from './globalStyles'
import styled from 'styled-components'
import TwitterButtonStyles from './twitterButtonStyles'
import { motion } from 'framer-motion'

export const OptionRow = styled.div`
	display: flex;
	flex-wrap: wrap;
	margin: -10px;
	height: 80px;
	width: 100%;

	.item {
		margin: 10px;
		height: 100%;
		width: 150px;
		display: flex;
		align-items: center;
		justify-content: center;
		text-align: center;
		background: ${ props => props.theme.settings.item };
		border-radius: 6px;
		cursor: pointer;
		border: solid 3px transparent;
		transition: background .2s ease, border-color .2s ease;

		&:hover {
			background: ${ props => props.theme.settings.itemHover };
		}

		&.selected {
			border-color: ${ props => props.theme.settings.itemSelected };
		}
	}
`

export const SettingsPage = styled.div`
	margin-top: 58px;
	padding-top: 40px;
	padding-bottom: 30px;

	h1 {
		font-weight: 700;
		color: ${ props => props.theme.textPrimary };
		margin: 0 0 10px;
		font-size: 22px;
	}

	h2 {
		font-weight: 600;
		color: ${ props => props.theme.textSEcondary };
		margin: 25px 0 5px;
		font-size: 20px;
	}

	${ OptionRow } {
		margin: 10px -10px 25px;
	}
`

export const MotionSettingsPage = motion(SettingsPage)