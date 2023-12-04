import { type } from './auto-imports.d'

declare global {
  declare type IBreadCrumbItem = {
    title: string
    link?: string
    icon?: string
  }

  declare type IListCaseItem = {
    id?: number
    user_id?: number
    author_id?: number
    title: string
    slug?: string
    description?: string
    pvc: string
    stock: string
    archived: 1 | 0
  }
}
