import { faArrowCircleDown, faArrowCircleUp, faQuestion } from '@fortawesome/pro-solid-svg-icons'
import dateFormat from 'dateformat'
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
	let difference = Math.abs(to - from) / 1000
	const result = {}
	const steps = {
		year: 31536000,
		month: 2592000,
		week: 604800,
		day: 86400,
		hour: 3600,
		minute: 60,
		second: 1,
	}

	Object.keys(steps).forEach(function (key) {
		result[key] = Math.floor(difference / steps[key])
		difference -= result[key] * steps[key]
	})

	return result as DifferenceResponse
}

export function formatDate(timestamp: number, t: TFunction): string {
	const date = new Date(timestamp)

	return dateFormat(date, t('dateTimeFormat'))
}

export function formatAction(action: string, t: TFunction): string {
	switch (action) {
		case 'follow_self':
			return t('actions.followSelf')
		case 'follow_other':
			return t('actions.followOther')
		case 'unfollow_self':
			return t('actions.unfollowSelf')
		case 'unfollow_other':
			return t('actions.unfollowOther')
		default:
			return ''
	}
}

export function formatActionExpanded(action: string, twitterUser: TwitterUser, t: TFunction): string {
	switch (action) {
		case 'follow_self':
			return t('actions.followSelfExpanded', { name: twitterUser.screen_name })
		case 'follow_other':
			return t('actions.followOtherExpanded', { name: twitterUser.screen_name })
		case 'unfollow_self':
			return t('actions.unfollowSelfExpanded', { name: twitterUser.screen_name })
		case 'unfollow_other':
			return t('actions.unfollowOtherExpanded', { name: twitterUser.screen_name })
		default:
			return ''
	}
}

export function getActionIcon(action: string) {
	switch (action) {
		case 'follow_self':
		case 'follow_other':
			return faArrowCircleUp
		case 'unfollow_self':
		case 'unfollow_other':
			return faArrowCircleDown
		default:
			return faQuestion
	}
}

export function thousandSeparator(x: number): string {
	return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',')
}

export function getAccountAge(createdAt: string, t: TFunction): string {
	const difference = getDifference(Date.parse(createdAt), Date.now())

	let output = []

	if (difference.year > 0) output.push(t('profile.userAnalytics.ageItems.year', { count: difference.year }))
	if (difference.year > 0 || difference.month > 0)
		output.push(t('profile.userAnalytics.ageItems.month', { count: difference.month }))
	if (difference.year > 0 || difference.month > 0 || difference.week > 0)
		output.push(t('profile.userAnalytics.ageItems.week', { count: difference.week }))
	output.push(t('profile.userAnalytics.ageItems.day', { count: difference.day }))

	return output.join(', ')
}

export function getExpandedAge(createdAt: string, t: TFunction): string {
	const difference = getDifference(Date.parse(createdAt), Date.now())

	let output = []

	if (difference.year > 0) output.push(t('profile.userAnalytics.ageItems.year', { count: difference.year }))
	if (difference.year > 0 || difference.month > 0)
		output.push(t('profile.userAnalytics.ageItems.month', { count: difference.month }))
	if (difference.year > 0 || difference.month > 0 || difference.week > 0)
		output.push(t('profile.userAnalytics.ageItems.week', { count: difference.week }))
	if (difference.year > 0 || difference.month > 0 || difference.week > 0 || difference.day > 0)
		output.push(t('profile.userAnalytics.ageItems.day', { count: difference.day }))
	if (difference.year > 0 || difference.month > 0 || difference.week > 0 || difference.day > 0 || difference.hour > 0)
		output.push(t('profile.userAnalytics.ageItems.hour', { count: difference.hour }))

	output.push(t('profile.userAnalytics.ageItems.minute', { count: difference.minute }))

	return output.join(', ')
}

export function isOnMobile() {
	let check = false
	;(function (a) {
		if (
			/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(
				a
			) ||
			/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(
				a.substr(0, 4)
			)
		)
			check = true
		//@ts-ignore
	})(navigator.userAgent || navigator.vendor || window.opera)
	return check
}
