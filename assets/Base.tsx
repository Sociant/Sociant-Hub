import React, { useEffect, useState } from 'react'
import { Link, Route, Switch, useHistory, useLocation } from 'react-router-dom'
import Home from './routes/Home'
import GlobalStyle from './styledComponents/globalStyles'
import Navigation, { Content, Footer, Website } from './styledComponents/navigationStyles'
import { ThemeProvider } from 'styled-components'
import { darkTheme, lightTheme } from './styledComponents/themes'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faCog, faHome, faMoon, faSignOut, faSun, faUser } from '@fortawesome/pro-light-svg-icons'
import TwitterButtonStyles from './styledComponents/twitterButtonStyles'
import { faTwitter } from '@fortawesome/free-brands-svg-icons'
import { useApp } from './provider/AppProvider'
import Profile from './routes/Profile'
import { useTranslation } from 'react-i18next'
import User from './routes/User'
import Activities from './routes/Activities'
import { faHeart } from '@fortawesome/pro-solid-svg-icons'
import Settings from './routes/Settings'
import Followers from './routes/Followers'
import titleColorBars from './types/titleColorBars'

export type AppData = {
	authenticated: boolean
	twitterUser: {
		name: string
		screenName: string
		profileImageURL: string
	} | null
	screenName: string | null
	name: string | null
	apiToken: string | null
}

export default function Base() {
	const { theme, titleBarColors, setTitleBarColors, isReady, data, setTheme } = useApp()
	const { t, i18n } = useTranslation()

	const { pathname } = useLocation()

	const [menuOpened, setMenuOpened] = useState(false)

	if (!isReady) return <div>Loading</div>

	const onClickItem = (event) => {
		setMenuOpened(false)
	}
	

	return (
		<>
			<ThemeProvider theme={theme}>
				<GlobalStyle />
				<Website>
					<Navigation titleBarColors={titleColorBars[titleBarColors]} transparent={ pathname === '/' }>
						<div className="container">
							<div className="title">
								<div className="inner">
									{t('navigation.title')}
									<div />
								</div>
							</div>
							<div className="spacer" />
							{data.authenticated ? (
								<div className="items">
									<div className="menu">
										<div className="base" onClick={ () => setMenuOpened(current => !current) }>
											<span>
												{data.twitterUser ? (
													<>
														<b>{data.twitterUser.name}</b>
														<br />@{data.twitterUser.screenName}
													</>
												) : (
													`@${data.screenName}`
												)}
											</span>
											<div style={{ backgroundImage: `url(${data.twitterUser.profileImageURL})` }} />
										</div>
										<div className={ `items${ menuOpened ? ' reveal' : '' }` } onClick={ onClickItem }>
											<Link className="item" to={'/profile'}>
												<FontAwesomeIcon icon={faUser} />
												<span>{ t('navigation.items.profile') }</span>
											</Link>
											<Link className="item" to={'/settings'}>
												<FontAwesomeIcon icon={faCog} />
												<span>{ t('navigation.items.settings') }</span>
											</Link>
											<div className="divider" />
											<Link className="item" to={'/'}>
												<FontAwesomeIcon icon={faHome} />
												<span>{ t('navigation.items.home') }</span>
											</Link>
											<a href='/logout' className="item">
												<FontAwesomeIcon icon={faSignOut} />
												<span>{ t('navigation.items.logout') }</span>
											</a>
										</div>
									</div>
									<div
										className="toggle"
										onClick={() =>
											setTheme((current) => (current === darkTheme ? lightTheme : darkTheme))
										}>
										<FontAwesomeIcon icon={theme === darkTheme ? faMoon : faSun} />
									</div>
								</div>
							) : (
								<div className="items">
									<TwitterButtonStyles href="/login">
										<FontAwesomeIcon icon={faTwitter} />
										{t('navigation.signInText')}
									</TwitterButtonStyles>
									<div
										className="toggle"
										onClick={() =>
											setTheme((current) => (current === darkTheme ? lightTheme : darkTheme))
										}>
										<FontAwesomeIcon icon={theme === darkTheme ? faMoon : faSun} />
									</div>
								</div>
							)}
						</div>
					</Navigation>
					<Content>
						<Switch>
							<Route path="/" component={Home} exact />
							<Route path="/profile" component={Profile} />
							<Route path="/user/:uuid" component={User} />
							<Route path="/activities" component={Activities} />
							<Route path="/settings" component={Settings} />
							<Route path="/followers/:type" component={Followers} />
						</Switch>
					</Content>
					<Footer>
						<div className="container">
							<div className="column">
								<h3>{t('footer.about.title')}</h3>
								<span>Sociant Hub v2.0.0 © 2021</span>
								<span>
									{t('footer.about.madeWith.prefix')} <FontAwesomeIcon icon={faHeart} />{' '}
									{t('footer.about.madeWith.suffix')}
								</span>
								<a href="https://github.com/Sociant/Sociant-Hub" target="_blank">
									{t('footer.about.github')}
								</a>
							</div>
							<div className="column">
								<h3>{t('footer.page.title')}</h3>
								<Link to="/">{t('footer.page.mainpage')}</Link>
								<Link to="/profile">{t('footer.page.profile')}</Link>
								<Link to="/activities">{t('footer.page.activities')}</Link>
								<Link to="/settings">{t('footer.page.settings')}</Link>
							</div>
							<div className="column">
								<h3>{t('footer.legal.title')}</h3>
								<Link to="/feedback-contact">{t('footer.legal.feedbackContact')}</Link>
								<Link to="/privacy-policy">{t('footer.legal.privacyPolicy')}</Link>
								<Link to="/legal-disclosure">{t('footer.legal.legalDisclosure')}</Link>
							</div>
							<div className="column">
								<h3>{t('footer.social.title')}</h3>
								<a href="https://twitter.com/SociantWD" target="_blank">
									{t('footer.social.twitter', { name: 'SociantWD' })}
								</a>
								<a href="https://github.com/Sociant" target="_blank">
									{t('footer.social.github', { name: 'Sociant' })}
								</a>
								<a href="https://twitter.com/l9cgv" target="_blank">
									{t('footer.social.twitter', { name: 'l9cgv' })}
								</a>
							</div>
						</div>
						<div className="container bottom">
							<div>
								<span>{ t('footer.theme.title') }:</span>
								<select value={ theme === darkTheme ? 'dark' : 'light' } onChange={(e) => setTheme(e.target.value === 'dark' ? darkTheme : lightTheme)}>
									<option value={ 'dark' }>{ t('footer.theme.items.darkmode') }</option>
									<option value={ 'light' }>{ t('footer.theme.items.lightmode') }</option>
								</select>
							</div>
							<div>
								<span>{ t('footer.language.title') }:</span>
								<select value={i18n.language} onChange={(e) => i18n.changeLanguage(e.target.value)}>
									<option value="en">{ t('footer.language.items.english') }</option>
									<option value="de">{ t('footer.language.items.german') }</option>
								</select>
							</div>
							<div>
								<span>{ t('footer.logoVariant.title') }:</span>
								<select value={titleBarColors} onChange={(e) => setTitleBarColors(e.target.value)}>
									{Object.keys(titleColorBars).map((item, index) => (
										<option key={index} value={item}>
											{ t('footer.logoVariant.items.' + item) }
										</option>
									))}
								</select>
							</div>
						</div>
					</Footer>
				</Website>
			</ThemeProvider>
		</>
	)
}
