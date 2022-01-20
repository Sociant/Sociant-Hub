import { faTwitter } from '@fortawesome/free-brands-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import React, { useEffect } from 'react'
import { useTranslation } from 'react-i18next'
import { Container } from '../styledComponents/globalStyles'
import { Header, HomePage } from '../styledComponents/homeStyles'
import TwitterButtonStyles from '../styledComponents/twitterButtonStyles'

export default function Home() {
	const { t } = useTranslation()

	useEffect(() => {
		document.title = `Sociant Hub - ${t('pageTitles.home')}`
	}, [])

	return (
		<HomePage>
			<Header>
				<Container>
					<div className="description">
						<h1>
							Get to know your followers with <b>Sociant Hub</b>
						</h1>
						<div>
							the easiest way to analyse and track your followers and unfollowers without any hassle
						</div>
						<TwitterButtonStyles white href="/login">
							<FontAwesomeIcon icon={faTwitter} />
							{t('navigation.signInText')}
						</TwitterButtonStyles>
					</div>
					<div className="image">
						<img src="/assets/images/mainpage-top.png" alt="Phone image" />
					</div>
				</Container>
			</Header>
			<Container>
				<div className="row reversed">
					<div className="image">
						<img src="/assets/images/mockup-1-1.jpg" alt="" />
					</div>
					<div className="text">
						<h2>
							Everything at a <b>glance</b>
						</h2>
						<div>
							See who and when follows and unfollows you, analyze your growth and learn more about your
							followers. You can decide how often Sociant Hub should search for new followers and
							unfollowers or you can do it by your own in your personal panel.
						</div>
					</div>
				</div>
				<div className="row">
					<div className="image smaller">
						<img src="/assets/images/mockup-1-2.jpg" alt="" />
					</div>
					<div className="text">
						<h2>
							Know your <b>followers</b>
						</h2>
						<div>
							Wether you want to find out how many followers are verified, who is the oldest account or
							how many tweets your followers have in total, Sociant Hub calculates everything for you so
							you can sit back and relax..
						</div>
					</div>
				</div>
				<div className="row reversed">
					<div className="image">
						<img src="/assets/images/mockup-2.jpg" alt="" />
					</div>
					<div className="text">
						<h2>
							Everything at a <b>glance</b>
						</h2>
						<div>
							See who and when follows and unfollows you, analyze your growth and learn more about your
							followers. You can decide how often Sociant Hub should search for new followers and
							unfollowers or you can do it by your own in your personal panel.
						</div>
					</div>
				</div>
			</Container>
		</HomePage>
	)
}
