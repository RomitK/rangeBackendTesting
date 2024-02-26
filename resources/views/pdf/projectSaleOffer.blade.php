<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>

<style>
body{
padding: 0px;
margin: 0px;
overflow-x: hidden;
font-family: 'Poppins';
}
p,span.a,h1,h2,h3,h4,h5,h6,td,th{
margin: 0px;
padding: 0px;
font-family: 'Poppins';
}
table tr td{
	word-break:break-all;
	vertical-align: top;
}		
.mainTable{
width: 100%;
max-width: 800px;
margin: 0 auto;
padding: 20px 50px 0px;

}
.headerTable{
width: 100%;
margin-bottom: 20px;
}
.headerTable td{
width: 50%;
}
.bannerImg{
width: 100%;
}
.titleText{
font-size: 48px;
color: #2879bc;
text-transform: uppercase;
}
.tblTitleTd{
width: 100%;
font-size: 22px;
color:#2879bc;
font-weight: 600;
padding-bottom: 15px;
}
.gridMainTable{
width: 100%;
margin-bottom: 30px;
}
.gridTable{
width: 100%;
}
.cell3Td{
width: 60%;
}
.cell2Td{
width: 40%;
}
.clm3BarTbl{
width: 60%;
}
.clmTbl{
width: 100%;
}
.clum3Bar{
width: 60%;
}
.clum2Bar{
	width: 40%;
}
.clum3Bar.bg-primery{
background-color: #2879bc;
color: #fff;
font-size: 13px;
font-weight: 500;
padding: 3px 5px;
}
.clum2Bar.bg-gray{
background-color: #f7f8f9;
color:#01020a;
font-size: 13px;
padding: 3px 5px;
font-weight: 400;
}
.htFix{
height: 36px;
vertical-align: baseline;
}
.cell3Td.rightBdr{
border-right: 10px solid #fff;
}
.cell2Td.leftBdr {
border-left: 10px solid #fff;
}
.cell3Td.leftBdr{
border-left: 10px solid #fff;	
}
.cell2Td.rightBdr {
border-right: 10px solid #fff;
}
.clum2Bar.bg-primery{
background-color: #2879bc;
color: #fff;
font-size: 14px;
font-weight: 500;
padding: 3px 5px;
}
.clum3Bar.bg-gray{
background-color: #f7f8f9;
color: #01020a;
font-size: 13px;
padding: 3px 5px;
font-weight: 400;	
}

.textArea{
font-size: 14px;
color: #01020a;
font-style: italic;
}
.mainLogo{
width: 220px;
margin-bottom: 10px;
object-fit: contain;
}

.smLogo{
width: 160px;
object-fit: contain;
}

.paymentPlanTable{
	width: 100%;
	border-collapse: collapse;
}
.paymentPlanTable td{
	border: 1.5px solid #000000;
	border-top: 0px;
	text-align: center;
	font-size: 14px;
	color: #01020a;
	padding: 3px 0px;
}
.paymentPlanTable th{
	background-color: #2879bc;
	color: #fff;
	border-left: 1.5px solid #fff;
	border-right: 1.5px solid #fff;
	text-align: center;
	font-weight: 500;
	font-size: 14px;
}
.MapTable{
	width: 100%;
   max-width: 800px;
   margin: 0 auto 20px;
}
.mapImg{
	width: 100%;
	object-fit: contain;
}
.footerTable{
	width: 100%;
	max-width: 800px;
	margin: 0 auto;

}
.pageLogoBox{
 width: 100%;
 vertical-align: middle;
 height: 70vh;

}
.pageLogo{
width: 70%;
object-fit: contain;
margin: 0 auto;
display: block;
}
.footertextBox{
	width: 100%;
	vertical-align: middle;
	height: 30vh;
	text-align: center;
	background-color: #2879bc;
}
.footertextBox h2{
color: #fff;
font-size: 30px;
font-weight: 600;
line-height: 30px;
}
.footertextBox h3{
color: #fff;
font-size: 24px;
font-weight: 600;
margin-bottom: 15px;
}
.footertextBox p{
color: #fff;
font-size: 18px;
font-weight: 400;
}
</style>

<body>
<!--- first Page Start --->

<table class="mainTable">
	   <tr>
	   	   <td style="width:100%">
	   	   	     <table class="headerTable">
	   	   	     	       <tr>
	   	   	     	       	   <td>
						  	   	   <img src="{{ asset('frontend/assets/images/logo.png') }}" class="mainLogo">
						  	   </td>
	   	   	     	       </tr>
				   	       <tr>
				   	           <td>
						  	   	   <img src="img/banner-1.png" class="bannerImg">
						  	   </td>
						  	   <td>
						  	   	   <img src="img/banner-1.png" class="bannerImg">
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
				  	     	 	  	    	  	          <td class="clum2Bar bg-gray">{{ $project->title}}</td>
				  	     	 	  	    	  	     </tr>
				  	     	 	  	    	  	     <tr>
				  	     	 	  	    	  	     	  <td class="clum3Bar bg-primery">Community Name</td>
				  	     	 	  	    	  	          <td class="clum2Bar bg-gray">{{ $project->mainCommunity->name}}</td>
				  	     	 	  	    	  	     </tr>
				  	     	 	  	    	  	     <tr>
				  	     	 	  	    	  	     	  <td class="clum3Bar bg-primery">Product Status</td>
				  	     	 	  	    	  	          <td class="clum2Bar bg-gray">Off-plan</td>
				  	     	 	  	    	  	     </tr>
				  	     	 	  	    	  	     <tr>
				  	     	 	  	    	  	     	  <td class="clum3Bar bg-primery">Anticipated Completion Date</td>
				  	     	 	  	    	  	          <td class="clum2Bar bg-gray">{{ $handOver }}</td>
				  	     	 	  	    	  	     </tr>

				  	     	 	  	    	  </table>
				  	     	 	  	    </td>
				  	     	 	  	    <td class="cell2Td leftBdr">
				  	     	 	  	    	 <table class="clmTbl">
				  	     	 	  	    	  	     <tr class="htFix">
				  	     	 	  	    	  	     	  <td class="clum2Bar bg-primery">Gross Price</td>
				  	     	 	  	    	  	          <td class="clum3Bar bg-gray">AED 2,966,000</td>
				  	     	 	  	    	  	     </tr>
				  	     	 	  	    	  	     <tr class="htFix">
				  	     	 	  	    	  	     	  <td class="clum2Bar bg-primery">VAT</td>
				  	     	 	  	    	  	          <td class="clum3Bar bg-gray">N/A</td>
				  	     	 	  	    	  	     </tr>
				  	     	 	  	    	  	     <tr class="htFix">
				  	     	 	  	    	  	     	  <td class="clum2Bar bg-primery">Net Price</td>
				  	     	 	  	    	  	          <td class="clum3Bar bg-gray">AED 2,966,000</td>
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
				  	     	 	  	    	  	          <td class="clum3Bar bg-gray">PRKG1/SD206/XP2807F</td>
				  	     	 	  	    	  	     </tr>
				  	     	 	  	    	  	     <tr>
				  	     	 	  	    	  	     	  <td class="clum2Bar bg-primery">Unit Type</td>
				  	     	 	  	    	  	          <td class="clum3Bar bg-gray"></td>
				  	     	 	  	    	  	     </tr>
				  	     	 	  	    	  	     <tr>
				  	     	 	  	    	  	     	  <td class="clum2Bar bg-primery">Area (sqft)</td>
				  	     	 	  	    	  	          <td class="clum3Bar bg-gray">3541.68</td>
				  	     	 	  	    	  	     </tr>
				  	     	 	  	    	  	      <tr>
				  	     	 	  	    	  	     	  <td class="clum2Bar bg-primery">View Type</td>
				  	     	 	  	    	  	          <td class="clum3Bar bg-gray">Park</td>
				  	     	 	  	    	  	     </tr>
				  	     	 	  	    	  	    

				  	     	 	  	    	  </table>
				  	     	 	  	    </td>
				  	     	 	  	    <td class="cell3Td leftBdr">
				  	     	 	  	    	  <table class="clmTbl">
				  	     	 	  	    	  	     <tr>
				  	     	 	  	    	  	     	  <td class="clum3Bar bg-primery">Bedrooms</td>
				  	     	 	  	    	  	          <td class="clum2Bar bg-gray">5 BR</td>
				  	     	 	  	    	  	     </tr>
				  	     	 	  	    	  	     <tr>
				  	     	 	  	    	  	     	  <td class="clum3Bar bg-primery">Bedroom Type/Villa Type</td>
				  	     	 	  	    	  	          <td class="clum2Bar bg-gray">5 BR</td>
				  	     	 	  	    	  	     </tr>
				  	     	 	  	    	  	     <tr>
				  	     	 	  	    	  	     	  <td class="clum3Bar bg-primery">Plot Area (sqft)</td>
				  	     	 	  	    	  	          <td class="clum2Bar bg-gray">2462</td>
				  	     	 	  	    	  	     </tr>
				  	     	 	  	    	  	     <tr>
				  	     	 	  	    	  	     	  <td class="clum3Bar bg-primery">Parking</td>
				  	     	 	  	    	  	          <td class="clum2Bar bg-gray">2</td>
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
				  	     	 	  	    	  	          <td class="clum3Bar bg-gray">AED 118,640</td>
				  	     	 	  	    	  	     </tr>
				  	     	 	  	    	  	     <tr>
				  	     	 	  	    	  	     	  <td class="clum2Bar bg-primery">OQOOD Fee(C)</td>
				  	     	 	  	    	  	          <td class="clum3Bar bg-gray">AED 1,000</td>
				  	     	 	  	    	  	     </tr>
				  	     	 	  	    	  	     <tr>
				  	     	 	  	    	  	     	  <td class="clum2Bar bg-primery">Other Fee(E)</td>
				  	     	 	  	    	  	          <td class="clum3Bar bg-gray">N/A</td>
				  	     	 	  	    	  	     </tr>
				  	     	 	  	    	  	      <tr>
				  	     	 	  	    	  	     	  <td class="clum2Bar bg-primery">Total Fees to DLD(A+B+C+D+E)</td>
				  	     	 	  	    	  	          <td class="clum3Bar bg-gray">AED 121,180</td>
				  	     	 	  	    	  	     </tr> 
				  	     	 	  	    	  	    

				  	     	 	  	    	  </table>
				  	     	 	  	    </td>
				  	     	 	  	    <td class="cell3Td leftBdr">
				  	     	 	  	    	  <table class="clmTbl">
				  	     	 	  	    	  	     <tr>
				  	     	 	  	    	  	     	  <td class="clum3Bar bg-primery">Title Deed (B)</td>
				  	     	 	  	    	  	          <td class="clum2Bar bg-gray">AED 540</td>
				  	     	 	  	    	  	     </tr>
				  	     	 	  	    	  	     <tr>
				  	     	 	  	    	  	     	  <td class="clum3Bar bg-primery">DSR Fees(D)</td>
				  	     	 	  	    	  	          <td class="clum2Bar bg-gray">AED 1,000</td>
				  	     	 	  	    	  	     </tr>

				  	     	 	  	    	  </table>
				  	     	 	  	    </td>
				  	     	 	  </table>
				  	     	 </td>
				  	     </tr>
				  	     <tr>
				  	     	  <td class="textArea" style="padding-top: 20px;">
				  	     	  	   Disclaimer - These charges are approximate values. It may be subject to change by the
									authorities. Please always consult your agent on these charges. *Land Registration Fee is
									4% of the Price
				  	     	  </td>
				  	     </tr>
				  </table>


	   	   </td>
	   </tr>
</table>

<!--- First Page End --->

<!--- 2 Page Start --->
<table class="mainTable ">
	   <tr >
	   	 <td class="tblTitleTd" width="50%" style="margin-bottom: 0px;padding:0px; vertical-align:middle;" >
	   	 	  Payment Plan
	   	 </td>
	   	 <td width="50%" style="text-align: right;vertical-align:middle;">
	   	 	   <img src="img/color-logo.png" class="smLogo">
	   	 </td>
	   </tr>
	   <tr>
	   	    <td style="width:100%;padding-top: 10px;" colspan="2">
	   	    	  <table class="paymentPlanTable">
	   	    	  	     <thead>
	   	    	  	     	    <tr>
	   	    	  	     	    	  <th>
	   	    	  	     	    	  	  Description
	   	    	  	     	    	  </th>
	   	    	  	     	    	  <th>
	   	    	  	     	    	  	 Percent
	   	    	  	     	    	  </th>
	   	    	  	     	    	  <th>
	   	    	  	     	    	  	  Milestone Event
	   	    	  	     	    	  </th>
	   	    	  	     	    	  <th>
	   	    	  	     	    	  	  Amount
	   	    	  	     	    	  </th>
	   	    	  	     	    </tr>
	   	    	  	     </thead>
	   	    	  	     <tbody>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>Deposit</td>
	   	    	  	     	    	 <td>24</td>
	   	    	  	     	    	 <td>Immediate</td>
	   	    	  	     	    	 <td>AED 711,840</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>
	   	    	  	     	    <tr>
	   	    	  	     	    	 <td>1st Installment</td>
	   	    	  	     	    	 <td>1</td>
	   	    	  	     	    	 <td>Within 3 month(s) of Sale Date</td>
	   	    	  	     	    	 <td>AED 29,660</td>
	   	    	  	     	    </tr>

	   	    	  	     </tbody>
	   	    	  </table>
	   	    </td>
	   </tr>
</table>
<!--- 2 Page End --->
<br/>
<br/>
<!--- 3 Page Start --->
 
 <table class="MapTable">
 	    <tr>
 	    	  <td style="width:100%; text-align:right; padding-right: 50px;">
 	    	  	   <img src="img/color-logo.png" class="smLogo">
 	    	  </td>
 	    </tr>
 	    <tr>
 	    	  <td>
 	    	  	    <table>
 	    	  	    	   <tr>
 	    	  	    	   	     <td style="
					 	    	       width:180px; 
					 	    	       text-align:left;
					 	    	       background-color: #2879bc;
					 	    	       font-size: 22px;color: #fff;
					 	    	       font-weight: 600;
					 	    	       padding-left: 100px;">

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
 	    	  	   	      	   	   <img src="img/map-1.png" class="mapImg">
 	    	  	   	      	   </td>
 	    	  	   	      </tr>
 	    	  	   </table>
 	    	  	  
 	    	  </td>
 	    </tr>
 </table>
 <table class="MapTable">
 	    <tr>
 	    	  <td style="width:100%; text-align:right; padding-right: 50px;">
 	    	  	   <img src="img/color-logo.png" class="smLogo">
 	    	  </td>
 	    </tr>
 	    <tr>
 	    	  <td>
 	    	  	    <table>
 	    	  	    	   <tr>
 	    	  	    	   	     <td style="
					 	    	       width:180px; 
					 	    	       text-align:left;
					 	    	       background-color: #2879bc;
					 	    	       font-size: 22px;color: #fff;
					 	    	       font-weight: 600;
					 	    	       padding-left: 100px;">

					 	    	  	   Unit Plan
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
 	    	  	   	      	   	   <img src="img/map-1.png" class="mapImg">
 	    	  	   	      	   </td>
 	    	  	   	      </tr>
 	    	  	   </table>
 	    	  	  
 	    	  </td>
 	    </tr>
 </table>
 <table class="footerTable">
 	     <tr>
 	     	  <td class="pageLogoBox" style="text-align:center">
 	     	  	   <img src="{{ asset('frontend/assets/images/logo.png') }}" class="pageLogo" >
 	     	  </td>
 	     </tr>
 	     <tr>
 	     	  <td class="footertextBox">
 	     	  	   <h2>TOLL FREE 800 72 888</h2>
 	     	  	   <h3>sales@range.ae</h3>
 	     	  	   <p>http://www.range.ae/</p>
 	     	  </td>
 	     </tr>
 </table>
</body>
</html>