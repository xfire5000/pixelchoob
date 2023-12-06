import { type } from './auto-imports.d'

interface fourSection {
  l1: boolean
  l2: boolean
  w1: boolean
  w2: boolean
}

interface twoSection {
  l: boolean
  w: boolean
}

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
    author?: IUserItem
  }

  declare type IRoleItem = {
    name: string
    title: string
    id?: number
  }

  declare type IAddressInfo = {
    id?: number
    user_id: number
    type: string
    description: string
    isShow: 1 | 0
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
    addressInfos?: IAddressInfo[]
  }

  declare type IListItem = {
    id?: number
    list_case_id?: number
    description?: string
    chamfer: skyfourQuarter
    gazor_hinge: skytwoQuarter
    groove: skytwoQuarter
    pvc: skyfourQuarter
    qty: number
    dimensions: { w: number; h: number }
    sortable?: number
  }
}
