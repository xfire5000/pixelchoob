import type { LoDashStatic } from 'lodash'

declare global {
  const _: LoDashStatic
  const JQuery: JQueryStatic
}
