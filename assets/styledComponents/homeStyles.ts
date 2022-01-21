import styled from 'styled-components'
import { Container } from './globalStyles'
import TwitterButtonStyles from './twitterButtonStyles'

export const HomePage = styled.div`
	padding-bottom: 30px;

	${Container} .row {
		display: flex;
		padding: 5px 0;

		&.reversed {
			flex-direction: row-reverse;
		}

		.image {
			flex: 1.3;
			margin: 20px;

			img {
				width: 100%;
				max-width: 700px;
				box-shadow: 0 1px 3px 0 rgb(63 63 68 / 25%);
				border-radius: 6px;
			}

			&.smaller {
				flex: 0.7;
			}
		}

		.text {
			flex: 1;
			margin: 20px;
			padding: 10px 30px;
			display: flex;
			flex-direction: column;
			align-items: flex-start;

			h1 {
				color: ${(props) => props.theme.textPrimary};
				font-weight: 500;
				font-size: 21px;
			}

			div {
				color: ${(props) => props.theme.textSecondary};
				font-size: 18px;
				max-width: 500px;
			}
		}
	}

	@media (max-width: 750px) {
		${Container} .row {
			flex-direction: column-reverse !important;
			align-items: center;

			.image img {
				max-width: 500px;
			}
		}
	}
`

export const Header = styled.div`
	background: linear-gradient(to right, #ad5389, #3c1053);
	padding-top: 78px;
	padding-bottom: 20px;

	${Container} {
		display: flex;
		align-items: center;
		padding: 0 15px;

		.description {
			flex: 1.5;
			color: white;
			margin: 20px;
			display: flex;
			flex-direction: column;
			align-items: flex-start;

			${TwitterButtonStyles} {
				padding: 10px 16px;
				font-size: 16px;
				margin-top: 30px;
			}

			h1 {
				font-size: 30px;
				font-weight: 400;

				b {
					font-weight: 600;
				}
			}

			div {
				max-width: 450px;
				font-size: 18px;
			}
		}

		.image {
			flex: 1;
			margin: 20px;

			img {
				width: 100%;
				max-width: 400px;
			}
		}
	}

	@media (max-width: 900px) {
		${Container} {
			flex-direction: column;

			.description {
				text-align: center;
				align-items: center;
			}

			.image {
				img {
					max-width: 300px;
				}
			}
		}
	}
`
