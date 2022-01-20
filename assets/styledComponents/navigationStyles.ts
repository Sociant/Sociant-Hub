import styled from 'styled-components'
import { convertToGradient } from '../types/titleColorBars'

const Navigation = styled.nav`
	position: ${ props => props.transparent ? 'absolute' : 'fixed' };
	top: 0;
	width: 100%;
	height: 58px;
	background: ${ props => props.transparent ? 'none' : props.theme.navigation.background };
  	box-shadow: ${ props => props.transparent ? 'none' : '0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24)' };
  	z-index: 100;
	
    transition: background .2s ease;
	
	.title {
		color: ${ props => props.transparent ? '#ffffff' : props.theme.navigation.title };
		font-weight: 500;
		
    	transition: color .2s ease;
	
		.inner {
			position: relative;
			padding: 0 6px ${ props => props.titleBarColors == null ? '0' : '10px' };
			
			div {
				display: ${ props => props.titleBarColors == null ? 'none' : 'block' };
				position: absolute;
				bottom: 0;
				left: 0;
				height: 8px;
				width: 100%;
				background: ${ props => convertToGradient(props.titleBarColors) };
				border-radius: 2px;
  				box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
			}
		}
	}
	
	.items {
		display: flex;
		align-items: center;
		
		.toggle {
			font-size: 20px;
			margin-left: 15px;
			cursor: pointer;
			user-select: none;
			color: ${ props => props.transparent ? '#ffffff' : props.theme.navigation.item };
		
    		transition: color .2s ease;
			
			&:hover {
				color: ${ props => props.transparent ? '#d1d1d1' : props.theme.navigation.itemHover };
			}
		}

		.menu {
			position: relative;

			.base {
				font-size: 15px;
				padding: 5px 10px;
				display: flex;
    			align-items: center;
				color: ${ props => props.transparent ? '#ffffff' : props.theme.navigation.item };
			
				transition: color .2s ease;
				
				&:hover {
					color: ${ props => props.transparent ? '#d1d1d1' : props.theme.navigation.itemHover };
				}
    			
    			span {
    				text-align: right;
    				font-size: 13px;
    				
    				b {
    					font-size: 15px;
    					font-weight: 600;
    				}
    			}
    			
    			div {
					height: 36px;
					width: 36px;
					border-radius: 18px;
					background-size: cover;
					background-position: center;
					margin-left: 10px;
    			}
			}

			.items {
				position: absolute;
				top: 100%;
				margin-top: 10px;

				width: 180px;
				background: ${ props => props.theme.navigation.background };
				border-radius: 6px;
				display: flex;
				flex-direction: column;
				opacity: 0;
				pointer-events: none;
				transition: opacity .2s ease;

				&.reveal {
					opacity: 1;
					pointer-events: auto;
				}

				.item {
					height: 50px;
					width: 100%;
					display: flex;
					align-items: center;

					svg {
						width: 40px;
					}
				}

				.divider {
					margin: 5px 0;
				}
			}
		}
		
		.item {
			font-size: 15px;
			padding: 5px 10px;
			text-decoration: none;
			color: ${ props => props.transparent ? '#ffffff' : props.theme.navigation.item };
		
    		transition: color .2s ease;
			
			&:hover {
				color: ${ props => props.transparent ? '#d1d1d1' : props.theme.navigation.itemHover };
			}
    		
    		&.profile {
    			display: flex;
    			align-items: center;
    			
    			span {
    				text-align: right;
    				font-size: 13px;
    				
    				b {
    					font-size: 15px;
    					font-weight: 600;
    				}
    			}
    			
    			div {
					height: 36px;
					width: 36px;
					border-radius: 18px;
					background-size: cover;
					background-position: center;
					margin-left: 10px;
    			}
    		}
		}
	}
	
	.container {
		max-width: 1260px;
		margin: 0 auto;
		display: flex;
		align-items: center;
		height: 100%;
		padding: 0 30px;
		border-bottom: ${ props => props.transparent ? 'solid 1px white' : 'none' };
		transition: border-bottom .2s ease;
		
		.spacer {
			flex: 1;
		}
	}
`

export const Footer = styled.footer`
    background: ${ props => props.theme.footer.background };
    padding: 40px 50px;
    margin-top: 30px;
  	box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);

    .container {
        max-width: 1200px;
        margin: 0 auto;
        display: grid;
		grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
		grid-gap: 20px;
        height: 100%;
        color: ${ props => props.theme.textSecondary };

        .column {
            display: flex;
            flex-direction: column;

            h3 {
                font-weight: 600;
                font-size: 18px;
                margin: 0 0 15px;
                position: relative;
                color: ${ props => props.theme.textPrimary };
                
                :before {
                    content: '';
                    display: block;
                    position: absolute;
                    background: ${ props => props.theme.textSecondary };
                    height: 100%;
                    width: 2px;
                    top: 0;
                    left: -12px;
                }
            }

            span, a {
                font-size: 15px;
                margin: 2px 0;
                text-decoration: none;
                color: inherit;
                transition: color .2s ease;

                &[href]:hover {
                    color: ${ props => props.theme.textPrimary };
                }

                svg {
                    margin: 0 4px;
                    color: #FF4343;
                }
            }
        }

		&.bottom {
			justify-content: center;
			margin-top: 40px;

			div {
				display: flex;
				flex-direction: row;
				align-items: center;
				margin: 0 15px;

				span {
					color: ${ props => props.theme.textSecondary };
					font-size: 14px;
				}

				select {
					background: ${ props => props.theme.footer.selectBackground };
					border: none;
					color: ${ props => props.theme.textPrimary };
					padding: 6px 12px;
					border-radius: 6px;
					margin-left: 8px;

					&:focus {
						outline: none;
					}
				}
			}
		}

		@media(max-width: 1100px) {
			&.bottom {
				div {
					flex-direction: column;

					span {
						margin-bottom: 5px;
					}

					select {
						width: 100%;
						max-width: 200px;
						height: 40px;
					}
				}
			}
		}

		@media(max-width: 620px) {
			& {
				display: flex;
				flex-direction: column;
				align-items: center;

				.column {
					text-align: center;

					h3:before {
						display: none;
					}
				}
			}

			&.bottom {
				width: 100%;
				max-width: 200px;

				div {
					width: 100%;
				}
			}
		}
    }
`

export const Content = styled.div`
    flex: 1;
`

export const Website = styled.div`
    display: flex;
    flex-direction: column;
    min-height: 100vh;
`

export default Navigation