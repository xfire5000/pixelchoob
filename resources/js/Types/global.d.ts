import { type } from './auto-imports.d'

declare global {
  declare type IBreadCrumbItem = {
    title: string
    link?: string
    icon?: string
  }

  declare type IMenuItem = {
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
    pvc: { reduce_thickness: boolean; size: '1' | '2'; color_code: string }
    stock: {
      sizes: { w: number; h: number }
      qty: number
      color: string
      pattern: boolean
      material: string
    }
    archived: 1 | 0
    viewed?: 1 | 0
    created_at?: Date
    updated_at?: Date
    deleted_at?: Date
    author?: IUserItem
    list_items?: listItems[]
    invoice?: IInvoiceItem
    ticket?: ITicketItem
  }

  declare type IRoleItem = {
    name: string
    title: string
    id?: number
  }

  declare type IAddressInfo = {
    id?: number
    user_id?: number
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
    profile_photo_path?: string
    contacts?: IUserItem[]
    address_infos?: IAddressInfo[]
  }

  declare type IFourSections = {
    l1: boolean
    l2: boolean
    w1: boolean
    w2: boolean
  }

  declare type ITwoSections = {
    l: boolean
    w: boolean
  }

  declare type IListItem = {
    id?: number
    list_case_id?: number
    description?: string
    chamfer: IFourSections
    gazor_hinge: ITwoSections
    groove: ITwoSections
    pvc: IFourSections
    qty: number
    dimensions: { w: number; h: number }
    sortable?: number
  }

  declare type IInvoiceItem = {
    id?: number
    list_case_id?: number
    description?: string
    sumPVC?: number
    sumCutting?: number
    countParts?: number
    sumGroove?: number
    sumChamfers?: number
    cutting: number
    pvc: {
      size_1: number
      size_2: number
    }
    chamfer: number
    groove: number
  }

  declare type ISettingItem = {
    id?: number
    value?: string
    user_id?: number
  }

  declare type InvoicePriceSetting = {
    cutting: number
    pvc: {
      size_1: number
      size_2: number
    }
    chamfer: number
    groove: number
  }

  declare type ITicketItem = {
    id?: number
    UUID?: number
    user_id?: number
    title: string
    message?: string
    priority?: string
    status?: string
    is_resolved?: boolean
    is_locked?: boolean
    assigned_to?: number
    created_at?: Date
    updated_at?: Date
    messages?: ITicketMessageItem[]
    new_messages?: ITicketMessageItem[]
    new_messages_count?: number
  }

  declare type ITicketMessageItem = {
    id?: number
    user_id?: number
    ticket_id?: number
    message: string
    created_at?: Date
    updated_at?: Date
    user?: IUserItem
  }
}
