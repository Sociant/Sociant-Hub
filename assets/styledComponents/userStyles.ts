import { Container } from './globalStyles'
import styled from 'styled-components'
import TwitterButtonStyles from './twitterButtonStyles'
import { motion } from 'framer-motion'

export const UserCard = styled(Container)`
	background: ${ props => props.theme.card.background };
	border-radius: 6px;
	transition: background .2s ease;
`

export const UserData = styled.div`
	display: grid;
	grid-template-columns: 1fr;
	grid-gap: 15px;
	text-align: left;
	color: ${ props => props.theme.textPrimary }; 
	
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
			background: ${ props => props.theme.textSecondary };
			height: 100%;
			width: 3px;
			top: 0;
			left: 0;
		}
	}
	
	.profile-item {
		display: flex;
		align-items: flex-start;
		
		img {
			width: 100px;
			height: 100px;
			border-radius: 50px;
			object-fit: cover;
			object-position: center;
			margin-right: 25px;
		}
		
		div {
			display: flex;
			flex-direction: column;
			align-items: flex-start;
			color: ${ props => props.theme.textSecondary };
			
			b {
				color: ${ props => props.theme.textPrimary };
				display: flex;
				align-items: center;
				
				svg {
					margin-left: 5px;
				}
			}
			
			span {
				display: flex;
				align-items: center;
				
				svg {
					margin-left: 5px;
				}
			}
			
			.description {
				margin-top: 10px;
			}
			
			${ TwitterButtonStyles } {
				margin-top: 25px;
			}
		}
	}
	
	.item-row {
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(170px, 1fr));
		width: 100%;
		grid-gap: 15px;
		
		.item {
			flex: 1;
		}
	}
	
	.item {
		font-size: 30px;
		font-weight: 600;
		width: 100%;
		padding: 15px 25px;
		border-radius: 6px;
		background: ${ props => props.theme.card.itemBackground };
		
		&.smaller {
			font-size: 22px;
		}
		
		small {
			display: block;
			margin-top: -2px;
			font-size: 16px;
			font-weight: 500;
			color: ${ props => props.theme.textSecondary }; 
		}
		
		&.smaller small {
			margin-top: 0;
		}
		
		.profile {
			display: flex;
			align-items: center;
			justify-content: flex-end;
			margin-bottom: 4px;
			
			div {
				height: 36px;
				width: 36px;
				border-radius: 18px;
				background-size: cover;
				background-position: center;
				margin-left: 10px;
			}
		}
		
		.profile + small {
			margin-top: 0;
		}
	}

	.relation-item {
		display: flex;
		align-items: center;
		margin-bottom: 15px;

		svg {
			font-size: 16px;
			width: 30px;
			text-align: center;
		}

		&.self svg {
			color: #FF8C00;
		}

		&.other svg {
			color: #00B294;
		}

		span {
			flex: 1;
			padding-left: 5px;
			color: ${ props => props.theme.textPrimary };
			font-size: 14px;
		}
	}

	@media (max-width: 500px) {
		.profile-item {
			flex-direction: column;
			align-items: center;

			img {
				margin-bottom: 15px;
			}
		}
	}
`

export const Timeline = styled.div`
	h2 {
		font-weight: 600;
		font-size: 22px;
		margin: 0 0 40px;
		position: relative;
		padding-left: 10px;
		
		:after {
			content: '';
			display: block;
			position: absolute;
			background: ${ props => props.theme.textSecondary };
			height: 100%;
			width: 3px;
			top: 0;
			left: 0;
		}
	}
	
	.item {
		display: flex;
		align-items: center;
		margin-bottom: 30px;
		
		svg {
			transform: rotate(45deg);
			margin-right: 25px;
			font-size: 25px;
		}
		
		div {
			color: ${ props => props.theme.textSecondary };
			font-size: 13px;
			
			span {
				color: ${ props => props.theme.textPrimary };
				display: block;
				margin-bottom: 4px;
				font-size: 16px;
			}
		}
	}
	
	.empty {
		display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
		min-height: 200px;
		color: ${ props => props.theme.textSecondary };
		
		svg {
			font-size: 40px;
		}
		
		span {
			margin-top: 20px;
		}
	}
`

export const UserPage = styled.div`
	margin-top: 58px;
	padding-top: 40px;
	padding-bottom: 30px;
	position: relative;
	display: flex;
	flex-direction: column;
	align-items: center;
	
	${ UserCard } {
		width: 100%;
		position: relative;
	}
	
	.row {
		padding: 35px;
		display: grid;
		grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
		grid-gap: 50px;
		
		${ UserData } {
			flex: 1;
		}
		
		${ Timeline } {
			flex: 1;
		}
	}

	@media (max-width: 700px) {
		.row {
			display: flex;
			flex-direction: column;
		}
	}
`

export const MotionUserCard = motion(UserCard)