import { LanguageResource } from './i18n'

export default {
	localeName: 'English',
	navigation: {
		title: 'Sociant Hub',
		items: {
			home: 'Home',
			settings: 'Settings',
			profile: 'Profile',
			logout: 'Logout',
		},
		signInText: 'Sign in with Twitter',
	},
	home: {
		header: {
			title: 'Get to know your followers with <b>Sociant Hub</b>',
			message: 'The easiest way to analyse and track your followers and unfollowers without any hassle.',
		},
		item1: {
			title: 'Everything at a glance',
			message:
				'See who and when follows and unfollows you, analyze your growth and learn more about your followers. You can decide how often Sociant Hub should search for new followers and unfollowers or you can do it by your own in your personal panel.',
		},
		item2: {
			title: 'Learn more about your followers',
			message:
				'Wether you want to find out how many followers are verified, who is the oldest account or how many tweets your followers have in total, Sociant Hub calculates everything for you so you can sit back and relax.',
		},
		item3: {
			title: 'Down to the smallest detail',
			message:
				'Monitor the history of individual profiles with your account. Get information about the most important details and whether it is worth following the profile or not (if the profile uses an NFT as profile picture, we will warn you about it and you can easily unfollow him/her).',
		},
	},
	profile: {
		loading: 'Loading...',
		graph: {
			follower: 'Followers',
			following: 'Following',
			formats: {
				hour: 'MM/dd/yy HH:mm',
				day: 'MM/dd/yy',
				month: 'MMM yy',
			},
			notEnoughData: {
				title: 'Not enough data',
				message: 'As soon as there is enough data, the diagram will be displayed here',
			},
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
				day_plural: '{{count}} days',
				hour: '{{count}} hour',
				hour_plural: '{{count}} hours',
				minute: '{{count}} minute',
				minute_plural: '{{count}} minutes',
			},
			lastActivity: {
				tweet: 'Last tweet {{time}} ago',
				reply: 'Last reply {{time}} ago',
				retweet: 'Last retweet {{time}} ago',
				like: 'Last like {{time}} ago',
			},
		},
		followerAnalytics: {
			title: 'Follower Analytics',
			verified: 'Verified followers',
			protected: 'Protected followers',
			tweets: 'Tweets total',
			likes: 'Likes total',
			mostTweets: 'Most tweets',
			mostFollowers: 'Most followers',
			oldestAccount: 'Oldest account',
		},
		lastUpdate: 'Last update:',
		manualUpdate: 'Manual update',
		followersProtected: 'Private followers',
		followersVerified: 'Verified followers',
	},
	user: {
		followHistory: 'Follow History',
		userDetails: 'User Details',
		nftWarning: 'User has a NFT as profile picture',
		userStatistics: 'User Statistics',
		twitterButton: 'View on Twitter',
		noActivities: 'Keine Aktivitäten gefunden',
		relationship: {
			title: 'Current relationship',
			followOther: '@{{name}} is following you',
			followSelf: 'You are following @{{name}}',
			blockedOther: '@{{name}} has blocked you',
			blockedSelf: 'You blocked @{{name}}',
			mutedSelf: 'You muted @{{name}}',
			dmSelf: 'You can DM @{{name}}',
		},
	},
	actions: {
		followSelf: 'you followed',
		followOther: 'followed you',
		unfollowSelf: 'you unfollowed',
		unfollowOther: 'unfollowed you',
		followSelfExpanded: 'you followed @{{name}}',
		followOtherExpanded: '@{{name}} followed you',
		unfollowSelfExpanded: 'you unfollowed @{{name}}',
		unfollowOtherExpanded: '@{{name}} unfollowed you',
	},
	return: {
		profile: 'Back to profile',
		activities: 'Back to activities',
	},
	dateTimeFormat: 'HH:MM mm/dd/yyyy',
	loadMore: 'load more',
	period: {
		hour: 'Hourly',
		day: 'Daily',
		month: 'Monthly',
		hourInfo: 'Group graph by hour',
		dayInfo: 'Group graph by day',
		monthInfo: 'Group graph by month',
	},
	footer: {
		about: {
			title: 'About',
			madeWith: {
				prefix: 'Made with',
				suffix: 'in Germany',
			},
			github: 'View on Github',
		},
		page: {
			title: 'Sociant Hub',
			mainpage: 'Mainpage',
			profile: 'Profile',
			activities: 'Activities',
			settings: 'Settings',
			login: 'Login',
			setup: 'Setup',
		},
		legal: {
			title: 'Legal information',
			feedbackContact: 'Feedback / Contact',
			privacyPolicy: 'Privacy policy',
			legalDisclosure: 'Legal disclosure',
		},
		social: {
			title: 'Follow us',
			twitter: '@{{ name }} on Twitter',
			github: '@{{ name }} on Github',
		},
		theme: {
			title: 'Theme',
			items: {
				darkmode: 'Darkmode',
				lightmode: 'Lightmode',
			},
		},
		language: {
			title: 'Language',
			items: {
				english: 'English',
				german: 'German',
			},
		},
		logoVariant: {
			title: 'Logo variant',
			items: {
				none: 'None',
				pride: 'Pride-Flag',
				agender: 'Agender-Flag',
				asexual: 'Asexual-Flag',
				androgyne: 'Androgyne-Flag',
				aromantic: 'Aromantic-Flag',
				bigender: 'Bigender-Flag',
				bisexual: 'Bisexual-Flag',
				demigirl: 'Demi-Girl-Flag',
				demiguy: 'Demi-Guy-Flag',
				deminonbinary: 'Demi-Nonbinary-Flag',
				demisexual: 'Demi-Sexual-Flag',
				genderfluid: 'Genderfluid-Flag',
				genderqueer: 'Genderqueer-Flag',
				lesbian: 'Lesbian-Flag',
				neurotis: 'Neurotis-Flag',
				nonbinary: 'Non-Binary-Flag',
				omnisexual: 'Omni-Sexual-Flag',
				pansexual: 'Pan-Sexual-Flag',
				polysexual: 'Poly-Sexual-Flag',
				transgender: 'Transgender-Flag',
				aporagender: 'Aporagender-Flag',
				paragender: 'Paragender-Flag',
			},
		},
	},
	settings: {
		title: 'Settings',
		updateInterval: {
			title: 'Update interval',
			items: {
				m: 'Manual update',
				h1: 'Every hour',
				h12: 'Every 12 hours',
				d1: 'Every day',
				w1: 'Every week',
			},
		},
		theme: {
			title: 'Theme',
			items: {
				darkmode: 'Darkmode',
				lightmode: 'Lightmode',
			},
		},
		profileChartScrollEffect: {
			title: 'Scroll effect for chart in profile',
			items: {
				on: 'Enabled',
				off: 'Disabled',
			},
		},
		language: {
			title: 'Language',
			items: {
				english: 'English',
				german: 'German',
			},
		},
		apiToken: {
			title: 'Your API token',
			hint: 'This token is linked to your account and can be used to access the Sociant Hub API. Dont share it with anyone!',
		},
		download: {
			title: 'Download your data',
			csv: 'CSV',
			json: 'JSON',
			followerHistory: {
				title: 'Follower history',
				subtitle: 'List with users you followed, unfollowed and vice versa',
			},
			followerCount: {
				title: 'Follower Count',
				types: {
					total: 'total',
					year: 'by year',
					month: 'by month',
					day: 'by day',
				},
			},
			followerIds: 'Follower ids',
			followingIds: 'Following ids',
			additionalData: {
				title: 'Additional data',
				subtitle: 'Twitter-User, connected devices, automated updates, analytics and more',
			},
		},
	},
	setup: {
		title: 'Setup',
		welcomeMessage:
			'Welcome {{name}}! To proceed please check your settings, whether you want automated updates and in which interval. After confirmation we will fetch public user information once for basic functionality.',
		submitButton: 'Submit',
		loadingMessage:
			"We're saving your preferences and fetching your public user information. This might take a while...",
	},
	pageTitles: {
		activities: 'Activities',
		home: 'Know your followers',
		profile: 'Profile',
		settings: 'Settings',
		user: '@{{ name }}',
		userLoading: 'loading',
		setup: 'Setup',
		imprint: 'Legal Disclosure',
	},
} as LanguageResource
