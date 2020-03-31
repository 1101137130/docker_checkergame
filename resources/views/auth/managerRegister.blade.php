@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ url('managerRegister') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
                            <label for="username" class="col-md-4 control-label">Username:</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username"
                                    placeholder="username" required autofocus>

                                @if ($errors->has('username'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('username') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email"
                                    value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control"
                                    name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="authorities" class="col-md-4 control-label">權限</label>

                            <div class="col-md-6">
                                <input type="checkbox" id="view_orders" name="authorities[]" value="view_orders">
                                <label for="view_orders"> 可查詢其他客戶注單</label>
                                <input type="checkbox" id="manager_editor" name="authorities[]" value="manager_editor">
                                <label for="manager_editor"> 可新增管理者帳號</label><br>
                                <input type="checkbox" id="manage_rate" name="authorities[]" value="manage_rate">
                                <label for="manage_rate"> 可管理賠率</label>
                                <input type="checkbox" id="deposit_able" name="authorities[]" value="deposit_able">
                                <label for="deposit_able"> 可取出金額</label>
                                <input type="checkbox" id="order_amount_arrangement" name="authorities[]" value="order_amount_arrangement">
                                <label for="order_amount_arrangement"> 可限制下注金額</label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
