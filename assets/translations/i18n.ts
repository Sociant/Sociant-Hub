import i18n from 'i18next'
import { initReactI18next } from 'react-i18next'
import english from './english'
import german from './german'

const resources = {
	en: {
		translation: english,
	},
	de: {
		translation: german,
	},
}

i18n.use(initReactI18next).init({
	resources,
	lng: localStorage.getItem('language') ?? 'en',
	fallbackLng: 'en',
	interpolation: {
		escapeValue: false,
	},
})

i18n.on('languageChanged', (language) => {
	localStorage.setItem('language', language)
})

export type LanguageResource = {
	localeName: string
	navigation: {
		title: string
		items: {
			home: string
			settings: string
			profile: string
			logout: string
		}
		signInText: string
	}
	home: {
		header: {
			title: string
			message: string
		}
		item1: {
			title: string
			message: string
		}
		item2: {
			title: string
			message: string
		}
		item3: {
			title: string
			message: string
		}
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
			notEnoughData: {
				title: string
				message: string
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
				hour: string
				hour_plural: string
				minute: string
				minute_plural: string
			}
			lastActivity: {
				tweet: string
				reply: string
				retweet: string
				like: string
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
		followersProtected: string
		followersVerified: string
	}
	user: {
		followHistory: string
		userDetails: string
		nftWarning: string
		userStatistics: string
		twitterButton: string
		noActivities: string
		relationship: {
			title: string
			followOther: string
			followSelf: string
			blockedOther: string
			blockedSelf: string
			mutedSelf: string
			dmSelf: string
		}
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
			setup: string
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
		theme: {
			title: string
			items: {
				darkmode: string
				lightmode: string
			}
		}
		language: {
			title: string
			items: {
				english: string
				german: string
			}
		}
		logoVariant: {
			title: string
			items: {
				none: string
				pride: string
				agender: string
				asexual: string
				androgyne: string
				aromantic: string
				bigender: string
				bisexual: string
				demigirl: string
				demiguy: string
				deminonbinary: string
				demisexual: string
				genderfluid: string
				genderqueer: string
				lesbian: string
				neurotis: string
				nonbinary: string
				omnisexual: string
				pansexual: string
				polysexual: string
				transgender: string
				aporagender: string
				paragender: string
			}
		}
	}
	settings: {
		title: string
		updateInterval: {
			title: string
			items: {
				m: string
				h1: string
				h12: string
				d1: string
				w1: string
			}
		}
		theme: {
			title: string
			items: {
				darkmode: string
				lightmode: string
			}
		}
		profileChartScrollEffect: {
			title: string
			items: {
				on: string
				off: string
			}
		}
		language: {
			title: string
			items: {
				english: string
				german: string
			}
		}
		apiToken: {
			title: string
			hint: string
		}
		download: {
			title: string
			csv: string
			json: string
			followerHistory: {
				title: string
				subtitle: string
			}
			followerCount: {
				title: string
				types: {
					total: string
					year: string
					month: string
					day: string
				}
			}
			followerIds: string
			followingIds: string
			additionalData: {
				title: string
				subtitle: string
			}
		}
	}
	setup: {
		title: string
		welcomeMessage: string
		submitButton: string
		loadingMessage: string
	}
	pageTitles: {
		activities: string
		home: string
		profile: string
		settings: string
		user: string
		userLoading: string
		setup: string
		imprint: string
	}
}

export default i18n
