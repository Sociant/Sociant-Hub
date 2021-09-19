import styled from 'styled-components'

const Navigation = styled.nav`
	position: fixed;
	top: 0;
	width: 100%;
	height: 58px;
	background: ${ props => props.theme.navigation.background };
  	box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);
  	z-index: 100;
	
    transition: background .2s ease;
	
	.title {
		color: ${ props => props.theme.navigation.title };
		font-weight: 500;
		
    	transition: color .2s ease;
	
		.inner {
			position: relative;
			padding: 0 6px 10px;
			
			div {
				position: absolute;
				bottom: 0;
				left: 0;
				height: 8px;
				width: 100%;
				background: linear-gradient(to right, #e40303 0%, #e40303 16.6%, #ff8c00 16.6%, #ff8c00 33.3%, #ffed00 33.3%, #ffed00 50%, #008026 50%, #008026 66.6%, #004dff 66.6%, #004dff 83.3%, #750787 83.3%, #750787 100%);
				border-radius: 2px;
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
			color: ${ props => props.theme.navigation.item };
		
    		transition: color .2s ease;
			
			&:hover {
				color: ${ props => props.theme.navigation.itemHover };
			}
		}
		
		.item {
			font-size: 15px;
			padding: 5px 10px;
			text-decoration: none;
			color: ${ props => props.theme.navigation.item };
		
    		transition: color .2s ease;
			
			&:hover {
				color: ${ props => props.theme.navigation.itemHover };
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
		max-width: 1200px;
		margin: 0 auto;
		display: flex;
		align-items: center;
		height: 100%;
		
		.spacer {
			flex: 1;
		}
	}
`

export const Footer = styled.footer`
    background: ${ props => props.theme.footer.background };
    padding: 40px 0;
    margin-top: 30px;
  	box-shadow: 0 1px 3px rgba(0,0,0,0.12), 0 1px 2px rgba(0,0,0,0.24);

    .container {
        max-width: 1200px;
        margin: 0 auto;
        display: flex;
        align-items: flex-start;
        height: 100%;
        color: ${ props => props.theme.textSecondary };

        .column {
            flex: 1;
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