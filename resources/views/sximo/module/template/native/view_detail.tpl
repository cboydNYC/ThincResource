@extends('layouts.app')

@section('content')
<div class="page-content row">
 	<div class="page-content-wrapper m-t">   

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
  		
		<div class="sbox">
			<div class="sbox-title">

				 <h3> {{ $pageTitle }} <small>{{ $pageNote }}</small></h3>	
				 <div class="sbox-tools">

		   		<a href="{{ URL::to('{class}?return='.$return) }}" class="tips btn btn-xs btn-white pull-right" title="{{ Lang::get('core.btn_back') }}"><i class="fa fa-arrow-circle-left"></i>&nbsp;{{ Lang::get('core.btn_back') }}</a>
				@if($access['is_add'] ==1) 
		   		<a href="{{ URL::to('{class}/update/'.$id.'?return='.$return) }}" class="tips btn btn-xs btn-white pull-right" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit"></i>&nbsp;{{ Lang::get('core.btn_edit') }}</a>
				@endif 
					</div>
			</div>
			<div class="sbox-content" > 	

				<table class="table table-striped table-bordered" >
					<tbody>	
				{form_view}
						
					</tbody>	
				</table>   
			
			</div>
		</div>	
  	</div>
  	@foreach($subgrid as $sub)
  	<div role="tabpanel" class="tab-pane" id="{{ str_replace(" ","_",$sub['title']) }}{{ $row->{$sub['master_key']} }}"></div>
  	@endforeach
  </div>


</div>
</div>

<script type="text/javascript">
	$(function(){
		<?php foreach($subgrid as $sub) { ?>
			$('#{{ str_replace(" ","_",$sub['title']) }}{{ $row->{$sub['master_key']} }}').load('{!! url($sub['module']."/lookup/".implode("-",$sub)."-".$row->{$sub['master_key']})!!}')
		<?php } ?>

		
	})

</script>
	  
@stop