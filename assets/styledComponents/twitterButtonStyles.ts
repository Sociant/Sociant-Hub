import styled from 'styled-components'

const TwitterButtonStyles = styled.a`
	padding: 8px 16px;
	border: solid 1px ${ props => props.theme.twitter.border };
	border-radius: 5px;
	display: flex;
	align-items: center;
	background: ${ props => props.theme.twitter.background };
	color: ${ props => props.theme.twitter.text };
	cursor: pointer;
	text-decoration: none;
	font-size: 14px;
	
	transition: border-color .2s ease, background .2s ease, color .2s ease;
	
	svg {
		margin-right: 8px;
	}
	
	&:hover {
		border-color: ${ props => props.theme.twitter.borderHover };
		background: ${ props => props.theme.twitter.backgroundHover };
		color: ${ props => props.theme.twitter.textHover };
	}
`

export default TwitterButtonStyles