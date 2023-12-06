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
    viewed?: 1 | 0
    created_at?: Date
    updated_at?: Date
    deleted_at?: Date
  }

  declare type IRoleItem = {
    name: string
    title: string
    id?: number
  }

  declare type IUserItem = {
    id?: number
    name: string
    email: string
    password?: string
    email_verified_at?: Date
    roles?: IRoleItem[]
    profile_photo_url: string
    contacts?: IUserItem[]
  }
}
