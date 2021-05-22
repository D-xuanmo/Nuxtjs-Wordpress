<template>
  <div class="upload-img-wrap">
    <div class="sub-upload-wrap align-center">
      <template v-if="!bShowDragWrap">
        <h2 class="title">插入图片</h2>
        <x-icon type="icon-close" @click.native.stop="_hideUpload"></x-icon>
        <div class="progress-wrap">
          <p class="text">上传进度：</p>
          <div class="current-pro">
            <div class="current" :style="`width:${currentProgress}%`"></div>
          </div>
          <div>{{ currentProgress }}%</div>
        </div>
        <div class="select-img">
          <input type="file" name="file" value="" ref="inpFile" accept="image/png,image/gif,image/jpeg" @change.stop="_preview($event)">
          <p class="mask">
            <span v-if="bFileMark"><x-icon type="icon-upload-img2"></x-icon>点击选择图片或者拖动图片到此窗口内</span>
            <template v-else>
              已选择：<img :src="previewUrl" alt="">
            </template>
          </p>
        </div>
        <div class="btn-wrap">
          <div v-show="!bFileMark" class="btn btn-upload" @click.stop="_uploadImg">上传文件</div>
          <div class="btn btn-insert" @click.stop="_insertImg">插入到文章</div>
        </div>
        <div class="result-img">
          <img :src="resultImgUrl">
        </div>
      </template>
      <div v-else class="drag-wrap">
        <h2 class="title">松开鼠标完成上传</h2>
      </div>
    </div>
    <!-- 遮罩层 -->
    <div class="model" @click.stop="_hideUpload"></div>
  </div>
</template>
<script>
import { mapState, mapActions } from 'vuex'
export default {
  name: 'Upload',
  data: () => ({
    currentProgress: 0,
    resultImgUrl: '',
    bFileMark: true,
    resFileName: '',
    bShowDragWrap: false,
    previewUrl: ''
  }),
  props: ['showChart'],
  computed: {
    ...mapState({
      contentUrl: state => state.info.contentUrl,
      templeteUrl: state => state.info.templeteUrl
    })
  },
  mounted () {
    // 拖拽上传文件
    const oUploadWrap = document.querySelector('.sub-upload-wrap')
    oUploadWrap.ondragenter = (e) => {
      e.preventDefault()
      this.bShowDragWrap = true
    }
    oUploadWrap.ondragover = (e) => {
      e.preventDefault()
    }
    oUploadWrap.ondrop = (e) => {
      e.preventDefault()
      const oReader = new FileReader()
      const oFile = e.dataTransfer.files[0]
      this.bShowDragWrap = false
      // 判断文件是会否为图片格式
      if (oFile.type.indexOf('image') === -1) {
        this.$message({
          title: '请上传正确的图片格式',
          type: 'warning'
        })
        this.bFileMark = true
      } else {
        oReader.readAsDataURL(oFile)
        oReader.onload = async () => {
          const data = new FormData()
          this.previewUrl = oReader.result
          this.bFileMark = false
          data.append('postID', this.$route.params.id)
          data.append('file', oReader.result)
          data.append('url', this.contentUrl)
          data.append('mark', 'upload')
          try {
            // 上传实时进度
            const config = {
              onUploadProgress: progressEvent => (this.currentProgress = parseInt(progressEvent.loaded / progressEvent.total * 100))
            }
            const response = await this.uploadImage({
              requestData: data,
              config
            })
            if (!response.code) {
              this.$message({
                title: '上传失败！',
                type: 'error'
              })
            } else {
              this.resultImgUrl = response.path
              this.resFileName = response.name
            }
          } catch (error) {
            if (error === 404) {
              this.$message({
                title: '上传失败(404)！',
                type: 'error'
              })
            }
          }
        }
      }
    }
  },
  methods: {
    ...mapActions(['uploadImage', 'deleteImage']),
    _preview (event) {
      const oReader = new FileReader()
      oReader.readAsDataURL(event.target.files[0])
      oReader.onload = () => (this.previewUrl = oReader.result)
      this.bFileMark = false
    },

    // 上传图片
    async _uploadImg () {
      const _file = this.$refs.inpFile
      if (_file.value) {
        const data = new FormData()
        if (_file.files[0].size / 1024 > 2048) {
          this.$message({
            title: '请上传小于2M的图片！',
            type: 'error'
          })
        } else {
          data.append('postID', this.$route.params.id)
          data.append('file', _file.files[0])
          data.append('url', this.contentUrl)
          data.append('mark', 'upload')
          try {
            // 上传实时进度
            const config = {
              onUploadProgress: progressEvent => (this.currentProgress = parseInt(progressEvent.loaded / progressEvent.total * 100))
            }
            const response = await this.uploadImage({
              requestData: data,
              config
            })
            if (!response.code) {
              this.$message({
                title: '上传失败！',
                type: 'error'
              })
            } else {
              this.resultImgUrl = response.path
              this.resFileName = response.name
              _file.value = ''
            }
          } catch (error) {
            if (error === 404) {
              this.$message({
                title: '上传失败(404)！',
                type: 'error'
              })
            }
          }
        }
      }
    },

    // 隐藏上传控件
    async _hideUpload () {
      const data = new FormData()
      data.append('mark', 'close')
      data.append('url', this.contentUrl)
      data.append('postID', this.$route.params.id)
      data.append('fileName', this.resFileName)
      // 如果本次上传的图片未发表就从服务器删除此图片
      try {
        await this.deleteImage(data)
      } catch (error) {
        this.$message({
          title: error,
          type: 'error'
        })
      }
      // 关闭控件
      this.$emit('showChart', {
        close: false,
        resFileName: this.resFileName
      })
      this.currentProgress = 0
      this.resultImgUrl = ''
      this.bFileMark = true
    },

    // 插入到文章
    _insertImg () {
      this.$emit('updateContent', ` [img]${this.resultImgUrl}[/img] `)
      this.$emit('showChart', {
        close: false,
        resFileName: this.resFileName
      })
      this.currentProgress = 0
      this.resultImgUrl = ''
      this.bFileMark = true
    }
  }
}
</script>
<style lang="scss" scoped>
// 评论上传图片
.upload-img-wrap {
  position: fixed;
  top: 0;
  left: 0;
  z-index: 999;
  width: 100%;
  height: 100%;

  .model {
    position: absolute;
    top: 0;
    left: 0;
    z-index: -1;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,.7);
  }

  .sub-upload-wrap {
    box-sizing: border-box;
    position: absolute;
    top: 50%;
    left: 50%;
    width: 70%;
    min-height: 220px;
    padding: 10px;
    border-radius: $border-radius;
    background: var(--color-main-background);
    transform: translate(-50%,-50%);
  }

  // 拖拽上传容器
  .drag-wrap {
    height: 200px;
    border: 2px dashed var(--color-border);
    line-height: 200px;
  }

  .title {
    font-size: 20px;
    font-weight: lighter;
  }

  .icon-close {
    position: absolute;
    top: 10px;
    right: 10px;
    cursor: pointer;
  }

  // 进度条
  .progress-wrap {
    display: flex;
    align-items: center;
  }

  .current-pro {
    width: 80%;

    .current {
      width: 0px;
      height: 5px;
      border-radius: $border-radius;
      background-color: $color-theme;
      background-image: -webkit-linear-gradient(45deg, rgba(255, 255, 255, .3) 25%, transparent 25%, transparent 50%, rgba(255, 255, 255, .3) 50%, rgba(255, 255, 255, .3) 75%, transparent 75%, transparent);
    }
  }

  // 选择图片
  .select-img {
    position: relative;
    height: 50px;
    margin: 10px 0;
    border: 2px dashed var(--color-border);
    line-height: 50px;

    .mask,
    .iconfont {
      font-size: 18px;
    }

    .mask {

      span {
        overflow: hidden;
        display: block;
        text-overflow: ellipsis;
        white-space: nowrap;

        &.inline-block {
          display: inline-block;
          vertical-align: middle;
        }
      }

      img {
        max-width: 70px;
        max-height: 50px;
        vertical-align: middle;
      }
    }

    input {
      opacity: 0;
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      cursor: pointer;
    }
  }

  .result-img {
    img {
      max-width: 300px;
      max-height: 350px;
    }
  }

  .btn-wrap {
    display: flex;
    justify-content: space-between;
    width: 300px;
    margin: 0 auto;
    .btn {
      width: 100px;
      margin: 15px auto;
      border-radius: $border-radius;
      background: $color-theme;
      color: $color-white;
      cursor: pointer;
    }
  }
}
@media screen and (max-width: 767px) {
  .upload-img-wrap {
    .sub-upload-wrap {
      width: 90%;
    }
    .progress-wrap {
      flex-wrap: wrap;
      .text {
        width: 100%;
        text-align: left;
      }
      .current-pro {
        width: 90%;
      }
    }
    .result-img {
      img {
        max-width: 200px;
      }
    }
  }
}
@media screen and (max-height: 500px) {
  .upload-img-wrap {
    .result-img {
      img {
        max-width: 100px;
      }
    }
  }
}
</style>
