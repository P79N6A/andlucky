<footer class="aui-bar aui-bar-tab menu" id="footer" style="z-index:777">
    <a class="aui-bar-tab-item {{ $foot == 'microblog' ? 'aui-active' :'' }}" href="{{route('microblog.index')}}" tapmode>
        <i class="aui-iconfont aui-icon-menu"></i>
        <div class="aui-bar-tab-label">资讯</div>
    </a>
    <a class="aui-bar-tab-item {{ $foot == 'game' ? 'aui-active' :'' }}" href="{{route('wap.guess.index')}}" tapmode>
        <i class="aui-iconfont aui-icon-star"></i>
        <div class="aui-bar-tab-label">游戏</div>
    </a>
    <div class="aui-bar-tab-item menu-popup {{ $foot == 'form' ? 'aui-active' :'' }}" aui-popup-for="menu-popup-bottom">
        <i class="aui-iconfont aui-icon-plus"></i>
        <div class="aui-bar-tab-label">发布</div>
    </div>
    <a class="aui-bar-tab-item {{ $foot == 'friends' ? 'aui-active' :'' }}" tapmode href="{{route('friends')}}">
        <i class="aui-iconfont aui-icon-comment"></i>
        <div class="aui-bar-tab-label">好友</div>
    </a>
    <a class="aui-bar-tab-item {{ $foot == 'member' ? 'aui-active' :'' }}" tapmode href="{{route('member.index')}}">
        <i class="aui-iconfont aui-icon-my"></i>
        <div class="aui-bar-tab-label">
            我的
        </div>
    </a>
</footer>

<div class="aui-popup aui-popup-bottom" id="menu-popup-bottom" style="display: none;">
    <div class="aui-popup-arrow"></div>
    <div class="aui-popup-content">
        <ul class="aui-list aui-list-noborder">
            <li class="aui-list-item">
                <div class="aui-list-item-inner aui-list-item-middle">
                    <a href="{{route('microblog.create')}}">资讯</a>
                </div>
            </li>
            <li class="aui-list-item">
                <div class="aui-list-item-inner">
                    <a href="{{route('adv.create')}}">广告</a>
                </div>
            </li>
        </ul>
    </div>
</div>