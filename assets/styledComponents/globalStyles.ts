import { motion } from 'framer-motion'
import { Link } from 'react-router-dom'
import styled, { createGlobalStyle } from 'styled-components'

const GlobalStyle = createGlobalStyle`
  body {
    margin: 0;
    padding: 0;
    font-family: 'Inter', sans-serif;
    background: ${(props) => props.theme.background};
    
    transition: background .2s ease;
  }
  
  *, *:after, *:before {
    box-sizing: border-box;
	-webkit-tap-highlight-color:  rgba(255, 255, 255, 0); 
  }
`

export default GlobalStyle

export const Container = styled.div`
	max-width: 1200px;
	margin: 0 auto;
	color: ${(props) => props.theme.textPrimary};
`

export const Loader = styled.div`
	min-height: 400px;
	display: flex;
	align-items: center;
	justify-content: center;
	font-size: 26px;
	color: ${(props) => props.theme.textPrimary};
`

export const UserList = styled.div`
	.title {
		margin: 0 0 40px;
		display: flex;
		align-items: center;

		h2 {
			margin: 0 10px 0 0;
			font-weight: 600;
			font-size: 22px;
			position: relative;
			padding-left: 10px;
			flex: 1;

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

		a {
			color: ${(props) => props.theme.textSecondary};
			text-decoration: none;
			font-size: 14px;
			transition: color 0.2s ease;

			&:hover {
				color: ${(props) => props.theme.textPrimary};
			}
		}
	}

	.item-holder {
		text-decoration: none;
	}

	.item {
		display: flex;
		align-items: center;
		font-size: 13px;
		padding: 10px;
		border-radius: 6px;
		text-decoration: none;
		transition: background 0.2s ease;

		&:hover {
			background: ${(props) => props.theme.card.profileHover};
		}

		img {
			width: 40px;
			height: 40px;
			border-radius: 20px;
			margin-right: 15px;
			object-fit: cover;
			object-position: center;
		}

		.name {
			display: flex;
			flex-direction: column;
			align-items: flex-start;
			flex: 1;
			color: ${(props) => props.theme.textSecondary};
			transition: color 0.2s ease;

			b {
				color: ${(props) => props.theme.textPrimary};
				font-size: 14px;
				font-weight: 600;
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
		}

		.date-action {
			display: flex;
			flex-direction: column;
			align-items: flex-end;
			color: ${(props) => props.theme.textPrimary};
			transition: color 0.2s ease;
			font-size: 14px;

			small {
				color: ${(props) => props.theme.textSecondary};
				font-size: 13px;
				display: flex;
				align-items: center;

				svg {
					margin-left: 5px;
					transform: rotate(45deg);
				}
			}
		}
	}

	@media (max-width: 520px) {
		.item {
			display: flex;
			flex-wrap: wrap;

			.name {
				flex: 1;
			}

			.date-action {
				min-width: 100%;
				align-items: flex-start;
				padding-left: 55px;
				margin-top: 10px;
			}
		}
	}
`

export const Return = styled.div`
	margin-bottom: 25px;
	font-size: 15px;
	display: inline-flex;
	align-items: center;
	cursor: pointer;

	> * {
		text-decoration: none;
		color: ${(props) => props.theme.textSecondary};
		transition: color 0.2s ease;

		&:hover {
			color: ${(props) => props.theme.textPrimary};
		}
	}

	svg {
		margin-right: 10px;
		font-size: 13px;
	}
`

export const SpinnerButton = styled.div`
	display: flex;
	align-items: center;
	justify-content: center;
	padding: 15px 10px;

	> * {
		background: ${(props) => props.theme.card.button};
		padding: 8px 20px;
		color: ${(props) => props.theme.textPrimary};
		font-size: 16px;
		display: flex;
		align-items: center;
		border-radius: 6px;
		cursor: pointer;
		text-decoration: none;

		transition: background 0.2s ease;

		svg {
			margin-right: 5px;
		}

		&:hover {
			background: ${(props) => props.theme.card.buttonHover};
		}
	}
`

export const Error404 = styled.div`
	min-height: 400px;
	display: flex;
	align-items: center;
	justify-content: center;
	flex-direction: column;
	margin-top: 56px;
	color: ${(props) => props.theme.textSecondary};

	h2 {
		font-size: 70px;
		font-weight: 600;
		margin-bottom: 20px;
		color: ${(props) => props.theme.textPrimary};
	}

	span {
		font-size: 16px;
	}

	${SpinnerButton} {
		margin-top: 30px;
	}
`

export const MotionLoader = motion(Loader)
export const MotionReturn = motion(Return)
export const MotionError404 = motion(Error404)
export const MotionLink = motion(Link)
