import userHelpers from '@/helpers/user-helpers'
import apiHelpers from '@/helpers/api-helpers'
import formatHelpers from '@/helpers/format-helpers'

export default {
  install: (Vue) => {
    Vue.prototype.userHelpers = userHelpers
    Vue.userHelpers = userHelpers

    Vue.prototype.apiHelpers = apiHelpers
    Vue.apiHelpers = apiHelpers

    Vue.prototype.formatHelpers = formatHelpers
    Vue.formatHelpers = formatHelpers
  }
}
