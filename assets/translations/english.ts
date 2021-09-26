import { LanguageResource } from './i18n'

export default {
	localeName: 'English',
	navigation: {
		title: 'Sociant Hub',
		items: {
			home: 'Home',
			settings: 'Settings'
		},
		signInText: 'Sign in with Twitter'
	},
	profile: {
		loading: 'Loading...',
		graph: {
			follower: 'Followers',
			following: 'Following',
			formats: {
				hour: 'MM/dd/yy HH:mm',
				day: 'MM/dd/yy',
				month: 'MMM yy'
			}
		},
		recentActivities: 'Recent Activities',
        showMore: 'show more',
		userAnalytics: {
			title: 'User Analytics',
			followers: 'Followers',
			following: 'Following',
			tweets: 'Tweets',
			listedIn: 'Listed in',
			liked: 'Tweets liked',
			age: 'Account age',
			ageItems: {
				year: '{{count}} year',
				year_plural: '{{count}} years',
				month: '{{count}} month',
				month_plural: '{{count}} months',
				week: '{{count}} week',
				week_plural: '{{count}} weeks',
				day: '{{count}} day',
				day_plural: '{{count}} days'
			}
		},
		followerAnalytics: {
			title: 'Follower Analytics',
			verified: 'Verified followers',
			protected: 'Protected followers',
			tweets: 'Tweets total',
			likes: 'Likes total',
			mostTweets: 'Most tweets',
			mostFollowers: 'Most followers',
			oldestAccount: 'Oldest account'
		},
        lastUpdate: 'Last update:',
		manualUpdate: 'Manual update'
	},
	user: {
		followHistory: 'Follow History',
		userDetails: 'User Details',
		userStatistics: 'User Statistics',
		twitterButton: 'View on Twitter',
		noActivities: 'Keine Aktivitäten gefunden'
	},
	actions: {
		followSelf: 'you followed',
		followOther: 'followed you',
		unfollowSelf: 'you unfollowed',
		unfollowOther: 'unfollowed you',
		followSelfExpanded: 'you followed @{{name}}',
		followOtherExpanded: '@{{name}} followed you',
		unfollowSelfExpanded: 'you unfollowed @{{name}}',
		unfollowOtherExpanded: '@{{name}} unfollowed you'
	},
	return: {
		profile: 'Back to profile',
		activities: 'Back to activities'
	},
	dateTimeFormat: 'HH:MM mm/dd/yyyy',
    loadMore: 'load more',
    period: {
        hour: 'Hourly',
        day: 'Daily',
        month: 'Monthly',
        hourInfo: 'Group graph by hour',
        dayInfo: 'Group graph by day',
        monthInfo: 'Group graph by month'
    },
    footer: {
        about: {
            title: 'About',
            madeWith: {
                prefix: 'Made with',
                suffix: 'in Germany'
            },
            github: 'View on Github'
        },
        page: {
            title: 'Sociant Hub',
            mainpage: 'Mainpage',
            profile: 'Profile',
            activities: 'Activities',
            settings: 'Settings',
            login: 'Login'
        },
        legal: {
            title: 'Legal information',
            feedbackContact: 'Feedback / Contact',
            privacyPolicy: 'Privacy policy',
            legalDisclosure: 'Legal disclosure'
        },
        social: {
            title: 'Follow us',
            twitter: '@{{ name }} on Twitter',
            github: '@{{ name }} on Github'
        }
    },
    pageTitles: {
        activities: 'Activities',
        home: 'Know your followers',
        profile: 'Profile',
        settings: 'Settings',
        user: '@{{ name }}',
        userLoading: 'loading' 
    }
} as LanguageResource