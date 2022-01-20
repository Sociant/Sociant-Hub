import styled from 'styled-components'

const TwitterButtonStyles = styled.a`
	padding: 8px 16px;
	border: solid 1px ${ props => props.white ? 'white' : props.theme.twitter.border };
	border-radius: 5px;
	display: flex;
	align-items: center;
	background: ${ props => props.white ? 'none' : props.theme.twitter.background };
	color: ${ props => props.white ? 'white' : props.theme.twitter.text };
	cursor: pointer;
	text-decoration: none;
	font-size: 14px;
	
	transition: border-color .2s ease, background .2s ease, color .2s ease;
	
	svg {
		margin-right: 8px;
	}
	
	&:hover {
		border-color: ${ props => props.white ? '#1DA1F2' : props.theme.twitter.borderHover };
		background: ${ props => props.white ? '#1DA1F2' : props.theme.twitter.backgroundHover };
		color: ${ props => props.white ? '#ffffff' : props.theme.twitter.textHover };
	}
`

export default TwitterButtonStyles