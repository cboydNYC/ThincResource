

		 {!! Form::open(array('url'=>'staffadmin/savepublic', 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ')) !!}

	@if(Session::has('messagetext'))
	  
		   {!! Session::get('messagetext') !!}
	   
	@endif
	<ul class="parsley-error-list">
		@foreach($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>		


<div class="col-md-12">
						<fieldset><legend> Staff</legend>
				{!! Form::hidden('id', $row['id']) !!}					
									  <div class="form-group  " >
										<label for="Group" class=" control-label col-md-4 text-left"> Group <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  <select name='group_id' rows='5' id='group_id' class='select2 ' required  ></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> {!! Form::hidden('username', $row['username']) !!}{!! Form::hidden('password', $row['password']) !!}					
									  <div class="form-group  " >
										<label for="Email" class=" control-label col-md-4 text-left"> Email </label>
										<div class="col-md-6">
										  {!! Form::text('email', $row['email'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="First Name" class=" control-label col-md-4 text-left"> First Name <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  {!! Form::text('first_name', $row['first_name'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Last Name" class=" control-label col-md-4 text-left"> Last Name <span class="asterix"> * </span></label>
										<div class="col-md-6">
										  {!! Form::text('last_name', $row['last_name'],array('class'=>'form-control', 'placeholder'=>'', 'required'=>'true'  )) !!} 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Avatar" class=" control-label col-md-4 text-left"> Avatar </label>
										<div class="col-md-6">
										  {!! Form::text('avatar', $row['avatar'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Job Description" class=" control-label col-md-4 text-left"> Job Description </label>
										<div class="col-md-6">
										  <textarea name='job_description' rows='5' id='job_description' class='form-control '  
				           >{{ $row['job_description'] }}</textarea> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Aspirations" class=" control-label col-md-4 text-left"> Aspirations </label>
										<div class="col-md-6">
										  <textarea name='aspirations' rows='5' id='aspirations' class='form-control '  
				           >{{ $row['aspirations'] }}</textarea> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Skills" class=" control-label col-md-4 text-left"> Skills </label>
										<div class="col-md-6">
										  {!! Form::text('skills', $row['skills'],array('class'=>'form-control', 'placeholder'=>'',   )) !!} 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Weekly Email" class=" control-label col-md-4 text-left"> Weekly Email </label>
										<div class="col-md-6">
										  
					<?php $weekly_email = explode(',',$row['weekly_email']);
					$weekly_email_opt = array( '0' => 'No' ,  '1' => 'Yes' , ); ?>
					<select name='weekly_email' rows='5'   class='select2 '  > 
						<?php 
						foreach($weekly_email_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['weekly_email'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> 					
									  <div class="form-group  " >
										<label for="Active" class=" control-label col-md-4 text-left"> Active </label>
										<div class="col-md-6">
										  
					<?php $isactive = explode(',',$row['isactive']);
					$isactive_opt = array( '0' => 'No' ,  '1' => 'Yes' , ); ?>
					<select name='isactive' rows='5'   class='select2 '  > 
						<?php 
						foreach($isactive_opt as $key=>$val)
						{
							echo "<option  value ='$key' ".($row['isactive'] == $key ? " selected='selected' " : '' ).">$val</option>"; 						
						}						
						?></select> 
										 </div> 
										 <div class="col-md-2">
										 	
										 </div>
									  </div> {!! Form::hidden('login_attempt', $row['login_attempt']) !!}{!! Form::hidden('last_login', $row['last_login']) !!}{!! Form::hidden('created_at', $row['created_at']) !!}{!! Form::hidden('updated_at', $row['updated_at']) !!}{!! Form::hidden('reminder', $row['reminder']) !!}{!! Form::hidden('activation', $row['activation']) !!}{!! Form::hidden('remember_token', $row['remember_token']) !!}{!! Form::hidden('last_activity', $row['last_activity']) !!}</fieldset>
			</div>
			
			

			<div style="clear:both"></div>	
				
					
				  <div class="form-group">
					<label class="col-sm-4 text-right">&nbsp;</label>
					<div class="col-sm-8">	
					<button type="submit" name="apply" class="btn btn-info btn-sm" ><i class="fa  fa-check-circle"></i> {{ Lang::get('core.sb_apply') }}</button>
					<button type="submit" name="submit" class="btn btn-primary btn-sm" ><i class="fa  fa-save "></i> {{ Lang::get('core.sb_save') }}</button>
				  </div>	  
			
		</div> 
		 
		 {!! Form::close() !!}
		 
   <script type="text/javascript">
	$(document).ready(function() { 
		$('.addC').relCopy({});
		
		$("#group_id").jCombo("{!! url('staffadmin/comboselect?filter=tb_staff_groups:id:group_name') !!}",
		{  selected_value : '{{ $row["group_id"] }}' });
		 

		$('.removeCurrentFiles').on('click',function(){
			var removeUrl = $(this).attr('href');
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});		
		
	});
	</script>		 
