
@if($setting['form-method'] =='native')
	<div class="sbox">
		<div class="sbox-title">  
			<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
				<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')"><i class="fa fa fa-times"></i></a>
			</h4>
	</div>

	<div class="sbox-content"> 
@endif	
			{!! Form::open(array('url'=>'staffadmin/save/'.SiteHelpers::encryptID($row['id']), 'class'=>'form-horizontal','files' => true , 'parsley-validate'=>'','novalidate'=>' ','id'=> 'staffadminFormAjax')) !!}
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
			
												
						
	<hr />
	<div class="clr clear"></div>
	
	<h5> Software </h5>
	
	<div class="table-responsive">
    <table class="table table-striped ">
        <thead>
			<tr>
				@foreach ($subform['tableGrid'] as $t)
					@if($t['view'] =='1' && $t['field'] !='id')
						<th>{{ $t['label'] }}</th>
					@endif
				@endforeach
				<th></th>	
			  </tr>

        </thead>

        <tbody>
        @if(count($subform['rowData'])>=1)
            @foreach ($subform['rowData'] as $rows)
            <tr class="clone clonedInput">
									
			 @foreach ($subform['tableGrid'] as $field)
				 @if($field['view'] =='1' && $field['field'] !='id')
				 <td>					 
				 	{!! SiteHelpers::bulkForm($field['field'] , $subform['tableForm'] , $rows->{$field['field']}) !!}							 
				 </td>
				 @endif					 
			 
			 @endforeach
			 <td>
			 	<a onclick=" $(this).parents('.clonedInput').remove(); return false" href="#" class="remove btn btn-xs btn-danger">-</a>
			 	<input type="hidden" name="counter[]">
			 </td>
			@endforeach
			</tr> 

		@else
            <tr class="clone clonedInput">
									
			 @foreach ($subform['tableGrid'] as $field)

				 @if($field['view'] =='1' && $field['field'] !='id')
				 <td>					 
				 	{!! SiteHelpers::bulkForm($field['field'] , $subform['tableForm'] ) !!}							 
				 </td>
				 @endif					 
			 
			 @endforeach
			 <td>
			 	<a onclick=" $(this).parents('.clonedInput').remove(); return false" href="#" class="remove btn btn-xs btn-danger">-</a>
			 	<input type="hidden" name="counter[]">
			 </td>
			
			</tr> 

		
		@endif	


        </tbody>	

     </table>  
     <input type="hidden" name="enable-masterdetail" value="true">
     </div>
	<br /><br />
     
     <a href="javascript:void(0);" class="addC btn btn-xs btn-info" rel=".clone"><i class="fa fa-plus"></i> New Item</a>
     <hr />					
						
			<div style="clear:both"></div>	
							
			<div class="form-group">
				<label class="col-sm-4 text-right">&nbsp;</label>
				<div class="col-sm-8">	
					<button type="submit" class="btn btn-primary btn-sm "><i class="icon-checkmark-circle2"></i>  {{ Lang::get('core.sb_save') }} </button>
					<button type="button" onclick="ajaxViewClose('#{{ $pageModule }}')" class="btn btn-success btn-sm"><i class="icon-cancel-circle2 "></i>  {{ Lang::get('core.sb_cancel') }} </button>
				</div>			
			</div> 		 
			{!! Form::close() !!}


@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif	

	
</div>	
			 
<script type="text/javascript">
$(document).ready(function() { 
	
		$("#group_id").jCombo("{!! url('staffadmin/comboselect?filter=tb_staff_groups:id:group_name') !!}",
		{  selected_value : '{{ $row["group_id"] }}' });
		 
	$('.addC').relCopy({});
	$('.editor').summernote();
	$('.previewImage').fancybox();	
	$('.tips').tooltip();	
	$(".select2").select2({ width:"98%"});	
	$('.date').datepicker({format:'yyyy-mm-dd',autoClose:true})
	$('.datetime').datetimepicker({format: 'yyyy-mm-dd hh:ii:ss'}); 
	$('input[type="checkbox"],input[type="radio"]').iCheck({
		checkboxClass: 'icheckbox_square-red',
		radioClass: 'iradio_square-red',
	});			
		$('.removeMultiFiles').on('click',function(){
			var removeUrl = '{{ url("staffadmin/removefiles?file=")}}'+$(this).attr('url');
			$(this).parent().remove();
			$.get(removeUrl,function(response){});
			$(this).parent('div').empty();	
			return false;
		});
				
	var form = $('#staffadminFormAjax'); 
	form.parsley();
	form.submit(function(){
		
		if(form.parsley('isValid') == true){			
			var options = { 
				dataType:      'json', 
				beforeSubmit :  showRequest,
				success:       showResponse  
			}  
			$(this).ajaxSubmit(options); 
			return false;
						
		} else {
			return false;
		}		
	
	});

});

function showRequest()
{
	$('.ajaxLoading').show();		
}  
function showResponse(data)  {		
	
	if(data.status == 'success')
	{
		ajaxViewClose('#{{ $pageModule }}');
		ajaxFilter('#{{ $pageModule }}','{{ $pageUrl }}/data');
		notyMessage(data.message);	
		$('#sximo-modal').modal('hide');	
	} else {
		notyMessageError(data.message);	
		$('.ajaxLoading').hide();
		return false;
	}	
}			 

</script>		 