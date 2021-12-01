<template>
  <div class="comment-list-item__author">
    <a :href="author.site" class="comment-list-item__author-name vertical-middle" target="_blank">{{ author.name }}</a>
    <img
      v-if="!isHideLevel"
      :src="images[author.level]"
      class="icon-vip icon-level comment-list-item__author-level vertical-middle"
      :class="[author.style, author.level]"
      :title="author.levelTitle"
      :alt="author.levelTitle"
    />
    <svg-icon v-if="author.isAdmin" iconName="icon-vip" class="vertical-middle" title="博主" />
  </div>
</template>

<script>
export default {
  name: 'CommentListItemAuthor',

  props: {
    author: {
      type: Object,
      required: true,
      defaults: () => ({})
    },

    isHideLevel: {
      type: Boolean,
      default: false
    }
  },

  data() {
    const images = {}
    for (let i = 1; i < 8; i++) {
      images[`vip${i}`] = require(`../../assets/images/vip-style-1-vip${i}.png`)
    }
    return {
      images
    }
  }
}
</script>
