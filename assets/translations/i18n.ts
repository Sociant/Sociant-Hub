import i18n from 'i18next'
import { useTranslation, initReactI18next } from 'react-i18next'
import english from './english'
import german from './german'
import I18nextBrowserLanguageDetector from 'i18next-browser-languagedetector'

const resources = {
	en: {
		translation: english
	},
	de: {
		translation: german
	},
}

i18n
	.use(I18nextBrowserLanguageDetector)
	.use(initReactI18next)
	.init({
		resources,
		lng: 'de',
		fallbackLng: 'de',
		interpolation: {
			escapeValue: false
		}
	});

export type LanguageResource = {
	localeName: string
	navigation: {
		title: string
		items: {
			home: string
			settings: string
		}
		signInText: string
	}
	profile: {
		loading: string
		graph: {
			follower: string
			following: string
			formats: {
				hour: string
				day: string
				month: string
			}
		}
		recentActivities: string
        showMore: string
		userAnalytics: {
			title: string
			followers: string
			following: string
			tweets: string
			listedIn: string
			liked: string
			age: string
			ageItems: {
				year: string
				year_plural: string
				month: string
				month_plural: string
				week: string
				week_plural: string
				day: string
				day_plural: string
			}
		}
		followerAnalytics: {
			title: string
			verified: string
			protected: string
			tweets: string
			likes: string
			mostTweets: string
			mostFollowers: string
			oldestAccount: string
		}
        lastUpdate: string
		manualUpdate: string
	}
	user: {
		followHistory: string
		userDetails: string
		userStatistics: string
		twitterButton: string
		noActivities: string
	}
	actions: {
		followSelf: string
		followOther: string
		unfollowSelf: string
		unfollowOther: string
		followSelfExpanded: string
		followOtherExpanded: string
		unfollowSelfExpanded: string
		unfollowOtherExpanded: string
	}
	return: {
		profile: string
		activities: string
	}
	dateTimeFormat: string
    loadMore: string
    period: {
        hour: string
        day: string
        month: string
        hourInfo: string
        dayInfo: string
        monthInfo: string
    }
    footer: {
        about: {
            title: string
            madeWith: {
                prefix: string
                suffix: string
            }
            github: string
        }
        page: {
            title: string
            mainpage: string
            profile: string
            activities: string
            settings: string
            login: string
        }
        legal: {
            title: string
            feedbackContact: string
            privacyPolicy: string
            legalDisclosure: string
        }
        social: {
            title: string
            twitter: string
            github: string
        }
    }
}

export default i18n;