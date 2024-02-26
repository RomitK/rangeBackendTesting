<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<style>
	.page_break {
		page-break-before: always;
	}

	body {
		padding: 0px;
		margin: 0px;
		overflow-x: hidden;
		font-family: 'Poppins';
	}

	p,
	span.a,
	h1,
	h2,
	h3,
	h4,
	h5,
	h6,
	td,
	th {
		margin: 0px;
		padding: 0px;
		font-family: 'Poppins';
	}

	table tr td {
		word-break: break-all;
		vertical-align: top;
	}

	.mainTable {
		width: 100%;
		max-width: 800px;
		margin: 0 auto;
		padding: 20px 50px 0px;

	}

	.headerTable {
		width: 100%;
		margin-bottom: 20px;
	}

	.headerTable td {
		width: 50%;
	}

	.bannerImg {
		width: 100%;
	}

	.titleText {
		font-size: 48px;
		color: #2879bc;
		text-transform: uppercase;
	}

	.tblTitleTd {
		width: 100%;
		font-size: 22px;
		color: #2879bc;
		font-weight: 600;
		padding-bottom: 15px;
	}

	.gridMainTable {
		width: 100%;
		margin-bottom: 30px;
	}

	.gridTable {
		width: 100%;
	}

	.cell3Td {
		width: 60%;
	}

	.cell2Td {
		width: 40%;
	}

	.clm3BarTbl {
		width: 60%;
	}

	.clmTbl {
		width: 100%;
	}

	.clum3Bar {
		width: 60%;
	}

	.clum2Bar {
		width: 40%;
	}

	.clum3Bar.bg-primery {
		background-color: #2879bc;
		color: #fff;
		font-size: 13px;
		font-weight: 500;
		padding: 3px 5px;
	}

	.clum2Bar.bg-gray {
		background-color: #f7f8f9;
		color: #01020a;
		font-size: 13px;
		padding: 3px 5px;
		font-weight: 400;
	}

	.htFix {
		height: 36px;
		vertical-align: baseline;
	}

	.cell3Td.rightBdr {
		border-right: 10px solid #fff;
	}

	.cell2Td.leftBdr {
		border-left: 10px solid #fff;
	}

	.cell3Td.leftBdr {
		border-left: 10px solid #fff;
	}

	.cell2Td.rightBdr {
		border-right: 10px solid #fff;
	}

	.clum2Bar.bg-primery {
		background-color: #2879bc;
		color: #fff;
		font-size: 14px;
		font-weight: 500;
		padding: 3px 5px;
	}

	.clum3Bar.bg-gray {
		background-color: #f7f8f9;
		color: #01020a;
		font-size: 13px;
		padding: 3px 5px;
		font-weight: 400;
	}

	.textArea {
		font-size: 14px;
		color: #01020a;
		font-style: italic;
	}

	.mainLogo {
		width: 220px;
		margin-bottom: 10px;
		object-fit: contain;
	}

	.smLogo {
		width: 160px;
		object-fit: contain;
	}

	.paymentPlanTable {
		width: 100%;
		border-collapse: collapse;
	}

	.paymentPlanTable td {
		border: 1.5px solid #000000;
		border-top: 0px;
		text-align: center;
		font-size: 14px;
		color: #01020a;
		padding: 3px 0px;
	}

	.paymentPlanTable th {
		background-color: #2879bc;
		color: #fff;
		border-left: 1.5px solid #fff;
		border-right: 1.5px solid #fff;
		text-align: center;
		font-weight: 500;
		font-size: 14px;
	}

	.MapTable {
		width: 100%;
		max-width: 800px;
		margin: 0 auto 20px;
	}

	.mapImg {
		width: 100%;
		object-fit: contain;
	}

	.footerTable {
		width: 100%;
		max-width: 800px;
		margin: 0 auto;

	}

	.pageLogoBox {
		width: 100%;
		vertical-align: middle;
		height: 70vh;

	}

	.pageLogo {
		width: 70%;
		object-fit: contain;
		margin: 0 auto;
		display: block;
	}

	.footertextBox {
		width: 100%;
		vertical-align: middle;
		height: 30vh;
		text-align: center;
		background-color: #2879bc;
	}

	.footertextBox h2 {
		color: #fff;
		font-size: 30px;
		font-weight: 600;
		line-height: 30px;
	}

	.footertextBox h3 {
		color: #fff;
		font-size: 24px;
		font-weight: 600;
		margin-bottom: 15px;
	}

	.footertextBox p {
		color: #fff;
		font-size: 18px;
		font-weight: 400;
	}
</style>

<body>
	<!--- first Page Start --->
	@php
	$handOver = null;
	if($property->project){

	$dateStr = $property->project->completion_date;
	$month = date("n", strtotime($dateStr));
	$yearQuarter = ceil($month / 3);
	$handOver = "Q".$yearQuarter." ".date("Y", strtotime($dateStr));
	}

	$a_fees = $property->price/0.04;
	$b_fees = 540;
	$c_fees = 1000;
	$d_fees = 1000;
	$subTotal = $a_fees + $b_fees + $c_fees + $d_fees;
	$total= $property->price +$subTotal;
	@endphp
	<table class="mainTable">
		<tr>
			<td style="width:100%">
				<table class="headerTable">
					<tr>
						<td>
							<!--<img src="{{ asset('frontend/assets/images/logo.png') }}"-->
							<!--	class="mainLogo">-->
						</td>
					</tr>
					<tr class="bg-primery">
						<td class="clum2Bar bg-primery" style="vertical-align: middle; text-align: center;">
							<h3>
								{{ $property->project->title }}
							</h3>
							<h4>{{ $property->project->developer->name}}</h4>
						</td>
						<td>
							<img src="{{ $property->mainImage }}" class="bannerImg">
						</td>
					</tr>
				</table>
				<table class="gridMainTable">
					<tr>
						<td class="tblTitleTd">
							Property Details
						</td>

					</tr>
					<tr>
						<td style="width:100%">
							<table class="gridTable">
								<td class="cell3Td rightBdr">
									<table class="clmTbl">
										<tr>
											<td class="clum3Bar bg-primery">Product Name</td>
											<td class="clum2Bar bg-gray">{{ $property->name}}</td>
										</tr>
										<tr>
											<td class="clum3Bar bg-primery">Community Name</td>
											<td class="clum2Bar bg-gray">{{ $property->project->mainCommunity->name}}</td>
										</tr>
										<tr>
											<td class="clum3Bar bg-primery">Product Status</td>
											<td class="clum2Bar bg-gray">{{ $property->completionStatus->name}}</td>
										</tr>
										<tr>
											<td class="clum3Bar bg-primery">Anticipated Completion Date</td>
											<td class="clum2Bar bg-gray">{{ $handOver }}</td>
										</tr>
										@if($property->project)
										<tr>
											<td class="clum3Bar bg-primery">Permit Number</td>
											<td class="clum2Bar bg-gray">{{ $property->project->permit_number }}</td>
										</tr>
										@endif

									</table>
								</td>
								<td class="cell2Td leftBdr">
									<table class="clmTbl">
										<tr class="htFix">
											<td class="clum2Bar bg-primery">Gross Price</td>
											<td class="clum3Bar bg-gray">AED {{ number_format($property->price)
													}}</td>
										</tr>
										<tr class="htFix">
											<td class="clum2Bar bg-primery">VAT</td>
											<td class="clum3Bar bg-gray">N/A</td>
										</tr>
										<tr class="htFix">
											<td class="clum2Bar bg-primery">Net Price</td>
											<td class="clum3Bar bg-gray">AED {{ number_format($total) }}</td>
										</tr>

									</table>
								</td>
							</table>
						</td>
					</tr>
				</table>
				<table class="gridMainTable">
					<tr>
						<td class="tblTitleTd">
							Unit Details

						</td>

					</tr>
					<tr>
						<td style="width:100%">
							<table class="gridTable">
								<td class="cell2Td rightBdr">
									<table class="clmTbl">
										<tr>
											<td class="clum2Bar bg-primery">Unit</td>
											<td class="clum3Bar bg-gray">{{ $property->subProject->title }}</td>
										</tr>
										<tr>
											<td class="clum2Bar bg-primery">Unit Type</td>
											<td class="clum3Bar bg-gray">{{ $property->accommodations->name }}</td>
										</tr>
										<tr>
											<td class="clum2Bar bg-primery">Area (sqft)</td>
											<td class="clum3Bar bg-gray">{{ $property->area }}</td>
										</tr>


									</table>
								</td>
								<td class="cell3Td leftBdr">
									<table class="clmTbl">
										<tr>
											<td class="clum3Bar bg-primery">View Type</td>
											<td class="clum2Bar bg-gray">{{ $property->primary_view }}</td>
										</tr>
										<tr>
											<td class="clum3Bar bg-primery">Bedrooms</td>
											<td class="clum2Bar bg-gray">{{ $property->bedrooms}}</td>
										</tr>

										<tr>
											<td class="clum3Bar bg-primery">Parking</td>
											<td class="clum2Bar bg-gray">{{ $property->parking_space}}</td>
										</tr>

									</table>
								</td>
							</table>
						</td>
					</tr>
				</table>

				<table class="gridMainTable">
					<tr>
						<td class="tblTitleTd">
							Additional Charges

						</td>

					</tr>
					<tr>
						<td style="width:100%">
							<table class="gridTable">
								<td class="cell2Td rightBdr">
									<table class="clmTbl">
										<tr>
											<td class="clum2Bar bg-primery">Land Registration Fee (A)*</td>
											<td class="clum3Bar bg-gray">AED {{ number_format($a_fees) }}</td>
										</tr>
										<tr>
											<td class="clum2Bar bg-primery">OQOOD Fee(C)</td>
											<td class="clum3Bar bg-gray">AED {{ number_format($c_fees) }}</td>
										</tr>

										<tr>
											<td class="clum2Bar bg-primery">Total Fees to DLD(A+B+C+D+E)</td>
											<td class="clum3Bar bg-gray">AED {{ number_format($subTotal) }}</td>
										</tr>

									</table>
								</td>
								<td class="cell3Td leftBdr">
									<table class="clmTbl">
										<tr>
											<td class="clum3Bar bg-primery">Title Deed (B)</td>
											<td class="clum2Bar bg-gray">AED {{ number_format($b_fees) }}</td>
										</tr>
										<tr>
											<td class="clum3Bar bg-primery">DSR Fees(D)</td>
											<td class="clum2Bar bg-gray">AED {{ number_format($d_fees)}}</td>
										</tr>
										<tr>
											<td class="clum2Bar bg-primery">Other Fee(E)</td>
											<td class="clum3Bar bg-gray">N/A</td>
										</tr>

									</table>
								</td>
							</table>
						</td>
					</tr>
					<tr>
						<td class="textArea" style="padding-top: 20px;">
							Disclaimer - These charges are approximate values. It may be subject to
							change by the
							authorities. Please always consult your agent on these charges. *Land
							Registration Fee is
							4% of the Price
						</td>
					</tr>
				</table>

			</td>
		</tr>
	</table>
	<div class="page_break"></div>
	<!--- First Page End --->
	@if(count($property->project->mPaymentPlans) > 0)
	<!--- 2 Page Start --->
	<table class="mainTable ">
		<tr>
			<td class="tblTitleTd" width="50%" style="margin-bottom: 0px;padding:0px; vertical-align:middle;">
				Payment Plan
			</td>
		</tr>
		@foreach($property->project->mPaymentPlans as $paymentPlan)
		<tr>
			<td style="width:100%;padding-top: 10px;" colspan="2">
				<table class="paymentPlanTable">
					<thead>
						<tr>
							<th colspan="2">
								<div class="vtTextBXox">
									<h3 class="text-primary" style="text-transform: uppercase;">{{ $paymentPlan->value }}</h3>
								</div>
							</th>
						</tr>
						<tr>
							<th>
								Payments
							</th>
							<th>
								Percentage (%)
							</th>
							<!--<th>-->
							<!--	Milestones-->
							<!--</th>-->

						</tr>
					</thead>
					<tbody>
						@foreach($paymentPlan->paymentPlans as $row)
						<tr>
							<td>{{ $row->value }}</td>
							<!--<td>{{  $row->name }}</td>-->
							<td>{{ $row->key }}</td>

						</tr>
						@endforeach

					</tbody>
				</table>
			</td>
		</tr>
		@endforeach
	</table>
	@endif
	<br><br><br>
	<!--- 2 Page End --->
	<!--- 3 Page Start --->
	@if(count($property->subProject->floorPlan) > 0)

	<table class="MapTable">
		<tr>
			<td>
				<table>
					<tr>
						<td style="
					 	    	      width:180px; 
					 	    	       text-align:center;
					 	    	       background-color: #2879bc;
					 	    	       font-size: 22px;color: #fff;
					 	    	       font-weight: 600;">

							Floor Plan
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="width:100%;padding: 20px 100px;">
				<table>
					<tr>
						<td>
							<img src="{{ $property->subProject->floorPlan[0]['path']}}" class="mapImg">
						</td>
					</tr>
				</table>

			</td>
		</tr>
	</table>

	@endif

	@if($property->project->mainCommunity->clusterPlan)
	<div class="page_break"></div>
	<table class="MapTable">
		<tr>
			<td>
				<table>
					<tr>
						<td style="
					 	    	       width:180px; 
					 	    	       text-align:center;
					 	    	       background-color: #2879bc;
					 	    	       font-size: 22px;color: #fff;
					 	    	       font-weight: 600;
					 	    	      ">

							Master Plan
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="width:100%;padding: 20px 100px;">
				<table>
					<tr>
						<td>
							<img src="{{$property->project->mainCommunity->clusterPlan}}" class="mapImg">
						</td>
					</tr>
				</table>

			</td>
		</tr>
	</table>
	@endif

	<table class="footerTable">
		<tr>
			<td class="pageLogoBox" style="text-align:center">
				<img src="{{ asset('frontend/assets/images/logo.png') }}" class="pageLogo">
			</td>
		</tr>
		<tr>
			<td class="footertextBox">
				<h2>TOLL FREE 800 72 888</h2>
				<h3>sales@range.ae</h3>
				<p>www.range.ae</p>
			</td>
		</tr>
	</table>
</body>

</html>