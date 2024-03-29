import { motion } from 'framer-motion'
import styled from 'styled-components'
import { Container, UserList } from './globalStyles'

export const ChartContainer = styled(Container)`
	max-width: 1400px;
	margin-top: 20px;

	.not-enough-data {
		height: 100%;
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		color: ${(props) => props.theme.textSecondary};

		svg {
			font-size: 50px;
		}

		b {
			font-size: 20px;
			margin: 20px 0 10px;
		}

		span {
			font-size: 15px;
		}
	}
`

export const GraphSettings = styled.form`
	height: 50px;
	position: sticky;
	bottom: 15px;
	width: 100%;
	pointer-events: none;

	.inner {
		position: relative;
		height: 100%;
		width: 100%;
		display: flex;
		flex-direction: column;
		align-items: center;

		.background {
			height: 100%;
			background: ${(props) => props.theme.settingsBar.background};
			color: ${(props) => props.theme.textSecondary};
			backdrop-filter: blur(10px);
			border-radius: 25px;
			display: flex;
			align-items: center;
			padding: 0 15px;

			.base {
				height: 100%;
				display: flex;
				align-items: center;
				cursor: pointer;
				pointer-events: auto;
				padding: 0 10px;
				user-select: none;

				&.disabled {
					opacity: 0.5;
					pointer-events: none;
				}

				span {
					color: ${(props) => props.theme.textPrimary};
					margin-right: 10px;
				}
			}
		}

		.selection {
			position: absolute;
			bottom: 100%;
			margin-bottom: 10px;
			background: ${(props) => props.theme.settingsBar.background};
			backdrop-filter: blur(10px);
			max-width: 90vw;
			border-radius: 15px;
			padding: 8px;
			pointer-events: auto;

			.item {
				color: ${(props) => props.theme.textSecondary};
				transition: color 0.2s ease;
				padding: 12px;
				cursor: pointer;

				&:hover,
				&.active {
					color: ${(props) => props.theme.textPrimary};
				}

				b {
					display: block;
				}
			}
		}
	}
`

export const StatisticCard = styled(Container)`
	background: ${(props) => props.theme.card.background};
	backdrop-filter: blur(10px);
	margin-top: 90px;
	border-radius: 6px;
	transition: background 0.2s ease;
	display: flex;
	flex-direction: column;
	align-items: center;

	.card-image-holder {
		display: flex;
		justify-content: center;
		position: relative;
		height: 112px;

		a {
			position: absolute;
			width: 180px;
			height: 180px;
			transform: translateY(-80px);
		}

		img {
			width: 100%;
			height: 100%;
			border-radius: 100px;
			object-fit: cover;
			object-position: center;
			border: solid 6px ${(props) => props.theme.card.background};
			transition: border-color 0.2s ease;
		}
	}

	${GraphSettings} {
		margin-top: 20px;
		margin-bottom: 15px;
	}
`

export const Statistics = styled.div`
	display: grid;
	grid-template-columns: 1fr;
	grid-gap: 15px;
	text-align: left;
	color: ${(props) => props.theme.textPrimary};

	h2 {
		font-weight: 600;
		font-size: 22px;
		margin: 40px 0 20px;
		position: relative;
		padding-left: 10px;

		&.first {
			margin-top: 0;
		}

		:after {
			content: '';
			display: block;
			position: absolute;
			background: ${(props) => props.theme.textSecondary};
			height: 100%;
			width: 3px;
			top: 0;
			left: 0;
		}
	}

	.item-row {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
		width: 100%;
		grid-gap: 15px;
	}

	.item-container {
		width: 100%;
		text-decoration: none;
	}

	.item {
		font-size: 30px;
		font-weight: 600;
		width: 100%;
		padding: 15px 25px;
		border-radius: 6px;
		color: ${(props) => props.theme.textPrimary};
		text-decoration: none;
		background: ${(props) => props.theme.card.itemBackground};

		&.smaller {
			font-size: 22px;
		}

		small {
			display: block;
			margin-top: -2px;
			font-size: 16px;
			font-weight: 500;
			color: ${(props) => props.theme.textSecondary};
		}

		&.smaller small {
			margin-top: 0;
		}

		.profile {
			display: flex;
			align-items: center;
			justify-content: flex-start;
			margin-bottom: 4px;

			div {
				height: 36px;
				width: 36px;
				border-radius: 18px;
				background-size: cover;
				background-position: center;
				margin-right: 10px;
			}
		}

		.profile + small {
			margin-top: 0;
			padding-left: 46px;
		}
	}
`

export const ProfilePage = styled.div`
	margin-top: 58px;
	padding-top: 435px;
	padding-bottom: 30px;
	position: relative;
	display: flex;
	flex-direction: column;
	align-items: center;

	${ChartContainer} {
		position: fixed;
		top: 78px;
		width: 100%;
		height: 415px;
		margin-top: 0;
	}

	${StatisticCard} {
		width: 100%;
		position: relative;
	}

	.row {
		width: 100%;
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
		padding: 35px;
		grid-gap: 50px;

		${UserList} {
			flex: 1;
		}

		${Statistics} {
			flex: 1;
		}
	}

	.last-update {
		margin-top: 20px;
		margin-bottom: 40px;
		font-size: 14px;
		color: ${(props) => props.theme.textSecondary};
	}

	@media (max-width: 700px) {
		.row {
			display: flex;
			flex-direction: column;
		}
	}
`

export const MotionStatisticCard = motion(StatisticCard)
