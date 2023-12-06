import { router } from '@inertiajs/vue3'
import { StatusCodes } from 'http-status-codes'
import { toast } from 'vue3-toastify'
import route from 'ziggy-js'
import type { RouteName, RouteParams } from 'ziggy-js'

export const useBack = (routeName: string) => router.get(route(routeName))

export const useSearch = (routeName: string, text: string, query?: any) =>
  router.get(route(routeName, { _query: _.merge({ s: text }, query) }))

export const useDelete = (routeName: string, id: any) =>
  new Promise((resolve, reject) =>
    useFetchClient
      .delete(route(routeName, id))
      .then(({ data, error, statusCode }) => {
        if (statusCode.value === StatusCodes.OK) {
          toast(data.value['msg'], { type: 'success' })
          resolve(data.value)
        } else reject(error.value)
      }),
  )

export const useShow = (routeName: string, param: RouteParams<RouteName>) =>
  new Promise((resolve, reject) =>
    useFetchClient.get(route(routeName, param)).then(({ data, error }) => {
      if (data.value) resolve(data.value)
      else reject(error.value)
    }),
  )

export const useChangeRoute = (routeName: string, id: number) =>
  useFetchClient.get(route(routeName, id))
