import { LanguageResource } from './i18n'

export default {
	localeName: 'Deutsch',
	navigation: {
		title: 'Sociant Hub',
		items: {
			home: 'Home',
			settings: 'Einstellungen',
			profile: 'Profil',
			logout: 'Abmelden',
		},
		signInText: 'Mit Twitter anmelden',
	},
	home: {
		header: {
			title: 'Lerne deine Follower mit <b>Sociant Hub</b> kennen',
			message: 'Der einfachste Weg, um deine Follower und Unfollower zu analysieren',
		},
		item1: {
			title: 'Alles im Überblick',
			message:
				'Verfolge die Aktivitäten deiner Follower und sehe, wer dir folgt und entfolgt. Analysiere dabei dein Wachstum und lerne mehr über deine Follower. Du kannst Sociant Hub frei einstellen und selbst festlegen, in welchem Intervall deine Follower geladen werden sollen. Oder aktualisiere deine Follower manuell in deinem Panel.',
		},
		item2: {
			title: 'Erfahre mehr über deine Follower',
			message:
				'Du willst wissen, wie viele deiner Follower verifiziert sind oder wie viele Tweets alle zusammen getweetet haben, Sociant Hub erledigt das alles für dich automatisch und du kannst dich zurücklehnen und entspannen.',
		},
		item3: {
			title: 'Bis aufs kleinste Detail',
			message:
				'Beobachte den Verlauf einzelner Profile mit deinem Account. Erhalte Informationen über die wichtigsten Eigenschaften und ob es sich lohnt, dem Profil weiter zu folgen oder nicht (Sollte das Profil ein NFT als Profilbild verwenden, werden wir dich davor warnen und du kannst ihm/ihr einfach entfolgen).',
		},
	},
	profile: {
		loading: 'Wird geladen...',
		graph: {
			follower: 'Follower',
			following: 'Folge ich',
			formats: {
				hour: 'dd.MM.yy HH:mm',
				day: 'dd.MM.yy',
				month: 'MMM yy',
			},
			notEnoughData: {
				title: 'Nicht genügend Einträge',
				message: 'Sobald genügend Einträge vorhanden sind, wird das Diagramm hier angezeigt',
			},
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
				day_plural: '{{count}} Tage',
				hour: '{{count}} Stunde',
				hour_plural: '{{count}} Stunden',
				minute: '{{count}} Minute',
				minute_plural: '{{count}} Minuten',
			},
			lastActivityTitle: 'Letzte Aktivität',
			lastActivity: {
				tweet: 'Letzter Tweet vor {{time}}',
				reply: 'Letzte Antwort vor {{time}}',
				retweet: 'Letzter Retweet vor {{time}}',
				like: 'Letzter Like vor {{time}}',
			},
		},
		followerAnalytics: {
			title: 'Follower-Analyse',
			verified: 'Verifizierte Follower',
			protected: 'Private Follower',
			tweets: 'Tweets gesamt',
			likes: 'Likes gesamt',
			mostTweets: 'Meiste Tweets',
			mostFollowers: 'Meiste Follower',
			oldestAccount: 'Ältester Account',
		},
		lastUpdate: 'Letztes Update:',
		manualUpdate: 'Manuelles Update',
		followersProtected: 'Private Follower',
		followersVerified: 'Verifizierte Follower',
	},
	user: {
		followHistory: 'Verlauf',
		userDetails: 'Nutzerdetails',
		nftWarning: 'Nutzer hat ein NFT-Profilbild',
		userStatistics: 'Nutzerstatistiken',
		twitterButton: 'Auf Twitter anzeigen',
		noActivities: 'Keine Aktivitäten gefunden',
		relationship: {
			title: 'Aktuelles Verhältnis',
			followOther: '@{{name}} folgt dir',
			followSelf: 'Du folgst @{{name}}',
			blockedOther: '@{{name}} hat dich blockiert',
			blockedSelf: 'Du hast @{{name}} blockiert',
			mutedSelf: 'Du hast @{{name}} gemutet',
			dmSelf: 'Du kannst @{{name}} eine DM schreiben',
		},
	},
	actions: {
		followSelf: 'folgst du',
		followOther: 'folgt dir',
		unfollowSelf: 'folgst du nicht mehr',
		unfollowOther: 'folgt dir nicht mehr',
		followSelfExpanded: 'du folgst @{{name}}',
		followOtherExpanded: '@{{name}} folgt dir',
		unfollowSelfExpanded: 'du folgst @{{name}} nicht mehr',
		unfollowOtherExpanded: '@{{name}} folgt dir nicht mehr',
	},
	return: {
		profile: 'Zurück zum Profil',
		activities: 'Zurück zum Verlauf',
	},
	dateTimeFormat: 'HH:MM dd.mm.yyyy',
	loadMore: 'mehr laden',
	period: {
		hour: 'Stündlich',
		day: 'Täglich',
		month: 'Monatlich',
		hourInfo: 'Graph nach Stunde gruppieren',
		dayInfo: 'Graph nach Tag gruppieren',
		monthInfo: 'Graph nach Monat gruppieren',
	},
	footer: {
		about: {
			title: 'Über',
			madeWith: {
				prefix: 'Gemacht mit',
				suffix: 'aus Deutschland',
			},
			github: 'Auf Github anschauen',
		},
		page: {
			title: 'Sociant Hub',
			mainpage: 'Startseite',
			profile: 'Profil',
			activities: 'Aktivitäten',
			settings: 'Einstellungen',
			login: 'Login',
			setup: 'Einrichtung',
		},
		legal: {
			title: 'Rechtliches',
			feedbackContact: 'Feedback / Kontakt',
			privacyPolicy: 'Datenschutzerklärung',
			legalDisclosure: 'Impressum',
		},
		social: {
			title: 'Folge uns',
			twitter: '@{{ name }} auf Twitter',
			github: '@{{ name }} auf Github',
		},
		theme: {
			title: 'Design',
			items: {
				darkmode: 'Dunkles Design',
				lightmode: 'Helles Design',
			},
		},
		language: {
			title: 'Sprache',
			items: {
				english: 'Englisch',
				german: 'Deutsch',
			},
		},
		logoVariant: {
			title: 'Logo-Variante',
			items: {
				none: 'Keine',
				pride: 'Pride-Flagge',
				agender: 'Agender-Flagge',
				asexual: 'Asexual-Flagge',
				androgyne: 'Androgyne-Flagge',
				aromantic: 'Aromantic-Flagge',
				bigender: 'Bigender-Flagge',
				bisexual: 'Bisexual-Flagge',
				demigirl: 'Demi-Girl-Flagge',
				demiguy: 'Demi-Guy-Flagge',
				deminonbinary: 'Demi-Nonbinary-Flagge',
				demisexual: 'Demi-Sexual-Flagge',
				genderfluid: 'Genderfluid-Flagge',
				genderqueer: 'Genderqueer-Flagge',
				lesbian: 'Lesbian-Flagge',
				neurotis: 'Neurotis-Flagge',
				nonbinary: 'Non-Binary-Flagge',
				omnisexual: 'Omni-Sexual-Flagge',
				pansexual: 'Pan-Sexual-Flagge',
				polysexual: 'Poly-Sexual-Flagge',
				transgender: 'Transgender-Flagge',
				aporagender: 'Aporagender-Flagge',
				paragender: 'Paragender-Flagge',
			},
		},
	},
	settings: {
		title: 'Einstellungen',
		updateInterval: {
			title: 'Aktualisierungsintervall',
			items: {
				m: 'Manuelle Updates',
				h1: 'Jede Stunde',
				h12: 'Alle 12 Stunden',
				d1: 'Täglich',
				w1: 'Wöchentlich',
			},
		},
		theme: {
			title: 'Design',
			items: {
				darkmode: 'Dunkles Design',
				lightmode: 'Helles Design',
			},
		},
		profileChartScrollEffect: {
			title: 'Scroll-Effekt für Diagramm auf Profilseite',
			items: {
				on: 'Eingeschaltet',
				off: 'Ausgeschaltet',
			},
		},
		language: {
			title: 'Sprache',
			items: {
				english: 'Englisch',
				german: 'Deutsch',
			},
		},
		apiToken: {
			title: 'Dein API-Token',
			hint: 'Dieser Token ist mit deinem Account verknüpft und wird für die Sociant Hub API verwendet. Teile diesen Token mit niemandem.',
		},
		download: {
			title: 'Daten herunterladen',
			csv: 'CSV',
			json: 'JSON',
			followerHistory: {
				title: 'Follower-Verlauf',
				subtitle: 'Liste mit Nutzern, denen du gefolgt und entfolgt bist und umgekehrt',
			},
			followerCount: {
				title: 'Follower Anzahl',
				types: {
					total: 'gesamt',
					year: 'je Jahr',
					month: 'je Monat',
					day: 'je Tag',
				},
			},
			followerIds: 'Ids meiner Follower',
			followingIds: ' Ids von Nutzern, denen ich folge',
			additionalData: {
				title: 'Sonstige Daten',
				subtitle: 'Twitter-Nutzer, verknüpfte Geräte, automatisierte Updates, Analysen und mehr',
			},
		},
	},
	setup: {
		title: 'Einrichtung',
		welcomeMessage:
			'Willkommen {{name}}! Um fortzufahren, überprüfe bitte deine Einstellungen und ob du automatische Update einstellen möchtest und in welchem Intervall. Nach der Bestätigung werden wir öffentliche Benutzerinformationen über die Twitter API einmalig für grundlegende Funktionen abrufen.',
		submitButton: 'Absenden',
		loadingMessage:
			'Deine Einstellungen werden gespeichert und die Daten werden abgerufen. Das kann einen Moment dauern...',
	},
	pageTitles: {
		activities: 'Aktivitäten',
		home: 'Lerne deine Follower kennen',
		profile: 'Profil',
		settings: 'Einstellungen',
		user: '@{{ name }}',
		userLoading: 'wird geladen',
		setup: 'Einrichtung',
		imprint: 'Impressum',
	},
} as LanguageResource
