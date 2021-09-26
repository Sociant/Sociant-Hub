import React, { useEffect, useState } from 'react'
import { Link, Route, Switch } from 'react-router-dom'
import Home from './routes/Home'
import GlobalStyle from './styledComponents/globalStyles'
import Navigation, { Content, Footer, Website } from './styledComponents/navigationStyles'
import { ThemeProvider } from 'styled-components'
import { darkTheme, lightTheme } from './styledComponents/themes'
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome'
import { faMoon, faSun } from '@fortawesome/pro-light-svg-icons'
import TwitterButtonStyles from './styledComponents/twitterButtonStyles'
import { faTwitter } from '@fortawesome/free-brands-svg-icons'
import { useApp } from './provider/AppProvider'
import Profile from './routes/Profile'
import { useTranslation } from 'react-i18next'
import User from './routes/User'
import Activities from './routes/Activities'
import { faHeart } from '@fortawesome/pro-solid-svg-icons'
import Settings from './routes/Settings';
import Followers from './routes/Followers';

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

	const { theme, isReady, data, setTheme } = useApp()
	const { t } = useTranslation()

	if (!isReady) return <div>Loading</div>

	return <>
		<ThemeProvider theme={theme}>
			<GlobalStyle />
            <Website>
                <Navigation>
                    <div className="container">
                        <div className="title">
                            <div className="inner">
                                { t('navigation.title') }
                                <div />
                            </div>
                        </div>
                        <div className="spacer" />
                        {
                            data.authenticated ?
                                <div className="items">
                                    <Link className="item" to={'/'}>{ t('navigation.items.home') }</Link>
                                    <Link className="item" to={'/settings'}>{ t('navigation.items.settings') }</Link>
                                    <Link className="item profile" to={'/profile'}>
                                        <span>
                                            {data.twitterUser ? <>
                                                <b>{data.twitterUser.name}</b><br />
                                                @{data.twitterUser.screenName}
                                            </> : `@${data.screenName}`}
                                        </span>
                                        <div style={{ backgroundImage: `url(${data.twitterUser.profileImageURL})` }} />
                                    </Link>
                                    <div className="toggle"
                                        onClick={() => setTheme(current => current === darkTheme ? lightTheme : darkTheme)}>
                                        <FontAwesomeIcon icon={theme === darkTheme ? faMoon : faSun} />
                                    </div>
                                </div> :
                                <div className="items">
                                    <TwitterButtonStyles href="/login">
                                        <FontAwesomeIcon icon={faTwitter} />
                                        { t('navigation.signInText') }
                                    </TwitterButtonStyles>
                                    <div className="toggle"
                                        onClick={() => setTheme(current => current === darkTheme ? lightTheme : darkTheme)}>
                                        <FontAwesomeIcon icon={theme === darkTheme ? faMoon : faSun} />
                                    </div>
                                </div>
                        }
                    </div>
                </Navigation>
                <Content>
                    <Switch>
                        <Route path="/test" component={Home} />
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
                            <h3>{ t('footer.about.title') }</h3>
                            <span>Sociant Hub v2.0.0 © 2021</span>
                            <span>{ t('footer.about.madeWith.prefix') } <FontAwesomeIcon icon={ faHeart } /> { t('footer.about.madeWith.suffix') }</span>
                            <a href="https://github.com/Sociant/Sociant-Hub" target="_blank">{ t('footer.about.github') }</a>
                        </div>
                        <div className="column">
                            <h3>{ t('footer.page.title') }</h3>
                            <Link to="/">{ t('footer.page.mainpage') }</Link>
                            <Link to="/profile">{ t('footer.page.profile') }</Link>
                            <Link to="/activities">{ t('footer.page.activities') }</Link>
                            <Link to="/settings">{ t('footer.page.settings') }</Link>
                        </div>
                        <div className="column">
                            <h3>{ t('footer.legal.title') }</h3>
                            <Link to="/feedback-contact">{ t('footer.legal.feedbackContact') }</Link>
                            <Link to="/privacy-policy">{ t('footer.legal.privacyPolicy') }</Link>
                            <Link to="/legal-disclosure">{ t('footer.legal.legalDisclosure') }</Link>
                        </div>
                        <div className="column">
                            <h3>{ t('footer.social.title') }</h3>
                            <a href="https://twitter.com/SociantWD" target="_blank">{ t('footer.social.twitter', { name: 'SociantWD' }) }</a>
                            <a href="https://github.com/Sociant" target="_blank">{ t('footer.social.github', { name: 'Sociant' }) }</a>
                            <a href="https://twitter.com/l9cgv" target="_blank">{ t('footer.social.twitter', { name: 'l9cgv' }) }</a>
                        </div>
                    </div>
                </Footer>
            </Website>
		</ThemeProvider>
	</>
}