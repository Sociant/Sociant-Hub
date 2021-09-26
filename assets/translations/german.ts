import { LanguageResource } from './i18n'

export default {
	localeName: 'Deutsch',
	navigation: {
		title: 'Sociant Hub',
		items: {
			home: 'Home',
			settings: 'Einstellungen'
		},
		signInText: 'Mit Twitter anmelden'
	},
	profile: {
		loading: 'Wird geladen...',
		graph: {
			follower: 'Follower',
			following: 'Folge ich',
			formats: {
				hour: 'dd.MM.yy HH:mm',
				day: 'dd.MM.yy',
				month: 'MMM yy'
			}
		},
		recentActivities: 'Letzte Aktivitäten',
        showMore: 'mehr anzeigen',
		userAnalytics: {
			title: 'Nutzer-Analyse',
			followers: 'Follower',
			following: 'Folge ich',
			tweets: 'Tweets',
			listedIn: 'In Listen',
			liked: 'Tweets geliked',
			age: 'Account-Alter',
			ageItems: {
				year: '{{count}} Jahr',
				year_plural: '{{count}} Jahre',
				month: '{{count}} Monat',
				month_plural: '{{count}} Monate',
				week: '{{count}} Woche',
				week_plural: '{{count}} Wochen',
				day: '{{count}} Tag',
				day_plural: '{{count}} Tage'
			}
		},
		followerAnalytics: {
			title: 'Follower-Analyse',
			verified: 'Verifizierte Follower',
			protected: 'Private Follower',
			tweets: 'Tweets gesamt',
			likes: 'Likes gesamt',
			mostTweets: 'Meiste Tweets',
			mostFollowers: 'Meiste Follower',
			oldestAccount: 'Ältester Account'
		},
        lastUpdate: 'Letztes Update:',
		manualUpdate: 'Manuelles Update'
	},
	user: {
		followHistory: 'Verlauf',
		userDetails: 'Nutzerdetails',
		userStatistics: 'Nutzerstatistiken',
		twitterButton: 'Auf Twitter anzeigen',
		noActivities: 'Keine Aktivitäten gefunden'
	},
	actions: {
		followSelf: 'folgst du',
		followOther: 'folgt dir',
		unfollowSelf: 'folgst du nicht mehr',
		unfollowOther: 'folgt dir nicht mehr',
		followSelfExpanded: 'du folgst @{{name}}',
		followOtherExpanded: '@{{name}} folgt dir',
		unfollowSelfExpanded: 'du folgst @{{name}} nicht mehr',
		unfollowOtherExpanded: '@{{name}} folgt dir nicht mehr'
	},
	return: {
		profile: 'Zurück zum Profil',
		activities: 'Zurück zum Verlauf'
	},
	dateTimeFormat: 'HH:MM dd.mm.yyyy',
    loadMore: 'mehr laden',
    period: {
        hour: 'Stündlich',
        day: 'Täglich',
        month: 'Monatlich',
        hourInfo: 'Graph nach Stunde gruppieren',
        dayInfo: 'Graph nach Tag gruppieren',
        monthInfo: 'Graph nach Monat gruppieren'
    },
    footer: {
        about: {
            title: 'Über',
            madeWith: {
                prefix: 'Gemacht mit',
                suffix: 'aus Deutschland'
            },
            github: 'Auf Github anschauen'
        },
        page: {
            title: 'Sociant Hub',
            mainpage: 'Startseite',
            profile: 'Profil',
            activities: 'Aktivitäten',
            settings: 'Einstellungen',
            login: 'Login'
        },
        legal: {
            title: 'Rechtliches',
            feedbackContact: 'Feedback / Kontakt',
            privacyPolicy: 'Datenschutzerklärung',
            legalDisclosure: 'Impressum'
        },
        social: {
            title: 'Folge uns',
            twitter: '@{{ name }} auf Twitter',
            github: '@{{ name }} auf Github'
        }
    },
    pageTitles: {
        activities: 'Aktivitäten',
        home: 'Kenne deine Follower',
        profile: 'Profil',
        settings: 'Einstellungen',
        user: '@{{ name }}',
        userLoading: 'wird geladen' 
    }
} as LanguageResource