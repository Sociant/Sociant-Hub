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
						<h1 dangerouslySetInnerHTML={{ __html: t('home.header.title') }}></h1>
						<div>{t('home.header.message')}</div>
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
						<h2>{t('home.item1.title')}</h2>
						<div>{t('home.item1.message')}</div>
					</div>
				</div>
				<div className="row">
					<div className="image smaller">
						<img src="/assets/images/mockup-1-2.jpg" alt="" />
					</div>
					<div className="text">
						<h2>{t('home.item2.title')}</h2>
						<div>{t('home.item2.message')}</div>
					</div>
				</div>
				<div className="row reversed">
					<div className="image">
						<img src="/assets/images/mockup-2.jpg" alt="" />
					</div>
					<div className="text">
						<h2>{t('home.item3.title')}</h2>
						<div>{t('home.item3.message')}</div>
					</div>
				</div>
			</Container>
		</HomePage>
	)
}
