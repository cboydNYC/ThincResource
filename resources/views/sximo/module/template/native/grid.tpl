@extends('layouts.app')

@section('content')
{{--*/ usort($tableGrid, "SiteHelpers::_sort") /*--}}
  <div class="page-content row">
	
	
	<div class="page-content-wrapper m-t">	 	

<div class="sbox">
	<div class="sbox-title"> <h5> <i class="fa fa-table"></i> {{ $pageTitle }} <small>{{ $pageNote }}</small> </h5>
<div class="sbox-tools" >
		<a href="{{ url($pageModule) }}" class="btn btn-xs btn-white tips"  title="{{ Lang::get('core.btn_clearsearch') }}" ><i class="fa fa-trash-o"></i> {{ Lang::get('core.btn_clearsearch') }} </a>
		@if(Session::get('gid') ==1)
			<a href="{{ URL::to('sximo/module/config/'.$pageModule) }}" class="btn btn-xs btn-white tips" title=" {{ Lang::get('core.btn_config') }}" ><i class="fa fa-cog"></i></a>
		@endif 
		</div>
	</div>
	<div class="sbox-content"> 	
	    <div class="m-b row ">
		    <div class="col-md-6">
				@if($access['is_add'] ==1)
		   		<a href="{{ URL::to('{class}/update') }}" class="tips btn btn-sm btn-white"  title="{{ Lang::get('core.btn_create') }}">
				<i class="fa fa-plus-circle "></i>&nbsp;{{ Lang::get('core.btn_create') }}</a>
				<a href="javascript://ajax" class="btn btn-sm btn-white copy" ><i class="icon-copy"></i> Copy </a>
				@endif  
				@if($access['is_remove'] ==1)
				<a href="javascript://ajax"  onclick="SximoDelete();" class="tips btn btn-sm btn-white" title="{{ Lang::get('core.btn_remove') }}">
				<i class="fa fa-minus-circle "></i>&nbsp;{{ Lang::get('core.btn_remove') }}</a>
				@endif 
				<a href="{{ URL::to( '{class}/search') }}" class="btn btn-sm btn-white" onclick="SximoModal(this.href,'Advance Search'); return false;" title="{{ Lang::get('core.btn_search') }}"><i class="fa fa-search"></i> {{ Lang::get('core.btn_search') }} </a>				
							
		 	</div>

			<div class="col-md-6 "> 
				@if($access['is_excel'] ==1)
				<div class="pull-right">
					<a href="{{ URL::to( $pageModule .'/export/excel?return='.$return) }}" class="btn btn-sm btn-white"><i class="icon-file-download"></i> Excel</a>
					<a href="{{ URL::to( $pageModule .'/export/word?return='.$return) }}" class="btn btn-sm btn-white"><i class="icon-file-download"></i> Word </a>
					<a href="{{ URL::to( $pageModule .'/export/csv?return='.$return) }}" class="btn btn-sm btn-white"><i class="icon-file-download"></i> CSV </a>
					<a href="{{ URL::to( $pageModule .'/export/print?return='.$return) }}" class="btn btn-sm btn-white" onclick="ajaxPopupStatic(this.href); return false;" ><i class="icon-print2"></i> Print</a>
				</div>	
				@endif
			</div>

		</div> 	




	 {!! (isset($search_map) ? $search_map : '') !!}
	
	 {!! Form::open(array('url'=>'{class}/delete/', 'class'=>'form-horizontal' ,'id' =>'SximoTable' )) !!}
	 <div class="table-responsive" style="min-height:300px;">
    <table class="table table-striped ">
        <thead>
			<tr>
				<th class="number"> No </th>
				<th> <input type="checkbox" class="checkall" /></th>
				
				@foreach ($tableGrid as $t)
					@if($t['view'] =='1')				
						<?php $limited = isset($t['limited']) ? $t['limited'] :''; 
						if(SiteHelpers::filterColumn($limited ))
						{
							echo '<th align="'.$t['align'].'" width="'.$t['width'].'">'.\SiteHelpers::activeLang($t['label'],(isset($t['language'])? $t['language'] : array())).'</th>';				
						} 
						?>
					@endif
				@endforeach
				<th width="100" class="text-right" >{{ Lang::get('core.btn_action') }}</th>
			  </tr>
        </thead>

        <tbody>        						
            @foreach ($rowData as $row)
                <tr>
					<td width="30"> {{ ++$i }} </td>
					<td width="50"><input type="checkbox" class="ids" name="ids[]" value="{{ $row->{key} }}" />  </td>									
				 @foreach ($tableGrid as $field)
					 @if($field['view'] =='1')
					 	<?php $limited = isset($field['limited']) ? $field['limited'] :''; ?>
					 	@if(SiteHelpers::filterColumn($limited ))
						 <td>					 
						 	{!! SiteHelpers::formatRows($row->{$field['field']},$field ,$row ) !!}						 
						 </td>
						@endif	
					 @endif					 
				 @endforeach
				 <td width="100" class="text-right">
					 	@if($access['is_detail'] ==1)
						<a href="{{ URL::to('{class}/show/'.$row->{key}.'?return='.$return)}}" class="tips btn btn-xs btn-primary" title="{{ Lang::get('core.btn_view') }}"><i class="fa  fa-search "></i></a>
						@endif
						@if($access['is_edit'] ==1)
						<a  href="{{ URL::to('{class}/update/'.$row->{key}.'?return='.$return) }}" class="tips btn btn-xs btn-success" title="{{ Lang::get('core.btn_edit') }}"><i class="fa fa-edit "></i></a>
						@endif
												
					
				</td>				 
                </tr>
				
            @endforeach
              
        </tbody>
      
    </table>
	<input type="hidden" name="md" value="" />
	</div>
	{!! Form::close() !!}
	@include('footer')
	</div>
</div>	
	</div>	  
</div>	
<script>
$(document).ready(function(){

	$('.do-quick-search').click(function(){
		$('#SximoTable').attr('action','{{ URL::to("{class}/multisearch")}}');
		$('#SximoTable').submit();
	});

	$('.copy').click(function() {
		var total = $('input[class="ids"]:checkbox:checked').length;
		if(confirm('are u sure Copy selected rows ?'))
		{
				$('#SximoTable').attr('action','{{ url("{class}/copy")}}');
				$('#SximoTable').submit();// do the rest here	
		}
	})	
	
});	
</script>		
@stop