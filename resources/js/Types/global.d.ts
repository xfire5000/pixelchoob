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
    description?: string
    pvc: any | string
    stock: any | string
    archived: 1 | 0
    created_at?: Date
    updated_at?: Date
  }
}
