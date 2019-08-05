<template>
  <div class="container">
    <ul>
      <li v-for="(item, index) in list" :key="index" class="item">
        <img :src="info.adminPic" width="50" height="50" class="avatar">
        <template v-if="item.link">
          <a :href="item.link" class="block content">
            <div class="text" v-html="item.content"></div>
            <div class="date"><x-icon type="icon-time"></x-icon> {{ item.date }}</div>
          </a>
        </template>
        <template v-else>
          <div class="content">
            <div class="text" v-html="item.content"></div>
            <div class="date"><x-icon type="icon-time"></x-icon> {{ item.date }}</div>
          </div>
        </template>
      </li>
    </ul>
  </div>
</template>
<script>
import { mapState, mapActions } from 'vuex'
export default {
  name: 'Phrase',
  layout: 'page',
  computed: {
    ...mapState(['info']),
    ...mapState('phrase', ['list'])
  },
  created () {
    this.getPhraseList()
  },
  methods: {
    ...mapActions('phrase', ['getPhraseList'])
  }
}
</script>
<style lang="scss" scoped>
.container {
  position: relative;
  min-height: 400px;
  padding: $container-padding;
  border-radius: $border-radius;

  // 线条
  &:before {
    content: "";
    position: absolute;
    top: 0;
    left: 90px;
    width: 2px;
    height: 100%;
    background: #e0e0e0;
  }

  .item {
    position: relative;
    display: flex;

    &:not(:first-of-type) {
      margin-top: 30px;
    }

    // 圆点
    &:before {
      content: "";
      position: absolute;
      top: 25px;
      left: 76px;
      width: 16px;
      height: 16px;
      background: $color-theme;
      border-radius: 50%;
      transform: translate(-50%, -50%);
    }
    &:after {
      content: "";
      position: absolute;
      top: 25px;
      left: 76px;
      width: 8px;
      height: 8px;
      background: $color-theme;
      border: 3px solid $color-sub-background;
      border-radius: 50%;
      transform: translate(-50%, -50%);
    }

    .avatar {
      margin-right: 60px;
      border-radius: 50%;
    }

    &:hover {
      .avatar {
        transition: .5s;
        transform: rotateZ(360deg);
      }
      .content {
        box-shadow: $box-shadow;
        transform: translateY(-5px);
      }
    }

    &:nth-of-type(odd) {
      .content {
        background: #ff845d;

        &:before {
          border-color: transparent #ff845d transparent transparent;
        }
      }
    }
  }

  .content {
    position: relative;
    width: 100%;
    padding: 10px 20px;
    border-radius: 5px;
    background: #8696e2;
    color: #fff;
    transition: .3s;

    &:before {
      content: "";
      position: absolute;
      top: 15px;
      left: -20px;
      border: {
        width: 10px;
        style: solid;
        color: transparent #8696e2 transparent transparent;
      }
    }
  }

  .date {
    margin-top: 10px;
    padding-top: 10px;
    border-top: 1px dashed $color-border;
  }
}
</style>
