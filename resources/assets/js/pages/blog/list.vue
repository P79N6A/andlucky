<template>
 <div class="content" ref="listcontainer">
    <!-- 这里是页面内容区 -->
    <div class="content-block" style="padding:0rem;margin-top:0rem;">
      <div class="card facebook-card" style="margin:.5rem;" v-for="(item , index) in blogs" >
        <router-link :tag="div" :to="{name:'microblog-show' , params:{ id : item.id }}" class="blog-title">
          {{item.title}}
        </router-link>
        <div :to="{name:'microblog-space' , params:{ id : item.user.id }}" class="card-header no-border">
            <div class="facebook-avatar img-round">
                <img :src="avatar( item.user.avatar )"  width="34" height="34" />
            </div>
            <div class="facebook-name">{{ item.user.nickname ? item.user.nickname : '狗运'}}&nbsp;</div>
            <div class="facebook-date">{{item.created_at}}&nbsp;</div>
        </div>
        <router-link :to="{name:'microblog-show' , params:{ id : item.id }}" class="card-content">
            <div class="content-body content-padded" v-html="more( item.content )" >
                
            </div>
            
            <div v-if="item.extra.type =='image' && item.extra.data instanceof Array " class="content-body" >
              <div class="row">
              <div class="col-33 thumbs" v-for="( img , i ) in item.extra.data" :style="'background-image:url(' + img + ')'">
              </div>
              </div>
            </div>
        </router-link>
        <div class="card-footer">
          <a ><i class="iconfont icon-conversation_icon "></i>&nbsp;{{item.comment_count}}</a>
            <a 
                class="praise-btn"
                :class="{'has_praise' : item.praises_count }"
                data-type="microblog" 
            ><i class="iconfont icon-dislike"></i>&nbsp;<i class="blog-praise">{{item.prase}}</i></a>
            <router-link 
              :to="{name:'microblog-show' , params:{ id : item.id }}" 
              ><i class="iconfont icon-chakan1"></i>&nbsp;{{item.views}}
            </router-link>
        </div>
  </div>
  <infinite-loading @infinite="loadMore" ref="infiniteLoading">
    <span slot="no-more">没有更多数据了</span>
    <span slot="no-result">没有更多数据了</span>
  </infinite-loading>
    </div>
     
</div>
</template>

<script>
import InfiniteLoading from 'vue-infinite-loading'
import Axios from "axios"
export default {
  data:function(){
    return {
      title:'' ,
      blogs : [] ,
      blogCate : [] ,
      page : 1 ,
      cate_id : 0 
    }
  } ,
  created:function(){
    this.blogCate = window.blogCate 
    this.cate_id = this.$route.query.id || this.blogCate[0].id 
    this.title = this.blogCate[ this.cate_id ]
    //localStorage.setItem('scroll_' + this.cate_id , 0 ) 
  },
  mounted:function(){
    //document.querySelector('.content').scroll( 0 , window._scroll )
    
  },
  deactivated:function(){
    let that = this
  },
  activated:function(){
    console.log( 'aaa' )
    console.log(  localStorage.getItem('scroll_' + this.cate_id ) )
    document.querySelector('.content').scroll( 0 , localStorage.getItem('scroll_' + this.cate_id ) )
    
  },
  methods :{
    more:function( content ){
      return content.length < 50 ? content : content.substr(0 , 50 ) + '...[查看详细]' ;
    },
    loadMore:function( $state ){
      var that = this ;
      Axios.get('/microblog' + 
        '?_token='+  window._Token + 
        '&cate_id=' + that.cate_id + 
        '&keyword=' + that.keyword + 
        '&api_token=' + window.apiToken + 
        '&page=' + that.page
      ).then(function(response) {
        //console.log( that );
        $state.loaded();
        let data = response.data ;
        //如果页码为1 则重置数据
        if( that.page == 1 ) {
          that.blogs = [] 
        }
        if( data.errcode === 0 ) {
          let list = data.data 
          if( list.total < that.page * 10 ) {
            $state.complete();
          }
          for( var i = 0 ; i < list.data.length ; i ++ ) {
            list.data[i].user = list.data[i].user ? list.data[i].user : {'id':0 , 'avatar' : '' , 'nickname' : ''}
            that.blogs.push( list.data[i] ) ;
          }
          that.page++ ;
        } else {
          $state.complete();
        }
      });
    } ,
    avatar:function( v ) {
      return v ? v : '/images/logo.png'
    }
  } ,
  watch:{
    $route:function( o , n ){
      let that = this
      that.cate_id = o.query.id || that.blogCate[0].id 
      that.page = 1 
      that.blogs = [] 
      that.title = that.blogCate[ that.cate_id ]
      //有变动
      this.$nextTick(() => {
            this.$refs.infiniteLoading.$emit('$InfiniteLoading:reset');
      });
      document.querySelector('.content').scroll( 0 , localStorage.getItem('scroll_' + that.cate_id ) )
      
    } ,
  },
  components:{
    InfiniteLoading 
  }
};
</script>

<style scoped>
.thumbs {
  height: 5.5rem;
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover ;
  border:#f7f7f7 solid 1px;
}
.index_title {
  background: url('/images/dog_header.png') no-repeat ;
  background-size:2.2rem 2.2rem;
  padding-left: 3rem;
  height:2.2rem;
  display: inline-block;
  line-height: 2.2rem;
  font-size: .8em;
  color:#e8cc40;
}
.blog-title {
  font-size:1rem;
  padding: 0.5rem 0.5rem;
  color:#000;
  display: block;
}
.bar .button {
  top:0rem;
}
.theme-coffee .bar .active, .theme-coffee .bar-tab .active {
  color:#3d3f40;
}
.buttons-tab .button {
  font-size:.85rem;
}
.card {
  font-size:.8rem;
}
</style>
