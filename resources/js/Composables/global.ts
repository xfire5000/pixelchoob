import i18n from '../Plugins/i18n'
import type {
  UseTimeAgoMessages,
  UseTimeAgoUnitNamesDefault,
} from '@vueuse/core'

const { t } = i18n.global

export function useObjectResolve(path: string, obj?: any) {
  return path.split('.').reduce(function (prev, curr) {
    return prev ? prev[curr] : null
  }, obj || self)
}

export function useConvertFileSize(bytes: number, decimals?: number) {
  if (bytes == 0) return '0 Bytes'
  let k = 1000,
    dm = decimals + 1 || 3,
    sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'],
    i = Math.floor(Math.log(bytes) / Math.log(k))

  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i]
}

export function useLocaleTimeAgo(date: Date) {
  const I18N_MESSAGES: UseTimeAgoMessages<UseTimeAgoUnitNamesDefault> = {
    justNow: t('timeAgo.just-now'),
    past: (n) => (n.match(/\d/) ? t('timeAgo.ago', [n]) : n),
    future: (n) => (n.match(/\d/) ? t('timeAgo.in', [n]) : n),
    month: (n, past) =>
      n === 1
        ? past
          ? t('timeAgo.last-month')
          : t('timeAgo.next-month')
        : `${n} ${t('timeAgo.month', n)}`,
    year: (n, past) =>
      n === 1
        ? past
          ? t('timeAgo.last-year')
          : t('timeAgo.next-year')
        : `${n} ${t('timeAgo.year', n)}`,
    day: (n, past) =>
      n === 1
        ? past
          ? t('timeAgo.yesterday')
          : t('timeAgo.tomorrow')
        : `${n} ${t('timeAgo.day', n)}`,
    week: (n, past) =>
      n === 1
        ? past
          ? t('timeAgo.last-week')
          : t('timeAgo.next-week')
        : `${n} ${t('timeAgo.week', n)}`,
    hour: (n) => `${n} ${t('timeAgo.hour', n)}`,
    minute: (n) => `${n} ${t('timeAgo.minute', n)}`,
    second: (n) => `${n} ${t('timeAgo.second', n)}`,
    invalid: '',
  }

  return useTimeAgo(date, {
    fullDateFormatter: (date: Date) => date.toLocaleDateString(),
    messages: I18N_MESSAGES,
  })
}

export const useCsrf = document.head.querySelector(
  'meta[name="csrf-token"]',
).content

export const useDate = (
  date: Date = new Date(),
  type: 'date' | 'time' | 'datetime' = 'date',
) => {
  let dateSeparator = `${date.getFullYear()}-${
    date.getMonth() < 10 ? `0${date.getMonth()}` : date.getMonth()
  }-${date.getDay() < 10 ? `0${date.getDay()}` : date.getDay()}`
  let timeSeparator = `${date.getHours()}:${
    date.getMinutes() < 10 ? `0${date.getMinutes()}` : date.getMinutes()
  }:${date.getSeconds() < 10 ? `0${date.getSeconds()}` : date.getSeconds()}`

  switch (type) {
    case 'date':
      return dateSeparator
    case 'time':
      return timeSeparator
    default:
      return `${dateSeparator} ${timeSeparator}`
  }
}

export const addDays = (date: Date = new Date(), days: number = 1) =>
  date.setDate(date.getDate() + days)

export const eventDay = computed(() => {
  let currentTime = useNow()
  let hrs: number = currentTime.value.getHours()

  switch (true) {
    default:
      return t('dayEvents.good-morning')
    case hrs >= 10 && hrs < 12:
      return t('dayEvents.good-time')
    case hrs >= 12 && hrs < 15:
      return t('dayEvents.good-lunch')
    case hrs >= 15 && hrs < 19:
      return t('dayEvents.good-evening')
    case hrs >= 19:
      return t('dayEvents.good-night')
  }
})

export const useJsonParser = (data: string | any): any =>
  _.isString(data) ? JSON.parse(data) : data
