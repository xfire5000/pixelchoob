export const fourSections = defineComponent({
  props: {
    title: String,
    l1: Boolean,
    l2: Boolean,
    w1: Boolean,
    w2: Boolean,
    disabler: {
      default: { l1: false, l2: false, w1: false, w2: false },
      type: Object,
    },
  },
  setup(props, { emit }) {
    const { l1, l2, w1, w2 } = useVModels(props, emit)
    const ids = [
      (+new Date()).toString(),
      (+new Date()).toString(),
      (+new Date()).toString(),
      (+new Date()).toString(),
    ]

    return () => (
      <div class="hover:border-opacity-100 dark:border-white dark:border-opacity-20 dark:hover:border-opacity-80 flex flex-col items-center rounded-md border border-gray-600 border-opacity-30 p-2">
        <small>{props.title}</small>
        <div class="my-2 flex flex-row items-center justify-center gap-x-2">
          <div class="flex w-5 flex-col items-center gap-y-1">
            <label for={ids[0]}>L1</label>
            <input
              value={l1.value}
              onChange={() => (l1.value = !l1.value)}
              type="checkbox"
              disabled={props.disabler.l1}
            />
          </div>
          <div class="flex w-5 flex-col items-center gap-y-1">
            <label for={ids[1]}>L2</label>
            <input
              value={l2.value}
              onChange={() => (l2.value = !l2.value)}
              type="checkbox"
              disabled={props.disabler.l2}
            />
          </div>
          <div class="flex w-5 flex-col items-center gap-y-1">
            <label for={ids[2]}>W1</label>
            <input
              value={w1.value}
              onChange={() => (w1.value = !w1.value)}
              type="checkbox"
              disabled={props.disabler.w2}
            />
          </div>
          <div class="flex w-5 flex-col items-center gap-y-1">
            <label for={ids[3]}>W2</label>
            <input
              value={w2.value}
              onChange={() => (w2.value = !w2.value)}
              type="checkbox"
              disabled={props.disabler.w2}
            />
          </div>
        </div>
      </div>
    )
  },
})

export const twoSections = defineComponent({
  props: {
    title: String,
    l: Boolean,
    w: Boolean,
    disabler: { default: { l: false, w: false }, type: Object },
  },
  setup(props, { emit }) {
    const { l, w } = useVModels(props, emit)
    const ids = [(+new Date()).toString(), (+new Date()).toString()]

    return () => (
      <div class="hover:border-opacity-100 dark:border-white dark:border-opacity-20 dark:hover:border-opacity-80 flex flex-col items-center rounded-md border border-gray-600 border-opacity-30 p-2">
        <small>{props.title}</small>
        <div class="my-2 flex flex-row items-center justify-center gap-x-2">
          <div class="flex w-5 flex-col items-center gap-y-1">
            <label for={ids[0]}>L</label>
            <input
              value={l.value}
              onChange={() => (l.value = !l.value)}
              type="checkbox"
              disabled={props.disabler.l}
            />
          </div>
          <div class="flex w-5 flex-col items-center gap-y-1">
            <label for={ids[1]}>W</label>
            <input
              value={w.value}
              onChange={() => (w.value = !w.value)}
              type="checkbox"
              disabled={props.disabler.w}
            />
          </div>
        </div>
      </div>
    )
  },
})
