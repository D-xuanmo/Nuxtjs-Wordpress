<template>
  <div class="sidebar-wrap">
    <!-- 公告栏 -->
    <div class="sidebar-list notice">
      <div class="header">
        <p>
          <i class="iconfont icon-notice2"></i> 公告
        </p>
      </div>
      <div class="content" v-html="sidebar.setExtend.sidebar_notice"></div>
    </div>
    <!-- 最新评论 -->
    <div class="sidebar-list comment">
      <div class="header">
        <p>
          <i class="iconfont icon-hot1"></i> 最新评论
        </p>
      </div>
      <ul class="content">
        <li class="list" v-for="item in sidebar.newComment" :key="item.key">
          <img :src="item.avatar" class="thumbnail" width="50" height="50" alt="">
          <div class="right">
            <h3 class="author">{{ item.comment_author }}</h3>
            <p class="comment-text" v-html="item.comment_content"></p>
            <nuxt-link to="/" class="block title">评：{{ item.title }}</nuxt-link>
          </div>
        </li>
      </ul>
    </div>
    <!-- 站点统计 -->
    <div class="sidebar-list count">
      <div class="header">
        <p>
          <i class="iconfont icon-count"></i> 站点统计
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
  </div>
</template>
<script>
export default {
  name: 'AppSidebar',
  computed: {
    sidebar () {
      let result = this.$store.state.info
      result.newComment.map(item => (item.comment_content = item.comment_content.replace(/\[img\]\S+\[\/img\]/, '[图片]')))
      return result
    }
  }
}
</script>
<style lang="scss" scoped>
.sidebar-list{
  background: $color-white;
  margin-top: $container-margin;
  padding: 10px;

  &:first-of-type{
    margin-top: 0;
  }

  .header{
    margin-bottom: 5px;
    padding-bottom: 5px;
    border-bottom: 1px solid $color-main-background;

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
        background: $color-highlight-text;
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

    .author{
      color: $color-highlight-text;
    }

    .comment-text{
      font-size: $font-size-small;
      @include ellipsisMultiline(2);
    }

    .title{
      @include ellipsisMultiline(2);
      color: $color-highlight-text;
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
        width: 40%;
      }

      &:nth-of-type(even){
        width: 58%;
      }
    }
  }
}

</style>
