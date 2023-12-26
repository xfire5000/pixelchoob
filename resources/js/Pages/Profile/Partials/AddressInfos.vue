<script setup lang="ts">
  import { StatusCodes } from 'http-status-codes'
  import route from 'ziggy-js'

  interface ITypes {
    title: string
    value: string
  }

  const address = reactive<{
    activeAddress?: IAddressInfo
    address: IAddressInfo[]
    phone: IAddressInfo[]
    mobile: IAddressInfo[]
  }>({
    activeAddress: null,
    address: [],
    phone: [],
    mobile: [],
  })

  const types = ref<ITypes[]>([])

  const fetchAddresses = () =>
    useFetchClient
      .get<{ items: IAddressInfo[]; types: ITypes[] }>(route('address.index'))
      .then(({ data, statusCode }) => {
        if (statusCode.value === StatusCodes.OK) {
          types.value = data.value.types
          address.activeAddress = data.value.items.find(
            (item) => item.isShow === 1,
          )
          data.value.items.forEach((item) => address[item.type].push(item))
        }
      })

  onMounted(() => fetchAddresses())

  const addressDialog = ref<boolean>(false)
  const addressType = ref<string>('')
  const addressItems = ref<IAddressInfo[]>([])

  function initAddressDialog(type: string) {
    addressDialog.value = true
    addressType.value = type
    addressItems.value = address[type]
  }

  const referToDeactivateShow = (item) => {
    address[addressType.value].forEach((element) => (element.isShow = 0))
    address.activeAddress = item
  }

  function onSubmittedItem(item: IAddressInfo) {
    if (item.isShow === 1) referToDeactivateShow(item)
    address[addressType.value].unshift(item)
  }

  function onUpdatedItem(item: IAddressInfo) {
    if (item.isShow === 1) referToDeactivateShow(item)
    let index = address[addressType.value].findIndex(
      (element) => element.id === item.id,
    )
    address[addressType.value][index] = item
  }

  const onDeletedItem = (id: number) =>
    _.remove(address[addressType.value], (item: IAddressInfo) => item.id === id)
</script>

<template lang="pug">
ActionSection
  template(#title) {{ $t('address.title') }}
  template(#description) {{ $t('address.desc') }}
  template(#content)
    .flex.flex-col
      span(v-if="!address.activeAddress") {{ $t('address.no-address') }}
      span(v-else) {{ $t('address.default-address', { name: address.activeAddress.description }) }}
      .mt-6.flex.flex-row.items-center.gap-x-2
        v-btn(
          @click="initAddressDialog(item.value)",
          rounded="lg",
          v-for="item in types",
          variant="outlined"
        ) {{ $t('add', { name: item.title }) }}
AddressDialog(
  :items="addressItems",
  :show="addressDialog",
  :type="addressType",
  :types,
  @close="addressDialog = false",
  @deleted="onDeletedItem",
  @submitted="onSubmittedItem",
  @updated="onUpdatedItem"
)
</template>
