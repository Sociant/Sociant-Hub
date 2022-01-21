export type HistoryEntry = {
	date: number
	followerCount: number
	followingCount: number
}

export type HistoryResponse = {
	items: HistoryEntry[]
}

export type TwitterUser = {
	id: string
	name: string
	screen_name: string
	protected: boolean
	verified: boolean
	translator: boolean
	profile_image_url: string
}

export type TwitterUserExtended = TwitterUser & {
	created_at: string
	location: string | null
	description: string
	url: string | null
	followers_count: number
	friends_count: number
	listed_count: number
	favorites_count: number
	statuses_count: number
}

export type ActivityEntry = {
	id: number
	timestamp: number
	uuid: string
	twitter_user: TwitterUser | TwitterUserExtended
	action: string
}

export type AutomatedUpdate = {
	update_interval: string
	next_update: number
	last_update: number
}

export type HomeResponse = {
	type: string
	history: HistoryEntry[]
	activities: ActivityEntry[]
	twitter_user: TwitterUserExtended | null
	automated_update: AutomatedUpdate | null
	can_update: boolean
}

export type Statistics = {
	updated: string
	followers_count: number
	friends_count: number
	listed_count: number
	favorites_count: number
	statuses_count: number
	created_at: string
}

export type Analytics = {
	updated: string
	verified_followers: number
	protected_followers: number
	status_count: number
	favorite_count: number
	most_statuses: TwitterUser | TwitterUserExtended | null
	most_followers: TwitterUser | TwitterUserExtended | null
	oldest_account: TwitterUser | TwitterUserExtended | null
}

export type StatisticsResponse = {
	statistics: Statistics
	analytics: Analytics
}

export type PaginationResponse = {
	length: number
	limit: number
	more_available: boolean
	page: number
	slim: boolean
}

export type ActivityResponse = PaginationResponse & {
	items: ActivityEntry[]
}

export type UsersResponse = PaginationResponse & {
	items: TwitterUserExtended[] | TwitterUser[]
}

export type ErrorResponse = {
	error: string
}

export type Relationship = {
	source: {
		id: number
		id_str: string
		screen_name: string
		following: boolean
		followed_by: boolean
		live_following: boolean
		following_received: boolean
		following_requested: boolean
		notifications_enabled: boolean
		can_dm: boolean
		blocking: boolean
		blocked_by: boolean
		muting: boolean
		want_retweets: boolean
		all_replies: boolean
		marked_spam: boolean
	}
	target: {
		id: number
		id_str: string
		screen_name: string
		following: boolean
		followed_by: boolean
		following_received: boolean
		following_requested: boolean
	}
}

export type RelationshipResponse = {
	relationship: Relationship
}

export type InfoResponse = {
	setup_completed: boolean
	above_follower_limit: boolean
	follower_limit: number
	twitter_user: TwitterUser | TwitterUserExtended
	notification_settings?: any
	automated_update: AutomatedUpdate | null
}

export type LastActivity = {
	last_activity: string
	last_activity_id: number
	last_activity_screen_name: string
	last_activity_type: string
}
