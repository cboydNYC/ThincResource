<div class="m-t" style="padding-top:25px;">	
    <div class="row m-b-lg animated fadeInDown delayp1 text-center">
        <h3> {{ $pageTitle }} <small> {{ $pageNote }} </small></h3>
        <hr />       
    </div>
</div>
<div class="m-t">
	<div class="table-responsive" > 	

		<table class="table table-striped table-bordered" >
			<tbody>	
		
			
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Id', (isset($fields['id']['language'])? $fields['id']['language'] : array())) }}</td>
						<td>{{ $row->id}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Name', (isset($fields['name']['language'])? $fields['name']['language'] : array())) }}</td>
						<td>{{ $row->name}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Short Name', (isset($fields['short_name']['language'])? $fields['short_name']['language'] : array())) }}</td>
						<td>{{ $row->short_name}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Code', (isset($fields['code']['language'])? $fields['code']['language'] : array())) }}</td>
						<td>{{ $row->code}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Logo', (isset($fields['logo']['language'])? $fields['logo']['language'] : array())) }}</td>
						<td>{{ $row->logo}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Active', (isset($fields['isactive']['language'])? $fields['isactive']['language'] : array())) }}</td>
						<td>{{ SiteHelpers::formatBoolean($row->isactive) }} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Date Created', (isset($fields['dt_created']['language'])? $fields['dt_created']['language'] : array())) }}</td>
						<td>{{ $row->dt_created}} </td>
						
					</tr>
				
					<tr>
						<td width='30%' class='label-view text-right'>{{ SiteHelpers::activeLang('Date Updated', (isset($fields['dt_updated']['language'])? $fields['dt_updated']['language'] : array())) }}</td>
						<td>{{ $row->dt_updated}} </td>
						
					</tr>
						
					<tr>
						<td width='30%' class='label-view text-right'></td>
						<td> <a href="javascript:history.go(-1)" class="btn btn-primary"> Back To Grid <a> </td>
						
					</tr>					
				
			</tbody>	
		</table>   

	 
	
	</div>
</div>	