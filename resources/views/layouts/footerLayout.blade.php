
<footer id="footer-nav">
        <div class="weui-flex">
            <div class="weui-flex__item f-tac active">
                <a href="/"  {!! \Illuminate\Support\Facades\Input::getPathInfo()=='/'?'class="active"':'' !!}>
                    <i class="iconfont"></i>
                    <div class="ftext">首页</div>
                </a>
            </div>
            <div class="weui-flex__item f-tac">
                <a href="/wj"  {!! \Illuminate\Support\Facades\Input::getPathInfo()=='/wj'?'class="active"':'' !!}>
                    <i class="iconfont "></i>
                    <div class="ftext">满天星</div>
                </a>
            </div>
            <div class="weui-flex__item f-tac">
                <a href="/sl"  {!! \Illuminate\Support\Facades\Input::getPathInfo()=='/sl'?'class="active"':'' !!}>
                    <i class="iconfont "></i>
                    <div class="ftext">心跳踩雷</div>
                </a>
            </div>
            <div class="weui-flex__item f-tac">
                <a href="/user" {!! \Illuminate\Support\Facades\Input::getPathInfo()=='/user'?'class="active"':'' !!} >
                    <i class="iconfont "></i>
                    <div class="ftext">我的</div>
                </a>
            </div>
        </div>
    </footer>
