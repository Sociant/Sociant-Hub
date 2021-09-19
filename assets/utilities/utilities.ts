import dateFormat  from 'dateformat'
import { faArrowCircleDown, faArrowCircleUp, faQuestion } from '@fortawesome/pro-solid-svg-icons'
import { TFunction } from 'i18next'
import { TwitterUser } from '../types/global'

export type DifferenceResponse = {
	year: number
	month: number
	week: number
	day: number
	hour: number
	minute: number
	second: number
}

export function getDifference(from: number, to: number): DifferenceResponse {
	let difference = Math.abs(to - from) / 1000;
	const result = {};
	const steps = {
		year: 31536000,
		month: 2592000,
		week: 604800,
		day: 86400,
		hour: 3600,
		minute: 60,
		second: 1
	};

	Object.keys(steps).forEach(function(key){
		result[key] = Math.floor(difference / steps[key]);
		difference -= result[key] * steps[key];
	});

	return result as DifferenceResponse;
}

export function formatDate(timestamp: number, t: TFunction): string {
	const date = new Date(timestamp)

	return dateFormat(date, t('dateTimeFormat'))
}

export function formatAction(action: string, t: TFunction): string {
	switch(action) {
		case 'follow_self': return t('actions.followSelf');
		case 'follow_other': return t('actions.followOther');
		case 'unfollow_self': return t('actions.unfollowSelf');
		case 'unfollow_other': return t('actions.unfollowOther');
		default: return '';
	}
}

export function formatActionExpanded(action: string, twitterUser: TwitterUser, t: TFunction): string {
	switch(action) {
		case 'follow_self': return t('actions.followSelfExpanded', { name: twitterUser.screen_name });
		case 'follow_other': return t('actions.followOtherExpanded', { name: twitterUser.screen_name });
		case 'unfollow_self': return t('actions.unfollowSelfExpanded', { name: twitterUser.screen_name });
		case 'unfollow_other': return t('actions.unfollowOtherExpanded', { name: twitterUser.screen_name });
		default: return '';
	}
}

export function getActionIcon(action: string) {
	switch(action) {
		case 'follow_self': case 'follow_other': return faArrowCircleUp;
		case 'unfollow_self': case 'unfollow_other': return faArrowCircleDown;
		default: return faQuestion;
	}
}

export function thousandSeparator(x: number): string {
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

export function getAccountAge(createdAt: string, t: TFunction): string {
	const difference = getDifference(Date.parse(createdAt), Date.now());

	let output = [];


	if(difference.year > 0) output.push(t('profile.userAnalytics.ageItems.year',{count: difference.year}))
	if(difference.year > 0 || difference.month > 0) output.push(t('profile.userAnalytics.ageItems.month',{count: difference.month}))
	if(difference.year > 0 || difference.month > 0 || difference.week > 0) output.push(t('profile.userAnalytics.ageItems.week',{count: difference.week}))
	output.push(t('profile.userAnalytics.ageItems.day',{count: difference.day}))

	return output.join(', ')
}