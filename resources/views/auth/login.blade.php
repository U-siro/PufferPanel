@extends('layouts.master')

@section('title', 'Login')


@section('right-nav')
@endsection

@section('sidebar')
@endsection

@section('content')
<div class="col-md-6">
   	<form action="/auth/login" method="POST" id="login-form">
    	<legend>Login</legend>
    	<fieldset>
            @if (session('status'))
                {{ session('status') }}
            @endif
    		<div class="form-group">
    			<label for="email" class="control-label">Email</label>
    			<div>
    				<input type="text" class="form-control" name="email" id="email" placeholder="Email" />
    			</div>
    		</div>
    		<div class="form-group">
    			<label for="login-password" class="control-label">Password</label>
    			<div>
    				<input type="password" class="form-control" name="password" id="password" placeholder="Password" />
    			</div>
    		</div>
    		<div class="form-group">
    			<div>
    				<label><input type="checkbox" name="remember_me" /> Remember Me</label>
    			</div>
    		</div>
    		<div class="form-group">
    			<div>
                    {{ csrf_field() }}
    				<input type="submit" class="btn btn-primary btn-sm" value="Login" />
    				<button class="btn btn-default btn-sm" onclick="window.location='/auth/password';return false;">Reset Password</button>
    			</div>
    		</div>
    	</fieldset>
    </form>
</div>
<div class="modal fade" id="openTOTP" tabindex="-1" role="dialog">
	<div class="modal-dialog" style="width:400px;">
		<form action="/auth/login" method="POST" id="totp-form">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Two-Factor Authentication Checkpoint</h4>
				</div>
				<div class="modal-body" id="modal_insert_content">
					<div class="form-group">
						<label for="totp_token" class="control-label">Two-Factor Authentication Token</label>
						<div>
							<input class="form-control" type="text" placeholder="000111" name="totp_token" id="totp_token" />
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<input type="submit" class="btn btn-default btn-sm" value="Continue" />
				</div>
			</div>
		</form>
	</div>
</div>
<div class="col-md-3"></div>
<script type="text/javascript">
$(document).ready(function(){
	$("#login-form").submit(function(event){
		var check_email = $("#email").val();
		$.ajax({
			type: "POST",
			url: "/auth/login/totp",
			async: false,
			data: { check: check_email },
			success: function(data){
				if(data == 'true'){
					$("#openTOTP").modal('show');
					$('#openTOTP').on('shown.bs.modal', function(){
						$("#totp_token").focus();
					})
                    event.preventDefault();
				}else{
					$(this).submit();
				}
			}
		});
	});
	$("#totp-form").submit(function(){
		$('#login-form :input').not(':submit').clone().hide().appendTo('#totp-form');
		return true;
	});
});
</script>
@endsection
