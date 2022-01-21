import { faTwitter } from '@fortawesome/free-brands-svg-icons'
import { faCircleNotch, faCog, faHome, faMoon, faSignOut, faSun, faUser } from '@fortawesome/pro-light-svg-icons'
import { faHeart } from '@fortawesome/pro-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import React, { useState } from 'react'
import { useTranslation } from 'react-i18next'
import { Link, Redirect, Route, Switch, useLocation } from 'react-router-dom'
import { ThemeProvider } from 'styled-components'
import { useApp } from './provider/AppProvider'
import Activities from './routes/Activities'
import Followers from './routes/Followers'
import Home from './routes/Home'
import Imprint from './routes/Imprint'
import PrivacyPolicy from './routes/PrivacyPolicy'
import Profile from './routes/Profile'
import Settings from './routes/Settings'
import Setup from './routes/Setup'
import User from './routes/User'
import GlobalStyle, { Loader } from './styledComponents/globalStyles'
import Navigation, { Content, Footer, Website } from './styledComponents/navigationStyles'
import { darkTheme, lightTheme } from './styledComponents/themes'
import TwitterButtonStyles from './styledComponents/twitterButtonStyles'
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
	const { theme, titleBarColors, setTitleBarColors, isReady, data, setTheme, setupCompleted } = useApp()
	const { t, i18n } = useTranslation()

	const { pathname } = useLocation()

	const [menuOpened, setMenuOpened] = useState(false)

	if (!isReady)
		return (
			<ThemeProvider theme={(localStorage.getItem('theme') ?? 'dark') === 'dark' ? darkTheme : lightTheme}>
				<GlobalStyle />
				<Website>
					<Loader>
						<FontAwesomeIcon icon={faCircleNotch} spin={true} />
					</Loader>
				</Website>
			</ThemeProvider>
		)

	const onClickItem = () => {
		setMenuOpened(false)
	}

	return (
		<>
			<ThemeProvider theme={theme}>
				<GlobalStyle />
				<Website>
					<Navigation titleBarColors={titleColorBars[titleBarColors]} transparent={pathname === '/'}>
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
										<div className="base" onClick={() => setMenuOpened((current) => !current)}>
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
											<div
												style={{ backgroundImage: `url(${data.twitterUser.profileImageURL})` }}
											/>
										</div>
										<div className={`items${menuOpened ? ' reveal' : ''}`} onClick={onClickItem}>
											<Link className="item" to={'/profile'}>
												<FontAwesomeIcon icon={faUser} />
												<span>{t('navigation.items.profile')}</span>
											</Link>
											<Link className="item" to={'/settings'}>
												<FontAwesomeIcon icon={faCog} />
												<span>{t('navigation.items.settings')}</span>
											</Link>
											<div className="divider" />
											<Link className="item" to={'/'}>
												<FontAwesomeIcon icon={faHome} />
												<span>{t('navigation.items.home')}</span>
											</Link>
											<a href="/logout" className="item">
												<FontAwesomeIcon icon={faSignOut} />
												<span>{t('navigation.items.logout')}</span>
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
									<TwitterButtonStyles href="/login" white={true}>
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
							<Route path="/legal-disclosure" component={Imprint} />
							<Route path="/privacy-policy" component={PrivacyPolicy} />
							{data.twitterUser &&
								setupCompleted && [
									<Route key="profile" path="/profile" component={Profile} />,
									<Route key="user-detail" path="/user/:uuid" component={User} />,
									<Route key="activities" path="/activities" component={Activities} />,
									<Route key="settings" path="/settings" component={Settings} />,
									<Route key="followers" path="/followers/:type" component={Followers} />,
									<Redirect key="setup" from="/setup" to="/profile" />,
								]}
							{data.twitterUser &&
								!setupCompleted && [
									<Route key="setup" path="/setup" component={Setup} />,
									<Redirect key="profile" from="/profile" to="/setup" />,
									<Redirect key="user-detail" from="/user/:uuid" to="/setup" />,
									<Redirect key="activities" from="/activities" to="/setup" />,
									<Redirect key="settings" from="/settings" to="/setup" />,
									<Redirect key="followers" from="/followers/:type" to="/setup" />,
								]}
							{!data.twitterUser && [
								<Redirect key="setup" from="/setup" to="/" />,
								<Redirect key="profile" from="/profile" to="/" />,
								<Redirect key="user-detail" from="/user/:uuid" to="/" />,
								<Redirect key="activities" from="/activities" to="/" />,
								<Redirect key="settings" from="/settings" to="/" />,
								<Redirect key="followers" from="/followers/:type" to="/" />,
							]}
						</Switch>
					</Content>
					<Footer>
						<div className="container">
							<div className="column">
								<h3>{t('footer.about.title')}</h3>
								<span>Sociant Hub v2.0.0 © 2022</span>
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
								{data.twitterUser && setupCompleted && (
									<>
										<Link to="/profile">{t('footer.page.profile')}</Link>
										<Link to="/activities">{t('footer.page.activities')}</Link>
										<Link to="/settings">{t('footer.page.settings')}</Link>
									</>
								)}
								{data.twitterUser && !setupCompleted && (
									<>
										<Link to="/setup">{t('footer.page.setup')}</Link>
									</>
								)}
								{!data.twitterUser && (
									<>
										<a href="/login">{t('footer.page.login')}</a>
									</>
								)}
							</div>
							<div className="column">
								<h3>{t('footer.legal.title')}</h3>
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
								<span>{t('footer.theme.title')}:</span>
								<select
									value={theme === darkTheme ? 'dark' : 'light'}
									onChange={(e) => setTheme(e.target.value === 'dark' ? darkTheme : lightTheme)}>
									<option value={'dark'}>{t('footer.theme.items.darkmode')}</option>
									<option value={'light'}>{t('footer.theme.items.lightmode')}</option>
								</select>
							</div>
							<div>
								<span>{t('footer.language.title')}:</span>
								<select value={i18n.language} onChange={(e) => i18n.changeLanguage(e.target.value)}>
									<option value="en">{t('footer.language.items.english')}</option>
									<option value="de">{t('footer.language.items.german')}</option>
								</select>
							</div>
							<div>
								<span>{t('footer.logoVariant.title')}:</span>
								<select value={titleBarColors} onChange={(e) => setTitleBarColors(e.target.value)}>
									{Object.keys(titleColorBars).map((item, index) => (
										<option key={index} value={item}>
											{t('footer.logoVariant.items.' + item)}
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
