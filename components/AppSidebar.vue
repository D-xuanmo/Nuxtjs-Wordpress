<template>
  <div>
    <!-- 公告栏 -->
    <div class="sidebar-list notice">
      <div class="header">
        <p>
          <x-icon type="icon-notice2"></x-icon> 公告
        </p>
      </div>
      <div class="content" v-html="sidebar.extra.sidebar_notice"></div>
    </div>
    <!-- 最新评论 -->
    <div class="sidebar-list comment">
      <div class="header">
        <p>
          <x-icon type="icon-hot1"></x-icon> 最新评论
        </p>
      </div>
      <ul class="content">
        <li class="list" v-for="item in sidebar.newComment" :key="item.key">
          <template>
            <img v-if="$store.state.info.isTextThumbnail === 'off'" :src="item.avatar" class="thumbnail" width="50" height="50" alt="">
            <p v-else-if="$store.state.info.isTextThumbnail === 'on'" class="thumbnail-text" :style="{ background: item.background }">{{ item.comment_author.substr(0, 1) }}</p>
          </template>
          <div class="right">
            <h3 class="author">{{ item.comment_author }}</h3>
            <p class="comment-text" v-html="item.comment_content.replace(/\[img\]\S+\[\/img\]/, '[图片]')"></p>
            <nuxt-link :to="{ name: 'details-id', params: { id: item.comment_post_ID } }" class="block title">评：{{ item.title }}</nuxt-link>
          </div>
        </li>
      </ul>
    </div>
    <!-- 站点统计 -->
    <div class="sidebar-list count" v-if="sidebar.extra.aside_count === 'on'">
      <div class="header">
        <p>
          <x-icon type="icon-count"></x-icon> 站点统计
        </p>
      </div>
      <ul class="content">
        <li class="list">标签：{{ sidebar.getAllCountTag }}个</li>
        <li class="list">文章：{{ sidebar.getAllCountArticle }}篇</li>
        <li class="list">页面：{{ sidebar.getAllCountPage }}个</li>
        <li class="list">评论：{{ sidebar.getAllCountComment }}条</li>
        <li class="list">分类：{{ sidebar.getAllCountCat }}个</li>
        <li class="list">最后更新：{{ sidebar.lastUpDate }}</li>
      </ul>
    </div>
    <!-- 标签云 -->
    <div class="sidebar-list tag-cloud">
      <div class="header">
        <p>
          <x-icon type="icon-tag1"></x-icon> 标签云
        </p>
        <router-link :to="{ name: 'tags' }">更多</router-link>
      </div>
      <ul class="content">
        <li v-for="(item, index) in sidebar.tagCloud" :key="item.key" v-if="index < 20" class="list" :class="`color-${Math.floor(Math.random() * 8) + 1}`">
          <router-link :to="{ name: 'tags-id', params: { id: 1 }, query: { type: item.term_id, title: item.name } }">{{ item.name }}</router-link>
        </li>
      </ul>
    </div>
  </div>
</template>
<script>
export default {
  name: 'AppSidebar',
  computed: {
    sidebar () {
      return this.$store.state.info
    }
  }
}
</script>
<style lang="scss" scoped>
.sidebar-list{
  margin-top: $container-margin;
  padding: 10px;
  border-radius: $border-radius;
  background: $color-white;

  &:first-of-type{
    margin-top: 0;
  }

  .header{
    margin-bottom: 5px;
    padding-bottom: 5px;
    border-bottom: 1px solid $color-border;

    p{
      position: relative;
      display: inline-block;

      .iconfont{
        font-size: $font-size-large;
      }

      &:after{
        content: "";
        position: absolute;
        bottom: -6px;
        left: 0;
        width: 100%;
        height: 2px;
        background: $color-theme;
      }
    }
  }

  &.notice{
    .content{
      line-height: 2;
    }
  }

  // 评论列表
  &.comment{
    .list{
      display: flex;
      // align-items: center;
      padding: 8px 0;
      border-bottom: 1px dashed $color-main-background;

      &:last-of-type{
        border: 0;
      }
    }

    .right{
      width: 200px;
    }

    .thumbnail{
      margin-right: 10px;
      border-radius: $border-radius;
    }

    .thumbnail-text{
      width: 50px;
      height: 50px;
      margin-right: 10px;
      border-radius: $border-radius;
      font-size: 28px;
      text-align: center;
      line-height: 50px;
      text-transform: uppercase;
      color: #fff;
    }

    .author{
      height: 20px;
      color: $color-theme;
    }

    .comment-text{
      max-height: 34px;
      font-size: $font-size-small;
      @include ellipsisMultiline(2);
    }

    .title{
      @include ellipsisMultiline(2);
      color: $color-theme;
    }
  }

  // 站点统计
  &.count{
    .content{
      display: flex;
      flex-wrap: wrap;
    }

    .list{
      margin-top: 10px;

      &:nth-of-type(odd){
        width: 38%;
      }

      &:nth-of-type(even){
        width: 60%;
      }
    }
  }

  // 标签云
  &.tag-cloud{
      .header{
        display: flex;
        justify-content: space-between;
      }

    .content{
      display: flex;
      flex-wrap: wrap;
    }

    .list{
      margin: 10px 5px 0 0;
      padding: 3px 6px;
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
}

</style>
