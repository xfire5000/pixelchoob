import { mdiCheck } from '@mdi/js'
import { toast } from 'vue3-toastify'
import {
  VBtn,
  VCol,
  VDivider,
  VRow,
  VTextField,
} from 'vuetify/lib/components/index.mjs'
import route from 'ziggy-js'

export const InvoicesPrice = defineComponent({
  setup() {
    interface InvoicePriceProps {
      cutting: number
      pvc: { size_1: number; size_2: number }
      chamfer: number
      groove: number
    }

    const settings = ref<InvoicePriceProps>({
      cutting: 0,
      pvc: {
        size_1: 0,
        size_2: 0,
      },
      chamfer: 0,
      groove: 0,
    })
    const fetchSettings = () =>
      useFetchClient
        .get<string>(route('settings.show', 'invoices_price'))
        .then(({ data }) => (settings.value = useJsonParser(data.value)))
    onMounted(() => fetchSettings())
    const { t } = useI18n()
    const submit = () =>
      useFetchClient
        .post<{ msg: string }>(
          route('settings.store', { _query: { type: 'invoices_price' } }),
          settings.value,
        )
        .then(({ data }) => toast(data.value.msg, { type: 'success' }))

    return () => (
      <VRow class="m-2" dense>
        <VCol cols="12">
          <h6 class="dark:text-white mb-2">{t('pvc')}</h6>
        </VCol>
        <VCol cols="12" md="3">
          <VTextField
            suffix={t('currency')}
            modelValue={settings.value.pvc.size_1}
            hideDetails="auto"
            onUpdate:modelValue={(args: string) =>
              (settings.value.pvc.size_1 = parseInt(args))
            }
            label={t('pvc-price', { name: t('pvc-size.1mm') })}
            type="number"
          />
        </VCol>
        <VCol cols="12" md="3">
          <VTextField
            suffix={t('currency')}
            modelValue={settings.value.pvc.size_2}
            hideDetails="auto"
            onUpdate:modelValue={(args: string) =>
              (settings.value.pvc.size_2 = parseInt(args))
            }
            label={t('pvc-price', { name: t('pvc-size.2mm') })}
            type="number"
          />
        </VCol>
        <VDivider class="my-3" />
        <VCol cols="12" md="3">
          <h6 class="dark:text-white mb-2">{t('cutting')}</h6>
          <VTextField
            suffix={t('currency')}
            modelValue={settings.value.cutting}
            onUpdate:modelValue={(args: string) =>
              (settings.value.cutting = parseInt(args))
            }
            label={t('cutting-price')}
            type="number"
          />
        </VCol>
        <VCol cols="12" md="3">
          <h6 class="dark:text-white mb-2">{t('chamfer')}</h6>
          <VTextField
            suffix={t('currency')}
            modelValue={settings.value.chamfer}
            onUpdate:modelValue={(args: string) =>
              (settings.value.chamfer = parseInt(args))
            }
            label={t('chamfer-price')}
            type="number"
          />
        </VCol>
        <VCol cols="12" md="3">
          <h6 class="dark:text-white mb-2">{t('groove')}</h6>
          <VTextField
            suffix={t('currency')}
            modelValue={settings.value.groove}
            onUpdate:modelValue={(args: string) =>
              (settings.value.groove = parseInt(args))
            }
            label={t('groove-price')}
            type="number"
          />
        </VCol>
        <VCol cols="12">
          <div onClick={() => submit()}>
            <VBtn rounded="lg" color="primary" prependIcon={mdiCheck}>
              {t('submit-store')}
            </VBtn>
          </div>
        </VCol>
      </VRow>
    )
  },
})
