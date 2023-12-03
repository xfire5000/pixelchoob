import { router } from '@inertiajs/vue3'
import { toast } from 'vue3-toastify'
import route from 'ziggy-js'
import type { RouteName, RouteParams } from 'ziggy-js'

export const useBack = (routeName: string) => router.get(route(routeName))

export const useSearch = (routeName: string, text: string, query?: any) =>
  router.get(route(routeName, { _query: _.merge({ s: text }, query) }))

export const useDelete = (routeName: string, id: any) =>
  new Promise((resolve, reject) =>
    useFetchClient.delete(route(routeName, id)).then(({ data, error }) => {
      console.log(data.value)

      if (data.value) {
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

export const useChangeTaskStatus = (status: boolean = true) =>
  useTaskApi.changeTaskStatus(status).then(({ data }) => {
    toast(data.value['msg'], { type: 'info' })
  })
