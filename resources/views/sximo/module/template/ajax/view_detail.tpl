@if($setting['view-method'] =='native')
<div class="sbox">
	<div class="sbox-title">  
		<h4> <i class="fa fa-table"></i> <?php echo $pageTitle ;?> <small>{{ $pageNote }}</small>
			<a href="javascript:void(0)" class="collapse-close pull-right btn btn-xs btn-danger" onclick="ajaxViewClose('#{{ $pageModule }}')">
			<i class="fa fa fa-times"></i></a>
		</h4>
	 </div>

	<div class="sbox-content"> 
@endif	

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
  	<li role="presentation" class="active"><a href="#home{{ $row->customerNumber }}" aria-controls="home" role="tab" data-toggle="tab">  {{ $pageTitle}} :   View Detail </a></li>
	@foreach($subgrid as $sub)
		<li role="presentation"><a href="#{{ str_replace(" ","_",$sub['title']) }}{{ $row->{$sub['master_key']} }}" aria-controls="profile" role="tab" data-toggle="tab">{{ $pageTitle}} :  {{ $sub['title'] }}</a></li>
	@endforeach
  </ul>


  <!-- Tab panes -->
  <div class="tab-content m-t">
  	<div role="tabpanel" class="tab-pane active" id="home{{ $row->{key} }}">

		<div class="table-responsive" >  
			<table class="table table-striped table-bordered" >
				<tbody>	
					{form_view}		
				</tbody>	
			</table>  		
		</div>
		
  	</div>
  	@foreach($subgrid as $sub)
  	<div role="tabpanel" class="tab-pane" id="{{ str_replace(" ","_",$sub['title']) }}{{ $row->{$sub['master_key']} }}"></div>
  	@endforeach
  </div>

		 	

@if($setting['form-method'] =='native')
	</div>	
</div>	
@endif	

<script type="text/javascript">
	$(function(){
		<?php foreach($subgrid as $sub) { ?>
			$('#{{ str_replace(" ","_",$sub['title']) }}{{ $row->{$sub['master_key']} }}').load('{!! url($sub['module']."/lookup/".implode("-",$sub)."-".$row->{$sub['master_key']})!!}')
		<?php } ?>

		
	})

</script>