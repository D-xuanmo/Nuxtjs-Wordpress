<template>
  <div class="container">
    <h2 class="title">
      <span>标签云</span>
    </h2>
    <ul class="list-wrap">
      <li v-for="item in $store.state.globalConfig.tagCloud" :key="item.key" class="list" :class="`color-${Math.floor(Math.random() * 8) + 1}`">
        <router-link :to="{ name: 'tags-id', params: { id: 1 }, query: { type: item.term_id, title: item.name } }">{{ item.name }}({{ item.count }})</router-link>
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'Page',
  layout: 'page',
  head () {
    return {
      title: `标签云 | ${this.$store.state.globalConfig.blogName}`
    }
  }
}
</script>

<style lang="scss" scoped>
.container{
  padding: $container-padding;
  background: var(--color-sub-background);
  border-radius: $border-radius;

  .title{
    margin-bottom: 30px;
    font-size: 20px;
    text-align: center;

    span{
      display: inline-block;
      color: var(--color-primary);

      &:after{
        content: "";
        display: block;
        width: 20px;
        height: 2px;
        margin: 5px auto 0;
        background: var(--color-primary);
      }
    }
  }

  .list-wrap{
    display: flex;
    flex-wrap: wrap;
  }

  .list{
    margin: 0 10px 10px 0;
    padding: 5px 10px;
    border-radius: $border-radius;

    a{
      color: $color-white;
    }

    &.color-1{
      background: #f3a683;
    }

    &.color-2{
      background: #778beb;
    }

    &.color-3{
      background: #e77f67;
    }

    &.color-4{
      background: #f5cd79;
    }

    &.color-5{
      background: #0fb9b1;
    }

    &.color-6{
      background: #f8a5c2;
    }

    &.color-7{
      background: #596275;
    }

    &.color-8{
      background: #20bf6b;
    }
  }
}
</style>
