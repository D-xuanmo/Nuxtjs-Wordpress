<template>
  <div class="iconfont-container">
    <no-ssr>
      <ul class="list-wrap">
        <li v-for="item in iconList" :key="item.key" class="list-item">
          <x-icon :type="item"></x-icon>
          <p>iconfont</p>
          <p>{{ item }}</p>
        </li>
      </ul>
    </no-ssr>
  </div>
</template>
<script>
export default {
  name: 'Iconfont',
  layout: 'page',
  computed: {
    iconList () {
      let arr = []
      for (let key of Object.keys(this.$style)) {
        if (key.indexOf('icon-') !== -1) {
          arr.push(key)
        }
      }
      return arr
    }
  }
}
</script>
<style lang="scss" module>
@import "../../assets/scss/iconfont.scss";
</style>
<style lang="scss" scoped>
.list-wrap {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  flex-wrap: wrap;
  text-align: center;

  &:after {
    content: "";
    flex: auto;
  }

  .list-item {
    width: 150px;
    margin: 10px 0;
  }

  .iconfont {
    font-size: 26px;
  }
}
</style>
