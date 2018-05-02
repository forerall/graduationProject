
@extends('layouts.backstageLogin')
@section('title', '登录')
@section('body')
    <div class="main-container login-layout">
        <div class="main-content">
            <div class="row">
                <div class="col-sm-10 col-sm-offset-1">
                    <div class="login-container">
                        <div class="center">
                            <h1>
                                <i class="icon-leaf green"></i>
                                <span class="red">{{ config('app.name', 'Laravel') }}</span>
                            </h1>
                            <h4 class="blue"></h4>
                        </div>
                        <div class="space-6"></div>
                        <div class="position-relative">
                            <div id="login-box" class="login-box visible widget-box no-border">
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <h4 class="header blue lighter bigger">
                                            <i class="icon-coffee green"></i>请输入用户名密码
                                        </h4>

                                        <div class="space-6"></div>
                                        @if (count($errors) > 0)
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        <form>
                                            {{ csrf_field() }}
                                            <fieldset>
                                                <label class="block clearfix">
                                                <span class="block input-icon input-icon-right">
                                                    <input type="text" name="name" class="form-control" placeholder="Username" value="{{old('username')}}"/>
                                                    <i class="icon-user"></i>
                                                </span>
                                                </label>

                                                <label class="block clearfix">
                                                <span class="block input-icon input-icon-right">
                                                    <input type="password" name="password" class="form-control" placeholder="Password" />
                                                    <i class="icon-lock"></i>
                                                </span>
                                                </label>

                                                <div class="space"></div>

                                                <div class="clearfix">
                                                    <label class="inline">
                                                        <input type="checkbox" class="ace" />
                                                        <span class="lbl"> 记住我</span>
                                                    </label>

                                                    <button type="button" class="width-35 pull-right btn btn-sm btn-primary post-login">
                                                        <i class="icon-key"></i>
                                                        登录
                                                    </button>
                                                </div>

                                                <div class="space-4"></div>
                                            </fieldset>
                                        </form>

                                    </div><!-- /widget-main -->

                                </div><!-- /widget-body -->
                            </div><!-- /login-box -->

                            <div id="forgot-box" class="forgot-box widget-box no-border">
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <h4 class="header red lighter bigger">
                                            <i class="icon-key"></i>
                                            Retrieve Password
                                        </h4>

                                        <div class="space-6"></div>
                                        <p>
                                            Enter your email and to receive instructions
                                        </p>

                                        <form>
                                            <fieldset>
                                                <label class="block clearfix">
                                                                            <span class="block input-icon input-icon-right">
                                                                                <input type="email" class="form-control" placeholder="Email" />
                                                                                <i class="icon-envelope"></i>
                                                                            </span>
                                                </label>

                                                <div class="clearfix">
                                                    <button type="button" class="width-35 pull-right btn btn-sm btn-danger">
                                                        <i class="icon-lightbulb"></i>
                                                        Send Me!
                                                    </button>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div><!-- /widget-main -->

                                    <div class="toolbar center">
                                        <a href="#" onclick="show_box('login-box'); return false;" class="back-to-login-link">
                                            Back to login
                                            <i class="icon-arrow-right"></i>
                                        </a>
                                    </div>
                                </div><!-- /widget-body -->
                            </div><!-- /forgot-box -->

                            <div id="signup-box" class="signup-box widget-box no-border">
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <h4 class="header green lighter bigger">
                                            <i class="icon-group blue"></i>
                                            New User Registration
                                        </h4>

                                        <div class="space-6"></div>
                                        <p> Enter your details to begin: </p>

                                        <form>
                                            <fieldset>
                                                <label class="block clearfix">
                                                                            <span class="block input-icon input-icon-right">
                                                                                <input type="email" class="form-control" placeholder="Email" />
                                                                                <i class="icon-envelope"></i>
                                                                            </span>
                                                </label>

                                                <label class="block clearfix">
                                                                            <span class="block input-icon input-icon-right">
                                                                                <input type="text" class="form-control" placeholder="Username" />
                                                                                <i class="icon-user"></i>
                                                                            </span>
                                                </label>

                                                <label class="block clearfix">
                                                                            <span class="block input-icon input-icon-right">
                                                                                <input type="password" class="form-control" placeholder="Password" />
                                                                                <i class="icon-lock"></i>
                                                                            </span>
                                                </label>

                                                <label class="block clearfix">
                                                                            <span class="block input-icon input-icon-right">
                                                                                <input type="password" class="form-control" placeholder="Repeat password" />
                                                                                <i class="icon-retweet"></i>
                                                                            </span>
                                                </label>

                                                <label class="block">
                                                    <input type="checkbox" class="ace" />
                                                                            <span class="lbl">
                                                                                I accept the
                                                                                <a href="#">User Agreement</a>
                                                                            </span>
                                                </label>

                                                <div class="space-24"></div>

                                                <div class="clearfix">
                                                    <button type="reset" class="width-30 pull-left btn btn-sm">
                                                        <i class="icon-refresh"></i>
                                                        Reset
                                                    </button>

                                                    <button type="button" class="width-65 pull-right btn btn-sm btn-success">
                                                        Register
                                                        <i class="icon-arrow-right icon-on-right"></i>
                                                    </button>
                                                </div>
                                            </fieldset>
                                        </form>
                                    </div>

                                    <div class="toolbar center">
                                        <a href="#" onclick="show_box('login-box'); return false;" class="back-to-login-link">
                                            <i class="icon-arrow-left"></i>
                                            Back to login
                                        </a>
                                    </div>
                                </div><!-- /widget-body -->
                            </div><!-- /signup-box -->
                        </div><!-- /position-relative -->
                    </div>
                </div><!-- /.col -->
            </div>
        </div>
    </div>
@stop
@section('script')
    <script type="text/javascript">

        function show_box(id) {
            jQuery('.widget-box.visible').removeClass('visible');
            jQuery('#'+id).addClass('visible');
        }
        $(function(){
            $('.post-login').click(function(){
                $(this).closest('form').attr('Method','POST').submit();
            });
        })
    </script>
@stop