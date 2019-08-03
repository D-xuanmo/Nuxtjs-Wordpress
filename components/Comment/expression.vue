<template>
  <div v-show="isShow" class="expression-wrapper" ref="expression">
    <ul class="expression-list">
      <li v-for="(tabs, index) in expressionList" :key="index" v-show="active === index" class="item">
        <a
          href="javascript:;"
          v-for="(item, index) in tabs.list"
          :key="index"
          :title="item.title"
          @click.stop="choose(`[${item.code}]`)">
          <img :src="item.url" :alt="item.title" width="20">
        </a>
      </li>
    </ul>
    <ul class="tabs-wrapper">
      <li
        v-for="(tabs, index) in expressionList"
        :key="index"
        :class="['tabs-item', active === index && 'is-active']"
        @click.stop="active = index"
      >
        {{ tabs.text }}
      </li>
    </ul>
  </div>
</template>
<script>
import { mapState } from 'vuex'
export default {
  name: 'Expression',
  props: {
    isShow: Boolean
  },
  computed: {
    ...mapState('comment', ['expressionList'])
  },
  data () {
    return {
      active: 0
    }
  },
  mounted () {
    document.body.addEventListener('click', this.close, false)
  },
  beforeDestroy () {
    document.body.removeEventListener('click', this.close, false)
  },
  methods: {
    choose (v) {
      this.$emit('on-change', {
        type: 'insert',
        value: v
      })
    },
    close () {
      this.isShow && this.$emit('on-change', {
        type: 'close'
      })
    }
  }
}
</script>
<style lang="scss" scoped>
// 表情容器
.expression-wrapper {
  box-sizing: border-box;
  position: absolute;
  top: 30px;
  left: 0;
  z-index: 2;
  width: 410px;
  max-width: 100%;
  height: 300px;
  background: $color-white;
  box-shadow: $box-shadow;
  border-radius: $border-radius;

  &:after {
    content: "";
    display: inline-block;
    width: 100%;
  }

  .expression-list {
    height: calc(100% - 40px);
    padding: 10px;
    box-sizing: border-box;
    overflow-y: scroll;
    -webkit-overflow-scrolling: touch;
  }

  .tabs-wrapper {
    // position: absolute;
    // bottom: 0;
    display: flex;
    align-items: center;
    width: 100%;
    height: 40px;
    padding: 0 10px;
    box-sizing: border-box;
    border-top: 2px solid $color-border;
    line-height: 40px;

    .tabs-item {
      padding: 0 10px;
      border-right: 2px solid $color-border;
      cursor: pointer;

      &.is-active {
        color: $color-theme;
      }
    }
  }

  a {
    display: inline-block;
    width: 40px;
    height: 40px;
    margin: 4px;
    border-radius: $border-radius;
    background: $color-sub-background;
    text-align: center;
    line-height: 40px;
  }

  img {
    vertical-align: middle;
  }
}
</style>
