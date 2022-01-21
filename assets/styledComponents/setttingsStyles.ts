import { motion } from 'framer-motion'
import styled from 'styled-components'

export const OptionRow = styled.div`
	display: grid;
	grid-template-columns: repeat(auto-fit, minmax(150px, 200px));
	grid-gap: 10px;
	width: 100%;

	.item {
		padding: 5px;
		min-height: 60px;
		display: flex;
		align-items: center;
		justify-content: center;
		text-align: center;
		background: ${(props) => props.theme.settings.item};
		border-radius: 6px;
		cursor: pointer;
		border: solid 3px transparent;
		transition: background 0.2s ease, border-color 0.2s ease;

		&:hover {
			background: ${(props) => props.theme.settings.itemHover};
		}

		&.selected {
			border-color: ${(props) => props.theme.settings.itemSelected};
		}
	}

	&[disabled] {
		opacity: 0.5;
		pointer-events: none;
	}

	@media (max-width: 500px) {
		& {
			display: flex;
			flex-direction: column;
		}
	}
`

export const APIKeyContainer = styled.div`
	padding: 20px;
	background: ${(props) => props.theme.settings.tableOddRow};
	border-radius: 10px;
	margin-top: 20px;
	margin-bottom: 45px;
	display: flex;
	align-items: center;

	.container {
		flex: 1;

		span {
			color: ${(props) => props.theme.textPrimary};
			font-family: 'Courier New', Courier, monospace;
			display: flex;
			align-items: center;
		}

		small {
			color: ${(props) => props.theme.textSecondary};
			margin-top: 15px;
		}
	}

	.clipboard {
		background: ${(props) => props.theme.background};
		width: 40px;
		height: 40px;
		border-radius: 6px;
		display: grid;
		place-items: center;
		margin-left: 10px;
		cursor: pointer;
		color: ${(props) => props.theme.textPrimary};
		transition: background 0.2s ease, color 0.2s ease;

		span {
			display: none;
		}

		&.success {
			background: #16a34a;
			color: #fff;
		}

		&.error {
			background: #dc2626;
			color: #fff;
		}
	}

	@media (max-width: 500px) {
		& {
			flex-direction: column;

			.clipboard {
				margin-top: 20px;
				margin-left: 0;
				width: auto;
				padding: 0 15px;
				display: flex;
				align-items: center;

				span {
					display: block;
					margin-left: 10px;
				}
			}
		}
	}
`

export const SettingsPage = styled.div`
	margin-top: 58px;
	padding: 40px 40px 30px;

	h1 {
		font-weight: 700;
		color: ${(props) => props.theme.textPrimary};
		margin: 0 0 10px;
		font-size: 22px;
	}

	h2 {
		font-weight: 600;
		color: ${(props) => props.theme.textSecondary};
		margin: 25px 0 5px;
		font-size: 20px;
	}

	${OptionRow} {
		margin: 10px 0 45px;
	}

	table {
		margin-top: 20px;
		width: 100%;
		border-collapse: collapse;

		tr::after {
			content: '';
			display: inline-block;
			vertical-align: top;
			min-height: 70px;
		}

		tr:nth-child(odd) td {
			background: ${(props) => props.theme.settings.tableOddRow};

			&:first-child {
				border-radius: 10px 0 0 10px;
			}

			&:last-child {
				border-radius: 0 10px 10px 0;
			}
		}

		td {
			padding: 10px 20px;

			&:not(:first-child) {
				text-align: center;
			}

			a {
				color: ${(props) => props.theme.textPrimary};
				text-decoration: none;
			}

			&:first-child {
				font-weight: 600;
				color: ${(props) => props.theme.textPrimary};

				small {
					font-weight: 400;
					color: ${(props) => props.theme.textSecondary};
				}
			}
		}
	}

	@media (max-width: 500px) {
		& {
			padding: 40px 20px 30px;
		}

		table {
			display: block;

			tbody {
				display: block;
			}

			tr {
				display: flex;
				flex-wrap: wrap;
				padding: 10px 0;

				td {
					border-radius: 0 !important;
					height: 40px;
					background: none !important;
					padding: 10px 0;

					a {
						margin-right: 10px;
						background: ${(props) => props.theme.settings.item};
						padding: 10px 15px;
						border-radius: 5px;
						transition: background 0.2s ease;

						&:hover {
							background: ${(props) => props.theme.settings.itemHover};
						}
					}
				}

				td:first-child {
					min-width: 100%;
					height: auto;
					margin-bottom: 10px;
				}

				&:after {
					display: none;
				}
			}
		}
	}
`

export const MotionSettingsPage = motion(SettingsPage)
